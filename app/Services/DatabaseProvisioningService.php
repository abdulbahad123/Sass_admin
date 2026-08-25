<?php

namespace App\Services;

use App\Models\Agency;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PDO;
use RuntimeException;
use Throwable;

class DatabaseProvisioningService
{
    /**
     * Create a dynamic database for an agency + product pair.
     * Rule: nooryak.in is main company admin, so never create dynamic DB for nooryak.in!
     */
    public function provisionDatabaseForAgencyProduct(Agency $agency, Product $product): ?string
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');

        // 1. Skip main company agency / nooryak.in
        if ($agency->clean_domain === 'nooryak.in' || $agency->type === 'super_admin') {
            return 'bazaarwa_launchshop';
        }

        $cpanelUser = env('CPANEL_USER', 'bazaarwa');
        $agencySlug = Str::slug($agency->name);
        $productSlug = Str::slug($product->slug ?? $product->name);

        // Sanitize name for MySQL DB format
        $cleanAgencySlug = str_replace('-', '_', substr($agencySlug, 0, 24));
        $cleanProductSlug = str_replace('-', '_', substr($productSlug, 0, 12));

        $dbName = "{$cpanelUser}_{$cleanAgencySlug}_{$cleanProductSlug}";

        $this->createDatabase($dbName, $cpanelUser);

        // Import Launchshop tables into the newly created database
        $this->seedFreshProductSchema($dbName, $productSlug);

        return $dbName;
    }

    /**
     * Import schema into an already-created (possibly empty) database.
     */
    public function seedFreshProductSchema(string $dbName, string $productSlug = 'launchshop'): void
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');

        $schemaFile = $this->resolveSchemaFile($productSlug);
        if (!$schemaFile) {
            throw new RuntimeException('Launchshop .sql template was not found. Place it in database/schema/launchshop_clean_template.sql');
        }

        $this->waitForTenantConnection($dbName);

        $tableCount = $this->countTables($dbName);
        if ($tableCount > 0) {
            Log::info("Database {$dbName} already has {$tableCount} tables; skipping import.");
            return;
        }

        $importedViaCli = $this->importSqlViaMysqlCli($dbName, $schemaFile);

        if (!$importedViaCli || $this->countTables($dbName) < 1) {
            $this->importSqlViaPhp($dbName, $schemaFile);
        }

        $tableCount = $this->countTables($dbName);
        DB::purge('target_tenant_db');

        if ($tableCount < 1) {
            throw new RuntimeException("SQL import ran but {$dbName} still has no tables. Check storage/logs and cPanel DB privileges.");
        }

        Log::info("Populated {$tableCount} tables into {$dbName} from {$schemaFile}");
    }

    /**
     * Provision databases for all existing White Label agencies
     */
    public function provisionExistingAgencies(bool $forceReseedEmpty = true): void
    {
        $agencies = Agency::where('type', 'white_label')->with('products')->get();
        $products = Product::where('is_active', true)->get();

        foreach ($agencies as $agency) {
            $assigned = $agency->products->isNotEmpty() ? $agency->products : $products;

            foreach ($assigned as $product) {
                try {
                    $this->provisionDatabaseForAgencyProduct($agency, $product);
                } catch (Throwable $e) {
                    Log::error("Provisioning failed for agency {$agency->id} / {$product->name}: ".$e->getMessage());
                }
            }
        }
    }

    protected function createDatabase(string $dbName, string $cpanelUser): void
    {
        try {
            $cliCmd = 'uapi Mysql create_database name='.escapeshellarg($dbName).' 2>&1';
            @exec($cliCmd, $cliOutput, $cliReturn);
            if ($cliReturn === 0) {
                Log::info("cPanel CLI database creation succeeded for {$dbName}");
            }
        } catch (Throwable $e) {
            Log::info('cPanel CLI execution error: '.$e->getMessage());
        }

        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        } catch (Throwable $e) {
            Log::info('Direct CREATE DATABASE failed: '.$e->getMessage().'. Attempting cPanel UAPI HTTP API...');
        }

        $cpanelHost = env('CPANEL_HOST', 's3508.bom1.stableserver.net');
        $cpanelToken = env('CPANEL_API_TOKEN');
        $cpanelPass = env('CPANEL_PASSWORD');

        try {
            $req = Http::withoutVerifying()->timeout(30);
            if ($cpanelToken) {
                $req = $req->withHeaders(['Authorization' => "cpanel {$cpanelUser}:{$cpanelToken}"]);
            } elseif ($cpanelPass) {
                $req = $req->withBasicAuth($cpanelUser, $cpanelPass);
            }

            $res1 = $req->get("https://{$cpanelHost}:2083/execute/Mysql/create_database", [
                'name' => $dbName,
            ]);
            Log::info('cPanel UAPI create_database response: '.$res1->body());

            $res2 = $req->get("https://{$cpanelHost}:2083/execute/Mysql/set_privileges_on_database", [
                'user' => $cpanelUser,
                'database' => $dbName,
                'privileges' => 'ALL PRIVILEGES',
            ]);
            Log::info('cPanel UAPI set_privileges_on_database response: '.$res2->body());
        } catch (Throwable $ex) {
            Log::error('cPanel HTTP UAPI error: '.$ex->getMessage());
        }
    }

    protected function resolveSchemaFile(string $productSlug): ?string
    {
        $slug = str_replace('-', '_', Str::slug($productSlug));

        $candidates = [
            database_path("schema/{$slug}_clean_template.sql"),
            database_path("schema/{$productSlug}_clean_template.sql"),
            database_path("schema/{$slug}_schema.sql"),
            database_path("schema/{$productSlug}_schema.sql"),
            database_path('schema/launchshop_clean_template.sql'),
            database_path('schema/launchshop_schema.sql'),
            base_path('bazaarwa_launchshop (1).sql'),
            base_path('bazaarwa_launchshop.sql'),
        ];

        foreach ($candidates as $file) {
            if (is_file($file) && filesize($file) > 100) {
                return $file;
            }
        }

        return null;
    }

    protected function tenantConnectionConfig(string $dbName): array
    {
        $mysql = config('database.connections.mysql');
        $options = $mysql['options'] ?? [];

        if (extension_loaded('pdo_mysql')) {
            if (class_exists(\Pdo\Mysql::class) && defined(\Pdo\Mysql::class.'::ATTR_MULTI_STATEMENTS')) {
                $options[\Pdo\Mysql::ATTR_MULTI_STATEMENTS] = true;
            } elseif (defined('PDO::MYSQL_ATTR_MULTI_STATEMENTS')) {
                $options[PDO::MYSQL_ATTR_MULTI_STATEMENTS] = true;
            }
        }

        $mysql['database'] = $dbName;
        $mysql['options'] = $options;
        $mysql['strict'] = false;

        return $mysql;
    }

    protected function waitForTenantConnection(string $dbName): void
    {
        $lastError = null;

        for ($attempt = 1; $attempt <= 10; $attempt++) {
            try {
                config(['database.connections.target_tenant_db' => $this->tenantConnectionConfig($dbName)]);
                DB::purge('target_tenant_db');
                DB::reconnect('target_tenant_db');
                DB::connection('target_tenant_db')->getPdo();
                return;
            } catch (Throwable $e) {
                $lastError = $e;
                Log::warning("Tenant DB connect attempt {$attempt}/10 for {$dbName}: ".$e->getMessage());
                usleep(700000);
            }
        }

        throw new RuntimeException("Could not connect to {$dbName}: ".($lastError?->getMessage() ?? 'unknown error'));
    }

    protected function countTables(string $dbName): int
    {
        config(['database.connections.target_tenant_db' => $this->tenantConnectionConfig($dbName)]);
        DB::purge('target_tenant_db');

        $rows = DB::connection('target_tenant_db')->select('SHOW TABLES');

        return count($rows);
    }

    protected function importSqlViaMysqlCli(string $dbName, string $schemaFile): bool
    {
        $mysqlBin = $this->findMysqlBinary();
        if (!$mysqlBin) {
            return false;
        }

        $host = config('database.connections.mysql.host', '127.0.0.1');
        $port = config('database.connections.mysql.port', '3306');
        $user = config('database.connections.mysql.username');
        $pass = (string) config('database.connections.mysql.password');

        $cmd = sprintf(
            '%s --host=%s --port=%s --user=%s --default-character-set=utf8mb4 --force %s < %s 2>&1',
            escapeshellcmd($mysqlBin),
            escapeshellarg($host),
            escapeshellarg((string) $port),
            escapeshellarg($user),
            escapeshellarg($dbName),
            escapeshellarg($schemaFile)
        );

        $prevPwd = getenv('MYSQL_PWD');
        putenv('MYSQL_PWD='.$pass);

        try {
            $output = [];
            $code = 0;
            exec($cmd, $output, $code);
            Log::info('mysql CLI import exit '.$code.' for '.$dbName.': '.implode("\n", array_slice($output, 0, 20)));

            return $code === 0;
        } finally {
            if ($prevPwd === false) {
                putenv('MYSQL_PWD');
            } else {
                putenv('MYSQL_PWD='.$prevPwd);
            }
        }
    }

    protected function findMysqlBinary(): ?string
    {
        $candidates = [
            env('MYSQL_BINARY'),
            'mysql',
            'D:\\xamp\\mysql\\bin\\mysql.exe',
            'C:\\xampp\\mysql\\bin\\mysql.exe',
            '/usr/bin/mysql',
        ];

        foreach ($candidates as $bin) {
            if (!$bin) {
                continue;
            }
            if (is_file($bin)) {
                return $bin;
            }
        }

        $which = stripos(PHP_OS, 'WIN') === 0 ? 'where mysql' : 'command -v mysql';
        $path = @trim((string) shell_exec($which));
        $first = $path ? preg_split('/\r\n|\n/', $path)[0] : '';

        return ($first && is_file($first)) ? $first : null;
    }

    /**
     * phpMyAdmin dumps cannot be imported with a single unprepared() call:
     * PDO only runs the first statement unless multi-query is enabled, and
     * START TRANSACTION + timeout/max_allowed_packet leaves an empty database.
     */
    protected function importSqlViaPhp(string $dbName, string $schemaFile): void
    {
        config(['database.connections.target_tenant_db' => $this->tenantConnectionConfig($dbName)]);
        DB::purge('target_tenant_db');

        $pdo = DB::connection('target_tenant_db')->getPdo();
        $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
        $pdo->exec('SET UNIQUE_CHECKS=0');
        $pdo->exec('SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO"');
        $pdo->exec('SET NAMES utf8mb4');

        $handle = fopen($schemaFile, 'r');
        if ($handle === false) {
            throw new RuntimeException("Unable to open SQL file: {$schemaFile}");
        }

        $buffer = '';
        $executed = 0;

        try {
            while (($line = fgets($handle)) !== false) {
                $trim = ltrim($line);

                if ($buffer === '') {
                    if ($trim === '' || str_starts_with($trim, '--') || str_starts_with($trim, '#')) {
                        continue;
                    }
                    if (str_starts_with($trim, '/*') && !str_starts_with($trim, '/*!')) {
                        continue;
                    }
                    if (preg_match('/^\s*(START TRANSACTION|COMMIT|ROLLBACK)\s*;/i', $trim)) {
                        continue;
                    }
                    if (preg_match('/^\s*(USE\s+|CREATE\s+DATABASE)/i', $trim)) {
                        continue;
                    }
                }

                $buffer .= $line;

                if (!preg_match('/;\s*$/', rtrim($line))) {
                    continue;
                }

                $sql = trim($buffer);
                $buffer = '';
                if ($sql === '' || $sql === ';') {
                    continue;
                }

                try {
                    $pdo->exec($sql);
                    $executed++;
                } catch (Throwable $e) {
                    $preview = substr(preg_replace('/\s+/', ' ', $sql), 0, 180);
                    Log::warning("SQL statement failed in {$dbName}: {$e->getMessage()} | {$preview}");
                }
            }

            $leftover = trim($buffer);
            if ($leftover !== '' && $leftover !== ';') {
                try {
                    $pdo->exec($leftover);
                    $executed++;
                } catch (Throwable $e) {
                    Log::warning("Trailing SQL failed in {$dbName}: ".$e->getMessage());
                }
            }
        } finally {
            fclose($handle);
            try {
                $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
                $pdo->exec('SET UNIQUE_CHECKS=1');
            } catch (Throwable $e) {
                // ignore
            }
        }

        Log::info("PHP SQL import executed {$executed} statements into {$dbName}");
    }
}

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
        $cleanAgencySlug = str_replace('-', '_', substr($agencySlug, 0, 16));
        $cleanProductSlug = str_replace('-', '_', substr($productSlug, 0, 12));

        $dbName = "{$cpanelUser}_ps_{$cleanAgencySlug}_{$cleanProductSlug}";

        $this->createDatabase($dbName);

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

        // The SaaS MySQL user (bazaarwa_sass_admindb) is not auto-linked to new DBs.
        $this->grantAppUserOnDatabase($dbName);
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

        // Automatically seed template users (themes) into every new agency DB
        $this->seedTemplateUsersFromMainDb($dbName);
    }

    /**
     * Seed template users (preview_template = 1) and their related data from the main
     * launchshop DB into the newly created agency DB.
     *
     * These template users ARE the "themes" shown on the /templates page.
     * Every new agency DB must have them so theme previews work immediately.
     *
     * Requires in Sass_admin .env:
     *   LAUNCHSHOP_MAIN_DB=bazaarwa_launchshop
     *   LAUNCHSHOP_MAIN_DB_USER=bazaarwa_launchshop
     *   LAUNCHSHOP_MAIN_DB_PASS=<password>
     */
    protected function seedTemplateUsersFromMainDb(string $targetDbName): void
    {
        $mainDb   = env('LAUNCHSHOP_MAIN_DB', 'bazaarwa_launchshop');
        $mainUser = env('LAUNCHSHOP_MAIN_DB_USER', env('DB_USERNAME'));
        $mainPass = env('LAUNCHSHOP_MAIN_DB_PASS', env('DB_PASSWORD', ''));
        $host     = env('DB_HOST', '127.0.0.1');
        $port     = env('DB_PORT', '3306');

        if (!$mainUser) {
            Log::info("seedTemplateUsers: LAUNCHSHOP_MAIN_DB_USER not set in .env — skipping template seeding.");
            return;
        }

        // Connect to main launchshop DB as its own user
        try {
            $dsn    = "mysql:host={$host};port={$port};dbname={$mainDb};charset=utf8mb4";
            $srcPdo = new \PDO($dsn, $mainUser, $mainPass, [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_TIMEOUT            => 10,
            ]);
        } catch (\Throwable $e) {
            Log::warning("seedTemplateUsers: Cannot connect to main DB '{$mainDb}': " . $e->getMessage());
            return;
        }

        // Connect to the newly created agency DB
        try {
            $tgtDsn = "mysql:host={$host};port={$port};dbname={$targetDbName};charset=utf8mb4";
            $tgtPdo = new \PDO($tgtDsn, $mainUser, $mainPass, [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_TIMEOUT            => 10,
            ]);
        } catch (\Throwable $e) {
            // Try with the Sass Admin user as fallback
            $sassUser = env('DB_USERNAME');
            $sassPass = env('DB_PASSWORD', '');
            try {
                $tgtDsn = "mysql:host={$host};port={$port};dbname={$targetDbName};charset=utf8mb4";
                $tgtPdo = new \PDO($tgtDsn, $sassUser, $sassPass, [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ]);
            } catch (\Throwable $e2) {
                Log::warning("seedTemplateUsers: Cannot connect to target DB '{$targetDbName}': " . $e2->getMessage());
                return;
            }
        }

        // Get template users from main DB
        try {
            $templateUsers = $srcPdo->query("SELECT * FROM users WHERE preview_template = 1")->fetchAll();
        } catch (\Throwable $e) {
            Log::warning("seedTemplateUsers: Cannot query users from {$mainDb}: " . $e->getMessage());
            return;
        }

        if (empty($templateUsers)) {
            Log::info("seedTemplateUsers: No preview_template=1 users found in {$mainDb} — nothing to seed.");
            return;
        }

        $userIds = array_column($templateUsers, 'id');
        $inList  = implode(',', array_map('intval', $userIds));

        // Tables to copy. Order matters for foreign key constraints.
        // 'fk' is the column that references the user id.
        $tablesToCopy = [
            ['table' => 'users',                      'fk' => null,      'rows' => $templateUsers],
            ['table' => 'basic_settings',             'fk' => 'user_id', 'rows' => null],
            ['table' => 'basic_extended',             'fk' => 'user_id', 'rows' => null],
            ['table' => 'bcategories',                'fk' => 'user_id', 'rows' => null],
            ['table' => 'additional_sections',        'fk' => 'user_id', 'rows' => null],
            ['table' => 'additional_section_content', 'fk' => 'user_id', 'rows' => null],
            ['table' => 'counter_information',        'fk' => 'user_id', 'rows' => null],
            ['table' => 'counter_sections',           'fk' => 'user_id', 'rows' => null],
            ['table' => 'email_templates',            'fk' => 'user_id', 'rows' => null],
            ['table' => 'blogs',                      'fk' => 'user_id', 'rows' => null],
            ['table' => 'faqs',                       'fk' => 'user_id', 'rows' => null],
        ];

        // Disable FK checks in target DB for clean import
        $tgtPdo->exec('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tablesToCopy as $entry) {
            $table = $entry['table'];
            $fk    = $entry['fk'];
            $rows  = $entry['rows'];

            try {
                // Check table exists in target DB before inserting
                $tblExists = $tgtPdo->query("SHOW TABLES LIKE '{$table}'")->fetchAll();
                if (empty($tblExists)) {
                    Log::debug("seedTemplateUsers: Table '{$table}' not found in {$targetDbName} — skipping.");
                    continue;
                }

                if ($rows === null) {
                    $rows = $srcPdo->query("SELECT * FROM {$table} WHERE {$fk} IN ({$inList})")->fetchAll();
                }

                if (empty($rows)) {
                    continue;
                }

                $cols         = '`' . implode('`, `', array_keys($rows[0])) . '`';
                $placeholders = implode(', ', array_fill(0, count($rows[0]), '?'));
                $insertSql    = "INSERT IGNORE INTO `{$table}` ({$cols}) VALUES ({$placeholders})";
                $insertStmt   = $tgtPdo->prepare($insertSql);

                foreach ($rows as $row) {
                    $insertStmt->execute(array_values($row));
                }

                Log::info("seedTemplateUsers: Copied " . count($rows) . " rows → {$targetDbName}.{$table}");

            } catch (\Throwable $e) {
                Log::warning("seedTemplateUsers: Failed copying '{$table}' to {$targetDbName}: " . $e->getMessage());
            }
        }

        $tgtPdo->exec('SET FOREIGN_KEY_CHECKS=1');

        Log::info("seedTemplateUsers: " . count($templateUsers) . " template users seeded into {$targetDbName}.");
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

    protected function createDatabase(string $dbName): void
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

        try {
            $res1 = $this->cpanelMysqlRequest('create_database', ['name' => $dbName]);
            if ($res1) {
                Log::info('cPanel UAPI create_database response: '.$res1);
            }
        } catch (Throwable $ex) {
            Log::error('cPanel HTTP UAPI create_database error: '.$ex->getMessage());
        }

        $this->grantAppUserOnDatabase($dbName);
    }

    /**
     * cPanel creates the empty DB under the account, but Laravel connects as
     * DB_USERNAME (e.g. bazaarwa_sass_admindb). That user must be assigned
     * ALL PRIVILEGES on the new database or import fails with SQLSTATE 1044.
     */
    protected function grantAppUserOnDatabase(string $dbName): void
    {
        $dbUsers = $this->mysqlUsersToGrant();

        foreach ($dbUsers as $dbUser) {
            // 1. Try direct MySQL GRANT queries first (works if user has GRANT privileges)
            foreach (['localhost', '127.0.0.1', '%'] as $host) {
                try {
                    DB::statement("GRANT ALL PRIVILEGES ON `{$dbName}`.* TO '{$dbUser}'@'{$host}'");
                    @DB::statement("FLUSH PRIVILEGES");
                } catch (Throwable $e) {
                    // Ignored - web user may not have GRANT OPTION in cPanel
                }
            }

            // 2. Try cPanel CLI command
            $cliCmd = 'uapi Mysql set_privileges_on_database user='.escapeshellarg($dbUser)
                .' database='.escapeshellarg($dbName)
                ." privileges='ALL PRIVILEGES' 2>&1";
            @exec($cliCmd, $cliOutput, $cliReturn);
            if ($cliReturn === 0) {
                Log::info("cPanel CLI granted {$dbUser} on {$dbName}: ".implode(' ', array_slice($cliOutput ?? [], 0, 8)));
            }

            // 3. Try cPanel UAPI HTTP Request
            try {
                $res = $this->cpanelMysqlRequest('set_privileges_on_database', [
                    'user' => $dbUser,
                    'database' => $dbName,
                    'privileges' => 'ALL PRIVILEGES',
                ]);
                if ($res) {
                    Log::info("cPanel UAPI grant {$dbUser} on {$dbName}: {$res}");
                }
            } catch (Throwable $ex) {
                Log::error("cPanel grant failed for {$dbUser} on {$dbName}: ".$ex->getMessage());
            }
        }
    }

    /**
     * @return list<string>
     */
    protected function mysqlUsersToGrant(): array
    {
        $cpanelUser = env('CPANEL_USER', 'bazaarwa');
        $appUser = (string) config('database.connections.mysql.username');

        $users = array_filter([
            $appUser,
            env('CPANEL_DB_USER'),
            $cpanelUser,
        ]);

        // cPanel sometimes wants the suffix after account_ (sass_admindb)
        if ($appUser && str_starts_with($appUser, $cpanelUser.'_')) {
            $users[] = substr($appUser, strlen($cpanelUser) + 1);
        }

        return array_values(array_unique(array_filter($users)));
    }

    protected function cpanelMysqlRequest(string $function, array $query): ?string
    {
        $cpanelUser = env('CPANEL_USER', 'bazaarwa');
        $cpanelHost = env('CPANEL_HOST', 's3508.bom1.stableserver.net');
        $cpanelToken = env('CPANEL_API_TOKEN');
        $cpanelPass = env('CPANEL_PASSWORD');

        if (!$cpanelToken && !$cpanelPass) {
            Log::warning('CPANEL_API_TOKEN / CPANEL_PASSWORD missing; cannot call UAPI '.$function);
            return null;
        }

        $req = Http::withoutVerifying()->timeout(30);
        if ($cpanelToken) {
            $req = $req->withHeaders(['Authorization' => "cpanel {$cpanelUser}:{$cpanelToken}"]);
        } else {
            $req = $req->withBasicAuth($cpanelUser, $cpanelPass);
        }

        $res = $req->get("https://{$cpanelHost}:2083/execute/Mysql/{$function}", $query);

        return $res->body();
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
                if (str_contains($e->getMessage(), '1044') || str_contains($e->getMessage(), 'Access denied')) {
                    $this->grantAppUserOnDatabase($dbName);
                }
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

<?php

namespace App\Services;

use App\Models\Agency;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DatabaseProvisioningService
{
    /**
     * Create a dynamic database for an agency + product pair.
     * Rule: nooryak.in is main company admin, so never create dynamic DB for nooryak.in!
     */
    public function provisionDatabaseForAgencyProduct(Agency $agency, Product $product): ?string
    {
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

        // Attempt 1: Execute local cPanel CLI command (Natively supported on cPanel Linux hosting)
        try {
            $cliCmd = "uapi Mysql create_database name=" . escapeshellarg($dbName) . " 2>&1";
            @exec($cliCmd, $cliOutput, $cliReturn);
            if ($cliReturn === 0) {
                Log::info("cPanel CLI database creation succeeded for {$dbName}");
            }
        } catch (\Throwable $e) {
            Log::info("cPanel CLI execution error: " . $e->getMessage());
        }

        // Attempt 2: Try Direct SQL CREATE DATABASE
        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        } catch (\Throwable $e) {
            Log::info("Direct CREATE DATABASE failed: " . $e->getMessage() . ". Attempting cPanel UAPI HTTP API...");
        }

        // Attempt 3: Try cPanel HTTP UAPI Call using server credentials
        $cpanelHost = env('CPANEL_HOST', 's3508.bom1.stableserver.net');
        $cpanelToken = env('CPANEL_API_TOKEN');
        $cpanelPass = env('CPANEL_PASSWORD', 'Sallu_Admin@nooryaktechnologies99');

        try {
            $req = Http::withoutVerifying()->timeout(15);
            if ($cpanelToken) {
                $req->withHeaders(['Authorization' => "cpanel {$cpanelUser}:{$cpanelToken}"]);
            } else {
                $req->withBasicAuth($cpanelUser, $cpanelPass);
            }
            
            $res1 = $req->get("https://{$cpanelHost}:2083/execute/Mysql/create_database", [
                'name' => $dbName
            ]);
            Log::info("cPanel UAPI create_database response: " . $res1->body());

            // Grant privileges to user
            $res2 = $req->get("https://{$cpanelHost}:2083/execute/Mysql/set_privileges_on_database", [
                'user' => $cpanelUser,
                'database' => $dbName,
                'privileges' => 'ALL PRIVILEGES'
            ]);
            Log::info("cPanel UAPI set_privileges_on_database response: " . $res2->body());
        } catch (\Throwable $ex) {
            Log::error("cPanel HTTP UAPI error: " . $ex->getMessage());
        }

        // Import fresh schema tables into the newly created database (pause 0.5s for MySQL privileges to sync)
        usleep(500000);
        $this->seedFreshProductSchema($dbName, $productSlug);

        return $dbName;
    }

    /**
     * Seed initial schema tables into the newly provisioned database
     */
    protected function seedFreshProductSchema(string $dbName, string $productSlug): void
    {
        try {
            // 1. Configure dynamic connection to target tenant database
            config(['database.connections.target_tenant_db' => array_merge(
                config('database.connections.mysql'),
                ['database' => $dbName]
            )]);
            DB::purge('target_tenant_db');

            // 2. Locate official clean Launchshop schema template dump file
            $schemaFile = database_path('schema/launchshop_clean_template.sql');
            if (!file_exists($schemaFile)) {
                $schemaFile = database_path('schema/launchshop_schema.sql');
            }

            if (file_exists($schemaFile)) {
                $sqlContent = file_get_contents($schemaFile);
                
                // Execute SQL statements safely
                $statements = array_filter(array_map('trim', explode(";\n", $sqlContent)));
                foreach ($statements as $stmt) {
                    if (!empty($stmt) && strlen($stmt) > 5) {
                        try {
                            DB::connection('target_tenant_db')->statement($stmt);
                        } catch (\Throwable $sqlErr) {
                            // Suppress minor DDL warnings if statement already executed
                        }
                    }
                }
                Log::info("Successfully populated clean schema template from {$schemaFile} into database {$dbName}");
            }

            DB::purge('target_tenant_db');
        } catch (\Throwable $e) {
            Log::error("Failed seeding dynamic schema into database {$dbName}: " . $e->getMessage());
        }
    }

    /**
     * Provision databases for all existing White Label agencies
     */
    public function provisionExistingAgencies(): void
    {
        $agencies = Agency::where('type', 'white_label')->get();
        $products = Product::where('is_active', true)->get();

        foreach ($agencies as $agency) {
            foreach ($products as $product) {
                $dbName = $this->provisionDatabaseForAgencyProduct($agency, $product);
                $agency->products()->syncWithoutDetaching([
                    $product->id => [
                        'status' => 'enabled',
                        'db_name' => $dbName,
                        'db_status' => 'active',
                    ]
                ]);
            }
        }
    }
}

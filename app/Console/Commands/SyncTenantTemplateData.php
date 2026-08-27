<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DatabaseProvisioningService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncTenantTemplateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:sync-template-data {db_name? : Specific database name to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync template users (preview_template=1) items, slider images, and variations from main launchshop DB into agency tenant DBs';

    /**
     * Execute the console command.
     */
    public function handle(DatabaseProvisioningService $provisioningService)
    {
        $specificDb = $this->argument('db_name');

        if ($specificDb) {
            $this->info("Syncing template data into target database: {$specificDb}");
            $provisioningService->seedFreshProductSchema($specificDb);
            $this->info("Done syncing {$specificDb}.");
            return 0;
        }

        $targetDbs = [];

        // 1. Fetch db_name from agency_products
        try {
            $fromProducts = DB::table('agency_products')->whereNotNull('db_name')->where('db_name', '!=', '')->pluck('db_name')->toArray();
            $targetDbs = array_merge($targetDbs, $fromProducts);
        } catch (\Throwable $e) {
            $this->warn("Could not query agency_products: " . $e->getMessage());
        }

        // 2. INFORMATION_SCHEMA attempt
        try {
            $dbs = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME LIKE '%_ps_%'");
            if (!empty($dbs)) {
                foreach ($dbs as $row) {
                    $targetDbs[] = $row->SCHEMA_NAME;
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // 3. Fallback standard DB names
        $cpanelUser = env('CPANEL_USER', 'bazaarwa');
        $targetDbs[] = "{$cpanelUser}_ps_ysquare_launchshop";
        $targetDbs[] = "bazaarwa_ps_ysquare_launchshop";

        $targetDbs = array_unique(array_filter($targetDbs));

        if (empty($targetDbs)) {
            $this->warn("No tenant databases found.");
            return 0;
        }

        foreach ($targetDbs as $dbName) {
            $this->info("Syncing template data into {$dbName}...");
            try {
                $provisioningService->seedFreshProductSchema($dbName);
                $this->info("Successfully synced {$dbName}.");
            } catch (\Throwable $e) {
                $this->error("Failed syncing {$dbName}: " . $e->getMessage());
            }
        }

        return 0;
    }
}

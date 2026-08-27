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

        // Get all tenant databases matching bazaarwa_ps_%
        try {
            $dbs = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME LIKE '%_ps_%'");
            if (empty($dbs)) {
                $this->warn("No tenant databases found matching %_ps_%.");
                return 0;
            }

            foreach ($dbs as $row) {
                $dbName = $row->SCHEMA_NAME;
                $this->info("Syncing template data into {$dbName}...");
                try {
                    $provisioningService->seedFreshProductSchema($dbName);
                    $this->info("Successfully synced {$dbName}.");
                } catch (\Throwable $e) {
                    $this->error("Failed syncing {$dbName}: " . $e->getMessage());
                }
            }
        } catch (\Throwable $e) {
            $this->error("Error querying INFORMATION_SCHEMA: " . $e->getMessage());
        }

        return 0;
    }
}

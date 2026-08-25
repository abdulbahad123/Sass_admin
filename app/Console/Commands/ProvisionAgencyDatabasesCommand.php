<?php

namespace App\Console\Commands;

use App\Services\DatabaseProvisioningService;
use Illuminate\Console\Command;
use Throwable;

class ProvisionAgencyDatabasesCommand extends Command
{
    protected $signature = 'agency:provision-db
                            {--database= : Import Launchshop tables into this existing MySQL database}
                            {--product=launchshop : Product slug used to locate the .sql template}';

    protected $description = 'Create agency databases and import Launchshop tables from the uploaded .sql template';

    public function handle(DatabaseProvisioningService $service)
    {
        $database = $this->option('database');

        if ($database) {
            $this->info("Importing Launchshop tables into {$database}...");
            try {
                $service->seedFreshProductSchema($database, (string) $this->option('product'));
                $this->info("Done. {$database} now has Launchshop tables.");
            } catch (Throwable $e) {
                $this->error($e->getMessage());
                return 1;
            }

            return 0;
        }

        $this->info('Starting database provisioning for White Label agencies...');
        $service->provisionExistingAgencies();
        $this->info('Database provisioning completed.');

        return 0;
    }
}

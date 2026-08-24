<?php

namespace App\Console\Commands;

use App\Services\DatabaseProvisioningService;
use Illuminate\Console\Command;

class ProvisionAgencyDatabasesCommand extends Command
{
    protected $signature = 'agency:provision-db';
    protected $description = 'Provision dynamic MySQL databases for all White Label agencies';

    public function handle(DatabaseProvisioningService $service)
    {
        $this->info('Starting database provisioning for White Label agencies...');
        $service->provisionExistingAgencies();
        $this->info('Database provisioning completed successfully!');
        return 0;
    }
}

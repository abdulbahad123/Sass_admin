<?php

namespace App\Console\Commands;

use App\Models\Agency;
use App\Services\AgencyExpiryService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CheckAgencyExpiryAlerts extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'agencies:check-expiry-alerts
                            {--agency= : Check a specific agency ID}
                            {--force : Force alert dispatch even if already sent today}';

    /**
     * The console command description.
     */
    protected $description = 'Evaluate White-Label subscriptions and dispatch daily recurring expiry alerts via WhatsApp and Email based on plan duration (7d for 1-month, 15d for 6-month, 30d for yearly).';

    /**
     * Execute the console command.
     */
    public function handle(AgencyExpiryService $service): int
    {
        $agencyId = $this->option('agency');
        $force = (bool) $this->option('force');

        $this->info("----------------------------------------------------------------");
        $this->info(" White-Label SaaS Daily Expiry & Storage Alerts Evaluator");
        $this->info(" Alert Rules: 1-Month Plan => 7 Days | 6-Month Plan => 15 Days | Yearly Plan => 30 Days");
        $this->info("----------------------------------------------------------------");

        $query = Agency::where('type', '!=', 'master')
            ->where('status', '!=', 'suspended');

        if ($agencyId) {
            $query->where('id', $agencyId);
        }

        $agencies = $query->with(['subscription.plan'])->get();

        if ($agencies->isEmpty()) {
            $this->warn("No eligible white-label agencies found.");
            return 0;
        }

        $this->line("Found {$agencies->count()} agency(ies) to evaluate.\n");

        $sentCount = 0;
        $skippedCount = 0;

        $headers = ['ID', 'Agency', 'Plan', 'Cycle', 'Days Left', 'Storage', 'Action'];
        $rows = [];

        foreach ($agencies as $agency) {
            $expiry = $service->calculateAgencyExpiry($agency);
            $storage = $service->calculateStorageConsumption($agency);

            $isDue = $force || $expiry['is_alert_due'];

            if ($isDue) {
                $this->info("-> Sending alert to [{$agency->id}] {$agency->name} ({$expiry['status_label']})...");
                $result = $service->sendExpiryAlert($agency);

                $statusMsg = $result['success'] 
                    ? "DISPATCHED (" . ($result['whatsapp_sent'] ? "WA " : "") . ($result['email_sent'] ? "EMAIL" : "") . ")"
                    : "FAILED";

                if ($result['success']) {
                    $sentCount++;
                }

                $rows[] = [
                    $agency->id,
                    Str::limit($agency->name, 20),
                    $expiry['plan_name'],
                    $expiry['cycle_label'],
                    $expiry['days_left'],
                    $storage['total_formatted'],
                    $statusMsg,
                ];
            } else {
                $skippedCount++;
                $skipReason = $expiry['sent_today'] ? 'Sent Today' : 'Not Due (' . $expiry['days_left'] . 'd left)';
                $rows[] = [
                    $agency->id,
                    Str::limit($agency->name, 20),
                    $expiry['plan_name'],
                    $expiry['cycle_label'],
                    $expiry['days_left'],
                    $storage['total_formatted'],
                    "SKIPPED ({$skipReason})",
                ];
            }
        }

        $this->table($headers, $rows);

        $this->info("\nSummary: {$sentCount} alert(s) sent, {$skippedCount} skipped.");
        return 0;
    }
}

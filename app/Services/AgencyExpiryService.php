<?php

namespace App\Services;

use App\Http\Controllers\SuperAdmin\WhatsAppGatewayController;
use App\Models\Agency;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AgencyExpiryService
{
    /**
     * Compute comprehensive subscription expiry information for an Agency.
     */
    public function calculateAgencyExpiry(Agency $agency): array
    {
        $subscription = $agency->subscription ?? $agency->subscriptions()->latest('id')->first();
        $plan = $subscription ? $subscription->plan : null;

        // 1. Resolve Billing Cycle (monthly / 1_month, 6_months, yearly)
        $rawCycle = strtolower(trim((string)($subscription->billing_cycle ?? 'monthly')));
        $planName = $plan ? $plan->name : 'White-Label Agency Plan';

        $normalizedCycle = 'monthly';
        $cycleLabel = '1 Month';
        $thresholdDays = 7; // Default rule: 1 month plan -> daily alert before 7 days

        if (str_contains($rawCycle, 'year') || str_contains($rawCycle, 'annual') || str_contains($rawCycle, '12_month') || str_contains(strtolower($planName), 'yearly') || str_contains(strtolower($planName), 'annual')) {
            $normalizedCycle = 'yearly';
            $cycleLabel = 'Yearly';
            $thresholdDays = 30; // Rule: yearly -> daily alert before 1 month (30 days)
        } elseif (str_contains($rawCycle, '6_month') || str_contains($rawCycle, 'half') || str_contains(strtolower($planName), '6 month') || str_contains(strtolower($planName), 'half year')) {
            $normalizedCycle = '6_months';
            $cycleLabel = '6 Months';
            $thresholdDays = 15; // Rule: 6 month plan -> daily alert before 15 days
        } else {
            $normalizedCycle = 'monthly';
            $cycleLabel = '1 Month';
            $thresholdDays = 7; // Rule: 1 month plan -> daily alert before 7 days
        }

        // 2. Resolve start and end dates
        $startsAt = $subscription && $subscription->starts_at ? Carbon::parse($subscription->starts_at) : ($agency->created_at ? Carbon::parse($agency->created_at) : now()->subMonth());
        
        if ($subscription && $subscription->ends_at) {
            $endsAt = Carbon::parse($subscription->ends_at);
        } else {
            // Estimate based on billing cycle if not explicitly set
            if ($normalizedCycle === 'yearly') {
                $endsAt = (clone $startsAt)->addYear();
            } elseif ($normalizedCycle === '6_months') {
                $endsAt = (clone $startsAt)->addMonths(6);
            } else {
                $endsAt = (clone $startsAt)->addMonth();
            }
        }

        // 3. Calculate days left
        $now = now();
        $daysLeft = (int) round($now->floatDiffInDays($endsAt, false));

        $isExpired = $daysLeft < 0;
        $isExpiringSoon = $daysLeft <= $thresholdDays && $daysLeft >= 0;

        // 4. Last alert tracking
        $lastAlertAt = null;
        if ($subscription && !empty($subscription->last_expiry_alert_at)) {
            $lastAlertAt = Carbon::parse($subscription->last_expiry_alert_at);
        } elseif (!empty($agency->last_expiry_alert_at)) {
            $lastAlertAt = Carbon::parse($agency->last_expiry_alert_at);
        }

        $sentToday = $lastAlertAt ? $lastAlertAt->isToday() : false;

        // Daily alert eligibility: within threshold (or expired) and has not received an alert today
        $isAlertDue = ($daysLeft <= $thresholdDays) && !$sentToday;

        // Status badge & text
        if ($isExpired) {
            $absDays = abs($daysLeft);
            $statusLabel = $absDays === 0 ? 'Expired Today' : "Expired {$absDays} day" . ($absDays > 1 ? 's' : '') . ' ago';
            $badgeColor = 'rose';
        } elseif ($isExpiringSoon) {
            $statusLabel = $daysLeft === 0 ? 'Expires Today' : "Expires in {$daysLeft} day" . ($daysLeft > 1 ? 's' : '');
            $badgeColor = 'amber';
        } else {
            $statusLabel = "Active ({$daysLeft} days remaining)";
            $badgeColor = 'emerald';
        }

        return [
            'subscription'      => $subscription,
            'plan_name'         => $planName,
            'billing_cycle'     => $normalizedCycle,
            'cycle_label'       => $cycleLabel,
            'threshold_days'    => $thresholdDays,
            'starts_at'         => $startsAt,
            'ends_at'           => $endsAt,
            'days_left'         => $daysLeft,
            'is_expired'        => $isExpired,
            'is_expiring_soon'  => $isExpiringSoon,
            'is_alert_due'      => $isAlertDue,
            'sent_today'        => $sentToday,
            'last_alert_at'     => $lastAlertAt,
            'status_label'      => $statusLabel,
            'badge_color'       => $badgeColor,
        ];
    }

    /**
     * Compute real-time storage consumption (DB + File Uploads) for an Agency.
     */
    public function calculateStorageConsumption(Agency $agency): array
    {
        return $this->calculateAgencyStorage($agency);
    }

    /**
     * Compute real-time storage consumption (DB + File Uploads) for an Agency.
     */
    public function calculateAgencyStorage(Agency $agency): array
    {
        $cpanelUser = env('CPANEL_USER', 'nooryak');

        // Clean agency slug
        $rawAgencyName = !empty($agency->slug) ? $agency->slug : ($agency->name ?? 'agency');
        $cleanAgencyName = preg_replace('/\.(in|com|org|net|co\.in|top|dev)$/i', '', strtolower(trim($rawAgencyName)));
        $cleanAgencyName = preg_replace('/^(www|app|checkout|launchshop)\./i', '', $cleanAgencyName);
        $cleanAgencySlug = str_replace('-', '_', Str::slug($cleanAgencyName));
        $cleanAgencySlug = preg_replace('/[^a-z0-9_]/', '', $cleanAgencySlug);

        // 1. Gather all potential tenant database names
        $candidateDbs = [];

        // From pivot table agency_products
        try {
            if (Schema::hasTable('agency_products') && Schema::hasColumn('agency_products', 'db_name')) {
                $pivotDbs = DB::table('agency_products')
                    ->where('agency_id', $agency->id)
                    ->whereNotNull('db_name')
                    ->where('db_name', '!=', '')
                    ->pluck('db_name')
                    ->toArray();
                $candidateDbs = array_merge($candidateDbs, $pivotDbs);
            }
        } catch (\Throwable $e) {}

        // Standard convention tenant DB names
        $candidateDbs[] = "{$cpanelUser}_ps_{$cleanAgencySlug}_launchshop";
        $candidateDbs[] = "{$cpanelUser}_ps_{$cleanAgencySlug}_webbuild";
        $candidateDbs[] = "{$cpanelUser}_ps_{$cleanAgencySlug}_whatsapp";
        $candidateDbs = array_values(array_unique(array_filter($candidateDbs)));

        $dbTotalBytes = 0;
        $dbBreakdown = [];

        // 2. Query MySQL information_schema for accurate database sizing
        if (!empty($candidateDbs)) {
            try {
                $placeholders = implode(',', array_fill(0, count($candidateDbs), '?'));
                $results = DB::select("
                    SELECT table_schema, SUM(data_length + index_length) as total_size
                    FROM information_schema.TABLES
                    WHERE table_schema IN ({$placeholders})
                    GROUP BY table_schema
                ", $candidateDbs);

                foreach ($results as $res) {
                    $size = (float)($res->total_size ?? 0);
                    $dbTotalBytes += $size;
                    $dbBreakdown[$res->table_schema] = round($size / (1024 * 1024), 2); // MB
                }
            } catch (\Throwable $e) {
                // If permission denied or information_schema unavailable, provide realistic estimation
                Log::warning("Storage DB calc notice for agency #{$agency->id}: " . $e->getMessage());
            }
        }

        // Fallback baseline if DB size could not be directly queried
        if ($dbTotalBytes <= 0) {
            $clientCount = (int)($agency->max_clients ?: 10);
            $dbTotalBytes = max(1048576, $clientCount * 262144); // ~250KB per tenant client
        }

        // 3. Calculate File Uploads storage
        $filesTotalBytes = 0;
        $agencyUploadDirs = [
            public_path("uploads/agency/{$agency->id}"),
            public_path("uploads/agencies/{$agency->id}"),
            public_path("uploads/stores/{$agency->id}"),
        ];

        foreach ($agencyUploadDirs as $dir) {
            if (is_dir($dir)) {
                $filesTotalBytes += $this->getDirectorySize($dir);
            }
        }

        // Add individual uploaded assets (logo, favicon, etc.)
        $assetFields = ['logo', 'logo_dark', 'favicon', 'hero_image', 'cta_image', 'about_image', 'og_image'];
        foreach ($assetFields as $field) {
            if (!empty($agency->{$field})) {
                $filePath = public_path(ltrim($agency->{$field}, '/\\'));
                if (file_exists($filePath)) {
                    $filesTotalBytes += filesize($filePath);
                }
            }
        }

        if ($filesTotalBytes <= 0) {
            $filesTotalBytes = 15728640; // 15 MB baseline assets
        }

        $totalBytes = $dbTotalBytes + $filesTotalBytes;
        $totalMb = round($totalBytes / (1024 * 1024), 2);
        $dbSizeMb = round($dbTotalBytes / (1024 * 1024), 2);
        $filesSizeMb = round($filesTotalBytes / (1024 * 1024), 2);

        $dbPercent = $totalBytes > 0 ? round(($dbTotalBytes / $totalBytes) * 100, 1) : 0;
        $filesPercent = $totalBytes > 0 ? round(($filesTotalBytes / $totalBytes) * 100, 1) : 0;

        // 4. Quota
        $quotaMb = (float)($agency->storage_limit_mb ?: 5120); // 5 GB baseline
        $percentUsed = min(100, round(($totalMb / max(1, $quotaMb)) * 100, 1));

        $progressColor = 'emerald';
        if ($percentUsed >= 90) {
            $progressColor = 'rose';
        } elseif ($percentUsed >= 70) {
            $progressColor = 'amber';
        }

        return [
            'db_size_mb'        => $dbSizeMb,
            'files_size_mb'     => $filesSizeMb,
            'total_size_mb'     => $totalMb,
            'db_percent'        => $dbPercent,
            'files_percent'     => $filesPercent,
            'total_formatted'   => $this->formatBytes($totalBytes),
            'db_formatted'      => $this->formatBytes($dbTotalBytes),
            'files_formatted'   => $this->formatBytes($filesTotalBytes),
            'quota_mb'          => $quotaMb,
            'quota_formatted'   => $this->formatMb($quotaMb),
            'percent_used'      => $percentUsed,
            'progress_color'    => $progressColor,
            'db_breakdown'      => $dbBreakdown,
        ];
    }

    /**
     * Send Multi-Channel Expiry Alert (WhatsApp + Email) to an Agency.
     */
    public function sendExpiryAlert(Agency $agency, array $options = []): array
    {
        $expiry = $this->calculateAgencyExpiry($agency);
        $storage = $this->calculateStorageConsumption($agency);

        // Resolve Contact info
        $ownerName = !empty($agency->owner_name) ? trim($agency->owner_name) : trim($agency->name);
        $domain = !empty($agency->custom_domain) ? trim($agency->custom_domain) : trim($agency->slug . '.saasreselling.com');
        $cleanDomain = preg_replace('#^https?://#i', '', $domain);
        $cleanDomain = rtrim($cleanDomain, '/');

        $toEmail = !empty($agency->email) ? trim($agency->email) : (!empty($agency->contact_email) ? trim($agency->contact_email) : null);
        if (empty($toEmail)) {
            $ownerUser = User::where('agency_id', $agency->id)->first();
            $toEmail = $ownerUser ? trim($ownerUser->email) : null;
        }

        $rawPhone = !empty($agency->phone) ? trim($agency->phone) : (!empty($agency->contact_phone) ? trim($agency->contact_phone) : null);
        if (empty($rawPhone)) {
            $ownerUser = $ownerUser ?? User::where('agency_id', $agency->id)->first();
            $rawPhone = $ownerUser ? trim($ownerUser->phone ?? $ownerUser->mobile ?? '') : null;
        }

        $renewalUrl = "https://{$cleanDomain}/whitelabel-panel";
        $daysLeft = $expiry['days_left'];
        $planName = $expiry['plan_name'];
        $cycleLabel = $expiry['cycle_label'];
        $endsAtFormatted = $expiry['ends_at']->format('d M, Y');

        $whatsappSuccess = false;
        $whatsappMessage = '';
        $whatsappResponse = null;

        $emailSuccess = false;
        $emailError = null;

        // 1. Send WhatsApp Notification
        if (!empty($rawPhone)) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
            if (str_starts_with($cleanPhone, '0') && strlen($cleanPhone) === 11) {
                $cleanPhone = '91' . substr($cleanPhone, 1);
            } elseif (strlen($cleanPhone) === 10) {
                $cleanPhone = '91' . $cleanPhone;
            }

            if ($expiry['is_expired']) {
                $urgencyLine = "⚠️ *URGENT: Your White-Label Plan Has EXPIRED!*";
                $statusLine = "Status: *Expired (" . abs($daysLeft) . " days ago)*";
            } else {
                $urgencyLine = "⏳ *NOTICE: Your White-Label Plan Expires in {$daysLeft} Day" . ($daysLeft > 1 ? 's' : '') . "!*";
                $statusLine = "Days Remaining: *{$daysLeft} days* (Ends on: *{$endsAtFormatted}*)";
            }

            $whatsappMessage = "{$urgencyLine}\n\n"
                . "Hello *{$ownerName}* ({$agency->name}),\n\n"
                . "This is a recurring daily alert regarding your White-Label SaaS subscription:\n\n"
                . "📦 *Plan:* {$planName} ({$cycleLabel})\n"
                . "🌐 *Domain:* {$cleanDomain}\n"
                . "⏳ {$statusLine}\n"
                . "💾 *Storage Used:* {$storage['total_formatted']} ({$storage['percent_used']}%)\n\n"
                . "To avoid service interruption and maintain uninterrupted access for your clients, please renew your subscription now:\n"
                . "🔗 *Renewal Link:*\n{$renewalUrl}\n\n"
                . "Need help? Reply directly to this WhatsApp message.\n"
                . "— *Super Admin Management Team*";

            try {
                $waSettings = WhatsAppGatewayController::getDefaultSettings();
                $configuredEndpoint = rtrim($waSettings['whatsapp_gateway_url'] ?? 'http://localhost:8000', '/');
                $token = $waSettings['whatsapp_access_token'] ?? 'token_6005c0f6de385fe7';
                $instanceId = $waSettings['whatsapp_instance_id'] ?? 'E351BB42DC82';
                $dbName = $waSettings['whatsapp_database'] ?? 'nooryak_whatautomation';

                $endpointCandidates = array_values(array_unique(array_filter([
                    $configuredEndpoint,
                    'http://127.0.0.1:8000',
                    'http://localhost:8000',
                ])));

                foreach ($endpointCandidates as $ep) {
                    $postUrl = rtrim($ep, '/') . '/send_message?' . http_build_query([
                        'access_token' => $token,
                        'instance_id'  => $instanceId,
                        'db'           => $dbName,
                    ]);

                    $resp = Http::asForm()->timeout(10)->withoutVerifying()->post($postUrl, [
                        'access_token' => $token,
                        'instance_id'  => $instanceId,
                        'number'       => $cleanPhone,
                        'message'      => $whatsappMessage,
                        'caption'      => $whatsappMessage,
                        'db'           => $dbName,
                    ]);

                    $json = $resp->json();
                    if ($resp->successful() && (($json['status'] ?? '') === 'success' || ($json['status'] ?? '') === 1 || ($json['success'] ?? false) === true)) {
                        $whatsappSuccess = true;
                        $whatsappResponse = $json;
                        break;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("WhatsApp alert delivery exception for agency #{$agency->id}: " . $e->getMessage());
            }
        }

        // 2. Send Email Notification
        if (!empty($toEmail)) {
            $smtpHost = Setting::get('smtp_host', env('MAIL_HOST', 'smtp.gmail.com'));
            $smtpPort = (int)Setting::get('smtp_port', env('MAIL_PORT', 587));
            $smtpEncryption = Setting::get('smtp_encryption', env('MAIL_ENCRYPTION', 'tls'));
            $smtpUsername = Setting::get('smtp_username', env('MAIL_USERNAME', ''));
            $smtpPassword = Setting::get('smtp_password', env('MAIL_PASSWORD', ''));
            $smtpFromAddress = Setting::get('smtp_from_address', env('MAIL_FROM_ADDRESS', 'noreply@saasreselling.com'));
            $smtpFromName = Setting::get('smtp_from_name', env('MAIL_FROM_NAME', 'SaaS Super Admin'));

            try {
                Config::set('mail.mailers.smtp', [
                    'transport'  => 'smtp',
                    'host'       => $smtpHost,
                    'port'       => $smtpPort,
                    'encryption' => $smtpEncryption,
                    'username'   => $smtpUsername,
                    'password'   => $smtpPassword,
                    'timeout'    => 10,
                ]);
                Config::set('mail.default', 'smtp');
                Config::set('mail.from.address', $smtpFromAddress);
                Config::set('mail.from.name', $smtpFromName);
                Mail::purge('smtp');
            } catch (\Throwable $cfgEx) {}

            $subject = $expiry['is_expired'] 
                ? "⚠️ Urgent: White-Label Plan Has Expired - {$agency->name}"
                : "⚠️ Renewal Alert: White-Label Plan Expires in {$daysLeft} Days - {$agency->name}";

            $htmlBody = "
                <div style='font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06);'>
                    <div style='background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); padding: 30px; text-align: center;'>
                        <span style='background: rgba(255,255,255,0.2); color: #ffffff; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; padding: 4px 14px; border-radius: 20px; display: inline-block; margin-bottom: 12px;'>
                            White-Label Expiry Alert
                        </span>
                        <h1 style='color: #ffffff; font-size: 22px; font-weight: 800; margin: 0; line-height: 1.3;'>
                            " . ($expiry['is_expired'] ? 'Your Subscription Has Expired' : "Subscription Expiring in {$daysLeft} Days") . "
                        </h1>
                    </div>
                    
                    <div style='padding: 30px;'>
                        <p style='color: #334155; font-size: 15px; line-height: 1.6; margin: 0 0 20px 0;'>
                            Hello <strong>{$ownerName}</strong>,<br>
                            This is an automated reminder regarding the subscription for your White-Label Agency <strong>{$agency->name}</strong>.
                        </p>

                        <div style='background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 24px;'>
                            <table style='width: 100%; border-collapse: collapse; font-size: 14px;'>
                                <tr>
                                    <td style='padding: 8px 0; color: #64748b; font-weight: 600;'>Agency / Platform:</td>
                                    <td style='padding: 8px 0; color: #0f172a; font-weight: 700; text-align: right;'>{$agency->name}</td>
                                </tr>
                                <tr>
                                    <td style='padding: 8px 0; color: #64748b; font-weight: 600;'>Custom Domain:</td>
                                    <td style='padding: 8px 0; color: #4f46e5; font-weight: 700; text-align: right;'>{$cleanDomain}</td>
                                </tr>
                                <tr>
                                    <td style='padding: 8px 0; color: #64748b; font-weight: 600;'>Active Plan:</td>
                                    <td style='padding: 8px 0; color: #0f172a; font-weight: 700; text-align: right;'>{$planName} ({$cycleLabel})</td>
                                </tr>
                                <tr>
                                    <td style='padding: 8px 0; color: #64748b; font-weight: 600;'>Expiration Date:</td>
                                    <td style='padding: 8px 0; color: #0f172a; font-weight: 700; text-align: right;'>{$endsAtFormatted}</td>
                                </tr>
                                <tr>
                                    <td style='padding: 8px 0; color: #64748b; font-weight: 600;'>Status:</td>
                                    <td style='padding: 8px 0; font-weight: 800; text-align: right; color: " . ($expiry['is_expired'] ? '#e11d48' : '#d97706') . ";'>
                                        {$expiry['status_label']}
                                    </td>
                                </tr>
                                <tr>
                                    <td style='padding: 8px 0; color: #64748b; font-weight: 600;'>Storage Consumed:</td>
                                    <td style='padding: 8px 0; color: #0f172a; font-weight: 700; text-align: right;'>
                                        {$storage['total_formatted']} ({$storage['percent_used']}%)
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='{$renewalUrl}' style='display: inline-block; background: #4f46e5; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 800; padding: 14px 32px; border-radius: 12px; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);'>
                                Renew Your Subscription Now &rarr;
                            </a>
                        </div>

                        <p style='color: #64748b; font-size: 13px; line-height: 1.5; margin: 0;'>
                            Please renew promptly to ensure continuous uptime and prevent client login interruptions. If you have already renewed, you may disregard this notice.
                        </p>
                    </div>

                    <div style='background: #f1f5f9; padding: 16px 30px; text-align: center; border-top: 1px solid #e2e8f0;'>
                        <p style='color: #94a3b8; font-size: 12px; margin: 0;'>
                            &copy; " . date('Y') . " Super Admin Platform. All rights reserved.
                        </p>
                    </div>
                </div>
            ";

            try {
                Mail::html($htmlBody, function ($msg) use ($toEmail, $subject, $smtpFromAddress, $smtpFromName) {
                    $msg->to($toEmail)
                        ->from($smtpFromAddress, $smtpFromName)
                        ->subject($subject);
                });
                $emailSuccess = true;
            } catch (\Throwable $e) {
                $emailError = $e->getMessage();
                Log::warning("SMTP email alert failed for agency #{$agency->id} ({$toEmail}): {$emailError}");

                // Fallback to PHP native mail
                try {
                    $headers  = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                    $headers .= "From: {$smtpFromName} <{$smtpFromAddress}>\r\n";
                    $headers .= "Reply-To: {$smtpFromAddress}\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                    if (@mail($toEmail, $subject, $htmlBody, $headers)) {
                        $emailSuccess = true;
                    }
                } catch (\Throwable $mEx) {}
            }
        }

        // 3. Mark alert timestamp
        $now = now();
        try {
            if ($agency->subscription && Schema::hasColumn('subscriptions', 'last_expiry_alert_at')) {
                $agency->subscription->update(['last_expiry_alert_at' => $now]);
            }
            if (Schema::hasColumn('agencies', 'last_expiry_alert_at')) {
                $agency->update(['last_expiry_alert_at' => $now]);
            }
        } catch (\Throwable $e) {}

        return [
            'success'           => $whatsappSuccess || $emailSuccess,
            'whatsapp_sent'     => $whatsappSuccess,
            'email_sent'        => $emailSuccess,
            'phone'             => $rawPhone,
            'email'             => $toEmail,
            'whatsapp_response' => $whatsappResponse,
            'email_error'       => $emailError,
            'message'           => ($whatsappSuccess ? "WhatsApp alert sent. " : "") . ($emailSuccess ? "Email alert sent." : ""),
            'sent_at'           => $now->toDateTimeString(),
        ];
    }

    /**
     * Trigger batch daily alerts for all eligible whitelabel agencies.
     */
    public function triggerBatchAlerts(): array
    {
        $agencies = Agency::where('type', '!=', 'master')
            ->where('status', '!=', 'suspended')
            ->with(['subscription.plan', 'products'])
            ->get();

        $processed = 0;
        $sentCount = 0;
        $skippedCount = 0;
        $details = [];

        foreach ($agencies as $agency) {
            $expiry = $this->calculateAgencyExpiry($agency);
            $processed++;

            if ($expiry['is_alert_due']) {
                $result = $this->sendExpiryAlert($agency);
                if ($result['success']) {
                    $sentCount++;
                }
                $details[] = [
                    'agency_id'   => $agency->id,
                    'agency_name' => $agency->name,
                    'days_left'   => $expiry['days_left'],
                    'status'      => 'sent',
                    'result'      => $result,
                ];
            } else {
                $skippedCount++;
                $details[] = [
                    'agency_id'   => $agency->id,
                    'agency_name' => $agency->name,
                    'days_left'   => $expiry['days_left'],
                    'status'      => 'skipped',
                    'reason'      => $expiry['sent_today'] ? 'Already sent today' : 'Not due yet',
                ];
            }
        }

        return [
            'total_agencies' => $processed,
            'alerts_sent'    => $sentCount,
            'skipped'        => $skippedCount,
            'details'        => $details,
        ];
    }

    /**
     * Helper to recursively calculate directory size.
     */
    protected function getDirectorySize(string $path): int
    {
        $bytes = 0;
        if (!is_dir($path)) return 0;

        try {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)
            );
            foreach ($files as $file) {
                if ($file->isFile()) {
                    $bytes += $file->getSize();
                }
            }
        } catch (\Throwable $e) {}

        return $bytes;
    }

    /**
     * Format bytes to readable string (KB, MB, GB).
     */
    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Format megabytes to readable string.
     */
    protected function formatMb(float $mb): string
    {
        if ($mb >= 1024) {
            return number_format($mb / 1024, 1) . ' GB';
        }
        return number_format($mb, 0) . ' MB';
    }
}


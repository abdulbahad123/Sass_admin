<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class WhiteLabelWhatsAppGatewayController extends Controller
{
    /**
     * Default system message formats and credentials.
     */
    public static function getSystemDefaults(): array
    {
        $dbSettings = [];
        try {
            if (Schema::hasTable('settings')) {
                $rows = DB::table('settings')->where('key', 'LIKE', 'whatsapp_%')->get();
                foreach ($rows as $r) {
                    $dbSettings[$r->key] = $r->value;
                }
            }
        } catch (\Throwable $e) {}

        return [
            'whatsapp_gateway_url' => $dbSettings['whatsapp_gateway_url'] ?? env('WHATSAPP_SERVER_URL', 'http://127.0.0.1:8000'),
            'whatsapp_access_token' => $dbSettings['whatsapp_access_token'] ?? '',
            'whatsapp_instance_id' => $dbSettings['whatsapp_instance_id'] ?? '',
            'whatsapp_database' => $dbSettings['whatsapp_database'] ?? 'nooryak_whatautomation',
            'whatsapp_is_active' => (int)($dbSettings['whatsapp_is_active'] ?? 1),
            'whatsapp_otp_active' => (int)($dbSettings['whatsapp_otp_active'] ?? 1),
            'whatsapp_credentials_active' => (int)($dbSettings['whatsapp_credentials_active'] ?? 1),
            'whatsapp_invoice_active' => (int)($dbSettings['whatsapp_invoice_active'] ?? 1),
            'whatsapp_otp_template' => $dbSettings['whatsapp_otp_template'] ?? "Your OTP verification code is *{otp}* for *{product_name}* - This code is valid for *{validity_minutes} minutes* - Please do not share it with anyone.",
            'whatsapp_credentials_template' => $dbSettings['whatsapp_credentials_template'] ?? "🎉 *Congratulations! Your {product_name} store is live!*\n\nYour store account has been created successfully.\n\n👤 *Store Name:* {store_name}\n📧 *Email / Username:* {email}\n🔑 *Password:* {password}\n📦 *Plan:* {plan_name}\n\n🔗 *Store Live URL:*\n{store_url}\n\n🔐 *Dashboard Login:*\n{login_url}\n\nNeed help? Reply to this message anytime.\n– Team {product_name} 🚀",
            'whatsapp_invoice_template' => $dbSettings['whatsapp_invoice_template'] ?? "🧾 *Payment Receipt / Invoice Confirmation*\n\nThank you for your payment with *{product_name}*!\n\n📄 *Invoice / Order ID:* #{invoice_id}\n📦 *Plan / Product:* {plan_title}\n💰 *Amount Paid:* {amount}\n💳 *Payment Method:* {payment_method}\n\n🔗 *View / Download Invoice:*\n{invoice_url}\n\nThank you for choosing *{product_name}*! 🚀",
        ];
    }

    /**
     * Compute the standard dedicated database name for an agency.
     */
    public static function getAgencyDefaultDb(Agency $agency): string
    {
        $cpanelUser = env('CPANEL_USER', 'nooryak');
        $slug = str_replace(['-', ' '], '_', strtolower($agency->slug ?: Str::slug($agency->name)));
        return "{$cpanelUser}_ps_{$slug}_whatsapp";
    }

    /**
     * Show WhatsApp Gateway Settings page for WhiteLabel Agency.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $agency = Agency::resolveCurrent($request, $user);

        if (!$agency) {
            return redirect()->route('whitelabel.dashboard')->with('error', 'Agency profile not found.');
        }

        $systemDefaults = self::getSystemDefaults();
        $this->ensureAgencyColumns();

        // Automatically assign agency database and server URL if not already assigned
        $autoDb = self::getAgencyDefaultDb($agency);
        $autoGateway = env('WHATSAPP_SERVER_URL', 'http://127.0.0.1:8000');

        $needSave = false;
        if (empty($agency->whatsapp_database) || in_array($agency->whatsapp_database, ['nooryak_whatautomation', 'nooryak_whatsapp'])) {
            $agency->whatsapp_database = $autoDb;
            $needSave = true;
        }
        if (empty($agency->whatsapp_gateway_url) || $agency->whatsapp_gateway_url === 'http://localhost:8000') {
            $agency->whatsapp_gateway_url = $autoGateway;
            $needSave = true;
        }

        if ($needSave) {
            $agency->save();
            try {
                DB::table('agencies')->where('id', $agency->id)->update([
                    'whatsapp_database' => $agency->whatsapp_database,
                    'whatsapp_gateway_url' => $agency->whatsapp_gateway_url,
                ]);
            } catch (\Throwable $e) {}
        }

        // Auto-heal WhatsApp Bulk logo and options for this agency's database
        $this->syncWhatsAppBulkLogo($agency, $autoDb);

        return view('whitelabel.whatsapp.index', compact('user', 'agency', 'systemDefaults', 'autoDb', 'autoGateway'));
    }

    /**
     * Update WhatsApp Gateway Settings & Message Formats.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $agency = Agency::resolveCurrent($request, $user);

        if (!$agency) {
            return back()->with('error', 'Agency not found.');
        }

        $this->ensureAgencyColumns();

        $autoDb = self::getAgencyDefaultDb($agency);
        $autoGateway = env('WHATSAPP_SERVER_URL', 'http://127.0.0.1:8000');
        $systemDefaults = self::getSystemDefaults();

        $accessToken = trim((string)$request->input('whatsapp_access_token'));
        $instanceId = trim((string)$request->input('whatsapp_instance_id'));

        // If user mistakenly removed the format or left it empty, automatically restore default format
        $otpTemplate = trim((string)$request->input('whatsapp_otp_template'));
        if (empty($otpTemplate)) {
            $otpTemplate = $systemDefaults['whatsapp_otp_template'];
        }

        $credsTemplate = trim((string)$request->input('whatsapp_credentials_template'));
        if (empty($credsTemplate)) {
            $credsTemplate = $systemDefaults['whatsapp_credentials_template'];
        }

        $invoiceTemplate = trim((string)$request->input('whatsapp_invoice_template'));
        if (empty($invoiceTemplate)) {
            $invoiceTemplate = $systemDefaults['whatsapp_invoice_template'];
        }

        $data = [
            'is_whatsapp_active' => 1,
            'is_whatsapp_otp_active' => 1,
            'is_whatsapp_credentials_active' => 1,
            'is_whatsapp_invoice_active' => 1,

            // Automatically manage Gateway URL and Database (not editable by agency)
            'whatsapp_gateway_url' => $autoGateway,
            'whatsapp_database' => $autoDb,

            // Agency provides API Token and Instance ID
            'whatsapp_access_token' => $accessToken,
            'whatsapp_instance_id' => $instanceId,

            'whatsapp_otp_template' => $otpTemplate,
            'whatsapp_credentials_template' => $credsTemplate,
            'whatsapp_invoice_template' => $invoiceTemplate,
        ];

        // 1. Direct Eloquent assignment & save
        foreach ($data as $key => $val) {
            $agency->{$key} = $val;
        }
        $agency->save();

        // 2. Direct DB query update to guarantee persistence even if model events or fillable intervene
        try {
            DB::table('agencies')->where('id', $agency->id)->update($data);
        } catch (\Throwable $e) {}

        // 3. Auto-fix logo and options in agency's WhatsApp bulk database
        $this->syncWhatsAppBulkLogo($agency, $autoDb);

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => "Updated WhatsApp Gateway API credentials for Agency: {$agency->name}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'WhatsApp Gateway credentials and message templates saved successfully!');
    }

    /**
     * Send a live Test WhatsApp Message.
     */
    public function testSend(Request $request)
    {
        $request->validate([
            'test_phone' => 'required|string',
            'test_type' => 'required|string|in:otp,credentials,invoice,custom',
            'custom_message' => 'nullable|string',
            'access_token' => 'nullable|string',
            'instance_id' => 'nullable|string',
        ]);

        $user = Auth::user();
        $agency = Agency::resolveCurrent($request, $user);
        $systemDefaults = self::getSystemDefaults();

        $autoDb = self::getAgencyDefaultDb($agency);
        $autoGateway = env('WHATSAPP_SERVER_URL', 'http://127.0.0.1:8000');

        // Resolve credentials: from request inputs (what user typed) or from saved agency record
        $token = trim((string)($request->input('access_token') ?: ($agency->whatsapp_access_token ?: '')));
        $instanceId = trim((string)($request->input('instance_id') ?: ($agency->whatsapp_instance_id ?: '')));
        $endpoint = $autoGateway;
        $dbName = $agency->whatsapp_database ?: $autoDb;

        if (empty($token) || empty($instanceId)) {
            return response()->json([
                'success' => false,
                'status_code' => 400,
                'error' => 'Please enter your WhatsApp API Key / Access Token and Instance ID first, then click Save.',
            ], 200);
        }

        $productName = $agency->name ?? 'Ecom Builder';
        $phone = preg_replace('/[^0-9]/', '', $request->input('test_phone'));
        if (str_starts_with($phone, '0') && strlen($phone) === 11) {
            $phone = '91' . substr($phone, 1);
        } elseif (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        $type = $request->input('test_type');
        $demoDomain = !empty($agency->custom_domain) ? $agency->clean_domain : ($request->getHost() ?: 'saasreselling.com');

        if ($type === 'otp') {
            $template = !empty($agency->whatsapp_otp_template) ? $agency->whatsapp_otp_template : $systemDefaults['whatsapp_otp_template'];
            $message = str_replace(
                ['{otp}', '{product_name}', '{validity_minutes}'],
                ['849201', $productName, '5'],
                $template
            );
        } elseif ($type === 'credentials') {
            $template = !empty($agency->whatsapp_credentials_template) ? $agency->whatsapp_credentials_template : $systemDefaults['whatsapp_credentials_template'];
            $message = str_replace(
                ['{user_name}', '{store_name}', '{email}', '{password}', '{store_url}', '{login_url}', '{product_name}', '{plan_name}'],
                ['Alex Morgan', 'Demo Fashion Hub', 'alex@example.com', 'Pass@1234', 'https://demostore.' . $demoDomain, 'https://' . $demoDomain . '/login', $productName, 'Pro Store Plan'],
                $template
            );
        } elseif ($type === 'invoice') {
            $template = !empty($agency->whatsapp_invoice_template) ? $agency->whatsapp_invoice_template : $systemDefaults['whatsapp_invoice_template'];
            $message = str_replace(
                ['{user_name}', '{invoice_id}', '{amount}', '{plan_title}', '{payment_method}', '{invoice_url}', '{product_name}'],
                ['Alex Morgan', 'INV-2026-9481', '₹1,999', 'Ecom Premium Yearly', 'Online Payment (Razorpay)', 'https://' . $demoDomain . '/invoice', $productName],
                $template
            );
        } else {
            $message = $request->input('custom_message') ?: "Hello! This is a test message from {$productName} WhatsApp Gateway 🚀";
        }

        // Try candidate gateway endpoints (127.0.0.1 first, then localhost, then public gateway if available)
        $endpointCandidates = array_values(array_unique(array_filter([
            $endpoint,
            'http://127.0.0.1:8000',
            'http://localhost:8000',
        ])));

        $tokensToTry = array_values(array_unique(array_filter([
            $token,
            str_starts_with($token, 'token_') ? substr($token, 6) : 'token_' . $token,
        ])));

        $lastError = null;
        $lastStatus = 500;
        $lastResponse = null;

        foreach ($endpointCandidates as $candEndpoint) {
            $endpointBase = rtrim($candEndpoint, '/');
            $sendPath = $endpointBase . '/send_message';

            foreach ($tokensToTry as $currentToken) {
                $queryUrl = $sendPath . '?' . http_build_query([
                    'access_token' => $currentToken,
                    'instance_id' => $instanceId,
                    'db' => $dbName,
                ]);

                try {
                    $response = Http::asForm()->timeout(5)->withoutVerifying()->post($queryUrl, [
                        'access_token' => $currentToken,
                        'instance_id' => $instanceId,
                        'number' => $phone,
                        'message' => $message,
                        'caption' => $message,
                        'db' => $dbName,
                    ]);

                    $json = $response->json();
                    $status = $response->status();

                    $isSuccess = $response->successful() && (
                        ($json['status'] ?? '') === 'success' ||
                        ($json['status'] ?? '') === 1 ||
                        ($json['success'] ?? false) === true
                    );

                    if ($isSuccess) {
                        return response()->json([
                            'success' => true,
                            'status_code' => $status,
                            'gateway_response' => $json,
                            'phone' => $phone,
                            'message_preview' => $message,
                            'instance_id' => $instanceId,
                            'db' => $dbName,
                            'endpoint' => $candEndpoint,
                            'raw_body' => $response->body(),
                        ]);
                    }

                    $lastStatus = $status;
                    $lastResponse = $json;
                    $lastError = $json['message'] ?? 'Gateway rejected credentials. Please verify your Access Token and Instance ID in WhatsApp Bulk portal.';
                } catch (\Throwable $e) {
                    $lastError = $e->getMessage();
                }
            }
        }

        return response()->json([
            'success' => false,
            'status_code' => $lastStatus,
            'gateway_response' => $lastResponse,
            'error' => $lastError,
            'instance_id' => $instanceId,
            'db' => $dbName,
            'endpoint' => $endpoint,
        ], 200);
    }

    /**
     * Auto-heal logo and image options in agency's WhatsApp bulk database.
     * Fixes broken images caused by hardcoded localhost:8080 or /public/ prefixes.
     */
    protected function syncWhatsAppBulkLogo(Agency $agency, string $primaryDb): void
    {
        $slug = str_replace(['-', ' '], '_', strtolower($agency->slug ?: Str::slug($agency->name)));
        $cpanelUser = env('CPANEL_USER', 'nooryak');

        $candidateDbs = array_unique([
            $primaryDb,
            "{$cpanelUser}_ps_{$slug}_whatsapp",
            "{$cpanelUser}_ps_{$slug}_whatsappbulk",
            "bazaarwa_ps_{$slug}_whatsappbulk",
            "bazaarwa_ps_{$slug}_whatsapp",
            "nooryak_whatautomation",
            "nooryak_whatsapp",
        ]);

        $logoVal = 'img/logo-dark.png';
        if (!empty($agency->logo)) {
            $logoVal = str_starts_with($agency->logo, 'http') ? $agency->logo : url($agency->logo);
        }

        $brandLogoVal = 'img/logo-brand-dark.png';
        if (!empty($agency->logo)) {
            $brandLogoVal = $logoVal;
        }

        $faviconVal = 'img/favicon.png';
        if (!empty($agency->favicon)) {
            $faviconVal = str_starts_with($agency->favicon, 'http') ? $agency->favicon : url($agency->favicon);
        }

        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');
        $user = env('DB_USERNAME_admin', env('DB_USERNAME', 'root'));
        $pass = env('DB_PASSWORD_admin', env('DB_PASSWORD', ''));

        foreach ($candidateDbs as $db) {
            try {
                $pdo = new \PDO("mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4", $user, $pass, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_SILENT,
                    \PDO::ATTR_TIMEOUT => 2,
                ]);

                // Check if options table exists
                $stmt = $pdo->query("SHOW TABLES LIKE 'options'");
                if ($stmt && $stmt->fetch()) {
                    $uStmt = $pdo->prepare("UPDATE `options` SET `value` = ? WHERE `name` = ?");
                    $uStmt->execute([$logoVal, 'website_logo_dark']);
                    $uStmt->execute([$brandLogoVal, 'website_logo_brand_dark']);
                    $uStmt->execute([$faviconVal, 'website_favicon']);
                    $uStmt->execute(['http://127.0.0.1:8000', 'whatsapp_server_url']);
                }
            } catch (\Throwable $e) {}
        }
    }

    /**
     * Ensure columns exist in agencies table safely without crashing.
     */
    protected function ensureAgencyColumns(): void
    {
        $cols = [
            'whatsapp_gateway_url' => "VARCHAR(255) NULL",
            'whatsapp_access_token' => "VARCHAR(255) NULL",
            'whatsapp_instance_id' => "VARCHAR(100) NULL",
            'whatsapp_database' => "VARCHAR(100) NULL",
            'is_whatsapp_active' => "TINYINT DEFAULT 1",
            'is_whatsapp_otp_active' => "TINYINT DEFAULT 1",
            'is_whatsapp_credentials_active' => "TINYINT DEFAULT 1",
            'is_whatsapp_invoice_active' => "TINYINT DEFAULT 1",
            'whatsapp_otp_template' => "TEXT NULL",
            'whatsapp_credentials_template' => "TEXT NULL",
            'whatsapp_invoice_template' => "TEXT NULL",
        ];

        foreach ($cols as $col => $sqlType) {
            try {
                if (!Schema::hasColumn('agencies', $col)) {
                    DB::statement("ALTER TABLE `agencies` ADD COLUMN `{$col}` {$sqlType}");
                }
            } catch (\Throwable $e) {}
        }
    }
}

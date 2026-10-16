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
            'whatsapp_access_token' => $dbSettings['whatsapp_access_token'] ?? 'token_6005c0f6de385fe7',
            'whatsapp_instance_id' => $dbSettings['whatsapp_instance_id'] ?? 'E351BB42DC82',
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
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        if (!$agency) {
            return redirect()->route('whitelabel.dashboard')->with('error', 'Agency profile not found.');
        }

        $systemDefaults = self::getSystemDefaults();
        $this->ensureAgencyColumns();

        // Automatically assign agency database and server URL if not already assigned
        $autoDb = self::getAgencyDefaultDb($agency);
        $autoGateway = env('WHATSAPP_SERVER_URL', 'http://127.0.0.1:8000');

        if (empty($agency->whatsapp_database) || in_array($agency->whatsapp_database, ['nooryak_whatautomation', 'nooryak_whatsapp'])) {
            $agency->whatsapp_database = $autoDb;
            $agency->save();
        }
        if (empty($agency->whatsapp_gateway_url) || $agency->whatsapp_gateway_url === 'http://localhost:8000') {
            $agency->whatsapp_gateway_url = $autoGateway;
            $agency->save();
        }

        return view('whitelabel.whatsapp.index', compact('user', 'agency', 'systemDefaults', 'autoDb', 'autoGateway'));
    }

    /**
     * Update WhatsApp Gateway Settings & Message Formats.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        if (!$agency) {
            return back()->with('error', 'Agency not found.');
        }

        $this->ensureAgencyColumns();

        $autoDb = self::getAgencyDefaultDb($agency);
        $autoGateway = env('WHATSAPP_SERVER_URL', 'http://127.0.0.1:8000');

        $data = [
            'is_whatsapp_active' => $request->has('is_whatsapp_active') ? 1 : 0,
            'is_whatsapp_otp_active' => $request->has('is_whatsapp_otp_active') ? 1 : 0,
            'is_whatsapp_credentials_active' => $request->has('is_whatsapp_credentials_active') ? 1 : 0,
            'is_whatsapp_invoice_active' => $request->has('is_whatsapp_invoice_active') ? 1 : 0,

            // Automatically manage Gateway URL and Database (not editable by agency)
            'whatsapp_gateway_url' => $autoGateway,
            'whatsapp_database' => $autoDb,

            // Agency provides API Token and Instance ID
            'whatsapp_access_token' => trim((string)$request->input('whatsapp_access_token')),
            'whatsapp_instance_id' => trim((string)$request->input('whatsapp_instance_id')),

            'whatsapp_otp_template' => $request->input('whatsapp_otp_template'),
            'whatsapp_credentials_template' => $request->input('whatsapp_credentials_template'),
            'whatsapp_invoice_template' => $request->input('whatsapp_invoice_template'),
        ];

        $agency->update($data);

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
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();
        $systemDefaults = self::getSystemDefaults();

        $autoDb = self::getAgencyDefaultDb($agency);
        $autoGateway = env('WHATSAPP_SERVER_URL', 'http://127.0.0.1:8000');

        // Resolve credentials: from request inputs (what user typed) or from saved agency record
        $token = trim((string)($request->input('access_token') ?: ($agency->whatsapp_access_token ?: $systemDefaults['whatsapp_access_token'])));
        $instanceId = trim((string)($request->input('instance_id') ?: ($agency->whatsapp_instance_id ?: $systemDefaults['whatsapp_instance_id'])));
        $endpoint = $autoGateway;
        $dbName = $agency->whatsapp_database ?: $autoDb;

        $productName = $agency->name ?? 'Ecom Builder';
        $phone = preg_replace('/[^0-9]/', '', $request->input('test_phone'));
        if (str_starts_with($phone, '0') && strlen($phone) === 11) {
            $phone = '91' . substr($phone, 1);
        } elseif (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        $type = $request->input('test_type');
        $message = '';

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
                ['Alex Morgan', 'Demo Fashion Hub', 'alex@example.com', 'Pass@1234', 'https://demostore.' . ($agency->custom_domain ?: 'youverse.in'), 'https://' . ($agency->custom_domain ?: 'youverse.in') . '/login', $productName, 'Pro Store Plan'],
                $template
            );
        } elseif ($type === 'invoice') {
            $template = !empty($agency->whatsapp_invoice_template) ? $agency->whatsapp_invoice_template : $systemDefaults['whatsapp_invoice_template'];
            $message = str_replace(
                ['{user_name}', '{invoice_id}', '{amount}', '{plan_title}', '{payment_method}', '{invoice_url}', '{product_name}'],
                ['Alex Morgan', 'INV-2026-9481', '₹1,999', 'Ecom Premium Yearly', 'Online Payment (Razorpay)', 'https://' . ($agency->custom_domain ?: 'youverse.in') . '/invoice', $productName],
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
            'https://whatsappbulk.saasreselling.com',
        ])));

        $tokensToTry = [$token];
        if (str_starts_with($token, 'token_')) {
            $tokensToTry[] = substr($token, 6);
        } else {
            $tokensToTry[] = 'token_' . $token;
        }

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
                    $response = Http::asForm()->timeout(6)->withoutVerifying()->post($queryUrl, [
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
                    $lastError = $json['message'] ?? 'Gateway rejected credentials.';
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
     * Ensure columns exist in agencies table safely without crashing.
     */
    protected function ensureAgencyColumns(): void
    {
        try {
            Schema::table('agencies', function ($table) {
                if (!Schema::hasColumn('agencies', 'whatsapp_gateway_url')) $table->string('whatsapp_gateway_url', 255)->nullable();
                if (!Schema::hasColumn('agencies', 'whatsapp_access_token')) $table->string('whatsapp_access_token', 255)->nullable();
                if (!Schema::hasColumn('agencies', 'whatsapp_instance_id')) $table->string('whatsapp_instance_id', 100)->nullable();
                if (!Schema::hasColumn('agencies', 'whatsapp_database')) $table->string('whatsapp_database', 100)->nullable();
                if (!Schema::hasColumn('agencies', 'is_whatsapp_active')) $table->tinyInteger('is_whatsapp_active')->default(1);
                if (!Schema::hasColumn('agencies', 'is_whatsapp_otp_active')) $table->tinyInteger('is_whatsapp_otp_active')->default(1);
                if (!Schema::hasColumn('agencies', 'is_whatsapp_credentials_active')) $table->tinyInteger('is_whatsapp_credentials_active')->default(1);
                if (!Schema::hasColumn('agencies', 'is_whatsapp_invoice_active')) $table->tinyInteger('is_whatsapp_invoice_active')->default(1);
                if (!Schema::hasColumn('agencies', 'whatsapp_otp_template')) $table->text('whatsapp_otp_template')->nullable();
                if (!Schema::hasColumn('agencies', 'whatsapp_credentials_template')) $table->text('whatsapp_credentials_template')->nullable();
                if (!Schema::hasColumn('agencies', 'whatsapp_invoice_template')) $table->text('whatsapp_invoice_template')->nullable();
            });
        } catch (\Throwable $e) {}
    }
}

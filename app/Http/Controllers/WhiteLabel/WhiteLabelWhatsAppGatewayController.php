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
            'whatsapp_gateway_url' => $dbSettings['whatsapp_gateway_url'] ?? 'http://localhost:8000',
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

        // Ensure database columns exist safely
        $this->ensureAgencyColumns();

        return view('whitelabel.whatsapp.index', compact('user', 'agency', 'systemDefaults'));
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

        $useCustomGateway = $request->input('use_custom_gateway') == '1';

        $data = [
            'is_whatsapp_active' => $request->has('is_whatsapp_active') ? 1 : 0,
            'is_whatsapp_otp_active' => $request->has('is_whatsapp_otp_active') ? 1 : 0,
            'is_whatsapp_credentials_active' => $request->has('is_whatsapp_credentials_active') ? 1 : 0,
            'is_whatsapp_invoice_active' => $request->has('is_whatsapp_invoice_active') ? 1 : 0,
            'whatsapp_otp_template' => $request->input('whatsapp_otp_template'),
            'whatsapp_credentials_template' => $request->input('whatsapp_credentials_template'),
            'whatsapp_invoice_template' => $request->input('whatsapp_invoice_template'),
        ];

        if ($useCustomGateway) {
            $data['whatsapp_gateway_url'] = trim((string)$request->input('whatsapp_gateway_url'));
            $data['whatsapp_access_token'] = trim((string)$request->input('whatsapp_access_token'));
            $data['whatsapp_instance_id'] = trim((string)$request->input('whatsapp_instance_id'));
            $data['whatsapp_database'] = trim((string)$request->input('whatsapp_database'));
        } else {
            // Null out custom gateway fields to inherit SuperAdmin system defaults
            $data['whatsapp_gateway_url'] = null;
            $data['whatsapp_access_token'] = null;
            $data['whatsapp_instance_id'] = null;
            $data['whatsapp_database'] = null;
        }

        $agency->update($data);

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => "Updated WhatsApp Gateway & Notification Templates for Agency: {$agency->name}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'WhatsApp Gateway settings and message templates saved successfully!');
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
        ]);

        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();
        $systemDefaults = self::getSystemDefaults();

        // Resolve active credentials (custom or fallback to system)
        $endpoint = !empty($agency->whatsapp_gateway_url) ? $agency->whatsapp_gateway_url : $systemDefaults['whatsapp_gateway_url'];
        $token = !empty($agency->whatsapp_access_token) ? $agency->whatsapp_access_token : $systemDefaults['whatsapp_access_token'];
        $instanceId = !empty($agency->whatsapp_instance_id) ? $agency->whatsapp_instance_id : $systemDefaults['whatsapp_instance_id'];
        $dbName = !empty($agency->whatsapp_database) ? $agency->whatsapp_database : $systemDefaults['whatsapp_database'];

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

        // Send via gateway
        $endpointBase = rtrim($endpoint, '/');
        $sendPath = $endpointBase . '/send_message';
        $queryUrl = $sendPath . '?' . http_build_query([
            'access_token' => $token,
            'instance_id' => $instanceId,
            'db' => $dbName,
        ]);

        try {
            $response = Http::asForm()->timeout(12)->withoutVerifying()->post($queryUrl, [
                'access_token' => $token,
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

            return response()->json([
                'success' => $isSuccess,
                'status_code' => $status,
                'gateway_response' => $json,
                'phone' => $phone,
                'message_preview' => $message,
                'instance_id' => $instanceId,
                'endpoint' => $endpoint,
                'raw_body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'endpoint' => $endpoint,
            ], 500);
        }
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

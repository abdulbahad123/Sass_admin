<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WhatsAppGatewayController extends Controller
{
    /**
     * Default WhatsApp settings list.
     */
    public static function getDefaultSettings(): array
    {
        return [
            'whatsapp_gateway_url' => Setting::get('whatsapp_gateway_url', 'http://localhost:8000'),
            'whatsapp_access_token' => Setting::get('whatsapp_access_token', 'token_6005c0f6de385fe7'),
            'whatsapp_instance_id' => Setting::get('whatsapp_instance_id', 'E351BB42DC82'),
            'whatsapp_database' => Setting::get('whatsapp_database', 'nooryak_whatautomation'),
            'whatsapp_is_active' => (int)Setting::get('whatsapp_is_active', 1),
            'whatsapp_otp_active' => (int)Setting::get('whatsapp_otp_active', 1),
            'whatsapp_credentials_active' => (int)Setting::get('whatsapp_credentials_active', 1),
            'whatsapp_invoice_active' => (int)Setting::get('whatsapp_invoice_active', 1),
            'whatsapp_otp_template' => Setting::get('whatsapp_otp_template', "Your OTP verification code is *{otp}* for *{product_name}* - This code is valid for *{validity_minutes} minutes* - Please do not share it with anyone."),
            'whatsapp_credentials_template' => Setting::get('whatsapp_credentials_template', "🎉 *Congratulations! Your {product_name} store is live!*\n\nYour store account has been created successfully.\n\n👤 *Store Name:* {store_name}\n📧 *Email / Username:* {email}\n🔑 *Password:* {password}\n📦 *Plan:* {plan_name}\n\n🔗 *Store Live URL:*\n{store_url}\n\n🔐 *Dashboard Login:*\n{login_url}\n\nNeed help? Reply to this message anytime.\n– Team {product_name} 🚀"),
            'whatsapp_invoice_template' => Setting::get('whatsapp_invoice_template', "🧾 *Payment Receipt / Invoice Confirmation*\n\nThank you for your payment with *{product_name}*!\n\n📄 *Invoice / Order ID:* #{invoice_id}\n📦 *Plan / Product:* {plan_title}\n💰 *Amount Paid:* {amount}\n💳 *Payment Method:* {payment_method}\n\n🔗 *View / Download Invoice:*\n{invoice_url}\n\nThank you for choosing *{product_name}*! 🚀"),
        ];
    }

    /**
     * Display SuperAdmin WhatsApp Gateway Management.
     */
    public function index()
    {
        $user = Auth::user();
        $settings = self::getDefaultSettings();

        return view('admin.whatsapp.index', compact('user', 'settings'));
    }

    /**
     * Update Global SuperAdmin WhatsApp Gateway Settings.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        Setting::set('whatsapp_gateway_url', trim((string)$request->input('whatsapp_gateway_url')));
        Setting::set('whatsapp_access_token', trim((string)$request->input('whatsapp_access_token')));
        Setting::set('whatsapp_instance_id', trim((string)$request->input('whatsapp_instance_id')));
        Setting::set('whatsapp_database', trim((string)$request->input('whatsapp_database')));

        Setting::set('whatsapp_is_active', $request->has('whatsapp_is_active') ? '1' : '0');
        Setting::set('whatsapp_otp_active', $request->has('whatsapp_otp_active') ? '1' : '0');
        Setting::set('whatsapp_credentials_active', $request->has('whatsapp_credentials_active') ? '1' : '0');
        Setting::set('whatsapp_invoice_active', $request->has('whatsapp_invoice_active') ? '1' : '0');

        Setting::set('whatsapp_otp_template', $request->input('whatsapp_otp_template'));
        Setting::set('whatsapp_credentials_template', $request->input('whatsapp_credentials_template'));
        Setting::set('whatsapp_invoice_template', $request->input('whatsapp_invoice_template'));

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => "Updated Global WhatsApp Gateway Settings and Templates",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Global WhatsApp Gateway Settings and Templates updated successfully!');
    }

    /**
     * Send a Live Test WhatsApp Message.
     */
    public function testSend(Request $request)
    {
        $request->validate([
            'test_phone' => 'required|string',
            'test_type' => 'required|string|in:otp,credentials,invoice,custom',
            'custom_message' => 'nullable|string',
        ]);

        $settings = self::getDefaultSettings();
        $endpoint = $settings['whatsapp_gateway_url'];
        $token = $settings['whatsapp_access_token'];
        $instanceId = $settings['whatsapp_instance_id'];
        $dbName = $settings['whatsapp_database'];

        $productName = 'Ecom Builder';
        $phone = preg_replace('/[^0-9]/', '', $request->input('test_phone'));
        if (str_starts_with($phone, '0') && strlen($phone) === 11) {
            $phone = '91' . substr($phone, 1);
        } elseif (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        $type = $request->input('test_type');
        $message = '';

        if ($type === 'otp') {
            $template = $settings['whatsapp_otp_template'];
            $message = str_replace(
                ['{otp}', '{product_name}', '{validity_minutes}'],
                ['549102', $productName, '5'],
                $template
            );
        } elseif ($type === 'credentials') {
            $template = $settings['whatsapp_credentials_template'];
            $message = str_replace(
                ['{user_name}', '{store_name}', '{email}', '{password}', '{store_url}', '{login_url}', '{product_name}', '{plan_name}'],
                ['John Doe', 'Super Store', 'john@example.com', 'Pass@1234', 'https://superstore.launchshop.in', 'https://launchshop.in/login', $productName, 'Enterprise Plan'],
                $template
            );
        } elseif ($type === 'invoice') {
            $template = $settings['whatsapp_invoice_template'];
            $message = str_replace(
                ['{user_name}', '{invoice_id}', '{amount}', '{plan_title}', '{payment_method}', '{invoice_url}', '{product_name}'],
                ['John Doe', 'INV-2026-8801', '₹2,499', 'Annual Unlimited Store', 'Razorpay Online', 'https://launchshop.in/invoice', $productName],
                $template
            );
        } else {
            $message = $request->input('custom_message') ?: "Hello! This is a test message from SuperAdmin WhatsApp Gateway 🚀";
        }

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
}

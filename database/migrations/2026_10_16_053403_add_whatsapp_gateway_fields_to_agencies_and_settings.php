<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            if (!Schema::hasColumn('agencies', 'whatsapp_gateway_url')) {
                $table->string('whatsapp_gateway_url', 255)->nullable();
            }
            if (!Schema::hasColumn('agencies', 'whatsapp_access_token')) {
                $table->string('whatsapp_access_token', 255)->nullable();
            }
            if (!Schema::hasColumn('agencies', 'whatsapp_instance_id')) {
                $table->string('whatsapp_instance_id', 100)->nullable();
            }
            if (!Schema::hasColumn('agencies', 'whatsapp_database')) {
                $table->string('whatsapp_database', 100)->nullable();
            }
            if (!Schema::hasColumn('agencies', 'is_whatsapp_active')) {
                $table->tinyInteger('is_whatsapp_active')->default(1);
            }
            if (!Schema::hasColumn('agencies', 'is_whatsapp_otp_active')) {
                $table->tinyInteger('is_whatsapp_otp_active')->default(1);
            }
            if (!Schema::hasColumn('agencies', 'is_whatsapp_credentials_active')) {
                $table->tinyInteger('is_whatsapp_credentials_active')->default(1);
            }
            if (!Schema::hasColumn('agencies', 'is_whatsapp_invoice_active')) {
                $table->tinyInteger('is_whatsapp_invoice_active')->default(1);
            }
            if (!Schema::hasColumn('agencies', 'whatsapp_otp_template')) {
                $table->text('whatsapp_otp_template')->nullable();
            }
            if (!Schema::hasColumn('agencies', 'whatsapp_credentials_template')) {
                $table->text('whatsapp_credentials_template')->nullable();
            }
            if (!Schema::hasColumn('agencies', 'whatsapp_invoice_template')) {
                $table->text('whatsapp_invoice_template')->nullable();
            }
        });

        // Seed default SuperAdmin settings if settings table exists
        if (Schema::hasTable('settings')) {
            $defaults = [
                'whatsapp_gateway_url' => 'http://localhost:8000',
                'whatsapp_access_token' => 'token_6005c0f6de385fe7',
                'whatsapp_instance_id' => 'E351BB42DC82',
                'whatsapp_database' => 'nooryak_whatautomation',
                'whatsapp_is_active' => '1',
                'whatsapp_otp_active' => '1',
                'whatsapp_credentials_active' => '1',
                'whatsapp_invoice_active' => '1',
                'whatsapp_otp_template' => "Your OTP verification code is *{otp}* for *{product_name}* - This code is valid for *{validity_minutes} minutes* - Please do not share it with anyone.",
                'whatsapp_credentials_template' => "🎉 *Congratulations! Your {product_name} store is live!*\n\nYour store account has been created successfully.\n\n👤 *Store Name:* {store_name}\n📧 *Email / Username:* {email}\n🔑 *Password:* {password}\n📦 *Plan:* {plan_name}\n\n🔗 *Store Live URL:*\n{store_url}\n\n🔐 *Dashboard Login:*\n{login_url}\n\nNeed help? Reply to this message anytime.\n– Team {product_name} 🚀",
                'whatsapp_invoice_template' => "🧾 *Payment Receipt / Invoice Confirmation*\n\nThank you for your payment with *{product_name}*!\n\n📄 *Invoice / Order ID:* #{invoice_id}\n📦 *Plan / Product:* {plan_title}\n💰 *Amount Paid:* {amount}\n💳 *Payment Method:* {payment_method}\n\n🔗 *View / Download Invoice:*\n{invoice_url}\n\nThank you for choosing *{product_name}*! 🚀",
            ];

            foreach ($defaults as $key => $val) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $val, 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $cols = [
                'whatsapp_gateway_url',
                'whatsapp_access_token',
                'whatsapp_instance_id',
                'whatsapp_database',
                'is_whatsapp_active',
                'is_whatsapp_otp_active',
                'is_whatsapp_credentials_active',
                'is_whatsapp_invoice_active',
                'whatsapp_otp_template',
                'whatsapp_credentials_template',
                'whatsapp_invoice_template',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('agencies', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

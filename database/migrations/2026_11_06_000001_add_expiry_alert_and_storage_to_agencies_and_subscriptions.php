<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add alert tracking columns to subscriptions table
        if (Schema::hasTable('subscriptions')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                if (!Schema::hasColumn('subscriptions', 'last_expiry_alert_at')) {
                    $table->timestamp('last_expiry_alert_at')->nullable()->after('ends_at');
                }
            });
        }

        // 2. Add storage limit and alert tracking columns to agencies table
        if (Schema::hasTable('agencies')) {
            Schema::table('agencies', function (Blueprint $table) {
                if (!Schema::hasColumn('agencies', 'storage_limit_mb')) {
                    $table->unsignedBigInteger('storage_limit_mb')->default(5120)->after('max_products'); // Default 5GB (5120 MB)
                }
                if (!Schema::hasColumn('agencies', 'last_expiry_alert_at')) {
                    $table->timestamp('last_expiry_alert_at')->nullable()->after('storage_limit_mb');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('subscriptions')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                if (Schema::hasColumn('subscriptions', 'last_expiry_alert_at')) {
                    $table->dropColumn('last_expiry_alert_at');
                }
            });
        }

        if (Schema::hasTable('agencies')) {
            Schema::table('agencies', function (Blueprint $table) {
                if (Schema::hasColumn('agencies', 'last_expiry_alert_at')) {
                    $table->dropColumn('last_expiry_alert_at');
                }
                if (Schema::hasColumn('agencies', 'storage_limit_mb')) {
                    $table->dropColumn('storage_limit_mb');
                }
            });
        }
    }
};


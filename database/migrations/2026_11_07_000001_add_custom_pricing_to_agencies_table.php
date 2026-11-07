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
        if (Schema::hasTable('agencies')) {
            Schema::table('agencies', function (Blueprint $table) {
                if (!Schema::hasColumn('agencies', 'custom_renewal_price')) {
                    $table->decimal('custom_renewal_price', 10, 2)->nullable()->after('storage_limit_mb');
                }
                if (!Schema::hasColumn('agencies', 'custom_expiry_message')) {
                    $table->text('custom_expiry_message')->nullable()->after('custom_renewal_price');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('agencies')) {
            Schema::table('agencies', function (Blueprint $table) {
                if (Schema::hasColumn('agencies', 'custom_renewal_price')) {
                    $table->dropColumn('custom_renewal_price');
                }
                if (Schema::hasColumn('agencies', 'custom_expiry_message')) {
                    $table->dropColumn('custom_expiry_message');
                }
            });
        }
    }
};


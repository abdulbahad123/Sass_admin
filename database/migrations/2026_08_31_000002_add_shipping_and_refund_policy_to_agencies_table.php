<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            if (!Schema::hasColumn('agencies', 'shipping_policy')) {
                $table->longText('shipping_policy')->nullable()->after('cookie_policy');
            }
            if (!Schema::hasColumn('agencies', 'refund_policy')) {
                $table->longText('refund_policy')->nullable()->after('shipping_policy');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn(['shipping_policy', 'refund_policy']);
        });
    }
};

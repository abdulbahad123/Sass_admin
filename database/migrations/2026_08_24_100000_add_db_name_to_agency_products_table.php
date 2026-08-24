<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('agency_products', 'db_name')) {
            Schema::table('agency_products', function (Blueprint $table) {
                $table->string('db_name')->nullable()->after('status');
                $table->string('db_status')->default('pending')->after('db_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('agency_products', 'db_name')) {
            Schema::table('agency_products', function (Blueprint $table) {
                $table->dropColumn(['db_name', 'db_status']);
            });
        }
    }
};

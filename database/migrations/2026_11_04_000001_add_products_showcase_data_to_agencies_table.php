<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            if (!Schema::hasColumn('agencies', 'products_showcase_data')) {
                $table->longText('products_showcase_data')->nullable()->after('products_section_subtitle');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            if (Schema::hasColumn('agencies', 'products_showcase_data')) {
                $table->dropColumn('products_showcase_data');
            }
        });
    }
};


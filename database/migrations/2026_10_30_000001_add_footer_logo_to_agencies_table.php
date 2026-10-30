<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            if (!Schema::hasColumn('agencies', 'footer_logo')) {
                $table->string('footer_logo')->nullable()->after('logo_dark');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            if (Schema::hasColumn('agencies', 'footer_logo')) {
                $table->dropColumn('footer_logo');
            }
        });
    }
};

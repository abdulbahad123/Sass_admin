<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            if (!Schema::hasColumn('agencies', 'logo_dark')) {
                $table->string('logo_dark')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('agencies', 'about_section_badge')) {
                $table->string('about_section_badge')->nullable()->after('about_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            if (Schema::hasColumn('agencies', 'logo_dark')) {
                $table->dropColumn('logo_dark');
            }
            if (Schema::hasColumn('agencies', 'about_section_badge')) {
                $table->dropColumn('about_section_badge');
            }
        });
    }
};

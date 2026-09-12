<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            if (!Schema::hasColumn('agencies', 'pricing_plans_data')) {
                $table->longText('pricing_plans_data')->nullable()->after('faq_data');
            }
            if (!Schema::hasColumn('agencies', 'pricing_section_title')) {
                $table->string('pricing_section_title')->nullable()->after('pricing_plans_data');
            }
            if (!Schema::hasColumn('agencies', 'pricing_section_subtitle')) {
                $table->text('pricing_section_subtitle')->nullable()->after('pricing_section_title');
            }
            if (!Schema::hasColumn('agencies', 'pricing_trust_bar')) {
                $table->text('pricing_trust_bar')->nullable()->after('pricing_section_subtitle');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn(['pricing_plans_data', 'pricing_section_title', 'pricing_section_subtitle', 'pricing_trust_bar']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            // About features
            if (!Schema::hasColumn('agencies', 'about_features_data')) {
                $table->longText('about_features_data')->nullable()->after('about_image');
            }
            // Why Choose Us
            if (!Schema::hasColumn('agencies', 'why_choose_title')) {
                $table->string('why_choose_title')->nullable()->after('about_features_data');
            }
            if (!Schema::hasColumn('agencies', 'why_choose_subtitle')) {
                $table->text('why_choose_subtitle')->nullable()->after('why_choose_title');
            }
            // Products section
            if (!Schema::hasColumn('agencies', 'products_section_badge')) {
                $table->string('products_section_badge')->nullable()->after('services_data');
            }
            if (!Schema::hasColumn('agencies', 'products_section_title')) {
                $table->string('products_section_title')->nullable()->after('products_section_badge');
            }
            if (!Schema::hasColumn('agencies', 'products_section_subtitle')) {
                $table->text('products_section_subtitle')->nullable()->after('products_section_title');
            }
            if (!Schema::hasColumn('agencies', 'products_cta_text')) {
                $table->string('products_cta_text')->nullable()->after('products_section_subtitle');
            }
            if (!Schema::hasColumn('agencies', 'products_cta_url')) {
                $table->string('products_cta_url')->nullable()->after('products_cta_text');
            }
            // How it works
            if (!Schema::hasColumn('agencies', 'how_it_works_title')) {
                $table->string('how_it_works_title')->nullable()->after('products_cta_url');
            }
            if (!Schema::hasColumn('agencies', 'how_it_works_subtitle')) {
                $table->text('how_it_works_subtitle')->nullable()->after('how_it_works_title');
            }
            if (!Schema::hasColumn('agencies', 'how_it_works_steps')) {
                $table->longText('how_it_works_steps')->nullable()->after('how_it_works_subtitle');
            }
            // CTA Banner
            if (!Schema::hasColumn('agencies', 'cta_section_title')) {
                $table->string('cta_section_title')->nullable()->after('cta_image');
            }
            if (!Schema::hasColumn('agencies', 'cta_section_subtitle')) {
                $table->text('cta_section_subtitle')->nullable()->after('cta_section_title');
            }
            if (!Schema::hasColumn('agencies', 'cta_features_data')) {
                $table->longText('cta_features_data')->nullable()->after('cta_section_subtitle');
            }
            if (!Schema::hasColumn('agencies', 'cta_button_text')) {
                $table->string('cta_button_text')->nullable()->after('cta_features_data');
            }
            if (!Schema::hasColumn('agencies', 'cta_button_url')) {
                $table->string('cta_button_url')->nullable()->after('cta_button_text');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $cols = [
                'about_features_data', 'why_choose_title', 'why_choose_subtitle',
                'products_section_badge', 'products_section_title', 'products_section_subtitle', 'products_cta_text', 'products_cta_url',
                'how_it_works_title', 'how_it_works_subtitle', 'how_it_works_steps',
                'cta_section_title', 'cta_section_subtitle', 'cta_features_data', 'cta_button_text', 'cta_button_url'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('agencies', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            // Stats Bar (500+ Partners, 50K+ Businesses Served, 5 Products, 99.9% Uptime, 24/7 Support)
            if (!Schema::hasColumn('agencies', 'stats_bar_data')) {
                $table->longText('stats_bar_data')->nullable();
            }

            // Model Cards (Model 01: White Label SaaS Partner, Model 02: White Label SaaS Master Panel)
            if (!Schema::hasColumn('agencies', 'model_cards_data')) {
                $table->longText('model_cards_data')->nullable();
            }

            // Revenue Opportunity Calculator
            if (!Schema::hasColumn('agencies', 'revenue_calculator_data')) {
                $table->longText('revenue_calculator_data')->nullable();
            }

            // How It Works / Process steps
            if (!Schema::hasColumn('agencies', 'how_it_works_data')) {
                $table->longText('how_it_works_data')->nullable();
            }

            // Growth Path (Start → Grow → Expand → Global)
            if (!Schema::hasColumn('agencies', 'growth_path_data')) {
                $table->longText('growth_path_data')->nullable();
            }

            // CTA Banner custom heading and subtext
            if (!Schema::hasColumn('agencies', 'cta_banner_heading')) {
                $table->string('cta_banner_heading')->nullable();
            }
            if (!Schema::hasColumn('agencies', 'cta_banner_subtext')) {
                $table->string('cta_banner_subtext')->nullable();
            }

            // Announcement/top bar text
            if (!Schema::hasColumn('agencies', 'announcement_bar_text')) {
                $table->string('announcement_bar_text')->nullable();
            }

            // KB floating stats (hero right side floating card values)
            if (!Schema::hasColumn('agencies', 'kb_stats')) {
                $table->longText('kb_stats')->nullable();
            }
            if (!Schema::hasColumn('agencies', 'kb_floating_data')) {
                $table->longText('kb_floating_data')->nullable();
            }

            // Nav links JSON
            if (!Schema::hasColumn('agencies', 'nav_links_data')) {
                $table->longText('nav_links_data')->nullable();
            }

            // Trusted by / categories data
            if (!Schema::hasColumn('agencies', 'categories_data')) {
                $table->longText('categories_data')->nullable();
            }

            // Section headings
            if (!Schema::hasColumn('agencies', 'why_choose_title')) {
                $table->string('why_choose_title')->nullable();
            }
            if (!Schema::hasColumn('agencies', 'products_section_title')) {
                $table->string('products_section_title')->nullable();
            }

            // Second CTA button
            if (!Schema::hasColumn('agencies', 'cta2_text')) {
                $table->string('cta2_text')->nullable();
            }
            if (!Schema::hasColumn('agencies', 'cta2_url')) {
                $table->string('cta2_url')->nullable();
            }

            // Contact address (for footer)
            if (!Schema::hasColumn('agencies', 'contact_address')) {
                $table->text('contact_address')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $cols = [
                'stats_bar_data', 'model_cards_data', 'revenue_calculator_data',
                'how_it_works_data', 'growth_path_data', 'cta_banner_heading',
                'cta_banner_subtext', 'announcement_bar_text', 'kb_stats',
                'kb_floating_data', 'nav_links_data', 'categories_data',
                'why_choose_title', 'products_section_title', 'cta2_text', 'cta2_url',
                'contact_address',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('agencies', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

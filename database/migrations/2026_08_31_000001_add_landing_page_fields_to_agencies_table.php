<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            if (!Schema::hasColumn('agencies', 'secondary_color')) {
                $table->string('secondary_color')->default('#9333ea')->after('primary_color');
            }
            if (!Schema::hasColumn('agencies', 'accent_color')) {
                $table->string('accent_color')->default('#3b82f6')->after('secondary_color');
            }
            if (!Schema::hasColumn('agencies', 'bg_color')) {
                $table->string('bg_color')->default('#ffffff')->after('accent_color');
            }
            if (!Schema::hasColumn('agencies', 'text_color')) {
                $table->string('text_color')->default('#0f172a')->after('bg_color');
            }
            if (!Schema::hasColumn('agencies', 'favicon')) {
                $table->string('favicon')->nullable()->after('logo');
            }

            // Hero section
            if (!Schema::hasColumn('agencies', 'hero_title')) {
                $table->text('hero_title')->nullable()->after('favicon');
            }
            if (!Schema::hasColumn('agencies', 'hero_subtitle')) {
                $table->text('hero_subtitle')->nullable()->after('hero_title');
            }
            if (!Schema::hasColumn('agencies', 'hero_description')) {
                $table->text('hero_description')->nullable()->after('hero_subtitle');
            }
            if (!Schema::hasColumn('agencies', 'cta_text')) {
                $table->string('cta_text')->default('Start Free Today')->after('hero_description');
            }
            if (!Schema::hasColumn('agencies', 'cta_url')) {
                $table->string('cta_url')->default('/login')->after('cta_text');
            }
            if (!Schema::hasColumn('agencies', 'hero_image')) {
                $table->string('hero_image')->nullable()->after('cta_url');
            }

            // Sections content
            if (!Schema::hasColumn('agencies', 'about_content')) {
                $table->longText('about_content')->nullable()->after('hero_image');
            }
            if (!Schema::hasColumn('agencies', 'about_image')) {
                $table->string('about_image')->nullable()->after('about_content');
            }
            if (!Schema::hasColumn('agencies', 'services_data')) {
                $table->longText('services_data')->nullable()->after('about_image');
            }
            if (!Schema::hasColumn('agencies', 'features_data')) {
                $table->longText('features_data')->nullable()->after('services_data');
            }
            if (!Schema::hasColumn('agencies', 'testimonials_data')) {
                $table->longText('testimonials_data')->nullable()->after('features_data');
            }
            if (!Schema::hasColumn('agencies', 'faq_data')) {
                $table->longText('faq_data')->nullable()->after('testimonials_data');
            }

            // Contact & Social
            if (!Schema::hasColumn('agencies', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('faq_data');
            }
            if (!Schema::hasColumn('agencies', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('contact_email');
            }
            if (!Schema::hasColumn('agencies', 'contact_address')) {
                $table->text('contact_address')->nullable()->after('contact_phone');
            }
            if (!Schema::hasColumn('agencies', 'social_links')) {
                $table->longText('social_links')->nullable()->after('contact_address');
            }

            // Footer & Legal
            if (!Schema::hasColumn('agencies', 'footer_content')) {
                $table->text('footer_content')->nullable()->after('social_links');
            }
            if (!Schema::hasColumn('agencies', 'privacy_policy')) {
                $table->longText('privacy_policy')->nullable()->after('footer_content');
            }
            if (!Schema::hasColumn('agencies', 'terms_conditions')) {
                $table->longText('terms_conditions')->nullable()->after('privacy_policy');
            }
            if (!Schema::hasColumn('agencies', 'cookie_policy')) {
                $table->longText('cookie_policy')->nullable()->after('terms_conditions');
            }
            if (!Schema::hasColumn('agencies', 'disclaimer')) {
                $table->longText('disclaimer')->nullable()->after('cookie_policy');
            }

            // SEO & Controls
            if (!Schema::hasColumn('agencies', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('disclaimer');
            }
            if (!Schema::hasColumn('agencies', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('agencies', 'og_image')) {
                $table->string('og_image')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('agencies', 'sections_enabled')) {
                $table->longText('sections_enabled')->nullable()->after('og_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn([
                'secondary_color', 'accent_color', 'bg_color', 'text_color', 'favicon',
                'hero_title', 'hero_subtitle', 'hero_description', 'cta_text', 'cta_url', 'hero_image',
                'about_content', 'about_image', 'services_data', 'features_data', 'testimonials_data', 'faq_data',
                'contact_email', 'contact_phone', 'contact_address', 'social_links',
                'footer_content', 'privacy_policy', 'terms_conditions', 'cookie_policy', 'disclaimer',
                'meta_title', 'meta_description', 'og_image', 'sections_enabled'
            ]);
        });
    }
};

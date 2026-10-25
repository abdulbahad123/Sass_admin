<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create agency_contact_submissions table if not exists
        if (!Schema::hasTable('agency_contact_submissions')) {
            Schema::create('agency_contact_submissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('agency_id')->index();
                $table->string('name');
                $table->string('email');
                $table->string('subject')->nullable();
                $table->text('message');
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('status')->default('unread'); // unread, read, archived
                $table->timestamps();

                $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');
            });
        }

        // 2. Add dynamic website fields to agencies table if missing
        Schema::table('agencies', function (Blueprint $table) {
            // Category pills & trusted businesses
            if (!Schema::hasColumn('agencies', 'trusted_businesses_title')) {
                $table->string('trusted_businesses_title')->nullable()->after('why_choose_subtitle');
            }
            if (!Schema::hasColumn('agencies', 'categories_data')) {
                $table->longText('categories_data')->nullable()->after('trusted_businesses_title');
            }

            // Pricing badges & trust cards
            if (!Schema::hasColumn('agencies', 'pricing_badge_text')) {
                $table->string('pricing_badge_text')->nullable()->after('pricing_section_subtitle');
            }
            if (!Schema::hasColumn('agencies', 'pricing_yearly_discount')) {
                $table->string('pricing_yearly_discount')->nullable()->after('pricing_badge_text');
            }
            if (!Schema::hasColumn('agencies', 'trust_elements_data')) {
                $table->longText('trust_elements_data')->nullable()->after('pricing_trust_bar');
            }

            // SMTP & reCAPTCHA settings
            if (!Schema::hasColumn('agencies', 'smtp_settings')) {
                $table->longText('smtp_settings')->nullable()->after('footer_content');
            }
            if (!Schema::hasColumn('agencies', 'recaptcha_settings')) {
                $table->longText('recaptcha_settings')->nullable()->after('smtp_settings');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agency_contact_submissions');

        Schema::table('agencies', function (Blueprint $table) {
            $cols = [
                'trusted_businesses_title',
                'categories_data',
                'pricing_badge_text',
                'pricing_yearly_discount',
                'trust_elements_data',
                'smtp_settings',
                'recaptcha_settings',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('agencies', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

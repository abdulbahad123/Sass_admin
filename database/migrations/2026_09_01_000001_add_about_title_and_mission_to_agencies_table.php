<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            if (!Schema::hasColumn('agencies', 'about_title')) {
                $table->string('about_title')->nullable()->after('about_image');
            }
            if (!Schema::hasColumn('agencies', 'about_mission')) {
                $table->text('about_mission')->nullable()->after('about_title');
            }
            if (!Schema::hasColumn('agencies', 'cta_image')) {
                $table->string('cta_image')->nullable()->after('hero_image');
            }
            if (!Schema::hasColumn('agencies', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('social_links');
            }
            if (!Schema::hasColumn('agencies', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('facebook_url');
            }
            if (!Schema::hasColumn('agencies', 'youtube_url')) {
                $table->string('youtube_url')->nullable()->after('instagram_url');
            }
            if (!Schema::hasColumn('agencies', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable()->after('youtube_url');
            }
            if (!Schema::hasColumn('agencies', 'twitter_url')) {
                $table->string('twitter_url')->nullable()->after('linkedin_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn([
                'about_title',
                'about_mission',
                'cta_image',
                'facebook_url',
                'instagram_url',
                'youtube_url',
                'linkedin_url',
                'twitter_url',
            ]);
        });
    }
};

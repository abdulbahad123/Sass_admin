<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            if (!Schema::hasColumn('agencies', 'gemini_api_key')) {
                $table->string('gemini_api_key', 255)->nullable();
            }
            if (!Schema::hasColumn('agencies', 'openai_api_key')) {
                $table->string('openai_api_key', 255)->nullable();
            }
            if (!Schema::hasColumn('agencies', 'is_gemini_active')) {
                $table->tinyInteger('is_gemini_active')->default(1);
            }
            if (!Schema::hasColumn('agencies', 'is_openai_active')) {
                $table->tinyInteger('is_openai_active')->default(1);
            }
        });
    }

    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('agencies', 'gemini_api_key')) $cols[] = 'gemini_api_key';
            if (Schema::hasColumn('agencies', 'openai_api_key')) $cols[] = 'openai_api_key';
            if (Schema::hasColumn('agencies', 'is_gemini_active')) $cols[] = 'is_gemini_active';
            if (Schema::hasColumn('agencies', 'is_openai_active')) $cols[] = 'is_openai_active';
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};

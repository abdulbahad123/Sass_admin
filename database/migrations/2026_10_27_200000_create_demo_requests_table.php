<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('demo_requests')) {
            Schema::create('demo_requests', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('whatsapp');
                $table->string('email');
                $table->string('profession')->nullable();
                $table->text('notes')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('status')->default('new'); // new, contacted, scheduled, completed, cancelled
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('demo_requests');
    }
};

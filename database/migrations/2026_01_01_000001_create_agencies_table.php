<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['master', 'white_label'])->default('white_label');
            $table->foreignId('parent_id')->nullable()->constrained('agencies')->onDelete('cascade');
            $table->string('owner_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
            $table->string('custom_domain')->nullable();
            $table->string('primary_color')->default('#4f46e5');
            $table->string('status')->default('active'); // active, pending, suspended
            $table->integer('max_clients')->default(50);
            $table->integer('max_products')->default(5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};

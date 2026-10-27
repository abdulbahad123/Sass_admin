<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class DemoRequest extends Model
{
    protected $table = 'demo_requests';

    protected $fillable = [
        'name',
        'whatsapp',
        'email',
        'profession',
        'notes',
        'ip_address',
        'status'
    ];

    /**
     * Auto ensure table exists in case migration has not yet been executed on server
     */
    public static function ensureTableExists(): void
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
                $table->string('status')->default('new');
                $table->timestamps();
            });
        }
    }
}

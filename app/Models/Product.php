<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'description',
        'icon',
        'app_url',
        'api_key',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_product');
    }

    public function agencies()
    {
        return $this->belongsToMany(Agency::class, 'agency_products')->withPivot('status')->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'parent_id',
        'owner_name',
        'email',
        'phone',
        'logo',
        'custom_domain',
        'primary_color',
        'status',
        'max_clients',
        'max_products',
    ];

    // Master agency has sub-agencies
    public function subAgencies()
    {
        return $this->hasMany(Agency::class, 'parent_id');
    }

    // Sub agency belongs to parent master agency
    public function parentAgency()
    {
        return $this->belongsTo(Agency::class, 'parent_id');
    }

    // Users linked to this agency
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Active subscription
    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Products directly entitled to this agency
    public function products()
    {
        return $this->belongsToMany(Product::class, 'agency_products')->withPivot('status')->withTimestamps();
    }
}

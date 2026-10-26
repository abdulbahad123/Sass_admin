<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyBroadcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'target_agency_id',
        'send_email',
        'send_mobile',
        'is_active',
        'created_by',
    ];

    public function targetAgency()
    {
        return $this->belongsTo(Agency::class, 'target_agency_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'agency_id',
        'user_id',
        'product_id',
        'assigned_to',
        'subject',
        'priority',
        'status',
        'message',
        'attachment',
        'last_replied_at',
    ];

    protected $casts = [
        'last_replied_at' => 'datetime',
    ];

    public static function generateTicketNumber(): string
    {
        $prefix = 'TKT-' . date('Y') . '-';
        $latest = self::where('ticket_number', 'LIKE', $prefix . '%')->orderBy('id', 'desc')->first();
        if ($latest) {
            $number = intval(substr($latest->ticket_number, -4)) + 1;
        } else {
            $number = 1;
        }
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at', 'asc');
    }

    public function getAttachmentUrlAttribute()
    {
        return $this->attachment ? Storage::url($this->attachment) : null;
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'open' => ['label' => 'Open', 'class' => 'bg-amber-500/10 text-amber-600 border-amber-200'],
            'in_progress' => ['label' => 'In Progress', 'class' => 'bg-blue-500/10 text-blue-600 border-blue-200'],
            'pending_reply' => ['label' => 'Pending Reply', 'class' => 'bg-purple-500/10 text-purple-600 border-purple-200'],
            'resolved' => ['label' => 'Resolved', 'class' => 'bg-emerald-500/10 text-emerald-600 border-emerald-200'],
            'closed' => ['label' => 'Closed', 'class' => 'bg-slate-500/10 text-slate-600 border-slate-200'],
            default => ['label' => ucfirst($this->status), 'class' => 'bg-slate-100 text-slate-700 border-slate-200'],
        };
    }

    public function getPriorityBadgeAttribute(): array
    {
        return match ($this->priority) {
            'low' => ['label' => 'Low', 'class' => 'bg-slate-100 text-slate-600 border-slate-200'],
            'medium' => ['label' => 'Medium', 'class' => 'bg-indigo-50 text-indigo-600 border-indigo-200'],
            'high' => ['label' => 'High', 'class' => 'bg-orange-50 text-orange-600 border-orange-200'],
            'urgent' => ['label' => 'Urgent', 'class' => 'bg-rose-50 text-rose-600 border-rose-200 animate-pulse'],
            default => ['label' => ucfirst($this->priority), 'class' => 'bg-slate-100 text-slate-700 border-slate-200'],
        };
    }
}

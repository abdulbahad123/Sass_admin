<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'attachment',
        'is_internal_note',
    ];

    protected $casts = [
        'is_internal_note' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAttachmentsListAttribute(): array
    {
        if (empty($this->attachment)) {
            return [];
        }

        $decoded = json_decode($this->attachment, true);
        $paths = is_array($decoded) ? $decoded : [$this->attachment];

        $urls = [];
        foreach ($paths as $path) {
            if (empty($path)) continue;
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                $urls[] = $path;
            } else {
                $urls[] = asset(ltrim($path, '/'));
            }
        }
        return $urls;
    }

    public function getAttachmentUrlAttribute()
    {
        $list = $this->attachments_list;
        return $list[0] ?? null;
    }
}

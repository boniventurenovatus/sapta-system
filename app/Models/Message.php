<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'conversation_id', 'sender_id', 'recipient_id', 'subject', 'body',
        'status', 'is_draft', 'deleted_for_sender', 'deleted_for_recipient',
        'attachments', 'read_at', 'sent_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_draft' => 'boolean',
        'deleted_for_sender' => 'boolean',
        'deleted_for_recipient' => 'boolean',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function scopeInbox($query, $userId)
    {
        return $query->where('recipient_id', $userId)
                    ->where('is_draft', false)
                    ->where('deleted_for_recipient', false)
                    ->where('status', '!=', 'deleted');
    }

    public function scopeSent($query, $userId)
    {
        return $query->where('sender_id', $userId)
                    ->where('is_draft', false)
                    ->where('deleted_for_sender', false)
                    ->where('status', '!=', 'deleted');
    }

    public function scopeDrafts($query, $userId)
    {
        return $query->where('sender_id', $userId)
                    ->where('is_draft', true);
    }

    public function scopeUnread($query, $userId)
    {
        return $query->where('recipient_id', $userId)
                    ->whereNull('read_at')
                    ->where('is_draft', false);
    }
}
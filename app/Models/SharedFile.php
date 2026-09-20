<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SharedFile extends Model
{
    use SoftDeletes;

    protected $table = 'shared_files';

    protected $fillable = [
        'name', 'file_path', 'file_type', 'file_size', 'uploaded_by',
        'conversation_id', 'message_id', 'group_id', 'visibility',
        'shared_with', 'download_count',
    ];

    protected $casts = [
        'shared_with' => 'array',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function scopeVisibleTo($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('visibility', 'public')
              ->orWhere('uploaded_by', $userId)
              ->orWhereJsonContains('shared_with', $userId);
        });
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $size = $this->file_size;
        if ($size < 1024) return $size . ' B';
        if ($size < 1048576) return round($size / 1024, 1) . ' KB';
        return round($size / 1048576, 1) . ' MB';
    }
}
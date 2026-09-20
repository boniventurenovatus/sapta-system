<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'type', 'created_by', 'members',
        'color', 'icon', 'is_active',
    ];

    protected $casts = [
        'members' => 'array',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function membersList()
    {
        return $this->belongsToMany(User::class, 'group_members', 'group_id', 'user_id')
                    ->withPivot('role', 'joined_at', 'last_read_at')
                    ->withTimestamps();
    }

    public function groupMembers()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function messages()
    {
        return $this->hasMany(GroupMessage::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(GroupMessage::class)->latestOfMany();
    }

    public function getMemberCountAttribute(): int
    {
        return $this->groupMembers()->count();
    }

    public function isMember($userId): bool
    {
        return $this->groupMembers()->where('user_id', $userId)->exists();
    }

    public function isAdminOf($userId): bool
    {
        return $this->groupMembers()
                    ->where('user_id', $userId)
                    ->where('role', 'admin')
                    ->exists();
    }

    public function canManage($userId): bool
    {
        // Group admin, au super_admin/ceo/bod
        $user = User::find($userId);
        if (!$user) return false;

        return $this->isAdminOf($userId)
            || $user->hasAnyRole(['super_admin', 'admin', 'ceo', 'bod']);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('groupMembers', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}
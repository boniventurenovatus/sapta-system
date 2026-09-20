<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'auditable_type', 'auditable_id', 'user_id', 'action',
        'previous_status', 'new_status', 'comment', 'meta',
        'ip_address', 'user_agent',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function auditable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAuditLog extends Model
{
    protected $fillable = [
        'employee_id',
        'user_id',
        'action',
        'old_status',
        'new_status',
        'reason',
        'ip_address',
        'user_agent',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'deactivate' => 'Deactivated',
            'terminate'  => 'Terminated',
            'activate'   => 'Activated',
            'suspend'    => 'Suspended',
            'delete'     => 'Deleted',
            default      => ucfirst($this->action),
        };
    }

    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'activate'   => 'green',
            'deactivate' => 'yellow',
            'suspend'    => 'purple',
            'terminate'  => 'red',
            'delete'     => 'red',
            default      => 'gray',
        };
    }

    public function getUserNameAttribute(): string
    {
        if (!$this->user) {
            return 'System';
        }

        return $this->user->username
            ?? $this->user->email
            ?? 'User #' . $this->user_id;
    }
}
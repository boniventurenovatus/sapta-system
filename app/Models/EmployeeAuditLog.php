<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAuditLog extends Model
{
    protected $table = 'employee_audit_logs';

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

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
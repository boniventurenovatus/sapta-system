<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasWorkflow;

class LeaveRequest extends Model
{
    use HasWorkflow;

    protected $table = 'leave_requests';

    protected $fillable = [
        'employee_id', 'region_id', 'district_id', 'ward_id',
        'leave_type', 'start_date', 'end_date', 'total_days',
        'reason', 'status', 'approved_by', 'approved_at', 'rejection_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function getFormType(): string
    {
        return 'leave_request';
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function submissions()
    {
        return $this->morphMany(Submission::class, 'submittable');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDrafts($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'rejected');
    }

    public function getDaysAttribute(): int
    {
        return $this->total_days ?? 1;
    }

    public function getReturnReasonAttribute(): ?string
    {
        return $this->rejection_reason;
    }

    public function getRequestNumberAttribute(): string
    {
        return 'LV-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public static function generateRequestNumber(): string
    {
        $year = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'LV-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'approved' => 'green',
            'rejected' => 'red',
            'pending' => 'yellow',
            default => 'slate',
        };
    }

    public function getLeaveTypeLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->leave_type));
    }
}
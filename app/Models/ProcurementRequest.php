<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasWorkflow;

class ProcurementRequest extends Model
{
    use SoftDeletes, HasWorkflow;

    protected $table = 'procurement_requests';

    protected $fillable = [
        'request_number', 'title', 'description', 'category',
        'quantity', 'estimated_cost', 'priority', 'required_date',
        'requested_by', 'status', 'return_reason',
        'approved_by', 'approved_at', 'submitted_at',
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'required_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function getFormType(): string
    {
        return 'procurement_request';
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function submissions()
    {
        return $this->morphMany(Submission::class, 'submittable');
    }

    public function scopeForUser($query, $userId = null)
    {
        return $query->where('requested_by', $userId ?? auth()->id());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public static function generateRequestNumber(): string
    {
        $year = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'PR-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'approved', 'completed' => 'green',
            'rejected' => 'red',
            'pending' => 'yellow',
            default => 'slate',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'critical' => 'red',
            'high' => 'yellow',
            'medium' => 'blue',
            default => 'slate',
        };
    }
}
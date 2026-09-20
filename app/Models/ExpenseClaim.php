<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasWorkflow;

class ExpenseClaim extends Model
{
    use SoftDeletes, HasWorkflow;

    protected $table = 'expense_claims';

    protected $fillable = [
        'claim_number', 'employee_id', 'title', 'category', 'amount', 'currency',
        'expense_date', 'description', 'project', 'department', 'payment_method',
        'receipt_number', 'status', 'return_reason', 'created_by', 'approved_by',
        'submitted_at', 'approved_at', 'paid_at', 'attachments',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'attachments' => 'array',
    ];

    public function getFormType(): string
    {
        return 'expense_claim';
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
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
        return $query->where('created_by', $userId ?? auth()->id());
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['submitted', 'pending_approval']);
    }

    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    public static function generateClaimNumber(): string
    {
        $year = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'EXP-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'approved', 'paid', 'completed' => 'green',
            'rejected', 'returned' => 'red',
            'submitted', 'pending_approval' => 'yellow',
            default => 'slate',
        };
    }
}
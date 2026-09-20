<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'budgets';

    protected $fillable = [
        'budget_number', 'name', 'fiscal_year', 'project_id',
        'region_id',
        'district_id',
        'ward_id', 'department_id',
        'category', 'allocated_amount', 'spent_amount', 'currency',
        'start_date', 'end_date', 'status', 'created_by', 'approved_by',
        'approved_at', 'notes',
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->allocated_amount - $this->spent_amount;
    }

    public function getUtilizationPercentAttribute(): float
    {
        if ($this->allocated_amount <= 0) return 0;
        return round(($this->spent_amount / $this->allocated_amount) * 100, 1);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'approved' => 'info',
            'active' => 'success',
            'closed' => 'dark',
            default => 'secondary',
        };
    }

    public static function generateNumber(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $next = $last ? $last->id + 1 : 1;
        return 'BGT-' . $year . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function scopeDraft($q) { return $q->where('status', 'draft'); }
    public function scopeActive($q) { return $q->where('status', 'active'); }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }
}

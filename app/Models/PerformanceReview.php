<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformanceReview extends Model
{
    use HasFactory;

    protected $table = 'performance_reviews';

    protected $fillable = [
        'review_number', 'employee_id',
        'region_id',
        'district_id',
        'ward_id', 'reviewer_id', 'review_period',
        'review_date', 'period_start', 'period_end', 'overall_rating',
        'strengths', 'improvements', 'goals', 'comments', 'status',
        'approved_by', 'approved_at', 'rejection_reason',
    ];

    protected $casts = [
        'review_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
        'overall_rating' => 'decimal:1',
        'approved_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function kpis(): HasMany
    {
        return $this->hasMany(PerformanceKpi::class, 'performance_review_id');
    }

    public function getRatingLabelAttribute(): string
    {
        if ($this->overall_rating >= 4.5) return 'Outstanding';
        if ($this->overall_rating >= 3.5) return 'Excellent';
        if ($this->overall_rating >= 2.5) return 'Good';
        if ($this->overall_rating >= 1.5) return 'Fair';
        return 'Needs Improvement';
    }

    public function getRatingColorAttribute(): string
    {
        if ($this->overall_rating >= 4.5) return 'success';
        if ($this->overall_rating >= 3.5) return 'info';
        if ($this->overall_rating >= 2.5) return 'primary';
        if ($this->overall_rating >= 1.5) return 'warning';
        return 'danger';
    }

    public static function generateNumber(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $next = $last ? $last->id + 1 : 1;
        return 'PR-' . $year . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function scopeDraft($q) { return $q->where('status', 'draft'); }
    public function scopeSubmitted($q) { return $q->where('status', 'submitted'); }
    public function scopeApproved($q) { return $q->where('status', 'approved'); }

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

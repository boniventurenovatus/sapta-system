<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    protected $fillable = [
        'training_number',
        'title', 'type', 'description',
        'start_date', 'end_date', 'duration_hours', 'cost',
        'region_id', 'district_id', 'ward_id',
        'organization_id', 'department_id',
        'trainer_name', 'trainer_email',
        'max_participants', 'venue',
        'status', 'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'cost' => 'decimal:2',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(TrainingEnrollment::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(TrainingEnrollment::class);
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('end_date', '<', now());
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getDurationDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getIsOngoingAttribute(): bool
    {
        return $this->start_date <= now() && $this->end_date >= now();
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->start_date > now();
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->end_date < now();
    }
}
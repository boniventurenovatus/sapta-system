<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    use HasFactory;

    protected $table = 'trainings';

    protected $fillable = [
        'training_number', 'title',
        'region_id',
        'district_id',
        'ward_id', 'description', 'category',
        'trainer_name', 'trainer_type', 'trainer_contact', 'location',
        'start_date', 'end_date', 'start_time', 'end_time',
        'duration_hours', 'max_participants', 'cost', 'currency',
        'department_id', 'status', 'created_by', 'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(TrainingEnrollment::class, 'training_id');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'planned' => 'info',
            'ongoing' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'orientation' => 'compass',
            'technical' => 'gears',
            'soft_skills' => 'comments',
            'compliance' => 'shield-halved',
            'leadership' => 'crown',
            'safety' => 'helmet-safety',
            default => 'book',
        };
    }

    public function getEnrolledCountAttribute(): int
    {
        return $this->enrollments()->count();
    }

    public function getCompletedCountAttribute(): int
    {
        return $this->enrollments()->where('status', 'completed')->count();
    }

    public static function generateNumber(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $next = $last ? $last->id + 1 : 1;
        return 'TRN-' . $year . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function scopePlanned($q) { return $q->where('status', 'planned'); }
    public function scopeOngoing($q) { return $q->where('status', 'ongoing'); }
    public function scopeCompleted($q) { return $q->where('status', 'completed'); }

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

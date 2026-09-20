<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingEnrollment extends Model
{
    use HasFactory;

    protected $table = 'training_enrollments';

    protected $fillable = [
        'training_id', 'employee_id', 'status', 'score',
        'certificate_number', 'completed_at', 'feedback', 'notes',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'enrolled' => 'info',
            'attended' => 'warning',
            'completed' => 'success',
            'dropped' => 'danger',
            default => 'secondary',
        };
    }
}

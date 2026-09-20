<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'job_applications';

    protected $fillable = [
        'job_posting_id', 'applicant_name', 'email', 'phone',
        'resume_path', 'cover_letter', 'status', 'rating',
        'interview_date', 'notes',
    ];

    protected $casts = [
        'interview_date' => 'datetime',
    ];

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'applied' => 'secondary',
            'screening' => 'info',
            'interview' => 'warning',
            'offered' => 'primary',
            'hired' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }
}

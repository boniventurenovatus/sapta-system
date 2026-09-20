<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'region_id',
        'district_id',
        'ward_id',
        'assigned_to',
        'created_by',
        'status',
        'priority',
        'start_date',
        'due_date',
        'completed_date',
        'estimated_hours',
        'actual_hours',
        'progress',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_date' => 'date',
        'estimated_hours' => 'integer',
        'actual_hours' => 'integer',
        'progress' => 'integer'
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['todo', 'in_progress', 'review']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'done');
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $labels = [
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'review' => 'Review',
            'done' => 'Done'
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getPriorityLabelAttribute()
    {
        $labels = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical'
        ];
        return $labels[$this->priority] ?? $this->priority;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'todo' => 'secondary',
            'in_progress' => 'primary',
            'review' => 'warning',
            'done' => 'success'
        ];
        return $colors[$this->status] ?? 'secondary';
    }

    public function getPriorityColorAttribute()
    {
        $colors = [
            'low' => 'secondary',
            'medium' => 'info',
            'high' => 'warning',
            'critical' => 'danger'
        ];
        return $colors[$this->priority] ?? 'secondary';
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->due_date) return false;
        return now()->gt($this->due_date) && $this->status !== 'done';
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->due_date) return null;
        return now()->diffInDays($this->due_date, false);
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }
}

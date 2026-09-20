<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'documents';

    protected $fillable = [
        'document_number', 'title', 'description', 'category',
        'file_path', 'file_name', 'file_type', 'file_size',
        'employee_id', 'project_id', 'department_id',
        'region_id',
        'district_id',
        'ward_id',
        'status', 'visibility', 'issue_date', 'expiry_date',
        'uploaded_by', 'approved_by', 'approved_at',
        'version', 'tags', 'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'approved_at' => 'datetime',
        'tags' => 'array',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'active' => 'success',
            'archived' => 'dark',
            'expired' => 'danger',
            default => 'secondary',
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'contract' => 'file-contract',
            'policy' => 'file-shield',
            'report' => 'file-lines',
            'invoice' => 'file-invoice',
            'receipt' => 'receipt',
            'certificate' => 'award',
            'memo' => 'file-pen',
            default => 'file',
        };
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        if (!$this->expiry_date) return false;
        return $this->expiry_date->isFuture() && $this->expiry_date->diffInDays(now()) <= 30;
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expiry_date) return false;
        return $this->expiry_date->isPast();
    }

    public static function generateNumber(): string
    {
        $year = date('Y');
        $last = static::withTrashed()->whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $next = $last ? $last->id + 1 : 1;
        return 'DOC-' . $year . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeExpiring($q) {
        return $q->whereNotNull('expiry_date')
                 ->whereDate('expiry_date', '>=', now())
                 ->whereDate('expiry_date', '<=', now()->addDays(30));
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

    /**
     * Alias for uploadedBy() — used in views as $document->uploader
     */
    public function uploader(): BelongsTo
    {
        return $this->uploadedBy();
    }


    /**
     * Alias for approvedBy() — used in controller as pprover
     */
    public function approver(): BelongsTo
    {
        return $this->approvedBy();
    }
}
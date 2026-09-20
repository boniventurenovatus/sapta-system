<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    use HasFactory;

    protected $table = 'receipts';

    protected $fillable = [
        'receipt_number',
        'receipt_date',
        'payer_name',
        'payer_type',
        'payer_contact',
        'project_id',
        'region_id',
        'district_id',
        'ward_id',
        'department_id',
        'amount',
        'currency',
        'payment_method',
        'reference_number',
        'description',
        'status',
        'received_by',
        'confirmed_by',
        'confirmed_at',
        'cancellation_reason',
        'attachments',
        'notes',
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'attachments' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public static function generateNumber(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $next = $last ? $last->id + 1 : 1;
        return 'RCT-' . $year . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function scopeDraft($q) { return $q->where('status', 'draft'); }
    public function scopeConfirmed($q) { return $q->where('status', 'confirmed'); }
    public function scopeCancelled($q) { return $q->where('status', 'cancelled'); }

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

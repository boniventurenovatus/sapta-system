<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasWorkflow;

class PaymentVoucher extends Model
{
    use SoftDeletes, HasWorkflow;

    protected $table = 'payment_vouchers';

    protected $fillable = [
        'trans_no', 'voucher_number', 'payee_name', 'payee_pobox', 'batch',
        'payee_type', 'payee_contact',
        'project_id', 'payee_account', 'amount', 'amount_in_words', 'currency', 'payment_method', 'mode',
        'bank', 'cheque_number',
        'project', 'budget_line', 'department', 'payment_date',
        'description', 'notes', 'status', 'return_reason',
        'created_by', 'approved_by', 'submitted_at', 'approved_at',
        'prepared_by_id', 'prepared_at', 'checked_by_id', 'checked_at',
        'authorized_by_id', 'authorized_at',
        'received_by_name', 'received_signature', 'received_at',
        'paid_at', 'attachments',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'prepared_at' => 'datetime',
        'checked_at' => 'datetime',
        'authorized_at' => 'datetime',
        'received_at' => 'datetime',
        'attachments' => 'array',
    ];

    public function getFormType(): string
    {
        return 'payment_voucher';
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function projectModel()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function submissions()
    {
        return $this->morphMany(Submission::class, 'submittable');
    }

    public function drafts()
    {
        return $this->morphMany(Draft::class, 'draftable');
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

    public static function generateVoucherNumber(): string
    {
        $year = date('Y');
        $prefix = 'PV-' . $year . '-';

        // Pata voucher ya mwisho ya mwaka huu (hata iliyofutwa)
        $last = static::withTrashed()
            ->where('voucher_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            // Chukua sehemu ya mwisho baada ya `-`
            $parts = explode('-', $last->voucher_number);
            $lastNumber = (int) end($parts);
            $next = $lastNumber + 1;
        } else {
            $next = 1;
        }

        // Rudi na namba mpya (5 digits)
        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
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


    /**
     * Line items (hasMany).
     */
    public function items()
    {
        return $this->hasMany(PaymentVoucherItem::class, 'payment_voucher_id')
            ->orderBy('sort_order');
    }

    /**
     * Prepared By user.
     */
    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by_id');
    }

    /**
     * Checked By user.
     */
    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by_id');
    }

    /**
     * Authorized By user.
     */
    public function authorizedBy()
    {
        return $this->belongsTo(User::class, 'authorized_by_id');
    }}
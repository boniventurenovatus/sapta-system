<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payslip extends Model
{
    use HasFactory;

    protected $table = 'payslips';

    protected $fillable = [
        'employee_id',
        'region_id',
        'district_id',
        'ward_id', 'salary_id', 'payslip_number', 'month', 'year',
        'basic_salary', 'total_allowances', 'gross_salary', 'total_deductions',
        'net_salary', 'allowances_breakdown', 'deductions_breakdown',
        'status', 'payment_date', 'notes',
    ];

    protected $casts = [
        'allowances_breakdown' => 'array',
        'deductions_breakdown' => 'array',
        'payment_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class, 'salary_id');
    }

    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    public function getPeriodAttribute(): string
    {
        return $this->month_name . ' ' . $this->year;
    }

    public static function generateNumber(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $next = $last ? $last->id + 1 : 1;
        return 'PS-' . $year . '-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function scopeDraft($q) { return $q->where('status', 'draft'); }
    public function scopeApproved($q) { return $q->where('status', 'approved'); }
    public function scopePaid($q) { return $q->where('status', 'paid'); }

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    use HasFactory;

    protected $table = 'salaries';

    protected $fillable = [
        'employee_id', 'basic_salary', 'house_allowance', 'transport_allowance',
        'medical_allowance', 'other_allowances', 'tax_deduction', 'nssf_deduction',
        'nhif_deduction', 'loan_deduction', 'other_deductions', 'currency',
        'payment_method', 'bank_name', 'bank_account', 'effective_date', 'status', 'notes',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'effective_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getTotalAllowancesAttribute(): float
    {
        return $this->house_allowance + $this->transport_allowance + $this->medical_allowance + $this->other_allowances;
    }

    public function getTotalDeductionsAttribute(): float
    {
        return $this->tax_deduction + $this->nssf_deduction + $this->nhif_deduction + $this->loan_deduction + $this->other_deductions;
    }

    public function getGrossSalaryAttribute(): float
    {
        return $this->basic_salary + $this->total_allowances;
    }

    public function getNetSalaryAttribute(): float
    {
        return $this->gross_salary - $this->total_deductions;
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }
}

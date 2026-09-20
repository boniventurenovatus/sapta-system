@extends('layouts.sapta')

@section('title', 'Edit Salary')
@section('page-title', 'Edit Salary')

@section('content')
<style>
    .es-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .es-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .es-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .es-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .es-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .es-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .es-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .es-card-head h2 { font-size: 0.85rem; font-weight: 800; color: #f59e0b; margin: 0; text-transform: uppercase; letter-spacing: 0.05em; }
    .es-card-body { padding: 1.5rem; }
    .es-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .es-grid { grid-template-columns: 1fr; } }
    .es-group { margin-bottom: 1rem; }
    .es-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .es-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; }
    .es-input:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }
    .es-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; }
    .es-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; }
    .es-btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .es-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
</style>

<div class="es-page">
    <div class="es-head">
        <div class="es-head-icon"><i class="fas fa-pen"></i></div>
        <div>
            <h1>Edit Salary</h1>
            <p>Update salary structure for {{ $salary->employee->first_name ?? '' }} {{ $salary->employee->last_name ?? '' }}.</p>
        </div>
    </div>

    <form action="{{ route('payroll.salaries.update', $salary) }}" method="POST">
        @csrf @method('PUT')

        <div class="es-card">
            <div class="es-card-head"><h2>Earnings</h2></div>
            <div class="es-card-body">
                <div class="es-grid">
                    <div class="es-group">
                        <label>Basic Salary</label>
                        <input type="number" name="basic_salary" class="es-input" value="{{ old('basic_salary', $salary->basic_salary) }}" step="0.01" min="0" required>
                    </div>
                    <div class="es-group">
                        <label>House Allowance</label>
                        <input type="number" name="house_allowance" class="es-input" value="{{ old('house_allowance', $salary->house_allowance) }}" step="0.01" min="0">
                    </div>
                    <div class="es-group">
                        <label>Transport Allowance</label>
                        <input type="number" name="transport_allowance" class="es-input" value="{{ old('transport_allowance', $salary->transport_allowance) }}" step="0.01" min="0">
                    </div>
                    <div class="es-group">
                        <label>Medical Allowance</label>
                        <input type="number" name="medical_allowance" class="es-input" value="{{ old('medical_allowance', $salary->medical_allowance) }}" step="0.01" min="0">
                    </div>
                    <div class="es-group">
                        <label>Other Allowances</label>
                        <input type="number" name="other_allowances" class="es-input" value="{{ old('other_allowances', $salary->other_allowances) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>
        </div>

        <div class="es-card">
            <div class="es-card-head"><h2>Deductions</h2></div>
            <div class="es-card-body">
                <div class="es-grid">
                    <div class="es-group">
                        <label>Tax (PAYE)</label>
                        <input type="number" name="tax_deduction" class="es-input" value="{{ old('tax_deduction', $salary->tax_deduction) }}" step="0.01" min="0">
                    </div>
                    <div class="es-group">
                        <label>NSSF</label>
                        <input type="number" name="nssf_deduction" class="es-input" value="{{ old('nssf_deduction', $salary->nssf_deduction) }}" step="0.01" min="0">
                    </div>
                    <div class="es-group">
                        <label>NHIF</label>
                        <input type="number" name="nhif_deduction" class="es-input" value="{{ old('nhif_deduction', $salary->nhif_deduction) }}" step="0.01" min="0">
                    </div>
                    <div class="es-group">
                        <label>Loan</label>
                        <input type="number" name="loan_deduction" class="es-input" value="{{ old('loan_deduction', $salary->loan_deduction) }}" step="0.01" min="0">
                    </div>
                    <div class="es-group">
                        <label>Other Deductions</label>
                        <input type="number" name="other_deductions" class="es-input" value="{{ old('other_deductions', $salary->other_deductions) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>
        </div>

        <div class="es-card">
            <div class="es-card-head"><h2>Payment Details</h2></div>
            <div class="es-card-body">
                <div class="es-grid">
                    <input type="hidden" name="employee_id" value="{{ $salary->employee_id }}">
                    <div class="es-group">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" class="es-input" value="{{ old('bank_name', $salary->bank_name) }}">
                    </div>
                    <div class="es-group">
                        <label>Bank Account</label>
                        <input type="text" name="bank_account" class="es-input" value="{{ old('bank_account', $salary->bank_account) }}">
                    </div>
                    <div class="es-group">
                        <label>Effective Date</label>
                        <input type="date" name="effective_date" class="es-input" value="{{ old('effective_date', $salary->effective_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="es-group">
                        <label>Status</label>
                        <select name="status" class="es-input" required>
                            <option value="active" @selected(old('status', $salary->status) === 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $salary->status) === 'inactive')>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="es-card">
            <div class="es-actions">
                <button type="submit" class="es-btn es-btn-warning"><i class="fas fa-save"></i> Update Salary</button>
                <a href="{{ route('payroll.salaries') }}" class="es-btn es-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection

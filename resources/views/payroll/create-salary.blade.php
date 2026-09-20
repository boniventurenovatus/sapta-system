@extends('layouts.sapta')

@section('title', 'Add Salary')
@section('page-title', 'Add Salary')

@section('content')
<style>
    .cs-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .cs-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .cs-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .cs-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .cs-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .cs-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .cs-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .cs-card-head h2 { font-size: 0.85rem; font-weight: 800; color: #2563eb; margin: 0; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.5rem; }
    .cs-card-body { padding: 1.5rem; }
    .cs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .cs-grid { grid-template-columns: 1fr; } }
    .cs-group { margin-bottom: 1rem; }
    .cs-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .cs-group label .req { color: #dc2626; }
    .cs-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; transition: all 0.2s; }
    .cs-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .cs-error { color: #dc2626; font-size: 0.8rem; margin-top: 0.3rem; font-weight: 600; }
    .cs-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; }
    .cs-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .cs-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
    .cs-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .cs-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
</style>

<div class="cs-page">
    <div class="cs-head">
        <div class="cs-head-icon"><i class="fas fa-money-bill"></i></div>
        <div>
            <h1>Add Salary</h1>
            <p>Create salary structure for an employee.</p>
        </div>
    </div>

    <form action="{{ route('payroll.salaries.store') }}" method="POST">
        @csrf

        <div class="cs-card">
            <div class="cs-card-head"><h2><i class="fas fa-user"></i> Employee Information</h2></div>
            <div class="cs-card-body">
                <div class="cs-group">
                    <label>Employee <span class="req">*</span></label>
                    <select name="employee_id" class="cs-input" required>
                        <option value="">-- Select Employee --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" @selected(old('employee_id') == $emp->id)>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id')<div class="cs-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="cs-card">
            <div class="cs-card-head"><h2><i class="fas fa-plus"></i> Earnings</h2></div>
            <div class="cs-card-body">
                <div class="cs-grid">
                    <div class="cs-group">
                        <label>Basic Salary <span class="req">*</span></label>
                        <input type="number" name="basic_salary" class="cs-input" value="{{ old('basic_salary', 0) }}" step="0.01" min="0" required>
                    </div>
                    <div class="cs-group">
                        <label>House Allowance</label>
                        <input type="number" name="house_allowance" class="cs-input" value="{{ old('house_allowance', 0) }}" step="0.01" min="0">
                    </div>
                    <div class="cs-group">
                        <label>Transport Allowance</label>
                        <input type="number" name="transport_allowance" class="cs-input" value="{{ old('transport_allowance', 0) }}" step="0.01" min="0">
                    </div>
                    <div class="cs-group">
                        <label>Medical Allowance</label>
                        <input type="number" name="medical_allowance" class="cs-input" value="{{ old('medical_allowance', 0) }}" step="0.01" min="0">
                    </div>
                    <div class="cs-group">
                        <label>Other Allowances</label>
                        <input type="number" name="other_allowances" class="cs-input" value="{{ old('other_allowances', 0) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>
        </div>

        <div class="cs-card">
            <div class="cs-card-head"><h2><i class="fas fa-minus"></i> Deductions</h2></div>
            <div class="cs-card-body">
                <div class="cs-grid">
                    <div class="cs-group">
                        <label>Tax (PAYE)</label>
                        <input type="number" name="tax_deduction" class="cs-input" value="{{ old('tax_deduction', 0) }}" step="0.01" min="0">
                    </div>
                    <div class="cs-group">
                        <label>NSSF</label>
                        <input type="number" name="nssf_deduction" class="cs-input" value="{{ old('nssf_deduction', 0) }}" step="0.01" min="0">
                    </div>
                    <div class="cs-group">
                        <label>NHIF</label>
                        <input type="number" name="nhif_deduction" class="cs-input" value="{{ old('nhif_deduction', 0) }}" step="0.01" min="0">
                    </div>
                    <div class="cs-group">
                        <label>Loan</label>
                        <input type="number" name="loan_deduction" class="cs-input" value="{{ old('loan_deduction', 0) }}" step="0.01" min="0">
                    </div>
                    <div class="cs-group">
                        <label>Other Deductions</label>
                        <input type="number" name="other_deductions" class="cs-input" value="{{ old('other_deductions', 0) }}" step="0.01" min="0">
                    </div>
                </div>
            </div>
        </div>

        <div class="cs-card">
            <div class="cs-card-head"><h2><i class="fas fa-university"></i> Payment Details</h2></div>
            <div class="cs-card-body">
                <div class="cs-grid">
                    <div class="cs-group">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" class="cs-input" value="{{ old('bank_name') }}">
                    </div>
                    <div class="cs-group">
                        <label>Bank Account</label>
                        <input type="text" name="bank_account" class="cs-input" value="{{ old('bank_account') }}">
                    </div>
                    <div class="cs-group">
                        <label>Effective Date</label>
                        <input type="date" name="effective_date" class="cs-input" value="{{ old('effective_date', date('Y-m-d')) }}">
                    </div>
                    <div class="cs-group">
                        <label>Status <span class="req">*</span></label>
                        <select name="status" class="cs-input" required>
                            <option value="active" @selected(old('status') === 'active')>Active</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="cs-card">
            <div class="cs-actions">
                <button type="submit" class="cs-btn cs-btn-primary"><i class="fas fa-save"></i> Save Salary</button>
                <a href="{{ route('payroll.salaries') }}" class="cs-btn cs-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection

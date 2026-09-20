@extends('layouts.sapta')

@section('title', 'Payslip Details')
@section('page-title', 'Payslip Details')

@section('content')
<style>
    .ps-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .ps-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .ps-hero { padding: 2rem; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; text-align: center; }
    .ps-hero h1 { font-size: 1.5rem; font-weight: 800; margin: 0 0 0.5rem; }
    .ps-hero .code { display: inline-block; background: rgba(255,255,255,0.2); padding: 0.3rem 0.875rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; }
    .ps-body { padding: 1.5rem; }
    .ps-row { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9; }
    .ps-row:last-child { border-bottom: none; }
    .ps-label { font-weight: 700; color: #64748b; font-size: 0.85rem; }
    .ps-value { font-weight: 700; color: #0f172a; font-size: 0.9rem; }
    .ps-section-title { font-size: 0.75rem; font-weight: 800; color: #2563eb; text-transform: uppercase; letter-spacing: 0.08em; margin: 1.5rem 0 0.75rem; padding-bottom: 0.5rem; border-bottom: 2px solid #eff6ff; }
    .ps-total { background: #eff6ff; padding: 1.25rem; border-radius: 0.75rem; margin-top: 1rem; display: flex; justify-content: space-between; align-items: center; }
    .ps-total strong { font-size: 1.25rem; color: #1e40af; }
    .ps-actions { padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; display: flex; gap: 0.75rem; }
    .ps-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; }
    .ps-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
    .ps-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .ps-btn-success { background: #10b981; color: #fff; }
    .ps-btn-warning { background: #f59e0b; color: #fff; }
    .ps-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
    .ps-badge.draft { background: #f1f5f9; color: #64748b; }
    .ps-badge.approved { background: #fef3c7; color: #b45309; }
    .ps-badge.paid { background: #dcfce7; color: #15803d; }
</style>

<div class="ps-page">
    <div class="ps-card">
        <div class="ps-hero">
            <h1>Payslip</h1>
            <span class="code">{{ $payslip->payslip_number }} ? {{ $payslip->period }}</span>
        </div>

        <div class="ps-body">
            <div class="ps-row">
                <span class="ps-label">Employee</span>
                <span class="ps-value">{{ $payslip->employee->first_name ?? '—' }} {{ $payslip->employee->last_name ?? '' }}</span>
            </div>
            <div class="ps-row">
                <span class="ps-label">Status</span>
                <span class="ps-value"><span class="ps-badge {{ $payslip->status }}">{{ $payslip->status }}</span></span>
            </div>

            <div class="ps-section-title">Earnings</div>
            <div class="ps-row">
                <span class="ps-label">Basic Salary</span>
                <span class="ps-value">{{ number_format($payslip->basic_salary, 2) }}</span>
            </div>
            @if($payslip->allowances_breakdown)
                @foreach($payslip->allowances_breakdown as $key => $val)
                    @if($val > 0)
                        <div class="ps-row">
                            <span class="ps-label">{{ ucfirst($key) }} Allowance</span>
                            <span class="ps-value">{{ number_format($val, 2) }}</span>
                        </div>
                    @endif
                @endforeach
            @endif
            <div class="ps-row">
                <span class="ps-label"><strong>Gross Salary</strong></span>
                <span class="ps-value"><strong>{{ number_format($payslip->gross_salary, 2) }}</strong></span>
            </div>

            <div class="ps-section-title">Deductions</div>
            @if($payslip->deductions_breakdown)
                @foreach($payslip->deductions_breakdown as $key => $val)
                    @if($val > 0)
                        <div class="ps-row">
                            <span class="ps-label">{{ ucfirst($key) }} Deduction</span>
                            <span class="ps-value" style="color:#dc2626;">- {{ number_format($val, 2) }}</span>
                        </div>
                    @endif
                @endforeach
            @endif
            <div class="ps-row">
                <span class="ps-label"><strong>Total Deductions</strong></span>
                <span class="ps-value" style="color:#dc2626;"><strong>- {{ number_format($payslip->total_deductions, 2) }}</strong></span>
            </div>

            <div class="ps-total">
                <span class="ps-label" style="color:#1e40af; font-size:0.95rem;">NET PAY</span>
                <strong>{{ number_format($payslip->net_salary, 2) }} {{ $payslip->salary->currency ?? 'TZS' }}</strong>
            </div>
        </div>

        <div class="ps-actions">
            @if($payslip->status === 'draft')
                <form action="{{ route('payroll.approve', $payslip) }}" method="POST">
                    @csrf
                    <button type="submit" class="ps-btn ps-btn-success"><i class="fas fa-check"></i> Approve</button>
                </form>
            @endif
            @if($payslip->status === 'approved')
                <form action="{{ route('payroll.paid', $payslip) }}" method="POST">
                    @csrf
                    <button type="submit" class="ps-btn ps-btn-success"><i class="fas fa-money-bill"></i> Mark as Paid</button>
                </form>
            @endif
            <a href="{{ route('payroll.index') }}" class="ps-btn ps-btn-secondary" style="margin-left:auto;"><i class="fas fa-arrow-left"></i> Back</a>
            <button onclick="window.print()" class="ps-btn ps-btn-primary"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>
</div>

    {{-- LOCATION --}}
    <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; overflow:hidden; margin-top:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <div style="padding:16px 24px; border-bottom:1px solid #f1f5f9; background:linear-gradient(135deg,#f8fafc,#f1f5f9); display:flex; align-items:center; gap:10px;">
            <div style="width:32px; height:32px; border-radius:10px; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px;">
                <i class="fas fa-map-location-dot"></i>
            </div>
            <h2 style="font-size:13px; font-weight:800; color:#0f172a; margin:0; text-transform:uppercase; letter-spacing:0.08em;">Location</h2>
        </div>
        <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px;">
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Region</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($payslip->region){{ $payslip->region->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">District</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($payslip->district){{ $payslip->district->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Ward</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($payslip->ward){{ $payslip->ward->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
        </div>
    </div>

@endsection

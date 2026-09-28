@extends('layouts.sapta')

@section('title', 'Payslip Verification')
@section('page-title', 'Payslip Verification')

@section('content')
<style>
    .vf-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .vf-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .vf-hero { padding: 2rem; text-align: center; }
    .vf-hero.verified { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
    .vf-hero.unverified { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .vf-hero.invalid { background: linear-gradient(135deg, #dc2626, #b91c1c); color: #fff; }
    .vf-hero h1 { font-size: 1.5rem; font-weight: 800; margin: 0 0 0.5rem; }
    .vf-hero .icon { font-size: 3rem; margin-bottom: 0.5rem; }
    .vf-hero .code { display: inline-block; background: rgba(255,255,255,0.2); padding: 0.3rem 0.875rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; }
    .vf-body { padding: 1.5rem; }
    .vf-row { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9; }
    .vf-row:last-child { border-bottom: none; }
    .vf-label { font-weight: 700; color: #64748b; font-size: 0.85rem; }
    .vf-value { font-weight: 700; color: #0f172a; font-size: 0.9rem; }
    .vf-section-title { font-size: 0.75rem; font-weight: 800; color: #2563eb; text-transform: uppercase; letter-spacing: 0.08em; margin: 1.5rem 0 0.75rem; padding-bottom: 0.5rem; border-bottom: 2px solid #eff6ff; }
    .vf-total { background: #eff6ff; padding: 1.25rem; border-radius: 0.75rem; margin-top: 1rem; display: flex; justify-content: space-between; align-items: center; }
    .vf-total strong { font-size: 1.25rem; color: #1e40af; }
    .vf-alert { padding: 1rem; border-radius: 0.75rem; margin-bottom: 1rem; display: flex; gap: 0.75rem; align-items: flex-start; }
    .vf-alert-warning { background: #fef3c7; color: #92400e; }
    .vf-alert-info { background: #dbeafe; color: #1e40af; }
    .vf-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
    .vf-badge.draft { background: #f1f5f9; color: #64748b; }
    .vf-badge.approved { background: #fef3c7; color: #b45309; }
    .vf-badge.paid { background: #dcfce7; color: #15803d; }
    .vf-actions { padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; display: flex; gap: 0.75rem; justify-content: flex-end; }
    .vf-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; }
    .vf-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
</style>

<div class="vf-page">
    @if(!$payslip)
        <div class="vf-card">
            <div class="vf-hero invalid">
                <div class="icon">❌</div>
                <h1>Invalid QR Code</h1>
                <p>QR code is invalid or has expired.</p>
            </div>
        </div>
    @else
        @if($canViewFull)
            <div class="vf-alert vf-alert-info">
                <span>✅</span>
                <div><strong>Full access granted.</strong> You can view the complete payslip details.</div>
            </div>
        @else
            <div class="vf-alert vf-alert-warning">
                <span>⚠️</span>
                <div><strong>Limited access.</strong> You are viewing a summary only. Log in as the employee, HR, or Finance to see full details.</div>
            </div>
        @endif

        <div class="vf-card">
            <div class="vf-hero {{ $canViewFull ? 'verified' : 'unverified' }}">
                <div class="icon">{{ $canViewFull ? '✅' : '⚠️' }}</div>
                <h1>{{ $canViewFull ? 'Payslip Verified' : 'Payslip Summary' }}</h1>
                <span class="code">{{ $payslip->payslip_number }} &bull; {{ $payslip->period }}</span>
            </div>

            <div class="vf-body">
                <div class="vf-row">
                    <span class="vf-label">Employee</span>
                    <span class="vf-value">{{ $payslip->employee->first_name ?? '' }} {{ $payslip->employee->last_name ?? '' }}</span>
                </div>
                <div class="vf-row">
                    <span class="vf-label">Status</span>
                    <span class="vf-value"><span class="vf-badge {{ $payslip->status }}">{{ $payslip->status }}</span></span>
                </div>
                <div class="vf-row">
                    <span class="vf-label">Period</span>
                    <span class="vf-value">{{ $payslip->period }}</span>
                </div>

                @if($canViewFull)
                    <div class="vf-section-title">Earnings</div>
                    <div class="vf-row">
                        <span class="vf-label">Basic Salary</span>
                        <span class="vf-value">{{ number_format($payslip->basic_salary, 2) }}</span>
                    </div>
                    @if($payslip->allowances_breakdown)
                        @foreach($payslip->allowances_breakdown as $key => $val)
                            @if($val > 0)
                                <div class="vf-row">
                                    <span class="vf-label">{{ ucfirst($key) }} Allowance</span>
                                    <span class="vf-value">{{ number_format($val, 2) }}</span>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    <div class="vf-section-title">Deductions</div>
                    @if($payslip->deductions_breakdown)
                        @foreach($payslip->deductions_breakdown as $key => $val)
                            @if($val > 0)
                                <div class="vf-row">
                                    <span class="vf-label">{{ ucfirst($key) }} Deduction</span>
                                    <span class="vf-value" style="color:#dc2626;">-{{ number_format($val, 2) }}</span>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    <div class="vf-total">
                        <span class="vf-label" style="font-size:1rem;">Net Pay</span>
                        <strong>{{ number_format($payslip->net_salary, 2) }} TZS</strong>
                    </div>
                @endif
            </div>

            <div class="vf-actions">
                <a href="{{ route('dashboard') }}" class="vf-btn vf-btn-primary">Back to Dashboard</a>
            </div>
        </div>
    @endif
</div>
@endsection

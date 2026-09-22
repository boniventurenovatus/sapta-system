@extends('layouts.reports')

@section('title', 'Payroll Report')
@section('report-title', 'Payroll Report')
@section('report-subtitle', 'All payslips and salaries')
@section('export-route', route('reports.export.payroll'))

@section('report-content')

<div class="rp-stats">
    <div class="rp-stat">
        <p class="rp-stat-label">Total Payslips</p>
        <p class="rp-stat-value">{{ $stats['total'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-file-invoice-dollar"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Gross Salary</p>
        <p class="rp-stat-value">{{ number_format($stats['gross'] ?? 0, 0) }}</p>
        <div class="rp-stat-icon"><i class="fas fa-money-bill"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Deductions</p>
        <p class="rp-stat-value" style="color:#dc3545;">{{ number_format($stats['deductions'] ?? 0, 0) }}</p>
        <div class="rp-stat-icon"><i class="fas fa-minus-circle"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Net Salary</p>
        <p class="rp-stat-value" style="color:#8b5cf6;">{{ number_format($stats['net'] ?? 0, 0) }}</p>
        <div class="rp-stat-icon"><i class="fas fa-money-bill-wave"></i></div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-card-head">
        <h2><i class="fas fa-list"></i> Payslips ({{ $payslips->count() ?? 0 }})</h2>
    </div>
    @if(($payslips->count() ?? 0) > 0)
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>Payslip #</th>
                        <th>Employee</th>
                        <th>Period</th>
                        <th>Gross</th>
                        <th>Net</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payslips as $p)
                    <tr>
                        <td><code>{{ $p->payslip_number ?? $p->id }}</code></td>
                        <td><strong>{{ $p->employee->first_name ?? '—' }} {{ $p->employee->last_name ?? '' }}</strong></td>
                        <td>{{ $p->period ?? '—' }}</td>
                        <td>{{ number_format($p->gross_salary ?? 0, 0) }}</td>
                        <td>{{ number_format($p->net_salary ?? 0, 0) }}</td>
                        <td><span class="rp-badge rp-badge-{{ $p->status ?? 'pending' }}">{{ ucfirst($p->status ?? 'pending') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rp-empty">
            <i class="fas fa-inbox"></i>
            <p>No payslips found.</p>
        </div>
    @endif
</div>

@endsection
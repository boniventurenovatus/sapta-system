@extends('layouts.sapta')

@section('title', 'Payroll Report')
@section('page-title', 'Payroll Report')

@section('content')
<style>
    .rpp-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .rpp-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .rpp-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .rpp-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .rpp-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .rpp-stat { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; }
    .rpp-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; display: flex; align-items: center; gap: 0.4rem; }
    .rpp-stat-value { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0.35rem 0 0; }
    .rpp-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .rpp-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .rpp-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .rpp-card-head h2 i { color: #10b981; }
    .rpp-card-body { padding: 1.25rem; }
    .rpp-filter { display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end; }
    .rpp-form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .rpp-input { width: 100%; padding: 0.6rem 0.75rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.88rem; }
    .rpp-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .rpp-btn-primary { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
    .rpp-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
</style>

<div class="rpp-page">
    <div class="rpp-head">
        <div>
            <h1>Payroll Report</h1>
            <p>Employee salary and payslip report.</p>
        </div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('reports.payroll.export') }}" class="rpp-btn rpp-btn-secondary" style="color:#0284c7;"><i class="fas fa-file-csv"></i> Export CSV</a>
            <a href="{{ route('reports.index') }}" class="rpp-btn rpp-btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <button onclick="window.print()" class="rpp-btn rpp-btn-primary"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>

    <div class="rpp-stats">
        <div class="rpp-stat"><p class="rpp-stat-label"><i class="fas fa-file-invoice-dollar" style="color:#2563eb;"></i> Total Payslips</p><p class="rpp-stat-value">{{ $stats['total'] }}</p></div>
        <div class="rpp-stat"><p class="rpp-stat-label"><i class="fas fa-money-bill" style="color:#10b981;"></i> Gross Salary</p><p class="rpp-stat-value">{{ number_format($stats['gross'], 0) }}</p></div>
        <div class="rpp-stat"><p class="rpp-stat-label"><i class="fas fa-minus-circle" style="color:#dc2626;"></i> Deductions</p><p class="rpp-stat-value">{{ number_format($stats['deductions'], 0) }}</p></div>
        <div class="rpp-stat"><p class="rpp-stat-label"><i class="fas fa-money-bill-wave" style="color:#8b5cf6;"></i> Net Salary</p><p class="rpp-stat-value">{{ number_format($stats['net'], 0) }}</p></div>
    </div>

    <div class="rpp-card">
        <div class="rpp-card-head"><h2><i class="fas fa-list"></i> Payslips <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $payslips->count() }})</span></h2></div>
        @if($payslips->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-code">Payslip #</th>
                            <th class="col-title">Employee</th>
                            <th style="width:120px;">Period</th>
                            <th style="width:120px;">Gross</th>
                            <th style="width:120px;">Deductions</th>
                            <th style="width:120px;">Net</th>
                            <th class="col-status">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payslips as $p)
                            <tr>
                                <td class="col-code"><code>{{ $p->payslip_number }}</code></td>
                                <td class="col-title"><strong>{{ $p->employee->first_name ?? '—' }} {{ $p->employee->last_name ?? '' }}</strong></td>
                                <td>{{ $p->period }}</td>
                                <td>{{ number_format($p->gross_salary, 0) }}</td>
                                <td style="color:#dc2626;">- {{ number_format($p->total_deductions, 0) }}</td>
                                <td><strong style="color:#059669;">{{ number_format($p->net_salary, 0) }}</strong></td>
                                <td class="col-status"><span class="badge badge-{{ $p->status === 'paid' ? 'success' : ($p->status === 'approved' ? 'warning' : 'secondary') }}">{{ ucfirst($p->status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align:center; padding:3rem 1rem; color:#94a3b8;">
                <i class="fas fa-file-invoice-dollar" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
                No payslips yet.
            </div>
        @endif
    </div>
</div>
@endsection

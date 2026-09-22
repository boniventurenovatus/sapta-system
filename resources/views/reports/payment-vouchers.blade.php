@extends('layouts.sapta')

@section('title', 'Payment Vouchers Report')
@section('page-title', 'Payment Vouchers Report')

@section('content')
<style>
    .rpv-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .rpv-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .rpv-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .rpv-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .rpv-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .rpv-stat { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; }
    .rpv-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; display: flex; align-items: center; gap: 0.4rem; }
    .rpv-stat-value { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0.35rem 0 0; }
    .rpv-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .rpv-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .rpv-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .rpv-card-head h2 i { color: #2563eb; }
    .rpv-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .rpv-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
    .rpv-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
</style>

@include('reports.partials.location-filter', ['action' => route('reports.payment-vouchers')])

<div class="rpv-page">
    <div class="rpv-head">
        <div>
            <h1>Payment Vouchers Report</h1>
            <p>All payment vouchers summary.</p>
        </div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('reports.index') }}" class="rpv-btn rpv-btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        <a href="{{ route('reports.export.vouchers') }}" style="padding:0.6rem 1rem; background:#10b981; color:#fff; border:none; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-file-csv"></i> CSV</a>
        <button onclick="window.print()" style="padding:0.6rem 1rem; background:#3b82f6; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>

    <div class="rpv-stats">
        <div class="rpv-stat"><p class="rpv-stat-label"><i class="fas fa-file-invoice" style="color:#2563eb;"></i> Total</p><p class="rpv-stat-value">{{ $stats['total'] }}</p></div>
        <div class="rpv-stat"><p class="rpv-stat-label"><i class="fas fa-clock" style="color:#f59e0b;"></i> Pending</p><p class="rpv-stat-value">{{ $stats['pending'] }}</p></div>
        <div class="rpv-stat"><p class="rpv-stat-label"><i class="fas fa-circle-check" style="color:#0ea5e9;"></i> Approved</p><p class="rpv-stat-value">{{ $stats['approved'] }}</p></div>
        <div class="rpv-stat"><p class="rpv-stat-label"><i class="fas fa-money-bill-wave" style="color:#10b981;"></i> Paid</p><p class="rpv-stat-value">{{ $stats['paid'] }}</p></div>
        <div class="rpv-stat"><p class="rpv-stat-label"><i class="fas fa-coins" style="color:#8b5cf6;"></i> Total Amount</p><p class="rpv-stat-value">{{ number_format($stats['total_amount'], 0) }}</p></div>
    </div>

    <div class="rpv-card">
        <div class="rpv-card-head"><h2><i class="fas fa-list"></i> Vouchers ({{ $vouchers->count() }})</h2></div>
        @if($vouchers->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-code">Voucher #</th>
                            <th class="col-title">Payee</th>
                            <th style="width:110px;">Type</th>
                            <th style="width:120px;">Date</th>
                            <th style="width:130px;">Amount</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vouchers as $v)
                            <tr>
                                <td class="col-code"><code>{{ $v->voucher_number }}</code></td>
                                <td class="col-title"><strong>{{ $v->payee_name }}</strong></td>
                                <td>{{ ucfirst($v->payee_type) }}</td>
                                <td>{{ $v->voucher_date->format('M d, Y') }}</td>
                                <td><strong>{{ number_format($v->amount, 0) }} {{ $v->currency }}</strong></td>
                                <td class="col-status"><span class="badge badge-{{ $v->status_color }}">{{ ucfirst($v->status) }}</span></td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="/payment-vouchers/{{ $v->id }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                        <a href="/payment-vouchers/{{ $v->id }}/edit" class="action-btn edit"><i class="fas fa-pen"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align:center; padding:3rem 1rem; color:#94a3b8;">
                <i class="fas fa-file-invoice" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i>
                No vouchers yet.
            </div>
        @endif
    </div>
</div>
@endsection



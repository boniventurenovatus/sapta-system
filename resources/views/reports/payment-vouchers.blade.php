@extends('layouts.reports')

@section('title', 'Payment Vouchers Report')
@section('report-title', 'Payment Vouchers Report')
@section('report-subtitle', 'All payment vouchers')
@section('export-route', route('reports.export.vouchers'))

@section('report-content')

<div class="rp-stats">
    <div class="rp-stat">
        <p class="rp-stat-label">Total</p>
        <p class="rp-stat-value">{{ $stats['total'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-file-invoice"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Pending</p>
        <p class="rp-stat-value" style="color:#ffc107;">{{ $stats['pending'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-clock"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Approved</p>
        <p class="rp-stat-value" style="color:#3b82f6;">{{ $stats['approved'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-check"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Paid</p>
        <p class="rp-stat-value" style="color:#28a745;">{{ $stats['paid'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-money-bill-wave"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Total Amount</p>
        <p class="rp-stat-value">{{ number_format($stats['total_amount'] ?? 0, 0) }}</p>
        <div class="rp-stat-icon"><i class="fas fa-coins"></i></div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-card-head">
        <h2><i class="fas fa-list"></i> Vouchers ({{ $vouchers->count() ?? 0 }})</h2>
    </div>
    @if(($vouchers->count() ?? 0) > 0)
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>Voucher #</th>
                        <th>Payee</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vouchers as $v)
                    <tr>
                        <td><code>{{ $v->voucher_number ?? $v->id }}</code></td>
                        <td><strong>{{ $v->payee_name ?? '—' }}</strong></td>
                        <td>{{ isset($v->voucher_date) ? \Carbon\Carbon::parse($v->voucher_date)->format('M d, Y') : '—' }}</td>
                        <td><strong>{{ number_format($v->amount ?? 0, 0) }} {{ $v->currency ?? 'TZS' }}</strong></td>
                        <td><span class="rp-badge rp-badge-{{ $v->status ?? 'pending' }}">{{ ucfirst($v->status ?? 'pending') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rp-empty">
            <i class="fas fa-inbox"></i>
            <p>No vouchers found.</p>
        </div>
    @endif
</div>

@endsection
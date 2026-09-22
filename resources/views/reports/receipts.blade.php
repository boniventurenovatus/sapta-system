@extends('layouts.reports')

@section('title', 'Receipts Report')
@section('report-title', 'Receipts Report')
@section('report-subtitle', 'All payment receipts')
@section('export-route', route('reports.export.receipts'))

@section('report-content')

<div class="rp-stats">
    <div class="rp-stat">
        <p class="rp-stat-label">Total Receipts</p>
        <p class="rp-stat-value">{{ $stats['total'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-receipt"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Confirmed</p>
        <p class="rp-stat-value">{{ $stats['confirmed'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-circle-check"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Total Amount</p>
        <p class="rp-stat-value">{{ number_format($stats['total_amount'] ?? 0, 0) }}</p>
        <div class="rp-stat-icon"><i class="fas fa-coins"></i></div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-card-head">
        <h2><i class="fas fa-list"></i> Receipts ({{ $receipts->count() ?? 0 }})</h2>
    </div>
    @if(($receipts->count() ?? 0) > 0)
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>Receipt #</th>
                        <th>Payer</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipts as $r)
                    <tr>
                        <td><code>{{ $r->receipt_number ?? $r->id }}</code></td>
                        <td><strong>{{ $r->payer_name ?? '—' }}</strong></td>
                        <td>{{ ucfirst($r->payer_type ?? '—') }}</td>
                        <td>{{ isset($r->receipt_date) ? \Carbon\Carbon::parse($r->receipt_date)->format('M d, Y') : '—' }}</td>
                        <td><strong>{{ number_format($r->amount ?? 0, 0) }} {{ $r->currency ?? 'TZS' }}</strong></td>
                        <td><span class="rp-badge rp-badge-{{ $r->status ?? 'pending' }}">{{ ucfirst($r->status ?? 'pending') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rp-empty">
            <i class="fas fa-inbox"></i>
            <p>No receipts found.</p>
        </div>
    @endif
</div>

@endsection
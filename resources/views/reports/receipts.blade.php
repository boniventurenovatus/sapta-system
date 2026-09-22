@extends('layouts.sapta')
@section('title', 'Receipts Report')
@section('page-title', 'Receipts Report')
@section('content')
<div style="padding:1.5rem; max-width:1500px; margin:0 auto;">

    @include('reports.partials.location-filter', ['action' => route('reports.receipts')])
    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap;">
        <div><h1 style="font-size:1.75rem; font-weight:800; margin:0 0 0.25rem;">Receipts Report</h1><p style="color:#64748b; margin:0;">All receipts summary.</p></div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('reports.index') }}" style="padding:0.6rem 1rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-arrow-left"></i> Back</a>
        <a href="{{ route('reports.export.receipts') }}" style="padding:0.6rem 1rem; background:#10b981; color:#fff; border:none; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-file-csv"></i> CSV</a>
        <button onclick="window.print()" style="padding:0.6rem 1rem; background:#3b82f6; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-receipt" style="color:#0ea5e9;"></i> Total</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['total'] }}</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-circle-check" style="color:#10b981;"></i> Confirmed</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['confirmed'] }}</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-coins" style="color:#8b5cf6;"></i> Total Amount</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ number_format($stats['total_amount'], 0) }}</p></div>
    </div>
    <div style="background:#fff; border-radius:0.875rem; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; background:#fafbfc;"><h2 style="font-size:0.95rem; font-weight:700; margin:0;"><i class="fas fa-list" style="color:#0ea5e9;"></i> Receipts ({{ $receipts->count() }})</h2></div>
        @if($receipts->count() > 0)
            <div class="sapta-table-wrap"><table class="sapta-table"><thead><tr><th>Receipt #</th><th>Payer</th><th>Type</th><th>Date</th><th>Amount</th><th>Status</th></tr></thead><tbody>
                @foreach($receipts as $r)
                    <tr><td><code>{{ $r->receipt_number }}</code></td><td><strong>{{ $r->payer_name }}</strong></td><td>{{ ucfirst($r->payer_type) }}</td><td>{{ $r->receipt_date->format('M d, Y') }}</td><td><strong>{{ number_format($r->amount, 0) }} {{ $r->currency }}</strong></td><td><span class="badge badge-{{ $r->status_color }}">{{ ucfirst($r->status) }}</span></td></tr>
                @endforeach
            </tbody></table></div>
        @else
            <div style="text-align:center; padding:3rem 1rem; color:#94a3b8;"><i class="fas fa-receipt" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i> No receipts yet.</div>
        @endif
    </div>
</div>
@endsection

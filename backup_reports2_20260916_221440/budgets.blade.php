@extends('layouts.sapta')
@section('title', 'Budgets Report')
@section('page-title', 'Budgets Report')
@section('content')
<div style="padding:1.5rem; max-width:1500px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap;">
        <div><h1 style="font-size:1.75rem; font-weight:800; margin:0 0 0.25rem;">Budgets Report</h1><p style="color:#64748b; margin:0;">Budget utilization report.</p></div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('reports.budgets.export') }}" style="padding:0.6rem 1rem; background:#fff; color:#0284c7; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-file-csv"></i> Export CSV</a>
            <a href="{{ route('reports.index') }}" style="padding:0.6rem 1rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-arrow-left"></i> Back</a>
            <button onclick="window.print()" style="padding:0.6rem 1rem; background:#8b5cf6; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-chart-pie" style="color:#8b5cf6;"></i> Total</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['total'] }}</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-money-bill" style="color:#2563eb;"></i> Allocated</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ number_format($stats['allocated'], 0) }}</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-minus-circle" style="color:#dc2626;"></i> Spent</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ number_format($stats['spent'], 0) }}</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-coins" style="color:#10b981;"></i> Remaining</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ number_format($stats['remaining'], 0) }}</p></div>
    </div>
    <div style="background:#fff; border-radius:0.875rem; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; background:#fafbfc;"><h2 style="font-size:0.95rem; font-weight:700; margin:0;"><i class="fas fa-list" style="color:#8b5cf6;"></i> Budgets ({{ $budgets->count() }})</h2></div>
        @if($budgets->count() > 0)
            <div class="sapta-table-wrap"><table class="sapta-table"><thead><tr><th>Budget #</th><th>Name</th><th>Year</th><th>Allocated</th><th>Spent</th><th>Remaining</th><th>Status</th></tr></thead><tbody>
                @foreach($budgets as $b)
                    <tr><td><code>{{ $b->budget_number }}</code></td><td><strong>{{ $b->name }}</strong></td><td>{{ $b->fiscal_year }}</td><td>{{ number_format($b->allocated_amount, 0) }}</td><td style="color:#dc2626;">- {{ number_format($b->spent_amount, 0) }}</td><td style="color:#059669;"><strong>{{ number_format($b->remaining_amount, 0) }}</strong></td><td><span class="badge badge-{{ $b->status_color }}">{{ ucfirst($b->status) }}</span></td></tr>
                @endforeach
            </tbody></table></div>
        @else
            <div style="text-align:center; padding:3rem 1rem; color:#94a3b8;"><i class="fas fa-chart-pie" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i> No budgets yet.</div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.sapta')
@section('title', 'Performance Report')
@section('page-title', 'Performance Report')
@section('content')
<div style="padding:1.5rem; max-width:1500px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap;">
        <div><h1 style="font-size:1.75rem; font-weight:800; margin:0 0 0.25rem;">Performance Report</h1><p style="color:#64748b; margin:0;">Employee performance reviews.</p></div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('reports.performance.export') }}" style="padding:0.6rem 1rem; background:#fff; color:#0284c7; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-file-csv"></i> CSV</a>
            <a href="{{ route('reports.index') }}" style="padding:0.6rem 1rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;"><i class="fas fa-arrow-left"></i> Back</a>
            <button onclick="window.print()" style="padding:0.6rem 1rem; background:#f59e0b; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-star" style="color:#f59e0b;"></i> Total</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['total'] }}</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-chart-line" style="color:#8b5cf6;"></i> Avg Rating</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ number_format($stats['avg_rating'], 1) }}/5</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-clock" style="color:#94a3b8;"></i> Draft</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['draft'] }}</p></div>
        <div style="background:#fff; border-radius:0.875rem; padding:1.25rem; border:1px solid #e2e8f0;"><p style="font-size:0.72rem; color:#64748b; font-weight:800; text-transform:uppercase; margin:0;"><i class="fas fa-check-circle" style="color:#10b981;"></i> Approved</p><p style="font-size:1.5rem; font-weight:800; margin:0.35rem 0 0;">{{ $stats['approved'] }}</p></div>
    </div>
    <div style="background:#fff; border-radius:0.875rem; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; background:#fafbfc;"><h2 style="font-size:0.95rem; font-weight:700; margin:0;"><i class="fas fa-list" style="color:#f59e0b;"></i> Reviews ({{ $reviews->count() }})</h2></div>
        @if($reviews->count() > 0)
            <div class="sapta-table-wrap"><table class="sapta-table"><thead><tr><th>Review #</th><th>Employee</th><th>Period</th><th>Rating</th><th>Date</th><th>Status</th></tr></thead><tbody>
                @foreach($reviews as $r)
                    <tr><td><code>{{ $r->review_number }}</code></td><td><strong>{{ $r->employee->first_name ?? '—' }} {{ $r->employee->last_name ?? '' }}</strong></td><td>{{ $r->review_period }}</td><td><strong>{{ number_format($r->overall_rating, 1) }}</strong></td><td>{{ $r->review_date->format('M d, Y') }}</td><td><span class="badge badge-{{ $r->status === 'approved' ? 'success' : ($r->status === 'submitted' ? 'warning' : 'secondary') }}">{{ ucfirst($r->status) }}</span></td></tr>
                @endforeach
            </tbody></table></div>
        @else
            <div style="text-align:center; padding:3rem 1rem; color:#94a3b8;"><i class="fas fa-star" style="font-size:2rem; display:block; margin-bottom:0.5rem;"></i> No reviews yet.</div>
        @endif
    </div>
</div>
@endsection

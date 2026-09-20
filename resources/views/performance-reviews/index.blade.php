@extends('layouts.sapta')

@section('title', 'Performance Reviews')
@section('page-title', 'Performance Reviews')

@section('content')
<style>
    .pr-page { padding: 1.5rem; max-width: 1500px; margin: 0 auto; }
    .pr-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .pr-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .pr-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .pr-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .pr-stat { background: #fff; border-radius: 0.875rem; padding: 1.25rem; border: 1px solid #e2e8f0; }
    .pr-stat-label { font-size: 0.72rem; color: #64748b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; margin: 0; display: flex; align-items: center; gap: 0.4rem; }
    .pr-stat-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0.35rem 0 0; }
    .pr-card { background: #fff; border-radius: 0.875rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .pr-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .pr-card-head h2 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .pr-card-head h2 i { color: #f59e0b; }
    .pr-card-body { padding: 1.25rem; }
    .pr-filter { display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: end; }
    @media (max-width: 1024px) { .pr-filter { grid-template-columns: 1fr 1fr; } }
    .pr-form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .pr-input-wrap { position: relative; }
    .pr-input-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; pointer-events: none; }
    .pr-input { width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.88rem; }
    .pr-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .pr-btn-primary { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .pr-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .pr-alert { padding: 0.75rem 1rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1rem; }
    .pr-rating { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.25rem 0.6rem; border-radius: 999px; font-size: 0.75rem; font-weight: 800; }
    .pr-rating.success { background: #dcfce7; color: #15803d; }
    .pr-rating.info { background: #dbeafe; color: #1e40af; }
    .pr-rating.primary { background: #ede9fe; color: #6d28d9; }
    .pr-rating.warning { background: #fef3c7; color: #b45309; }
    .pr-rating.danger { background: #fee2e2; color: #991b1b; }
</style>

<div class="pr-page">
    <div class="pr-head">
        <div>
            <h1>Performance Reviews</h1>
            <p>Manage employee performance evaluations.</p>
        </div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('performance-reviews.export.all-excel') }}" class="pr-btn pr-btn-secondary" style="color:#059669;"><i class="fas fa-file-excel"></i> Excel</a>
            <a href="{{ route('performance-reviews.export.all-csv') }}" class="pr-btn pr-btn-secondary" style="color:#0284c7;"><i class="fas fa-file-csv"></i> CSV</a>
            <a href="{{ route('performance-reviews.create') }}" class="pr-btn pr-btn-primary"><i class="fas fa-plus"></i> New Review</a>
        </div>
    </div>

    

    <div class="pr-stats">
        <div class="pr-stat"><p class="pr-stat-label"><i class="fas fa-star" style="color:#f59e0b;"></i> Total</p><p class="pr-stat-value">{{ $stats['total'] }}</p></div>
        <div class="pr-stat"><p class="pr-stat-label"><i class="fas fa-clock" style="color:#94a3b8;"></i> Draft</p><p class="pr-stat-value">{{ $stats['draft'] }}</p></div>
        <div class="pr-stat"><p class="pr-stat-label"><i class="fas fa-paper-plane" style="color:#0ea5e9;"></i> Submitted</p><p class="pr-stat-value">{{ $stats['submitted'] }}</p></div>
        <div class="pr-stat"><p class="pr-stat-label"><i class="fas fa-check-circle" style="color:#10b981;"></i> Approved</p><p class="pr-stat-value">{{ $stats['approved'] }}</p></div>
        <div class="pr-stat"><p class="pr-stat-label"><i class="fas fa-chart-line" style="color:#8b5cf6;"></i> Avg Rating</p><p class="pr-stat-value">{{ number_format($stats['average_rating'], 1) }}/5</p></div>
    </div>

    <div class="pr-card">
        <div class="pr-card-head"><h2><i class="fas fa-filter"></i> Search & Filter</h2></div>
        <div class="pr-card-body">
            <form method="GET" action="{{ route('performance-reviews.index') }}" class="pr-filter">
                <div class="pr-form-group">
                    <label>Search</label>
                    <div class="pr-input-wrap"><i class="fas fa-magnifying-glass"></i><input type="text" name="search" class="pr-input" value="{{ request('search') }}" placeholder="Review #, employee..."></div>
                </div>
                <div class="pr-form-group">
                    <label>Period</label>
                    <div class="pr-input-wrap"><i class="fas fa-calendar"></i><select name="review_period" class="pr-input"><option value="">All Periods</option>@foreach($periods as $p)<option value="{{ $p }}" @selected(request('review_period') === $p)>{{ $p }}</option>@endforeach</select></div>
                </div>
                <div class="pr-form-group">
                    <label>Status</label>
                    <div class="pr-input-wrap"><i class="fas fa-toggle-on"></i><select name="status" class="pr-input"><option value="">All Status</option><option value="draft" @selected(request('status') === 'draft')>Draft</option><option value="submitted" @selected(request('status') === 'submitted')>Submitted</option><option value="approved" @selected(request('status') === 'approved')>Approved</option><option value="rejected" @selected(request('status') === 'rejected')>Rejected</option></select></div>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <button type="submit" class="pr-btn pr-btn-primary"><i class="fas fa-magnifying-glass"></i></button>
                    <a href="{{ route('performance-reviews.index') }}" class="pr-btn pr-btn-secondary"><i class="fas fa-rotate-left"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="pr-card">
        <div class="pr-card-head"><h2><i class="fas fa-list"></i> Reviews <span style="color:#64748b; font-weight:500; margin-left:0.5rem;">({{ $reviews->total() }})</span></h2></div>
        @if($reviews->count() > 0)
            <div class="sapta-table-wrap">
                <table class="sapta-table">
                    <thead>
                        <tr>
                            <th class="col-code">Review #</th>
                            <th class="col-title">Employee</th>
                            <th style="width:120px;">Period</th>
                            <th style="width:120px;">Date</th>
                            <th style="width:120px;">Rating</th>
                            <th class="col-status">Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $r)
                            <tr>
                                <td class="col-code"><code>{{ $r->review_number }}</code></td>
                                <td class="col-title"><strong>{{ $r->employee->first_name ?? '—' }} {{ $r->employee->last_name ?? '' }}</strong></td>
                                <td>{{ $r->review_period }}</td>
                                <td>{{ $r->review_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="pr-rating {{ $r->rating_color }}">
                                        <i class="fas fa-star"></i> {{ number_format($r->overall_rating, 1) }}
                                    </span>
                                </td>
                                <td class="col-status"><span class="badge badge-{{ $r->status === 'approved' ? 'success' : ($r->status === 'submitted' ? 'warning' : 'secondary') }}">{{ ucfirst($r->status) }}</span></td>
                                <td class="col-actions">
                                    <div class="actions">
                                        <a href="{{ route('performance-reviews.show', $r) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('performance-reviews.edit', $r) }}" class="action-btn edit"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('performance-reviews.destroy', $r) }}" method="POST" class="sapta-delete-form" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($reviews->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $reviews->links() }}</div>
            @endif
        @else
            <div style="text-align:center; padding:3rem 1rem;">
                <div style="width:4rem; height:4rem; margin:0 auto 1rem; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#94a3b8;"><i class="fas fa-star"></i></div>
                <h3 style="font-size:1.1rem; color:#1e293b; margin:0 0 0.5rem;">No reviews yet</h3>
                <p style="color:#64748b; margin:0 0 1.25rem;">Create your first performance review.</p>
                <a href="{{ route('performance-reviews.create') }}" class="pr-btn pr-btn-primary"><i class="fas fa-plus"></i> New Review</a>
            </div>
        @endif
    </div>
</div>
@endsection

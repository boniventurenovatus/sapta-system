@extends('layouts.reports')

@section('title', 'Performance Report')
@section('report-title', 'Performance Report')
@section('report-subtitle', 'Employee performance reviews')
@section('export-route', route('reports.export.performance'))

@section('report-content')

<div class="rp-stats">
    <div class="rp-stat">
        <p class="rp-stat-label">Total Reviews</p>
        <p class="rp-stat-value">{{ $stats['total'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-star"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Avg Rating</p>
        <p class="rp-stat-value">{{ number_format($stats['avg_rating'] ?? 0, 1) }}/5</p>
        <div class="rp-stat-icon"><i class="fas fa-chart-line"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Draft</p>
        <p class="rp-stat-value">{{ $stats['draft'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-clock"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Approved</p>
        <p class="rp-stat-value">{{ $stats['approved'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-check-circle"></i></div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-card-head">
        <h2><i class="fas fa-list"></i> Reviews ({{ $reviews->count() ?? 0 }})</h2>
    </div>
    @if(($reviews->count() ?? 0) > 0)
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>Review #</th>
                        <th>Employee</th>
                        <th>Period</th>
                        <th>Rating</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $r)
                    <tr>
                        <td><code>{{ $r->review_number ?? $r->id }}</code></td>
                        <td><strong>{{ $r->employee->first_name ?? '' }} {{ $r->employee->last_name ?? '' }}</strong></td>
                        <td>{{ $r->review_period ?? '—' }}</td>
                        <td><strong>{{ number_format($r->overall_rating ?? 0, 1) }}</strong></td>
                        <td>{{ isset($r->review_date) ? \Carbon\Carbon::parse($r->review_date)->format('M d, Y') : '—' }}</td>
                        <td><span class="rp-badge rp-badge-{{ $r->status ?? 'draft' }}">{{ ucfirst($r->status ?? 'draft') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rp-empty">
            <i class="fas fa-inbox"></i>
            <p>No performance reviews found.</p>
        </div>
    @endif
</div>

@endsection
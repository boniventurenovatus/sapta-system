@extends('layouts.reports')

@section('title', 'Budgets Report')
@section('report-title', 'Budgets Report')
@section('report-subtitle', 'All budget allocations and utilization')
@section('export-route', route('reports.export.budgets'))

@section('report-content')

<div class="rp-stats">
    <div class="rp-stat">
        <p class="rp-stat-label">Total Budgets</p>
        <p class="rp-stat-value">{{ $stats['total'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-chart-pie"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Allocated</p>
        <p class="rp-stat-value">{{ number_format($stats['allocated'] ?? 0, 0) }}</p>
        <div class="rp-stat-icon"><i class="fas fa-money-bill"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Spent</p>
        <p class="rp-stat-value">{{ number_format($stats['spent'] ?? 0, 0) }}</p>
        <div class="rp-stat-icon"><i class="fas fa-minus-circle"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Remaining</p>
        <p class="rp-stat-value">{{ number_format($stats['remaining'] ?? 0, 0) }}</p>
        <div class="rp-stat-icon"><i class="fas fa-coins"></i></div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-card-head">
        <h2><i class="fas fa-list"></i> Budgets ({{ $budgets->count() ?? 0 }})</h2>
    </div>
    @if(($budgets->count() ?? 0) > 0)
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>Budget #</th>
                        <th>Name</th>
                        <th>Year</th>
                        <th>Allocated</th>
                        <th>Spent</th>
                        <th>Remaining</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($budgets as $b)
                    <tr>
                        <td><code>{{ $b->budget_number ?? $b->id }}</code></td>
                        <td><strong>{{ $b->name ?? '—' }}</strong></td>
                        <td>{{ $b->fiscal_year ?? '—' }}</td>
                        <td>{{ number_format($b->allocated_amount ?? 0, 0) }}</td>
                        <td style="color:#dc2626;">- {{ number_format($b->spent_amount ?? 0, 0) }}</td>
                        <td style="color:#059669;"><strong>{{ number_format(($b->allocated_amount ?? 0) - ($b->spent_amount ?? 0), 0) }}</strong></td>
                        <td><span class="rp-badge rp-badge-{{ $b->status ?? 'pending' }}">{{ ucfirst($b->status ?? 'pending') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rp-empty">
            <i class="fas fa-inbox"></i>
            <p>No budgets found.</p>
        </div>
    @endif
</div>

@endsection
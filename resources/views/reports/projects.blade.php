@extends('layouts.reports')

@section('title', 'Projects Report')
@section('report-title', 'Projects Report')
@section('report-subtitle', 'All projects overview')
@section('export-route', route('reports.export.projects'))

@section('report-content')

<div class="rp-stats">
    <div class="rp-stat">
        <p class="rp-stat-label">Total Projects</p>
        <p class="rp-stat-value">{{ $stats['total'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-diagram-project"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Active</p>
        <p class="rp-stat-value" style="color:#28a745;">{{ $stats['active'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-play"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Completed</p>
        <p class="rp-stat-value" style="color:#3b82f6;">{{ $stats['completed'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-check"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Pending</p>
        <p class="rp-stat-value" style="color:#ffc107;">{{ $stats['pending'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-clock"></i></div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-card-head">
        <h2><i class="fas fa-list"></i> Project List ({{ $records->count() ?? 0 }})</h2>
    </div>
    @if(($records->count() ?? 0) > 0)
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Organization</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Budget</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td><strong>{{ $p->name ?? '—' }}</strong></td>
                        <td>{{ $p->organization->name ?? '—' }}</td>
                        <td><span class="rp-badge rp-badge-{{ $p->status ?? 'pending' }}">{{ ucfirst(str_replace('_',' ', $p->status ?? 'pending')) }}</span></td>
                        <td>{{ $p->progress ?? 0 }}%</td>
                        <td>{{ number_format($p->budget ?? 0, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rp-empty">
            <i class="fas fa-inbox"></i>
            <p>No projects found.</p>
        </div>
    @endif
</div>

@endsection
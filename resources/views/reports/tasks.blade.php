@extends('layouts.reports')

@section('title', 'Tasks Report')
@section('report-title', 'Tasks Report')
@section('report-subtitle', 'All task status')
@section('export-route', route('reports.export.tasks'))

@section('report-content')

<div class="rp-stats">
    <div class="rp-stat">
        <p class="rp-stat-label">Total Tasks</p>
        <p class="rp-stat-value">{{ $stats['total'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-list-check"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Pending</p>
        <p class="rp-stat-value" style="color:#ffc107;">{{ $stats['pending'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-clock"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">In Progress</p>
        <p class="rp-stat-value" style="color:#3b82f6;">{{ $stats['in_progress'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-spinner"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Completed</p>
        <p class="rp-stat-value" style="color:#28a745;">{{ $stats['completed'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-check"></i></div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-card-head">
        <h2><i class="fas fa-list"></i> Task List ({{ $records->count() ?? 0 }})</h2>
    </div>
    @if(($records->count() ?? 0) > 0)
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Assigned To</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $t)
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td><strong>{{ $t->title ?? '—' }}</strong></td>
                        <td>{{ $t->assignee->name ?? '—' }}</td>
                        <td><span class="rp-badge rp-badge-{{ $t->priority ?? 'pending' }}">{{ ucfirst($t->priority ?? '—') }}</span></td>
                        <td>{{ isset($t->due_date) ? \Carbon\Carbon::parse($t->due_date)->format('M d, Y') : '—' }}</td>
                        <td><span class="rp-badge rp-badge-{{ $t->status ?? 'pending' }}">{{ ucfirst(str_replace('_',' ', $t->status ?? 'pending')) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rp-empty">
            <i class="fas fa-inbox"></i>
            <p>No tasks found.</p>
        </div>
    @endif
</div>

@endsection
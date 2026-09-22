@extends('layouts.reports')

@section('title', 'Leaves Report')
@section('report-title', 'Leaves Report')
@section('report-subtitle', 'All leave requests')
@section('export-route', route('reports.export.leaves'))

@section('report-content')

<div class="rp-stats">
    <div class="rp-stat">
        <p class="rp-stat-label">Total</p>
        <p class="rp-stat-value">{{ $stats['total'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-calendar-check"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Pending</p>
        <p class="rp-stat-value" style="color:#ffc107;">{{ $stats['pending'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-clock"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Approved</p>
        <p class="rp-stat-value" style="color:#28a745;">{{ $stats['approved'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-check"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Rejected</p>
        <p class="rp-stat-value" style="color:#dc3545;">{{ $stats['rejected'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-times"></i></div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-card-head">
        <h2><i class="fas fa-list"></i> Leave Requests ({{ $records->count() ?? 0 }})</h2>
    </div>
    @if(($records->count() ?? 0) > 0)
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Days</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td><strong>{{ $r->employee->first_name ?? '—' }} {{ $r->employee->last_name ?? '' }}</strong></td>
                        <td>{{ ucfirst(str_replace('_',' ', $r->leave_type ?? '—')) }}</td>
                        <td>{{ isset($r->start_date) ? \Carbon\Carbon::parse($r->start_date)->format('M d, Y') : '—' }}</td>
                        <td>{{ isset($r->end_date) ? \Carbon\Carbon::parse($r->end_date)->format('M d, Y') : '—' }}</td>
                        <td>{{ $r->days ?? '—' }}</td>
                        <td><span class="rp-badge rp-badge-{{ $r->status ?? 'pending' }}">{{ ucfirst($r->status ?? 'pending') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rp-empty">
            <i class="fas fa-inbox"></i>
            <p>No leave requests found.</p>
        </div>
    @endif
</div>

@endsection
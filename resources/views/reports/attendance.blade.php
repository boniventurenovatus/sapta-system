@extends('layouts.reports')

@section('title', 'Attendance Report')
@section('report-title', 'Attendance Report')
@section('report-subtitle', 'All attendance records')
@section('export-route', route('reports.export.attendance'))

@section('report-content')

<div class="rp-stats">
    <div class="rp-stat">
        <p class="rp-stat-label">Total Records</p>
        <p class="rp-stat-value">{{ $stats['total'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-clock"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Present</p>
        <p class="rp-stat-value" style="color:#28a745;">{{ $stats['present'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-check"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Absent</p>
        <p class="rp-stat-value" style="color:#dc3545;">{{ $stats['absent'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-times"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Late</p>
        <p class="rp-stat-value" style="color:#ffc107;">{{ $stats['late'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-hourglass"></i></div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-card-head">
        <h2><i class="fas fa-list"></i> Attendance Records ({{ $records->count() ?? 0 }})</h2>
    </div>
    @if(($records->count() ?? 0) > 0)
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td><strong>{{ $r->employee->first_name ?? '—' }} {{ $r->employee->last_name ?? '' }}</strong></td>
                        <td>{{ isset($r->attendance_date) ? \Carbon\Carbon::parse($r->attendance_date)->format('M d, Y') : '—' }}</td>
                        <td>{{ $r->check_in ?? '—' }}</td>
                        <td>{{ $r->check_out ?? '—' }}</td>
                        <td><span class="rp-badge rp-badge-{{ $r->status ?? 'pending' }}">{{ ucfirst($r->status ?? 'pending') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rp-empty">
            <i class="fas fa-inbox"></i>
            <p>No attendance records found.</p>
        </div>
    @endif
</div>

@endsection
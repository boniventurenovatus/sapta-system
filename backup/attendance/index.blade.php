@extends('layouts.sapta')

@section('title', 'Attendance | SAPTA')

@section('content')
<style>
    .page-attendance {
        max-width: 1400px;
        margin: 0 auto;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #7a8a9e;
        margin-bottom: 20px;
    }
    .breadcrumb a {
        color: #4f8cf7;
        text-decoration: none;
    }
    .breadcrumb a:hover {
        text-decoration: underline;
    }
    .breadcrumb .sep { color: #d0d8e0; }
    .breadcrumb .current { color: #1a2a3a; font-weight: 500; }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 28px;
    }
    .page-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #0a1628;
        letter-spacing: -0.3px;
    }
    .page-header p {
        font-size: 14px;
        color: #7a8a9e;
        margin-top: 2px;
    }
    .btn-add {
        padding: 10px 24px;
        background: #0a1628;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-add:hover {
        background: #1a2a3a;
        color: #fff;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(10,22,40,0.15);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
    .stat-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #eef2f6;
        padding: 18px 20px;
        transition: 0.2s;
    }
    .stat-card:hover {
        border-color: #d0d8e0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .stat-card .top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .stat-card .number {
        font-size: 26px;
        font-weight: 700;
        color: #0a1628;
    }
    .stat-card .label {
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-top: 2px;
    }
    .stat-card .icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-green { background: #ecfdf5; color: #065f46; }
    .icon-red { background: #fef2f2; color: #991b1b; }
    .icon-amber { background: #fffbeb; color: #92400e; }
    .icon-purple { background: #f5f3ff; color: #5b21b6; }

    .filter-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #eef2f6;
        padding: 18px 20px;
        margin-bottom: 28px;
    }
    .filter-card .filter-label {
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 4px;
    }
    .filter-input {
        width: 100%;
        height: 40px;
        padding: 0 12px;
        border: 1.5px solid #eef2f6;
        border-radius: 8px;
        font-size: 13px;
        color: #1a2a3a;
        background: #fafbfc;
        transition: 0.2s;
        outline: none;
    }
    .filter-input:focus {
        border-color: #0a1628;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(10,22,40,0.06);
    }
    .filter-select {
        width: 100%;
        height: 40px;
        padding: 0 12px;
        border: 1.5px solid #eef2f6;
        border-radius: 8px;
        font-size: 13px;
        color: #1a2a3a;
        background: #fafbfc;
        transition: 0.2s;
        outline: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7a8e' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 32px;
        cursor: pointer;
    }
    .filter-select:focus {
        border-color: #0a1628;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(10,22,40,0.06);
    }
    .btn-filter {
        padding: 8px 24px;
        background: #0a1628;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-filter:hover {
        background: #1a2a3a;
    }
    .btn-clear {
        padding: 8px 18px;
        background: transparent;
        color: #6a7a8e;
        font-size: 13px;
        font-weight: 500;
        border: 1.5px solid #eef2f6;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
    }
    .btn-clear:hover {
        background: #f5f6f8;
        border-color: #d0d8e0;
        text-decoration: none;
        color: #6a7a8e;
    }

    .table-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #eef2f6;
        overflow: hidden;
    }
    .table-card .table-header {
        padding: 16px 20px;
        border-bottom: 1px solid #eef2f6;
        background: #fafbfc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .table-card .table-header h3 {
        font-size: 15px;
        font-weight: 600;
        color: #0a1628;
    }
    .table-card .table-header .count {
        font-size: 13px;
        color: #7a8a9e;
        font-weight: 400;
    }
    .table-card table {
        width: 100%;
        font-size: 14px;
        border-collapse: collapse;
    }
    .table-card thead {
        background: #f8f9fb;
        border-bottom: 1px solid #eef2f6;
    }
    .table-card thead th {
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .table-card tbody tr {
        border-bottom: 1px solid #f0f2f5;
        transition: 0.15s;
    }
    .table-card tbody tr:hover {
        background: #fafbfc;
    }
    .table-card tbody td {
        padding: 12px 16px;
        vertical-align: middle;
    }

    .employee-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .employee-cell .avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #eef2f6;
        color: #1a2a3a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }
    .employee-cell .avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    .employee-cell .name {
        font-weight: 600;
        color: #0a1628;
    }
    .employee-cell .meta {
        font-size: 12px;
        color: #94a3b8;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2px 12px;
        border-radius: 14px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-badge .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .status-present { background: #ecfdf5; color: #065f46; }
    .status-present .dot { background: #10b981; }
    .status-absent { background: #fef2f2; color: #991b1b; }
    .status-absent .dot { background: #ef4444; }
    .status-late { background: #fffbeb; color: #92400e; }
    .status-late .dot { background: #f59e0b; }
    .status-half_day { background: #fff7ed; color: #9a3412; }
    .status-half_day .dot { background: #f97316; }
    .status-on_leave { background: #f5f3ff; color: #5b21b6; }
    .status-on_leave .dot { background: #8b5cf6; }
    .status-holiday { background: #eff6ff; color: #1e40af; }
    .status-holiday .dot { background: #3b82f6; }

    .action-btns {
        display: flex;
        gap: 4px;
        justify-content: flex-end;
    }
    .action-btn {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        border: 1px solid #eef2f6;
        background: #fff;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.15s;
        cursor: pointer;
        text-decoration: none;
    }
    .action-btn:hover {
        border-color: #d0d8e0;
        background: #f5f6f8;
    }
    .action-btn.view:hover { border-color: #0a1628; background: #f0f2f5; color: #0a1628; }
    .action-btn.edit:hover { border-color: #f59e0b; background: #fffbeb; color: #d97706; }
    .action-btn.delete:hover { border-color: #ef4444; background: #fef2f2; color: #dc2626; }
    .action-btn svg {
        width: 14px;
        height: 14px;
    }

    .empty-state {
        padding: 40px 20px;
        text-align: center;
    }
    .empty-state .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: #94a3b8;
    }
    .empty-state h4 {
        font-size: 15px;
        font-weight: 600;
        color: #0a1628;
    }
    .empty-state p {
        font-size: 13px;
        color: #94a3b8;
        margin-top: 2px;
    }

    @media (max-width: 768px) {
        .table-card table { font-size: 13px; }
        .table-card thead th, .table-card tbody td { padding: 10px 12px; }
        .employee-cell .meta { display: none; }
    }
    @media (max-width: 640px) {
        .table-card .table-header { flex-direction: column; align-items: flex-start; }
    }

        /* =========================================================
           ICON SIZES - ATTENDANCE PAGE
        ========================================================= */

        /* Force all icons to be small */
        .page-attendance svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
            flex-shrink: 0 !important;
        }

        /* Stats icons - slightly bigger */
        .stat-card .icon svg {
            width: 20px !important;
            height: 20px !important;
            max-width: 20px !important;
            max-height: 20px !important;
        }

        /* Action buttons icons */
        .action-btn svg {
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
        }

        /* Empty state icon */
        .empty-state .icon-box svg {
            width: 28px !important;
            height: 28px !important;
            max-width: 28px !important;
            max-height: 28px !important;
        }

        /* Button icons */
        .btn-add svg,
        .btn-filter svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
        }

        /* Filter input icons */
        .filter-card .relative svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
        }
</style>

<div class="page-attendance">

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span class="sep">/</span>
        <span class="current">Attendance</span>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>Attendance Management</h1>
            <p>Monitor and manage employee attendance records</p>
        </div>
        <a href="{{ route('attendances.create') }}" class="btn-add">
            <!-- Lucide Plus -->
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Add Attendance
        </a>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="top">
                <div>
                    <div class="number">{{ $presentCount ?? 0 }}</div>
                    <div class="label">Present</div>
                </div>
                <div class="icon icon-green">
                    <!-- Lucide Check -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="top">
                <div>
                    <div class="number">{{ $absentCount ?? 0 }}</div>
                    <div class="label">Absent</div>
                </div>
                <div class="icon icon-red">
                    <!-- Lucide X -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="top">
                <div>
                    <div class="number">{{ $lateCount ?? 0 }}</div>
                    <div class="label">Late</div>
                </div>
                <div class="icon icon-amber">
                    <!-- Lucide Clock -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="top">
                <div>
                    <div class="number">{{ $leaveCount ?? 0 }}</div>
                    <div class="label">On Leave</div>
                </div>
                <div class="icon icon-purple">
                    <!-- Lucide Calendar -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-card">
        <form method="GET" action="{{ route('attendances.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <div class="filter-label">Search</div>
                    <div class="relative">
                        <!-- Lucide Search -->
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or ID..." class="filter-input pl-9">
                    </div>
                </div>
                <div>
                    <div class="filter-label">Date</div>
                    <div class="relative">
                        <!-- Lucide Calendar -->
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <input type="date" name="date" value="{{ request('date') }}" class="filter-input pl-9">
                    </div>
                </div>
                <div>
                    <div class="filter-label">Employee</div>
                    <div class="relative">
                        <!-- Lucide User -->
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <select name="employee_id" class="filter-select pl-9">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" @selected(request('employee_id') == $employee->id)>{{ $employee->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <div class="filter-label">Status</div>
                    <div class="relative">
                        <!-- Lucide Filter -->
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 13 10 21 14 18 14 13 22 3"/>
                        </svg>
                        <select name="status" class="filter-select pl-9">
                            <option value="">All Statuses</option>
                            <option value="present" @selected(request('status') == 'present')>Present</option>
                            <option value="absent" @selected(request('status') == 'absent')>Absent</option>
                            <option value="late" @selected(request('status') == 'late')>Late</option>
                            <option value="half_day" @selected(request('status') == 'half_day')>Half Day</option>
                            <option value="on_leave" @selected(request('status') == 'on_leave')>On Leave</option>
                            <option value="holiday" @selected(request('status') == 'holiday')>Holiday</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-4 justify-end">
                <a href="{{ route('attendances.index') }}" class="btn-clear">Clear</a>
                <button type="submit" class="btn-filter flex items-center gap-2">
                    <!-- Lucide Filter -->
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 13 10 21 14 18 14 13 22 3"/>
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="table-header">
            <div>
                <h3>Attendance Records</h3>
                <span class="count">{{ $attendances->total() }} records found</span>
            </div>
        </div>

        @if($attendances->count())
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    @php
                        $status = strtolower($attendance->status ?? '');
                        $statusClass = match($status) {
                            'present' => 'status-present',
                            'absent' => 'status-absent',
                            'late' => 'status-late',
                            'half_day' => 'status-half_day',
                            'on_leave' => 'status-on_leave',
                            'holiday' => 'status-holiday',
                            default => ''
                        };
                        $statusLabel = match($status) {
                            'half_day' => 'Half Day',
                            'on_leave' => 'On Leave',
                            default => ucfirst(str_replace('_', ' ', $status))
                        };
                    @endphp
                    <tr>
                        <td>
                            <div class="employee-cell">
                                <div class="avatar">
                                    @if($attendance->employee?->profile_image)
                                        <img src="{{ asset('storage/' . $attendance->employee->profile_image) }}" alt="{{ $attendance->employee->full_name }}">
                                    @else
                                        {{ $attendance->employee?->initials ?? '?' }}
                                    @endif
                                </div>
                                <div>
                                    <div class="name">{{ $attendance->employee?->full_name ?? 'Unknown' }}</div>
                                    <div class="meta">{{ $attendance->employee?->employee_number ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $attendance->attendance_date?->format('d M Y') ?? '—' }}</td>
                        <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '—' }}</td>
                        <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '—' }}</td>
                        <td>
                            <span class="status-badge {{ $statusClass }}">
                                <span class="dot"></span>
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <!-- Lucide Eye (View) -->
                                <a href="{{ route('attendances.show', $attendance) }}" class="action-btn view" title="View">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </a>
                                <!-- Lucide Pencil (Edit) -->
                                <a href="{{ route('attendances.edit', $attendance) }}" class="action-btn edit" title="Edit">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>
                                <!-- Lucide Trash2 (Delete) -->
                                <form method="POST" action="{{ route('attendances.destroy', $attendance) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" data-delete-button data-user-name="{{ $attendance->employee?->full_name ?? 'this record' }}" data-delete-type="attendance record" class="action-btn delete" title="Delete">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            <line x1="10" y1="11" x2="10" y2="17"/>
                                            <line x1="14" y1="11" x2="14" y2="17"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($attendances->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">
            {{ $attendances->links() }}
        </div>
        @endif
        @else
        <div class="empty-state">
            <div class="icon-box">
                <!-- Lucide Calendar -->
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <h4>No records found</h4>
            <p>Try adjusting your filters or add a new record</p>
            <a href="{{ route('attendances.create') }}" class="btn-add" style="margin-top:12px;display:inline-flex;">
                <!-- Lucide Plus -->
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add Attendance
            </a>
        </div>
        @endif
    </div>
</div>
@endsection



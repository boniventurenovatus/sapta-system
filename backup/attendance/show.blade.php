@extends('layouts.sapta')

@section('title', 'Attendance Details | SAPTA')

@section('content')
<style>
    .details-page {
        max-width: 900px;
        margin: 0 auto;
    }
    .details-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e9edf2;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        overflow: hidden;
    }
    .details-header {
        padding: 22px 30px;
        border-bottom: 1px solid #f0f2f5;
        background: #fafbfc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .details-header h1 {
        font-size: 20px;
        font-weight: 700;
        color: #0a1628;
        letter-spacing: -0.3px;
    }
    .details-header p {
        font-size: 14px;
        color: #7a8a9e;
        margin-top: 2px;
    }
    .details-header .actions {
        display: flex;
        gap: 8px;
    }
    .btn-edit {
        padding: 8px 20px;
        background: #f59e0b;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-edit:hover {
        background: #d97706;
        text-decoration: none;
        color: #fff;
    }
    .btn-back {
        padding: 8px 20px;
        background: transparent;
        color: #6a7a8e;
        font-size: 13px;
        font-weight: 500;
        border: 1.5px solid #dee4ec;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-back:hover {
        background: #f5f6f8;
        border-color: #c8d0dc;
        text-decoration: none;
        color: #6a7a8e;
    }
    .details-body {
        padding: 28px 30px 30px;
    }
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px 40px;
    }
    @media (max-width: 768px) {
        .details-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }
    .details-item .label {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }
    .details-item .value {
        font-size: 15px;
        font-weight: 600;
        color: #0a1628;
    }
    .details-item .sub {
        font-size: 13px;
        color: #7a8a9e;
        font-weight: 400;
    }
    .employee-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .employee-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #e8edf5;
        color: #1a2a3a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        flex-shrink: 0;
    }
    .employee-name {
        font-size: 16px;
        font-weight: 700;
        color: #0a1628;
    }
    .employee-meta {
        font-size: 13px;
        color: #7a8a9e;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .status-badge .dot {
        width: 8px;
        height: 8px;
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
    .details-notes {
        margin-top: 20px;
        padding: 16px 20px;
        background: #f8f9fb;
        border-radius: 10px;
        border: 1px solid #f0f2f5;
    }
    .details-notes .label {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .details-notes .text {
        font-size: 14px;
        color: #1a2a3a;
        margin-top: 4px;
    }
    .details-footer {
        padding: 16px 30px;
        border-top: 1px solid #f0f2f5;
        background: #fafbfc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .details-footer .id {
        font-size: 12px;
        color: #94a3b8;
    }
    .btn-delete {
        padding: 8px 20px;
        background: #fef2f2;
        color: #dc2626;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #fecaca;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-delete:hover {
        background: #fee2e2;
        border-color: #fca5a5;
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
    .breadcrumb .sep {
        color: #d0d8e0;
    }
    .breadcrumb .current {
        color: #1a2a3a;
        font-weight: 500;
    }
</style>

<div class="details-page">

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span class="sep">/</span>
        <a href="{{ route('attendances.index') }}">Attendance</a>
        <span class="sep">/</span>
        <span class="current">Details</span>
    </div>

    <!-- Card -->
    <div class="details-card">

        <!-- Header -->
        <div class="details-header">
            <div>
                <h1>Attendance Details</h1>
                <p>Record for {{ $attendance->employee?->full_name ?? 'Unknown Employee' }}</p>
            </div>
            <div class="actions">
                <a href="{{ route('attendances.index') }}" class="btn-back">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
                <a href="{{ route('attendances.edit', $attendance) }}" class="btn-edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="details-body">

            <!-- Employee -->
            <div class="employee-info" style="margin-bottom: 24px;">
                <div class="employee-avatar">
                    {{ $attendance->employee?->initials ?? '?' }}
                </div>
                <div>
                    <div class="employee-name">{{ $attendance->employee?->full_name ?? 'Unknown Employee' }}</div>
                    <div class="employee-meta">
                        {{ $attendance->employee?->employee_number ?? '—' }} 
                        @if($attendance->employee?->email) 
                            • {{ $attendance->employee->email }} 
                        @endif
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="details-grid">

                <div class="details-item">
                    <div class="label">Date</div>
                    <div class="value">{{ $attendance->attendance_date?->format('l, d F Y') ?? '—' }}</div>
                </div>

                <div class="details-item">
                    <div class="label">Status</div>
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
                    <div>
                        <span class="status-badge {{ $statusClass }}">
                            <span class="dot"></span>
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <div class="details-item">
                    <div class="label">Check In Time</div>
                    <div class="value">{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '—' }}</div>
                </div>

                <div class="details-item">
                    <div class="label">Check Out Time</div>
                    <div class="value">{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '—' }}</div>
                </div>

                <div class="details-item">
                    <div class="label">Record Created</div>
                    <div class="value">{{ $attendance->created_at?->format('d M Y, H:i') ?? '—' }}</div>
                    <div class="sub">Last updated: {{ $attendance->updated_at?->format('d M Y, H:i') ?? '—' }}</div>
                </div>

            </div>

            <!-- Notes -->
            @if($attendance->notes)
                <div class="details-notes">
                    <div class="label">Notes</div>
                    <div class="text">{{ $attendance->notes }}</div>
                </div>
            @endif

        </div>

        <!-- Footer -->
        <div class="details-footer">
            <span class="id">Record ID: #{{ $attendance->id }}</span>
            <form method="POST" action="{{ route('attendances.destroy', $attendance) }}" onsubmit="return confirm('Are you sure you want to delete this attendance record?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h12"/>
                    </svg>
                    Delete
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

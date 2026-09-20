@extends('layouts.sapta')

@section('title', 'Edit Attendance | SAPTA')

@section('content')
<style>
    .edit-page {
        max-width: 900px;
        margin: 0 auto;
    }
    .edit-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e9edf2;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        overflow: hidden;
    }
    .edit-header {
        padding: 22px 30px;
        border-bottom: 1px solid #f0f2f5;
        background: #fafbfc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .edit-header h1 {
        font-size: 20px;
        font-weight: 700;
        color: #0a1628;
        letter-spacing: -0.3px;
    }
    .edit-header p {
        font-size: 14px;
        color: #7a8a9e;
        margin-top: 2px;
    }
    .edit-header .badge-id {
        font-size: 12px;
        font-weight: 600;
        color: #4f8cf7;
        background: #eef4ff;
        padding: 4px 14px;
        border-radius: 20px;
        border: 1px solid #d6e4ff;
    }
    .edit-body {
        padding: 28px 30px 30px;
    }
    .field-group {
        margin-bottom: 22px;
    }
    .field-group:last-child {
        margin-bottom: 0;
    }
    .field-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #1a2a3a;
        margin-bottom: 5px;
    }
    .field-label .star {
        color: #e74c3c;
        margin-left: 2px;
    }
    .field-control {
        width: 100%;
        height: 44px;
        padding: 0 14px;
        border: 1.5px solid #dee4ec;
        border-radius: 10px;
        font-size: 14px;
        color: #1a2a3a;
        background: #fcfcfd;
        transition: 0.2s;
        outline: none;
    }
    .field-control:focus {
        border-color: #4f8cf7;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(79,140,247,0.08);
    }
    .field-control.error {
        border-color: #e74c3c;
    }
    .field-control::placeholder {
        color: #b0bccd;
    }
    .field-control:disabled {
        background: #f5f6f8;
        cursor: not-allowed;
    }
    select.field-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7a8e' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px;
        cursor: pointer;
    }
    textarea.field-control {
        height: 80px;
        padding: 12px 14px;
        resize: vertical;
        font-family: inherit;
    }
    .field-error {
        font-size: 12px;
        color: #e74c3c;
        margin-top: 4px;
    }
    .row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }
    @media (max-width: 768px) {
        .row-2 {
            grid-template-columns: 1fr;
        }
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 22px;
        margin-top: 6px;
        border-top: 1px solid #f0f2f5;
        flex-wrap: wrap;
    }
    .btn-update {
        padding: 10px 32px;
        background: #1a2a3a;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-update:hover {
        background: #0a1a2a;
        box-shadow: 0 4px 12px rgba(26,42,58,0.15);
    }
    .btn-cancel {
        padding: 10px 24px;
        background: transparent;
        color: #6a7a8e;
        font-size: 14px;
        font-weight: 500;
        border: 1.5px solid #dee4ec;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-cancel:hover {
        background: #f5f6f8;
        border-color: #c8d0dc;
        text-decoration: none;
        color: #6a7a8e;
    }
    .btn-view {
        padding: 10px 24px;
        background: #f0f4ff;
        color: #4f8cf7;
        font-size: 14px;
        font-weight: 500;
        border: 1.5px solid #d6e4ff;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-view:hover {
        background: #e4edff;
        border-color: #b8cfff;
        text-decoration: none;
        color: #4f8cf7;
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

<div class="edit-page">

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span class="sep">/</span>
        <a href="{{ route('attendances.index') }}">Attendance</a>
        <span class="sep">/</span>
        <span class="current">Edit</span>
    </div>

    <!-- Card -->
    <div class="edit-card">

        <!-- Header -->
        <div class="edit-header">
            <div>
                <h1>Edit Attendance Record</h1>
                <p>Update attendance details for {{ $attendance->employee?->full_name ?? 'Employee' }}</p>
            </div>
            <span class="badge-id">ID: #{{ $attendance->id }}</span>
        </div>

        <!-- Body -->
        <div class="edit-body">
            <form method="POST" action="{{ route('attendances.update', $attendance) }}">
                @csrf
                @method('PUT')

                <!-- Employee -->
                <div class="field-group">
                    <label class="field-label">Employee <span class="star">*</span></label>
                    <select name="employee_id" class="field-control @error('employee_id') error @enderror" required>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('employee_id', $attendance->employee_id) == $employee->id)>
                                {{ $employee->full_name }} @if($employee->employee_number) ({{ $employee->employee_number }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Row: Date + Status -->
                <div class="row-2">
                    <div class="field-group">
                        <label class="field-label">Date <span class="star">*</span></label>
                        <input type="date" name="attendance_date" value="{{ old('attendance_date', $attendance->attendance_date?->format('Y-m-d')) }}" class="field-control @error('attendance_date') error @enderror" required>
                        @error('attendance_date')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label">Status <span class="star">*</span></label>
                        <select name="status" class="field-control @error('status') error @enderror" required>
                            <option value="present" @selected(old('status', $attendance->status) == 'present')>Present</option>
                            <option value="absent" @selected(old('status', $attendance->status) == 'absent')>Absent</option>
                            <option value="late" @selected(old('status', $attendance->status) == 'late')>Late</option>
                            <option value="half_day" @selected(old('status', $attendance->status) == 'half_day')>Half Day</option>
                            <option value="on_leave" @selected(old('status', $attendance->status) == 'on_leave')>On Leave</option>
                            <option value="holiday" @selected(old('status', $attendance->status) == 'holiday')>Holiday</option>
                        </select>
                        @error('status')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Row: Check In + Check Out -->
                <div class="row-2">
                    <div class="field-group">
                        <label class="field-label">Check In Time</label>
                        <input type="time" name="check_in" value="{{ old('check_in', $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '') }}" class="field-control @error('check_in') error @enderror" step="60">
                        @error('check_in')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label">Check Out Time</label>
                        <input type="time" name="check_out" value="{{ old('check_out', $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '') }}" class="field-control @error('check_out') error @enderror" step="60">
                        @error('check_out')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="field-group">
                    <label class="field-label">Notes</label>
                    <textarea name="notes" rows="3" class="field-control @error('notes') error @enderror" placeholder="Add any additional notes...">{{ old('notes', $attendance->notes) }}</textarea>
                    @error('notes')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <a href="{{ route('attendances.show', $attendance) }}" class="btn-view">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Record
                    </a>
                    <a href="{{ route('attendances.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-update">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Record
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

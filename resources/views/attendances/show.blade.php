@extends('layouts.sapta')
@section('title', 'Attendance Details')
@section('page-title', 'Attendance Details')

@section('content')
<style>
    .att-show-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .att-show-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .att-show-header-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 20px rgba(37,99,235,0.25); flex-shrink: 0; }
    .att-show-header h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .att-show-header p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .att-show-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .att-show-section { padding: 1.5rem 1.75rem; }
    .att-show-section-title { font-size: 0.75rem; font-weight: 800; color: #2563eb; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 1.25rem; padding-bottom: 0.75rem; border-bottom: 2px solid #eff6ff; display: flex; align-items: center; gap: 0.5rem; }
    .att-show-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem 2rem; }
    .att-show-field { padding: 0.75rem 0; border-bottom: 1px dashed #f1f5f9; }
    .att-show-field.full { grid-column: 1 / -1; }
    .att-show-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem; }
    .att-show-value { font-size: 0.95rem; color: #0f172a; font-weight: 600; }
    .att-show-value.muted { color: #94a3b8; font-weight: 400; font-style: italic; }
    .att-show-badge { display: inline-block; padding: 0.35rem 0.9rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; }
    .att-show-badge.present { background: #dcfce7; color: #166534; }
    .att-show-badge.absent { background: #fee2e2; color: #991b1b; }
    .att-show-badge.late { background: #fef3c7; color: #92400e; }
    .att-show-badge.on_leave { background: #dbeafe; color: #1e40af; }
    .att-show-badge.half_day { background: #e0e7ff; color: #3730a3; }
    .att-show-badge.holiday { background: #f3e8ff; color: #6b21a8; }
    .att-show-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.75rem; background: #fafbfc; border-top: 1px solid #f1f5f9; flex-wrap: wrap; }
    .att-show-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .att-show-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
    .att-show-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .att-show-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .att-show-btn-secondary:hover { background: #f8fafc; color: #1e293b; }
    .att-show-btn-danger { background: #fff; color: #dc2626; border: 1.5px solid #fecaca; }
    .att-show-btn-danger:hover { background: #fef2f2; color: #991b1b; }
    @media (max-width: 640px) {
        .att-show-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="att-show-page">

    <div class="att-show-header">
        <div class="att-show-header-icon"><i class="fas fa-clipboard-check"></i></div>
        <div>
            <h1>Attendance Details</h1>
            <p>Record #{{ $attendance->id }} ? {{ $attendance->attendance_date?->format('F d, Y') }}</p>
        </div>
    </div>

    {{-- Employee Card --}}
    <div class="att-show-card">
        <div class="att-show-section">
            <div class="att-show-section-title"><i class="fas fa-user"></i> Employee Information</div>
            <div class="att-show-grid">
                <div class="att-show-field full">
                    <div class="att-show-label">Employee</div>
                    <div class="att-show-value">
                        @if($attendance->employee)
                            {{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}
                            @if($attendance->employee->employee_number)
                                <span style="color:#94a3b8; font-weight:400;">({{ $attendance->employee->employee_number }})</span>
                            @endif
                        @else
                            <span class="att-show-value muted">?</span>
                        @endif
                    </div>
                </div>
                @if($attendance->employee?->job_title)
                    <div class="att-show-field">
                        <div class="att-show-label">Job Title</div>
                        <div class="att-show-value">{{ $attendance->employee->job_title }}</div>
                    </div>
                @endif
                @if($attendance->employee?->department?->name)
                    <div class="att-show-field">
                        <div class="att-show-label">Department</div>
                        <div class="att-show-value">{{ $attendance->employee->department->name }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Attendance Details --}}
    <div class="att-show-card">
        <div class="att-show-section">
            <div class="att-show-section-title"><i class="fas fa-clock"></i> Attendance Details</div>
            <div class="att-show-grid">
                <div class="att-show-field">
                    <div class="att-show-label">Date</div>
                    <div class="att-show-value">{{ $attendance->attendance_date?->format('M d, Y') ?? '?' }}</div>
                </div>
                <div class="att-show-field">
                    <div class="att-show-label">Status</div>
                    <div class="att-show-value">
                        <span class="att-show-badge {{ $attendance->status }}">{{ ucfirst(str_replace('_', ' ', $attendance->status)) }}</span>
                    </div>
                </div>
                <div class="att-show-field">
                    <div class="att-show-label">Check In</div>
                    <div class="att-show-value">{{ $attendance->check_in ?? '?' }}</div>
                </div>
                <div class="att-show-field">
                    <div class="att-show-label">Check Out</div>
                    <div class="att-show-value">{{ $attendance->check_out ?? '?' }}</div>
                </div>
                @if($attendance->notes)
                    <div class="att-show-field full">
                        <div class="att-show-label">Notes</div>
                        <div class="att-show-value" style="font-weight:400;">{{ $attendance->notes }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Location Card --}}
    <div class="att-show-card">
        <div class="att-show-section">
            <div class="att-show-section-title"><i class="fas fa-map-location-dot"></i> Location</div>
            <div class="att-show-grid">
                <div class="att-show-field">
                    <div class="att-show-label">Region</div>
                    <div class="att-show-value {{ $attendance->region ? '' : 'muted' }}">{{ $attendance->region?->name ?? '?' }}</div>
                </div>
                <div class="att-show-field">
                    <div class="att-show-label">District</div>
                    <div class="att-show-value {{ $attendance->district ? '' : 'muted' }}">{{ $attendance->district?->name ?? '?' }}</div>
                </div>
                <div class="att-show-field full">
                    <div class="att-show-label">Ward</div>
                    <div class="att-show-value {{ $attendance->ward ? '' : 'muted' }}">{{ $attendance->ward?->name ?? '?' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="att-show-card">
        <div class="att-show-actions">
            <a href="{{ route('attendances.edit', $attendance) }}" class="att-show-btn att-show-btn-primary"><i class="fas fa-pen"></i> Edit</a>
            <a href="{{ route('attendances.index') }}" class="att-show-btn att-show-btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <button type="button" class="att-show-btn att-show-btn-danger" style="margin-left:auto;" onclick="openDeleteModal()"><i class="fas fa-trash"></i> Delete</button>
        </div>
    </div>

</div>

{{-- DELETE MODAL --}}
<div id="deleteModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:1rem; padding:2rem 2.5rem; max-width:420px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.3); text-align:center;">
        <div style="width:4rem; height:4rem; border-radius:50%; background:#fef3c7; color:#d97706; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; font-size:2rem;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h2 style="font-size:1.25rem; font-weight:800; color:#0f172a; margin:0 0 0.5rem;">Are you sure?</h2>
        <p style="color:#64748b; margin:0 0 1.5rem; font-size:0.9rem;">This action cannot be undone!</p>
        <div style="display:flex; gap:0.75rem; justify-content:center;">
            <button type="button" onclick="closeDeleteModal()" style="padding:0.65rem 1.5rem; border-radius:0.5rem; font-weight:700; font-size:0.9rem; border:none; cursor:pointer; background:#e2e8f0; color:#475569;">
                Cancel
            </button>
            <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" style="margin:0;">
                @csrf @method('DELETE')
                <button type="submit" style="padding:0.65rem 1.5rem; border-radius:0.5rem; font-weight:700; font-size:0.9rem; border:none; cursor:pointer; background:#dc2626; color:#fff;">
                    Yes, Proceed
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}
function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'none';
    }
}
// Close on background click
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeDeleteModal();
            }
        });
    }
});
</script>
@endpush

    
    </div>


    {{-- LOCATION --}}
    <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; overflow:hidden; margin-top:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <div style="padding:16px 24px; border-bottom:1px solid #f1f5f9; background:linear-gradient(135deg,#f8fafc,#f1f5f9); display:flex; align-items:center; gap:10px;">
            <div style="width:32px; height:32px; border-radius:10px; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px;">
                <i class="fas fa-map-location-dot"></i>
            </div>
            <h2 style="font-size:13px; font-weight:800; color:#0f172a; margin:0; text-transform:uppercase; letter-spacing:0.08em;">Location</h2>
        </div>
        <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px;">
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Region</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($attendance->region){{ $attendance->region->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">District</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($attendance->district){{ $attendance->district->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
            <div>
                <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Ward</div>
                <div style="font-size:15px; font-weight:600; color:#0f172a;">@if($attendance->ward){{ $attendance->ward->name }}@else<span style="color:#cbd5e1; font-weight:400;">—</span>@endif</div>
            </div>
        </div>
    </div>

@endsection








@extends('layouts.sapta')
@section('title', 'Edit Attendance')
@section('page-title', 'Edit Attendance')

@section('content')
<style>
    .att-edit-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .att-edit-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .att-edit-header-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 20px rgba(245,158,11,0.25); flex-shrink: 0; }
    .att-edit-header h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .att-edit-header p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .att-edit-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .att-edit-section { padding: 1.75rem; }
    .att-edit-section-title { font-size: 0.75rem; font-weight: 800; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 1.25rem; padding-bottom: 0.75rem; border-bottom: 2px solid #fef3c7; display: flex; align-items: center; gap: 0.5rem; }
    .att-edit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .att-edit-group { margin-bottom: 1rem; }
    .att-edit-group.full { grid-column: 1 / -1; }
    .att-edit-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .att-edit-group label .req { color: #dc2626; }
    .att-edit-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; background: #fff; }
    .att-edit-input:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }
    .att-edit-error { color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem; }
    .att-edit-actions { padding: 1.25rem 1.75rem; background: #fafbfc; border-top: 1px solid #f1f5f9; display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .att-edit-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .att-edit-btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .att-edit-btn-warning:hover { transform: translateY(-1px); color: #fff; }
    .att-edit-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .att-edit-btn-secondary:hover { background: #f8fafc; color: #1e293b; }
    @media (max-width: 640px) {
        .att-edit-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="att-edit-page">

    <div class="att-edit-header">
        <div class="att-edit-header-icon"><i class="fas fa-pen"></i></div>
        <div>
            <h1>Edit Attendance</h1>
            <p>Update record #{{ $attendance->id }} ? {{ $attendance->attendance_date?->format('F d, Y') }}</p>
        </div>
    </div>

    @if($errors->any())
        <div style="padding:0.75rem 1rem; background:#fee2e2; color:#991b1b; border-radius:0.5rem; margin-bottom:1rem; border-left:4px solid #dc2626;">
            <strong>Please correct the following:</strong>
            <ul style="margin:8px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('attendances.update', $attendance) }}" method="POST">
        @csrf @method('PUT')

        {{-- Employee --}}
        <div class="att-edit-card">
            <div class="att-edit-section">
                <div class="att-edit-section-title"><i class="fas fa-user"></i> Employee</div>
                <div class="att-edit-grid">
                    <div class="att-edit-group full">
                        <label>Employee <span class="req">*</span></label>
                        <select name="employee_id" class="att-edit-input" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($employees ?? [] as $emp)
                                <option value="{{ $emp->id }}" @selected(old('employee_id', $attendance->employee_id) == $emp->id)>
                                    {{ $emp->first_name }} {{ $emp->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id') <div class="att-edit-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendance Details --}}
        <div class="att-edit-card">
            <div class="att-edit-section">
                <div class="att-edit-section-title"><i class="fas fa-clock"></i> Attendance Details</div>
                <div class="att-edit-grid">
                    <div class="att-edit-group">
                        <label>Date <span class="req">*</span></label>
                        <input type="date" name="attendance_date" class="att-edit-input" value="{{ old('attendance_date', $attendance->attendance_date?->format('Y-m-d')) }}" required>
                        @error('attendance_date') <div class="att-edit-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="att-edit-group">
                        <label>Status <span class="req">*</span></label>
                        <select name="status" class="att-edit-input" required>
                            @foreach(['present','absent','late','half_day','on_leave','holiday'] as $s)
                                <option value="{{ $s }}" @selected(old('status', $attendance->status) === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                            @endforeach
                        </select>
                        @error('status') <div class="att-edit-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="att-edit-group">
                        <label>Check In</label>
                        <input type="time" name="check_in" class="att-edit-input" value="{{ old('check_in', $attendance->check_in) }}">
                        @error('check_in') <div class="att-edit-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="att-edit-group">
                        <label>Check Out</label>
                        <input type="time" name="check_out" class="att-edit-input" value="{{ old('check_out', $attendance->check_out) }}">
                        @error('check_out') <div class="att-edit-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="att-edit-group full">
                        <label>Notes</label>
                        <textarea name="notes" rows="3" class="att-edit-input" placeholder="Additional notes...">{{ old('notes', $attendance->notes) }}</textarea>
                        @error('notes') <div class="att-edit-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Location --}}
        <div class="att-edit-card">
            <div class="att-edit-section">
                <div class="att-edit-section-title"><i class="fas fa-map-location-dot"></i> Location</div>
                <div class="att-edit-grid">
                    <div class="att-edit-group">
                        <label>Region</label>
                        <select id="region_id" name="region_id" class="att-edit-input">
                            <option value="">-- Select Region --</option>
                            @foreach($regions ?? [] as $r)
                                <option value="{{ $r->id }}" @selected(old('region_id', $attendance->region_id) == $r->id)>{{ $r->name }}</option>
                            @endforeach
                        </select>
                        @error('region_id') <div class="att-edit-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="att-edit-group">
                        <label>District</label>
                        <select id="district_id" name="district_id" class="att-edit-input">
                            <option value="">-- Select District --</option>
                            @if($attendance->district_id)
                                <option value="{{ $attendance->district_id }}" selected>{{ $attendance->district?->name }}</option>
                            @endif
                        </select>
                        @error('district_id') <div class="att-edit-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="att-edit-group full">
                        <label>Ward</label>
                        <select id="ward_id" name="ward_id" class="att-edit-input">
                            <option value="">-- Select Ward --</option>
                            @if($attendance->ward_id)
                                <option value="{{ $attendance->ward_id }}" selected>{{ $attendance->ward?->name }}</option>
                            @endif
                        </select>
                        @error('ward_id') <div class="att-edit-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="att-edit-card">
            <div class="att-edit-actions">
                <button type="submit" class="att-edit-btn att-edit-btn-warning"><i class="fas fa-save"></i> Update Attendance</button>
                <a href="{{ route('attendances.index') }}" class="att-edit-btn att-edit-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
            </div>
        </div>

    </form>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionSelect = document.getElementById('region_id');
    const districtSelect = document.getElementById('district_id');
    const wardSelect = document.getElementById('ward_id');

    const savedDistrictId = '{{ old('district_id', $attendance->district_id) }}';
    const savedWardId = '{{ old('ward_id', $attendance->ward_id) }}';

    if (regionSelect && districtSelect && wardSelect) {
        regionSelect.addEventListener('change', function() {
            const regionId = this.value;
            districtSelect.innerHTML = '<option value="">Loading...</option>';
            wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';

            if (!regionId) {
                districtSelect.innerHTML = '<option value="">-- Select District --</option>';
                return;
            }

            fetch('/location/districts?region_id=' + regionId)
                .then(res => res.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">-- Select District --</option>';
                    data.forEach(d => {
                        const sel = (d.id == savedDistrictId) ? ' selected' : '';
                        districtSelect.innerHTML += `<option value="${d.id}"${sel}>${d.name}</option>`;
                    });
                    if (savedDistrictId) districtSelect.dispatchEvent(new Event('change'));
                });
        });

        districtSelect.addEventListener('change', function() {
            const districtId = this.value;
            wardSelect.innerHTML = '<option value="">Loading...</option>';

            if (!districtId) {
                wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';
                return;
            }

            fetch('/location/wards?district_id=' + districtId)
                .then(res => res.json())
                .then(data => {
                    wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';
                    data.forEach(w => {
                        const sel = (w.id == savedWardId) ? ' selected' : '';
                        wardSelect.innerHTML += `<option value="${w.id}"${sel}>${w.name}</option>`;
                    });
                });
        });

        if (regionSelect.value) regionSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection

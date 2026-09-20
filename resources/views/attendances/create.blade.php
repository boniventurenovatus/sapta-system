@extends('layouts.sapta')

@section('title', 'Add Attendance')
@section('page-title', 'Add Attendance')

@section('content')
<style>
    .atf-page { padding: 1.5rem; max-width: 800px; margin: 0 auto; }
    .atf-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .atf-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 20px rgba(37,99,235,0.25); flex-shrink: 0; }
    .atf-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .atf-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .atf-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; }
    .atf-section { padding: 1.75rem; }
    .atf-section-title { display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 800; color: #2563eb; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9; }
    .atf-group { margin-bottom: 1.25rem; }
    .atf-group label { display: block; font-size: 0.875rem; font-weight: 700; color: #334155; margin-bottom: 0.5rem; }
    .atf-group label .req { color: #dc2626; }
    .atf-input-wrap { position: relative; }
    .atf-input-wrap i { position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem; pointer-events: none; }
    .atf-input { width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1.5px solid #e2e8f0; border-radius: 0.625rem; font-size: 0.9rem; color: #1e293b; background: #fff; transition: all 0.2s; font-family: inherit; }
    .atf-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37,99,235,0.1); }
    .atf-input.is-invalid { border-color: #dc2626; background: #fef2f2; }
    .atf-error { display: flex; align-items: center; gap: 0.35rem; color: #dc2626; font-size: 0.8rem; margin-top: 0.4rem; font-weight: 600; }
    .atf-alert-error { padding: 0.875rem 1rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 600; border-left: 4px solid #dc2626; }
    .atf-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.75rem; background: #fafbfc; border-top: 1px solid #f1f5f9; }
    .atf-btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1.5rem; border-radius: 0.625rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; font-family: inherit; }
    .atf-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,0.25); }
    .atf-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .atf-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
</style>

<div class="atf-page">
    <div class="atf-head">
        <div class="atf-head-icon"><i class="fas fa-clock"></i></div>
        <div>
            <h1>Add Attendance</h1>
            <p>Record employee attendance for a specific date.</p>
        </div>
    </div>

    <div class="atf-card">
        <form action="{{ route('attendances.store') }}" method="POST">
            @csrf

            <div class="atf-section">
                @if($errors->any())
                    <div class="atf-alert-error">
                        <i class="fas fa-circle-exclamation"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="atf-section-title"><i class="fas fa-circle-info"></i> Attendance Details</div>

                <div class="atf-group">
                    <label for="employee_id">Employee <span class="req">*</span></label>
                    <div class="atf-input-wrap">
                        <i class="fas fa-user"></i>
                        <select id="employee_id" name="employee_id" class="atf-input @error('employee_id') is-invalid @enderror" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" @selected(old('employee_id') == $emp->id)>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('employee_id')<div class="atf-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                </div>

                <div class="atf-group">
                    <label for="attendance_date">Date <span class="req">*</span></label>
                    <div class="atf-input-wrap">
                        <i class="fas fa-calendar"></i>
                        <input type="date" id="attendance_date" name="attendance_date" class="atf-input @error('attendance_date') is-invalid @enderror" value="{{ old('attendance_date', date('Y-m-d')) }}" required>
                    </div>
                    @error('attendance_date')<div class="atf-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                </div>

                <div class="atf-group">
                    <label for="check_in">Check In</label>
                    <div class="atf-input-wrap">
                        <i class="fas fa-right-to-bracket"></i>
                        <input type="time" id="check_in" name="check_in" class="atf-input" value="{{ old('check_in') }}">
                    </div>
                </div>

                <div class="atf-group">
                    <label for="check_out">Check Out</label>
                    <div class="atf-input-wrap">
                        <i class="fas fa-right-from-bracket"></i>
                        <input type="time" id="check_out" name="check_out" class="atf-input" value="{{ old('check_out') }}">
                    </div>
                </div>

                <div class="atf-group">
                    <label for="status">Status <span class="req">*</span></label>
                    <div class="atf-input-wrap">
                        <i class="fas fa-toggle-on"></i>
                        <select id="status" name="status" class="atf-input" required>
                            <option value="present" @selected(old('status') === 'present')>Present</option>
                            <option value="absent" @selected(old('status') === 'absent')>Absent</option>
                            <option value="late" @selected(old('status') === 'late')>Late</option>
                            <option value="on_leave" @selected(old('status') === 'on_leave')>On Leave</option>
                        </select>
                    </div>
                </div>

                <div class="atf-group">
                    <label for="notes">Notes</label>
                    <div class="atf-input-wrap">
                        <i class="fas fa-align-left" style="top:1rem; transform:none;"></i>
                        <textarea id="notes" name="notes" class="atf-input" rows="3" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="atf-group">
                    <label for="region_id">Region</label>
                    <div class="atf-input-wrap">
                        <i class="fas fa-map"></i>
                        <select id="region_id" name="region_id" class="atf-input">
                            <option value="">-- Select Region --</option>
                            @foreach($regions ?? [] as $r)
                                <option value="{{ $r->id }}" @selected(old('region_id') == $r->id)>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="atf-group">
                    <label for="district_id">District</label>
                    <div class="atf-input-wrap">
                        <i class="fas fa-map-location-dot"></i>
                        <select id="district_id" name="district_id" class="atf-input">
                            <option value="">-- Select District --</option>
                        </select>
                    </div>
                </div>

                <div class="atf-group">
                    <label for="ward_id">Ward</label>
                    <div class="atf-input-wrap">
                        <i class="fas fa-location-crosshairs"></i>
                        <select id="ward_id" name="ward_id" class="atf-input">
                            <option value="">-- Select Ward --</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="atf-actions">
                <button type="submit" class="atf-btn atf-btn-primary">
                    <i class="fas fa-save"></i> Save Attendance
                </button>
                <a href="{{ route('attendances.index') }}" class="atf-btn atf-btn-secondary">
                    <i class="fas fa-xmark"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionSelect = document.getElementById('region_id');
    const districtSelect = document.getElementById('district_id');
    const wardSelect = document.getElementById('ward_id');

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
                        districtSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                    });
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
                        wardSelect.innerHTML += `<option value="${w.id}">${w.name}</option>`;
                    });
                });
        });
    }
});
</script>
@endpush

@endsection






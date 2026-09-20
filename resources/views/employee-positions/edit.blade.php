@extends('layouts.sapta')
@section('title', 'Edit Assignment')
@section('page-title', 'Edit Assignment')

@section('content')
<style>
    .epe-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .epe-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .epe-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 20px rgba(245,158,11,0.25); }
    .epe-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .epe-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .epe-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; box-shadow: 0 4px 16px rgba(0,0,0,0.04); }
    .epe-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .epe-card-head h2 { font-size: 0.75rem; font-weight: 800; color: #f59e0b; margin: 0; text-transform: uppercase; letter-spacing: 0.08em; display: flex; align-items: center; gap: 0.5rem; }
    .epe-card-body { padding: 1.5rem; }
    .epe-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .epe-grid { grid-template-columns: 1fr; } }
    .epe-group { margin-bottom: 1rem; }
    .epe-group.full { grid-column: 1 / -1; }
    .epe-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .epe-group label .req { color: #dc2626; }
    .epe-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; background: #fff; }
    .epe-input:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }
    .epe-error { color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem; }
    .epe-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; flex-wrap: wrap; }
    .epe-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .epe-btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .epe-btn-warning:hover { transform: translateY(-1px); color: #fff; }
    .epe-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .epe-btn-secondary:hover { background: #f8fafc; color: #1e293b; }
</style>

<div class="epe-page">
    <div class="epe-head">
        <div class="epe-head-icon"><i class="fas fa-pen"></i></div>
        <div>
            <h1>Edit Assignment</h1>
            <p>Update employee position assignment</p>
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

    <form action="{{ route('employee-positions.update', $employeePosition) }}" method="POST">
        @csrf @method('PUT')

        <div class="epe-card">
            <div class="epe-card-head"><h2><i class="fas fa-user-tie"></i> Assignment Information</h2></div>
            <div class="epe-card-body">
                <div class="epe-grid">
                    <div class="epe-group">
                        <label>Employee <span class="req">*</span></label>
                        <select name="employee_id" class="epe-input" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($employees ?? [] as $e)
                                <option value="{{ $e->id }}" @selected(old('employee_id', $employeePosition->employee_id) == $e->id)>{{ $e->first_name }} {{ $e->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="epe-group">
                        <label>Position <span class="req">*</span></label>
                        <select name="position_id" class="epe-input" required>
                            <option value="">-- Select Position --</option>
                            @foreach($positions ?? [] as $p)
                                <option value="{{ $p->id }}" @selected(old('position_id', $employeePosition->position_id) == $p->id)>{{ $p->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="epe-group">
                        <label>Start Date <span class="req">*</span></label>
                        <input type="date" name="start_date" class="epe-input" value="{{ old('start_date', $employeePosition->start_date?->format('Y-m-d')) }}" required>
                    </div>
                    <div class="epe-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="epe-input" value="{{ old('end_date', $employeePosition->end_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="epe-group">
                        <label>Status <span class="req">*</span></label>
                        <select name="status" class="epe-input" required>
                            @foreach(['active','inactive','ended'] as $s)
                                <option value="{{ $s }}" @selected(old('status', $employeePosition->status) === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="epe-group">
                        <label>Is Primary</label>
                        <select name="is_primary" class="epe-input">
                            <option value="0" @selected(old('is_primary', $employeePosition->is_primary) == 0)>No</option>
                            <option value="1" @selected(old('is_primary', $employeePosition->is_primary) == 1)>Yes</option>
                        </select>
                    </div>
                    <div class="epe-group full">
                        <label>Notes</label>
                        <textarea name="notes" rows="2" class="epe-input">{{ old('notes', $employeePosition->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="epe-card">
            <div class="epe-card-head"><h2><i class="fas fa-map-location-dot"></i> Location</h2></div>
            <div class="epe-card-body">
                <div class="epe-grid">
                    <div class="epe-group">
                        <label>Region</label>
                        <select id="region_id" name="region_id" class="epe-input">
                            <option value="">-- Select Region --</option>
                            @foreach($regions ?? [] as $r)
                                <option value="{{ $r->id }}" @selected(old('region_id', $employeePosition->region_id) == $r->id)>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="epe-group">
                        <label>District</label>
                        <select id="district_id" name="district_id" class="epe-input">
                            <option value="">-- Select District --</option>
                            @if($employeePosition->district_id)
                                <option value="{{ $employeePosition->district_id }}" selected>{{ $employeePosition->district?->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="epe-group">
                        <label>Ward</label>
                        <select id="ward_id" name="ward_id" class="epe-input">
                            <option value="">-- Select Ward --</option>
                            @if($employeePosition->ward_id)
                                <option value="{{ $employeePosition->ward_id }}" selected>{{ $employeePosition->ward?->name }}</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="epe-card">
            <div class="epe-actions">
                <button type="submit" class="epe-btn epe-btn-warning"><i class="fas fa-save"></i> Update Assignment</button>
                <a href="{{ route('employee-positions.index') }}" class="epe-btn epe-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
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
    if (!regionSelect || !districtSelect || !wardSelect) return;

    const savedRegionId = '{{ old('region_id', $employeePosition->region_id) }}';
    const savedDistrictId = '{{ old('district_id', $employeePosition->district_id) }}';
    const savedWardId = '{{ old('ward_id', $employeePosition->ward_id) }}';

    if (savedRegionId) regionSelect.value = savedRegionId;

    function loadDistricts(regionId) {
        if (!regionId) {
            districtSelect.innerHTML = '<option value="">-- Select District --</option>';
            wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';
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
                if (savedDistrictId) loadWards(savedDistrictId);
            });
    }

    function loadWards(districtId) {
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
    }

    regionSelect.addEventListener('change', function() { loadDistricts(this.value); });
    districtSelect.addEventListener('change', function() { loadWards(this.value); });
    if (regionSelect.value) loadDistricts(regionSelect.value);
});
</script>
@endpush
@endsection
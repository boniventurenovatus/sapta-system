@extends('layouts.sapta')
@section('title', 'Edit Training')
@section('page-title', 'Edit Training')

@section('content')
<style>
    .tfe-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .tfe-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .tfe-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 20px rgba(245,158,11,0.25); }
    .tfe-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .tfe-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .tfe-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; box-shadow: 0 4px 16px rgba(0,0,0,0.04); }
    .tfe-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .tfe-card-head h2 { font-size: 0.75rem; font-weight: 800; color: #f59e0b; margin: 0; text-transform: uppercase; letter-spacing: 0.08em; display: flex; align-items: center; gap: 0.5rem; }
    .tfe-card-body { padding: 1.5rem; }
    .tfe-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .tfe-grid { grid-template-columns: 1fr; } }
    .tfe-group { margin-bottom: 1rem; }
    .tfe-group.full { grid-column: 1 / -1; }
    .tfe-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .tfe-group label .req { color: #dc2626; }
    .tfe-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; background: #fff; }
    .tfe-input:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }
    .tfe-error { color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem; }
    .tfe-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; flex-wrap: wrap; }
    .tfe-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .tfe-btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .tfe-btn-warning:hover { transform: translateY(-1px); color: #fff; }
    .tfe-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .tfe-btn-secondary:hover { background: #f8fafc; color: #1e293b; }
</style>

<div class="tfe-page">
    <div class="tfe-head">
        <div class="tfe-head-icon"><i class="fas fa-pen"></i></div>
        <div>
            <h1>Edit Training</h1>
            <p>Update training information</p>
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

    <form action="{{ route('trainings.update', $training) }}" method="POST">
        @csrf @method('PUT')

        <div class="tfe-card">
            <div class="tfe-card-head"><h2><i class="fas fa-graduation-cap"></i> Training Information</h2></div>
            <div class="tfe-card-body">
                <div class="tfe-grid">
                    <div class="tfe-group full">
                        <label>Title <span class="req">*</span></label>
                        <input type="text" name="title" class="tfe-input" value="{{ old('title', $training->title) }}" required>
                        @error('title') <div class="tfe-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="tfe-group full">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="tfe-input">{{ old('description', $training->description) }}</textarea>
                    </div>
                    <div class="tfe-group">
                        <label>Trainer</label>
                        <input type="text" name="trainer" class="tfe-input" value="{{ old('trainer', $training->trainer) }}">
                    </div>
                    <div class="tfe-group">
                        <label>Location</label>
                        <input type="text" name="location" class="tfe-input" value="{{ old('location', $training->location) }}">
                    </div>
                    <div class="tfe-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="tfe-input" value="{{ old('start_date', $training->start_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="tfe-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="tfe-input" value="{{ old('end_date', $training->end_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="tfe-group">
                        <label>Capacity</label>
                        <input type="number" name="capacity" class="tfe-input" value="{{ old('capacity', $training->capacity) }}" min="1">
                    </div>
                    <div class="tfe-group">
                        <label>Status <span class="req">*</span></label>
                        <select name="status" class="tfe-input" required>
                            @foreach(['planned','ongoing','completed','cancelled'] as $s)
                                <option value="{{ $s }}" @selected(old('status', $training->status) === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="tfe-card">
            <div class="tfe-card-head"><h2><i class="fas fa-map-location-dot"></i> Location</h2></div>
            <div class="tfe-card-body">
                <div class="tfe-grid">
                    <div class="tfe-group">
                        <label>Region</label>
                        <select id="region_id" name="region_id" class="tfe-input">
                            <option value="">-- Select Region --</option>
                            @foreach($regions ?? [] as $r)
                                <option value="{{ $r->id }}" @selected(old('region_id', $training->region_id) == $r->id)>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="tfe-group">
                        <label>District</label>
                        <select id="district_id" name="district_id" class="tfe-input">
                            <option value="">-- Select District --</option>
                            @if($training->district_id)
                                <option value="{{ $training->district_id }}" selected>{{ $training->district?->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="tfe-group">
                        <label>Ward</label>
                        <select id="ward_id" name="ward_id" class="tfe-input">
                            <option value="">-- Select Ward --</option>
                            @if($training->ward_id)
                                <option value="{{ $training->ward_id }}" selected>{{ $training->ward?->name }}</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="tfe-card">
            <div class="tfe-actions">
                <button type="submit" class="tfe-btn tfe-btn-warning"><i class="fas fa-save"></i> Update Training</button>
                <a href="{{ route('trainings.index') }}" class="tfe-btn tfe-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
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

    const savedRegionId = '{{ old('region_id', $training->region_id) }}';
    const savedDistrictId = '{{ old('district_id', $training->district_id) }}';
    const savedWardId = '{{ old('ward_id', $training->ward_id) }}';

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
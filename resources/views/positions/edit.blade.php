@extends('layouts.sapta')

@section('title', 'Edit Position')
@section('page-title', 'Edit Position')

@section('content')
<div class="sapta-form-page">
    <div class="sapta-form-header">
        <div class="sapta-form-header-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);"><i class="fas fa-pen"></i></div>
        <div>
            <h1>Edit Position</h1>
            <p>Update position information.</p>
        </div>
    </div>

    <div class="sapta-form-card">
        <form action="{{ route('positions.update', $position) }}" method="POST">
            @csrf @method('PUT')
            <div class="sapta-form-section">
                <div class="sapta-form-section-title" style="color:#f59e0b;"><i class="fas fa-circle-info"></i> Position Information</div>

                <div class="sapta-form-group">
                    <label for="organizational_unit_id">Organizational Unit <span class="hint">Optional</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-building prefix"></i>
                        <select id="organizational_unit_id" name="organizational_unit_id" class="sapta-form-control">
                            <option value="">-- Select a unit --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" @selected(old('organizational_unit_id', $position->organizational_unit_id) == $unit->id)>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label for="title">Position Title <span class="req">*</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-briefcase prefix"></i>
                        <input type="text" id="title" name="title" class="sapta-form-control @error('title') is-invalid @enderror" value="{{ old('title', $position->title) }}" required>
                    </div>
                    @error('title')<div class="sapta-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                </div>

                <div class="sapta-form-group">
                    <label for="code">Position Code <span class="req">*</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-hashtag prefix"></i>
                        <input type="text" id="code" name="code" class="sapta-form-control @error('code') is-invalid @enderror" value="{{ old('code', $position->code) }}" required>
                    </div>
                    @error('code')<div class="sapta-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                </div>

                <div class="sapta-form-group">
                    <label for="description">Description <span class="hint">Optional</span></label>
                    <textarea id="description" name="description" class="sapta-form-control">{{ old('description', $position->description) }}</textarea>
                </div>

                <div class="sapta-form-group">
                    <label for="region_id">Region <span class="hint">Optional</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-map prefix"></i>
                        <select id="region_id" name="region_id" class="sapta-form-control">
                            <option value="">-- Select Region --</option>
                            @foreach($regions ?? [] as $r)
                                <option value="{{ $r->id }}" @selected(old('region_id', $position->region_id) == $r->id)>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label for="district_id">District <span class="hint">Optional</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-map-location-dot prefix"></i>
                        <select id="district_id" name="district_id" class="sapta-form-control">
                            <option value="">-- Select District --</option>
                            @if($position->district_id)
                                <option value="{{ $position->district_id }}" selected>{{ $position->district?->name }}</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label for="ward_id">Ward <span class="hint">Optional</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-location-crosshairs prefix"></i>
                        <select id="ward_id" name="ward_id" class="sapta-form-control">
                            <option value="">-- Select Ward --</option>
                            @if($position->ward_id)
                                <option value="{{ $position->ward_id }}" selected>{{ $position->ward?->name }}</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label for="status">Status <span class="req">*</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-toggle-on prefix"></i>
                        <select id="status" name="status" class="sapta-form-control" required>
                            <option value="active" @selected(old('status', $position->status) === 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $position->status) === 'inactive')>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="sapta-form-actions">
                <button type="submit" class="sapta-btn sapta-btn-warning"><i class="fas fa-save"></i> Update Position</button>
                <a href="{{ route('positions.index') }}" class="sapta-btn sapta-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
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

    const savedDistrictId = '{{ old('district_id', $position->district_id) }}';
    const savedWardId = '{{ old('ward_id', $position->ward_id) }}';

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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionSelect = document.getElementById('region_id');
    const districtSelect = document.getElementById('district_id');
    const wardSelect = document.getElementById('ward_id');

    const savedDistrictId = '{{ old('district_id', $position->district_id) }}';
    const savedWardId = '{{ old('ward_id', $position->ward_id) }}';

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




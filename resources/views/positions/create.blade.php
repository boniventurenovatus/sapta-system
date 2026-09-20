@extends('layouts.sapta')

@section('title', 'Add Position')
@section('page-title', 'Add Position')

@section('content')
<div class="sapta-form-page">
    <div class="sapta-form-header">
        <div class="sapta-form-header-icon"><i class="fas fa-sitemap"></i></div>
        <div>
            <h1>Add Position</h1>
            <p>Create a new position in your organization.</p>
        </div>
    </div>

    <div class="sapta-form-card">
        <form action="{{ route('positions.store') }}" method="POST">
            @csrf
            <div class="sapta-form-section">
                <div class="sapta-form-section-title"><i class="fas fa-circle-info"></i> Position Information</div>

                <div class="sapta-form-group">
                    <label for="organizational_unit_id">Organizational Unit <span class="hint">Optional</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-building prefix"></i>
                        <select id="organizational_unit_id" name="organizational_unit_id" class="sapta-form-control">
                            <option value="">-- Select a unit --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" @selected(old('organizational_unit_id') == $unit->id)>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label for="title">Position Title <span class="req">*</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-briefcase prefix"></i>
                        <input type="text" id="title" name="title" class="sapta-form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. Accountant" required autofocus>
                    </div>
                    @error('title')<div class="sapta-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                </div>

                <div class="sapta-form-group">
                    <label for="code">Position Code <span class="req">*</span><span class="hint">Auto-generated</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-hashtag prefix"></i>
                        <input type="text" id="code" name="code" class="sapta-form-control @error('code') is-invalid @enderror" value="{{ old('code', $nextCode) }}" required>
                    </div>
                    @error('code')<div class="sapta-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>@enderror
                </div>

                <div class="sapta-form-group">
                    <label for="description">Description <span class="hint">Optional</span></label>
                    <textarea id="description" name="description" class="sapta-form-control" placeholder="Brief description...">{{ old('description') }}</textarea>
                </div>

                <div class="sapta-form-group">
                    <label for="region_id">Region <span class="hint">Optional</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-map prefix"></i>
                        <select id="region_id" name="region_id" class="sapta-form-control">
                            <option value="">-- Select Region --</option>
                            @foreach($regions ?? [] as $r)
                                <option value="{{ $r->id }}" @selected(old('region_id') == $r->id)>{{ $r->name }}</option>
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
                        </select>
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label for="ward_id">Ward <span class="hint">Optional</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-location-crosshairs prefix"></i>
                        <select id="ward_id" name="ward_id" class="sapta-form-control">
                            <option value="">-- Select Ward --</option>
                        </select>
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label for="status">Status <span class="req">*</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-toggle-on prefix"></i>
                        <select id="status" name="status" class="sapta-form-control" required>
                            <option value="active" @selected(old('status') === 'active')>Active</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="sapta-form-actions">
                <button type="submit" class="sapta-btn sapta-btn-primary"><i class="fas fa-save"></i> Save Position</button>
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



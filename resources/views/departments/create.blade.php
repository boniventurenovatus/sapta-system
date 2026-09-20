@extends('layouts.sapta')

@section('title', 'Add Department')
@section('page-title', 'Add Department')

@section('content')
<div style="padding:1.5rem; max-width:900px; margin:0 auto;">
    <h1 style="font-size:1.75rem; font-weight:800; margin:0 0 1.5rem;">Add Department</h1>

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

    <form action="{{ route('departments.store') }}" method="POST">
        @csrf
        <div style="background:#fff; border-radius:1rem; border:1px solid #e2e8f0; padding:1.5rem; margin-bottom:1.5rem;">
            <h2 style="color:#2563eb; font-size:0.85rem; text-transform:uppercase; margin:0 0 1rem;">Department Information</h2>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Name *</label><input type="text" name="name" value="{{ old('name') }}" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Code *</label><input type="text" name="code" value="{{ old('code') }}" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div style="grid-column:1/-1;"><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Organization *</label><select name="organization_id" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="">Select Organization</option>@foreach($organizations as $org)<option value="{{ $org->id }}" @selected(old('organization_id') == $org->id)>{{ $org->name }}</option>@endforeach</select></div>
                <div style="grid-column:1/-1;"><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Description</label><textarea name="description" rows="3" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;">{{ old('description') }}</textarea></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Status *</label><select name="is_active" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="1" @selected(old('is_active', '1') == '1')>Active</option><option value="0" @selected(old('is_active') == '0')>Inactive</option></select></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Region</label><select id="region_id" name="region_id" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="">Select Region</option>@foreach($regions ?? [] as $r)<option value="{{ $r->id }}" @selected(old('region_id') == $r->id)>{{ $r->name }}</option>@endforeach</select></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">District</label><select id="district_id" name="district_id" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="">Select District</option></select></div>
                <div style="grid-column:1/-1;"><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Ward</label><select id="ward_id" name="ward_id" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="">Select Ward</option></select></div>
            </div>
        </div>
        <div style="display:flex; gap:0.75rem;">
            <button type="submit" style="padding:0.7rem 1.5rem; background:#2563eb; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;"><i class="fas fa-save"></i> Save Department</button>
            <a href="{{ route('departments.index') }}" style="padding:0.7rem 1.5rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;">Cancel</a>
        </div>
    </form>
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
            wardSelect.innerHTML = '<option value="">Select Ward</option>';

            if (!regionId) {
                districtSelect.innerHTML = '<option value="">Select District</option>';
                return;
            }

            fetch('/location/districts?region_id=' + regionId)
                .then(res => res.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">Select District</option>';
                    data.forEach(d => {
                        districtSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                    });
                });
        });

        districtSelect.addEventListener('change', function() {
            const districtId = this.value;
            wardSelect.innerHTML = '<option value="">Loading...</option>';

            if (!districtId) {
                wardSelect.innerHTML = '<option value="">Select Ward</option>';
                return;
            }

            fetch('/location/wards?district_id=' + districtId)
                .then(res => res.json())
                .then(data => {
                    wardSelect.innerHTML = '<option value="">Select Ward</option>';
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



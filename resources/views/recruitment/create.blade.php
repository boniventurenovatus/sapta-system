@extends('layouts.sapta')
@section('title', 'New Job Posting')
@section('page-title', 'New Job Posting')
@section('content')
<div style="padding:1.5rem; max-width:1000px; margin:0 auto;">
    <h1 style="font-size:1.75rem; font-weight:800; margin:0 0 1.5rem;">New Job Posting</h1>
    <form action="{{ route('recruitment.store') }}" method="POST">
        @csrf
        <div style="background:#fff; border-radius:1rem; border:1px solid #e2e8f0; padding:1.5rem; margin-bottom:1.5rem;">
            <h2 style="color:#6366f1; font-size:0.85rem; text-transform:uppercase; margin:0 0 1rem;">Job Information</h2>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Job Number</label><input type="text" name="job_number" value="{{ $nextNumber }}" readonly style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem; background:#f8fafc;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Title *</label><input type="text" name="title" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div style="grid-column:1/-1;"><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Description</label><textarea name="description" rows="3" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></textarea></div>
                <div style="grid-column:1/-1;"><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Requirements</label><textarea name="requirements" rows="3" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></textarea></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Department</label><select name="department_id" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="">? None ?</option>@foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Position</label><select name="position_id" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="">? None ?</option>@foreach($positions as $p)<option value="{{ $p->id }}">{{ $p->title }}</option>@endforeach</select></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Employment Type *</label><select name="employment_type" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="full_time">Full Time</option><option value="part_time">Part Time</option><option value="contract">Contract</option><option value="internship">Internship</option></select></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Experience Level *</label><select name="experience_level" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="entry">Entry</option><option value="mid">Mid</option><option value="senior">Senior</option><option value="executive">Executive</option></select></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Vacancies *</label><input type="number" name="vacancies" value="1" min="1" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Location</label><input type="text" name="location" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Salary Min</label><input type="number" name="salary_min" value="0" step="0.01" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Salary Max</label><input type="number" name="salary_max" value="0" step="0.01" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Currency *</label><select name="currency" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="TZS">TZS</option><option value="USD">USD</option></select></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Posted Date *</label><input type="date" name="posted_date" value="{{ date('Y-m-d') }}" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Closing Date *</label><input type="date" name="closing_date" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
            </div>
        </div>
        <div style="display:flex; gap:0.75rem;"><button type="submit" style="padding:0.7rem 1.5rem; background:#6366f1; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;">Create Job Posting</button><a href="{{ route('recruitment.index') }}" style="padding:0.7rem 1.5rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;">Cancel</a></div>
    
                    {{-- LOCATION --}}
                    <div class="form-group">
                        <label>Region</label>
                        <select id="region_id" name="region_id" class="form-control">
                            <option value="">-- Select Region --</option>
                            @foreach($regions ?? [] as $r)
                                <option value="{{ $r->id }}" @selected(old('region_id', '') == $r->id)>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>District</label>
                        <select id="district_id" name="district_id" class="form-control">
                            <option value="">-- Select District --</option>
                            @if(false)
                                <option value="{{ '' }}" selected>{{ '' }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Ward</label>
                        <select id="ward_id" name="ward_id" class="form-control">
                            <option value="">-- Select Ward --</option>
                            @if(false)
                                <option value="{{ '' }}" selected>{{ '' }}</option>
                            @endif
                        </select>
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

    const savedDistrictId = districtSelect.querySelector('option[selected]')?.value || '';
    const savedWardId = wardSelect.querySelector('option[selected]')?.value || '';

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
});
</script>
@endpush

@endsection


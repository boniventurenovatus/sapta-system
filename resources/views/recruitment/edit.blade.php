@extends('layouts.sapta')
@section('title', 'Edit Job Posting')
@section('page-title', 'Edit Job Posting')

@section('content')
<style>
    .jpe-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .jpe-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .jpe-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 20px rgba(245,158,11,0.25); }
    .jpe-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .jpe-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .jpe-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; box-shadow: 0 4px 16px rgba(0,0,0,0.04); }
    .jpe-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .jpe-card-head h2 { font-size: 0.75rem; font-weight: 800; color: #f59e0b; margin: 0; text-transform: uppercase; letter-spacing: 0.08em; display: flex; align-items: center; gap: 0.5rem; }
    .jpe-card-body { padding: 1.5rem; }
    .jpe-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .jpe-grid { grid-template-columns: 1fr; } }
    .jpe-group { margin-bottom: 1rem; }
    .jpe-group.full { grid-column: 1 / -1; }
    .jpe-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .jpe-group label .req { color: #dc2626; }
    .jpe-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; background: #fff; }
    .jpe-input:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }
    .jpe-error { color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem; }
    .jpe-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; flex-wrap: wrap; }
    .jpe-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .jpe-btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .jpe-btn-warning:hover { transform: translateY(-1px); color: #fff; }
    .jpe-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .jpe-btn-secondary:hover { background: #f8fafc; color: #1e293b; }
</style>

<div class="jpe-page">
    <div class="jpe-head">
        <div class="jpe-head-icon"><i class="fas fa-pen"></i></div>
        <div>
            <h1>Edit Job Posting</h1>
            <p>Update job posting information</p>
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

    <form action="{{ route('recruitment.update', $job) }}" method="POST">
        @csrf @method('PUT')

        <div class="jpe-card">
            <div class="jpe-card-head"><h2><i class="fas fa-briefcase"></i> Job Information</h2></div>
            <div class="jpe-card-body">
                <div class="jpe-grid">
                    <div class="jpe-group full">
                        <label>Title <span class="req">*</span></label>
                        <input type="text" name="title" class="jpe-input" value="{{ old('title', $job->title) }}" required>
                        @error('title') <div class="jpe-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="jpe-group full">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="jpe-input">{{ old('description', $job->description) }}</textarea>
                    </div>
                    <div class="jpe-group full">
                        <label>Requirements</label>
                        <textarea name="requirements" rows="3" class="jpe-input">{{ old('requirements', $job->requirements) }}</textarea>
                    </div>
                    <div class="jpe-group">
                        <label>Department</label>
                        <select name="department_id" class="jpe-input">
                            <option value="">-- Select Department --</option>
                            @foreach($departments ?? [] as $d)
                                <option value="{{ $d->id }}" @selected(old('department_id', $job->department_id) == $d->id)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="jpe-group">
                        <label>Position</label>
                        <select name="position_id" class="jpe-input">
                            <option value="">-- Select Position --</option>
                            @foreach($positions ?? [] as $p)
                                <option value="{{ $p->id }}" @selected(old('position_id', $job->position_id) == $p->id)>{{ $p->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="jpe-group">
                        <label>Employment Type</label>
                        <select name="employment_type" class="jpe-input">
                            @foreach(['full_time','part_time','contract','internship'] as $t)
                                <option value="{{ $t }}" @selected(old('employment_type', $job->employment_type) === $t)>{{ ucwords(str_replace('_', ' ', $t)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="jpe-group">
                        <label>Experience Level</label>
                        <select name="experience_level" class="jpe-input">
                            @foreach(['entry','mid','senior'] as $l)
                                <option value="{{ $l }}" @selected(old('experience_level', $job->experience_level) === $l)>{{ ucfirst($l) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="jpe-group">
                        <label>Vacancies</label>
                        <input type="number" name="vacancies" class="jpe-input" value="{{ old('vacancies', $job->vacancies) }}" min="1">
                    </div>
                    <div class="jpe-group">
                        <label>Salary Min</label>
                        <input type="number" name="salary_min" class="jpe-input" value="{{ old('salary_min', $job->salary_min) }}" step="0.01">
                    </div>
                    <div class="jpe-group">
                        <label>Salary Max</label>
                        <input type="number" name="salary_max" class="jpe-input" value="{{ old('salary_max', $job->salary_max) }}" step="0.01">
                    </div>
                    <div class="jpe-group">
                        <label>Posted Date</label>
                        <input type="date" name="posted_date" class="jpe-input" value="{{ old('posted_date', $job->posted_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="jpe-group">
                        <label>Closing Date</label>
                        <input type="date" name="closing_date" class="jpe-input" value="{{ old('closing_date', $job->closing_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="jpe-group">
                        <label>Status <span class="req">*</span></label>
                        <select name="status" class="jpe-input" required>
                            @foreach(['open','closed','draft'] as $s)
                                <option value="{{ $s }}" @selected(old('status', $job->status) === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="jpe-card">
            <div class="jpe-card-head"><h2><i class="fas fa-map-location-dot"></i> Location</h2></div>
            <div class="jpe-card-body">
                <div class="jpe-grid">
                    <div class="jpe-group">
                        <label>Region</label>
                        <select id="region_id" name="region_id" class="jpe-input">
                            <option value="">-- Select Region --</option>
                            @foreach($regions ?? [] as $r)
                                <option value="{{ $r->id }}" @selected(old('region_id', $job->region_id) == $r->id)>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="jpe-group">
                        <label>District</label>
                        <select id="district_id" name="district_id" class="jpe-input">
                            <option value="">-- Select District --</option>
                            @if($job->district_id)
                                <option value="{{ $job->district_id }}" selected>{{ $job->district?->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="jpe-group">
                        <label>Ward</label>
                        <select id="ward_id" name="ward_id" class="jpe-input">
                            <option value="">-- Select Ward --</option>
                            @if($job->ward_id)
                                <option value="{{ $job->ward_id }}" selected>{{ $job->ward?->name }}</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="jpe-card">
            <div class="jpe-actions">
                <button type="submit" class="jpe-btn jpe-btn-warning"><i class="fas fa-save"></i> Update Job Posting</button>
                <a href="{{ route('recruitment.index') }}" class="jpe-btn jpe-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
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

    const savedRegionId = '{{ old('region_id', $job->region_id) }}';
    const savedDistrictId = '{{ old('district_id', $job->district_id) }}';
    const savedWardId = '{{ old('ward_id', $job->ward_id) }}';

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
@extends('layouts.sapta')

@section('title', 'Edit Organization')
@section('page-title', 'Edit Organization')

@section('content')
<style>
    .org-edit-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .org-edit-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .org-edit-header-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 20px rgba(245,158,11,0.25); flex-shrink: 0; }
    .org-edit-header h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .org-edit-header p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .org-edit-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .org-edit-section { padding: 1.75rem; }
    .org-edit-section-title { font-size: 0.75rem; font-weight: 800; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 1.25rem; padding-bottom: 0.75rem; border-bottom: 2px solid #fef3c7; }
    .org-edit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .org-edit-group { margin-bottom: 1rem; }
    .org-edit-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .org-edit-group label .req { color: #dc2626; }
    .org-edit-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; }
    .org-edit-input:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }
    .org-edit-actions { padding: 1.25rem 1.75rem; background: #fafbfc; border-top: 1px solid #f1f5f9; display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .org-edit-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .org-edit-btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .org-edit-btn-warning:hover { transform: translateY(-1px); color: #fff; }
    .org-edit-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .org-edit-btn-secondary:hover { background: #f8fafc; color: #1e293b; }
</style>

<div class="org-edit-page">

    <div class="org-edit-header">
        <div class="org-edit-header-icon"><i class="fas fa-pen"></i></div>
        <div>
            <h1>Edit Organization</h1>
            <p>Update organization information.</p>
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

    <form action="{{ route('organizations.update', $organization) }}" method="POST">
        @csrf @method('PUT')

        <div class="org-edit-card">
            <div class="org-edit-section">
                <div class="org-edit-section-title"><i class="fas fa-info-circle"></i> Basic Information</div>
                <div class="org-edit-grid">
                    <div class="org-edit-group">
                        <label>Name <span class="req">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $organization->name) }}" class="org-edit-input" required>
                    </div>
                    <div class="org-edit-group">
                        <label>Code <span class="req">*</span></label>
                        <input type="text" name="code" value="{{ old('code', $organization->code) }}" class="org-edit-input" required>
                    </div>
                    <div class="org-edit-group">
                        <label>Type <span class="req">*</span></label>
                        <select name="type" class="org-edit-input" required>
                            @foreach(['headquarters','branch','subsidiary'] as $t)
                                <option value="{{ $t }}" @selected(old('type', $organization->type) === $t)>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="org-edit-group">
                        <label>Parent Organization</label>
                        <select name="parent_id" class="org-edit-input">
                            <option value="">None</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}" @selected(old('parent_id', $organization->parent_id) == $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="org-edit-group">
                        <label>Status <span class="req">*</span></label>
                        <select name="is_active" class="org-edit-input" required>
                            <option value="1" @selected(old('is_active', $organization->is_active) == '1')>Active</option>
                            <option value="0" @selected(old('is_active', $organization->is_active) == '0')>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="org-edit-card">
            <div class="org-edit-section">
                <div class="org-edit-section-title"><i class="fas fa-address-book"></i> Contact Information</div>
                <div class="org-edit-grid">
                    <div class="org-edit-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $organization->phone) }}" class="org-edit-input">
                    </div>
                    <div class="org-edit-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $organization->email) }}" class="org-edit-input">
                    </div>
                    <div class="org-edit-group" style="grid-column:1/-1;">
                        <label>Website</label>
                        <input type="text" name="website" value="{{ old('website', $organization->website) }}" class="org-edit-input">
                    </div>
                </div>
            </div>
        </div>

        <div class="org-edit-card">
            <div class="org-edit-section">
                <div class="org-edit-section-title"><i class="fas fa-location-dot"></i> Location</div>
                <div class="org-edit-grid">
                    <div class="org-edit-group" style="grid-column:1/-1;">
                        <label>Address</label>
                        <input type="text" name="address" value="{{ old('address', $organization->address) }}" class="org-edit-input">
                    </div>
                    <div class="org-edit-group">
                        <label>City</label>
                        <input type="text" name="city" value="{{ old('city', $organization->city) }}" class="org-edit-input">
                    </div>
                    <div class="org-edit-group">
                        <label>Region <span class="req">*</span></label>
                        <select name="region_id" id="region_id" class="org-edit-input" required>
                            <option value="">Select Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" @selected(old('region_id', $organization->region_id) == $region->id)>{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="org-edit-group">
                        <label>District <span class="req">*</span></label>
                        <select name="district_id" id="district_id" class="org-edit-input" required>
                            <option value="">Select District</option>
                            @if($organization->district_id)
                                <option value="{{ $organization->district_id }}" selected>{{ $organization->district?->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="org-edit-group">
                        <label>Ward</label>
                        <select name="ward_id" id="ward_id" class="org-edit-input">
                            <option value="">Select Ward</option>
                            @if($organization->ward_id)
                                <option value="{{ $organization->ward_id }}" selected>{{ $organization->ward?->name }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="org-edit-group">
                        <label>Country <span class="req">*</span></label>
                        <input type="text" name="country" value="{{ old('country', $organization->country) }}" class="org-edit-input" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="org-edit-card">
            <div class="org-edit-actions">
                <button type="submit" class="org-edit-btn org-edit-btn-warning"><i class="fas fa-save"></i> Update Organization</button>
                <a href="{{ route('organizations.index') }}" class="org-edit-btn org-edit-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
            </div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionSelect = document.getElementById('region_id');
    const districtSelect = document.getElementById('district_id');
    const wardSelect = document.getElementById('ward_id');

    const savedDistrictId = '{{ old('district_id', $organization->district_id) }}';
    const savedWardId = '{{ old('ward_id', $organization->ward_id) }}';

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
                    const sel = (d.id == savedDistrictId) ? ' selected' : '';
                    districtSelect.innerHTML += `<option value="${d.id}"${sel}>${d.name}</option>`;
                });
                if (savedDistrictId) {
                    districtSelect.dispatchEvent(new Event('change'));
                }
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
                    const sel = (w.id == savedWardId) ? ' selected' : '';
                    wardSelect.innerHTML += `<option value="${w.id}"${sel}>${w.name}</option>`;
                });
            });
    });

    // Trigger on page load kama region ipo
    if (regionSelect.value) {
        regionSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush


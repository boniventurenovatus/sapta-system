@extends('layouts.sapta')

@section('title', 'New Organization')
@section('page-title', 'New Organization')

@section('content')
<style>
    .org-create-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .org-create-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .org-create-header-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 20px rgba(37,99,235,0.25); flex-shrink: 0; }
    .org-create-header h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .org-create-header p { color: #64748b; margin: 0; font-size: 0.9rem; }

    .org-create-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .org-create-section { padding: 1.75rem; }
    .org-create-section-title { font-size: 0.75rem; font-weight: 800; color: #2563eb; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 1.25rem; padding-bottom: 0.75rem; border-bottom: 2px solid #eff6ff; }
    .org-create-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .org-create-group { margin-bottom: 1rem; }
    .org-create-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .org-create-group label .req { color: #dc2626; }
    .org-create-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; }
    .org-create-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .org-create-actions { padding: 1.25rem 1.75rem; background: #fafbfc; border-top: 1px solid #f1f5f9; display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .org-create-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .org-create-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }
    .org-create-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .org-create-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
</style>

<div class="org-create-page">

    <div class="org-create-header">
        <div class="org-create-header-icon"><i class="fas fa-building"></i></div>
        <div>
            <h1>New Organization</h1>
            <p>Create a new organization or branch.</p>
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

    <form action="{{ route('organizations.store') }}" method="POST">
        @csrf

        <div class="org-create-card">
            <div class="org-create-section">
                <div class="org-create-section-title"><i class="fas fa-info-circle"></i> Basic Information</div>
                <div class="org-create-grid">
                    <div class="org-create-group">
                        <label>Name <span class="req">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="org-create-input" required>
                    </div>
                    <div class="org-create-group">
                        <label>Code <span class="req">*</span></label>
                        <input type="text" name="code" value="{{ old('code') }}" class="org-create-input" required>
                    </div>
                    <div class="org-create-group">
                        <label>Type <span class="req">*</span></label>
                        <select name="type" class="org-create-input" required>
                            <option value="headquarters" @selected(old('type') === 'headquarters')>Headquarters</option>
                            <option value="branch" @selected(old('type') === 'branch')>Branch</option>
                            <option value="subsidiary" @selected(old('type') === 'subsidiary')>Subsidiary</option>
                        </select>
                    </div>
                    <div class="org-create-group">
                        <label>Parent Organization</label>
                        <select name="parent_id" class="org-create-input">
                            <option value="">None</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}" @selected(old('parent_id') == $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="org-create-card">
            <div class="org-create-section">
                <div class="org-create-section-title"><i class="fas fa-address-book"></i> Contact Information</div>
                <div class="org-create-grid">
                    <div class="org-create-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="org-create-input">
                    </div>
                    <div class="org-create-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="org-create-input">
                    </div>
                    <div class="org-create-group" style="grid-column:1/-1;">
                        <label>Website</label>
                        <input type="text" name="website" value="{{ old('website') }}" class="org-create-input">
                    </div>
                </div>
            </div>
        </div>

        <div class="org-create-card">
            <div class="org-create-section">
                <div class="org-create-section-title"><i class="fas fa-location-dot"></i> Location</div>
                <div class="org-create-grid">
                    <div class="org-create-group" style="grid-column:1/-1;">
                        <label>Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="org-create-input">
                    </div>
                    <div class="org-create-group">
                        <label>Region <span class="req">*</span></label>
                        <select name="region_id" id="region_id" class="org-create-input" required>
                            <option value="">Select Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" @selected(old('region_id') == $region->id)>{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="org-create-group">
                        <label>District <span class="req">*</span></label>
                        <select name="district_id" id="district_id" class="org-create-input" required>
                            <option value="">Select District</option>
                        </select>
                    </div>
                    <div class="org-create-group">
                        <label>Ward</label>
                        <select name="ward_id" id="ward_id" class="org-create-input">
                            <option value="">Select Ward</option>
                        </select>
                    </div>
                    <div class="org-create-group">
                        <label>Country <span class="req">*</span></label>
                        <input type="text" name="country" value="{{ old('country', 'Tanzania') }}" class="org-create-input" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="org-create-card">
            <div class="org-create-actions">
                <button type="submit" class="org-create-btn org-create-btn-primary"><i class="fas fa-save"></i> Create Organization</button>
                <a href="{{ route('organizations.index') }}" class="org-create-btn org-create-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
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

    if (!regionSelect || !districtSelect || !wardSelect) {
        console.error('DROPDOWNS NOT FOUND!', {regionSelect, districtSelect, wardSelect});
        return;
    }
    console.log('Dropdowns zimepatikana, script inafanya kazi');

    regionSelect.addEventListener('change', function() {
        const regionId = this.value;
        districtSelect.innerHTML = '<option value="">Loading...</option>';
        wardSelect.innerHTML = '<option value="">Select Ward</option>';

        if (!regionId) {
            districtSelect.innerHTML = '<option value="">Select District</option>';
            return;
        }

        console.log('Fetching districts for region:', regionId);
        fetch('/location/districts?region_id=' + regionId)
            .then(res => {
                console.log('Response status:', res.status);
                if (!res.ok) {
                    throw new Error('HTTP ' + res.status + ' ? kama ni 302, haupo logged in');
                }
                return res.json();
            })
            .then(data => {
                console.log('Data received:', data);
                districtSelect.innerHTML = '<option value="">Select District</option>';
                data.forEach(d => {
                    districtSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                });
            })
            .catch(err => {
                console.error('FETCH ERROR:', err);
                districtSelect.innerHTML = '<option value="">ERROR: ' + err.message + '</option>';
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
});
</script>
@endpush
@endsection



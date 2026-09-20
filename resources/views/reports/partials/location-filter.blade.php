{{-- LOCATION FILTER PARTIAL --}}
<div class="filter-card" style="background:#fff; border-radius:16px; border:1px solid #e8ecf1; padding:20px 24px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
    <h6 style="font-size:14px; font-weight:600; color:#1a1a2e; margin:0 0 16px 0; display:flex; align-items:center; gap:8px;">
        <i class="fas fa-filter" style="color:#1a5276;"></i> Filters
    </h6>
    <form method="GET" action="{{ $action ?? url()->current() }}">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:16px; align-items:end;">
            {{-- REGION --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#4a5a6f; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Region</label>
                <select name="region_id" id="filter_region" style="width:100%; padding:10px 14px; border:1.5px solid #e8ecf1; border-radius:10px; font-size:14px; background:#fff;">
                    <option value="">All Regions</option>
                    @foreach($regions ?? [] as $r)
                        <option value="{{ $r->id }}" @selected(request('region_id') == $r->id)>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            {{-- DISTRICT --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#4a5a6f; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">District</label>
                <select name="district_id" id="filter_district" style="width:100%; padding:10px 14px; border:1.5px solid #e8ecf1; border-radius:10px; font-size:14px; background:#fff;">
                    <option value="">All Districts</option>
                    @if(request('district_id'))
                        <option value="{{ request('district_id') }}" selected>{{ \App\Models\District::find(request('district_id'))?->name }}</option>
                    @endif
                </select>
            </div>
            {{-- WARD --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#4a5a6f; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Ward</label>
                <select name="ward_id" id="filter_ward" style="width:100%; padding:10px 14px; border:1.5px solid #e8ecf1; border-radius:10px; font-size:14px; background:#fff;">
                    <option value="">All Wards</option>
                    @if(request('ward_id'))
                        <option value="{{ request('ward_id') }}" selected>{{ \App\Models\Ward::find(request('ward_id'))?->name }}</option>
                    @endif
                </select>
            </div>
            {{-- ACTIONS --}}
            <div style="display:flex; gap:8px;">
                <button type="submit" style="padding:10px 20px; border-radius:10px; font-weight:700; font-size:13px; background:linear-gradient(135deg,#1a5276,#2d8a9e); color:#fff; border:none; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ $action ?? url()->current() }}" style="padding:10px 20px; border-radius:10px; font-weight:700; font-size:13px; background:#fff; color:#4a5a6f; border:1.5px solid #e8ecf1; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                    <i class="fas fa-rotate-left"></i> Clear
                </a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionSelect = document.getElementById('filter_region');
    const districtSelect = document.getElementById('filter_district');
    const wardSelect = document.getElementById('filter_ward');

    if (!regionSelect || !districtSelect || !wardSelect) return;

    const savedDistrictId = '{{ request('district_id') }}';
    const savedWardId = '{{ request('ward_id') }}';

    function loadDistricts(regionId) {
        if (!regionId) {
            districtSelect.innerHTML = '<option value="">All Districts</option>';
            wardSelect.innerHTML = '<option value="">All Wards</option>';
            return;
        }
        districtSelect.innerHTML = '<option value="">Loading...</option>';
        fetch('/location/districts?region_id=' + regionId)
            .then(res => res.json())
            .then(data => {
                districtSelect.innerHTML = '<option value="">All Districts</option>';
                data.forEach(d => {
                    const sel = (d.id == savedDistrictId) ? ' selected' : '';
                    districtSelect.innerHTML += `<option value="${d.id}"${sel}>${d.name}</option>`;
                });
                if (savedDistrictId) loadWards(savedDistrictId);
            });
    }

    function loadWards(districtId) {
        if (!districtId) {
            wardSelect.innerHTML = '<option value="">All Wards</option>';
            return;
        }
        wardSelect.innerHTML = '<option value="">Loading...</option>';
        fetch('/location/wards?district_id=' + districtId)
            .then(res => res.json())
            .then(data => {
                wardSelect.innerHTML = '<option value="">All Wards</option>';
                data.forEach(w => {
                    const sel = (w.id == savedWardId) ? ' selected' : '';
                    wardSelect.innerHTML += `<option value="${w.id}"${sel}>${w.name}</option>`;
                });
            });
    }

    regionSelect.addEventListener('change', function() {
        loadDistricts(this.value);
    });

    districtSelect.addEventListener('change', function() {
        loadWards(this.value);
    });

    if (regionSelect.value) loadDistricts(regionSelect.value);
});
</script>
@endpush
@props([
    'regionId' => null,
    'districtId' => null,
    'wardId' => null,
    'required' => false,
    'showLabels' => true,
])

<div class="location-dropdown-group" data-location-group>
    {{-- REGION --}}
    <div class="location-field">
        @if($showLabels)
            <label class="location-label">Mkoa (Region)</label>
        @endif
        <select name="region_id" class="location-select" data-region-select {{ $required ? 'required' : '' }}>
            <option value="">— Chagua Mkoa —</option>
        </select>
    </div>

    {{-- DISTRICT --}}
    <div class="location-field">
        @if($showLabels)
            <label class="location-label">Wilaya (District)</label>
        @endif
        <select name="district_id" class="location-select" data-district-select {{ $required ? 'required' : '' }}>
            <option value="">— Chagua Wilaya —</option>
        </select>
    </div>

    {{-- WARD --}}
    <div class="location-field">
        @if($showLabels)
            <label class="location-label">Kata (Ward)</label>
        @endif
        <select name="ward_id" class="location-select" data-ward-select {{ $required ? 'required' : '' }}>
            <option value="">— Chagua Kata —</option>
        </select>
    </div>
</div>

<script>
(function() {
    const group = document.currentScript.previousElementSibling;
    if (!group) return;

    const regionSelect = group.querySelector('[data-region-select]');
    const districtSelect = group.querySelector('[data-district-select]');
    const wardSelect = group.querySelector('[data-ward-select]');

    const preselected = {
        region: '{{ $regionId ?? "" }}',
        district: '{{ $districtId ?? "" }}',
        ward: '{{ $wardId ?? "" }}'
    };

    // ============================================================
    // LOAD REGIONS
    // ============================================================
    async function loadRegions() {
        try {
            const res = await fetch('/location/regions', { headers: { 'Accept': 'application/json' }});
            const data = await res.json();
            regionSelect.innerHTML = '<option value="">— Chagua Mkoa —</option>';
            data.forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.id;
                opt.textContent = r.name;
                regionSelect.appendChild(opt);
            });
            if (preselected.region) {
                regionSelect.value = preselected.region;
                await loadDistricts(preselected.region);
            }
        } catch (e) { console.error('Regions error:', e); }
    }

    // ============================================================
    // LOAD DISTRICTS
    // ============================================================
    async function loadDistricts(regionId) {
        districtSelect.innerHTML = '<option value="">— Chagua Wilaya —</option>';
        wardSelect.innerHTML = '<option value="">— Chagua Kata —</option>';
        if (!regionId) return;
        try {
            const res = await fetch('/location/districts?region_id=' + regionId, { headers: { 'Accept': 'application/json' }});
            const data = await res.json();
            data.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id;
                opt.textContent = d.name;
                districtSelect.appendChild(opt);
            });
            if (preselected.district) {
                districtSelect.value = preselected.district;
                await loadWards(preselected.district);
            }
        } catch (e) { console.error('Districts error:', e); }
    }

    // ============================================================
    // LOAD WARDS
    // ============================================================
    async function loadWards(districtId) {
        wardSelect.innerHTML = '<option value="">— Chagua Kata —</option>';
        if (!districtId) return;
        try {
            const res = await fetch('/location/wards?district_id=' + districtId, { headers: { 'Accept': 'application/json' }});
            const data = await res.json();
            data.forEach(w => {
                const opt = document.createElement('option');
                opt.value = w.id;
                opt.textContent = w.name;
                wardSelect.appendChild(opt);
            });
            if (preselected.ward) wardSelect.value = preselected.ward;
        } catch (e) { console.error('Wards error:', e); }
    }

    // ============================================================
    // EVENTS
    // ============================================================
    regionSelect.addEventListener('change', function() {
        preselected.region = this.value;
        preselected.district = '';
        preselected.ward = '';
        loadDistricts(this.value);
    });

    districtSelect.addEventListener('change', function() {
        preselected.district = this.value;
        preselected.ward = '';
        loadWards(this.value);
    });

    // ============================================================
    // INIT
    // ============================================================
    loadRegions();
})();
</script>
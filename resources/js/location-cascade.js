/**
 * Global Location Cascade Dropdowns
 * Inafanya kazi kwa select zote zenye:
 *   name="region_id", name="district_id", name="ward_id"
 */
(function() {
    'use strict';

    console.log('[LocationCascade] Initializing...');

    async function fetchJson(url) {
        try {
            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return await res.json();
        } catch (e) {
            console.error('[LocationCascade] Fetch error:', url, e);
            return [];
        }
    }

    function populateSelect(select, items, placeholder, selectedValue) {
        if (!select) return;
        const currentVal = selectedValue || select.value || select.dataset.selected || '';
        select.innerHTML = '<option value="">' + placeholder + '</option>';
        items.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.name;
            if (String(item.id) === String(currentVal)) {
                opt.selected = true;
            }
            select.appendChild(opt);
        });
    }

    function setupCascade(regionSelect, districtSelect, wardSelect) {
        if (!regionSelect) return;

        const savedRegion = regionSelect.dataset.selected || regionSelect.value || '';
        const savedDistrict = districtSelect ? (districtSelect.dataset.selected || districtSelect.value || '') : '';
        const savedWard = wardSelect ? (wardSelect.dataset.selected || wardSelect.value || '') : '';

        // --- LOAD REGIONS ---
        fetchJson('/location/regions').then(regions => {
            populateSelect(regionSelect, regions, '-- Select Region --', savedRegion);

            if (regionSelect.value) {
                loadDistricts(regionSelect.value, savedDistrict);
            }
        });

        // --- LOAD DISTRICTS ---
        async function loadDistricts(regionId, selectedDistrict) {
            if (!districtSelect) return;
            districtSelect.innerHTML = '<option value="">-- Loading... --</option>';
            if (wardSelect) wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';
            if (!regionId) {
                districtSelect.innerHTML = '<option value="">-- Select District --</option>';
                return;
            }
            const districts = await fetchJson('/location/districts?region_id=' + regionId);
            populateSelect(districtSelect, districts, '-- Select District --', selectedDistrict);

            if (districtSelect.value) {
                loadWards(districtSelect.value, savedWard);
            }
        }

        // --- LOAD WARDS ---
        async function loadWards(districtId, selectedWard) {
            if (!wardSelect) return;
            wardSelect.innerHTML = '<option value="">-- Loading... --</option>';
            if (!districtId) {
                wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';
                return;
            }
            const wards = await fetchJson('/location/wards?district_id=' + districtId);
            populateSelect(wardSelect, wards, '-- Select Ward --', selectedWard);
        }

        // --- EVENTS ---
        regionSelect.addEventListener('change', function() {
            if (districtSelect) districtSelect.dataset.selected = '';
            if (wardSelect) wardSelect.dataset.selected = '';
            loadDistricts(this.value);
        });

        if (districtSelect) {
            districtSelect.addEventListener('change', function() {
                if (wardSelect) wardSelect.dataset.selected = '';
                loadWards(this.value);
            });
        }
    }

    function initAll() {
        const regionSelects = document.querySelectorAll('select[name="region_id"]');
        console.log('[LocationCascade] Found', regionSelects.length, 'region selects');

        regionSelects.forEach(regionSelect => {
            const form = regionSelect.closest('form') || document;
            const districtSelect = form.querySelector('select[name="district_id"]');
            const wardSelect = form.querySelector('select[name="ward_id"]');

            setupCascade(regionSelect, districtSelect, wardSelect);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }
})();
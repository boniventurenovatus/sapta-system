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
            <label class="location-label">Region</label>
        @endif
        <select name="region_id" class="location-select" data-region-select data-selected="{{ $regionId ?? '' }}" {{ $required ? 'required' : '' }}>
            <option value="">-- Select Region --</option>
        </select>
    </div>

    {{-- DISTRICT --}}
    <div class="location-field">
        @if($showLabels)
            <label class="location-label">District</label>
        @endif
        <select name="district_id" class="location-select" data-district-select data-selected="{{ $districtId ?? '' }}" {{ $required ? 'required' : '' }}>
            <option value="">-- Select District --</option>
        </select>
    </div>

    {{-- WARD --}}
    <div class="location-field">
        @if($showLabels)
            <label class="location-label">Ward</label>
        @endif
        <select name="ward_id" class="location-select" data-ward-select data-selected="{{ $wardId ?? '' }}" {{ $required ? 'required' : '' }}>
            <option value="">-- Select Ward --</option>
        </select>
    </div>
</div>
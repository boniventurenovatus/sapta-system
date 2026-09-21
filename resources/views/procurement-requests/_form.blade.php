<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title', $procurementRequest->title ?? '') }}" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="e.g., Office Supplies Q1 2026">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
        <textarea name="description" rows="3"
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="Describe the items needed...">{{ old('description', $procurementRequest->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Category <span class="text-red-500">*</span></label>
        <select name="category" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            @foreach(['Office Supplies', 'Equipment', 'Furniture', 'IT Services', 'Consultancy', 'Other'] as $cat)
                <option value="{{ $cat }}" {{ old('category', $procurementRequest->category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Quantity <span class="text-red-500">*</span></label>
        <input type="number" name="quantity" value="{{ old('quantity', $procurementRequest->quantity ?? 1) }}" min="1" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Estimated Cost (TZS) <span class="text-red-500">*</span></label>
        <input type="number" name="estimated_cost" value="{{ old('estimated_cost', $procurementRequest->estimated_cost ?? '') }}" min="0.01" step="0.01" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="0.00">
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Priority <span class="text-red-500">*</span></label>
        <select name="priority" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical'] as $val => $label)
                <option value="{{ $val }}" {{ old('priority', $procurementRequest->priority ?? 'medium') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Required Date <span class="text-red-500">*</span></label>
        <input type="date" name="required_date" value="{{ old('required_date', isset($procurementRequest) ? $procurementRequest->required_date?->format('Y-m-d') : date('Y-m-d', strtotime('+7 days'))) }}" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
    </div>
</div>
{{-- LOCATION INFORMATION --}}
<div class="bg-slate-50 border border-slate-200 rounded-xl p-6 mt-6">
    <h3 class="text-sm font-bold text-slate-700 mb-4">
        <i class="fas fa-map-marker-alt" style="color:#2563eb;"></i>
        Location Information
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- REGION --}}
        <div>
            <label for="region_id" class="block text-sm font-semibold text-slate-700 mb-2">
                Region <span style="color:#dc2626;">*</span>
            </label>
            <select name="region_id" id="region_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">Select Region</option>
                @foreach($regions ?? [] as $region)
                    <option value="{{ $region->id }}" @selected(old('region_id') == $region->id)>{{ $region->name }}</option>
                @endforeach
            </select>
            @error('region_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- DISTRICT --}}
        <div>
            <label for="district_id" class="block text-sm font-semibold text-slate-700 mb-2">
                District <span style="color:#dc2626;">*</span>
            </label>
            <select name="district_id" id="district_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">Select District</option>
                @foreach($districts ?? [] as $district)
                    <option value="{{ $district->id }}" @selected(old('district_id') == $district->id)>{{ $district->name }}</option>
                @endforeach
            </select>
            @error('district_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- WARD --}}
        <div>
            <label for="ward_id" class="block text-sm font-semibold text-slate-700 mb-2">
                Ward
            </label>
            <select name="ward_id" id="ward_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">Select Ward</option>
                @foreach($wards ?? [] as $ward)
                    <option value="{{ $ward->id }}" @selected(old('ward_id') == $ward->id)>{{ $ward->name }}</option>
                @endforeach
            </select>
            @error('ward_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Data kutoka Controller
    const districtsData = @json($districts ?? []);
    const wardsData = @json($wards ?? []);

    const regionSelect = document.getElementById('region_id');
    const districtSelect = document.getElementById('district_id');
    const wardSelect = document.getElementById('ward_id');

    if (!regionSelect || !districtSelect || !wardSelect) {
        console.warn('Location dropdowns hazipo');
        return;
    }

    // Thamani za awali
    const oldDistrictId = '{{ old('district_id') }}';
    const oldWardId = '{{ old('ward_id') }}';

    function populateDistricts(regionId, selectedDistrictId = null) {
        districtSelect.innerHTML = '<option value="">Select District</option>';
        wardSelect.innerHTML = '<option value="">Select Ward</option>';

        if (!regionId) return;

        const filtered = districtsData.filter(d => d.region_id == regionId);
        filtered.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = d.name;
            if (selectedDistrictId && d.id == selectedDistrictId) {
                opt.selected = true;
            }
            districtSelect.appendChild(opt);
        });
    }

    function populateWards(districtId, selectedWardId = null) {
        wardSelect.innerHTML = '<option value="">Select Ward</option>';

        if (!districtId) return;

        const filtered = wardsData.filter(w => w.district_id == districtId);
        filtered.forEach(w => {
            const opt = document.createElement('option');
            opt.value = w.id;
            opt.textContent = w.name;
            if (selectedWardId && w.id == selectedWardId) {
                opt.selected = true;
            }
            wardSelect.appendChild(opt);
        });
    }

    // Region change
    regionSelect.addEventListener('change', function () {
        populateDistricts(this.value);
    });

    // District change
    districtSelect.addEventListener('change', function () {
        populateWards(this.value);
    });

    // On load
    if (regionSelect.value) {
        populateDistricts(regionSelect.value, oldDistrictId);
    }
    if (districtSelect.value) {
        populateWards(districtSelect.value, oldWardId);
    }
});
</script>
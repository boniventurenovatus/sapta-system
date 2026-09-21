<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Employee <span class="text-red-500">*</span></label>
        <select name="employee_id" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
            <option value="">Select Employee</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" {{ old('employee_id', $expenseClaim->employee_id ?? '') == $emp->id ? 'selected' : '' }}>
                    {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_number }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Claim Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title', $expenseClaim->title ?? '') }}" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none"
            placeholder="e.g., Transport costs for field visit">
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Category <span class="text-red-500">*</span></label>
        <select name="category" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
            @foreach(['transport' => 'Transport', 'accommodation' => 'Accommodation', 'meals' => 'Meals', 'supplies' => 'Supplies', 'communication' => 'Communication', 'other' => 'Other'] as $val => $label)
                <option value="{{ $val }}" {{ old('category', $expenseClaim->category ?? 'other') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Amount <span class="text-red-500">*</span></label>
        <input type="number" name="amount" value="{{ old('amount', $expenseClaim->amount ?? '') }}" min="0.01" step="0.01" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none"
            placeholder="0.00">
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Currency <span class="text-red-500">*</span></label>
        <select name="currency" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
            <option value="TZS">TZS</option>
            <option value="USD">USD</option>
            <option value="EUR">EUR</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Expense Date <span class="text-red-500">*</span></label>
        <input type="date" name="expense_date" value="{{ old('expense_date', isset($expenseClaim) ? $expenseClaim->expense_date?->format('Y-m-d') : date('Y-m-d')) }}" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
        <select name="payment_method" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
            <option value="cash" {{ old('payment_method', $expenseClaim->payment_method ?? 'cash') === 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="bank_transfer" {{ old('payment_method', $expenseClaim->payment_method ?? '') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
            <option value="mobile_money" {{ old('payment_method', $expenseClaim->payment_method ?? '') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
            <option value="cheque" {{ old('payment_method', $expenseClaim->payment_method ?? '') === 'cheque' ? 'selected' : '' }}>Cheque</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Receipt Number</label>
        <input type="text" name="receipt_number" value="{{ old('receipt_number', $expenseClaim->receipt_number ?? '') }}"
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none"
            placeholder="Receipt #">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Project</label>
        <select name="project" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
            <option value="">Select Project (optional)</option>
            @foreach($projects as $project)
                <option value="{{ $project->name }}" {{ old('project', $expenseClaim->project ?? '') === $project->name ? 'selected' : '' }}>{{ $project->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Description <span class="text-red-500">*</span></label>
        <textarea name="description" rows="4" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 outline-none"
            placeholder="Describe the expense...">{{ old('description', $expenseClaim->description ?? '') }}</textarea>
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
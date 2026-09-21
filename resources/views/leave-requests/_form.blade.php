<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Employee <span class="text-red-500">*</span></label>
        <select name="employee_id" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">Select Employee</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" {{ old('employee_id', $leaveRequest->employee_id ?? '') == $emp->id ? 'selected' : '' }}>
                    {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_number }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Leave Type <span class="text-red-500">*</span></label>
        <select name="leave_type" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            @foreach(['annual' => 'Annual Leave', 'sick' => 'Sick Leave', 'maternity' => 'Maternity Leave', 'paternity' => 'Paternity Leave', 'study' => 'Study Leave', 'other' => 'Other'] as $val => $label)
                <option value="{{ $val }}" {{ old('leave_type', $leaveRequest->leave_type ?? 'annual') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Start Date <span class="text-red-500">*</span></label>
        <input type="date" name="start_date" value="{{ old('start_date', isset($leaveRequest) ? $leaveRequest->start_date?->format('Y-m-d') : date('Y-m-d')) }}" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">End Date <span class="text-red-500">*</span></label>
        <input type="date" name="end_date" value="{{ old('end_date', isset($leaveRequest) ? $leaveRequest->end_date?->format('Y-m-d') : date('Y-m-d')) }}" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Reason <span class="text-red-500">*</span></label>
        <textarea name="reason" rows="4" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="Reason for leave...">{{ old('reason', $leaveRequest->reason ?? '') }}</textarea>
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

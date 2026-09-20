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
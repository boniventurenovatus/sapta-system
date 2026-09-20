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
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Payee Name <span class="text-red-500">*</span></label>
        <input type="text" name="payee_name" value="{{ old('payee_name') }}" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="e.g., TANESCO">
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Payee Type <span class="text-red-500">*</span></label>
        <select name="payee_type" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="individual">Individual</option>
            <option value="company">Company</option>
            <option value="government">Government</option>
            <option value="ngo">NGO</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Payee Contact</label>
        <input type="text" name="payee_contact" value="{{ old('payee_contact') }}"
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="Phone or email">
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Amount <span class="text-red-500">*</span></label>
        <input type="number" name="amount" value="{{ old('amount') }}" min="0.01" step="0.01" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="0.00">
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Currency <span class="text-red-500">*</span></label>
        <select name="currency" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="TZS">TZS</option>
            <option value="USD">USD</option>
            <option value="EUR">EUR</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
        <select name="payment_method" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="bank_transfer">Bank Transfer</option>
            <option value="cash">Cash</option>
            <option value="cheque">Cheque</option>
            <option value="mobile_money">Mobile Money</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Payment Date <span class="text-red-500">*</span></label>
        <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Project</label>
        <select name="project_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">Select Project (optional)</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Department</label>
        <select name="department_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">Select Department (optional)</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
        <textarea name="description" rows="3"
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="Purpose of this payment...">{{ old('description') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-slate-700 mb-2">Notes</label>
        <textarea name="notes" rows="2"
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="Additional notes...">{{ old('notes') }}</textarea>
    </div>
</div>
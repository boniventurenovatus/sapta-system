{{-- ============================================================ --}}
{{-- PAYMENT VOUCHER FORM — PDF Layout + Live Validation        --}}
{{-- ============================================================ --}}



<div class="bg-white border-2 border-slate-800 p-8 text-sm">

    {{-- HEADER --}}
    <div class="text-center border-b-2 border-slate-800 pb-4 mb-4">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-widest">PAYMENT VOUCHER</h1>
        <div class="text-base font-bold text-slate-800 mt-1">SOIL- ANIMALS' POWER TANZANIA</div>
        <div class="text-xs text-slate-600 mt-1">P.O Box 149, Morogoro, Tanzania Kihonda- Kilimanjaro</div>
        <img src="{{ asset('images/sapta-logo.png') }}" alt="SAPTA" class="mx-auto mt-3" style="height:85px; width:auto;">
    </div>

    {{-- INFO FIELDS --}}
    <div class="mb-4 space-y-4 text-sm">

        {{-- Row 1: Date + Trans No --}}
        <div class="flex gap-6">
            <div class="flex-1 flex items-center gap-3">
                <span class="font-bold w-32">Date:</span>
                <input type="date" name="payment_date" value="{{ old('payment_date', isset($paymentVoucher) && $paymentVoucher->payment_date ? \Carbon\Carbon::parse($paymentVoucher->payment_date)->format('Y-m-d') : date('Y-m-d')) }}" required
                    class="flex-1 px-2 py-1 border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 outline-none text-sm">
            </div>
            <div class="flex-1 flex items-center gap-3">
                <span class="font-bold w-32">Trans No:</span>
                <input type="text" name="trans_no" value="{{ old('trans_no', $paymentVoucher->trans_no ?? '') }}" readonly placeholder="Auto-generated"
                    class="flex-1 px-2 py-1 border border-slate-300 rounded bg-slate-100 text-slate-600 font-mono text-sm">
            </div>
        </div>

        {{-- Row 2: Payee + P.O Box --}}
        <div class="flex gap-6">
            <div class="flex-1 flex flex-col">
                
<div class="flex items-center gap-2">
                    <span class="font-bold w-32">Name of Payee:</span>
                    <input type="text" name="payee_name" value="{{ old('payee_name', $paymentVoucher->payee_name ?? '') }}" required
                        class="flex-1 px-2 py-1 border border-slate-300 rounded text-sm" placeholder="e.g., LUNEX INVESTMENT"
                        data-validate="payee_name" data-error-target="payee_name-error">
                </div>
                <div id="payee_name-error" style="display:none; color:#dc2626; font-size:11px; margin-top:4px; margin-left:7.5rem; font-weight:600;"></div>
            </div>
            <div class="flex-1 flex flex-col">
                <div class="flex items-center gap-2">
                    <span class="font-bold w-32">P.O Box:</span>
                    <input type="text" name="payee_pobox" value="{{ old('payee_pobox', $paymentVoucher->payee_pobox ?? '') }}"
                        class="flex-1 px-2 py-1 border border-slate-300 rounded text-sm" placeholder="P.O Box 356, MOROGORO"
                        data-validate="payee_pobox" data-error-target="payee_pobox-error">
                </div>
                <div id="payee_pobox-error" style="display:none; color:#dc2626; font-size:11px; margin-top:4px; margin-left:7.5rem; font-weight:600;"></div>
            </div>
        </div>

        {{-- Row 3: Batch + Bank + Currency --}}
        <div class="flex gap-6">
            <div class="flex-1 flex flex-col">
                <div class="flex items-center gap-2">
                    <span class="font-bold w-32">Batch:</span>
                    <input type="text" name="batch" value="{{ old('batch', $paymentVoucher->batch ?? '') }}" readonly placeholder="Auto-generated"
                        class="flex-1 px-2 py-1 border border-slate-300 rounded bg-slate-100 text-slate-600 font-mono text-sm">
                </div>
            </div>
            <div class="flex-1 flex flex-col">
                <div class="flex items-center gap-2">
                    <span class="font-bold w-32">Bank:</span>
                    <input type="text" name="bank" value="{{ old('bank', $paymentVoucher->bank ?? '') }}"
                        class="flex-1 px-2 py-1 border border-slate-300 rounded text-sm" placeholder="1020"
                        data-validate="bank" data-error-target="bank-error">
                </div>
                <div id="bank-error" style="display:none; color:#dc2626; font-size:11px; margin-top:4px; margin-left:7.5rem; font-weight:600;"></div>
            </div>
            <div class="w-44 flex flex-col">
                <div class="flex items-center gap-2">
                    <span class="font-bold w-20">Currency:</span>
                    <select name="currency" required class="flex-1 px-2 py-1 border border-slate-300 rounded text-sm">
                        <option value="TZS" {{ old('currency', $paymentVoucher->currency ?? 'TZS') == 'TZS' ? 'selected' : '' }}>TZS</option>
                        <option value="USD" {{ old('currency', $paymentVoucher->currency ?? '') == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('currency', $paymentVoucher->currency ?? '') == 'EUR' ? 'selected' : '' }}>EUR</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Row 4: Mode + Cheque Number --}}
        <div class="flex gap-6">
            <div class="flex-1 flex items-center gap-3">
                <span class="font-bold w-32">Mode:</span>
                <select name="mode" required class="flex-1 px-2 py-1 border border-slate-300 rounded text-sm">
                    <option value="transfer" {{ old('mode', $paymentVoucher->mode ?? '') == 'transfer' ? 'selected' : '' }}>TRANSFER</option>
                    <option value="cheque" {{ old('mode', $paymentVoucher->mode ?? '') == 'cheque' ? 'selected' : '' }}>CHEQUE</option>
                    <option value="cash" {{ old('mode', $paymentVoucher->mode ?? '') == 'cash' ? 'selected' : '' }}>CASH</option>
                    <option value="mobile_money" {{ old('mode', $paymentVoucher->mode ?? '') == 'mobile_money' ? 'selected' : '' }}>MOBILE MONEY</option>
                </select>
            </div>
            <div class="flex-1 flex flex-col">
                <div class="flex items-center gap-2">
                    <span class="font-bold w-32">Cheque Number:</span>
                    <input type="text" name="cheque_number" value="{{ old('cheque_number', $paymentVoucher->cheque_number ?? '') }}"
                        class="flex-1 px-2 py-1 border border-slate-300 rounded text-sm" placeholder="if there"
                        data-validate="cheque_number" data-error-target="cheque_number-error">
                </div>
                <div id="cheque_number-error" style="display:none; color:#dc2626; font-size:11px; margin-top:4px; margin-left:8rem; font-weight:600;"></div>
            </div>
        </div>

        {{-- Row 5: Payee Type + Contact --}}
        <div class="flex gap-6">
            <div class="flex-1 flex items-center gap-3">
                <span class="font-bold w-32">Payee Type:</span>
                <select name="payee_type" required class="flex-1 px-2 py-1 border border-slate-300 rounded text-sm">
                    <option value="individual" {{ old('payee_type', $paymentVoucher->payee_type ?? '') == 'individual' ? 'selected' : '' }}>Individual</option>
                    <option value="company" {{ old('payee_type', $paymentVoucher->payee_type ?? '') == 'company' ? 'selected' : '' }}>Company</option>
                    <option value="government" {{ old('payee_type', $paymentVoucher->payee_type ?? '') == 'government' ? 'selected' : '' }}>Government</option>
                    <option value="ngo" {{ old('payee_type', $paymentVoucher->payee_type ?? '') == 'ngo' ? 'selected' : '' }}>NGO</option>
                </select>
            </div>
            <div class="flex-1 flex flex-col">
                <div class="flex items-center gap-2">
                    <span class="font-bold w-32">Contact:</span>
                    <input type="text" name="payee_contact" value="{{ old('payee_contact', $paymentVoucher->payee_contact ?? '') }}"
                        class="flex-1 px-2 py-1 border border-slate-300 rounded text-sm" placeholder="Phone or email"
                        data-validate="payee_contact" data-error-target="payee_contact-error">
                </div>
                <div id="payee_contact-error" style="display:none; color:#dc2626; font-size:11px; margin-top:4px; margin-left:7.5rem; font-weight:600;"></div>
            </div>
        </div>
    </div>

    {{-- LINE ITEMS TABLE --}}
    <div class="border-2 border-slate-800 mb-3">
        <table class="w-full" id="items-table">
            <thead class="bg-slate-100 border-b-2 border-slate-800">
                <tr>
                    <th class="text-left py-2 px-3 text-xs font-extrabold text-slate-800 uppercase border-r border-slate-300" style="width:20%">Account/Invoice No</th>
                    <th class="text-left py-2 px-3 text-xs font-extrabold text-slate-800 uppercase border-r border-slate-300" style="width:55%">Details</th>
                    <th class="text-right py-2 px-3 text-xs font-extrabold text-slate-800 uppercase border-r border-slate-300" style="width:20%">Amount TZS</th>
                    <th class="text-center py-2 px-3 text-xs font-extrabold text-slate-800 uppercase" style="width:5%"></th>
                </tr>
            </thead>
            <tbody id="items-body">
                @php
                    $items = old('items', isset($paymentVoucher) && $paymentVoucher->items && $paymentVoucher->items->count() > 0
                        ? $paymentVoucher->items->map(fn($i) => ['account_invoice_no' => $i->account_invoice_no, 'details' => $i->details, 'amount' => $i->amount])->toArray()
                        : [['account_invoice_no' => '', 'details' => '', 'amount' => '']]);
                @endphp

                @foreach($items as $index => $item)
                    <tr class="item-row border-b border-slate-200">
                        <td class="py-1 px-2 border-r border-slate-200">
                            <input type="text" name="items[{{ $index }}][account_invoice_no]" value="{{ $item['account_invoice_no'] ?? '' }}"
                                class="w-full px-2 py-1 border-0 outline-none text-sm bg-transparent" placeholder="Invoice 0106">
                        </td>
                        <td class="py-1 px-2 border-r border-slate-200">
                            <input type="text" name="items[{{ $index }}][details]" value="{{ $item['details'] ?? '' }}"
                                class="w-full px-2 py-1 border-0 outline-none text-sm bg-transparent" placeholder="Office utilities, Training equipment...">
                        </td>
                        <td class="py-1 px-2 border-r border-slate-200">
                            <input type="number" name="items[{{ $index }}][amount]" value="{{ $item['amount'] ?? '' }}" step="0.01" min="0"
                                class="w-full px-2 py-1 border-0 outline-none text-sm text-right bg-transparent item-amount"
                                placeholder="0.00" onchange="calcTotal()">
                        </td>
                        <td class="py-1 px-2 text-center">
                            <button type="button" onclick="removeItem(this)" class="w-6 h-6 rounded bg-red-100 hover:bg-red-600 text-red-600 hover:text-white text-xs transition">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t-2 border-slate-800">
                <tr>
                    <td colspan="2" class="py-2 px-3 text-xs font-bold text-slate-700">
                        Amount in Words:
                        <div class="font-semibold text-slate-900 mt-0.5" id="amount-words">—</div>
                    </td>
                    <td class="py-2 px-3 text-right">
                        <div class="text-xs font-bold text-slate-700">TOTAL</div>
                        <div class="text-lg font-extrabold text-slate-900" id="total-display">0.00</div>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mb-3 text-right">
        <button type="button" onclick="addItem()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded text-xs">
            <i class="fas fa-plus"></i> Add Row
        </button>
    </div>

    <input type="hidden" name="amount" id="hidden-total" value="0">

    {{-- SIGNATURES TABLE --}}
    <div class="border-2 border-slate-800">
        <table class="w-full">
            <thead class="bg-slate-100 border-b-2 border-slate-800">
                <tr>
                    <th class="text-left py-2 px-3 text-xs font-extrabold text-slate-800 uppercase border-r border-slate-300" style="width:40%">Name</th>
                    <th class="text-left py-2 px-3 text-xs font-extrabold text-slate-800 uppercase border-r border-slate-300" style="width:35%">Signature</th>
                    <th class="text-left py-2 px-3 text-xs font-extrabold text-slate-800 uppercase" style="width:25%">Date</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-slate-200">
                    <td class="py-1 px-2 border-r border-slate-200">
                        <span class="font-bold text-xs">Prepared By:</span>
                        <select name="prepared_by_id" class="w-full px-1 py-0.5 border-0 text-sm bg-transparent outline-none mt-0.5">
                            <option value="">— Select —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('prepared_by_id', $paymentVoucher->prepared_by_id ?? auth()->id()) == $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="py-1 px-2 border-r border-slate-200 text-center">
                        <div style="height:30px;"></div>
                    </td>
                    <td class="py-1 px-2">
                        <input type="date" name="prepared_at" value="{{ old('prepared_at', isset($paymentVoucher) && $paymentVoucher->prepared_at ? \Carbon\Carbon::parse($paymentVoucher->prepared_at)->format('Y-m-d') : date('Y-m-d')) }}"
                            class="w-full px-1 py-0.5 border-0 text-sm bg-transparent outline-none">
                    </td>
                </tr>
                <tr class="border-b border-slate-200">
                    <td class="py-1 px-2 border-r border-slate-200">
                        <span class="font-bold text-xs">Checked By:</span>
                        <select name="checked_by_id" class="w-full px-1 py-0.5 border-0 text-sm bg-transparent outline-none mt-0.5">
                            <option value="">— Select —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('checked_by_id', $paymentVoucher->checked_by_id ?? '') == $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="py-1 px-2 border-r border-slate-200 text-center">
                        <div style="height:30px;"></div>
                    </td>
                    <td class="py-1 px-2">
                        <input type="date" name="checked_at" value="{{ old('checked_at', isset($paymentVoucher) && $paymentVoucher->checked_at ? \Carbon\Carbon::parse($paymentVoucher->checked_at)->format('Y-m-d') : '') }}"
                            class="w-full px-1 py-0.5 border-0 text-sm bg-transparent outline-none">
                    </td>
                </tr>
                <tr>
                    <td class="py-1 px-2 border-r border-slate-200">
                        <span class="font-bold text-xs">Authorized By:</span>
                        <select name="authorized_by_id" class="w-full px-1 py-0.5 border-0 text-sm bg-transparent outline-none mt-0.5">
                            <option value="">— Select —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('authorized_by_id', $paymentVoucher->authorized_by_id ?? '') == $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="py-1 px-2 border-r border-slate-200 text-center">
                        <div style="height:30px;"></div>
                    </td>
                    <td class="py-1 px-2">
                        <input type="date" name="authorized_at" value="{{ old('authorized_at', isset($paymentVoucher) && $paymentVoucher->authorized_at ? \Carbon\Carbon::parse($paymentVoucher->authorized_at)->format('Y-m-d') : '') }}"
                            class="w-full px-1 py-0.5 border-0 text-sm bg-transparent outline-none">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- RECEIVED BY --}}
    <div class="mt-4 flex gap-6 items-center">
        <div class="flex-1 flex items-center gap-3">
            <span class="font-bold text-sm">Received BY:</span>
            <input type="text" name="received_by_name" value="{{ old('received_by_name', $paymentVoucher->received_by_name ?? '') }}"
                class="flex-1 px-2 py-1 border-b border-slate-400 outline-none text-sm" placeholder="Name of receiver">
        </div>
        <div class="flex-1 flex items-center gap-3">
            <span class="font-bold text-sm">Signature:</span>
            <div class="flex-1 border-b border-slate-400" style="height:24px;"></div>
        </div>
    </div>

</div>

<script>
// ============================================================
// LINE ITEMS
// ============================================================
let itemIndex = {{ count($items) }};

function addItem() {
    const tbody = document.getElementById('items-body');
    const row = document.createElement('tr');
    row.className = 'item-row border-b border-slate-200';
    row.innerHTML = `
        <td class="py-1 px-2 border-r border-slate-200">
            <input type="text" name="items[${itemIndex}][account_invoice_no]" class="w-full px-2 py-1 border-0 outline-none text-sm bg-transparent" placeholder="Invoice 0106">
        </td>
        <td class="py-1 px-2 border-r border-slate-200">
            <input type="text" name="items[${itemIndex}][details]" class="w-full px-2 py-1 border-0 outline-none text-sm bg-transparent" placeholder="Details">
        </td>
        <td class="py-1 px-2 border-r border-slate-200">
            <input type="number" name="items[${itemIndex}][amount]" step="0.01" min="0" class="w-full px-2 py-1 border-0 outline-none text-sm text-right bg-transparent item-amount" placeholder="0.00" onchange="calcTotal()">
        </td>
        <td class="py-1 px-2 text-center">
            <button type="button" onclick="removeItem(this)" class="w-6 h-6 rounded bg-red-100 hover:bg-red-600 text-red-600 hover:text-white text-xs transition"><i class="fas fa-times"></i></button>
        </td>
    `;
    tbody.appendChild(row);
    itemIndex++;
}

function removeItem(btn) {
    const row = btn.closest('tr');
    const tbody = document.getElementById('items-body');
    if (tbody.children.length > 1) { row.remove(); calcTotal(); }
    else { alert('At least one item is required.'); }
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('.item-amount').forEach(input => { total += parseFloat(input.value) || 0; });
    document.getElementById('total-display').textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('hidden-total').value = total;
}

document.addEventListener('DOMContentLoaded', calcTotal);

// ============================================================
// LIVE VALIDATION
// ============================================================
(function() {
    const rules = {
        bank: { test: (v) => /^[0-9]{1,15}$/.test(v), message: 'Bank must contain numbers only', optional: true },
        cheque_number: { test: (v) => /^[0-9]{1,15}$/.test(v), message: 'Cheque Number must contain numbers only', optional: true },
        payee_name: { test: (v) => v.length >= 2 && /^[a-zA-Z0-9\s\-\.\'&,()]+$/.test(v), message: 'Only letters, numbers, spaces, and - . \' & , ( )', optional: false },
        payee_pobox: { test: (v) => v === '' || /^[a-zA-Z0-9\s\-\.\,]+$/.test(v), message: 'Only letters, numbers, spaces, and - . ,', optional: true },
        payee_contact: {
            test: (v) => { if (v === '') return true; const p = /^[0-9]{10,15}$/.test(v); const e = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); return p || e; },
            message: 'Must be a valid phone (10-15 digits) or email',
            optional: true,
        },
    };

    function showError(field, message) {
        const targetId = field.dataset.errorTarget;
        const errorEl = document.getElementById(targetId);
        if (!errorEl) return;
        field.classList.remove('border-slate-300');
        field.classList.add('border-red-500', 'bg-red-50');
        errorEl.textContent = '⚠ ' + message;
        errorEl.style.display = 'block';
    }

    function clearError(field) {
        const targetId = field.dataset.errorTarget;
        const errorEl = document.getElementById(targetId);
        if (!errorEl) return;
        field.classList.remove('border-red-500', 'bg-red-50');
        field.classList.add('border-slate-300');
        errorEl.textContent = '';
        errorEl.style.display = 'none';
    }

    function validateField(field) {
        const ruleName = field.dataset.validate;
        if (!ruleName) return;
        const rule = rules[ruleName];
        if (!rule) return;
        const value = field.value.trim();
        if (value === '' && rule.optional) { clearError(field); return; }
        if (!rule.test(value)) { showError(field, rule.message); } else { clearError(field); }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-validate]').forEach(field => {
            field.addEventListener('blur', () => validateField(field));
            field.addEventListener('input', () => { if (field.value.trim() === '') clearError(field); });
        });

        @if($errors->any())
            const serverErrors = {
                @foreach($errors->keys() as $key)
                    "{{ $key }}": "{{ addslashes($errors->first($key)) }}",
                @endforeach
            };
            Object.keys(serverErrors).forEach(function(key) {
                const field = document.querySelector('[name="' + key + '"]');
                if (field) {
                    const errorDiv = document.getElementById(key + '-error');
                    if (errorDiv) {
                        field.classList.add('border-red-500', 'bg-red-50');
                        errorDiv.textContent = '⚠ ' + serverErrors[key];
                        errorDiv.style.display = 'block';
                    }
                }
            });
        @endif
    });
})();
</script>
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

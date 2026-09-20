@extends('layouts.sapta')

@section('title', 'New Receipt')
@section('page-title', 'New Receipt')

@section('content')
<style>
    .rcc-page { padding: 1.5rem; max-width: 1000px; margin: 0 auto; }
    .rcc-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .rcc-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .rcc-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .rcc-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .rcc-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .rcc-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .rcc-card-head h2 { font-size: 0.85rem; font-weight: 800; color: #10b981; margin: 0; text-transform: uppercase; letter-spacing: 0.05em; }
    .rcc-card-body { padding: 1.5rem; }
    .rcc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .rcc-grid { grid-template-columns: 1fr; } }
    .rcc-group { margin-bottom: 1rem; }
    .rcc-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .rcc-group label .req { color: #dc2626; }
    .rcc-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; }
    .rcc-input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.1); }
    .rcc-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; }
    .rcc-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; }
    .rcc-btn-primary { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
    .rcc-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .rcc-alert { padding: 0.75rem 1rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; margin-bottom: 1rem; border-left: 4px solid #dc2626; }
</style>

<div class="rcc-page">
    <div class="rcc-head">
        <div class="rcc-head-icon"><i class="fas fa-receipt"></i></div>
        <div>
            <h1>New Receipt</h1>
            <p>Create a new receipt.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="rcc-alert">
            <strong>Please correct the following:</strong>
            <ul style="margin:8px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('receipts.store') }}" method="POST">
        @csrf

        <div class="rcc-card">
            <div class="rcc-card-head"><h2>Receipt Information</h2></div>
            <div class="rcc-card-body">
                <div class="rcc-grid">
                    <div class="rcc-group">
                        <label>Receipt Number <span class="req">*</span></label>
                        <input type="text" name="receipt_number" class="rcc-input" value="{{ old('receipt_number', $nextNumber) }}" required readonly style="background:#f8fafc;">
                    </div>
                    <div class="rcc-group">
                        <label>Receipt Date <span class="req">*</span></label>
                        <input type="date" name="receipt_date" class="rcc-input" value="{{ old('receipt_date', date('Y-m-d')) }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="rcc-card">
            <div class="rcc-card-head"><h2>Payer Information</h2></div>
            <div class="rcc-card-body">
                <div class="rcc-grid">
                    <div class="rcc-group">
                        <label>Payer Name <span class="req">*</span></label>
                        <input type="text" name="payer_name" class="rcc-input" value="{{ old('payer_name') }}" placeholder="e.g. ABC Company" required>
                    </div>
                    <div class="rcc-group">
                        <label>Payer Type <span class="req">*</span></label>
                        <select name="payer_type" class="rcc-input" required>
                            <option value="client" @selected(old('payer_type') === 'client')>Client</option>
                            <option value="donor" @selected(old('payer_type') === 'donor')>Donor</option>
                            <option value="employee" @selected(old('payer_type') === 'employee')>Employee</option>
                            <option value="other" @selected(old('payer_type') === 'other')>Other</option>
                        </select>
                    </div>
                    <div class="rcc-group">
                        <label>Contact</label>
                        <input type="text" name="payer_contact" class="rcc-input" value="{{ old('payer_contact') }}" placeholder="Phone or email">
                    </div>
                    <div class="rcc-group">
                        <label>Payment Method <span class="req">*</span></label>
                        <select name="payment_method" class="rcc-input" required>
                            <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Bank Transfer</option>
                            <option value="cash" @selected(old('payment_method') === 'cash')>Cash</option>
                            <option value="cheque" @selected(old('payment_method') === 'cheque')>Cheque</option>
                            <option value="mobile_money" @selected(old('payment_method') === 'mobile_money')>Mobile Money</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="rcc-card">
            <div class="rcc-card-head"><h2>Amount & Description</h2></div>
            <div class="rcc-card-body">
                <div class="rcc-grid">
                    <div class="rcc-group">
                        <label>Amount <span class="req">*</span></label>
                        <input type="number" name="amount" class="rcc-input" value="{{ old('amount', 0) }}" step="0.01" min="0" required>
                    </div>
                    <div class="rcc-group">
                        <label>Currency <span class="req">*</span></label>
                        <select name="currency" class="rcc-input" required>
                            <option value="TZS" @selected(old('currency', 'TZS') === 'TZS')>TZS</option>
                            <option value="USD" @selected(old('currency') === 'USD')>USD</option>
                            <option value="EUR" @selected(old('currency') === 'EUR')>EUR</option>
                        </select>
                    </div>
                </div>

                <div class="rcc-grid">
                    <div class="rcc-group">
                        <label>Project</label>
                        <select name="project_id" class="rcc-input">
                            <option value="">? None ?</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" @selected(old('project_id') == $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="rcc-group">
                        <label>Department</label>
                        <select name="department_id" class="rcc-input">
                            <option value="">? None ?</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}" @selected(old('department_id') == $d->id)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="rcc-group">
                    <label>Reference Number</label>
                    <input type="text" name="reference_number" class="rcc-input" value="{{ old('reference_number') }}" placeholder="e.g. Bank ref, cheque number">
                </div>

                <div class="rcc-group">
                    <label>Description</label>
                    <textarea name="description" class="rcc-input" rows="3" placeholder="Reason for payment...">{{ old('description') }}</textarea>
                </div>

                <div class="rcc-group">
                    <label>Notes</label>
                    <textarea name="notes" class="rcc-input" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>

                    {{-- LOCATION --}}
                    <div class="form-group">
                        <label>Region</label>
                        <select id="region_id" name="region_id" class="form-control">
                            <option value="">-- Select Region --</option>
                            @foreach($regions ?? [] as $r)
                                <option value="{{ $r->id }}" @selected(old('region_id', '') == $r->id)>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>District</label>
                        <select id="district_id" name="district_id" class="form-control">
                            <option value="">-- Select District --</option>
                            @if(false)
                                <option value="{{ '' }}" selected>{{ '' }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Ward</label>
                        <select id="ward_id" name="ward_id" class="form-control">
                            <option value="">-- Select Ward --</option>
                            @if(false)
                                <option value="{{ '' }}" selected>{{ '' }}</option>
                            @endif
                        </select>
                    </div>
        </div>

        <div class="rcc-card">
            <div class="rcc-actions">
                <button type="submit" class="rcc-btn rcc-btn-primary"><i class="fas fa-save"></i> Create Receipt</button>
                <a href="{{ route('receipts.index') }}" class="rcc-btn rcc-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionSelect = document.getElementById('region_id');
    const districtSelect = document.getElementById('district_id');
    const wardSelect = document.getElementById('ward_id');

    if (!regionSelect || !districtSelect || !wardSelect) return;

    const savedDistrictId = districtSelect.querySelector('option[selected]')?.value || '';
    const savedWardId = wardSelect.querySelector('option[selected]')?.value || '';

    regionSelect.addEventListener('change', function() {
        const regionId = this.value;
        districtSelect.innerHTML = '<option value="">Loading...</option>';
        wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';

        if (!regionId) {
            districtSelect.innerHTML = '<option value="">-- Select District --</option>';
            return;
        }

        fetch('/location/districts?region_id=' + regionId)
            .then(res => res.json())
            .then(data => {
                districtSelect.innerHTML = '<option value="">-- Select District --</option>';
                data.forEach(d => {
                    const sel = (d.id == savedDistrictId) ? ' selected' : '';
                    districtSelect.innerHTML += `<option value="${d.id}"${sel}>${d.name}</option>`;
                });
                if (savedDistrictId) districtSelect.dispatchEvent(new Event('change'));
            });
    });

    districtSelect.addEventListener('change', function() {
        const districtId = this.value;
        wardSelect.innerHTML = '<option value="">Loading...</option>';

        if (!districtId) {
            wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';
            return;
        }

        fetch('/location/wards?district_id=' + districtId)
            .then(res => res.json())
            .then(data => {
                wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';
                data.forEach(w => {
                    const sel = (w.id == savedWardId) ? ' selected' : '';
                    wardSelect.innerHTML += `<option value="${w.id}"${sel}>${w.name}</option>`;
                });
            });
    });

    if (regionSelect.value) regionSelect.dispatchEvent(new Event('change'));
});
</script>
@endpush

@endsection



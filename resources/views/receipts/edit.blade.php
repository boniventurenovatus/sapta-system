@extends('layouts.sapta')

@section('title', 'Edit Receipt')
@section('page-title', 'Edit Receipt')

@section('content')
<style>
    .rce-page { padding: 1.5rem; max-width: 1000px; margin: 0 auto; }
    .rce-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .rce-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .rce-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .rce-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .rce-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .rce-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .rce-card-head h2 { font-size: 0.85rem; font-weight: 800; color: #f59e0b; margin: 0; text-transform: uppercase; letter-spacing: 0.05em; }
    .rce-card-body { padding: 1.5rem; }
    .rce-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .rce-grid { grid-template-columns: 1fr; } }
    .rce-group { margin-bottom: 1rem; }
    .rce-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .rce-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; }
    .rce-input:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }
    .rce-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; }
    .rce-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; }
    .rce-btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .rce-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
</style>

<div class="rce-page">
    <div class="rce-head">
        <div class="rce-head-icon"><i class="fas fa-pen"></i></div>
        <div>
            <h1>Edit Receipt</h1>
            <p>Update receipt {{ $receipt->receipt_number }}</p>
        </div>
    </div>

    <form action="{{ route('receipts.update', $receipt) }}" method="POST">
        @csrf @method('PUT')

        <div class="rce-card">
            <div class="rce-card-head"><h2>Receipt Information</h2></div>
            <div class="rce-card-body">
                <div class="rce-grid">
                    <div class="rce-group">
                        <label>Receipt Number</label>
                        <input type="text" class="rce-input" value="{{ $receipt->receipt_number }}" readonly style="background:#f8fafc;">
                    </div>
                    <div class="rce-group">
                        <label>Receipt Date</label>
                        <input type="date" name="receipt_date" class="rce-input" value="{{ old('receipt_date', $receipt->receipt_date->format('Y-m-d')) }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="rce-card">
            <div class="rce-card-head"><h2>Payer Information</h2></div>
            <div class="rce-card-body">
                <div class="rce-grid">
                    <div class="rce-group">
                        <label>Payer Name</label>
                        <input type="text" name="payer_name" class="rce-input" value="{{ old('payer_name', $receipt->payer_name) }}" required>
                    </div>
                    <div class="rce-group">
                        <label>Payer Type</label>
                        <select name="payer_type" class="rce-input" required>
                            @foreach(['client','donor','employee','other'] as $t)
                                <option value="{{ $t }}" @selected(old('payer_type', $receipt->payer_type) === $t)>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="rce-group">
                        <label>Contact</label>
                        <input type="text" name="payer_contact" class="rce-input" value="{{ old('payer_contact', $receipt->payer_contact) }}">
                    </div>
                    <div class="rce-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="rce-input" required>
                            @foreach(['bank_transfer','cash','cheque','mobile_money'] as $m)
                                <option value="{{ $m }}" @selected(old('payment_method', $receipt->payment_method) === $m)>{{ ucwords(str_replace('_', ' ', $m)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="rce-card">
            <div class="rce-card-head"><h2>Amount & Description</h2></div>
            <div class="rce-card-body">
                <div class="rce-grid">
                    <div class="rce-group">
                        <label>Amount</label>
                        <input type="number" name="amount" class="rce-input" value="{{ old('amount', $receipt->amount) }}" step="0.01" min="0" required>
                    </div>
                    <div class="rce-group">
                        <label>Currency</label>
                        <select name="currency" class="rce-input" required>
                            @foreach(['TZS','USD','EUR'] as $c)
                                <option value="{{ $c }}" @selected(old('currency', $receipt->currency) === $c)>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="rce-group">
                        <label>Project</label>
                        <select name="project_id" class="rce-input">
                            <option value="">? None ?</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" @selected(old('project_id', $receipt->project_id) == $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="rce-group">
                        <label>Department</label>
                        <select name="department_id" class="rce-input">
                            <option value="">? None ?</option>
                            @foreach(($departments ?? \App\Models\Department::where("is_active", true)->orderBy("name")->get()) as $d)
                                <option value="{{ $d->id }}" @selected(old('department_id', $receipt->department_id) == $d->id)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="rce-group">
                    <label>Reference Number</label>
                    <input type="text" name="reference_number" class="rce-input" value="{{ old('reference_number', $receipt->reference_number) }}">
                </div>

                <div class="rce-group">
                    <label>Description</label>
                    <textarea name="description" class="rce-input" rows="3">{{ old('description', $receipt->description) }}</textarea>
                </div>

                <div class="rce-group">
                    <label>Notes</label>
                    <textarea name="notes" class="rce-input" rows="2">{{ old('notes', $receipt->notes) }}</textarea>
                </div>
            </div>

                    {{-- LOCATION --}}
                    <div class="form-group">
                        <label>Region</label>
                        <select id="region_id" name="region_id" class="form-control">
                            <option value="">-- Select Region --</option>
                            @foreach($regions ?? [] as $r)
                                <option value="{{ $r->id }}" @selected(old('region_id', isset($receipt) ? $receipt->region_id : '') == $r->id)>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>District</label>
                        <select id="district_id" name="district_id" class="form-control">
                            <option value="">-- Select District --</option>
                            @if(isset($receipt) && $receipt->district_id)
                                <option value="{{ $receipt->district_id }}" selected>{{ $receipt->district?->name }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Ward</label>
                        <select id="ward_id" name="ward_id" class="form-control">
                            <option value="">-- Select Ward --</option>
                            @if(isset($receipt) && $receipt->ward_id)
                                <option value="{{ $receipt->ward_id }}" selected>{{ $receipt->ward?->name }}</option>
                            @endif
                        </select>
                    </div>
        </div>

        <div class="rce-card">
            <div class="rce-actions">
                <button type="submit" class="rce-btn rce-btn-warning"><i class="fas fa-save"></i> Update Receipt</button>
                <a href="{{ route('receipts.index') }}" class="rce-btn rce-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
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



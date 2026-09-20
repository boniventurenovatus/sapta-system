@extends('layouts.sapta')

@section('title', 'New Budget')
@section('page-title', 'New Budget')

@section('content')
<style>
    .bgc-page { padding: 1.5rem; max-width: 1000px; margin: 0 auto; }
    .bgc-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .bgc-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .bgc-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .bgc-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .bgc-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .bgc-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .bgc-card-head h2 { font-size: 0.85rem; font-weight: 800; color: #8b5cf6; margin: 0; text-transform: uppercase; letter-spacing: 0.05em; }
    .bgc-card-body { padding: 1.5rem; }
    .bgc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .bgc-grid { grid-template-columns: 1fr; } }
    .bgc-group { margin-bottom: 1rem; }
    .bgc-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .bgc-group label .req { color: #dc2626; }
    .bgc-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; }
    .bgc-input:focus { outline: none; border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139,92,246,0.1); }
    .bgc-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; }
    .bgc-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; }
    .bgc-btn-primary { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; }
    .bgc-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .bgc-alert { padding: 0.75rem 1rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; margin-bottom: 1rem; border-left: 4px solid #dc2626; }
</style>

<div class="bgc-page">
    <div class="bgc-head">
        <div class="bgc-head-icon"><i class="fas fa-chart-pie"></i></div>
        <div><h1>New Budget</h1><p>Create a new budget.</p></div>
    </div>

    @if($errors->any())
        <div class="bgc-alert">
            <strong>Please correct the following:</strong>
            <ul style="margin:8px 0 0 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('budgets.store') }}" method="POST">
        @csrf

        <div class="bgc-card">
            <div class="bgc-card-head"><h2>Budget Information</h2></div>
            <div class="bgc-card-body">
                <div class="bgc-grid">
                    <div class="bgc-group">
                        <label>Budget Number <span class="req">*</span></label>
                        <input type="text" name="budget_number" class="bgc-input" value="{{ old('budget_number', $nextNumber) }}" readonly style="background:#f8fafc;">
                    </div>
                    <div class="bgc-group">
                        <label>Fiscal Year <span class="req">*</span></label>
                        <input type="number" name="fiscal_year" class="bgc-input" value="{{ old('fiscal_year', date('Y')) }}" min="2020" max="2100" required>
                    </div>
                    <div class="bgc-group">
                        <label>Budget Name <span class="req">*</span></label>
                        <input type="text" name="name" class="bgc-input" value="{{ old('name') }}" placeholder="e.g. 2026 Operations Budget" required>
                    </div>
                    <div class="bgc-group">
                        <label>Category <span class="req">*</span></label>
                        <select name="category" class="bgc-input" required>
                            <option value="operations" @selected(old('category') === 'operations')>Operations</option>
                            <option value="salaries" @selected(old('category') === 'salaries')>Salaries</option>
                            <option value="supplies" @selected(old('category') === 'supplies')>Supplies</option>
                            <option value="travel" @selected(old('category') === 'travel')>Travel</option>
                            <option value="training" @selected(old('category') === 'training')>Training</option>
                            <option value="equipment" @selected(old('category') === 'equipment')>Equipment</option>
                            <option value="other" @selected(old('category') === 'other')>Other</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="bgc-card">
            <div class="bgc-card-head"><h2>Allocation</h2></div>
            <div class="bgc-card-body">
                <div class="bgc-grid">
                    <div class="bgc-group">
                        <label>Allocated Amount <span class="req">*</span></label>
                        <input type="number" name="allocated_amount" class="bgc-input" value="{{ old('allocated_amount', 0) }}" step="0.01" min="0" required>
                    </div>
                    <div class="bgc-group">
                        <label>Currency <span class="req">*</span></label>
                        <select name="currency" class="bgc-input" required>
                            <option value="TZS" @selected(old('currency', 'TZS') === 'TZS')>TZS</option>
                            <option value="USD" @selected(old('currency') === 'USD')>USD</option>
                            <option value="EUR" @selected(old('currency') === 'EUR')>EUR</option>
                        </select>
                    </div>
                    <div class="bgc-group">
                        <label>Project</label>
                        <select name="project_id" class="bgc-input">
                            <option value="">? None ?</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" @selected(old('project_id') == $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="bgc-group">
                        <label>Department</label>
                        <select name="department_id" class="bgc-input">
                            <option value="">? None ?</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}" @selected(old('department_id') == $d->id)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="bgc-card">
            <div class="bgc-card-head"><h2>Period & Notes</h2></div>
            <div class="bgc-card-body">
                <div class="bgc-grid">
                    <div class="bgc-group">
                        <label>Start Date <span class="req">*</span></label>
                        <input type="date" name="start_date" class="bgc-input" value="{{ old('start_date', date('Y-01-01')) }}" required>
                    </div>
                    <div class="bgc-group">
                        <label>End Date <span class="req">*</span></label>
                        <input type="date" name="end_date" class="bgc-input" value="{{ old('end_date', date('Y-12-31')) }}" required>
                    </div>
                </div>
                <div class="bgc-group">
                    <label>Notes</label>
                    <textarea name="notes" class="bgc-input" rows="3">{{ old('notes') }}</textarea>
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

        <div class="bgc-card">
            <div class="bgc-actions">
                <button type="submit" class="bgc-btn bgc-btn-primary"><i class="fas fa-save"></i> Create Budget</button>
                <a href="{{ route('budgets.index') }}" class="bgc-btn bgc-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
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



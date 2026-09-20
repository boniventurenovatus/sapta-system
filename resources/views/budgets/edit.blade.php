@extends('layouts.sapta')

@section('title', 'Edit Budget')
@section('page-title', 'Edit Budget')

@section('content')
<style>
    .bge-page { padding: 1.5rem; max-width: 1000px; margin: 0 auto; }
    .bge-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .bge-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .bge-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .bge-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .bge-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .bge-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .bge-card-head h2 { font-size: 0.85rem; font-weight: 800; color: #f59e0b; margin: 0; text-transform: uppercase; letter-spacing: 0.05em; }
    .bge-card-body { padding: 1.5rem; }
    .bge-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .bge-grid { grid-template-columns: 1fr; } }
    .bge-group { margin-bottom: 1rem; }
    .bge-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .bge-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; }
    .bge-input:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }
    .bge-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; }
    .bge-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; }
    .bge-btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .bge-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
</style>

<div class="bge-page">
    <div class="bge-head">
        <div class="bge-head-icon"><i class="fas fa-pen"></i></div>
        <div><h1>Edit Budget</h1><p>Update budget {{ $budget->budget_number }}</p></div>
    </div>

    <form action="{{ route('budgets.update', $budget) }}" method="POST">
        @csrf @method('PUT')

        <div class="bge-card">
            <div class="bge-card-head"><h2>Budget Information</h2></div>
            <div class="bge-card-body">
                <div class="bge-grid">
                    <div class="bge-group">
                        <label>Budget Number</label>
                        <input type="text" class="bge-input" value="{{ $budget->budget_number }}" readonly style="background:#f8fafc;">
                    </div>
                    <div class="bge-group">
                        <label>Fiscal Year</label>
                        <input type="number" name="fiscal_year" class="bge-input" value="{{ old('fiscal_year', $budget->fiscal_year) }}" required>
                    </div>
                    <div class="bge-group">
                        <label>Budget Name</label>
                        <input type="text" name="name" class="bge-input" value="{{ old('name', $budget->name) }}" required>
                    </div>
                    <div class="bge-group">
                        <label>Category</label>
                        <select name="category" class="bge-input" required>
                            @foreach(['salaries','operations','supplies','travel','training','equipment','other'] as $c)
                                <option value="{{ $c }}" @selected(old('category', $budget->category) === $c)>{{ ucfirst($c) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="bge-card">
            <div class="bge-card-head"><h2>Allocation</h2></div>
            <div class="bge-card-body">
                <div class="bge-grid">
                    <div class="bge-group">
                        <label>Allocated Amount</label>
                        <input type="number" name="allocated_amount" class="bge-input" value="{{ old('allocated_amount', $budget->allocated_amount) }}" step="0.01" min="0" required>
                    </div>
                    <div class="bge-group">
                        <label>Spent Amount</label>
                        <input type="number" name="spent_amount" class="bge-input" value="{{ old('spent_amount', $budget->spent_amount) }}" step="0.01" min="0">
                    </div>
                    <div class="bge-group">
                        <label>Currency</label>
                        <select name="currency" class="bge-input" required>
                            @foreach(['TZS','USD','EUR'] as $c)
                                <option value="{{ $c }}" @selected(old('currency', $budget->currency) === $c)>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="bge-group">
                        <label>Project</label>
                        <select name="project_id" class="bge-input">
                            <option value="">? None ?</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" @selected(old('project_id', $budget->project_id) == $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="bge-group">
                        <label>Department</label>
                        <select name="department_id" class="bge-input">
                            <option value="">? None ?</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}" @selected(old('department_id', $budget->department_id) == $d->id)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="bge-card">
            <div class="bge-card-head"><h2>Period & Notes</h2></div>
            <div class="bge-card-body">
                <div class="bge-grid">
                    <div class="bge-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="bge-input" value="{{ old('start_date', $budget->start_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="bge-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="bge-input" value="{{ old('end_date', $budget->end_date->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="bge-group">
                    <label>Notes</label>
                    <textarea name="notes" class="bge-input" rows="3">{{ old('notes', $budget->notes) }}</textarea>
                </div>
            </div>

                    {{-- LOCATION --}}
                    <div class="form-group">
                        <label>Region</label>
                        <select id="region_id" name="region_id" class="form-control">
                            <option value="">-- Select Region --</option>
                            @foreach($regions ?? [] as $r)
                                <option value="{{ $r->id }}" @selected(old('region_id', isset($budget) ? $budget->region_id : '') == $r->id)>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>District</label>
                        <select id="district_id" name="district_id" class="form-control">
                            <option value="">-- Select District --</option>
                            @if(isset($budget) && $budget->district_id)
                                <option value="{{ $budget->district_id }}" selected>{{ $budget->district?->name }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Ward</label>
                        <select id="ward_id" name="ward_id" class="form-control">
                            <option value="">-- Select Ward --</option>
                            @if(isset($budget) && $budget->ward_id)
                                <option value="{{ $budget->ward_id }}" selected>{{ $budget->ward?->name }}</option>
                            @endif
                        </select>
                    </div>
        </div>

        <div class="bge-card">
            <div class="bge-actions">
                <button type="submit" class="bge-btn bge-btn-warning"><i class="fas fa-save"></i> Update Budget</button>
                <a href="{{ route('budgets.index') }}" class="bge-btn bge-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
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



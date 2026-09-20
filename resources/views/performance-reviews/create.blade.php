@extends('layouts.sapta')

@section('title', 'New Performance Review')
@section('page-title', 'New Performance Review')

@section('content')
<style>
    .prc-page { padding: 1.5rem; max-width: 1000px; margin: 0 auto; }
    .prc-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .prc-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .prc-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .prc-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .prc-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; }
    .prc-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .prc-card-head h2 { font-size: 0.85rem; font-weight: 800; color: #f59e0b; margin: 0; text-transform: uppercase; letter-spacing: 0.05em; }
    .prc-card-body { padding: 1.5rem; }
    .prc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .prc-grid { grid-template-columns: 1fr; } }
    .prc-group { margin-bottom: 1rem; }
    .prc-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .prc-group label .req { color: #dc2626; }
    .prc-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; }
    .prc-input:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }
    .prc-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; }
    .prc-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; }
    .prc-btn-primary { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .prc-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .prc-alert { padding: 0.75rem 1rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; margin-bottom: 1rem; border-left: 4px solid #dc2626; }
</style>

<div class="prc-page">
    <div class="prc-head">
        <div class="prc-head-icon"><i class="fas fa-star"></i></div>
        <div><h1>New Performance Review</h1><p>Create a new employee performance evaluation.</p></div>
    </div>

    @if($errors->any())
        <div class="prc-alert">
            <strong>Please correct the following:</strong>
            <ul style="margin:8px 0 0 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('performance-reviews.store') }}" method="POST">
        @csrf

        <div class="prc-card">
            <div class="prc-card-head"><h2>Review Information</h2></div>
            <div class="prc-card-body">
                <div class="prc-grid">
                    <div class="prc-group">
                        <label>Review Number <span class="req">*</span></label>
                        <input type="text" name="review_number" class="prc-input" value="{{ old('review_number', $nextNumber) }}" readonly style="background:#f8fafc;">
                    </div>
                    <div class="prc-group">
                        <label>Employee <span class="req">*</span></label>
                        <select name="employee_id" class="prc-input" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" @selected(old('employee_id') == $emp->id)>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="prc-group">
                        <label>Review Period <span class="req">*</span></label>
                        <input type="text" name="review_period" class="prc-input" value="{{ old('review_period', 'Q' . ceil(date('n')/3) . ' ' . date('Y')) }}" placeholder="e.g. Q1 2026" required>
                    </div>
                    <div class="prc-group">
                        <label>Review Date <span class="req">*</span></label>
                        <input type="date" name="review_date" class="prc-input" value="{{ old('review_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="prc-group">
                        <label>Period Start <span class="req">*</span></label>
                        <input type="date" name="period_start" class="prc-input" value="{{ old('period_start') }}" required>
                    </div>
                    <div class="prc-group">
                        <label>Period End <span class="req">*</span></label>
                        <input type="date" name="period_end" class="prc-input" value="{{ old('period_end') }}" required>
                    </div>
                    <div class="prc-group">
                        <label>Overall Rating (0-5)</label>
                        <input type="number" name="overall_rating" class="prc-input" value="{{ old('overall_rating', 0) }}" step="0.1" min="0" max="5">
                    </div>
                </div>
            </div>
        </div>

        <div class="prc-card">
            <div class="prc-card-head"><h2>Evaluation</h2></div>
            <div class="prc-card-body">
                <div class="prc-group">
                    <label>Strengths</label>
                    <textarea name="strengths" class="prc-input" rows="3" placeholder="Employee strengths...">{{ old('strengths') }}</textarea>
                </div>
                <div class="prc-group">
                    <label>Areas for Improvement</label>
                    <textarea name="improvements" class="prc-input" rows="3" placeholder="Areas to improve...">{{ old('improvements') }}</textarea>
                </div>
                <div class="prc-group">
                    <label>Goals for Next Period</label>
                    <textarea name="goals" class="prc-input" rows="3" placeholder="Goals...">{{ old('goals') }}</textarea>
                </div>
                <div class="prc-group">
                    <label>Additional Comments</label>
                    <textarea name="comments" class="prc-input" rows="2">{{ old('comments') }}</textarea>
                </div>
            </div>
        </div>

        <div class="prc-card">
            <div class="prc-actions">
                <button type="submit" class="prc-btn prc-btn-primary"><i class="fas fa-save"></i> Create Review</button>
                <a href="{{ route('performance-reviews.index') }}" class="prc-btn prc-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
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


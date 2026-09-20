@extends('layouts.sapta')
@section('title', 'Edit Performance Review')
@section('page-title', 'Edit Performance Review')

@section('content')
<style>
    .pre-page { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
    .pre-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .pre-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 20px rgba(245,158,11,0.25); }
    .pre-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .pre-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .pre-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1.5rem; box-shadow: 0 4px 16px rgba(0,0,0,0.04); }
    .pre-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .pre-card-head h2 { font-size: 0.75rem; font-weight: 800; color: #f59e0b; margin: 0; text-transform: uppercase; letter-spacing: 0.08em; display: flex; align-items: center; gap: 0.5rem; }
    .pre-card-body { padding: 1.5rem; }
    .pre-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .pre-grid { grid-template-columns: 1fr; } }
    .pre-group { margin-bottom: 1rem; }
    .pre-group.full { grid-column: 1 / -1; }
    .pre-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    .pre-group label .req { color: #dc2626; }
    .pre-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; font-family: inherit; background: #fff; }
    .pre-input:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }
    .pre-error { color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem; }
    .pre-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; flex-wrap: wrap; }
    .pre-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .pre-btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .pre-btn-warning:hover { transform: translateY(-1px); color: #fff; }
    .pre-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .pre-btn-secondary:hover { background: #f8fafc; color: #1e293b; }
</style>

<div class="pre-page">
    <div class="pre-head">
        <div class="pre-head-icon"><i class="fas fa-pen"></i></div>
        <div>
            <h1>Edit Performance Review</h1>
            <p>Update review {{ $performanceReview->review_number ?? '' }}</p>
        </div>
    </div>

    @if($errors->any())
        <div style="padding:0.75rem 1rem; background:#fee2e2; color:#991b1b; border-radius:0.5rem; margin-bottom:1rem; border-left:4px solid #dc2626;">
            <strong>Please correct the following:</strong>
            <ul style="margin:8px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('performance-reviews.update', $performanceReview) }}" method="POST">
        @csrf @method('PUT')

        <div class="pre-card">
            <div class="pre-card-head"><h2><i class="fas fa-star"></i> Review Information</h2></div>
            <div class="pre-card-body">
                <div class="pre-grid">
                    <div class="pre-group">
                        <label>Employee <span class="req">*</span></label>
                        <select name="employee_id" class="pre-input" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($employees ?? [] as $e)
                                <option value="{{ $e->id }}" @selected(old('employee_id', $performanceReview->employee_id) == $e->id)>{{ $e->first_name }} {{ $e->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pre-group">
                        <label>Reviewer</label>
                        <select name="reviewer_id" class="pre-input">
                            <option value="">-- Select Reviewer --</option>
                            @foreach($employees ?? [] as $e)
                                <option value="{{ $e->id }}" @selected(old('reviewer_id', $performanceReview->reviewer_id) == $e->id)>{{ $e->first_name }} {{ $e->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pre-group">
                        <label>Review Period</label>
                        <input type="text" name="review_period" class="pre-input" value="{{ old('review_period', $performanceReview->review_period) }}" placeholder="e.g. Q1 2026">
                    </div>
                    <div class="pre-group">
                        <label>Review Date</label>
                        <input type="date" name="review_date" class="pre-input" value="{{ old('review_date', $performanceReview->review_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="pre-group">
                        <label>Period Start</label>
                        <input type="date" name="period_start" class="pre-input" value="{{ old('period_start', $performanceReview->period_start?->format('Y-m-d')) }}">
                    </div>
                    <div class="pre-group">
                        <label>Period End</label>
                        <input type="date" name="period_end" class="pre-input" value="{{ old('period_end', $performanceReview->period_end?->format('Y-m-d')) }}">
                    </div>
                    <div class="pre-group">
                        <label>Overall Rating</label>
                        <input type="number" name="overall_rating" class="pre-input" value="{{ old('overall_rating', $performanceReview->overall_rating) }}" min="1" max="5" step="0.1">
                    </div>
                    <div class="pre-group">
                        <label>Status <span class="req">*</span></label>
                        <select name="status" class="pre-input" required>
                            @foreach(['draft','submitted','approved','rejected'] as $s)
                                <option value="{{ $s }}" @selected(old('status', $performanceReview->status) === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pre-group full">
                        <label>Strengths</label>
                        <textarea name="strengths" rows="2" class="pre-input">{{ old('strengths', $performanceReview->strengths) }}</textarea>
                    </div>
                    <div class="pre-group full">
                        <label>Improvements</label>
                        <textarea name="improvements" rows="2" class="pre-input">{{ old('improvements', $performanceReview->improvements) }}</textarea>
                    </div>
                    <div class="pre-group full">
                        <label>Goals</label>
                        <textarea name="goals" rows="2" class="pre-input">{{ old('goals', $performanceReview->goals) }}</textarea>
                    </div>
                    <div class="pre-group full">
                        <label>Comments</label>
                        <textarea name="comments" rows="2" class="pre-input">{{ old('comments', $performanceReview->comments) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="pre-card">
            <div class="pre-card-head"><h2><i class="fas fa-map-location-dot"></i> Location</h2></div>
            <div class="pre-card-body">
                <div class="pre-grid">
                    <div class="pre-group">
                        <label>Region</label>
                        <select id="region_id" name="region_id" class="pre-input">
                            <option value="">-- Select Region --</option>
                            @foreach($regions ?? [] as $r)
                                <option value="{{ $r->id }}" @selected(old('region_id', $performanceReview->region_id) == $r->id)>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pre-group">
                        <label>District</label>
                        <select id="district_id" name="district_id" class="pre-input">
                            <option value="">-- Select District --</option>
                            @if($performanceReview->district_id)
                                <option value="{{ $performanceReview->district_id }}" selected>{{ $performanceReview->district?->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="pre-group">
                        <label>Ward</label>
                        <select id="ward_id" name="ward_id" class="pre-input">
                            <option value="">-- Select Ward --</option>
                            @if($performanceReview->ward_id)
                                <option value="{{ $performanceReview->ward_id }}" selected>{{ $performanceReview->ward?->name }}</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="pre-card">
            <div class="pre-actions">
                <button type="submit" class="pre-btn pre-btn-warning"><i class="fas fa-save"></i> Update Review</button>
                <a href="{{ route('performance-reviews.index') }}" class="pre-btn pre-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
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

    const savedRegionId = '{{ old('region_id', $performanceReview->region_id) }}';
    const savedDistrictId = '{{ old('district_id', $performanceReview->district_id) }}';
    const savedWardId = '{{ old('ward_id', $performanceReview->ward_id) }}';

    if (savedRegionId) regionSelect.value = savedRegionId;

    function loadDistricts(regionId) {
        if (!regionId) {
            districtSelect.innerHTML = '<option value="">-- Select District --</option>';
            wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';
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
                if (savedDistrictId) loadWards(savedDistrictId);
            });
    }

    function loadWards(districtId) {
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
    }

    regionSelect.addEventListener('change', function() { loadDistricts(this.value); });
    districtSelect.addEventListener('change', function() { loadWards(this.value); });
    if (regionSelect.value) loadDistricts(regionSelect.value);
});
</script>
@endpush
@endsection
@extends('layouts.sapta')
@section('title', 'Edit Performance Review')
@section('page-title', 'Edit Performance Review')
@section('content')
<div style="padding:1.5rem; max-width:1000px; margin:0 auto;">
    <h1 style="font-size:1.75rem; font-weight:800; color:#0f172a; margin:0 0 1.5rem;">Edit Performance Review</h1>
    <form action="{{ route('performance-reviews.update', $performanceReview) }}" method="POST">
        @csrf @method('PUT')
        <div style="background:#fff; border-radius:1rem; border:1px solid #e2e8f0; padding:1.5rem; margin-bottom:1.5rem;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Employee</label><select name="employee_id" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;">@foreach($employees as $emp)<option value="{{ $emp->id }}" @selected($performanceReview->employee_id == $emp->id)>{{ $emp->first_name }} {{ $emp->last_name }}</option>@endforeach</select></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Review Period</label><input type="text" name="review_period" value="{{ old('review_period', $performanceReview->review_period) }}" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Review Date</label><input type="date" name="review_date" value="{{ old('review_date', $performanceReview->review_date->format('Y-m-d')) }}" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Overall Rating</label><input type="number" name="overall_rating" value="{{ old('overall_rating', $performanceReview->overall_rating) }}" step="0.1" min="0" max="5" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Period Start</label><input type="date" name="period_start" value="{{ old('period_start', $performanceReview->period_start->format('Y-m-d')) }}" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Period End</label><input type="date" name="period_end" value="{{ old('period_end', $performanceReview->period_end->format('Y-m-d')) }}" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
            </div>
            <div style="margin-top:1rem;"><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Strengths</label><textarea name="strengths" rows="3" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;">{{ old('strengths', $performanceReview->strengths) }}</textarea></div>
            <div style="margin-top:1rem;"><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Improvements</label><textarea name="improvements" rows="3" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;">{{ old('improvements', $performanceReview->improvements) }}</textarea></div>
            <div style="margin-top:1rem;"><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Goals</label><textarea name="goals" rows="3" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;">{{ old('goals', $performanceReview->goals) }}</textarea></div>
        </div>
                {{-- LOCATION --}}
                <div class="form-group">
                    <label>Region</label>
                    <select id="region_id" name="region_id" class="form-control">
                        <option value="">-- Select Region --</option>
                        @foreach($regions ?? [] as $r)
                            <option value="{{ $r->id }}" @selected(old('region_id', $performanceReview->region_id) == $r->id)>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>District</label>
                    <select id="district_id" name="district_id" class="form-control">
                        <option value="">-- Select District --</option>
                        @if($performanceReview->district_id)
                            <option value="{{ $performanceReview->district_id }}" selected>{{ $performanceReview->district?->name }}</option>
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label>Ward</label>
                    <select id="ward_id" name="ward_id" class="form-control">
                        <option value="">-- Select Ward --</option>
                        @if($performanceReview->ward_id)
                            <option value="{{ $performanceReview->ward_id }}" selected>{{ $performanceReview->ward?->name }}</option>
                        @endif
                    </select>
                </div>

        <div style="display:flex; gap:0.75rem;"><button type="submit" style="padding:0.7rem 1.5rem; background:#f59e0b; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;">Update</button><a href="{{ route('performance-reviews.index') }}" style="padding:0.7rem 1.5rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;">Cancel</a></div>
    
                    
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


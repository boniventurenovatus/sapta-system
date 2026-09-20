@extends('layouts.sapta')

@section('title', 'New Task')
@section('page-title', 'New Task')

@section('content')
<div style="padding:1.5rem; max-width:900px; margin:0 auto;">
    <h1 style="font-size:1.75rem; font-weight:800; margin:0 0 1.5rem;">New Task</h1>

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

    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <div style="background:#fff; border-radius:1rem; border:1px solid #e2e8f0; padding:1.5rem; margin-bottom:1.5rem;">
            <h2 style="color:#2563eb; font-size:0.85rem; text-transform:uppercase; margin:0 0 1rem;">Task Information</h2>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div style="grid-column:1/-1;"><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Title *</label><input type="text" name="title" value="{{ old('title') }}" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div style="grid-column:1/-1;"><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Description</label><textarea name="description" rows="3" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;">{{ old('description') }}</textarea></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Project</label><select name="project_id" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="">? None ?</option>@foreach($projects as $p)<option value="{{ $p->id }}" @selected(old('project_id') == $p->id)>{{ $p->name }}</option>@endforeach</select></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Assigned To</label><select name="assigned_to" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="">? Unassigned ?</option>@foreach($employees as $emp)<option value="{{ $emp->id }}" @selected(old('assigned_to') == $emp->id)>{{ $emp->first_name }} {{ $emp->last_name }}</option>@endforeach</select></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Status *</label><select name="status" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="todo" @selected(old('status') === 'todo')>To Do</option><option value="in_progress" @selected(old('status') === 'in_progress')>In Progress</option><option value="review" @selected(old('status') === 'review')>Review</option><option value="done" @selected(old('status') === 'done')>Done</option></select></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Priority *</label><select name="priority" required style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="low" @selected(old('priority') === 'low')>Low</option><option value="medium" @selected(old('priority', 'medium') === 'medium')>Medium</option><option value="high" @selected(old('priority') === 'high')>High</option><option value="critical" @selected(old('priority') === 'critical')>Critical</option></select></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Start Date</label><input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Due Date</label><input type="date" name="due_date" value="{{ old('due_date') }}" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Estimated Hours</label><input type="number" name="estimated_hours" value="{{ old('estimated_hours', 0) }}" min="0" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Progress (%)</label><input type="number" name="progress" value="{{ old('progress', 0) }}" min="0" max="100" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Region</label><select id="region_id" name="region_id" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="">Select Region</option>@foreach($regions ?? [] as $r)<option value="{{ $r->id }}" @selected(old('region_id') == $r->id)>{{ $r->name }}</option>@endforeach</select></div>
                <div><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">District</label><select id="district_id" name="district_id" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="">Select District</option></select></div>
                <div style="grid-column:1/-1;"><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Ward</label><select id="ward_id" name="ward_id" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;"><option value="">Select Ward</option></select></div>
                <div style="grid-column:1/-1;"><label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Notes</label><textarea name="notes" rows="2" style="width:100%; padding:0.65rem; border:1.5px solid #e2e8f0; border-radius:0.5rem;">{{ old('notes') }}</textarea></div>
            </div>
        </div>
        <div style="display:flex; gap:0.75rem;">
            <button type="submit" style="padding:0.7rem 1.5rem; background:#2563eb; color:#fff; border:none; border-radius:0.5rem; font-weight:700; cursor:pointer;"><i class="fas fa-save"></i> Save Task</button>
            <a href="{{ route('tasks.index') }}" style="padding:0.7rem 1.5rem; background:#fff; color:#475569; border:1.5px solid #e2e8f0; border-radius:0.5rem; text-decoration:none; font-weight:700;">Cancel</a>
        </div>
    </form>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionSelect = document.getElementById('region_id');
    const districtSelect = document.getElementById('district_id');
    const wardSelect = document.getElementById('ward_id');

    if (regionSelect && districtSelect && wardSelect) {
        regionSelect.addEventListener('change', function() {
            const regionId = this.value;
            districtSelect.innerHTML = '<option value="">Loading...</option>';
            wardSelect.innerHTML = '<option value="">Select Ward</option>';

            if (!regionId) {
                districtSelect.innerHTML = '<option value="">Select District</option>';
                return;
            }

            fetch('/location/districts?region_id=' + regionId)
                .then(res => res.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">Select District</option>';
                    data.forEach(d => {
                        districtSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                    });
                });
        });

        districtSelect.addEventListener('change', function() {
            const districtId = this.value;
            wardSelect.innerHTML = '<option value="">Loading...</option>';

            if (!districtId) {
                wardSelect.innerHTML = '<option value="">Select Ward</option>';
                return;
            }

            fetch('/location/wards?district_id=' + districtId)
                .then(res => res.json())
                .then(data => {
                    wardSelect.innerHTML = '<option value="">Select Ward</option>';
                    data.forEach(w => {
                        wardSelect.innerHTML += `<option value="${w.id}">${w.name}</option>`;
                    });
                });
        });
    }
});
</script>
@endpush

@endsection



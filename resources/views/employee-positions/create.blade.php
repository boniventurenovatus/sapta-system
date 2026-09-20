@extends('layouts.sapta')

@section('title', 'Assign Position')
@section('page-title', 'Assign Position')

@section('content')
<div class="sapta-form-page">
    <div class="sapta-form-header">
        <div class="sapta-form-header-icon"><i class="fas fa-id-badge"></i></div>
        <div>
            <h1>Assign Position</h1>
            <p>Assign a position to an employee.</p>
        </div>
    </div>

    <div class="sapta-form-card">
        <form action="{{ route('employee-positions.store') }}" method="POST">
            @csrf
            <div class="sapta-form-section">
                <div class="sapta-form-section-title"><i class="fas fa-circle-info"></i> Assignment Information</div>

                <div class="sapta-form-group">
                    <label for="employee_id">Employee <span class="req">*</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-user prefix"></i>
                        <select id="employee_id" name="employee_id" class="sapta-form-control" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" @selected(old('employee_id') == $emp->id)>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label for="position_id">Position Title <span class="req">*</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-briefcase prefix"></i>
                        <select id="position_id" name="position_id" class="sapta-form-control" required>
                            <option value="">-- Select Position --</option>
                            @foreach($positions as $p)
                                <option value="{{ $p->id }}" data-code="{{ $p->code }}" data-unit="{{ $p->organizationalUnit?->name }}" @selected(old('position_id') == $p->id)>
                                    {{ $p->title }} ({{ $p->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label>Position Code</label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-hashtag prefix"></i>
                        <input type="text" id="position_code" class="sapta-form-control" readonly style="background:#f8fafc;">
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label>Organizational Unit</label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-building prefix"></i>
                        <input type="text" id="position_unit" class="sapta-form-control" readonly style="background:#f8fafc;">
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label for="start_date">Start Date <span class="req">*</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-calendar prefix"></i>
                        <input type="date" id="start_date" name="start_date" class="sapta-form-control" value="{{ old('start_date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label for="end_date">End Date <span class="hint">Optional</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-calendar prefix"></i>
                        <input type="date" id="end_date" name="end_date" class="sapta-form-control" value="{{ old('end_date') }}">
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label for="status">Status <span class="req">*</span></label>
                    <div class="sapta-input-wrapper">
                        <i class="fas fa-toggle-on prefix"></i>
                        <select id="status" name="status" class="sapta-form-control" required>
                            <option value="active" @selected(old('status') === 'active')>Active</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                            <option value="ended" @selected(old('status') === 'ended')>Ended</option>
                        </select>
                    </div>
                </div>

                <div class="sapta-form-group">
                    <label for="notes">Notes <span class="hint">Optional</span></label>
                    <textarea id="notes" name="notes" class="sapta-form-control">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="sapta-form-actions">
                <button type="submit" class="sapta-btn sapta-btn-primary"><i class="fas fa-save"></i> Save Assignment</button>
                <a href="{{ route('employee-positions.index') }}" class="sapta-btn sapta-btn-secondary"><i class="fas fa-xmark"></i> Cancel</a>
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
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const positionSelect = document.getElementById('position_id');
    const codeInput = document.getElementById('position_code');
    const unitInput = document.getElementById('position_unit');

    positionSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        codeInput.value = option.getAttribute('data-code') || '';
        unitInput.value = option.getAttribute('data-unit') || '';
    });
});
</script>
@endpush


@extends('layouts.sapta')

@section('title', 'New Project')
@section('page-title', 'New Project')

@section('content')

<style>
    .sapta-form-container {
        max-width: 800px;
        width: 100%;
        margin: 0 auto;
        background: #fff;
        padding: 20px 24px;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }

    .sapta-form-title {
        margin-bottom: 18px;
    }

    .sapta-form-title h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }

    .sapta-form-title p {
        color: #8898aa;
        font-size: 14px;
        margin: 4px 0 0;
    }

    .sapta-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #1a1a2e;
        margin: 16px 0 10px;
        padding-bottom: 7px;
        border-bottom: 2px solid #e8ecf1;
    }

    .sapta-form-group {
        margin-bottom: 12px;
    }

    .sapta-form-group label {
        display: block;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 4px;
        font-size: 13px;
    }

    .sapta-form-group .required {
        color: #dc2626;
    }

    .sapta-form-control {
        width: 100%;
        padding: 9px 12px;
        border: 2px solid #e8ecf1;
        border-radius: 8px;
        font-size: 14px;
        color: #1a1a2e;
        background: #f8f9fa;
        outline: none;
        font-family: inherit;
        transition: .2s;
    }

    .sapta-form-control:focus {
        border-color: #1a5276;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(26,82,118,.1);
    }

    .sapta-help-text {
        font-size: 12px;
        color: #8898aa;
        margin-top: 3px;
    }

    .sapta-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .sapta-form-actions {
        display: flex;
        gap: 12px;
        margin-top: 18px;
        padding-top: 14px;
        border-top: 1px solid #e8ecf1;
    }

    .sapta-form-actions .btn {
        padding: 9px 24px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .sapta-btn-cancel {
        background: #e8ecf1;
        color: #4a5a6f;
    }

    .sapta-btn-save {
        background: #1a5276;
        color: #fff;
    }

    .sapta-btn-save:hover {
        background: #154360;
    }

    textarea.sapta-form-control {
        resize: vertical;
        min-height: 65px;
    }

    @media (max-width: 768px) {
        .sapta-form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .sapta-form-container {
            padding: 16px;
        }

        .sapta-form-actions {
            flex-direction: column;
        }

        .sapta-form-actions .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="sapta-form-container">

```
<div class="sapta-form-title">
    <h2>New Project</h2>
    <p>Create a new project</p>
</div>

<form action="{{ route('projects.store') }}" method="POST">
    @csrf

    <div class="sapta-section-title">Basic Information</div>

    <div class="sapta-form-group">
        <label>
            Project Name <span class="required">*</span>
        </label>

        <input
            type="text"
            name="name"
            class="sapta-form-control"
            value="{{ old('name') }}"
            required
        >
    </div>

    <div class="sapta-form-row">

        <div class="sapta-form-group">
            <label>Project Code</label>

            <input
                type="text"
                name="code"
                class="sapta-form-control"
                value="{{ old('code') }}"
            >

            <div class="sapta-help-text">
                Unique identifier for the project
            </div>
        </div>

        <div class="sapta-form-group">
            <label>Budget</label>

            <input
                type="number"
                name="budget"
                class="sapta-form-control"
                step="0.01"
                value="{{ old('budget') }}"
            >
        </div>

    </div>

    <div class="sapta-form-group">
        <label>Description</label>

        <textarea
            name="description"
            class="sapta-form-control"
            rows="2"
        >{{ old('description') }}</textarea>
    </div>

    <div class="sapta-section-title">Assignment</div>

    <div class="sapta-form-row">

        <div class="sapta-form-group">
            <label>Organization</label>

            <select name="organization_id" class="sapta-form-control">
                <option value="">Select Organization</option>

                @foreach($organizations ?? [] as $org)
                    <option
                        value="{{ $org->id }}"
                        {{ old('organization_id') == $org->id ? 'selected' : '' }}
                    >
                        {{ $org->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="sapta-form-group">
            <label>Project Manager</label>

            <select name="project_manager_id" class="sapta-form-control">
                <option value="">Select Manager</option>

                @foreach($employees ?? [] as $emp)
                    <option
                        value="{{ $emp->id }}"
                        {{ old('project_manager_id') == $emp->id ? 'selected' : '' }}
                    >
                        {{ $emp->first_name }} {{ $emp->last_name }}
                    </option>
                @endforeach
            </select>
        </div>
    <div class="sapta-section-title">Location</div>

    <div class="sapta-form-row">

        <div class="sapta-form-group">
            <label>Region</label>

            <select id="region_id" name="region_id" class="sapta-form-control">
                <option value="">Select Region</option>

                @foreach($regions ?? [] as $region)
                    <option value="{{ $region->id }}" @selected(old('region_id') == $region->id)>{{ $region->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="sapta-form-group">
            <label>District</label>

            <select id="district_id" name="district_id" class="sapta-form-control">
                <option value="">Select District</option>
            </select>
        </div>

    </div>

    <div class="sapta-form-group">
        <label>Ward</label>

        <select id="ward_id" name="ward_id" class="sapta-form-control">
            <option value="">Select Ward</option>
        </select>
    </div>


    </div>

    <div class="sapta-section-title">Status & Timeline</div>

    <div class="sapta-form-row">

        <div class="sapta-form-group">
            <label>
                Status <span class="required">*</span>
            </label>

            <select name="status" class="sapta-form-control" required>
                <option value="planning">Planning</option>
                <option value="in_progress">In Progress</option>
                <option value="on_hold">On Hold</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="sapta-form-group">
            <label>
                Priority <span class="required">*</span>
            </label>

            <select name="priority" class="sapta-form-control" required>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
            </select>
        </div>

    </div>

    <div class="sapta-form-row">

        <div class="sapta-form-group">
            <label>Start Date</label>

            <input
                type="date"
                name="start_date"
                class="sapta-form-control"
                value="{{ old('start_date') }}"
            >
        </div>

        <div class="sapta-form-group">
            <label>End Date</label>

            <input
                type="date"
                name="end_date"
                class="sapta-form-control"
                value="{{ old('end_date') }}"
            >
        </div>

    </div>

    <div class="sapta-section-title">Notes</div>

    <div class="sapta-form-group">
        <label>Notes</label>

        <textarea
            name="notes"
            class="sapta-form-control"
            rows="2"
        >{{ old('notes') }}</textarea>
    </div>

    <div class="sapta-form-actions">

        <a
            href="{{ route('projects.index') }}"
            class="btn sapta-btn-cancel"
        >
            Cancel
        </a>

        <button
            type="submit"
            class="btn sapta-btn-save"
        >
            Create Project
        </button>

    </div>

</form>
```

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



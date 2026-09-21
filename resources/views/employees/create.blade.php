@extends('layouts.sapta')

@section('title', 'Add New Employee')

@section('page-title', 'Add New Employee')

@section('content')

<style>
    .employee-form-wrapper {
        max-width: 900px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #e8ecf1;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
        
    }

    .employee-form-header {
        padding: 24px 28px;
        border-bottom: 1px solid #e8ecf1;
        background: #ffffff;
    }

    .employee-form-heading {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .employee-form-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #eaf2f8;
        color: #1a5276;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .employee-form-heading h2 {
        margin: 0;
        color: #1a1a2e;
        font-size: 22px;
        font-weight: 700;
    }

    .employee-form-heading p {
        margin: 4px 0 0;
        color: #8898aa;
        font-size: 13px;
    }

    .employee-form-body {
        padding: 26px 28px;
    }

    .employee-section {
        margin-bottom: 28px;
    }

    .employee-section:last-child {
        margin-bottom: 0;
    }

    .employee-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e8ecf1;
    }

    .employee-section-number {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #1a5276;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
    }

    .employee-section-title h3 {
        margin: 0;
        color: #1a1a2e;
        font-size: 15px;
        font-weight: 700;
    }

    .employee-section-title p {
        margin: 2px 0 0;
        color: #8898aa;
        font-size: 12px;
    }

    .employee-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .employee-field {
        min-width: 0;
    }

    .employee-field.full {
        grid-column: 1 / -1;
    }

    .employee-field label {
        display: block;
        margin-bottom: 6px;
        color: #1a1a2e;
        font-size: 13px;
        font-weight: 600;
    }

    .employee-field label .required {
        color: #dc2626;
        margin-left: 3px;
    }

    .employee-input {
        position: relative;
    }

    .employee-input > i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #8898aa;
        font-size: 13px;
        pointer-events: none;
        z-index: 1;
    }

    .employee-input textarea + i,
    .employee-input.textarea > i {
        top: 17px;
        transform: none;
    }

    .employee-input input,
    .employee-input select,
    .employee-input textarea {
        width: 100%;
        border: 1px solid #dfe5ec;
        border-radius: 9px;
        background: #f8fafc;
        color: #1a1a2e;
        font-size: 14px;
        outline: none;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .employee-input input,
    .employee-input select {
        height: 42px;
        padding: 0 12px 0 38px;
    }

    .employee-input textarea {
        min-height: 90px;
        padding: 12px 12px 12px 38px;
        resize: vertical;
    }

    .employee-input input:focus,
    .employee-input select:focus,
    .employee-input textarea:focus {
        border-color: #1a5276;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(26, 82, 118, 0.08);
    }

    .employee-input select {
        cursor: pointer;
    }

    .employee-help {
        margin-top: 5px;
        color: #8898aa;
        font-size: 11px;
    }

    .employee-error {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 5px;
        color: #dc2626;
        font-size: 12px;
    }

    .employee-status-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 15px 16px;
        border: 1px solid #dfe5ec;
        border-radius: 10px;
        background: #f8fafc;
    }

    .employee-status-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .employee-status-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: #eaf2f8;
        color: #1a5276;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .employee-status-info strong {
        display: block;
        color: #1a1a2e;
        font-size: 13px;
    }

    .employee-status-info span {
        display: block;
        margin-top: 2px;
        color: #8898aa;
        font-size: 11px;
    }

    .
                    {{-- ADDITIONAL FIELDS --}}

                    <div class="employee-field full">

                        <h3 style="font-size: 1.1rem; font-weight: 700; margin: 1.5rem 0 1rem 0; padding-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb;">
                            <i class="fas fa-info-circle"></i>
                            Additional Information
                        </h3>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">

                            <div>
                                <label for="nationality">Nationality <span style="color: red;">*</span></label>
                                <input type="text" id="nationality" name="nationality" value="{{ old('nationality') }}" class="employee-input">
                                @error('nationality') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="marital_status">Marital Status <span style="color: red;">*</span></label>
                                <select id="marital_status" name="marital_status" class="employee-input">
                                    <option value="">-- Select --</option>
                                    <option value="single" @selected(old('marital_status') == 'single')>Single</option>
                                    <option value="married" @selected(old('marital_status') == 'married')>Married</option>
                                    <option value="divorced" @selected(old('marital_status') == 'divorced')>Divorced</option>
                                    <option value="widowed" @selected(old('marital_status') == 'widowed')>Widowed</option>
                                </select>
                                @error('marital_status') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="alternative_phone">Alternative Phone <span style="color: red;">*</span></label>
                                <input type="text" id="alternative_phone" name="alternative_phone" value="{{ old('alternative_phone') }}" class="employee-input">
                                @error('alternative_phone') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="city">City <span style="color: red;">*</span></label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}" class="employee-input">
                                @error('city') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="organizational_unit_id">Organizational Unit <span style="color: red;">*</span></label>
                                <select id="organizational_unit_id" name="organizational_unit_id" class="employee-input">
                                    <option value="">-- Select --</option>
                                    @foreach(($organizationalUnits ?? collect()) as $unit)
                                        <option value="{{ $unit->id }}" @selected(old('organizational_unit_id') == $unit->id)>{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                @error('organizational_unit_id') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="position_id">Position <span style="color: red;">*</span></label>
                                <select id="position_id" name="position_id" class="employee-input">
                                    <option value="">-- Select --</option>
                                    @foreach(($positions ?? collect()) as $position)
                                        <option value="{{ $position->id }}" @selected(old('position_id') == $position->id)>{{ $position->title ?? $position->name }}</option>
                                    @endforeach
                                </select>
                                @error('position_id') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="employment_type">Employment Type <span style="color: red;">*</span></label>
                                <select id="employment_type" name="employment_type" class="employee-input">
                                    <option value="">-- Select --</option>
                                    <option value="full_time" @selected(old('employment_type') == 'full_time')>Full Time</option>
                                    <option value="part_time" @selected(old('employment_type') == 'part_time')>Part Time</option>
                                    <option value="contract" @selected(old('employment_type') == 'contract')>Contract</option>
                                    <option value="intern" @selected(old('employment_type') == 'intern')>Intern</option>
                                </select>
                                @error('employment_type') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="contract_type">Contract Type <span style="color: red;">*</span></label>
                                <select id="contract_type" name="contract_type" class="employee-input">
                                    <option value="">-- Select --</option>
                                    <option value="permanent" @selected(old('contract_type') == 'permanent')>Permanent</option>
                                    <option value="temporary" @selected(old('contract_type') == 'temporary')>Temporary</option>
                                    <option value="probation" @selected(old('contract_type') == 'probation')>Probation</option>
                                </select>
                                @error('contract_type') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="salary">Salary (TZS) <span style="color: red;">*</span></label>
                                <input type="number" step="0.01" id="salary" name="salary" value="{{ old('salary') }}" class="employee-input">
                                @error('salary') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="bank_account">Bank Account <span style="color: red;">*</span></label>
                                <input type="text" id="bank_account" name="bank_account" value="{{ old('bank_account') }}" class="employee-input">
                                @error('bank_account') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="bank_name">Bank Name <span style="color: red;">*</span></label>
                                <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" class="employee-input">
                                @error('bank_name') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="tin_number">TIN Number <span style="color: red;">*</span></label>
                                <input type="text" id="tin_number" name="tin_number" value="{{ old('tin_number') }}" class="employee-input">
                                @error('tin_number') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="nssf_number">NSSF Number <span style="color: red;">*</span></label>
                                <input type="text" id="nssf_number" name="nssf_number" value="{{ old('nssf_number') }}" class="employee-input">
                                @error('nssf_number') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="nhif_number">NHIF Number <span style="color: red;">*</span></label>
                                <input type="text" id="nhif_number" name="nhif_number" value="{{ old('nhif_number') }}" class="employee-input">
                                @error('nhif_number') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="emergency_contact_name">Emergency Contact Name <span style="color: red;">*</span></label>
                                <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="employee-input">
                                @error('emergency_contact_name') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="emergency_contact_phone">Emergency Contact Phone <span style="color: red;">*</span></label>
                                <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="employee-input">
                                @error('emergency_contact_phone') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="emergency_relationship">Emergency Relationship <span style="color: red;">*</span></label>
                                <input type="text" id="emergency_relationship" name="emergency_relationship" value="{{ old('emergency_relationship') }}" class="employee-input">
                                @error('emergency_relationship') <div class="employee-error">{{ $message }}</div> @enderror
                            </div>

                        </div>

                    </div>
employee-form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 18px 28px;
        border-top: 1px solid #e8ecf1;
        background: #fafbfc;
    }

    .employee-btn {
        height: 40px;
        padding: 0 20px;
        border-radius: 8px;
        border: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        transition: all 0.2s ease;
    }

    .employee-btn-cancel {
        background: #e8ecf1;
        color: #4a5a6f;
    }

    .employee-btn-cancel:hover {
        background: #dce1e8;
        color: #1a1a2e;
    }

    .employee-btn-primary {
        background: #1a5276;
        color: #ffffff;
    }

    .employee-btn-primary:hover {
        background: #154360;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(26, 82, 118, 0.25);
    }

    @media (max-width: 768px) {
        .employee-form-wrapper {
            border-radius: 12px;
        }

        .employee-form-header,
        .employee-form-body {
            padding: 20px;
        }

        .employee-form-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .employee-field.full {
            grid-column: auto;
        }

        .employee-form-footer {
            padding: 16px 20px;
        }
    }

    @media (max-width: 520px) {
        .employee-form-heading h2 {
            font-size: 19px;
        }

        .employee-form-footer {
            flex-direction: column-reverse;
        }

        .employee-btn {
            width: 100%;
        }

        .employee-status-box {
            align-items: flex-start;
        }
    }
    .employee-form-body select {
        position: relative;
        z-index: 1;
    }
    .employee-form-body select:focus {
        z-index: 100;
    }
    .employee-form-body {
        
    }
    .employee-form-wrapper {
        overflow: visible !important;
        position: relative;
    }
    .employee-form-body {
        overflow: visible !important;
        position: relative;
    }
    .employee-section {
        overflow: visible !important;
        position: relative;
    }
    .employee-form-grid {
        overflow: visible !important;
    }
    .employee-field {
        overflow: visible !important;
    }
    select {
        position: relative;
        z-index: 1;
    }
    select:focus {
        z-index: 100;
    }
</style>


<div class="employee-form-wrapper">

    {{-- HEADER --}}
    <div class="employee-form-header">

        <div class="employee-form-heading">

            <div class="employee-form-icon">
                <i class="fas fa-user-plus"></i>
            </div>

            <div>
                <h2
                    data-en="Add New Employee"
                    data-sw="Ongeza Mfanyakazi Mpya"
                >
                    Add New Employee
                </h2>

                <p
                    data-en="Create an employee profile and assign organization and department."
                    data-sw="Tengeneza wasifu wa mfanyakazi na mpe shirika pamoja na idara."
                >
                    Create an employee profile and assign organization and department.
                </p>
            </div>

        </div>

    </div>


    {{-- FORM --}}
    <form
        action="{{ route('employees.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        <div class="employee-form-body">


            {{-- ========================================================= --}}
            {{-- 01 ORGANIZATION & DEPARTMENT --}}
            {{-- ========================================================= --}}

            <div class="employee-section">

                <div class="employee-section-title">

                    <div class="employee-section-number">
                        01
                    </div>

                    <div>
                        <h3
                            data-en="Organization Assignment"
                            data-sw="Ugawaji wa Shirika"
                        >
                            Organization Assignment
                        </h3>

                        <p
                            data-en="Assign the employee to an organization and department."
                            data-sw="Mpe mfanyakazi shirika na idara."
                        >
                            Assign the employee to an organization and department.
                        </p>
                    </div>

                </div>


                <div class="employee-form-grid">

                    {{-- ORGANIZATION --}}
                    <div class="employee-field">

                        <label for="organization_id">

                            <span
                                data-en="Organization"
                                data-sw="Shirika"
                            >
                                Organization
                            </span>

                        </label>

                        <div class="employee-input">

                            <i class="fas fa-building"></i>

                            <select
                                id="organization_id"
                                name="organization_id"
                            >

                                <option
                                    value=""
                                    data-en="Select Organization"
                                    data-sw="Chagua Shirika"
                                >
                                    Select Organization
                                </option>

                                @foreach($organizations as $organization)

                                    <option
                                        value="{{ $organization->id }}"
                                        {{ old('organization_id') == $organization->id ? 'selected' : '' }}
                                    >
                                        {{ $organization->name }}

                                        @if($organization->code)
                                            ? {{ $organization->code }}
                                        @endif
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        @error('organization_id')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- DEPARTMENT --}}
                    <div class="employee-field">

                        <label for="department_id">

                            <span
                                data-en="Department"
                                data-sw="Idara"
                            >
                                Department
                            </span>

                        </label>

                        <div class="employee-input">

                            <i class="fas fa-sitemap"></i>

                            <select
                                id="department_id"
                                name="department_id"
                               
                             required>

                                <option
                                    value=""
                                    data-en="Select Department"
                                    data-sw="Chagua Idara"
                                >
                                    Select Department
                                </option>

                                @foreach(($departments ?? \App\Models\Department::where("is_active", true)->orderBy("name")->get()) as $department)

                                    <option
                                        value="{{ $department->id }}"
                                        data-organization-id="{{ $department->organization_id }}"
                                        {{ old('department_id') == $department->id ? 'selected' : '' }}
                                    >
                                        {{ $department->name }}

                                        @if($department->code)
                                            | {{ $department->code }}
                                        @endif
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div
                            class="employee-help"
                            data-en="Departments are linked to their organization."
                            data-sw="Idara zimeunganishwa na mashirika yao."
                        >
                            Departments are linked to their organization.
                        </div>

                        @error('department_id')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- 02 PERSONAL INFORMATION --}}
            {{-- ========================================================= --}}

            <div class="employee-section">

                <div class="employee-section-title">

                    <div class="employee-section-number">
                        02
                    </div>

                    <div>
                        <h3
                            data-en="Personal Information"
                            data-sw="Maelezo ya Kibinafsi"
                        >
                            Personal Information
                        </h3>

                        <p
                            data-en="Enter the employee's basic personal information."
                            data-sw="Weka taarifa za msingi za mfanyakazi."
                        >
                            Enter the employee's basic personal information.
                        </p>
                    </div>

                </div>


                <div class="employee-form-grid">

                    {{-- FIRST NAME --}}
                    <div class="employee-field">

                        <label for="first_name">
                            First Name
                            <span class="required">*</span>
                        </label>

                        <div class="employee-input">

                            <i class="fas fa-user"></i>

                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                maxlength="255"
                                required
                            >

                        </div>

                        @error('first_name')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- MIDDLE NAME --}}
                    <div class="employee-field">

                        <label for="middle_name">
                            Middle Name
                        </label>

                        <div class="employee-input">

                            <i class="fas fa-user"></i>

                            <input
                                type="text"
                                id="middle_name"
                                name="middle_name"
                                value="{{ old('middle_name') }}"
                                maxlength="255"
                            >

                        </div>

                        @error('middle_name')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- LAST NAME --}}
                    <div class="employee-field">

                        <label for="last_name">
                            Last Name
                            <span class="required">*</span>
                        </label>

                        <div class="employee-input">

                            <i class="fas fa-user"></i>

                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                maxlength="255"
                                required
                            >

                        </div>

                        @error('last_name')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- GENDER --}}
                    <div class="employee-field">

                        <label for="gender">
                            Gender
                        </label>

                        <div class="employee-input">

                            <i class="fas fa-venus-mars"></i>

                            <select
                                id="gender"
                                name="gender"
                             required>

                                <option value="">
                                    Select Gender
                                </option>

                                <option
                                    value="male"
                                    {{ old('gender') === 'male' ? 'selected' : '' }}
                                >
                                    Male
                                </option>

                                <option
                                    value="female"
                                    {{ old('gender') === 'female' ? 'selected' : '' }}
                                >
                                    Female
                                </option>

                                <option
                                    value="other"
                                    {{ old('gender') === 'other' ? 'selected' : '' }}
                                >
                                    Other
                                </option>

                            </select>

                        </div>

                        @error('gender')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- DATE OF BIRTH --}}
                    <div class="employee-field">

                        <label for="date_of_birth">
                            Date of Birth
                        </label>

                        <div class="employee-input">

                            <i class="fas fa-calendar"></i>

                            <input
                                type="date"
                                id="date_of_birth"
                                name="date_of_birth"
                                value="{{ old('date_of_birth') }}"
                            >

                        </div>

                        @error('date_of_birth')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- 03 CONTACT INFORMATION --}}
            {{-- ========================================================= --}}

            <div class="employee-section">

                <div class="employee-section-title">

                    <div class="employee-section-number">
                        03
                    </div>

                    <div>
                        <h3
                            data-en="Contact Information"
                            data-sw="Maelezo ya Mawasiliano"
                        >
                            Contact Information
                        </h3>

                        <p
                            data-en="Enter the employee's contact details."
                            data-sw="Weka maelezo ya mawasiliano ya mfanyakazi."
                        >
                            Enter the employee's contact details.
                        </p>
                    </div>

                </div>


                <div class="employee-form-grid">

                    {{-- EMAIL --}}
                    <div class="employee-field">

                        <label for="email">
                            Email
                        </label>

                        <div class="employee-input">

                            <i class="fas fa-envelope"></i>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                maxlength="255"
                             required>

                        </div>

                        @error('email')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- PHONE --}}
                    <div class="employee-field">

                        <label for="phone">
                            Phone
                        </label>

                        <div class="employee-input">

                            <i class="fas fa-phone"></i>

                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                maxlength="20"
                             required>

                        </div>

                        @error('phone')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- ADDRESS --}}
                    <div class="employee-field full">

                        <label for="address">
                            Address
                        </label>

                        <div class="employee-input textarea">

                            <i class="fas fa-location-dot"></i>

                            <textarea
                                id="address"
                                name="address"
                                maxlength="5000"
                                placeholder="Employee address..."
                             required>{{ old('address') }}</textarea>

                        </div>

                        @error('address')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                    {{-- REGION --}}
                    <div class="employee-field">
                        <label for="region_id">
                            Region <span style="color:#dc2626;">*</span>
                        </label>
                        <div class="employee-input">
                            <i class="fas fa-map"></i>
                            <select id="region_id" name="region_id">
                                <option value="">Select Region</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" @selected(old('region_id') == $region->id)>{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('region_id')
                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- DISTRICT --}}
                    <div class="employee-field">
                        <label for="district_id">
                            District <span style="color:#dc2626;">*</span>
                        </label>
                        <div class="employee-input">
                            <i class="fas fa-map-location-dot"></i>
                            <select id="district_id" name="district_id">
                                <option value="">Select District</option>
                                @foreach($districts ?? [] as $district)
                                    <option value="{{ $district->id }}" @selected(old('district_id') == $district->id)>{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('district_id')
                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- WARD --}}
                    <div class="employee-field">
                        <label for="ward_id">
                            Ward
                        </label>
                        <div class="employee-input">
                            <i class="fas fa-location-crosshairs"></i>
                            <select id="ward_id" name="ward_id">
                                <option value="">Select Ward</option>
                                @foreach($wards ?? [] as $ward)
                                    <option value="{{ $ward->id }}" @selected(old('ward_id') == $ward->id)>{{ $ward->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('ward_id')
                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- 04 EMPLOYMENT INFORMATION --}}
            {{-- ========================================================= --}}

            <div class="employee-section">

                <div class="employee-section-title">

                    <div class="employee-section-number">
                        04
                    </div>

                    <div>
                        <h3
                            data-en="Employment Information"
                            data-sw="Maelezo ya Ajira"
                        >
                            Employment Information
                        </h3>

                        <p
                            data-en="Enter the employee's employment details."
                            data-sw="Weka taarifa za ajira za mfanyakazi."
                        >
                            Enter the employee's employment details.
                        </p>
                    </div>

                </div>


                <div class="employee-form-grid">

                    {{-- EMPLOYEE NUMBER --}}
                    <div class="employee-field">

                        <label for="employee_number">
                            Employee Number
                            <span class="required">*</span>
                        </label>

                        <div class="employee-input">

                            <i class="fas fa-id-card"></i>

                            <input
                                type="text"
                                id="employee_number"
                                name="employee_number"
                                value="{{ old('employee_number') }}"
                                maxlength="50"
                                placeholder="e.g. EMP-001"
                                required
                            >

                        </div>

                        <div
                            class="employee-help"
                            data-en="Enter a unique employee number."
                            data-sw="Weka nambari ya kipekee ya mfanyakazi."
                        >
                            Enter a unique employee number.
                        </div>

                        @error('employee_number')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- JOB TITLE --}}
                    <div class="employee-field">

                        <label for="job_title">
                            Job Title
                        </label>

                        <div class="employee-input">

                            <i class="fas fa-briefcase"></i>

                            <input
                                type="text"
                                id="job_title"
                                name="job_title"
                                value="{{ old('job_title') }}"
                                maxlength="150"
                                placeholder="e.g. HR Officer"
                             required>

                        </div>

                        @error('job_title')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- HIRE DATE --}}
                    <div class="employee-field">

                        <label for="hire_date">
                            Hire Date
                        </label>

                        <div class="employee-input">

                            <i class="fas fa-calendar-check"></i>

                            <input
                                type="date"
                                id="hire_date"
                                name="hire_date"
                                value="{{ old('hire_date', date('Y-m-d')) }}"
                             required>

                        </div>

                        @error('hire_date')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- STATUS --}}
                    <div class="employee-field">

                        <label for="employment_status">
                            Employment Status
                            <span class="required">*</span>
                        </label>

                        <div class="employee-input">

                            <i class="fas fa-circle-check"></i>

                            <select
                                id="employment_status"
                                name="employment_status"
                                required
                            >

                                <option
                                    value="active"
                                    {{ old('employment_status', 'active') === 'active' ? 'selected' : '' }}
                                >
                                    Active
                                </option>

                                <option
                                    value="inactive"
                                    {{ old('employment_status') === 'inactive' ? 'selected' : '' }}
                                >
                                    Inactive
                                </option>

                                <option
                                    value="on_leave"
                                    {{ old('employment_status') === 'on_leave' ? 'selected' : '' }}
                                >
                                    On Leave
                                </option>

                                <option
                                    value="suspended"
                                    {{ old('employment_status') === 'suspended' ? 'selected' : '' }}
                                >
                                    Suspended
                                </option>

                                <option
                                    value="terminated"
                                    {{ old('employment_status') === 'terminated' ? 'selected' : '' }}
                                >
                                    Terminated
                                </option>

                            </select>

                        </div>

                        @error('employment_status')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- 05 PROFILE IMAGE --}}
            {{-- ========================================================= --}}

            <div class="employee-section">

                <div class="employee-section-title">

                    <div class="employee-section-number">
                        05
                    </div>

                    <div>
                        <h3
                            data-en="Profile Image"
                            data-sw="Picha ya Profaili"
                        >
                            Profile Image
                        </h3>

                        <p
                            data-en="Upload an optional employee profile image."
                            data-sw="Pakia picha ya profaili ya mfanyakazi ikiwa unahitaji."
                        >
                            Upload an optional employee profile image.
                        </p>
                    </div>

                </div>


                <div class="employee-form-grid">

                    <div class="employee-field full">

                        <label for="profile_image">
                            Profile Image
                        </label>

                        <div class="employee-input">

                            <i class="fas fa-image"></i>

                            <input
                                type="file"
                                id="profile_image"
                                name="profile_image"
                                accept="image/*"
                                style="padding-top: 10px;"
                            >

                        </div>

                        <div class="employee-help">
                            JPG, PNG or GIF. Maximum 2MB.
                        </div>

                        @error('profile_image')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- 06 NOTES --}}
            {{-- ========================================================= --}}

            <div class="employee-section">

                <div class="employee-section-title">

                    <div class="employee-section-number">
                        06
                    </div>

                    <div>
                        <h3
                            data-en="Additional Notes"
                            data-sw="Maelezo ya Ziada"
                        >
                            Additional Notes
                        </h3>

                        <p
                            data-en="Add any additional information about the employee."
                            data-sw="Ongeza taarifa nyingine muhimu kuhusu mfanyakazi."
                        >
                            Add any additional information about the employee.
                        </p>
                    </div>

                </div>


                <div class="employee-form-grid">

                    <div class="employee-field full">

                        <label for="notes">
                            Notes
                        </label>

                        <div class="employee-input textarea">

                            <i class="fas fa-align-left"></i>

                            <textarea
                                id="notes"
                                name="notes"
                                maxlength="5000"
                                placeholder="Additional notes..."
                            >{{ old('notes') }}</textarea>

                        </div>

                        @error('notes')

                            <div class="employee-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


        </div>


        {{-- ============================================================= --}}
        {{-- FOOTER --}}
        {{-- ============================================================= --}}

        <div class="employee-form-footer">

            <a
                href="{{ route('employees.index') }}"
                class="employee-btn employee-btn-cancel"
            >
                <i class="fas fa-xmark"></i>
                <span
                    data-en="Cancel"
                    data-sw="Ghairi"
                >
                    Cancel
                </span>
            </a>

            <button
                type="submit"
                class="employee-btn employee-btn-primary"
            >
                <i class="fas fa-user-plus"></i>
                <span
                    data-en="Save Employee"
                    data-sw="Hifadhi Mfanyakazi"
                >
                    Save Employee
                </span>
            </button>

        </div>

    </form>

</div>


{{-- ================================================================ --}}
{{-- DEPARTMENT FILTER BY ORGANIZATION --}}
{{-- ================================================================ --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const organizationSelect =
        document.getElementById('organization_id');

    const departmentSelect =
        document.getElementById('department_id');

    if (!organizationSelect || !departmentSelect) {
        return;
    }

    const allDepartmentOptions =
        Array.from(
            departmentSelect.querySelectorAll(
                'option[data-organization-id]'
            )
        );

    function filterDepartments() {

        const organizationId =
            organizationSelect.value;

        const currentDepartment =
            departmentSelect.value;

        allDepartmentOptions.forEach(function (option) {

            if (!organizationId) {

                option.hidden = false;

                return;
            }

            option.hidden =
                option.dataset.organizationId !== organizationId;
        });


        /*
        |--------------------------------------------------------------------------
        | Clear invalid department selection
        |--------------------------------------------------------------------------
        */

        const selectedOption =
            departmentSelect.options[
                departmentSelect.selectedIndex
            ];

        if (
            selectedOption &&
            selectedOption.dataset.organizationId &&
            selectedOption.dataset.organizationId !== organizationId
        ) {
            departmentSelect.value = '';
        }
    }


    organizationSelect.addEventListener(
        'change',
        filterDepartments
    );


    /*
    |--------------------------------------------------------------------------
    | Initial filtering
    |--------------------------------------------------------------------------
    */

    filterDepartments();

    /*
    |--------------------------------------------------------------------------
    | LOCATION ? Dependent Dropdowns
    |--------------------------------------------------------------------------
    */

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
                })
                .catch(err => {
                    districtSelect.innerHTML = '<option value="">Error loading</option>';
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
                })
                .catch(err => {
                    wardSelect.innerHTML = '<option value="">Error loading</option>';
                });
        });
    }

});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    // Data kutoka Controller
    const districtsData = @json($districts ?? []);
    const wardsData = @json($wards ?? []);

    const regionSelect = document.getElementById('region_id');
    const districtSelect = document.getElementById('district_id');
    const wardSelect = document.getElementById('ward_id');

    if (!regionSelect || !districtSelect || !wardSelect) {
        console.warn('Location dropdowns hazipo');
        return;
    }

    // Thamani za awali
    const oldDistrictId = '{{ old('district_id') }}';
    const oldWardId = '{{ old('ward_id') }}';

    function populateDistricts(regionId, selectedDistrictId = null) {
        districtSelect.innerHTML = '<option value="">Select District</option>';
        wardSelect.innerHTML = '<option value="">Select Ward</option>';

        if (!regionId) return;

        const filtered = districtsData.filter(d => d.region_id == regionId);
        filtered.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = d.name;
            if (selectedDistrictId && d.id == selectedDistrictId) {
                opt.selected = true;
            }
            districtSelect.appendChild(opt);
        });
    }

    function populateWards(districtId, selectedWardId = null) {
        wardSelect.innerHTML = '<option value="">Select Ward</option>';

        if (!districtId) return;

        const filtered = wardsData.filter(w => w.district_id == districtId);
        filtered.forEach(w => {
            const opt = document.createElement('option');
            opt.value = w.id;
            opt.textContent = w.name;
            if (selectedWardId && w.id == selectedWardId) {
                opt.selected = true;
            }
            wardSelect.appendChild(opt);
        });
    }

    // Region change
    regionSelect.addEventListener('change', function () {
        populateDistricts(this.value);
    });

    // District change
    districtSelect.addEventListener('change', function () {
        populateWards(this.value);
    });

    // On load
    if (regionSelect.value) {
        populateDistricts(regionSelect.value, oldDistrictId);
    }
    if (districtSelect.value) {
        populateWards(districtSelect.value, oldWardId);
    }
});
</script>@endsection














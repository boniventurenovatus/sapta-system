@extends('layouts.sapta')

@section('title', 'Add New Employee')
@section('page-title', 'Add New Employee')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 2rem;">

    <div><strong>User:</strong> {{ auth()->user()?->email ?? 'HAJALOGIN' }}</div>
    <div><strong>Errors:</strong> {{ json_encode($errors->all()) }}</div>
</div>


    <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem;">Add New Employee</h1>
    <p style="color: #64748b; margin-bottom: 2rem;">Create an employee profile.</p>

    @if ($errors->any())
        <div style="background:#fee2e2; border:1px solid #ef4444; border-radius:8px; padding:1rem; margin-bottom:1.5rem;">
            <h4 style="color:#dc2626; font-weight:700; margin-bottom:0.5rem;">Kuna errors:</h4>
            <ul style="margin:0; padding-left:1.5rem; color:#991b1b;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div style="background:#dcfce7; border:1px solid #22c55e; border-radius:8px; padding:1rem; margin-bottom:1.5rem; color:#166534;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data"
          style="background:#fff; border-radius:12px; padding:2rem; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
        @csrf

        <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:1px solid #e5e7eb;">Location</h3>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:2rem;">
            <div><label>Region *</label>
                <select name="region_id" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="">-- Select --</option>
                    @foreach($regions ?? [] as $region)
                        <option value="{{ $region->id }}" @selected(old('region_id') == $region->id)>{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label>District *</label>
                <select name="district_id" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="">-- Select --</option>
                    @foreach($districts ?? [] as $district)
                        <option value="{{ $district->id }}" @selected(old('district_id') == $district->id)>{{ $district->name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label>Ward *</label>
                <select name="ward_id" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="">-- Select --</option>
                    @foreach($wards ?? [] as $ward)
                        <option value="{{ $ward->id }}" @selected(old('ward_id') == $ward->id)>{{ $ward->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:1px solid #e5e7eb;">Organization</h3>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:2rem;">
            <div><label>Organization *</label>
                <select name="organization_id" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="">-- Select --</option>
                    @foreach($organizations ?? [] as $org)
                        <option value="{{ $org->id }}" @selected(old('organization_id') == $org->id)>{{ $org->name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label>Department *</label>
                <select name="department_id" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="">-- Select --</option>
                    @foreach($departments ?? [] as $dept)
                        <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id)>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label>Organizational Unit *</label>
                <select name="organizational_unit_id" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="">-- Select --</option>
                    @foreach($organizationalUnits ?? [] as $unit)
                        <option value="{{ $unit->id }}" @selected(old('organizational_unit_id') == $unit->id)>{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label>Position *</label>
                <select name="position_id" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="">-- Select --</option>
                    @foreach($positions ?? [] as $pos)
                        <option value="{{ $pos->id }}" @selected(old('position_id') == $pos->id)>{{ $pos->title ?? $pos->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:1px solid #e5e7eb;">Personal Information</h3>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:2rem;">
            <div><label>Employee Number *</label><input type="text" name="employee_number" value="{{ old('employee_number') }}" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>First Name *</label><input type="text" name="first_name" value="{{ old('first_name') }}" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Middle Name</label><input type="text" name="middle_name" value="{{ old('middle_name') }}" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Last Name *</label><input type="text" name="last_name" value="{{ old('last_name') }}" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Gender *</label>
                <select name="gender" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="">-- Select --</option>
                    <option value="male" @selected(old('gender') == 'male')>Male</option>
                    <option value="female" @selected(old('gender') == 'female')>Female</option>
                    <option value="other" @selected(old('gender') == 'other')>Other</option>
                </select>
            </div>
            <div><label>Date of Birth *</label><input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Nationality *</label><input type="text" name="nationality" value="{{ old('nationality', 'Tanzanian') }}" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Marital Status</label>
                <select name="marital_status" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="">-- Select --</option>
                    <option value="single" @selected(old('marital_status') == 'single')>Single</option>
                    <option value="married" @selected(old('marital_status') == 'married')>Married</option>
                    <option value="divorced" @selected(old('marital_status') == 'divorced')>Divorced</option>
                    <option value="widowed" @selected(old('marital_status') == 'widowed')>Widowed</option>
                </select>
            </div>
            <div><label>Email *</label><input type="email" name="email" value="{{ old('email') }}" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Phone *</label><input type="text" name="phone" value="{{ old('phone') }}" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Alternative Phone</label><input type="text" name="alternative_phone" value="{{ old('alternative_phone') }}" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Address</label><input type="text" name="address" value="{{ old('address') }}" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>City</label><input type="text" name="city" value="{{ old('city') }}" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
        </div>

        <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:1px solid #e5e7eb;">Employment</h3>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:2rem;">
            <div><label>Hire Date *</label><input type="date" name="hire_date" value="{{ old('hire_date') }}" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Job Title *</label><input type="text" name="job_title" value="{{ old('job_title') }}" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Employment Status *</label>
                <select name="employment_status" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="active" @selected(old('employment_status', 'active') == 'active')>Active</option>
                    <option value="inactive" @selected(old('employment_status') == 'inactive')>Inactive</option>
                    <option value="on_leave" @selected(old('employment_status') == 'on_leave')>On Leave</option>
                    <option value="suspended" @selected(old('employment_status') == 'suspended')>Suspended</option>
                    <option value="terminated" @selected(old('employment_status') == 'terminated')>Terminated</option>
                </select>
            </div>
            <div><label>Employment Type *</label>
                <select name="employment_type" required style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="full_time" @selected(old('employment_type', 'full_time') == 'full_time')>Full Time</option>
                    <option value="part_time" @selected(old('employment_type') == 'part_time')>Part Time</option>
                    <option value="contract" @selected(old('employment_type') == 'contract')>Contract</option>
                    <option value="intern" @selected(old('employment_type') == 'intern')>Intern</option>
                </select>
            </div>
            <div><label>Contract Type</label>
                <select name="contract_type" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="permanent" @selected(old('contract_type', 'permanent') == 'permanent')>Permanent</option>
                    <option value="temporary" @selected(old('contract_type') == 'temporary')>Temporary</option>
                    <option value="probation" @selected(old('contract_type') == 'probation')>Probation</option>
                </select>
            </div>
            <div><label>Salary</label><input type="number" step="0.01" name="salary" value="{{ old('salary') }}" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Bank Account</label><input type="text" name="bank_account" value="{{ old('bank_account') }}" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Bank Name</label><input type="text" name="bank_name" value="{{ old('bank_name') }}" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>TIN Number</label><input type="text" name="tin_number" value="{{ old('tin_number') }}" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>NSSF Number</label><input type="text" name="nssf_number" value="{{ old('nssf_number') }}" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>NHIF Number</label><input type="text" name="nhif_number" value="{{ old('nhif_number') }}" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
        </div>

        <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:1px solid #e5e7eb;">Emergency Contact</h3>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:2rem;">
            <div><label>Emergency Contact Name</label><input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Emergency Contact Phone</label><input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Emergency Relationship</label><input type="text" name="emergency_relationship" value="{{ old('emergency_relationship') }}" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
        </div>

        <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:1px solid #e5e7eb;">Profile & Notes</h3>
        <div style="display:grid; gap:1rem; margin-bottom:2rem;">
            <div><label>Profile Image</label><input type="file" name="profile_image" accept="image/*" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;"></div>
            <div><label>Notes</label><textarea name="notes" rows="4" style="width:100%; padding:0.6rem; border:1px solid #d1d5db; border-radius:6px;">{{ old('notes') }}</textarea></div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:1rem; padding-top:1.5rem; border-top:1px solid #e5e7eb;">
            <a href="{{ route('employees.index') }}" style="padding:0.75rem 1.5rem; border:1px solid #d1d5db; border-radius:6px; text-decoration:none; color:#374151;">Cancel</a>
            <button type="submit" style="padding:0.75rem 1.5rem; background:#1a5276; color:#fff; border:none; border-radius:6px; font-weight:600; cursor:pointer;">
                Save Employee
            </button>
        </div>

    </form>
</div>
@endsection
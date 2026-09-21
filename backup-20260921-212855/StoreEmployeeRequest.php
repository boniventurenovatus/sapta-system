<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // ===== LOCATION =====
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'district_id' => ['required', 'integer', 'exists:districts,id'],
            'ward_id' => ['required', 'integer', 'exists:wards,id'],

            // ===== ORGANIZATION =====
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'organizational_unit_id' => ['required', 'integer', 'exists:organizational_units,id'],
            'position_id' => ['required', 'integer', 'exists:positions,id'],

            // ===== BASIC INFO =====
            'employee_number' => ['required', 'string', 'max:50', 'unique:employees,employee_number'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:30'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'nationality' => ['required', 'string', 'max:100'],
            'marital_status' => ['required', 'string', 'max:30'],

            // ===== CONTACT =====
            'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
            'phone' => ['required', 'string', 'max:20'],
            'alternative_phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:5000'],
            'city' => ['required', 'string', 'max:100'],

            // ===== EMPLOYMENT =====
            'hire_date' => ['required', 'date'],
            'job_title' => ['required', 'string', 'max:150'],
            'employment_status' => ['required', 'in:active,inactive,on_leave,suspended,terminated'],
            'employment_type' => ['required', 'in:full_time,part_time,contract,intern'],
            'contract_type' => ['required', 'string', 'max:50'],

            // ===== FINANCIAL =====
            'salary' => ['required', 'numeric', 'min:0'],
            'bank_account' => ['required', 'string', 'max:50'],
            'bank_name' => ['required', 'string', 'max:100'],
            'tin_number' => ['required', 'string', 'max:50'],
            'nssf_number' => ['required', 'string', 'max:50'],
            'nhif_number' => ['required', 'string', 'max:50'],

            // ===== EMERGENCY =====
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'emergency_relationship' => ['required', 'string', 'max:50'],

            // ===== OTHER =====
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'notes' => ['required', 'string', 'max:5000'],

            // ===== USER ACCOUNT (optional) =====
            'create_user_account' => ['nullable', 'boolean'],
            'username' => ['nullable', 'string', 'max:100', 'unique:users,username'],
            'user_password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['nullable', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'region_id.required' => 'Region is required.',
            'district_id.required' => 'District is required.',
            'ward_id.required' => 'Ward is required.',
            'organization_id.required' => 'Organization is required.',
            'department_id.required' => 'Department is required.',
            'organizational_unit_id.required' => 'Organizational unit is required.',
            'position_id.required' => 'Position is required.',
            'employee_number.required' => 'Employee number is required.',
            'employee_number.unique' => 'Employee number already exists.',
            'first_name.required' => 'First name is required.',
            'middle_name.required' => 'Middle name is required.',
            'last_name.required' => 'Last name is required.',
            'gender.required' => 'Gender is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'nationality.required' => 'Nationality is required.',
            'marital_status.required' => 'Marital status is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'Email already exists.',
            'phone.required' => 'Phone is required.',
            'alternative_phone.required' => 'Alternative phone is required.',
            'address.required' => 'Address is required.',
            'city.required' => 'City is required.',
            'hire_date.required' => 'Hire date is required.',
            'job_title.required' => 'Job title is required.',
            'employment_status.required' => 'Employment status is required.',
            'employment_type.required' => 'Employment type is required.',
            'contract_type.required' => 'Contract type is required.',
            'salary.required' => 'Salary is required.',
            'bank_account.required' => 'Bank account is required.',
            'bank_name.required' => 'Bank name is required.',
            'tin_number.required' => 'TIN number is required.',
            'nssf_number.required' => 'NSSF number is required.',
            'nhif_number.required' => 'NHIF number is required.',
            'emergency_contact_name.required' => 'Emergency contact name is required.',
            'emergency_contact_phone.required' => 'Emergency contact phone is required.',
            'emergency_relationship.required' => 'Emergency relationship is required.',
            'notes.required' => 'Notes is required.',
        ];
    }
}
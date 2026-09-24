<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')->id;

        return [
            // ===== LOCATION =====
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'district_id' => ['required', 'integer', 'exists:districts,id'],
            'ward_id' => ['required', 'integer', 'exists:wards,id'],

            // ===== ORGANIZATION =====
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'organizational_unit_id' => ['nullable', 'integer', 'exists:organizational_units,id'],
            'position_id' => ['nullable', 'integer', 'exists:positions,id'],

            // ===== BASIC INFO =====
            'employee_number' => [
                'required', 'string', 'max:50',
                Rule::unique('employees', 'employee_number')->ignore($employeeId),
            ],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:30'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'marital_status' => ['nullable', 'string', 'max:30'],

            // ===== CONTACT =====
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('employees', 'email')->ignore($employeeId),
            ],
            'phone' => ['required', 'string', 'max:20'],
            'alternative_phone' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:5000'],
            'city' => ['nullable', 'string', 'max:100'],

            // ===== EMPLOYMENT =====
            'hire_date' => ['required', 'date'],
            'job_title' => ['required', 'string', 'max:150'],
            'employment_status' => ['required', 'in:active,inactive,on_leave,suspended,terminated'],
            'employment_type' => ['nullable', 'in:full_time,part_time,contract,intern'],
            'contract_type' => ['nullable', 'string', 'max:50'],

            // ===== FINANCIAL =====
            'salary' => ['nullable', 'numeric', 'min:0'],
            'bank_account' => ['nullable', 'string', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'tin_number' => ['nullable', 'string', 'max:50'],
            'nssf_number' => ['nullable', 'string', 'max:50'],
            'nhif_number' => ['nullable', 'string', 'max:50'],

            // ===== EMERGENCY =====
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_relationship' => ['nullable', 'string', 'max:50'],

            // ===== OTHER =====
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'notes' => ['required', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_number.required' => 'Employee number is required.',
            'employee_number.unique' => 'This employee number already exists.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email already exists.',
            'employment_status.required' => 'Employment status is required.',
            'employment_status.in' => 'Employment status is invalid.',
            'salary.numeric' => 'Salary must be a number.',
            'salary.min' => 'Salary cannot be negative.',
            'profile_image.image' => 'Profile image must be an image file.',
            'profile_image.mimes' => 'Profile image must be JPG, JPEG, PNG, or GIF.',
            'profile_image.max' => 'Profile image cannot exceed 2MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'employee_number' => strtoupper(trim($this->employee_number ?? '')),
            'email' => trim(strtolower($this->email ?? '')),
        ]);
    }
}
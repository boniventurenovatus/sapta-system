<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|integer|exists:employees,id',
            'leave_type' => 'required|in:annual,sick,maternity,paternity,study,other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:5|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Employee is required.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'leave_type.required' => 'Leave type is required.',
            'leave_type.in' => 'Leave type must be Annual, Sick, Maternity, Paternity, Study, or Other.',
            'start_date.required' => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date cannot be in the past.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be on or after start date.',
            'reason.required' => 'Reason is required.',
            'reason.min' => 'Reason must be at least 5 characters.',
            'reason.max' => 'Reason cannot exceed 2000 characters.',
        ];
    }
}
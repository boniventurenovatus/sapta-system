<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'employee_id' => 'nullable|exists:employees,id|unique:users,employee_id,' . $userId,
            'username' => [
                'required', 'string', 'min:3', 'max:50',
                'regex:/^[a-zA-Z0-9_\.]+$/',
                'unique:users,username,' . $userId,
            ],
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'nullable|exists:roles,id',
            'account_status' => 'required|in:active,inactive,suspended',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'username.min' => 'Username must be at least 3 characters.',
            'username.max' => 'Username cannot exceed 50 characters.',
            'username.regex' => 'Username can only contain letters, numbers, underscore, and dot.',
            'username.unique' => 'This username already exists.',
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email already exists.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'role_id.exists' => 'The selected role does not exist.',
            'account_status.required' => 'Account status is required.',
            'account_status.in' => 'Status must be Active, Inactive, or Suspended.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => trim(strtolower($this->username ?? '')),
            'email' => trim(strtolower($this->email ?? '')),
        ]);
    }
}
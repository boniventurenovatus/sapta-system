<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $project = $this->route('project');
        if (!$project) {
            return false;
        }

        return auth()->user()->can('update', $project);
    }

    public function rules(): array
    {
        $project = $this->route('project');

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('projects', 'code')->ignore($project->id)],
            'description' => ['nullable', 'string'],
            'organization_id' => ['nullable', 'integer', 'exists:organizations,id'],
            'region_id' => ['nullable', 'integer', 'exists:regions,id'],
            'district_id' => ['nullable', 'integer', 'exists:districts,id'],
            'ward_id' => ['nullable', 'integer', 'exists:wards,id'],
            'project_manager_id' => ['nullable', 'integer', 'exists:employees,id'],
            'status' => ['required', 'in:planning,in_progress,on_hold,completed,cancelled'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
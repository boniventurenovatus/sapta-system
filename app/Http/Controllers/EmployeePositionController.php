<?php

namespace App\Http\Controllers;

use App\Models\EmployeePosition;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;

class EmployeePositionController extends Controller
{
    public function index()
    {
        $employeePositions = EmployeePosition::with(['employee', 'position.organizationalUnit'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('employee-positions.index', compact('employeePositions'));
    }

    public function create()
    {
        $regions = \App\Models\Region::orderBy('name')->get();
        $employees = Employee::orderBy('first_name')->get();
        $positions = Position::with('organizationalUnit')->where('status', 'active')->orderBy('title')->get();

        return view('employee-positions.create', compact('employees', 'positions', 'regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'position_id' => 'required|exists:positions,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_primary' => 'boolean',
            'status' => 'required|in:active,inactive,ended',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'notes' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        EmployeePosition::create($validated);

        return redirect()->route('employee-positions.index')
            ->with('success', 'Employee position assigned successfully.');
    }

    public function show(EmployeePosition $employeePosition)
    {
        $employeePosition->load(['employee', 'position.organizationalUnit']);
        return view('employee-positions.show', compact('employeePosition'));
    }

    public function edit(EmployeePosition $employeePosition)
    {
        $regions = \App\Models\Region::orderBy('name')->get();
        $employees = Employee::orderBy('first_name')->get();
        $positions = Position::with('organizationalUnit')->where('status', 'active')->orderBy('title')->get();

        return view('employee-positions.edit', compact('employeePosition', 'employees', 'positions', 'regions'));
    }

    public function update(Request $request, EmployeePosition $employeePosition)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'position_id' => 'required|exists:positions,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_primary' => 'boolean',
            'status' => 'required|in:active,inactive,ended',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'notes' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $employeePosition->update($validated);

        return redirect()->route('employee-positions.index')
            ->with('success', 'Employee position updated successfully.');
    }

    public function destroy(EmployeePosition $employeePosition)
    {
        $employeePosition->delete();

        return redirect()->route('employee-positions.index')
            ->with('success', 'Employee position removed successfully.');
    }
}





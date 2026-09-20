<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee')->orderBy('attendance_date', 'desc');
        $user = auth()->user();
        $roleCodes = $user->roles->pluck('code')->toArray();

        $isStaff = in_array('staff', $roleCodes);
        $isManager = in_array('manager', $roleCodes);

        // Staff na Manager wanaona attendance yao tu
        if ($isStaff || $isManager) {
            $employeeId = $user->employee_id;
            if ($employeeId) {
                $query->where('employee_id', $employeeId);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('search')) {
            $s = $request->get('search');
            $query->whereHas('employee', function ($q) use ($s) {
                $q->where('first_name', 'LIKE', "%{$s}%")
                  ->orWhere('last_name', 'LIKE', "%{$s}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->get('date'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $attendances = $query->paginate(15)->withQueryString();

        return view('attendances.index', compact('attendances'));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        return view('attendances.create', compact('employees', 'regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:present,absent,late,on_leave',
            'notes' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
        ], [
            'employee_id.required' => 'Please select an employee.',
            'attendance_date.required' => 'Please select a date.',
        ]);

        // Check for duplicate
        $exists = Attendance::where('employee_id', $validated['employee_id'])
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['attendance_date' => 'This employee already has an attendance record for this date.']);
        }

        Attendance::create($validated);

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance recorded successfully.');
    }

    public function show(Attendance $attendance)
    {
        return view('attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $employees = Employee::orderBy('first_name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        $attendance->load(['district', 'ward', 'region']);
        return view('attendances.edit', compact('attendance', 'employees', 'regions'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:present,absent,late,on_leave',
            'notes' => 'nullable|string',
        ]);

        // Check for duplicate (ignore current record)
        $exists = Attendance::where('employee_id', $validated['employee_id'])
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->where('id', '!=', $attendance->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['attendance_date' => 'This employee already has an attendance record for this date.']);
        }

        $attendance->update($validated);

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendances.index')
            ->with('success', 'Attendance deleted successfully.');
    }
}




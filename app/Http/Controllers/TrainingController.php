<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Helpers\NotificationHelper;
use App\Models\TrainingEnrollment;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = Training::with(['department', 'createdBy'])
            ->withCount(['enrollments'])
            ->orderBy('start_date', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'LIKE', "%{$s}%")
                  ->orWhere('training_number', 'LIKE', "%{$s}%")
                  ->orWhere('trainer_name', 'LIKE', "%{$s}%");
            });
        }

        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('department_id')) $query->where('department_id', $request->department_id);

        $trainings = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Training::count(),
            'planned' => Training::where('status', 'planned')->count(),
            'ongoing' => Training::where('status', 'ongoing')->count(),
            'completed' => Training::where('status', 'completed')->count(),
            'total_enrolled' => TrainingEnrollment::count(),
        ];

        $departments = Department::orderBy('name')->get();

        return view('trainings.index', compact('trainings', 'stats', 'departments'));
    }

    public function create()
    {
        $departments = \App\Models\Department::where('is_active', true)->orderBy('name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $employees = Employee::orderBy('first_name')->get();
        $nextNumber = Training::generateNumber();
        return view('trainings.create', compact('departments', 'employees', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'training_number' => 'required|string|max:50|unique:trainings,training_number',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:orientation,technical,soft_skills,compliance,leadership,safety,other',
            'trainer_name' => 'nullable|string|max:200',
            'trainer_type' => 'required|in:internal,external',
            'trainer_contact' => 'nullable|string|max:200',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'duration_hours' => 'nullable|integer|min:0',
            'max_participants' => 'nullable|integer|min:0',
            'cost' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'department_id' => 'nullable|exists:departments,id',
            'notes' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'planned';

        Training::create($validated);

        return redirect()->route('trainings.index')
            ->with('success', 'Training created successfully.');
    }

    public function show(Training $training)
    {
        $training->load(['department', 'createdBy', 'enrollments.employee']);
        $employees = Employee::orderBy('first_name')->get();
        return view('trainings.show', compact('training', 'employees'));
    }

    public function edit(Training $training)
    {
        $regions = \App\Models\Region::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        return view('trainings.edit', compact('training', 'departments', 'regions'));
    }

    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:orientation,technical,soft_skills,compliance,leadership,safety,other',
            'trainer_name' => 'nullable|string|max:200',
            'trainer_type' => 'required|in:internal,external',
            'trainer_contact' => 'nullable|string|max:200',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'duration_hours' => 'nullable|integer|min:0',
            'max_participants' => 'nullable|integer|min:0',
            'cost' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'notes' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $training->update($validated);

        return redirect()->route('trainings.index')
            ->with('success', 'Training updated successfully.');
    }

    public function destroy(Training $training)
    {
        $training->delete();
        return redirect()->route('trainings.index')
            ->with('success', 'Training deleted.');
    }

    public function enroll(Request $request, Training $training)
    {
        $validated = $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        $count = 0;
        foreach ($validated['employee_ids'] as $empId) {
            $exists = TrainingEnrollment::where('training_id', $training->id)
                ->where('employee_id', $empId)
                ->exists();

            if (!$exists) {
                $enrollment = TrainingEnrollment::create([
                    'training_id' => $training->id,
                    'employee_id' => $empId,
                    'status' => 'enrolled',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
                ]);

                NotificationHelper::notifyTrainingEnrollment($enrollment);

                $count++;
            }
        }

        return back()->with('success', "$count employee(s) enrolled successfully.");
    }

    public function updateEnrollment(Request $request, TrainingEnrollment $enrollment)
    {
        $validated = $request->validate([
            'status' => 'required|in:enrolled,attended,completed,dropped',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'score' => 'nullable|numeric|min:0|max:100',
            'certificate_number' => 'nullable|string|max:100',
            'feedback' => 'nullable|string',
        ]);

        if ($validated['status'] === 'completed') {
            $validated['completed_at'] = now();
        }

        $enrollment->update($validated);

        return back()->with('success', 'Enrollment updated.');
    }

    public function removeEnrollment(TrainingEnrollment $enrollment)
    {
        $enrollment->delete();
        return back()->with('success', 'Enrollment removed.');
    }

    public function printCertificate(TrainingEnrollment $enrollment)
    {
        $enrollment->load(['training', 'employee']);
        return view('trainings.pdf.certificate', compact('enrollment'));
    }

    public function exportCsv()
    {
        $trainings = Training::with(['department'])->withCount('enrollments')->orderBy('start_date', 'desc')->get();
        $filename = 'trainings-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($trainings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['Training #', 'Title', 'Category', 'Trainer', 'Location', 'Start', 'End', 'Duration', 'Cost', 'Enrolled', 'Status']);

            foreach ($trainings as $t) {
                fputcsv($file, [
                    $t->training_number, $t->title, $t->category, $t->trainer_name,
                    $t->location, $t->start_date->format('Y-m-d'), $t->end_date->format('Y-m-d'),
                    $t->duration_hours, $t->cost, $t->enrollments_count, $t->status,
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}







<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Helpers\NotificationHelper;
use App\Models\JobApplication;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecruitmentController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosting::with(['department', 'position'])
            ->withCount('applications')
            ->orderBy('posted_date', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'LIKE', "%{$s}%")
                  ->orWhere('job_number', 'LIKE', "%{$s}%");
            });
        }

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('department_id')) $query->where('department_id', $request->department_id);

        $jobs = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => JobPosting::count(),
            'open' => JobPosting::where('status', 'open')->count(),
            'closed' => JobPosting::where('status', 'closed')->count(),
            'applications' => JobApplication::count(),
            'hired' => JobApplication::where('status', 'hired')->count(),
        ];

        $departments = Department::orderBy('name')->get();

        return view('recruitment.index', compact('jobs', 'stats', 'departments'));
    }

    public function create()
    {
        $regions = \App\Models\Region::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('title')->get();
        $nextNumber = JobPosting::generateNumber();
        return view('recruitment.create', compact('departments', 'positions', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_number' => 'required|string|max:50|unique:job_postings,job_number',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'employment_type' => 'required|in:full_time,part_time,contract,internship',
            'experience_level' => 'required|in:entry,mid,senior,executive',
            'vacancies' => 'required|integer|min:1',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'location' => 'nullable|string|max:255',
            'posted_date' => 'required|date',
            'closing_date' => 'required|date|after_or_equal:posted_date',
            'notes' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'draft';

        JobPosting::create($validated);

        return redirect()->route('recruitment.index')
            ->with('success', 'Job posting created successfully.');
    }

    public function show(JobPosting $job)
    {
        $job->load(['department', 'position', 'applications']);
        return view('recruitment.show', compact('job'));
    }

    public function edit(JobPosting $job)
    {
        $regions = \App\Models\Region::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('title')->get();
        return view('recruitment.edit', compact('job', 'departments', 'positions', 'regions'));
    }

    public function update(Request $request, JobPosting $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'employment_type' => 'required|in:full_time,part_time,contract,internship',
            'experience_level' => 'required|in:entry,mid,senior,executive',
            'vacancies' => 'required|integer|min:1',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'location' => 'nullable|string|max:255',
            'posted_date' => 'required|date',
            'closing_date' => 'required|date|after_or_equal:posted_date',
            'status' => 'required|in:draft,open,closed,filled,cancelled',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'notes' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $job->update($validated);

        return redirect()->route('recruitment.index')
            ->with('success', 'Job posting updated.');
    }

    public function destroy(JobPosting $job)
    {
        $job->delete();
        return redirect()->route('recruitment.index')
            ->with('success', 'Job posting deleted.');
    }

    public function open(JobPosting $job)
    {
        $job->update(['status' => 'open']);
        return back()->with('success', 'Job posting opened.');
    }

    public function close(JobPosting $job)
    {
        $job->update(['status' => 'closed']);
        return back()->with('success', 'Job posting closed.');
    }

    // APPLICATIONS

    public function addApplication(Request $request, JobPosting $job)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:200',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:50',
            'resume' => 'nullable|file|max:5120|mimes:pdf,doc,docx',
            'cover_letter' => 'nullable|string',
        ]);

        if ($request->hasFile('resume')) {
            $validated['resume_path'] = $request->file('resume')->store('resumes', 'public');
        }

        $validated['job_posting_id'] = $job->id;
        $validated['status'] = 'applied';

        $application = JobApplication::create($validated);

        NotificationHelper::notifyJobApplication($application);

        return back()->with('success', 'Application added.');
    }

    public function updateApplication(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:applied,screening,interview,offered,hired,rejected',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'rating' => 'nullable|integer|min:1|max:5',
            'interview_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $application->update($validated);

        return back()->with('success', 'Application updated.');
    }

    public function downloadResume(JobApplication $application)
    {
        if (!$application->resume_path || !Storage::disk('public')->exists($application->resume_path)) {
            return back()->with('error', 'Resume not found.');
        }
        return Storage::disk('public')->download($application->resume_path);
    }

    public function removeApplication(JobApplication $application)
    {
        if ($application->resume_path && Storage::disk('public')->exists($application->resume_path)) {
            Storage::disk('public')->delete($application->resume_path);
        }
        $application->delete();
        return back()->with('success', 'Application removed.');
    }

    public function exportCsv()
    {
        $jobs = JobPosting::with(['department'])->withCount('applications')->orderBy('posted_date', 'desc')->get();
        $filename = 'jobs-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($jobs) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['Job #', 'Title', 'Department', 'Type', 'Level', 'Vacancies', 'Location', 'Posted', 'Closing', 'Applications', 'Status']);

            foreach ($jobs as $j) {
                fputcsv($file, [
                    $j->job_number, $j->title, $j->department->name ?? '', $j->employment_type,
                    $j->experience_level, $j->vacancies, $j->location,
                    $j->posted_date->format('Y-m-d'), $j->closing_date->format('Y-m-d'),
                    $j->applications_count, $j->status,
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}






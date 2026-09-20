<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\Department;
use App\Models\Payslip;
use App\Models\PaymentVoucher;
use App\Models\Receipt;
use App\Models\Budget;
use App\Models\Training;
use App\Models\TrainingEnrollment;
use App\Models\PerformanceReview;
use App\Models\Document;
use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'employees' => Employee::count(),
            'attendance' => Attendance::count(),
            'leaves' => LeaveRequest::count(),
            'projects' => Project::count(),
            'tasks' => Task::count(),
            'payslips' => Payslip::count(),
            'vouchers' => PaymentVoucher::count(),
            'receipts' => Receipt::count(),
            'budgets' => Budget::count(),
            'trainings' => Training::count(),
            'performance' => PerformanceReview::count(),
            'documents' => Document::count(),
        ];

        return view('reports.index', compact('stats'));
    }

    // ========== EMPLOYEES ==========
    public function employees(Request $request)
    {
        $query = Employee::with(['department', 'organization', 'region', 'district', 'ward']);
        if ($request->filled('department_id')) $query->where('department_id', $request->department_id);
        if ($request->filled('status')) $query->where('employment_status', $request->status);
        if ($request->filled('region_id')) $query->where('region_id', $request->region_id);
        if ($request->filled('district_id')) $query->where('district_id', $request->district_id);

        $employees = $query->orderBy('first_name')->get();
        $departments = Department::orderBy('name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();

        $stats = [
            'total' => $employees->count(),
            'active' => $employees->where('employment_status', 'active')->count(),
            'inactive' => $employees->where('employment_status', 'inactive')->count(),
            'on_leave' => $employees->where('employment_status', 'on_leave')->count(),
        ];
        return view('reports.employees', compact('employees', 'departments', 'regions', 'stats'));
    }

    // ========== ATTENDANCE ==========
    public function attendance(Request $request)
    {
        $query = Attendance::with(['employee', 'region', 'district', 'ward']);
        if ($request->filled('from')) $query->whereDate('attendance_date', '>=', $request->from);
        if ($request->filled('to')) $query->whereDate('attendance_date', '<=', $request->to);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('region_id')) $query->where('region_id', $request->region_id);

        $attendances = $query->orderBy('attendance_date', 'desc')->get();
        $regions = \App\Models\Region::orderBy('name')->get();

        $stats = [
            'total' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
        ];
        return view('reports.attendance', compact('attendances', 'regions', 'stats'));
    }

    // ========== LEAVES ==========
    public function leaves(Request $request)
    {
        $query = LeaveRequest::with('employee');
        if ($request->filled('status')) $query->where('status', $request->status);
        $leaves = $query->orderBy('created_at', 'desc')->get();
        $stats = [
            'total' => $leaves->count(),
            'pending' => $leaves->where('status', 'pending')->count(),
            'approved' => $leaves->where('status', 'approved')->count(),
            'rejected' => $leaves->where('status', 'rejected')->count(),
        ];
        return view('reports.leaves', compact('leaves', 'stats'));
    }

    // ========== PROJECTS ==========
    public function projects(Request $request)
    {
        $projects = Project::with('organization')->orderBy('created_at', 'desc')->get();
        $stats = [
            'total' => $projects->count(),
            'active' => $projects->where('status', 'active')->count(),
            'completed' => $projects->where('status', 'completed')->count(),
            'overdue' => $projects->where('status', 'overdue')->count(),
        ];
        return view('reports.projects', compact('projects', 'stats'));
    }

    // ========== TASKS ==========
    public function tasks(Request $request)
    {
        $tasks = Task::with(['project', 'assignee'])->orderBy('created_at', 'desc')->get();
        $stats = [
            'total' => $tasks->count(),
            'todo' => $tasks->where('status', 'todo')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'done' => $tasks->where('status', 'done')->count(),
        ];
        return view('reports.tasks', compact('tasks', 'stats'));
    }

    // ========== PAYROLL ==========
    public function payroll(Request $request)
    {
        $query = Payslip::with('employee');
        if ($request->filled('month')) $query->where('month', $request->month);
        if ($request->filled('year')) $query->where('year', $request->year);
        $payslips = $query->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        $stats = [
            'total' => $payslips->count(),
            'gross' => $payslips->sum('gross_salary'),
            'deductions' => $payslips->sum('total_deductions'),
            'net' => $payslips->sum('net_salary'),
        ];
        return view('reports.payroll', compact('payslips', 'stats'));
    }

    // ========== PAYMENT VOUCHERS ==========
    public function paymentVouchers(Request $request)
    {
        $query = PaymentVoucher::with('project');
        if ($request->filled('status')) $query->where('status', $request->status);
        $vouchers = $query->orderBy('created_at', 'desc')->get();
        $stats = [
            'total' => $vouchers->count(),
            'draft' => $vouchers->where('status', 'draft')->count(),
            'pending' => $vouchers->where('status', 'pending')->count(),
            'approved' => $vouchers->where('status', 'approved')->count(),
            'paid' => $vouchers->where('status', 'paid')->count(),
            'total_amount' => $vouchers->sum('amount'),
        ];
        return view('reports.payment-vouchers', compact('vouchers', 'stats'));
    }

    // ========== RECEIPTS ==========
    public function receipts(Request $request)
    {
        $query = Receipt::with('project');
        if ($request->filled('status')) $query->where('status', $request->status);
        $receipts = $query->orderBy('created_at', 'desc')->get();
        $stats = [
            'total' => $receipts->count(),
            'draft' => $receipts->where('status', 'draft')->count(),
            'confirmed' => $receipts->where('status', 'confirmed')->count(),
            'cancelled' => $receipts->where('status', 'cancelled')->count(),
            'total_amount' => $receipts->sum('amount'),
        ];
        return view('reports.receipts', compact('receipts', 'stats'));
    }

    // ========== BUDGETS ==========
    public function budgets(Request $request)
    {
        $budgets = Budget::with(['project', 'department'])->orderBy('fiscal_year', 'desc')->get();
        $stats = [
            'total' => $budgets->count(),
            'allocated' => $budgets->sum('allocated_amount'),
            'spent' => $budgets->sum('spent_amount'),
            'remaining' => $budgets->sum('allocated_amount') - $budgets->sum('spent_amount'),
        ];
        return view('reports.budgets', compact('budgets', 'stats'));
    }

    // ========== TRAININGS ==========
    public function trainings(Request $request)
    {
        $trainings = Training::withCount('enrollments')->orderBy('start_date', 'desc')->get();
        $stats = [
            'total' => $trainings->count(),
            'planned' => $trainings->where('status', 'planned')->count(),
            'ongoing' => $trainings->where('status', 'ongoing')->count(),
            'completed' => $trainings->where('status', 'completed')->count(),
            'enrolled' => TrainingEnrollment::count(),
        ];
        return view('reports.trainings', compact('trainings', 'stats'));
    }

    // ========== RECRUITMENT ==========
    public function recruitment(Request $request)
    {
        $jobs = JobPosting::withCount('applications')->orderBy('created_at', 'desc')->get();
        $applications = JobApplication::with('jobPosting')->orderBy('created_at', 'desc')->get();
        $stats = [
            'total_jobs' => $jobs->count(),
            'open_jobs' => $jobs->where('status', 'open')->count(),
            'applications' => $applications->count(),
            'hired' => $applications->where('status', 'hired')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
        ];
        return view('reports.recruitment', compact('jobs', 'applications', 'stats'));
    }

    // ========== PERFORMANCE ==========
    public function performance(Request $request)
    {
        $reviews = PerformanceReview::with('employee')->orderBy('review_date', 'desc')->get();
        $stats = [
            'total' => $reviews->count(),
            'avg_rating' => $reviews->avg('overall_rating') ?? 0,
            'draft' => $reviews->where('status', 'draft')->count(),
            'submitted' => $reviews->where('status', 'submitted')->count(),
            'approved' => $reviews->where('status', 'approved')->count(),
        ];
        return view('reports.performance', compact('reviews', 'stats'));
    }

    // ========== DOCUMENTS ==========
    public function documents(Request $request)
    {
        $documents = Document::with(['employee', 'project'])->orderBy('created_at', 'desc')->get();
        $stats = [
            'total' => $documents->count(),
            'active' => $documents->where('status', 'active')->count(),
            'draft' => $documents->where('status', 'draft')->count(),
            'expiring' => $documents->filter(fn($d) => $d->is_expiring_soon)->count(),
        ];
        return view('reports.documents', compact('documents', 'stats'));
    }

    // ========== EXPORTS (Generic) ==========

    public function exportEmployeesCsv()
    {
        $employees = Employee::with('department')->get();
        return $this->csv('employees-' . date('Y-m-d') . '.csv',
            ['Name', 'Email', 'Phone', 'Department', 'Status'],
            $employees->map(fn($e) => [
                $e->first_name . ' ' . $e->last_name, $e->email, $e->phone,
                $e->department->name ?? '', $e->status ?? 'active',
            ])->toArray()
        );
    }

    public function exportPayrollCsv()
    {
        $payslips = Payslip::with('employee')->get();
        return $this->csv('payroll-' . date('Y-m-d') . '.csv',
            ['Payslip #', 'Employee', 'Period', 'Gross', 'Deductions', 'Net', 'Status'],
            $payslips->map(fn($p) => [
                $p->payslip_number, $p->employee->first_name . ' ' . $p->employee->last_name,
                $p->period, $p->gross_salary, $p->total_deductions, $p->net_salary, $p->status,
            ])->toArray()
        );
    }

    public function exportVouchersCsv()
    {
        $vouchers = PaymentVoucher::with('project')->get();
        return $this->csv('vouchers-' . date('Y-m-d') . '.csv',
            ['Voucher #', 'Payee', 'Type', 'Amount', 'Currency', 'Status', 'Date'],
            $vouchers->map(fn($v) => [
                $v->voucher_number, $v->payee_name, $v->payee_type,
                $v->amount, $v->currency, $v->status, $v->voucher_date->format('Y-m-d'),
            ])->toArray()
        );
    }

    public function exportReceiptsCsv()
    {
        $receipts = Receipt::with('project')->get();
        return $this->csv('receipts-' . date('Y-m-d') . '.csv',
            ['Receipt #', 'Payer', 'Type', 'Amount', 'Currency', 'Status', 'Date'],
            $receipts->map(fn($r) => [
                $r->receipt_number, $r->payer_name, $r->payer_type,
                $r->amount, $r->currency, $r->status, $r->receipt_date->format('Y-m-d'),
            ])->toArray()
        );
    }

    public function exportBudgetsCsv()
    {
        $budgets = Budget::with('department')->get();
        return $this->csv('budgets-' . date('Y-m-d') . '.csv',
            ['Budget #', 'Name', 'Year', 'Category', 'Allocated', 'Spent', 'Remaining', 'Status'],
            $budgets->map(fn($b) => [
                $b->budget_number, $b->name, $b->fiscal_year, $b->category,
                $b->allocated_amount, $b->spent_amount, $b->remaining_amount, $b->status,
            ])->toArray()
        );
    }

    public function exportTrainingsCsv()
    {
        $trainings = Training::withCount('enrollments')->get();
        return $this->csv('trainings-' . date('Y-m-d') . '.csv',
            ['Training #', 'Title', 'Category', 'Trainer', 'Start', 'End', 'Enrolled', 'Status'],
            $trainings->map(fn($t) => [
                $t->training_number, $t->title, $t->category, $t->trainer_name,
                $t->start_date->format('Y-m-d'), $t->end_date->format('Y-m-d'),
                $t->enrollments_count, $t->status,
            ])->toArray()
        );
    }

    public function exportRecruitmentCsv()
    {
        $jobs = JobPosting::withCount('applications')->get();
        return $this->csv('jobs-' . date('Y-m-d') . '.csv',
            ['Job #', 'Title', 'Type', 'Level', 'Vacancies', 'Applications', 'Status'],
            $jobs->map(fn($j) => [
                $j->job_number, $j->title, $j->employment_type, $j->experience_level,
                $j->vacancies, $j->applications_count, $j->status,
            ])->toArray()
        );
    }

    public function exportPerformanceCsv()
    {
        $reviews = PerformanceReview::with('employee')->get();
        return $this->csv('performance-' . date('Y-m-d') . '.csv',
            ['Review #', 'Employee', 'Period', 'Rating', 'Status', 'Date'],
            $reviews->map(fn($r) => [
                $r->review_number, $r->employee->first_name . ' ' . $r->employee->last_name,
                $r->review_period, $r->overall_rating, $r->status, $r->review_date->format('Y-m-d'),
            ])->toArray()
        );
    }

    public function exportDocumentsCsv()
    {
        $documents = Document::with('employee')->get();
        return $this->csv('documents-' . date('Y-m-d') . '.csv',
            ['Document #', 'Title', 'Category', 'Size', 'Status', 'Uploaded'],
            $documents->map(fn($d) => [
                $d->document_number, $d->title, $d->category,
                $d->file_size_formatted, $d->status, $d->created_at->format('Y-m-d'),
            ])->toArray()
        );
    }

    private function csv($filename, array $headers, array $rows)
    {
        $callback = function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $headers);
            foreach ($rows as $row) fputcsv($file, $row);
            fclose($file);
        };

        return new StreamedResponse($callback, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    // ========== LOCATION REPORTS ==========

    public function locationIndex()
    {
        $stats = [
            'regions' => \App\Models\Region::count(),
            'districts' => \App\Models\District::count(),
            'wards' => \App\Models\Ward::count(),
            'employees_with_location' => Employee::whereNotNull('region_id')->count(),
            'projects_with_location' => Project::whereNotNull('region_id')->count(),
        ];

        return view('reports.location-index', compact('stats'));
    }

    public function employeesByRegion(Request $request)
    {
        $query = Employee::with(['region', 'district', 'ward', 'department'])
            ->whereNotNull('region_id');

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        $employees = $query->orderBy('first_name')->get();

        $grouped = $employees->groupBy('region.name')->map->count()->sortDesc();
        $byDistrict = $employees->groupBy('district.name')->map->count()->sortDesc();
        $byWard = $employees->groupBy('ward.name')->map->count()->sortDesc();

        $regions = \App\Models\Region::orderBy('name')->get();

        $stats = [
            'total' => $employees->count(),
            'regions' => $grouped->count(),
            'top_region' => $grouped->keys()->first() ?? '—',
            'top_count' => $grouped->first() ?? 0,
        ];

        return view('reports.employees-by-region', compact('employees', 'grouped', 'byDistrict', 'byWard', 'regions', 'stats'));
    }

    public function exportEmployeesByRegionCsv(Request $request)
    {
        $query = Employee::with(['region', 'district', 'ward', 'department'])
            ->whereNotNull('region_id');

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        $employees = $query->orderBy('first_name')->get();

        $filename = 'employees_by_region_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Employee Number', 'Name', 'Email', 'Department', 'Region', 'District', 'Ward']);
            foreach ($employees as $e) {
                fputcsv($file, [
                    $e->employee_number,
                    $e->first_name . ' ' . $e->last_name,
                    $e->email,
                    $e->department?->name,
                    $e->region?->name,
                    $e->district?->name,
                    $e->ward?->name,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function projectsByRegion(Request $request)
    {
        $query = Project::with(['region', 'district', 'ward'])
            ->whereNotNull('region_id');

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        $projects = $query->orderBy('name')->get();

        $grouped = $projects->groupBy('region.name')->map->count()->sortDesc();
        $byDistrict = $projects->groupBy('district.name')->map->count()->sortDesc();
        $byStatus = $projects->groupBy('status')->map->count();

        $regions = \App\Models\Region::orderBy('name')->get();

        $stats = [
            'total' => $projects->count(),
            'regions' => $grouped->count(),
            'top_region' => $grouped->keys()->first() ?? '—',
            'top_count' => $grouped->first() ?? 0,
        ];

        return view('reports.projects-by-region', compact('projects', 'grouped', 'byDistrict', 'byStatus', 'regions', 'stats'));
    }

    public function attendanceByRegion(Request $request)
    {
        $query = Attendance::with(['employee', 'region', 'district', 'ward'])
            ->whereNotNull('region_id');

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        if ($request->filled('from')) {
            $query->whereDate('attendance_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('attendance_date', '<=', $request->to);
        }

        $attendances = $query->orderBy('attendance_date', 'desc')->get();

        $grouped = $attendances->groupBy('region.name')->map->count()->sortDesc();
        $byStatus = $attendances->groupBy('status')->map->count();

        $regions = \App\Models\Region::orderBy('name')->get();

        $stats = [
            'total' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
        ];

        return view('reports.attendance-by-region', compact('attendances', 'grouped', 'byStatus', 'regions', 'stats'));
    }

    public function budgetsByRegion(Request $request)
    {
        $query = Budget::with(['department', 'project', 'region', 'district', 'ward'])
            ->whereNotNull('region_id');

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        $budgets = $query->orderBy('name')->get();

        $grouped = $budgets->groupBy('region.name')->map(function($items) {
            return [
                'count' => $items->count(),
                'allocated' => $items->sum('allocated_amount'),
                'spent' => $items->sum('spent_amount'),
            ];
        });

        $regions = \App\Models\Region::orderBy('name')->get();

        $stats = [
            'total' => $budgets->count(),
            'total_allocated' => $budgets->sum('allocated_amount'),
            'total_spent' => $budgets->sum('spent_amount'),
            'regions' => $grouped->count(),
        ];

        return view('reports.budgets-by-region', compact('budgets', 'grouped', 'regions', 'stats'));
    }

}
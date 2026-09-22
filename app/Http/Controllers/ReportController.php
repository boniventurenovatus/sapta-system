<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    public function index()
    {
        $stats = $this->allStats();
        $reports = [
            ['title' => 'Employee Report', 'route' => 'reports.employees', 'icon' => 'fa-users', 'color' => 'blue', 'desc' => 'All employees data'],
            ['title' => 'Attendance Report', 'route' => 'reports.attendance', 'icon' => 'fa-clock', 'color' => 'green', 'desc' => 'Attendance records'],
            ['title' => 'Leaves Report', 'route' => 'reports.leaves', 'icon' => 'fa-calendar-check', 'color' => 'yellow', 'desc' => 'Leave requests'],
            ['title' => 'Payroll Report', 'route' => 'reports.payroll', 'icon' => 'fa-money-bill-wave', 'color' => 'purple', 'desc' => 'Payroll data'],
            ['title' => 'Budgets Report', 'route' => 'reports.budgets', 'icon' => 'fa-wallet', 'color' => 'green', 'desc' => 'Budget usage'],
            ['title' => 'Projects Report', 'route' => 'reports.projects', 'icon' => 'fa-diagram-project', 'color' => 'indigo', 'desc' => 'Projects overview'],
            ['title' => 'Tasks Report', 'route' => 'reports.tasks', 'icon' => 'fa-list-check', 'color' => 'red', 'desc' => 'Task status'],
            ['title' => 'Recruitment Report', 'route' => 'reports.recruitment', 'icon' => 'fa-user-plus', 'color' => 'pink', 'desc' => 'Recruitment data'],
            ['title' => 'Trainings Report', 'route' => 'reports.trainings', 'icon' => 'fa-graduation-cap', 'color' => 'orange', 'desc' => 'Training records'],
            ['title' => 'Documents Report', 'route' => 'reports.documents', 'icon' => 'fa-file-lines', 'color' => 'slate', 'desc' => 'Documents data'],
        ];
        return view('reports.index', compact('stats', 'reports'));
    }

    public function employees(Request $request)
    {
        $query = \App\Models\Employee::query();
        if ($request->filled('department')) $query->where('department_id', $request->department);
        if ($request->filled('status')) $query->where('employment_status', $request->status);
        if ($request->filled('region')) $query->where('region', $request->region);
        $employees = $query->paginate(50);
        $total = $employees->total();
        $active = \App\Models\Employee::where('employment_status', 'active')->count();
        $inactive = \App\Models\Employee::where('employment_status', 'inactive')->count();
        $onLeave = \App\Models\Employee::where('employment_status', 'on_leave')->count();
        return view('reports.employees', compact('employees', 'total', 'active', 'inactive', 'onLeave'));
    }

    public function attendance(Request $request)
    {
        $records = $this->has('attendances') ? DB::table('attendances')->orderByDesc('created_at')->paginate(50) : collect();
        $total = $this->count('attendances');
        $present = $this->countWhere('attendances', 'status', 'present');
        $absent = $this->countWhere('attendances', 'status', 'absent');
        $late = $this->countWhere('attendances', 'status', 'late');
        return view('reports.attendance', compact('records', 'total', 'present', 'absent', 'late'));
    }

    public function leaves(Request $request)
    {
        $records = $this->has('leave_requests') ? DB::table('leave_requests')->orderByDesc('created_at')->paginate(50) : collect();
        $total = $this->count('leave_requests');
        $pending = $this->countWhere('leave_requests', 'status', 'pending');
        $approved = $this->countWhere('leave_requests', 'status', 'approved');
        $rejected = $this->countWhere('leave_requests', 'status', 'rejected');
        return view('reports.leaves', compact('records', 'total', 'pending', 'approved', 'rejected'));
    }

    public function payroll(Request $request)
    {
        $payslips = $this->has('payslips') ? DB::table('payslips')->orderByDesc('created_at')->paginate(50) : collect();
        $total = $this->count('payslips');
        $gross = $this->has('payslips') ? (int) DB::table('payslips')->sum('gross_salary') : 0;
        $deductions = $this->has('payslips') ? (int) DB::table('payslips')->sum('total_deductions') : 0;
        $net = $this->has('payslips') ? (int) DB::table('payslips')->sum('net_salary') : 0;
        return view('reports.payroll', compact('payslips', 'total', 'gross', 'deductions', 'net'));
    }

    public function paymentVouchers(Request $request)
    {
        $vouchers = $this->has('payment_vouchers') ? DB::table('payment_vouchers')->orderByDesc('created_at')->paginate(50) : collect();
        $total = $this->count('payment_vouchers');
        $pending = $this->countWhere('payment_vouchers', 'status', 'pending');
        $approved = $this->countWhere('payment_vouchers', 'status', 'approved');
        $paid = $this->countWhere('payment_vouchers', 'status', 'paid');
        $totalAmount = $this->has('payment_vouchers') && Schema::hasColumn('payment_vouchers', 'amount') ? (int) DB::table('payment_vouchers')->sum('amount') : 0;
        return view('reports.payment-vouchers', compact('vouchers', 'total', 'pending', 'approved', 'paid', 'totalAmount') + ['total_amount' => $totalAmount]);
    }

    public function receipts(Request $request)
    {
        $receipts = $this->has('receipts') ? DB::table('receipts')->orderByDesc('created_at')->paginate(50) : collect();
        $total = $this->count('receipts');
        $confirmed = $this->countWhere('receipts', 'status', 'confirmed');
        $totalAmount = $this->has('receipts') && Schema::hasColumn('receipts', 'amount') ? (int) DB::table('receipts')->sum('amount') : 0;
        return view('reports.receipts', ['receipts' => $receipts, 'total' => $total, 'confirmed' => $confirmed, 'total_amount' => $totalAmount]);
    }

    public function budgets(Request $request)
    {
        $budgets = $this->has('budgets') ? DB::table('budgets')->orderByDesc('created_at')->paginate(50) : collect();
        $total = $this->count('budgets');
        $allocated = 0; $spent = 0; $remaining = 0;
        if ($this->has('budgets')) {
            foreach (['allocated_amount','allocated','amount','total_amount'] as $c) {
                if (Schema::hasColumn('budgets', $c)) { $allocated = (int) DB::table('budgets')->sum($c); break; }
            }
            foreach (['spent_amount','spent'] as $c) {
                if (Schema::hasColumn('budgets', $c)) { $spent = (int) DB::table('budgets')->sum($c); break; }
            }
            $remaining = $allocated - $spent;
        }
        return view('reports.budgets', compact('budgets', 'total', 'allocated', 'spent', 'remaining'));
    }

    public function projects(Request $request)
    {
        $records = $this->has('projects') ? DB::table('projects')->orderByDesc('created_at')->paginate(50) : collect();
        $total = $this->count('projects');
        $active = $this->countWhere('projects', 'status', 'active');
        $completed = $this->countWhere('projects', 'status', 'completed');
        $pending = $this->countWhere('projects', 'status', 'pending');
        return view('reports.projects', compact('records', 'total', 'active', 'completed', 'pending'));
    }

    public function tasks(Request $request)
    {
        $records = $this->has('tasks') ? DB::table('tasks')->orderByDesc('created_at')->paginate(50) : collect();
        $total = $this->count('tasks');
        $pending = $this->countWhere('tasks', 'status', 'pending');
        $inProgress = $this->countWhere('tasks', 'status', 'in_progress');
        $completed = $this->countWhere('tasks', 'status', 'completed');
        return view('reports.tasks', compact('records', 'total', 'pending', 'inProgress', 'completed') + ['in_progress' => $inProgress]);
    }

    public function recruitment(Request $request)
    {
        $jobs = $this->has('recruitments') ? DB::table('recruitments')->orderByDesc('created_at')->paginate(50) : collect();
        $total_jobs = $this->count('recruitments');
        $open_jobs = $this->countWhere('recruitments', 'status', 'open');
        $applications = 0; $hired = 0;
        return view('reports.recruitment', compact('jobs', 'total_jobs', 'open_jobs', 'applications', 'hired'));
    }

    public function trainings(Request $request)
    {
        $trainings = $this->has('trainings') ? DB::table('trainings')->orderByDesc('created_at')->paginate(50) : collect();
        $total = $this->count('trainings');
        $planned = $this->countWhere('trainings', 'status', 'planned');
        $ongoing = $this->countWhere('trainings', 'status', 'ongoing');
        $completed = $this->countWhere('trainings', 'status', 'completed');
        $enrolled = 0;
        return view('reports.trainings', compact('trainings', 'total', 'planned', 'ongoing', 'completed', 'enrolled'));
    }

    public function performance(Request $request)
    {
        $reviews = $this->has('performance_reviews') ? DB::table('performance_reviews')->orderByDesc('created_at')->paginate(50) : collect();
        $total = $this->count('performance_reviews');
        $avg_rating = 0;
        if ($this->has('performance_reviews') && Schema::hasColumn('performance_reviews', 'overall_rating')) {
            $avg_rating = (float) DB::table('performance_reviews')->avg('overall_rating');
        }
        $draft = $this->countWhere('performance_reviews', 'status', 'draft');
        $approved = $this->countWhere('performance_reviews', 'status', 'approved');
        return view('reports.performance', compact('reviews', 'total', 'avg_rating', 'draft', 'approved'));
    }

    public function documents(Request $request)
    {
        $documents = $this->has('documents') ? DB::table('documents')->orderByDesc('created_at')->paginate(50) : collect();
        $total = $this->count('documents');
        $active = $this->countWhere('documents', 'status', 'active');
        $draft = $this->countWhere('documents', 'status', 'draft');
        $expiring = 0;
        return view('reports.documents', compact('documents', 'total', 'active', 'draft', 'expiring'));
    }

    private function has(string $t): bool { return Schema::hasTable($t); }
    private function count(string $t): int { return $this->has($t) ? DB::table($t)->count() : 0; }
    private function countWhere(string $t, string $col, $val): int {
        return ($this->has($t) && Schema::hasColumn($t, $col)) ? DB::table($t)->where($col, $val)->count() : 0;
    }
    private function allStats(): array {
        return [
            'employees' => $this->count('employees'),
            'departments' => $this->count('departments'),
            'positions' => $this->count('positions'),
            'documents' => $this->count('documents'),
            'projects' => $this->count('projects'),
            'tasks' => $this->count('tasks'),
            'budgets' => $this->count('budgets'),
            'receipts' => $this->count('receipts'),
            'payslips' => $this->count('payslips'),
            'vouchers' => $this->count('payment_vouchers'),
            'attendance' => $this->count('attendances'),
            'leaves' => $this->count('leave_requests'),
            'recruitment' => $this->count('recruitments'),
            'trainings' => $this->count('trainings'),
            'performance' => $this->count('performance_reviews'),
            'users' => $this->count('users'),
        ];
    }
}
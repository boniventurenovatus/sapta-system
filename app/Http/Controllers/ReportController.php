<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    // ============================================================
    // INDEX
    // ============================================================
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

    // ============================================================
    // EMPLOYEES
    // ============================================================
    public function employees(Request $request)
    {
        $query = \App\Models\Employee::query();
        if ($request->filled('department')) $query->where('department_id', $request->department);
        if ($request->filled('status')) $query->where('employment_status', $request->status);
        $employees = $query->paginate(50);
        $stats = $this->allStats();
        return view('reports.employees', compact('employees', 'stats'));
    }

    // ============================================================
    // ATTENDANCE
    // ============================================================
    public function attendance(Request $request)
    {
        $records = Schema::hasTable('attendances')
            ? DB::table('attendances')->orderByDesc('created_at')->paginate(50)
            : collect();
        $stats = $this->allStats();
        return view('reports.attendance', compact('records', 'stats'));
    }

    // ============================================================
    // LEAVES
    // ============================================================
    public function leaves(Request $request)
    {
        $records = Schema::hasTable('leave_requests')
            ? DB::table('leave_requests')->orderByDesc('created_at')->paginate(50)
            : collect();
        $stats = $this->allStats();
        return view('reports.leaves', compact('records', 'stats'));
    }

    // ============================================================
    // PAYROLL
    // ============================================================
    public function payroll(Request $request)
    {
        $records = Schema::hasTable('payslips')
            ? DB::table('payslips')->orderByDesc('created_at')->paginate(50)
            : collect();
        $stats = $this->allStats();
        return view('reports.payroll', compact('records', 'stats'));
    }

    // ============================================================
    // PAYMENT VOUCHERS
    // ============================================================
    public function paymentVouchers(Request $request)
    {
        $records = Schema::hasTable('payment_vouchers')
            ? DB::table('payment_vouchers')->orderByDesc('created_at')->paginate(50)
            : collect();
        $stats = $this->allStats();
        return view('reports.payment-vouchers', compact('records', 'stats'));
    }

    // ============================================================
    // RECEIPTS
    // ============================================================
    public function receipts(Request $request)
    {
        $records = Schema::hasTable('receipts')
            ? DB::table('receipts')->orderByDesc('created_at')->paginate(50)
            : collect();
        $stats = $this->allStats();
        return view('reports.receipts', compact('records', 'stats'));
    }

    // ============================================================
    // BUDGETS
    // ============================================================
    public function budgets(Request $request)
    {
        $records = Schema::hasTable('budgets')
            ? DB::table('budgets')->orderByDesc('created_at')->paginate(50)
            : collect();
        $stats = $this->allStats();
        return view('reports.budgets', compact('records', 'stats'));
    }

    // ============================================================
    // PROJECTS
    // ============================================================
    public function projects(Request $request)
    {
        $records = Schema::hasTable('projects')
            ? DB::table('projects')->orderByDesc('created_at')->paginate(50)
            : collect();
        $stats = $this->allStats();
        return view('reports.projects', compact('records', 'stats'));
    }

    // ============================================================
    // TASKS
    // ============================================================
    public function tasks(Request $request)
    {
        $records = Schema::hasTable('tasks')
            ? DB::table('tasks')->orderByDesc('created_at')->paginate(50)
            : collect();
        $stats = $this->allStats();
        return view('reports.tasks', compact('records', 'stats'));
    }

    // ============================================================
    // RECRUITMENT
    // ============================================================
    public function recruitment(Request $request)
    {
        $records = Schema::hasTable('recruitments')
            ? DB::table('recruitments')->orderByDesc('created_at')->paginate(50)
            : collect();
        $stats = $this->allStats();
        return view('reports.recruitment', compact('records', 'stats'));
    }

    // ============================================================
    // TRAININGS
    // ============================================================
    public function trainings(Request $request)
    {
        $records = Schema::hasTable('trainings')
            ? DB::table('trainings')->orderByDesc('created_at')->paginate(50)
            : collect();
        $stats = $this->allStats();
        return view('reports.trainings', compact('records', 'stats'));
    }

    // ============================================================
    // PERFORMANCE
    // ============================================================
    public function performance(Request $request)
    {
        $records = Schema::hasTable('performance_reviews')
            ? DB::table('performance_reviews')->orderByDesc('created_at')->paginate(50)
            : collect();
        $stats = $this->allStats();
        return view('reports.performance', compact('records', 'stats'));
    }

    // ============================================================
    // DOCUMENTS
    // ============================================================
    public function documents(Request $request)
    {
        $records = Schema::hasTable('documents')
            ? DB::table('documents')->orderByDesc('created_at')->paginate(50)
            : collect();
        $stats = $this->allStats();
        return view('reports.documents', compact('records', 'stats'));
    }

    // ============================================================
    // EXPORTS
    // ============================================================
    public function exportEmployees() { return $this->csv('employees'); }
    public function exportPayroll() { return $this->csv('payslips'); }
    public function exportVouchers() { return $this->csv('payment_vouchers'); }
    public function exportReceipts() { return $this->csv('receipts'); }
    public function exportBudgets() { return $this->csv('budgets'); }
    public function exportTrainings() { return $this->csv('trainings'); }
    public function exportRecruitment() { return $this->csv('recruitments'); }
    public function exportPerformance() { return $this->csv('performance_reviews'); }
    public function exportDocuments() { return $this->csv('documents'); }

    private function csv(string $table)
    {
        if (!Schema::hasTable($table)) {
            return back()->with('error', "Table $table haipo.");
        }
        $rows = DB::table($table)->limit(1000)->get();
        $filename = $table . '_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        $callback = function() use ($rows) {
            $out = fopen('php://output', 'w');
            if ($rows->isNotEmpty()) {
                fputcsv($out, array_keys((array) $rows->first()));
                foreach ($rows as $row) fputcsv($out, (array) $row);
            }
            fclose($out);
        };
        return response()->stream($callback, 200, $headers);
    }

    // ============================================================
    // STATS HELPER
    // ============================================================
    private function allStats(): array
    {
        return [
            'employees' => Schema::hasTable('employees') ? DB::table('employees')->count() : 0,
            'departments' => Schema::hasTable('departments') ? DB::table('departments')->count() : 0,
            'positions' => Schema::hasTable('positions') ? DB::table('positions')->count() : 0,
            'documents' => Schema::hasTable('documents') ? DB::table('documents')->count() : 0,
            'projects' => Schema::hasTable('projects') ? DB::table('projects')->count() : 0,
            'tasks' => Schema::hasTable('tasks') ? DB::table('tasks')->count() : 0,
            'budgets' => Schema::hasTable('budgets') ? DB::table('budgets')->count() : 0,
            'receipts' => Schema::hasTable('receipts') ? DB::table('receipts')->count() : 0,
            'payslips' => Schema::hasTable('payslips') ? DB::table('payslips')->count() : 0,
            'vouchers' => Schema::hasTable('payment_vouchers') ? DB::table('payment_vouchers')->count() : 0,
            'attendance' => Schema::hasTable('attendances') ? DB::table('attendances')->count() : 0,
            'leaves' => Schema::hasTable('leave_requests') ? DB::table('leave_requests')->count() : 0,
            'recruitment' => Schema::hasTable('recruitments') ? DB::table('recruitments')->count() : 0,
            'trainings' => Schema::hasTable('trainings') ? DB::table('trainings')->count() : 0,
            'performance' => Schema::hasTable('performance_reviews') ? DB::table('performance_reviews')->count() : 0,
            'users' => Schema::hasTable('users') ? DB::table('users')->count() : 0,
        ];
    }
}
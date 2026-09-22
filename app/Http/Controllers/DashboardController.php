<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $roles = $user->roles()->pluck('code')->toArray();

        if (in_array('super_admin', $roles) || in_array('admin', $roles)) return redirect()->route('dashboard.admin');
        if (in_array('ceo', $roles) || in_array('bod', $roles) || in_array('director', $roles)) return redirect()->route('dashboard.director');
        if (in_array('hr_manager', $roles) || in_array('hr_officer', $roles) || in_array('admin_director', $roles)) return redirect()->route('dashboard.hr');
        if (in_array('finance_manager', $roles) || in_array('accountant', $roles)) return redirect()->route('dashboard.finance');
        if (in_array('ict_manager', $roles)) return redirect()->route('dashboard.ict');
        if (in_array('meal_manager', $roles) || in_array('meal_officer', $roles)) return redirect()->route('dashboard.meal');
        if (in_array('manager', $roles) || in_array('project_manager', $roles) || in_array('project_officer', $roles) || in_array('program_director', $roles)) return redirect()->route('dashboard.manager');

        return redirect()->route('dashboard.staff');
    }

    public function admin()
    {
        $kpis = [
            'total_users' => DB::table('users')->count(),
            'total_roles' => DB::table('roles')->count(),
            'total_permissions' => Schema::hasTable('permissions') ? DB::table('permissions')->count() : 0,
            'total_departments' => DB::table('departments')->count(),
            'total_positions' => DB::table('positions')->count(),
            'total_audit_logs' => DB::table('audit_logs')->count(),
        ];

        $usersByRole = DB::table('roles')
            ->leftJoin('user_roles', 'roles.id', '=', 'user_roles.role_id')
            ->select('roles.name', DB::raw('COUNT(user_roles.user_id) as total'))
            ->groupBy('roles.id', 'roles.name')->get();

        $documentsByCategory = collect();
        if (Schema::hasTable('documents') && Schema::hasColumn('documents', 'category')) {
            $documentsByCategory = DB::table('documents')
                ->select('category as cat', DB::raw('COUNT(*) as total'))
                ->groupBy('category')->get();
        }

        $charts = [
            'users_by_role' => [
                'labels' => $usersByRole->pluck('name')->toArray(),
                'datasets' => [[
                    'label' => 'Users',
                    'data' => $usersByRole->pluck('total')->map(fn($v) => (int)$v)->toArray(),
                    'backgroundColor' => ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444','#06b6d4','#ec4899','#6366f1'],
                ]],
            ],
            'user_activity' => $this->emptyActivity(),
            'documents_by_category' => [
                'labels' => $documentsByCategory->pluck('cat')->map(fn($v) => $v ?: 'Nyingine')->toArray() ?: ['Hakuna'],
                'datasets' => [[
                    'label' => 'Documents',
                    'data' => $documentsByCategory->pluck('total')->map(fn($v) => (int)$v)->toArray() ?: [0],
                    'backgroundColor' => ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444'],
                ]],
            ],
        ];

        $recent_users = \App\Models\User::orderByDesc('created_at')->limit(5)->get();
        $recent_logs = Schema::hasTable('audit_logs') ? DB::table('audit_logs')->orderByDesc('created_at')->limit(10)->get() : collect();

        return view('dashboard.admin', compact('kpis','charts','recent_users','recent_logs'));
    }

    public function director()
    {
        $kpis = [
            'total_employees' => DB::table('employees')->count(),
            'active_projects' => DB::table('projects')->where('status', 'active')->count(),
            'pending_approvals' => DB::table('leave_requests')->where('status', 'pending')->count(),
            'total_budget' => (int) DB::table('budgets')->sum('allocated_amount'),
            'total_tasks' => DB::table('tasks')->count(),
            'completed_tasks' => DB::table('tasks')->where('status', 'completed')->count(),
        ];

        $charts = [
            'projects_by_status' => [
                'labels' => ['Active','Completed','Pending'],
                'datasets' => [[
                    'label' => 'Projects',
                    'data' => [DB::table('projects')->where('status', 'active')->count(), DB::table('projects')->where('status', 'completed')->count(), 0],
                    'backgroundColor' => ['#10b981','#3b82f6','#f59e0b'],
                ]],
            ],
        ];

        $recent_activities = collect();
        return view('dashboard.director', compact('kpis','charts','recent_activities'));
    }

    public function executive()
    {
        $kpis = [
            'total_employees' => DB::table('employees')->count(),
            'total_projects' => DB::table('projects')->count(),
            'total_revenue' => 0,
            'total_tasks' => DB::table('tasks')->count(),
        ];

        $charts = [
            'revenue_trend' => [
                'labels' => ['Jan','Feb','Mar','Apr','May','Jun'],
                'datasets' => [[
                    'label' => 'Revenue',
                    'data' => [0,0,0,0,0,0],
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16,185,129,0.1)',
                ]],
            ],
        ];

        $recent_activities = collect();
        $top_projects = Schema::hasTable('projects') ? DB::table('projects')->limit(5)->get() : collect();

        return view('dashboard.executive', compact('kpis','charts','recent_activities','top_projects'));
    }

    public function hr()
    {
        $kpis = [
            'total_employees' => DB::table('employees')->count(),
            'active_employees' => DB::table('employees')->where('employment_status', 'active')->count(),
            'pending_leaves' => DB::table('leave_requests')->where('status', 'pending')->count(),
            'total_departments' => DB::table('departments')->count(),
        ];

        $charts = [
            'employees_by_department' => [
                'labels' => ['HR','Finance','Operations','ICT'],
                'datasets' => [[
                    'label' => 'Employees',
                    'data' => [0,0,0,0],
                    'backgroundColor' => ['#3b82f6','#8b5cf6','#10b981','#f59e0b'],
                ]],
            ],
        ];

        $pending_leaves_list = collect();
        return view('dashboard.hr', compact('kpis','charts','pending_leaves_list'));
    }

    public function finance()
    {
        $kpis = [
            'total_income' => 0,
            'total_expenses' => 0,
            'pending_payments' => DB::table('payment_vouchers')->where('status', 'pending')->count(),
            'total_budget' => (int) DB::table('budgets')->sum('allocated_amount'),
        ];

        $charts = [
            'income_expense' => [
                'labels' => ['Jan','Feb','Mar','Apr','May','Jun'],
                'datasets' => [
                    ['label' => 'Income', 'data' => [0,0,0,0,0,0], 'backgroundColor' => '#10b981'],
                    ['label' => 'Expenses', 'data' => [0,0,0,0,0,0], 'backgroundColor' => '#ef4444'],
                ],
            ],
        ];

        $recent_vouchers = Schema::hasTable('payment_vouchers') ? DB::table('payment_vouchers')->orderByDesc('created_at')->limit(5)->get() : collect();
        return view('dashboard.finance', compact('kpis','charts','recent_vouchers'));
    }

    public function manager()
    {
        $kpis = [
            'total_tasks' => DB::table('tasks')->count(),
            'completed_tasks' => DB::table('tasks')->where('status', 'completed')->count(),
            'pending_tasks' => DB::table('tasks')->where('status', 'pending')->count(),
            'team_members' => DB::table('users')->count(),
        ];

        $charts = [
            'tasks_by_status' => [
                'labels' => ['Completed','Pending','In Progress'],
                'datasets' => [[
                    'label' => 'Tasks',
                    'data' => [DB::table('tasks')->where('status', 'completed')->count(), DB::table('tasks')->where('status', 'pending')->count(), 0],
                    'backgroundColor' => ['#10b981','#f59e0b','#3b82f6'],
                ]],
            ],
        ];

        $recent_tasks = Schema::hasTable('tasks') ? DB::table('tasks')->orderByDesc('created_at')->limit(5)->get() : collect();
        return view('dashboard.manager', compact('kpis','charts','recent_tasks'));
    }

    public function staff()
    {
        $kpis = [
            'my_tasks' => 0,
            'pending_tasks' => DB::table('tasks')->where('status', 'pending')->count(),
            'completed_tasks' => DB::table('tasks')->where('status', 'completed')->count(),
            'attendance_rate' => 100,
        ];

        $charts = ['my_activity' => $this->emptyActivity()];

        $recent_tasks = Schema::hasTable('tasks') ? DB::table('tasks')->orderByDesc('created_at')->limit(5)->get() : collect();
        return view('dashboard.staff', compact('kpis','charts','recent_tasks'));
    }

    public function ict()
    {
        $kpis = [
            'total_users' => DB::table('users')->count(),
            'active_sessions' => 0,
            'system_health' => 95,
            'total_logs' => DB::table('audit_logs')->count(),
        ];

        $charts = ['system_usage' => $this->emptyActivity()];
        $recent_users = \App\Models\User::orderByDesc('created_at')->limit(5)->get();
        return view('dashboard.ict', compact('kpis','charts','recent_users'));
    }

    public function meal()
    {
        $kpis = [
            'total_reports' => 0,
            'pending_reports' => 0,
            'completed_reports' => 0,
            'total_projects' => DB::table('projects')->count(),
        ];

        $charts = ['reports_overview' => $this->emptyActivity()];
        $recent_reports = collect();
        return view('dashboard.meal', compact('kpis','charts','recent_reports'));
    }

    public function program()
    {
        $kpis = [
            'total_projects' => DB::table('projects')->count(),
            'active_projects' => DB::table('projects')->where('status', 'active')->count(),
            'completed_projects' => DB::table('projects')->where('status', 'completed')->count(),
            'total_budget' => (int) DB::table('budgets')->sum('allocated_amount'),
        ];

        $charts = [
            'projects_by_status' => [
                'labels' => ['Active','Completed','Pending'],
                'datasets' => [[
                    'label' => 'Projects',
                    'data' => [DB::table('projects')->where('status', 'active')->count(), DB::table('projects')->where('status', 'completed')->count(), 0],
                    'backgroundColor' => ['#10b981','#3b82f6','#f59e0b'],
                ]],
            ],
        ];

        $recent_projects = Schema::hasTable('projects') ? DB::table('projects')->orderByDesc('created_at')->limit(5)->get() : collect();
        return view('dashboard.program', compact('kpis','charts','recent_projects'));
    }

    private function emptyActivity(): array
    {
        return [
            'labels' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            'datasets' => [[
                'label' => 'Activity',
                'data' => array_fill(0, 12, 0),
                'borderColor' => '#3b82f6',
                'backgroundColor' => 'rgba(59,130,246,0.1)',
            ]],
        ];
    }
}
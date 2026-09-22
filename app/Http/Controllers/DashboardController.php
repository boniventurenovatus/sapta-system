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
            'total_departments' => Schema::hasTable('departments') ? DB::table('departments')->count() : 0,
            'total_positions' => Schema::hasTable('positions') ? DB::table('positions')->count() : 0,
            'total_audit_logs' => Schema::hasTable('audit_logs') ? DB::table('audit_logs')->count() : 0,
        ];

        $charts = [
            'users_by_role' => $this->chartUsersByRole(),
            'user_activity' => $this->chartEmptyActivity(),
            'documents_by_category' => $this->chartDocumentsByCategory(),
        ];

        $recent_users = \App\Models\User::orderByDesc('created_at')->limit(5)->get();
        $recent_logs = Schema::hasTable('audit_logs') ? DB::table('audit_logs')->orderByDesc('created_at')->limit(10)->get() : collect();

        return view('dashboard.admin', compact('kpis','charts','recent_users','recent_logs'));
    }

    public function director()
    {
        $kpis = [
            'total_employees' => DB::table('employees')->count(),
            'active_projects' => Schema::hasTable('projects') ? DB::table('projects')->where('status','active')->count() : 0,
            'pending_approvals' => Schema::hasTable('leave_requests') ? DB::table('leave_requests')->where('status','pending')->count() : 0,
            'total_budget' => $this->getBudgetSum(),
            'total_tasks' => Schema::hasTable('tasks') ? DB::table('tasks')->count() : 0,
            'completed_tasks' => Schema::hasTable('tasks') ? DB::table('tasks')->where('status','completed')->count() : 0,
        ];

        $charts = [
            'projects_by_status' => $this->chartProjectsByStatus(),
            'tasks_by_status' => $this->chartTasksByStatus(),
            'budget_overview' => $this->chartEmptyActivity(),
        ];

        $recent_activities = collect();

        return view('dashboard.director', compact('kpis','charts','recent_activities'));
    }

    public function executive()
    {
        $kpis = [
            'total_employees' => DB::table('employees')->count(),
            'total_projects' => Schema::hasTable('projects') ? DB::table('projects')->count() : 0,
            'total_revenue' => 0,
            'total_tasks' => Schema::hasTable('tasks') ? DB::table('tasks')->count() : 0,
            'active_projects' => Schema::hasTable('projects') ? DB::table('projects')->where('status','active')->count() : 0,
            'total_budget' => $this->getBudgetSum(),
        ];

        $charts = [
            'revenue_trend' => $this->chartEmptyActivity(),
            'projects_by_status' => $this->chartProjectsByStatus(),
            'department_performance' => $this->chartEmptyActivity(),
        ];

        $recent_activities = collect();
        $top_projects = Schema::hasTable('projects') ? DB::table('projects')->limit(5)->get() : collect();

        return view('dashboard.executive', compact('kpis','charts','recent_activities','top_projects'));
    }

    public function hr()
    {
        $kpis = [
            'total_employees' => DB::table('employees')->count(),
            'active_employees' => Schema::hasTable('employees') ? DB::table('employees')->where('employment_status','active')->count() : 0,
            'pending_leaves' => Schema::hasTable('leave_requests') ? DB::table('leave_requests')->where('status','pending')->count() : 0,
            'total_departments' => Schema::hasTable('departments') ? DB::table('departments')->count() : 0,
        ];

        $charts = [
            'employees_by_department' => $this->chartEmptyActivity(),
            'leaves_by_status' => $this->chartEmptyActivity(),
            'attendance_overview' => $this->chartEmptyActivity(),
        ];

        $pending_leaves_list = collect();

        return view('dashboard.hr', compact('kpis','charts','pending_leaves_list'));
    }

    public function finance()
    {
        $kpis = [
            'total_income' => 0,
            'total_expenses' => 0,
            'pending_payments' => Schema::hasTable('payment_vouchers') ? DB::table('payment_vouchers')->where('status','pending')->count() : 0,
            'total_budget' => $this->getBudgetSum(),
        ];

        $charts = [
            'income_expense' => $this->chartEmptyActivity(),
            'budget_utilization' => $this->chartEmptyActivity(),
        ];

        $recent_vouchers = Schema::hasTable('payment_vouchers') ? DB::table('payment_vouchers')->orderByDesc('created_at')->limit(5)->get() : collect();

        return view('dashboard.finance', compact('kpis','charts','recent_vouchers'));
    }

    public function manager()
    {
        $kpis = [
            'total_tasks' => Schema::hasTable('tasks') ? DB::table('tasks')->count() : 0,
            'completed_tasks' => Schema::hasTable('tasks') ? DB::table('tasks')->where('status','completed')->count() : 0,
            'pending_tasks' => Schema::hasTable('tasks') ? DB::table('tasks')->where('status','pending')->count() : 0,
            'team_members' => DB::table('users')->count(),
        ];

        $charts = [
            'tasks_by_status' => $this->chartTasksByStatus(),
            'team_performance' => $this->chartEmptyActivity(),
        ];

        $recent_tasks = Schema::hasTable('tasks') ? DB::table('tasks')->orderByDesc('created_at')->limit(5)->get() : collect();

        return view('dashboard.manager', compact('kpis','charts','recent_tasks'));
    }

    public function staff()
    {
        $kpis = [
            'my_tasks' => 0,
            'pending_tasks' => Schema::hasTable('tasks') ? DB::table('tasks')->where('status','pending')->count() : 0,
            'completed_tasks' => Schema::hasTable('tasks') ? DB::table('tasks')->where('status','completed')->count() : 0,
            'attendance_rate' => 100,
        ];

        $charts = [
            'my_activity' => $this->chartEmptyActivity(),
            'tasks_by_status' => $this->chartTasksByStatus(),
        ];

        $recent_tasks = Schema::hasTable('tasks') ? DB::table('tasks')->orderByDesc('created_at')->limit(5)->get() : collect();

        return view('dashboard.staff', compact('kpis','charts','recent_tasks'));
    }

    public function ict()
    {
        $kpis = [
            'total_users' => DB::table('users')->count(),
            'active_sessions' => 0,
            'system_health' => 95,
            'total_logs' => Schema::hasTable('audit_logs') ? DB::table('audit_logs')->count() : 0,
        ];

        $charts = [
            'system_usage' => $this->chartEmptyActivity(),
            'user_activity' => $this->chartEmptyActivity(),
        ];

        $recent_users = \App\Models\User::orderByDesc('created_at')->limit(5)->get();

        return view('dashboard.ict', compact('kpis','charts','recent_users'));
    }

    public function meal()
    {
        $kpis = [
            'total_reports' => 0,
            'pending_reports' => 0,
            'completed_reports' => 0,
            'total_projects' => Schema::hasTable('projects') ? DB::table('projects')->count() : 0,
        ];

        $charts = [
            'reports_overview' => $this->chartEmptyActivity(),
            'projects_by_status' => $this->chartProjectsByStatus(),
        ];

        $recent_reports = collect();

        return view('dashboard.meal', compact('kpis','charts','recent_reports'));
    }

    public function program()
    {
        $kpis = [
            'total_projects' => Schema::hasTable('projects') ? DB::table('projects')->count() : 0,
            'active_projects' => Schema::hasTable('projects') ? DB::table('projects')->where('status','active')->count() : 0,
            'completed_projects' => Schema::hasTable('projects') ? DB::table('projects')->where('status','completed')->count() : 0,
            'total_budget' => $this->getBudgetSum(),
        ];

        $charts = [
            'projects_by_status' => $this->chartProjectsByStatus(),
            'budget_overview' => $this->chartEmptyActivity(),
        ];

        $recent_projects = Schema::hasTable('projects') ? DB::table('projects')->orderByDesc('created_at')->limit(5)->get() : collect();

        return view('dashboard.program', compact('kpis','charts','recent_projects'));
    }

    // ============================================================
    // HELPERS
    // ============================================================
    private function getBudgetSum(): int
    {
        if (!Schema::hasTable('budgets')) return 0;
        foreach (['amount','budget_amount','total_amount','total','allocated_amount','allocated','budget'] as $col) {
            if (Schema::hasColumn('budgets', $col)) {
                return (int) DB::table('budgets')->sum($col);
            }
        }
        return 0;
    }

    private function chartEmptyActivity(): array
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

    private function chartUsersByRole(): array
    {
        $rows = DB::table('roles')
            ->leftJoin('user_roles', 'roles.id', '=', 'user_roles.role_id')
            ->select('roles.name', DB::raw('COUNT(user_roles.user_id) as total'))
            ->groupBy('roles.id', 'roles.name')
            ->get();

        return [
            'labels' => $rows->pluck('name')->toArray(),
            'datasets' => [[
                'label' => 'Users',
                'data' => $rows->pluck('total')->map(fn($v) => (int)$v)->toArray(),
                'backgroundColor' => ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444','#06b6d4','#ec4899','#6366f1'],
            ]],
        ];
    }

    private function chartDocumentsByCategory(): array
    {
        if (!Schema::hasTable('documents')) {
            return ['labels' => ['Hakuna'], 'datasets' => [['label' => 'Documents', 'data' => [0], 'backgroundColor' => ['#3b82f6']]]];
        }
        $col = null;
        foreach (['category','document_category','type'] as $c) {
            if (Schema::hasColumn('documents', $c)) { $col = $c; break; }
        }
        if (!$col) {
            return ['labels' => ['Hakuna'], 'datasets' => [['label' => 'Documents', 'data' => [0], 'backgroundColor' => ['#3b82f6']]]];
        }
        $rows = DB::table('documents')->select($col.' as cat', DB::raw('COUNT(*) as total'))->groupBy($col)->get();
        return [
            'labels' => $rows->pluck('cat')->map(fn($v) => $v ?: 'Nyingine')->toArray() ?: ['Hakuna'],
            'datasets' => [[
                'label' => 'Documents',
                'data' => $rows->pluck('total')->map(fn($v) => (int)$v)->toArray() ?: [0],
                'backgroundColor' => ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444'],
            ]],
        ];
    }

    private function chartProjectsByStatus(): array
    {
        if (!Schema::hasTable('projects')) {
            return ['labels' => ['Hakuna'], 'datasets' => [['label' => 'Projects', 'data' => [0], 'backgroundColor' => ['#3b82f6']]]];
        }
        $col = Schema::hasColumn('projects','status') ? 'status' : null;
        if (!$col) {
            return ['labels' => ['Hakuna'], 'datasets' => [['label' => 'Projects', 'data' => [0], 'backgroundColor' => ['#3b82f6']]]];
        }
        $rows = DB::table('projects')->select($col.' as st', DB::raw('COUNT(*) as total'))->groupBy($col)->get();
        return [
            'labels' => $rows->pluck('st')->map(fn($v) => $v ?: 'Nyingine')->toArray() ?: ['Hakuna'],
            'datasets' => [[
                'label' => 'Projects',
                'data' => $rows->pluck('total')->map(fn($v) => (int)$v)->toArray() ?: [0],
                'backgroundColor' => ['#10b981','#3b82f6','#f59e0b','#ef4444','#8b5cf6'],
            ]],
        ];
    }

    private function chartTasksByStatus(): array
    {
        if (!Schema::hasTable('tasks')) {
            return ['labels' => ['Hakuna'], 'datasets' => [['label' => 'Tasks', 'data' => [0], 'backgroundColor' => ['#3b82f6']]]];
        }
        $col = Schema::hasColumn('tasks','status') ? 'status' : null;
        if (!$col) {
            return ['labels' => ['Hakuna'], 'datasets' => [['label' => 'Tasks', 'data' => [0], 'backgroundColor' => ['#3b82f6']]]];
        }
        $rows = DB::table('tasks')->select($col.' as st', DB::raw('COUNT(*) as total'))->groupBy($col)->get();
        return [
            'labels' => $rows->pluck('st')->map(fn($v) => $v ?: 'Nyingine')->toArray() ?: ['Hakuna'],
            'datasets' => [[
                'label' => 'Tasks',
                'data' => $rows->pluck('total')->map(fn($v) => (int)$v)->toArray() ?: [0],
                'backgroundColor' => ['#10b981','#f59e0b','#3b82f6','#ef4444','#8b5cf6'],
            ]],
        ];
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $roles = auth()->user()->roles()->pluck('code')->toArray();
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
        return view('dashboard.admin', [
            'kpis' => $this->kpisAdmin(),
            'charts' => $this->allCharts(),
            'recent_users' => \App\Models\User::orderByDesc('created_at')->limit(5)->get(),
            'recent_logs' => $this->has('audit_logs') ? DB::table('audit_logs')->orderByDesc('created_at')->limit(10)->get() : collect(),
        ]);
    }

    public function director()
    {
        return view('dashboard.director', [
            'kpis' => $this->kpisDirector(),
            'charts' => $this->allCharts(),
            'recent_activities' => collect(),
        ]);
    }

    public function executive()
    {
        return view('dashboard.executive', [
            'kpis' => $this->kpisExecutive(),
            'charts' => $this->allCharts(),
            'recent_activities' => collect(),
            'top_projects' => $this->has('projects') ? DB::table('projects')->limit(5)->get() : collect(),
        ]);
    }

    public function hr()
    {
        return view('dashboard.hr', [
            'kpis' => $this->kpisHr(),
            'charts' => $this->allCharts(),
            'pending_leaves_list' => collect(),
        ]);
    }

    public function finance()
    {
        return view('dashboard.finance', [
            'kpis' => $this->kpisFinance(),
            'charts' => $this->allCharts(),
            'recent_vouchers' => $this->has('payment_vouchers') ? DB::table('payment_vouchers')->orderByDesc('created_at')->limit(5)->get() : collect(),
        ]);
    }

    public function manager()
    {
        return view('dashboard.manager', [
            'kpis' => $this->kpisManager(),
            'charts' => $this->allCharts(),
            'recent_tasks' => $this->has('tasks') ? DB::table('tasks')->orderByDesc('created_at')->limit(5)->get() : collect(),
        ]);
    }

    public function staff()
    {
        return view('dashboard.staff', [
            'kpis' => $this->kpisStaff(),
            'charts' => $this->allCharts(),
            'recent_tasks' => $this->has('tasks') ? DB::table('tasks')->orderByDesc('created_at')->limit(5)->get() : collect(),
        ]);
    }

    public function ict()
    {
        return view('dashboard.ict', [
            'kpis' => $this->kpisIct(),
            'charts' => $this->allCharts(),
            'recent_users' => \App\Models\User::orderByDesc('created_at')->limit(5)->get(),
        ]);
    }

    public function meal()
    {
        return view('dashboard.meal', [
            'kpis' => $this->kpisMeal(),
            'charts' => $this->allCharts(),
            'recent_reports' => collect(),
        ]);
    }

    public function program()
    {
        return view('dashboard.program', [
            'kpis' => $this->kpisProgram(),
            'charts' => $this->allCharts(),
            'recent_projects' => $this->has('projects') ? DB::table('projects')->orderByDesc('created_at')->limit(5)->get() : collect(),
        ]);
    }

    // ============================================================
    // KPIs
    // ============================================================
    private function kpisAdmin(): array { return [
        'total_users' => DB::table('users')->count(),
        'total_roles' => DB::table('roles')->count(),
        'total_permissions' => $this->count('permissions'),
        'total_departments' => $this->count('departments'),
            'total_trainings' => $this->has('trainings') ? \DB::table('trainings')->count() : 0,
        'total_positions' => $this->count('positions'),
        'total_audit_logs' => $this->count('audit_logs'),
    ];}

    private function kpisDirector(): array { return [
        'total_employees' => $this->count('employees'),
        'active_projects' => $this->countWhere('projects', 'status', 'active'),
        'pending_approvals' => $this->countWhere('leave_requests', 'status', 'pending'),
        'total_budget' => $this->budgetSum(),
        'total_tasks' => $this->count('tasks'),
        'completed_tasks' => $this->countWhere('tasks', 'status', 'completed'),
    ];}

    private function kpisExecutive(): array { return [
        'total_employees' => $this->count('employees'),
        'total_projects' => $this->count('projects'),
        'total_revenue' => 0,
        'total_tasks' => $this->count('tasks'),
        'active_projects' => $this->countWhere('projects', 'status', 'active'),
        'total_budget' => $this->budgetSum(),
    ];}

    private function kpisHr(): array { return [
        'total_employees' => $this->count('employees'),
        'active_employees' => $this->countWhere('employees', 'employment_status', 'active'),
        'pending_leaves' => $this->countWhere('leave_requests', 'status', 'pending'),
        'total_departments' => $this->count('departments'),
            'total_trainings' => $this->has('trainings') ? \DB::table('trainings')->count() : 0,
    ];}

    private function kpisFinance(): array { return [
        'total_income' => 0,
        'total_expenses' => 0,
        'pending_payments' => $this->countWhere('payment_vouchers', 'status', 'pending'),
        'total_budget' => $this->budgetSum(),
    ];}

    private function kpisManager(): array { return [
        'total_tasks' => $this->count('tasks'),
        'completed_tasks' => $this->countWhere('tasks', 'status', 'completed'),
        'pending_tasks' => $this->countWhere('tasks', 'status', 'pending'),
        'team_members' => DB::table('users')->count(),
    ];}

    private function kpisStaff(): array { return [
        'my_tasks' => 0,
        'pending_tasks' => $this->countWhere('tasks', 'status', 'pending'),
        'completed_tasks' => $this->countWhere('tasks', 'status', 'completed'),
        'attendance_rate' => 100,
    ];}

    private function kpisIct(): array { return [
        'total_users' => DB::table('users')->count(),
        'active_sessions' => 0,
        'system_health' => 95,
        'total_logs' => $this->count('audit_logs'),
    ];}

    private function kpisMeal(): array { return [
        'total_reports' => 0,
        'pending_reports' => 0,
        'completed_reports' => 0,
        'total_projects' => $this->count('projects'),
    ];}

    private function kpisProgram(): array { return [
        'total_projects' => $this->count('projects'),
        'active_projects' => $this->countWhere('projects', 'status', 'active'),
        'completed_projects' => $this->countWhere('projects', 'status', 'completed'),
        'total_budget' => $this->budgetSum(),
    ];}

    // ============================================================
    // CHARTS — ZOTE ZINAZOWEZEKANA
    // ============================================================
    private function allCharts(): array
    {
        return [
            'users_by_role' => $this->chartUsersByRole(),
            'user_activity' => $this->chartEmpty('Activity'),
            'documents_by_category' => $this->chartGroupBy('documents', ['category','document_category','type'], 'Documents'),
            'projects_by_status' => $this->chartGroupBy('projects', ['status'], 'Projects'),
            'tasks_by_status' => $this->chartGroupBy('tasks', ['status'], 'Tasks'),
            'employees_by_department' => $this->chartEmployeesByDept(),
            'leaves_by_status' => $this->chartGroupBy('leave_requests', ['status'], 'Leaves'),
            'attendance_overview' => $this->chartEmpty('Attendance'),
            'budget_overview' => $this->chartEmpty('Budget'),
            'budget_utilization' => $this->chartEmpty('Utilization'),
            'income_expense' => $this->chartEmpty('Income vs Expense'),
            'revenue_trend' => $this->chartEmpty('Revenue'),
            'department_performance' => $this->chartEmpty('Performance'),
            'team_performance' => $this->chartEmpty('Team'),
            'system_usage' => $this->chartEmpty('System'),
            'reports_overview' => $this->chartEmpty('Reports'),
            'my_activity' => $this->chartEmpty('Activity'),
            'projects_by_department' => $this->chartEmpty('Projects'),
            'recent_activities' => $this->chartEmpty('Activities'),
        ];
    }

    // ============================================================
    // HELPERS
    // ============================================================
    private function has(string $t): bool { return Schema::hasTable($t); }

    private function count(string $t): int { return $this->has($t) ? DB::table($t)->count() : 0; }

    private function countWhere(string $t, string $col, $val): int {
        return ($this->has($t) && Schema::hasColumn($t, $col)) ? DB::table($t)->where($col, $val)->count() : 0;
    }

    private function budgetSum(): int {
        if (!$this->has('budgets')) return 0;
        foreach (['amount','budget_amount','total_amount','total','allocated_amount','allocated','budget'] as $c) {
            if (Schema::hasColumn('budgets', $c)) return (int) DB::table('budgets')->sum($c);
        }
        return 0;
    }

    private function chartEmpty(string $label): array {
        return [
            'labels' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            'datasets' => [[
                'label' => $label,
                'data' => array_fill(0, 12, 0),
                'borderColor' => '#3b82f6',
                'backgroundColor' => 'rgba(59,130,246,0.1)',
            ]],
        ];
    }

    private function chartUsersByRole(): array {
        $rows = DB::table('roles')
            ->leftJoin('user_roles', 'roles.id', '=', 'user_roles.role_id')
            ->select('roles.name', DB::raw('COUNT(user_roles.user_id) as total'))
            ->groupBy('roles.id', 'roles.name')->get();
        return [
            'labels' => $rows->pluck('name')->toArray() ?: ['Hakuna'],
            'datasets' => [[
                'label' => 'Users',
                'data' => $rows->pluck('total')->map(fn($v) => (int)$v)->toArray() ?: [0],
                'backgroundColor' => ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444','#06b6d4','#ec4899','#6366f1'],
            ]],
        ];
    }

    private function chartGroupBy(string $table, array $candidates, string $label): array {
        if (!$this->has($table)) return $this->chartEmpty($label);
        $col = null;
        foreach ($candidates as $c) if (Schema::hasColumn($table, $c)) { $col = $c; break; }
        if (!$col) return $this->chartEmpty($label);
        $rows = DB::table($table)->select($col.' as k', DB::raw('COUNT(*) as total'))->groupBy($col)->get();
        return [
            'labels' => $rows->pluck('k')->map(fn($v) => $v ?: 'Nyingine')->toArray() ?: ['Hakuna'],
            'datasets' => [[
                'label' => $label,
                'data' => $rows->pluck('total')->map(fn($v) => (int)$v)->toArray() ?: [0],
                'backgroundColor' => ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444','#06b6d4','#ec4899','#6366f1'],
            ]],
        ];
    }

    private function chartEmployeesByDept(): array {
        if (!$this->has('employees')) return $this->chartEmpty('Employees');
        if (!Schema::hasColumn('employees', 'department_id')) return $this->chartEmpty('Employees');
        $rows = DB::table('employees')
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->select('departments.name as dept', DB::raw('COUNT(*) as total'))
            ->groupBy('departments.name')->get();
        return [
            'labels' => $rows->pluck('dept')->map(fn($v) => $v ?: 'Nyingine')->toArray() ?: ['Hakuna'],
            'datasets' => [[
                'label' => 'Employees',
                'data' => $rows->pluck('total')->map(fn($v) => (int)$v)->toArray() ?: [0],
                'backgroundColor' => ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444','#06b6d4','#ec4899','#6366f1'],
            ]],
        ];
    }
}
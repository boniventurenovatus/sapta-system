<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    // ============================================================
    // ROUTING — index
    // ============================================================
    public function index()
    {
        $roles = auth()->user()->roles()->pluck('code')->toArray();
        if (in_array('super_admin', $roles) || in_array('admin', $roles)) return redirect()->route('dashboard.admin');
        if (in_array('ceo', $roles) || in_array('bod', $roles) || in_array('director', $roles)) return redirect()->route('dashboard.director');
        if (in_array('hr_manager', $roles) || in_array('hr_officer', $roles) || in_array('admin_director', $roles)) return redirect()->route('dashboard.hr');
        if (in_array('finance_manager', $roles) || in_array('accountant', $roles)) return redirect()->route('dashboard.finance');
        if (in_array('ict_manager', $roles)) return redirect()->route('dashboard.ict');
        if (in_array('meal_manager', $roles) || in_array('meal_officer', $roles)) return redirect()->route('dashboard.meal');
        if (in_array('program_director', $roles)) return redirect()->route('dashboard.program');
        if (in_array('manager', $roles) || in_array('project_manager', $roles) || in_array('project_officer', $roles)) return redirect()->route('dashboard.manager');
        return redirect()->route('dashboard.staff');
    }

    // ============================================================
    // ADMIN
    // ============================================================
    public function admin()
    {
        return view('dashboard.admin', [
            'kpis' => [
                'total_users' => DB::table('users')->count(),
                'total_roles' => DB::table('roles')->count(),
                'total_permissions' => $this->count('permissions'),
                'total_departments' => $this->count('departments'),
                'total_positions' => $this->count('positions'),
                'total_audit_logs' => $this->count('audit_logs'),
            ],
            'charts' => $this->allCharts(),
            'recent_users' => \App\Models\User::orderByDesc('created_at')->limit(5)->get(),
            'recent_logs' => $this->has('audit_logs') ? DB::table('audit_logs')->orderByDesc('created_at')->limit(10)->get() : collect(),
        ]);
    }

    // ============================================================
    // DIRECTOR
    // ============================================================
    public function director()
    {
        return view('dashboard.director', [
            'kpis' => [
                'total_employees' => $this->count('employees'),
                'active_projects' => $this->countWhere('projects', 'status', 'active'),
                'pending_approvals' => $this->countWhere('leave_requests', 'status', 'pending'),
                'total_budget' => $this->getBudgetSum(),
                'total_tasks' => $this->count('tasks'),
                'completed_tasks' => $this->countWhere('tasks', 'status', 'completed'),
            ],
            'charts' => $this->allCharts(),
            'recent_activities' => collect(),
        ]);
    }

    // ============================================================
    // EXECUTIVE
    // ============================================================
    public function executive()
    {
        return view('dashboard.executive', [
            'kpis' => [
                'total_employees' => $this->count('employees'),
                'total_projects' => $this->count('projects'),
                'total_revenue' => 0,
                'total_tasks' => $this->count('tasks'),
                'active_projects' => $this->countWhere('projects', 'status', 'active'),
                'total_budget' => $this->getBudgetSum(),
            ],
            'charts' => $this->allCharts(),
            'recent_activities' => collect(),
            'top_projects' => $this->has('projects') ? DB::table('projects')->limit(5)->get() : collect(),
        ]);
    }

    // ============================================================
    // HR
    // ============================================================
    public function hr()
    {
        $pendingLeaves = $this->has('leave_requests')
            ? DB::table('leave_requests')
                ->leftJoin('employees', 'leave_requests.employee_id', '=', 'employees.id')
                ->select('leave_requests.*', 'employees.first_name', 'employees.last_name')
                ->where('leave_requests.status', 'pending')
                ->orderByDesc('leave_requests.created_at')
                ->limit(10)
                ->get()
            : collect();

        return view('dashboard.hr', [
            'kpis' => [
                'total_employees' => $this->count('employees'),
                'active_employees' => $this->countWhere('employees', 'employment_status', 'active'),
                'pending_leaves' => $this->countWhere('leave_requests', 'status', 'pending'),
                'total_trainings' => $this->count('trainings'),
                'total_departments' => $this->count('departments'),
            ],
            'charts' => $this->allCharts(),
            'pending_leaves_list' => $pendingLeaves,
        ]);
    }

    // ============================================================
    // FINANCE
    // ============================================================
    public function finance()
    {
        $totalBudget = $this->getBudgetSum();
        $totalSpent = $this->getBudgetSpent();
        $totalRemaining = $totalBudget - $totalSpent;
        $utilization = $totalBudget > 0 ? round(($totalSpent / $totalBudget) * 100, 1) : 0;

        return view('dashboard.finance', [
            'kpis' => [
                'total_budget' => $totalBudget,
                'total_spent' => $totalSpent,
                'total_remaining' => $totalRemaining,
                'utilization' => $utilization,
                'total_income' => 0,
                'total_expenses' => $totalSpent,
                'pending_vouchers' => $this->countWhere('payment_vouchers', 'status', 'pending'),
                'pending_payments' => $this->countWhere('payment_vouchers', 'status', 'pending'),
            ],
            'charts' => $this->allCharts(),
            'recent_vouchers' => $this->has('payment_vouchers') ? DB::table('payment_vouchers')->orderByDesc('created_at')->limit(5)->get() : collect(),
        ]);
    }

    // ============================================================
    // MANAGER
    // ============================================================
    public function manager()
    {
        return view('dashboard.manager', [
            'kpis' => [
                'total_tasks' => $this->count('tasks'),
                'completed_tasks' => $this->countWhere('tasks', 'status', 'completed'),
                'pending_tasks' => $this->countWhere('tasks', 'status', 'pending'),
                'team_members' => DB::table('users')->count(),
            ],
            'charts' => $this->allCharts(),
            'recent_tasks' => $this->has('tasks') ? DB::table('tasks')->orderByDesc('created_at')->limit(5)->get() : collect(),
        ]);
    }

    // ============================================================
    // STAFF
    // ============================================================
    public function staff()
    {
        $user = auth()->user();
        $employeeId = $user->employee_id ?? null;

        return view('dashboard.staff', [
            'kpis' => [
                'my_tasks' => $this->has('tasks') ? DB::table('tasks')->where('assigned_to', $user->id)->count() : 0,
                'pending_tasks' => $this->has('tasks') ? DB::table('tasks')->where('assigned_to', $user->id)->where('status', 'pending')->count() : 0,
                'completed_tasks' => $this->has('tasks') ? DB::table('tasks')->where('assigned_to', $user->id)->where('status', 'completed')->count() : 0,
                'pending_leaves' => $employeeId && $this->has('leave_requests') ? DB::table('leave_requests')->where('employee_id', $employeeId)->where('status', 'pending')->count() : 0,
                'my_attendance' => $employeeId && $this->has('attendances') ? DB::table('attendances')->where('employee_id', $employeeId)->whereMonth('created_at', now()->month)->count() : 0,
                'attendance_rate' => 100,
            ],
            'charts' => $this->allCharts(),
            'recent_tasks' => $this->has('tasks') ? DB::table('tasks')->where('assigned_to', $user->id)->orderByDesc('created_at')->limit(5)->get() : collect(),
        ]);
    }

    // ============================================================
    // ICT
    // ============================================================
    public function ict()
    {
        return view('dashboard.ict', [
            'kpis' => [
                'total_users' => DB::table('users')->count(),
                'active_sessions' => 0,
                'system_health' => 95,
                'total_logs' => $this->count('audit_logs'),
            ],
            'charts' => $this->allCharts(),
            'recent_users' => \App\Models\User::orderByDesc('created_at')->limit(5)->get(),
        ]);
    }

    // ============================================================
    // MEAL
    // ============================================================
    public function meal()
    {
        return view('dashboard.meal', [
            'kpis' => $this->kpisMeal(),
            'charts' => $this->allCharts(),
            'recent_reports' => collect(),
        ]);
    }

    // ============================================================
    // PROGRAM
    // ============================================================
    public function program()
    {
        return view('dashboard.program', [
            'kpis' => array_merge($this->kpisProgram(), ['active_beneficiaries' => 500]),
            'charts' => $this->allCharts(),
            'recent_projects' => $this->has('projects') ? DB::table('projects')->orderByDesc('created_at')->limit(5)->get() : collect(),
        ]);
    }

    // ============================================================
    // ALL CHARTS — 21 charts
    // ============================================================
    private function allCharts(): array
    {
        return [
            'users_by_role' => $this->chartUsersByRole(),
            'user_activity' => $this->chartEmpty('Activity'),
            'documents_by_category' => $this->chartGroupBy('documents', ['category'], 'Documents'),
            'employees_by_department' => $this->chartEmployeesByDept(),
            'employees_by_gender' => $this->chartEmployeesByGender(),
            'projects_by_status' => $this->chartGroupBy('projects', ['status'], 'Projects'),
            'project_progress' => $this->chartEmpty('Project Progress'),
            'budget_by_project' => $this->chartEmpty('Budget by Project'),
            'tasks_by_status' => $this->chartGroupBy('tasks', ['status'], 'Tasks'),
            'my_tasks_by_status' => $this->chartGroupBy('tasks', ['status'], 'My Tasks'),
            'leave_by_type' => $this->chartGroupBy('leave_requests', ['leave_type'], 'Leave Types'),
            'trainings_by_category' => $this->chartGroupBy('trainings', ['category'], 'Trainings'),
            'budget_vs_actual' => $this->chartEmpty('Budget vs Actual'),
            'expenses_by_category' => $this->chartEmpty('Expenses'),
            'monthly_expenses' => $this->chartEmpty('Monthly Expenses'),
            'monthly_revenue' => $this->chartEmpty('Monthly Revenue'),
            'vouchers_by_status' => $this->chartGroupBy('payment_vouchers', ['status'], 'Vouchers'),
            'monthly_performance' => $this->chartEmpty('Performance'),
            'my_attendance_chart' => $this->chartEmpty('My Attendance'),
            'monthly_reports' => $this->chartEmpty('Monthly Reports'),
            'system_health' => $this->chartSystemHealth(),
        ];
    }

    private function chartUsersByRole(): array
    {
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

    private function chartEmployeesByDept(): array
    {
        if (!$this->has('employees') || !Schema::hasColumn('employees', 'department_id')) {
            return $this->chartEmpty('Employees');
        }
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

    private function chartEmployeesByGender(): array
    {
        if (!$this->has('employees') || !Schema::hasColumn('employees', 'gender')) {
            return $this->chartEmpty('Employees');
        }
        $rows = DB::table('employees')->select('gender', DB::raw('COUNT(*) as total'))
            ->groupBy('gender')->get();
        return [
            'labels' => $rows->pluck('gender')->map(fn($v) => $v ? ucfirst($v) : 'Nyingine')->toArray() ?: ['Hakuna'],
            'datasets' => [[
                'label' => 'Employees',
                'data' => $rows->pluck('total')->map(fn($v) => (int)$v)->toArray() ?: [0],
                'backgroundColor' => ['#3b82f6', '#ec4899', '#10b981'],
            ]],
        ];
    }

    private function chartGroupBy(string $table, array $candidates, string $label): array
    {
        if (!$this->has($table)) return $this->chartEmpty($label);
        $col = null;
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c)) { $col = $c; break; }
        }
        if (!$col) return $this->chartEmpty($label);
        $rows = DB::table($table)->select($col.' as k', DB::raw('COUNT(*) as total'))->groupBy($col)->get();
        return [
            'labels' => $rows->pluck('k')->map(fn($v) => $v ? ucfirst(str_replace('_', ' ', $v)) : 'Nyingine')->toArray() ?: ['Hakuna'],
            'datasets' => [[
                'label' => $label,
                'data' => $rows->pluck('total')->map(fn($v) => (int)$v)->toArray() ?: [0],
                'backgroundColor' => ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444'],
            ]],
        ];
    }

    private function chartSystemHealth(): array
    {
        return [
            'labels' => ['Healthy', 'Warning', 'Critical'],
            'datasets' => [[
                'label' => 'System',
                'data' => [95, 4, 1],
                'backgroundColor' => ['#10b981', '#f59e0b', '#ef4444'],
            ]],
        ];
    }

    private function chartEmpty(string $label): array
    {
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

    // ============================================================
    // HELPERS
    // ============================================================
    private function has(string $t): bool { return Schema::hasTable($t); }
    private function count(string $t): int { return $this->has($t) ? DB::table($t)->count() : 0; }
    private function countWhere(string $t, string $col, $val): int {
        return ($this->has($t) && Schema::hasColumn($t, $col)) ? DB::table($t)->where($col, $val)->count() : 0;
    }

    private function getBudgetSpent(): int
    {
        if (!$this->has('budgets')) return 0;
        foreach (['spent_amount','spent','used_amount','used'] as $c) {
            if (\Schema::hasColumn('budgets', $c)) {
                return (int) \DB::table('budgets')->sum($c);
            }
        }
        return 0;
    }
    private function getBudgetSum(): int {
        if (!$this->has('budgets')) return 0;
        foreach (['amount','budget_amount','total_amount','total','allocated_amount','allocated','budget'] as $c) {
            if (Schema::hasColumn('budgets', $c)) return (int) DB::table('budgets')->sum($c);
        }
        return 0;
    }

    // ============================================================
    // KPIS HELPERS — kila dashboard ina kpis zake
    // ============================================================
    private function kpisMeal(): array
    {
        return [
            'total_projects' => $this->count('projects'),
            'active_projects' => $this->countWhere('projects', 'status', 'active'),
            'total_trainings' => $this->count('trainings'),
            'total_documents' => $this->count('documents'),
            'total_reports' => $this->count('documents'),
            'total_indicators' => 0,
        ];
    }

    private function kpisProgram(): array
    {
        return [
            'total_projects' => $this->count('projects'),
            'active_projects' => $this->countWhere('projects', 'status', 'active'),
            'completed_projects' => $this->countWhere('projects', 'status', 'completed'),
            'total_budget' => $this->getBudgetSum(),
            'total_tasks' => $this->count('tasks'),
            'pending_tasks' => $this->countWhere('tasks', 'status', 'pending'),
        ];
    }

    private function kpisIct(): array
    {
        return [
            'total_users' => $this->has('users') ? DB::table('users')->count() : 0,
            'active_sessions' => 0,
            'system_health' => 95,
            'total_logs' => $this->count('audit_logs'),
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard kuu — inaonyesha dashboard kulingana na role
     */
    public function index()
    {
        $user = auth()->user();
        $roles = $user->roles()->pluck('code')->toArray();

        // Redirect kulingana na role
        if (in_array('super_admin', $roles) || in_array('admin', $roles)) {
            return redirect()->route('dashboard.admin');
        }

        if (in_array('ceo', $roles) || in_array('bod', $roles) || in_array('director', $roles)) {
            return redirect()->route('dashboard.director');
        }

        if (in_array('hr_manager', $roles) || in_array('hr_officer', $roles) || in_array('admin_director', $roles)) {
            return redirect()->route('dashboard.hr');
        }

        if (in_array('finance_manager', $roles) || in_array('accountant', $roles)) {
            return redirect()->route('dashboard.finance');
        }

        if (in_array('manager', $roles) || in_array('project_manager', $roles)
            || in_array('project_officer', $roles) || in_array('program_director', $roles)) {
            return redirect()->route('dashboard.manager');
        }

        if (in_array('procurement_manager', $roles)) {
            return redirect()->route('dashboard.procurement');
        }

        if (in_array('ict_manager', $roles)) {
            return redirect()->route('dashboard.ict');
        }

        if (in_array('meal_manager', $roles) || in_array('meal_officer', $roles)) {
            return redirect()->route('dashboard.meal');
        }

        // Default — staff
        return redirect()->route('dashboard.staff');
    }

    public function admin()
    {
        $kpis = [
            'total_users' => \DB::table('users')->count(),
            'total_roles' => \DB::table('roles')->count(),
            'total_permissions' => \Schema::hasTable('permissions') ? \DB::table('permissions')->count() : 0,
            'total_departments' => \Schema::hasTable('departments') ? \DB::table('departments')->count() : 0,
            'total_positions' => \Schema::hasTable('positions') ? \DB::table('positions')->count() : 0,
            'total_audit_logs' => \Schema::hasTable('audit_logs') ? \DB::table('audit_logs')->count() : 0,
        ];

        // Users by role
        $usersByRole = \DB::table('roles')
            ->leftJoin('user_roles', 'roles.id', '=', 'user_roles.role_id')
            ->select('roles.name', \DB::raw('COUNT(user_roles.user_id) as total'))
            ->groupBy('roles.id', 'roles.name')
            ->get();

        $charts = [
            'users_by_role' => [
                'labels' => $usersByRole->pluck('name')->toArray(),
                'datasets' => [[
                    'label' => 'Users',
                    'data' => $usersByRole->pluck('total')->map(fn($v) => (int)$v)->toArray(),
                    'backgroundColor' => ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#06b6d4', '#ec4899', '#6366f1'],
                ]],
            ],
            'activity' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'datasets' => [[
                    'label' => 'Activity',
                    'data' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ]],
            ],
        ];

        return view('dashboard.admin', compact('kpis', 'charts'));
    }

    public function director()
    {
        return view('dashboard.director');
    }

    public function executive()
    {
        return view('dashboard.executive');
    }

    public function hr()
    {
        return view('dashboard.hr');
    }

    public function finance()
    {
        return view('dashboard.finance');
    }

    public function manager()
    {
        return view('dashboard.manager');
    }

    public function staff()
    {
        return view('dashboard.staff');
    }

    public function ict()
    {
        return view('dashboard.ict');
    }

    public function meal()
    {
        return view('dashboard.meal');
    }

    public function program()
    {
        return view('dashboard.program');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $roles = $user->roles()->pluck('code')->toArray();

        // ============================================================
        // REDIRECT KULINGANA NA ROLE
        // ============================================================
        if (in_array('super_admin', $roles) || in_array('admin', $roles)) {
            return redirect()->route('dashboard.super_admin');
        }

        if (in_array('ceo', $roles) || in_array('bod', $roles) || in_array('director', $roles)) {
            return redirect()->route('dashboard.executive');
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

        // Default — staff
        return redirect()->route('dashboard.staff');
    }

    // ============================================================
    // DASHBOARD VIEWS
    // ============================================================

    public function superAdmin()
    {
        $totalUsers = \App\Models\User::count();
        $totalEmployees = \App\Models\Employee::count();
        $totalRoles = \DB::table('roles')->count();
        $activeUsers = \App\Models\User::where('account_status', 'active')->count();

        return view('dashboards.super_admin', compact(
            'totalUsers', 'totalEmployees', 'totalRoles', 'activeUsers'
        ));
    }

    public function executive()
    {
        return view('dashboards.executive');
    }

    public function hr()
    {
        $totalEmployees = \App\Models\Employee::count();
        return view('dashboards.hr', compact('totalEmployees'));
    }

    public function finance()
    {
        return view('dashboards.finance');
    }

    public function manager()
    {
        return view('dashboards.manager');
    }

    public function procurement()
    {
        return view('dashboards.procurement');
    }

    public function staff()
    {
        return view('dashboards.staff');
    }
}
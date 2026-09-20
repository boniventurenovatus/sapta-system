<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardService $dashboard;

    public function __construct(DashboardService $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function index()
    {
        $user = auth()->user();
        $roleCodes = $user->roles->pluck('code')->toArray();

        // ================================================================
        // CHAGUA DASHBOARD KWA ROLE
        // ================================================================
        if (array_intersect($roleCodes, ['super_admin', 'admin'])) {
            return view('dashboard.admin', $this->dashboard->adminData());
        }

        if (array_intersect($roleCodes, ['bod', 'ceo'])) {
            return view('dashboard.executive', $this->dashboard->executiveData());
        }

        if (array_intersect($roleCodes, ['director', 'admin_director', 'program_director'])) {
            return view('dashboard.director', $this->dashboard->directorData());
        }

        if (array_intersect($roleCodes, ['hr_manager', 'hr_officer'])) {
            return view('dashboard.hr', $this->dashboard->hrData());
        }

        if (array_intersect($roleCodes, ['finance_manager', 'accountant', 'procurement_manager'])) {
            return view('dashboard.finance', $this->dashboard->financeData());
        }

        if (array_intersect($roleCodes, ['project_manager', 'project_officer', 'field_trainer', 'partnerships_manager'])) {
            return view('dashboard.program', $this->dashboard->programData());
        }

        if (array_intersect($roleCodes, ['meal_manager', 'meal_officer', 'research_officer'])) {
            return view('dashboard.meal', $this->dashboard->mealData());
        }

        if (array_intersect($roleCodes, ['ict_manager', 'community_manager'])) {
            return view('dashboard.ict', $this->dashboard->ictData());
        }

        if (in_array('manager', $roleCodes)) {
            return view('dashboard.manager', $this->dashboard->managerData());
        }

        if (in_array('staff', $roleCodes)) {
            return view('dashboard.staff', $this->dashboard->staffData($user->id));
        }

        // Default — Admin dashboard
        return view('dashboard.admin', $this->dashboard->adminData());
    }
}
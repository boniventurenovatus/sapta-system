<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

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
use Illuminate\View\View;
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
            'employees' => \App\Models\Employee::count(),
            'departments' => \App\Models\Department::count(),
            'positions' => \App\Models\Position::count(),
            'documents' => \App\Models\Document::count(),
            'projects' => \App\Models\Project::count(),
            'tasks' => \App\Models\Task::count(),
            'budgets' => \App\Models\Budget::count(),
            'receipts' => \App\Models\Receipt::count(),
            'payslips' => \Schema::hasTable('payslips') ? \DB::table('payslips')->count() : 0,
            'vouchers' => \Schema::hasTable('payment_vouchers') ? \DB::table('payment_vouchers')->count() : 0,
            'attendance' => \Schema::hasTable('attendances') ? \DB::table('attendances')->count() : 0,
            'leaves' => \Schema::hasTable('leave_requests') ? \DB::table('leave_requests')->count() : 0,
            'recruitment' => \Schema::hasTable('recruitments') ? \DB::table('recruitments')->count() : 0,
            'trainings' => \Schema::hasTable('trainings') ? \DB::table('trainings')->count() : 0,
            'performance' => \Schema::hasTable('performance_reviews') ? \DB::table('performance_reviews')->count() : 0,
            'users' => \Schema::hasTable('users') ? \DB::table('users')->count() : 0,
        ];

        return view('reports.index', compact('stats'));
    }
}

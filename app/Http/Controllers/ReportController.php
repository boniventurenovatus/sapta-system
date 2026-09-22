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
            'employees' => \Schema::hasTable('employees') ? \DB::table('employees')->whereNull('deleted_at')->count() : 0,
            'departments' => \Schema::hasTable('departments') ? \DB::table('departments')->count() : 0,
            'positions' => \Schema::hasTable('positions') ? \DB::table('positions')->count() : 0,
            'documents' => \Schema::hasTable('documents') ? \DB::table('documents')->whereNull('deleted_at')->count() : 0,
            'projects' => \Schema::hasTable('projects') ? \DB::table('projects')->whereNull('deleted_at')->count() : 0,
            'tasks' => \Schema::hasTable('tasks') ? \DB::table('tasks')->whereNull('deleted_at')->count() : 0,
            'budgets' => \Schema::hasTable('budgets') ? \DB::table('budgets')->count() : 0,
            'attendance' => \Schema::hasTable('attendances') ? \DB::table('attendances')->count() : 0,
            'leaves' => \Schema::hasTable('leave_requests') ? \DB::table('leave_requests')->count() : 0,
            'payroll' => \Schema::hasTable('payslips') ? \DB::table('payslips')->count() : 0,
            'vouchers' => \Schema::hasTable('payment_vouchers') ? \DB::table('payment_vouchers')->count() : 0,
            'receipts' => \Schema::hasTable('receipts') ? \DB::table('receipts')->count() : 0,
            'recruitment' => \Schema::hasTable('recruitment') ? \DB::table('recruitment')->count() : 0,
            'trainings' => \Schema::hasTable('trainings') ? \DB::table('trainings')->count() : 0,
            'performance' => \Schema::hasTable('performance_reviews') ? \DB::table('performance_reviews')->count() : 0,
            'users' => \Schema::hasTable('users') ? \DB::table('users')->count() : 0,
            'roles' => \Schema::hasTable('roles') ? \DB::table('roles')->count() : 0,
        ];

        return view('reports.index', compact('stats'));
    }
}

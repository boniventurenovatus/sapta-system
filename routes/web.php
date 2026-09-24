<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\EmployeePositionController;
use App\Http\Controllers\OrganogramController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SearchController;

// ROOT
Route::get('/', function () {
    if (auth()->check()) {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
    return redirect()->route('login');
})->name('home');

// AUTH
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest')->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

// PASSWORD RESET
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.update');

// DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// DASHBOARDS ZOTE
Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/admin', [DashboardController::class, 'admin'])->middleware('role:super_admin,admin')->name('admin');
    Route::get('/director', [DashboardController::class, 'director'])->middleware('role:ceo,bod,director,super_admin,admin')->name('director');
    Route::get('/executive', [DashboardController::class, 'executive'])->middleware('role:ceo,bod,director,super_admin,admin')->name('executive');
    Route::get('/hr', [DashboardController::class, 'hr'])->middleware('role:hr_manager,hr_officer,admin_director,super_admin,admin')->name('hr');
    Route::get('/finance', [DashboardController::class, 'finance'])->middleware('role:finance_manager,accountant,super_admin,admin')->name('finance');
    Route::get('/manager', [DashboardController::class, 'manager'])->middleware('role:manager,project_manager,project_officer,program_director,super_admin,admin')->name('manager');
    Route::get('/staff', [DashboardController::class, 'staff'])->middleware('role:staff,super_admin,admin')->name('staff');
    Route::get('/ict', [DashboardController::class, 'ict'])->middleware('role:ict_manager,super_admin,admin')->name('ict');
    Route::get('/meal', [DashboardController::class, 'meal'])->middleware('role:meal_manager,meal_officer,super_admin,admin')->name('meal');
    Route::get('/program', [DashboardController::class, 'program'])->middleware('role:program_director,super_admin,admin')->name('program');
});

// MANAGEMENT
Route::resource('employees', EmployeeController::class)->middleware('role:super_admin,admin,director,admin_director,program_director,hr_manager,hr_officer,ceo,bod,manager');
Route::resource('organizations', OrganizationController::class)->middleware('auth');
Route::resource('departments', DepartmentController::class)->middleware('auth');
Route::resource('positions', PositionController::class)->middleware('role:super_admin,admin,director,admin_director,hr_manager,hr_officer,ceo,bod,manager');
Route::resource('employee-positions', EmployeePositionController::class)->middleware('role:super_admin,admin,hr_manager,hr_officer');

// ORGANOGRAM
Route::get('/organogram', [OrganogramController::class, 'index'])->middleware('auth')->name('organogram.index');

// SETTINGS
Route::get('/settings', [SettingController::class, 'index'])->middleware('role:super_admin,admin')->name('settings.index');
Route::put('/settings', [SettingController::class, 'update'])->middleware('role:super_admin,admin')->name('settings.update');

// HR
Route::resource('attendances', AttendanceController::class)->middleware('role:super_admin,admin,director,manager,staff');
Route::resource('leave-requests', LeaveRequestController::class)->middleware('role:super_admin,admin,director,manager,staff');

// USER & RBAC
Route::resource('users', UserController::class)->middleware('role:super_admin,admin');
Route::resource('roles', RoleController::class)->middleware('role:super_admin,admin');
Route::resource('permissions', PermissionController::class)->middleware('role:super_admin,admin');

// EMPLOYEE STATUS ACTIONS
Route::post('employees/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->middleware('role:super_admin,admin,director,admin_director,hr_manager,hr_officer,ceo')->name('employees.deactivate');
Route::post('employees/{employee}/terminate', [EmployeeController::class, 'terminate'])->middleware('role:super_admin,admin,director,admin_director,hr_manager,hr_officer,ceo')->name('employees.terminate');
Route::post('employees/{employee}/activate', [EmployeeController::class, 'activate'])->middleware('role:super_admin,admin,director,admin_director,hr_manager,hr_officer,ceo')->name('employees.activate');
Route::post('employees/{employee}/suspend', [EmployeeController::class, 'suspend'])->middleware('role:super_admin,admin,director,admin_director,hr_manager,hr_officer,ceo')->name('employees.suspend');

// USER SUSPEND/ACTIVATE
Route::post('users/{user}/suspend', [UserController::class, 'suspend'])->middleware('role:super_admin,admin')->name('users.suspend');
Route::post('users/{user}/activate', [UserController::class, 'activate'])->middleware('role:super_admin,admin')->name('users.activate');

// PROFILE
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

// LANGUAGE
Route::get('/language/switch', [LanguageController::class, 'switch'])->name('language.switch');

// SEARCH
Route::get('/search', [SearchController::class, 'index'])->middleware('auth')->name('search');
Route::get('/search/live', [SearchController::class, 'search'])->middleware('auth')->name('search.live');

// REPORTS
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('auth');
Route::get('/reports/employees', [ReportController::class, 'employees'])->name('reports.employees')->middleware('role:super_admin,admin,director,manager,finance_manager');
Route::get('/reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance')->middleware('role:super_admin,admin,director,manager,finance_manager');
Route::get('/reports/projects', [ReportController::class, 'projects'])->name('reports.projects')->middleware('role:super_admin,admin,director,manager,finance_manager');
Route::get('/reports/tasks', [ReportController::class, 'tasks'])->name('reports.tasks')->middleware('role:super_admin,admin,director,manager,finance_manager');
Route::get('/reports/leaves', [ReportController::class, 'leaves'])->name('reports.leaves')->middleware('role:super_admin,admin,director,manager,finance_manager');

// TASKS
Route::resource('tasks', TaskController::class)->middleware('auth');

// PROJECTS
Route::resource('projects', ProjectController::class)->middleware('auth');
// ============================================================
// RESET PASSWORDS — kwa kudumu
// ============================================================
Route::get('/debug/reset-passwords', function() {
    $hashed = \Hash::make('Sapta@2025!');
    $updated = \DB::table('users')->update([
        'password_hash' => $hashed,
        'account_status' => 'active',
        'is_first_login' => false,
    ]);
    
    $superadmin = \DB::table('users')->where('username', 'superadmin')->first();
    return response()->json([
        'success' => true,
        'users_updated' => $updated,
        'verify' => \Hash::check('Sapta@2025!', $superadmin->password_hash),
    ]);
})->name('debug.reset-passwords');
// ============================================================
// RESET PASSWORDS
// ============================================================
Route::get('/debug/reset-passwords', function() {
    $hashed = \Hash::make('Sapta@2025!');
    $updated = \DB::table('users')->update([
        'password_hash' => $hashed,
        'account_status' => 'active',
        'is_first_login' => false,
    ]);
    
    $superadmin = \DB::table('users')->where('username', 'superadmin')->first();
    return response()->json([
        'success' => true,
        'users_updated' => $updated,
        'verify' => \Hash::check('Sapta@2025!', $superadmin->password_hash),
    ]);
})->name('debug.reset-passwords');
// ============================================================
// ACTIVITY LOGS
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{log}', [\App\Http\Controllers\ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::delete('/activity-logs/{log}', [\App\Http\Controllers\ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
});

// ============================================================
// BUDGETS
// ============================================================
Route::resource('budgets', \App\Http\Controllers\BudgetController::class)->middleware('auth');

// ============================================================
// RECEIPTS
// ============================================================
Route::resource('receipts', \App\Http\Controllers\ReceiptController::class)->middleware('auth');

// ============================================================
// PAYMENT VOUCHERS
// ============================================================
Route::resource('payment-vouchers', \App\Http\Controllers\PaymentVoucherController::class)->middleware('auth');

// ============================================================
// PAYROLL
// ============================================================
Route::resource('payroll', \App\Http\Controllers\PayrollController::class)->middleware('auth');

// ============================================================
// TRAININGS
// ============================================================
Route::resource('trainings', \App\Http\Controllers\TrainingController::class)->middleware('auth');

// ============================================================
// DOCUMENTS
// ============================================================
Route::resource('documents', \App\Http\Controllers\DocumentController::class)->middleware('auth');

// ============================================================
// RECRUITMENT
// ============================================================
Route::resource('recruitment', \App\Http\Controllers\RecruitmentController::class)->middleware('auth');

// ============================================================
// PERFORMANCE REVIEWS
// ============================================================
Route::resource('performance-reviews', \App\Http\Controllers\PerformanceReviewController::class)->middleware('auth');

// ============================================================
// EXPENSE CLAIMS
// ============================================================
Route::resource('expense-claims', \App\Http\Controllers\ExpenseClaimController::class)->middleware('auth');

// ============================================================
// PROCUREMENT REQUESTS
// ============================================================
Route::resource('procurement-requests', \App\Http\Controllers\ProcurementRequestController::class)->middleware('auth');

// ============================================================
// AUDIT LOGS
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{log}', [\App\Http\Controllers\AuditLogController::class, 'show'])->name('audit-logs.show');
});

// ============================================================
// LOCATION API
// ============================================================
Route::middleware(['auth'])->prefix('location')->name('location.')->group(function () {
    Route::get('/regions', [\App\Http\Controllers\LocationController::class, 'regions'])->name('regions');
    Route::get('/districts', [\App\Http\Controllers\LocationController::class, 'districts'])->name('districts');
    Route::get('/wards', [\App\Http\Controllers\LocationController::class, 'wards'])->name('wards');
});

// ============================================================
// COMMUNICATION
// ============================================================
Route::middleware(['auth'])->prefix('communication')->name('communication.')->group(function () {
    Route::get('/inbox', [\App\Http\Controllers\CommunicationController::class, 'inbox'])->name('inbox');
    Route::get('/sent', [\App\Http\Controllers\CommunicationController::class, 'sent'])->name('sent');
    Route::get('/message/create', [\App\Http\Controllers\CommunicationController::class, 'create'])->name('message-create');
    Route::post('/message', [\App\Http\Controllers\CommunicationController::class, 'store'])->name('message.store');
    Route::get('/message/{message}', [\App\Http\Controllers\CommunicationController::class, 'show'])->name('message-show');
});

// ============================================================
// NOTIFICATIONS
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// ============================================================
// MY WORK
// ============================================================
Route::middleware(['auth'])->prefix('my-work')->name('my-work.')->group(function () {
    Route::get('/', [\App\Http\Controllers\MyWorkController::class, 'index'])->name('index');
    Route::get('/tasks', [\App\Http\Controllers\MyWorkController::class, 'tasks'])->name('tasks');
    Route::get('/submissions', [\App\Http\Controllers\MyWorkController::class, 'submissions'])->name('submissions');
    Route::get('/approvals', [\App\Http\Controllers\MyWorkController::class, 'approvals'])->name('approvals');
    Route::get('/drafts', [\App\Http\Controllers\MyWorkController::class, 'drafts'])->name('drafts');
});

// ============================================================
// MY PAYSLIPS
// ============================================================
Route::get('/my-payslips', [\App\Http\Controllers\PayrollController::class, 'myPayslips'])->middleware('auth')->name('my-payslips');

// ============================================================
// DEBUG: RESET PASSWORDS
// ============================================================
Route::get('/debug/reset-passwords', function() {
    $hashed = \Hash::make('Sapta@2025!');
    $updated = \DB::table('users')->update([
        'password_hash' => $hashed,
        'account_status' => 'active',
        'is_first_login' => false,
    ]);
    $superadmin = \DB::table('users')->where('username', 'superadmin')->first();
    return response()->json([
        'success' => true,
        'users_updated' => $updated,
        'verify' => \Hash::check('Sapta@2025!', $superadmin->password_hash),
    ]);
})->name('debug.reset-passwords');
// ============================================================
// EMPLOYEE CREDENTIALS + RESET PASSWORD
// ============================================================
Route::middleware(['auth'])->group(function () {
    // Credentials — Admin anaona
    Route::get('employees/{employee}/credentials', [\App\Http\Controllers\EmployeeController::class, 'credentials'])
        ->name('employees.credentials')
        ->middleware('role:super_admin,admin,director,admin_director,hr_manager,hr_officer');
    
    // Reset Password — Admin
    Route::post('employees/{employee}/reset-password', [\App\Http\Controllers\EmployeeController::class, 'resetPassword'])
        ->name('employees.reset-password')
        ->middleware('role:super_admin,admin,hr_manager,hr_officer');
});
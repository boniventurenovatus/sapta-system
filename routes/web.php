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
    Route::get('/finance', [DashboardController::class, 'finance'])->middleware('role:finance_manager,accountant,procurement_manager,super_admin,admin')->name('finance');
    Route::get('/manager', [DashboardController::class, 'manager'])->middleware('role:manager,project_manager,project_officer,program_director,super_admin,admin')->name('manager');
    Route::get('/staff', [DashboardController::class, 'staff'])->middleware('role:staff,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,super_admin,admin')->name('staff');
    Route::get('/ict', [DashboardController::class, 'ict'])->middleware('role:ict_manager,super_admin,admin')->name('ict');
    Route::get('/meal', [DashboardController::class, 'meal'])->middleware('role:meal_manager,meal_officer,super_admin,admin')->name('meal');
    Route::get('/program', [DashboardController::class, 'program'])->middleware('role:program_director,super_admin,admin')->name('program');
});

// MANAGEMENT
Route::resource('employees', EmployeeController::class)->middleware('role:super_admin,admin,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,admin_director,program_director,hr_manager,hr_officer,ceo,bod,manager');
Route::resource('organizations', OrganizationController::class)->middleware('role:super_admin,admin,hr_manager,hr_officer,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager');
Route::resource('departments', DepartmentController::class)->middleware('role:super_admin,admin,hr_manager,hr_officer,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager');
Route::resource('positions', PositionController::class)->middleware('role:super_admin,admin,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,admin_director,hr_manager,hr_officer,ceo,bod,manager');
Route::resource('employee-positions', EmployeePositionController::class)->middleware('role:super_admin,admin,hr_manager,hr_officer,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager');

// ORGANOGRAM
Route::get('/organogram', [OrganogramController::class, 'index'])->middleware('auth')->name('organogram.index');

// SETTINGS
Route::get('/settings', [SettingController::class, 'index'])->middleware('role:super_admin,admin')->name('settings.index');
Route::put('/settings', [SettingController::class, 'update'])->middleware('role:super_admin,admin')->name('settings.update');

// HR
Route::resource('attendances', AttendanceController::class)->middleware('role:super_admin,admin,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,manager,staff');
Route::resource('leave-requests', LeaveRequestController::class)->middleware('role:super_admin,admin,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,manager,staff');

// USER & RBAC
Route::resource('users', UserController::class)->middleware('role:super_admin,admin');
Route::resource('roles', RoleController::class)->middleware('role:super_admin,admin');
Route::resource('permissions', PermissionController::class)->middleware('role:super_admin,admin');

// EMPLOYEE STATUS ACTIONS
Route::post('employees/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->middleware('role:super_admin,admin,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,admin_director,hr_manager,hr_officer,ceo')->name('employees.deactivate');
Route::post('employees/{employee}/terminate', [EmployeeController::class, 'terminate'])->middleware('role:super_admin,admin,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,admin_director,hr_manager,hr_officer,ceo')->name('employees.terminate');
Route::post('employees/{employee}/activate', [EmployeeController::class, 'activate'])->middleware('role:super_admin,admin,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,admin_director,hr_manager,hr_officer,ceo')->name('employees.activate');
Route::post('employees/{employee}/suspend', [EmployeeController::class, 'suspend'])->middleware('role:super_admin,admin,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,admin_director,hr_manager,hr_officer,ceo')->name('employees.suspend');

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
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('role:super_admin,admin,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,manager,finance_manager,hr_manager');
Route::get('/reports/employees', [ReportController::class, 'employees'])->name('reports.employees')->middleware('role:super_admin,admin,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,manager,finance_manager');
Route::get('/reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance')->middleware('role:super_admin,admin,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,manager,finance_manager');
Route::get('/reports/projects', [ReportController::class, 'projects'])->name('reports.projects')->middleware('role:super_admin,admin,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,manager,finance_manager');
Route::get('/reports/tasks', [ReportController::class, 'tasks'])->name('reports.tasks')->middleware('role:super_admin,admin,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,manager,finance_manager');
Route::get('/reports/leaves', [ReportController::class, 'leaves'])->name('reports.leaves')->middleware('role:super_admin,admin,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,manager,finance_manager');

// TASKS
Route::resource('tasks', TaskController::class)->middleware('role:super_admin,admin,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,program_director,project_manager,project_officer,manager,staff');

// PROJECTS
Route::resource('projects', ProjectController::class)->middleware('role:super_admin,admin,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,program_director,project_manager,project_officer');
// ============================================================
// RESET PASSWORDS — kwa kudumu
// ============================================================
// ============================================================
// RESET PASSWORDS
// ============================================================
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
Route::resource('budgets', \App\Http\Controllers\BudgetController::class)->middleware('role:super_admin,admin,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,finance_manager,accountant,procurement_manager');

// ============================================================
// RECEIPTS
// ============================================================
Route::resource('receipts', \App\Http\Controllers\ReceiptController::class)->middleware('role:super_admin,admin,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,finance_manager,accountant');

// ============================================================
// PAYMENT VOUCHERS
// ============================================================
Route::resource('payment-vouchers', \App\Http\Controllers\PaymentVoucherController::class)->middleware('role:super_admin,admin,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,finance_manager,accountant,procurement_manager');

// ============================================================
// PAYROLL
// ============================================================
// Route::resource('payroll', ...) imeondolewa kwa sababu inagongana na custom routes

// ============================================================
// TRAININGS
// ============================================================
Route::resource('trainings', \App\Http\Controllers\TrainingController::class)->middleware('role:super_admin,admin,hr_manager,hr_officer,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager');

// ============================================================
// DOCUMENTS
// ============================================================
Route::resource('documents', \App\Http\Controllers\DocumentController::class)->middleware('role:super_admin,admin,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,hr_manager,hr_officer,manager,staff');

// ============================================================
// DOCUMENTS — CUSTOM ROUTES
// ============================================================
Route::middleware(['auth'])->prefix('documents')->name('documents.')->group(function () {
    Route::get('/{document}/download', [\App\Http\Controllers\DocumentController::class, 'download'])->name('download');
    Route::get('/{document}/preview', [\App\Http\Controllers\DocumentController::class, 'preview'])->name('preview');
    Route::get('/{document}/raw', [\App\Http\Controllers\DocumentController::class, 'raw'])
        ->name('raw');
    Route::get('/{document}/raw-download', [\App\Http\Controllers\DocumentController::class, 'rawDownload'])
        ->name('raw-download');
    Route::post('/{document}/replace', [\App\Http\Controllers\DocumentController::class, 'replace'])->name('replace');
    Route::post('/{document}/approve', [\App\Http\Controllers\DocumentController::class, 'approve'])->name('approve');
    Route::post('/{document}/archive', [\App\Http\Controllers\DocumentController::class, 'archive'])->name('archive');
    Route::get('/export/all-csv', [\App\Http\Controllers\DocumentController::class, 'exportCsv'])->name('export.all-csv');
});

// ============================================================
// RECRUITMENT
// ============================================================
Route::resource('recruitment', \App\Http\Controllers\RecruitmentController::class)->middleware('role:super_admin,admin,hr_manager,hr_officer,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager');

// ============================================================
// PERFORMANCE REVIEWS
// ============================================================
Route::resource('performance-reviews', \App\Http\Controllers\PerformanceReviewController::class)->middleware('role:super_admin,admin,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,hr_manager,hr_officer,manager');

// ============================================================
// EXPENSE CLAIMS
// ============================================================
Route::resource('expense-claims', \App\Http\Controllers\ExpenseClaimController::class)->middleware('role:super_admin,admin,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,finance_manager,accountant');

// ============================================================
// PROCUREMENT REQUESTS
// ============================================================
Route::resource('procurement-requests', \App\Http\Controllers\ProcurementRequestController::class)->middleware('role:super_admin,admin,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,finance_manager,procurement_manager');

// ============================================================
// AUDIT LOGS
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{log}', [\App\Http\Controllers\AuditLogController::class, 'show'])->name('audit-logs.show');
});

// PAYROLL VERIFICATION (bila auth — QR code inaweza kufikiwa na mtu yeyote)
Route::get('/payroll/verify/{token}', [\App\Http\Controllers\PayrollController::class, 'verify'])->name('payroll.verify.public');


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
// ============================================================
// EMPLOYEE CREDENTIALS + RESET PASSWORD
// ============================================================
Route::middleware(['auth'])->group(function () {
    // Credentials — Admin anaona
    Route::get('employees/{employee}/credentials', [\App\Http\Controllers\EmployeeController::class, 'credentials'])
        ->name('employees.credentials')
        ->middleware('role:super_admin,admin,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager,director,admin_director,hr_manager,hr_officer');
    
    // Reset Password — Admin
    Route::post('employees/{employee}/reset-password', [\App\Http\Controllers\EmployeeController::class, 'resetPassword'])
        ->name('employees.reset-password')
        ->middleware('role:super_admin,admin,hr_manager,hr_officer,field_trainer,research_officer,community_manager,partnerships_manager,meal_officer,project_officer,staff,manager');
});
// ============================================================
// ============================================================

// ============================================================
// DEBUG: FORCE RESET SUPERADMIN (mara moja tu)
// ============================================================

// ============================================================
// DEBUG: TEST LOGIN FLOW
// ============================================================

// ============================================================
// DEBUG: RESET AND TEST
// ============================================================

// ============================================================
// DEBUG: CLEAR CACHE (futa baada ya kutumia!)
// ============================================================

// ============================================================
// DEBUG: TEST ICT DASHBOARD (futa baada ya kutumia!)
// ============================================================





// CHANGE PASSWORD
Route::get('/profile/change-password', [ProfileController::class, 'showChangePassword'])->name('profile.change-password');
Route::put('/profile/change-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

// ============================================================
// PAYMENT VOUCHER — WORKFLOW ACTIONS
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::post('payment-vouchers/{payment_voucher}/check', [\App\Http\Controllers\PaymentVoucherController::class, 'check'])
        ->name('payment-vouchers.check');

    Route::post('payment-vouchers/{payment_voucher}/authorize', [\App\Http\Controllers\PaymentVoucherController::class, 'authorize'])
        ->name('payment-vouchers.authorize');

    Route::post('payment-vouchers/{payment_voucher}/approve', [\App\Http\Controllers\PaymentVoucherController::class, 'approve'])
        ->name('payment-vouchers.approve');

    Route::post('payment-vouchers/{payment_voucher}/return', [\App\Http\Controllers\PaymentVoucherController::class, 'returnVoucher'])
        ->name('payment-vouchers.return');

    Route::post('payment-vouchers/{payment_voucher}/paid', [\App\Http\Controllers\PaymentVoucherController::class, 'markAsPaid'])
        ->name('payment-vouchers.paid');
});
// ============================================================
// PAYMENT VOUCHER — WORKFLOW ACTIONS
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::post('payment-vouchers/{payment_voucher}/check', [\App\Http\Controllers\PaymentVoucherController::class, 'check'])
        ->name('payment-vouchers.check');

    Route::post('payment-vouchers/{payment_voucher}/authorize', [\App\Http\Controllers\PaymentVoucherController::class, 'authorizeVoucher'])
        ->name('payment-vouchers.authorize');

    Route::post('payment-vouchers/{payment_voucher}/approve', [\App\Http\Controllers\PaymentVoucherController::class, 'approve'])
        ->name('payment-vouchers.approve');

    Route::post('payment-vouchers/{payment_voucher}/return', [\App\Http\Controllers\PaymentVoucherController::class, 'returnVoucher'])
        ->name('payment-vouchers.return');

    Route::post('payment-vouchers/{payment_voucher}/paid', [\App\Http\Controllers\PaymentVoucherController::class, 'markPaid'])
        ->name('payment-vouchers.paid');

    Route::get('payment-vouchers/{payment_voucher}/print', [\App\Http\Controllers\PaymentVoucherController::class, 'print'])
        ->name('payment-vouchers.print');

    Route::get('payment-vouchers/{payment_voucher}/pdf', [\App\Http\Controllers\PaymentVoucherController::class, 'downloadPdf'])
        ->name('payment-vouchers.pdf');
});


// ============================================================
// REPORTS — ZAIDI
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/reports/trainings', [\App\Http\Controllers\ReportController::class, 'trainings'])
        ->name('reports.trainings');

    Route::get('/reports/budgets', [\App\Http\Controllers\ReportController::class, 'budgets'])
        ->name('reports.budgets');
});
// ============================================================
// NOTIFICATIONS — ACTIONS
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');

    Route::post('/notifications/{notification}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-read');

    Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])
        ->name('notifications.destroy');
});
// ============================================================
// REPORTS — ZOTE
// ============================================================
Route::middleware(['auth'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/recruitment', [\App\Http\Controllers\ReportController::class, 'recruitment'])->name('recruitment');
    Route::get('/trainings', [\App\Http\Controllers\ReportController::class, 'trainings'])->name('trainings');
    Route::get('/budgets', [\App\Http\Controllers\ReportController::class, 'budgets'])->name('budgets');
});

// ============================================================
// NOTIFICATIONS — ACTIONS
// ============================================================
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::post('/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::post('/{notification}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::delete('/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
});

// ============================================================
// LOCATION — ZOTE
// ============================================================
Route::middleware(['auth'])->prefix('location')->name('location.')->group(function () {
    Route::get('/regions', [\App\Http\Controllers\LocationController::class, 'regions'])->name('regions');
    Route::get('/districts', [\App\Http\Controllers\LocationController::class, 'districts'])->name('districts');
    Route::get('/wards', [\App\Http\Controllers\LocationController::class, 'wards'])->name('wards');
});

// ============================================================
// COMMUNICATION — ZOTE
// ============================================================
Route::middleware(['auth'])->prefix('communication')->name('communication.')->group(function () {
    Route::get('/inbox', [\App\Http\Controllers\CommunicationController::class, 'inbox'])->name('inbox');
    Route::get('/sent', [\App\Http\Controllers\CommunicationController::class, 'sent'])->name('sent');
    Route::get('/compose', [\App\Http\Controllers\CommunicationController::class, 'compose'])->name('compose');
});
// ============================================================
// COMMUNICATION — ZOTE
// ============================================================
Route::middleware(['auth'])->prefix('communication')->name('communication.')->group(function () {
    Route::get('/', [\App\Http\Controllers\CommunicationController::class, 'index'])->name('index');
    Route::get('/inbox', [\App\Http\Controllers\CommunicationController::class, 'inbox'])->name('inbox');
    Route::get('/sent', [\App\Http\Controllers\CommunicationController::class, 'sent'])->name('sent');
    Route::get('/drafts', [\App\Http\Controllers\CommunicationController::class, 'drafts'])->name('drafts');
    Route::get('/conversations', [\App\Http\Controllers\CommunicationController::class, 'conversations'])->name('conversations');

    Route::get('/message/create', [\App\Http\Controllers\CommunicationController::class, 'createMessage'])->name('message-create');
    Route::post('/message', [\App\Http\Controllers\CommunicationController::class, 'storeMessage'])->name('message-store');
    Route::get('/message/{message}', [\App\Http\Controllers\CommunicationController::class, 'showMessage'])->name('message-show');
    Route::post('/message/{message}/unread', [\App\Http\Controllers\CommunicationController::class, 'markUnread'])->name('message-unread');
    Route::delete('/message/{message}', [\App\Http\Controllers\CommunicationController::class, 'destroyMessage'])->name('message-destroy');
});

// ============================================================
// REPORTS — ZOTE
// ============================================================
Route::middleware(['auth'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('index');
    Route::get('/employees', [\App\Http\Controllers\ReportController::class, 'employees'])->name('employees');
    Route::get('/attendance', [\App\Http\Controllers\ReportController::class, 'attendance'])->name('attendance');
    Route::get('/projects', [\App\Http\Controllers\ReportController::class, 'projects'])->name('projects');
    Route::get('/tasks', [\App\Http\Controllers\ReportController::class, 'tasks'])->name('tasks');
    Route::get('/leaves', [\App\Http\Controllers\ReportController::class, 'leaves'])->name('leaves');
    Route::get('/recruitment', [\App\Http\Controllers\ReportController::class, 'recruitment'])->name('recruitment');
    Route::get('/trainings', [\App\Http\Controllers\ReportController::class, 'trainings'])->name('trainings');
    Route::get('/budgets', [\App\Http\Controllers\ReportController::class, 'budgets'])->name('budgets');
});

// ============================================================
// NOTIFICATIONS — ZOTE
// ============================================================
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    Route::post('/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::post('/{notification}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::delete('/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
});

// ============================================================
// TRAININGS
// ============================================================
Route::middleware(['auth'])->prefix('trainings')->name('trainings.')->group(function () {
    Route::get('/', [\App\Http\Controllers\TrainingController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\TrainingController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\TrainingController::class, 'store'])->name('store');
    Route::get('/{training}', [\App\Http\Controllers\TrainingController::class, 'show'])->name('show');
    Route::get('/{training}/edit', [\App\Http\Controllers\TrainingController::class, 'edit'])->name('edit');
    Route::put('/{training}', [\App\Http\Controllers\TrainingController::class, 'update'])->name('update');
    Route::delete('/{training}', [\App\Http\Controllers\TrainingController::class, 'destroy'])->name('destroy');
});

// ============================================================
// BUDGETS
// ============================================================
Route::middleware(['auth'])->prefix('budgets')->name('budgets.')->group(function () {
    Route::get('/', [\App\Http\Controllers\BudgetController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\BudgetController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\BudgetController::class, 'store'])->name('store');
    Route::get('/{budget}', [\App\Http\Controllers\BudgetController::class, 'show'])->name('show');
    Route::get('/{budget}/edit', [\App\Http\Controllers\BudgetController::class, 'edit'])->name('edit');
    Route::put('/{budget}', [\App\Http\Controllers\BudgetController::class, 'update'])->name('update');
    Route::delete('/{budget}', [\App\Http\Controllers\BudgetController::class, 'destroy'])->name('destroy');
});

// ============================================================
// RECEIPTS
// ============================================================
Route::middleware(['auth'])->prefix('receipts')->name('receipts.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ReceiptController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\ReceiptController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\ReceiptController::class, 'store'])->name('store');
    Route::get('/{receipt}', [\App\Http\Controllers\ReceiptController::class, 'show'])->name('show');
    Route::get('/{receipt}/edit', [\App\Http\Controllers\ReceiptController::class, 'edit'])->name('edit');
    Route::put('/{receipt}', [\App\Http\Controllers\ReceiptController::class, 'update'])->name('update');
    Route::delete('/{receipt}', [\App\Http\Controllers\ReceiptController::class, 'destroy'])->name('destroy');
});

// ============================================================
// FINANCE — DASHBOARD
// ============================================================
Route::middleware(['auth'])->prefix('finance')->name('finance.')->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'finance'])->name('index');
});
// ============================================================
// EXPORT ROUTES — ZOTE
// ============================================================

// RECRUITMENT EXPORTS
Route::middleware(['auth'])->prefix('recruitment')->name('recruitment.')->group(function () {
    Route::get('/', [\App\Http\Controllers\RecruitmentController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\RecruitmentController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\RecruitmentController::class, 'store'])->name('store');
    Route::get('/export/all-csv', [\App\Http\Controllers\RecruitmentController::class, 'exportCsv'])->name('export.all-csv');
    Route::get('/export/csv', [\App\Http\Controllers\RecruitmentController::class, 'exportCsv'])->name('export.csv');
});

// TRAININGS EXPORTS
Route::middleware(['auth'])->prefix('trainings')->name('trainings.')->group(function () {
    Route::get('/', [\App\Http\Controllers\TrainingController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\TrainingController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\TrainingController::class, 'store'])->name('store');
    Route::get('/export/all-csv', [\App\Http\Controllers\TrainingController::class, 'exportCsv'])->name('export.all-csv');
    Route::get('/export/csv', [\App\Http\Controllers\TrainingController::class, 'exportCsv'])->name('export.csv');
});

// BUDGETS EXPORTS
Route::middleware(['auth'])->prefix('budgets')->name('budgets.')->group(function () {
    Route::get('/', [\App\Http\Controllers\BudgetController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\BudgetController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\BudgetController::class, 'store'])->name('store');
    Route::get('/export/all-csv', [\App\Http\Controllers\BudgetController::class, 'exportCsv'])->name('export.all-csv');
    Route::get('/export/all-excel', [\App\Http\Controllers\BudgetController::class, 'exportExcel'])->name('export.all-excel');
});

// RECEIPTS EXPORTS
Route::middleware(['auth'])->prefix('receipts')->name('receipts.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ReceiptController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\ReceiptController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\ReceiptController::class, 'store'])->name('store');
    Route::get('/export/all-csv', [\App\Http\Controllers\ReceiptController::class, 'exportCsv'])->name('export.all-csv');
    Route::get('/export/all-excel', [\App\Http\Controllers\ReceiptController::class, 'exportExcel'])->name('export.all-excel');
});

// DOCUMENTS EXPORTS
Route::middleware(['auth'])->prefix('documents')->name('documents.')->group(function () {
    Route::get('/', [\App\Http\Controllers\DocumentController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\DocumentController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\DocumentController::class, 'store'])->name('store');
    Route::get('/export/all-csv', [\App\Http\Controllers\DocumentController::class, 'exportCsv'])->name('export.all-csv');
});

// PERFORMANCE REVIEWS EXPORTS
Route::middleware(['auth'])->prefix('performance-reviews')->name('performance-reviews.')->group(function () {
    Route::get('/', [\App\Http\Controllers\PerformanceReviewController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\PerformanceReviewController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\PerformanceReviewController::class, 'store'])->name('store');
    Route::get('/export/all-csv', [\App\Http\Controllers\PerformanceReviewController::class, 'exportCsv'])->name('export.all-csv');
    Route::get('/export/all-excel', [\App\Http\Controllers\PerformanceReviewController::class, 'exportExcel'])->name('export.all-excel');
});

// PAYROLL
Route::middleware(['auth'])->prefix('payroll')->name('payroll.')->group(function () {
    Route::get('/', [\App\Http\Controllers\PayrollController::class, 'index'])->name('index');
    Route::post('/generate', [\App\Http\Controllers\PayrollController::class, 'generate'])->name('generate');
    Route::get('/salaries', [\App\Http\Controllers\PayrollController::class, 'salaries'])->name('salaries');
    Route::get('/salaries/create', [\App\Http\Controllers\PayrollController::class, 'createSalary'])->name('salaries.create');
    Route::post('/salaries', [\App\Http\Controllers\PayrollController::class, 'storeSalary'])->name('salaries.store');
    Route::get('/salaries/{salary}/edit', [\App\Http\Controllers\PayrollController::class, 'editSalary'])->name('salaries.edit');
    Route::put('/salaries/{salary}', [\App\Http\Controllers\PayrollController::class, 'updateSalary'])->name('salaries.update');
    Route::delete('/salaries/{salary}', [\App\Http\Controllers\PayrollController::class, 'destroySalary'])->name('salaries.destroy');
    Route::get('/export/all-csv', [\App\Http\Controllers\PayrollController::class, 'exportCsv'])->name('export.all-csv');

      Route::get('/{payslip}', [\App\Http\Controllers\PayrollController::class, 'show'])->name('show');
      Route::post('/{payslip}/approve', [\App\Http\Controllers\PayrollController::class, 'approve'])->name('approve');
      Route::post('/{payslip}/paid', [\App\Http\Controllers\PayrollController::class, 'markAsPaid'])->name('paid');
      Route::delete('/{payslip}', [\App\Http\Controllers\PayrollController::class, 'destroy'])->name('destroy');
});

// PROCUREMENT
Route::middleware(['auth'])->prefix('procurement')->name('procurement.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ProcurementController::class, 'index'])->name('index');
    Route::get('/requests', [\App\Http\Controllers\ProcurementController::class, 'requests'])->name('requests');
    Route::get('/requests/create', [\App\Http\Controllers\ProcurementController::class, 'createRequest'])->name('requests.create');
    Route::post('/requests', [\App\Http\Controllers\ProcurementController::class, 'storeRequest'])->name('requests.store');
    Route::get('/orders', [\App\Http\Controllers\ProcurementController::class, 'orders'])->name('orders');
    Route::get('/orders/create', [\App\Http\Controllers\ProcurementController::class, 'createOrder'])->name('orders.create');
    Route::post('/orders', [\App\Http\Controllers\ProcurementController::class, 'storeOrder'])->name('orders.store');
    Route::get('/suppliers', [\App\Http\Controllers\ProcurementController::class, 'suppliers'])->name('suppliers');
    Route::get('/suppliers/create', [\App\Http\Controllers\ProcurementController::class, 'createSupplier'])->name('suppliers.create');
    Route::post('/suppliers', [\App\Http\Controllers\ProcurementController::class, 'storeSupplier'])->name('suppliers.store');
});

// AUDIT LOGS
Route::middleware(['auth'])->prefix('audit-logs')->name('audit-logs.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('index');
    Route::get('/clear', [\App\Http\Controllers\AuditLogController::class, 'clear'])->name('clear');
});

// REPORTS — ZOTE
Route::middleware(['auth'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('index');
    Route::get('/employees', [\App\Http\Controllers\ReportController::class, 'employees'])->name('employees');
    Route::get('/attendance', [\App\Http\Controllers\ReportController::class, 'attendance'])->name('attendance');
    Route::get('/leaves', [\App\Http\Controllers\ReportController::class, 'leaves'])->name('leaves');
    Route::get('/projects', [\App\Http\Controllers\ReportController::class, 'projects'])->name('projects');
    Route::get('/tasks', [\App\Http\Controllers\ReportController::class, 'tasks'])->name('tasks');
    Route::get('/recruitment', [\App\Http\Controllers\ReportController::class, 'recruitment'])->name('recruitment');
    Route::get('/trainings', [\App\Http\Controllers\ReportController::class, 'trainings'])->name('trainings');
    Route::get('/budgets', [\App\Http\Controllers\ReportController::class, 'budgets'])->name('budgets');
    Route::get('/payroll', [\App\Http\Controllers\ReportController::class, 'payroll'])->name('payroll');
    Route::get('/performance', [\App\Http\Controllers\ReportController::class, 'performance'])->name('performance');
    Route::get('/documents', [\App\Http\Controllers\ReportController::class, 'documents'])->name('documents');
    Route::get('/location', [\App\Http\Controllers\ReportController::class, 'location'])->name('location');
    Route::get('/payment-vouchers', [\App\Http\Controllers\ReportController::class, 'paymentVouchers'])->name('payment-vouchers');
    Route::get('/receipts', [\App\Http\Controllers\ReportController::class, 'receipts'])->name('receipts');
    Route::get('/employees-by-region', [\App\Http\Controllers\ReportController::class, 'employeesByRegion'])->name('employees-by-region');
    Route::get('/attendance-by-region', [\App\Http\Controllers\ReportController::class, 'attendanceByRegion'])->name('attendance-by-region');
    Route::get('/budgets-by-region', [\App\Http\Controllers\ReportController::class, 'budgetsByRegion'])->name('budgets-by-region');
    Route::get('/projects-by-region', [\App\Http\Controllers\ReportController::class, 'projectsByRegion'])->name('projects-by-region');
});

// COMMUNICATION — ZOTE
Route::middleware(['auth'])->prefix('communication')->name('communication.')->group(function () {
    Route::get('/', [\App\Http\Controllers\CommunicationController::class, 'index'])->name('index');
    Route::get('/inbox', [\App\Http\Controllers\CommunicationController::class, 'inbox'])->name('inbox');
    Route::get('/sent', [\App\Http\Controllers\CommunicationController::class, 'sent'])->name('sent');
    Route::get('/drafts', [\App\Http\Controllers\CommunicationController::class, 'drafts'])->name('drafts');
    Route::get('/conversations', [\App\Http\Controllers\CommunicationController::class, 'conversations'])->name('conversations');
    Route::get('/message/create', [\App\Http\Controllers\CommunicationController::class, 'createMessage'])->name('message-create');
    Route::post('/message', [\App\Http\Controllers\CommunicationController::class, 'storeMessage'])->name('message-store');
    Route::post('/notifications/read-all', [\App\Http\Controllers\CommunicationController::class, 'markAllNotificationsRead'])->name('notifications-read-all');
    Route::get('/announcements', [\App\Http\Controllers\CommunicationController::class, 'announcements'])->name('announcements');
    Route::get('/announcements/create', [\App\Http\Controllers\CommunicationController::class, 'createAnnouncement'])->name('announcements-create');
    Route::post('/announcements', [\App\Http\Controllers\CommunicationController::class, 'storeAnnouncement'])->name('announcements-store');
    Route::get('/shared-files', [\App\Http\Controllers\CommunicationController::class, 'sharedFiles'])->name('shared-files');
    Route::get('/shared-files/create', [\App\Http\Controllers\CommunicationController::class, 'createSharedFile'])->name('shared-files-create');
    Route::post('/shared-files', [\App\Http\Controllers\CommunicationController::class, 'storeSharedFile'])->name('shared-files-store');
});

// COMMUNICATION GROUPS
Route::middleware(['auth'])->prefix('communication/groups')->name('communication.groups.')->group(function () {
    Route::get('/', [\App\Http\Controllers\GroupController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\GroupController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\GroupController::class, 'store'])->name('store');
});


// ============================================================
// TEMPORARY: Setup SAPTA Live Database — Ondoa baada ya kutumia
// ============================================================

// ============================================================
// TEMPORARY: Run sapta:setup kwenye Render
// ============================================================
Route::get('/run-sapta-setup', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('sapta:setup');
        return '<pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
// GET Logout (fallback kwa 419 issues)
Route::get('/logout', function () {
    \Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout.get');
// ⚠️ TEMPORARY — ONDOA BAADA YA KUTUMIA!
Route::get('/debug-run-migration-9x7k', function () {
    try {
        \Artisan::call('migrate', ['--force' => true]);
        $output = \Artisan::output();
        return "<h3>Migration Output:</h3><pre>" . $output . "</pre><br><a href='/employees/create'>Test Employee Create →</a>";
    } catch (\Exception $e) {
        return "❌ ERROR: " . $e->getMessage();
    }
});

Route::get('/debug-check-constraint-9x7k', function () {
    try {
        $result = \DB::select("SELECT conname, pg_get_constraintdef(oid) as definition FROM pg_constraint WHERE conrelid = 'employees'::regclass AND conname LIKE '%employment_type%'");
        $output = "<h3>Employment Type Constraints:</h3>";
        foreach ($result as $r) {
            $output .= "<b>" . $r->conname . ":</b><br>" . $r->definition . "<br><br>";
        }
        if (empty($result)) {
            $output .= "Hakuna constraints zilizopatikana.";
        }
        return $output;
    } catch (\Exception $e) {
        return "❌ ERROR: " . $e->getMessage();
    }
});
// ⚠️ TEMPORARY — ONDOA BAADA YA KUTUMIA!
Route::get('/debug-reset-ft-9x7k', function () {
    $user = \App\Models\User::where('username', 'LIKE', '%mkude%')
        ->orWhere('email', 'LIKE', '%soilanimals%')
        ->first();
    
    if (!$user) {
        $output = "<h2>User haipo!</h2>";
        $output .= "<p>Watumiaji wote:</p><ul>";
        foreach (\App\Models\User::all() as $u) {
            $output .= "<li>" . $u->id . " | " . $u->username . " | " . $u->email . "</li>";
        }
        $output .= "</ul>";
        return $output;
    }
    
    $user->password_hash = \Hash::make('Test@2026');
    $user->account_status = 'active';
    $user->is_first_login = false;
    $user->failed_login_attempts = 0;
    $user->locked_until = null;
    $user->save();
    
    return "✅ User: <b>" . $user->username . "</b><br>Email: " . $user->email . "<br>Password: <b>Test@2026</b><br><br><a href='/login'>Nenda Login →</a>";
});
// ⚠️ TEMPORARY — ONDOA BAADA YA KUTUMIA!
Route::get('/debug-check-profile-9x7k', function () {
    $user = \App\Models\User::where('username', 'mkude@sapta2024S')->first();
    
    if (!$user) {
        return "User haipo!";
    }
    
    $output = "<h2>Profile Image Check</h2>";
    $output .= "<b>Username:</b> " . $user->username . "<br>";
    $output .= "<b>profile_image (DB):</b> " . ($user->profile_image ?? 'NULL') . "<br>";
    $output .= "<b>FILESYSTEM_DISK:</b> " . config('filesystems.default') . "<br><br>";
    
    if ($user->profile_image) {
        $b2 = new \App\Services\B2StorageService();
        $contents = $b2->get($user->profile_image);
        $output .= "<b>File ipo B2:</b> " . ($contents ? 'YES (' . strlen($contents) . ' bytes)' : 'NO') . "<br>";
        
        // Jaribu URLs
        $output .= "<br><b>URLs:</b><br>";
        $output .= "Storage::url(): " . \Storage::disk(config('filesystems.default'))->url($user->profile_image) . "<br>";
        $output .= "asset(storage): " . asset('storage/' . $user->profile_image) . "<br>";
    }
    
    return $output;
});
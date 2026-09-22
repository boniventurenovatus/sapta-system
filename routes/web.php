<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\DepartmentController;
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
use App\Http\Controllers\PositionController;
use App\Http\Controllers\EmployeePositionController;
use App\Http\Controllers\OrganogramController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

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

// SEARCH
Route::get('/search', [SearchController::class, 'index'])->middleware('auth')->name('search');
Route::get('/search/live', [SearchController::class, 'search'])->middleware('auth')->name('search.live');

// MANAGEMENT
Route::resource('employees', EmployeeController::class)->middleware('role:super_admin,admin,director,admin_director,program_director,hr_manager,hr_officer,ceo,bod,manager');
Route::resource('organizations', OrganizationController::class);
Route::resource('departments', DepartmentController::class);
Route::resource('positions', PositionController::class)->middleware('role:super_admin,admin,director,admin_director,hr_manager,hr_officer,ceo,bod,manager');
Route::resource('employee-positions', EmployeePositionController::class)->middleware('role:super_admin,admin,hr_manager,hr_officer');

// ORGANOGRAM
Route::get('/organogram', [OrganogramController::class, 'index'])->middleware('auth')->name('organogram.index');

// SETTINGS
Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->middleware('role:super_admin,admin')->name('settings.index');
Route::put('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->middleware('role:super_admin,admin')->name('settings.update');

// NOTIFICATIONS
Route::middleware(['auth', 'account.status'])->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/clear-all', [\App\Http\Controllers\NotificationController::class, 'clearAll'])->name('notifications.clear-all');
});

// HR
Route::resource('attendances', AttendanceController::class)->middleware('role:super_admin,admin,director,manager,staff');
Route::resource('leave-requests', LeaveRequestController::class)->middleware('role:super_admin,admin,director,manager,staff');

// USER & RBAC
Route::resource('users', UserController::class)->middleware('role:super_admin,admin');

// EMPLOYEE STATUS ACTIONS
Route::post('employees/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->middleware('role:super_admin,admin,director,admin_director,hr_manager,hr_officer,ceo')->name('employees.deactivate');
Route::post('employees/{employee}/terminate', [EmployeeController::class, 'terminate'])->middleware('role:super_admin,admin,director,admin_director,hr_manager,hr_officer,ceo')->name('employees.terminate');
Route::post('employees/{employee}/activate', [EmployeeController::class, 'activate'])->middleware('role:super_admin,admin,director,admin_director,hr_manager,hr_officer,ceo')->name('employees.activate');
Route::post('employees/{employee}/suspend', [EmployeeController::class, 'suspend'])->middleware('role:super_admin,admin,director,admin_director,hr_manager,hr_officer,ceo')->name('employees.suspend');

// USER SUSPEND/ACTIVATE
Route::post('users/{user}/suspend', [UserController::class, 'suspend'])->middleware('role:super_admin,admin')->name('users.suspend');
Route::post('users/{user}/activate', [UserController::class, 'activate'])->middleware('role:super_admin,admin')->name('users.activate');
Route::resource('roles', RoleController::class)->middleware('role:super_admin,admin');
Route::resource('permissions', PermissionController::class)->middleware('role:super_admin,admin');

// PROFILE
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

// LANGUAGE
Route::get('/language/switch', [LanguageController::class, 'switch'])->name('language.switch');

// REPORTS
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('role:super_admin,admin,director,manager,finance_manager');
Route::get('/reports/employees', [ReportController::class, 'employees'])->name('reports.employees')->middleware('role:super_admin,admin,director,manager,finance_manager');
Route::get('/reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance')->middleware('role:super_admin,admin,director,manager,finance_manager');
Route::get('/reports/projects', [ReportController::class, 'projects'])->name('reports.projects')->middleware('role:super_admin,admin,director,manager,finance_manager');
Route::get('/reports/tasks', [ReportController::class, 'tasks'])->name('reports.tasks')->middleware('role:super_admin,admin,director,manager,finance_manager');
Route::get('/reports/leaves', [ReportController::class, 'leaves'])->name('reports.leaves')->middleware('role:super_admin,admin,director,manager,finance_manager');

// TASKS
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::post('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');

// PROJECTS
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
Route::post('/projects/{project}/update-progress', [ProjectController::class, 'updateProgress'])->name('projects.update-progress');




// AUDIT LOGS
Route::middleware(['auth', 'role:super_admin,admin,director,bod'])->group(function () {
    Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{auditLog}', [\App\Http\Controllers\AuditLogController::class, 'show'])->name('audit-logs.show');
    Route::delete('/audit-logs/{auditLog}', [\App\Http\Controllers\AuditLogController::class, 'destroy'])->name('audit-logs.destroy');
    Route::delete('/audit-logs/clear/all', [\App\Http\Controllers\AuditLogController::class, 'clear'])->name('audit-logs.clear');
});


// MY PAYSLIPS (Staff & Everyone)
Route::get('/my-payslips', [App\Http\Controllers\PayrollController::class, 'myPayslips'])
    ->middleware('auth')
    ->name('my-payslips');
// PAYROLL
Route::middleware(['auth', 'role:super_admin,admin,finance_manager,accountant,director'])->group(function () {
    Route::get('/payroll', [\App\Http\Controllers\PayrollController::class, 'index'])->name('payroll.index')->middleware('role:super_admin,admin,director,finance_manager,accountant,ceo,bod');
    Route::post('/payroll/generate', [\App\Http\Controllers\PayrollController::class, 'generate'])->name('payroll.generate');
    Route::get('/payroll/salaries', [\App\Http\Controllers\PayrollController::class, 'salaries'])->name('payroll.salaries');
    Route::get('/payroll/salaries/create', [\App\Http\Controllers\PayrollController::class, 'createSalary'])->name('payroll.salaries.create');
    Route::post('/payroll/salaries', [\App\Http\Controllers\PayrollController::class, 'storeSalary'])->name('payroll.salaries.store');
    Route::get('/payroll/salaries/{salary}/edit', [\App\Http\Controllers\PayrollController::class, 'editSalary'])->name('payroll.salaries.edit');
    Route::put('/payroll/salaries/{salary}', [\App\Http\Controllers\PayrollController::class, 'updateSalary'])->name('payroll.salaries.update');
    Route::delete('/payroll/salaries/{salary}', [\App\Http\Controllers\PayrollController::class, 'destroySalary'])->name('payroll.salaries.destroy');
    Route::get('/payroll/{payslip}', [\App\Http\Controllers\PayrollController::class, 'show'])->name('payroll.show');
    Route::post('/payroll/{payslip}/approve', [\App\Http\Controllers\PayrollController::class, 'approve'])->name('payroll.approve');
    Route::post('/payroll/{payslip}/paid', [\App\Http\Controllers\PayrollController::class, 'markAsPaid'])->name('payroll.paid');
    Route::delete('/payroll/{payslip}', [\App\Http\Controllers\PayrollController::class, 'destroy'])->name('payroll.destroy');
});

// PAYMENT VOUCHERS
Route::middleware(['auth', 'account.status'])->group(function () {
    Route::get('/payment-vouchers', [\App\Http\Controllers\PaymentVoucherController::class, 'index'])->name('payment-vouchers.index');
    Route::get('/payment-vouchers/create', [\App\Http\Controllers\PaymentVoucherController::class, 'create'])->name('payment-vouchers.create');
    Route::post('/payment-vouchers', [\App\Http\Controllers\PaymentVoucherController::class, 'store'])->name('payment-vouchers.store');
    Route::get('/payment-vouchers/{paymentVoucher}', [\App\Http\Controllers\PaymentVoucherController::class, 'show'])->name('payment-vouchers.show');
    Route::get('/payment-vouchers/{paymentVoucher}/edit', [\App\Http\Controllers\PaymentVoucherController::class, 'edit'])->name('payment-vouchers.edit');
    Route::put('/payment-vouchers/{paymentVoucher}', [\App\Http\Controllers\PaymentVoucherController::class, 'update'])->name('payment-vouchers.update');
    Route::delete('/payment-vouchers/{paymentVoucher}', [\App\Http\Controllers\PaymentVoucherController::class, 'destroy'])->name('payment-vouchers.destroy');
    Route::post('/payment-vouchers/{paymentVoucher}/approve', [\App\Http\Controllers\PaymentVoucherController::class, 'approve'])->name('payment-vouchers.approve');
    Route::post('/payment-vouchers/{paymentVoucher}/reject', [\App\Http\Controllers\PaymentVoucherController::class, 'reject'])->name('payment-vouchers.reject');
    Route::post('/payment-vouchers/{paymentVoucher}/paid', [\App\Http\Controllers\PaymentVoucherController::class, 'markAsPaid'])->name('payment-vouchers.paid');
});

// PAYMENT VOUCHER EXPORTS
Route::middleware(['auth', 'account.status'])->group(function () {
    Route::get('/payment-vouchers/{paymentVoucher}/print', [\App\Http\Controllers\PaymentVoucherController::class, 'printPdf'])->name('payment-vouchers.print');
    Route::get('/payment-vouchers/export/all/csv', [\App\Http\Controllers\PaymentVoucherController::class, 'exportCsv'])->name('payment-vouchers.export.all-csv');
    Route::get('/payment-vouchers/export/all/excel', [\App\Http\Controllers\PaymentVoucherController::class, 'exportExcel'])->name('payment-vouchers.export.all-excel');
});


// RECEIPTS
Route::middleware(['auth', 'account.status'])->group(function () {
    Route::get('/receipts', [\App\Http\Controllers\ReceiptController::class, 'index'])->name('receipts.index');
    Route::get('/receipts/create', [\App\Http\Controllers\ReceiptController::class, 'create'])->name('receipts.create');
    Route::post('/receipts', [\App\Http\Controllers\ReceiptController::class, 'store'])->name('receipts.store');
    Route::get('/receipts/{receipt}', [\App\Http\Controllers\ReceiptController::class, 'show'])->name('receipts.show');
    Route::get('/receipts/{receipt}/edit', [\App\Http\Controllers\ReceiptController::class, 'edit'])->name('receipts.edit');
    Route::put('/receipts/{receipt}', [\App\Http\Controllers\ReceiptController::class, 'update'])->name('receipts.update');
    Route::delete('/receipts/{receipt}', [\App\Http\Controllers\ReceiptController::class, 'destroy'])->name('receipts.destroy');
    Route::post('/receipts/{receipt}/confirm', [\App\Http\Controllers\ReceiptController::class, 'confirm'])->name('receipts.confirm');
    Route::post('/receipts/{receipt}/cancel', [\App\Http\Controllers\ReceiptController::class, 'cancel'])->name('receipts.cancel');
    Route::get('/receipts/{receipt}/print', [\App\Http\Controllers\ReceiptController::class, 'printPdf'])->name('receipts.print');
    Route::get('/receipts/export/all/csv', [\App\Http\Controllers\ReceiptController::class, 'exportCsv'])->name('receipts.export.all-csv');
    Route::get('/receipts/export/all/excel', [\App\Http\Controllers\ReceiptController::class, 'exportExcel'])->name('receipts.export.all-excel');
});


// BUDGETS
Route::middleware(['auth', 'account.status'])->group(function () {
    Route::get('/budgets', [\App\Http\Controllers\BudgetController::class, 'index'])->name('budgets.index');
    Route::get('/budgets/create', [\App\Http\Controllers\BudgetController::class, 'create'])->name('budgets.create');
    Route::post('/budgets', [\App\Http\Controllers\BudgetController::class, 'store'])->name('budgets.store');
    Route::get('/budgets/{budget}', [\App\Http\Controllers\BudgetController::class, 'show'])->name('budgets.show');
    Route::get('/budgets/{budget}/edit', [\App\Http\Controllers\BudgetController::class, 'edit'])->name('budgets.edit');
    Route::put('/budgets/{budget}', [\App\Http\Controllers\BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('/budgets/{budget}', [\App\Http\Controllers\BudgetController::class, 'destroy'])->name('budgets.destroy');
    Route::post('/budgets/{budget}/approve', [\App\Http\Controllers\BudgetController::class, 'approve'])->name('budgets.approve');
    Route::post('/budgets/{budget}/activate', [\App\Http\Controllers\BudgetController::class, 'activate'])->name('budgets.activate');
    Route::post('/budgets/{budget}/close', [\App\Http\Controllers\BudgetController::class, 'close'])->name('budgets.close');
    Route::get('/budgets/{budget}/print', [\App\Http\Controllers\BudgetController::class, 'printPdf'])->name('budgets.print');
    Route::get('/budgets/export/all/csv', [\App\Http\Controllers\BudgetController::class, 'exportCsv'])->name('budgets.export.all-csv');
    Route::get('/budgets/export/all/excel', [\App\Http\Controllers\BudgetController::class, 'exportExcel'])->name('budgets.export.all-excel');
});


// PERFORMANCE REVIEWS
Route::middleware(['auth', 'account.status'])->group(function () {
    Route::get('/performance-reviews', [\App\Http\Controllers\PerformanceReviewController::class, 'index'])->name('performance-reviews.index');
    Route::get('/performance-reviews/create', [\App\Http\Controllers\PerformanceReviewController::class, 'create'])->name('performance-reviews.create');
    Route::post('/performance-reviews', [\App\Http\Controllers\PerformanceReviewController::class, 'store'])->name('performance-reviews.store');
    Route::get('/performance-reviews/{performanceReview}', [\App\Http\Controllers\PerformanceReviewController::class, 'show'])->name('performance-reviews.show');
    Route::get('/performance-reviews/{performanceReview}/edit', [\App\Http\Controllers\PerformanceReviewController::class, 'edit'])->name('performance-reviews.edit');
    Route::put('/performance-reviews/{performanceReview}', [\App\Http\Controllers\PerformanceReviewController::class, 'update'])->name('performance-reviews.update');
    Route::delete('/performance-reviews/{performanceReview}', [\App\Http\Controllers\PerformanceReviewController::class, 'destroy'])->name('performance-reviews.destroy');
    Route::post('/performance-reviews/{performanceReview}/submit', [\App\Http\Controllers\PerformanceReviewController::class, 'submit'])->name('performance-reviews.submit');
    Route::post('/performance-reviews/{performanceReview}/approve', [\App\Http\Controllers\PerformanceReviewController::class, 'approve'])->name('performance-reviews.approve');
    Route::post('/performance-reviews/{performanceReview}/reject', [\App\Http\Controllers\PerformanceReviewController::class, 'reject'])->name('performance-reviews.reject');
    Route::post('/performance-reviews/{performanceReview}/kpi', [\App\Http\Controllers\PerformanceReviewController::class, 'addKpi'])->name('performance-reviews.kpi.add');
    Route::delete('/performance-reviews/kpi/{kpi}', [\App\Http\Controllers\PerformanceReviewController::class, 'deleteKpi'])->name('performance-reviews.kpi.delete');
    Route::get('/performance-reviews/{performanceReview}/print', [\App\Http\Controllers\PerformanceReviewController::class, 'printPdf'])->name('performance-reviews.print');
    Route::get('/performance-reviews/export/all/csv', [\App\Http\Controllers\PerformanceReviewController::class, 'exportCsv'])->name('performance-reviews.export.all-csv');
    Route::get('/performance-reviews/export/all/excel', [\App\Http\Controllers\PerformanceReviewController::class, 'exportExcel'])->name('performance-reviews.export.all-excel');
});


// DOCUMENTS
Route::middleware(['auth', 'account.status'])->group(function () {
    Route::get('/documents', [\App\Http\Controllers\DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [\App\Http\Controllers\DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [\App\Http\Controllers\DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [\App\Http\Controllers\DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/edit', [\App\Http\Controllers\DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [\App\Http\Controllers\DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [\App\Http\Controllers\DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{document}/download', [\App\Http\Controllers\DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/preview', [\App\Http\Controllers\DocumentController::class, 'preview'])->name('documents.preview');
    Route::post('/documents/{document}/approve', [\App\Http\Controllers\DocumentController::class, 'approve'])->name('documents.approve');
    Route::post('/documents/{document}/archive', [\App\Http\Controllers\DocumentController::class, 'archive'])->name('documents.archive');
    Route::get('/documents/export/all/csv', [\App\Http\Controllers\DocumentController::class, 'exportCsv'])->name('documents.export.all-csv');
});



// TRAININGS
Route::middleware(['auth', 'account.status'])->group(function () {
    Route::get('/trainings', [\App\Http\Controllers\TrainingController::class, 'index'])->name('trainings.index');
    Route::get('/trainings/create', [\App\Http\Controllers\TrainingController::class, 'create'])->name('trainings.create');
    Route::post('/trainings', [\App\Http\Controllers\TrainingController::class, 'store'])->name('trainings.store');
    Route::get('/trainings/{training}', [\App\Http\Controllers\TrainingController::class, 'show'])->name('trainings.show');
    Route::get('/trainings/{training}/edit', [\App\Http\Controllers\TrainingController::class, 'edit'])->name('trainings.edit');
    Route::put('/trainings/{training}', [\App\Http\Controllers\TrainingController::class, 'update'])->name('trainings.update');
    Route::delete('/trainings/{training}', [\App\Http\Controllers\TrainingController::class, 'destroy'])->name('trainings.destroy');
    Route::post('/trainings/{training}/enroll', [\App\Http\Controllers\TrainingController::class, 'enroll'])->name('trainings.enroll');
    Route::put('/trainings/enrollment/{enrollment}', [\App\Http\Controllers\TrainingController::class, 'updateEnrollment'])->name('trainings.enrollment.update');
    Route::delete('/trainings/enrollment/{enrollment}', [\App\Http\Controllers\TrainingController::class, 'removeEnrollment'])->name('trainings.enrollment.remove');
    Route::get('/trainings/enrollment/{enrollment}/certificate', [\App\Http\Controllers\TrainingController::class, 'printCertificate'])->name('trainings.certificate');
    Route::get('/trainings/export/all/csv', [\App\Http\Controllers\TrainingController::class, 'exportCsv'])->name('trainings.export.all-csv');
});


// RECRUITMENT
Route::middleware(['auth', 'account.status'])->group(function () {
    Route::get('/recruitment', [\App\Http\Controllers\RecruitmentController::class, 'index'])->name('recruitment.index');
    Route::get('/recruitment/create', [\App\Http\Controllers\RecruitmentController::class, 'create'])->name('recruitment.create');
    Route::post('/recruitment', [\App\Http\Controllers\RecruitmentController::class, 'store'])->name('recruitment.store');
    Route::get('/recruitment/{job}', [\App\Http\Controllers\RecruitmentController::class, 'show'])->name('recruitment.show');
    Route::get('/recruitment/{job}/edit', [\App\Http\Controllers\RecruitmentController::class, 'edit'])->name('recruitment.edit');
    Route::put('/recruitment/{job}', [\App\Http\Controllers\RecruitmentController::class, 'update'])->name('recruitment.update');
    Route::delete('/recruitment/{job}', [\App\Http\Controllers\RecruitmentController::class, 'destroy'])->name('recruitment.destroy');
    Route::post('/recruitment/{job}/open', [\App\Http\Controllers\RecruitmentController::class, 'open'])->name('recruitment.open');
    Route::post('/recruitment/{job}/close', [\App\Http\Controllers\RecruitmentController::class, 'close'])->name('recruitment.close');
    Route::post('/recruitment/{job}/application', [\App\Http\Controllers\RecruitmentController::class, 'addApplication'])->name('recruitment.application.add');
    Route::put('/recruitment/application/{application}', [\App\Http\Controllers\RecruitmentController::class, 'updateApplication'])->name('recruitment.application.update');
    Route::get('/recruitment/application/{application}/resume', [\App\Http\Controllers\RecruitmentController::class, 'downloadResume'])->name('recruitment.application.resume');
    Route::delete('/recruitment/application/{application}', [\App\Http\Controllers\RecruitmentController::class, 'removeApplication'])->name('recruitment.application.remove');
    Route::get('/recruitment/export/all/csv', [\App\Http\Controllers\RecruitmentController::class, 'exportCsv'])->name('recruitment.export.all-csv');
});


// REPORTS (EXTENDED)
Route::middleware(['auth', 'role:super_admin,admin,director,manager,finance_manager'])->prefix('reports')->group(function () {
    Route::get('/payroll', [\App\Http\Controllers\ReportController::class, 'payroll'])->name('reports.payroll');
    Route::get('/payment-vouchers', [\App\Http\Controllers\ReportController::class, 'paymentVouchers'])->name('reports.payment-vouchers');
    Route::get('/receipts', [\App\Http\Controllers\ReportController::class, 'receipts'])->name('reports.receipts');
    Route::get('/budgets', [\App\Http\Controllers\ReportController::class, 'budgets'])->name('reports.budgets');
    Route::get('/trainings', [\App\Http\Controllers\ReportController::class, 'trainings'])->name('reports.trainings');
    Route::get('/recruitment', [\App\Http\Controllers\ReportController::class, 'recruitment'])->name('reports.recruitment');
    Route::get('/performance', [\App\Http\Controllers\ReportController::class, 'performance'])->name('reports.performance');
    Route::get('/documents', [\App\Http\Controllers\ReportController::class, 'documents'])->name('reports.documents');

    // EXPORTS
    Route::get('/employees/export/csv', [\App\Http\Controllers\ReportController::class, 'exportEmployeesCsv'])->name('reports.employees.export');
    Route::get('/payroll/export/csv', [\App\Http\Controllers\ReportController::class, 'exportPayrollCsv'])->name('reports.payroll.export');
    Route::get('/vouchers/export/csv', [\App\Http\Controllers\ReportController::class, 'exportVouchersCsv'])->name('reports.vouchers.export');
    Route::get('/receipts/export/csv', [\App\Http\Controllers\ReportController::class, 'exportReceiptsCsv'])->name('reports.receipts.export');
    Route::get('/budgets/export/csv', [\App\Http\Controllers\ReportController::class, 'exportBudgetsCsv'])->name('reports.budgets.export');
    Route::get('/trainings/export/csv', [\App\Http\Controllers\ReportController::class, 'exportTrainingsCsv'])->name('reports.trainings.export');
    Route::get('/recruitment/export/csv', [\App\Http\Controllers\ReportController::class, 'exportRecruitmentCsv'])->name('reports.recruitment.export');
    Route::get('/performance/export/csv', [\App\Http\Controllers\ReportController::class, 'exportPerformanceCsv'])->name('reports.performance.export');
    Route::get('/documents/export/csv', [\App\Http\Controllers\ReportController::class, 'exportDocumentsCsv'])->name('reports.documents.export');
});



// LOCATION (AJAX)
Route::middleware(['auth', 'account.status'])->group(function () {
    Route::get('/location/districts', [\App\Http\Controllers\LocationController::class, 'getDistricts'])->name('location.districts');
    Route::get('/location/wards', [\App\Http\Controllers\LocationController::class, 'getWards'])->name('location.wards');
});

// LOCATION REPORTS
Route::middleware(['auth', 'account.status'])->group(function () {
    Route::get('/reports/location', [App\Http\Controllers\ReportController::class, 'locationIndex'])->name('reports.location');
    Route::get('/reports/employees-by-region', [App\Http\Controllers\ReportController::class, 'employeesByRegion'])->name('reports.employees-by-region');
    Route::get('/reports/employees-by-region/export/csv', [App\Http\Controllers\ReportController::class, 'exportEmployeesByRegionCsv'])->name('reports.employees-by-region.export');
    Route::get('/reports/projects-by-region', [App\Http\Controllers\ReportController::class, 'projectsByRegion'])->name('reports.projects-by-region');
    Route::get('/reports/attendance-by-region', [App\Http\Controllers\ReportController::class, 'attendanceByRegion'])->name('reports.attendance-by-region');
    Route::get('/reports/budgets-by-region', [App\Http\Controllers\ReportController::class, 'budgetsByRegion'])->name('reports.budgets-by-region');
});
// ================================================================
// MY WORK MODULE
// ================================================================
Route::middleware('auth')->prefix('my-work')->name('my-work.')->group(function () {
    Route::get('/', [\App\Http\Controllers\MyWorkController::class, 'index'])->name('index');
    Route::get('/drafts', [\App\Http\Controllers\MyWorkController::class, 'drafts'])->name('drafts');
    Route::get('/tasks', [\App\Http\Controllers\MyWorkController::class, 'tasks'])->name('tasks');
    Route::get('/approvals', [\App\Http\Controllers\MyWorkController::class, 'approvals'])->name('approvals');
});

// ================================================================
// PROCUREMENT MODULE
// ================================================================
Route::middleware('auth')->prefix('procurement')->name('procurement.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ProcurementController::class, 'index'])->name('index');
    Route::get('/requests', [\App\Http\Controllers\ProcurementController::class, 'requests'])->name('requests');
    Route::get('/orders', [\App\Http\Controllers\ProcurementController::class, 'orders'])->name('orders');
    Route::get('/suppliers', [\App\Http\Controllers\ProcurementController::class, 'suppliers'])->name('suppliers');
});

// ================================================================
// COMMUNICATION MODULE
// ================================================================
Route::middleware('auth')->prefix('communication')->name('communication.')->group(function () {
    Route::get('/', [\App\Http\Controllers\CommunicationController::class, 'index'])->name('index');
    Route::get('/inbox', [\App\Http\Controllers\CommunicationController::class, 'inbox'])->name('inbox');
    Route::get('/sent', [\App\Http\Controllers\CommunicationController::class, 'sent'])->name('sent');
    Route::get('/drafts', [\App\Http\Controllers\CommunicationController::class, 'drafts'])->name('drafts');
    Route::get('/conversations', [\App\Http\Controllers\CommunicationController::class, 'conversations'])->name('conversations');
    Route::get('/groups', [\App\Http\Controllers\CommunicationController::class, 'groups'])->name('groups');
    Route::get('/announcements', [\App\Http\Controllers\CommunicationController::class, 'announcements'])->name('announcements');
    Route::get('/shared-files', [\App\Http\Controllers\CommunicationController::class, 'sharedFiles'])->name('shared-files');
});
// ================================================================
// PROCUREMENT MODULE
// ================================================================
Route::middleware('auth')->prefix('procurement')->name('procurement.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ProcurementController::class, 'index'])->name('index');
    
    // Requests
    Route::get('/requests', [\App\Http\Controllers\ProcurementController::class, 'requests'])->name('requests');
    Route::get('/requests/create', [\App\Http\Controllers\ProcurementController::class, 'requestsCreate'])->name('requests.create');
    Route::post('/requests', [\App\Http\Controllers\ProcurementController::class, 'requestsStore'])->name('requests.store');
    Route::get('/requests/{id}', [\App\Http\Controllers\ProcurementController::class, 'requestsShow'])->name('requests.show');
    Route::get('/requests/{id}/pdf', [\App\Http\Controllers\ProcurementController::class, 'requestsPdf'])->name('requests.pdf');
    Route::get('/requests/{id}/print', [\App\Http\Controllers\ProcurementController::class, 'requestsPrint'])->name('requests.print');
    Route::patch('/requests/{id}/approve', [\App\Http\Controllers\ProcurementController::class, 'requestsApprove'])->name('requests.approve');
    Route::patch('/requests/{id}/reject', [\App\Http\Controllers\ProcurementController::class, 'requestsReject'])->name('requests.reject');
    Route::delete('/requests/{id}', [\App\Http\Controllers\ProcurementController::class, 'requestsDestroy'])->name('requests.destroy');
    
    // Orders
    Route::get('/orders', [\App\Http\Controllers\ProcurementController::class, 'orders'])->name('orders');
    Route::get('/orders/create', [\App\Http\Controllers\ProcurementController::class, 'ordersCreate'])->name('orders.create');
    Route::post('/orders', [\App\Http\Controllers\ProcurementController::class, 'ordersStore'])->name('orders.store');
    Route::patch('/orders/{id}/approve', [\App\Http\Controllers\ProcurementController::class, 'ordersApprove'])->name('orders.approve');
    Route::patch('/orders/{id}/deliver', [\App\Http\Controllers\ProcurementController::class, 'ordersDeliver'])->name('orders.deliver');
    Route::delete('/orders/{id}', [\App\Http\Controllers\ProcurementController::class, 'ordersDestroy'])->name('orders.destroy');
    
    // Suppliers
    Route::get('/suppliers', [\App\Http\Controllers\ProcurementController::class, 'suppliers'])->name('suppliers');
    Route::get('/suppliers/create', [\App\Http\Controllers\ProcurementController::class, 'suppliersCreate'])->name('suppliers.create');
    Route::post('/suppliers', [\App\Http\Controllers\ProcurementController::class, 'suppliersStore'])->name('suppliers.store');
    Route::delete('/suppliers/{id}', [\App\Http\Controllers\ProcurementController::class, 'suppliersDestroy'])->name('suppliers.destroy');
});
// ================================================================
// WORKFLOW ENGINE ROUTES
// ================================================================
Route::middleware(['auth', 'account.status'])->group(function () {
    // Drafts
    Route::get('/my-work/drafts', [\App\Http\Controllers\DraftController::class, 'index'])->name('my-work.drafts');
    Route::get('/drafts/{draft}', [\App\Http\Controllers\DraftController::class, 'show'])->name('drafts.show');
    Route::get('/drafts/{draft}/edit', [\App\Http\Controllers\DraftController::class, 'edit'])->name('drafts.edit');
    Route::delete('/drafts/{draft}', [\App\Http\Controllers\DraftController::class, 'destroy'])->name('drafts.destroy');
    Route::post('/drafts/{draft}/submit', [\App\Http\Controllers\DraftController::class, 'submitDraft'])->name('drafts.submit');

    // Submissions
    Route::get('/my-work/submissions', [\App\Http\Controllers\SubmissionController::class, 'index'])->name('my-work.submissions');
    Route::get('/submissions/{submission}', [\App\Http\Controllers\SubmissionController::class, 'show'])->name('submissions.show');

    // Approvals
    Route::get('/my-work/approvals', [\App\Http\Controllers\ApprovalController::class, 'index'])->name('my-work.approvals');
    Route::get('/approvals/{submission}', [\App\Http\Controllers\ApprovalController::class, 'show'])->name('approvals.show');
    Route::post('/approvals/{submission}/approve', [\App\Http\Controllers\ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{submission}/return', [\App\Http\Controllers\ApprovalController::class, 'returnForCorrection'])->name('approvals.return');
});
// ================================================================
// PAYMENT VOUCHERS — Full Workflow (POST only kwa simplicity)
// ================================================================

// ================================================================
// PAYMENT VOUCHERS — Full Workflow
// ================================================================
Route::middleware('auth')->prefix('payment-vouchers')->name('payment-vouchers.')->group(function () {
    Route::get('/', [\App\Http\Controllers\PaymentVoucherController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\PaymentVoucherController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\PaymentVoucherController::class, 'store'])->name('store');
    Route::get('/{paymentVoucher}', [\App\Http\Controllers\PaymentVoucherController::class, 'show'])->name('show');
    Route::get('/{paymentVoucher}/edit', [\App\Http\Controllers\PaymentVoucherController::class, 'edit'])->name('edit');
    Route::put('/{paymentVoucher}', [\App\Http\Controllers\PaymentVoucherController::class, 'update'])->name('update');
    Route::delete('/{paymentVoucher}', [\App\Http\Controllers\PaymentVoucherController::class, 'destroy'])->name('destroy');
    
    // Workflow Actions
    Route::post('/{paymentVoucher}/approve', [\App\Http\Controllers\PaymentVoucherController::class, 'approve'])->name('approve');
    Route::post('/{paymentVoucher}/return', [\App\Http\Controllers\PaymentVoucherController::class, 'returnVoucher'])->name('return');
    Route::post('/{paymentVoucher}/paid', [\App\Http\Controllers\PaymentVoucherController::class, 'markPaid'])->name('paid');

        // NEW: Check & Authorize workflow
        Route::post('/{paymentVoucher}/check', [\App\Http\Controllers\PaymentVoucherController::class, 'check'])
            ->name('check');
        Route::post('/{paymentVoucher}/authorize', [\App\Http\Controllers\PaymentVoucherController::class, 'authorizeVoucher'])
            ->name('authorize');
    
    // PDF + Print
    Route::get('/{paymentVoucher}/pdf', [\App\Http\Controllers\PaymentVoucherController::class, 'downloadPdf'])->name('pdf');
    Route::get('/{paymentVoucher}/print', [\App\Http\Controllers\PaymentVoucherController::class, 'print'])->name('print');
});
// ================================================================
// COMMUNICATION MODULE — Full
// ================================================================
Route::middleware('auth')->prefix('communication')->name('communication.')->group(function () {
    
    // Dashboard
    Route::get('/', [\App\Http\Controllers\CommunicationController::class, 'index'])->name('index');

    // Inbox / Sent / Drafts
    Route::get('/inbox', [\App\Http\Controllers\CommunicationController::class, 'inbox'])->name('inbox');
    Route::get('/sent', [\App\Http\Controllers\CommunicationController::class, 'sent'])->name('sent');
    Route::get('/drafts', [\App\Http\Controllers\CommunicationController::class, 'drafts'])->name('drafts');

    // Messages
    Route::get('/message/create', [\App\Http\Controllers\CommunicationController::class, 'createMessage'])->name('message-create');
    Route::post('/message', [\App\Http\Controllers\CommunicationController::class, 'storeMessage'])->name('message-store');
    Route::get('/message/{message}', [\App\Http\Controllers\CommunicationController::class, 'showMessage'])->name('message-show');
    Route::post('/message/{message}/unread', [\App\Http\Controllers\CommunicationController::class, 'markUnread'])->name('message-unread');
    Route::delete('/message/{message}', [\App\Http\Controllers\CommunicationController::class, 'destroyMessage'])->name('message-destroy');

    // Announcements
    Route::get('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements');
    Route::get('/announcements/create', [\App\Http\Controllers\AnnouncementController::class, 'create'])->name('announcements-create');
    Route::post('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements-store');
    Route::get('/announcements/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'show'])->name('announcements-show');
    Route::delete('/announcements/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('announcements-destroy');

    // Groups
    
    
    
    
    

    // Shared Files
    Route::get('/shared-files', [\App\Http\Controllers\SharedFileController::class, 'index'])->name('shared-files');
    Route::get('/shared-files/create', [\App\Http\Controllers\SharedFileController::class, 'create'])->name('shared-files-create');
    Route::post('/shared-files', [\App\Http\Controllers\SharedFileController::class, 'store'])->name('shared-files-store');
    Route::get('/shared-files/{sharedFile}', [\App\Http\Controllers\SharedFileController::class, 'show'])->name('shared-files-show');
    Route::get('/shared-files/{sharedFile}/download', [\App\Http\Controllers\SharedFileController::class, 'download'])->name('shared-files-download');
    Route::delete('/shared-files/{sharedFile}', [\App\Http\Controllers\SharedFileController::class, 'destroy'])->name('shared-files-destroy');
});
// ================================================================
// GROUPS — Full Chat System
// ================================================================

// ================================================================
// GROUPS — Full Chat System (route names: communication.groups.*)
// ================================================================
Route::middleware('auth')->prefix('communication/groups')->name('communication.groups.')->group(function () {
    Route::get('/', [\App\Http\Controllers\GroupController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\GroupController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\GroupController::class, 'store'])->name('store');
    Route::get('/{group}', [\App\Http\Controllers\GroupController::class, 'show'])->name('show');
    Route::post('/{group}/message', [\App\Http\Controllers\GroupController::class, 'sendMessage'])->name('send-message');
    Route::post('/{group}/add-member', [\App\Http\Controllers\GroupController::class, 'addMember'])->name('add-member');
    Route::delete('/{group}/remove-member/{user}', [\App\Http\Controllers\GroupController::class, 'removeMember'])->name('remove-member');
    Route::delete('/{group}', [\App\Http\Controllers\GroupController::class, 'destroy'])->name('destroy');
});
// ================================================================
// NOTIFICATIONS — Full
// ================================================================
Route::middleware('auth')->prefix('communication/notifications')->name('communication.')->group(function () {
    Route::get('/', [\App\Http\Controllers\CommunicationController::class, 'notifications'])->name('notifications');
    Route::post('/{id}/read', [\App\Http\Controllers\CommunicationController::class, 'markNotificationRead'])->name('notifications-read');
    Route::post('/read-all', [\App\Http\Controllers\CommunicationController::class, 'markAllNotificationsRead'])->name('notifications-read-all');
});
// ================================================================
// LEAVE REQUESTS — Full Workflow
// ================================================================
// ================================================================
// EXPENSE CLAIMS — Full Workflow
// ================================================================
Route::middleware('auth')->prefix('expense-claims')->name('expense-claims.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ExpenseClaimController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\ExpenseClaimController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\ExpenseClaimController::class, 'store'])->name('store');
    Route::get('/{expenseClaim}', [\App\Http\Controllers\ExpenseClaimController::class, 'show'])->name('show');
    Route::get('/{expenseClaim}/edit', [\App\Http\Controllers\ExpenseClaimController::class, 'edit'])->name('edit');
    Route::put('/{expenseClaim}', [\App\Http\Controllers\ExpenseClaimController::class, 'update'])->name('update');
    Route::delete('/{expenseClaim}', [\App\Http\Controllers\ExpenseClaimController::class, 'destroy'])->name('destroy');
    
    // Workflow Actions
    Route::post('/{expenseClaim}/approve', [\App\Http\Controllers\ExpenseClaimController::class, 'approve'])->name('approve');
    Route::post('/{expenseClaim}/return', [\App\Http\Controllers\ExpenseClaimController::class, 'returnClaim'])->name('return');
    Route::post('/{expenseClaim}/paid', [\App\Http\Controllers\ExpenseClaimController::class, 'markPaid'])->name('paid');
    
    // PDF + Print
    Route::get('/{expenseClaim}/pdf', [\App\Http\Controllers\ExpenseClaimController::class, 'downloadPdf'])->name('pdf');
    Route::get('/{expenseClaim}/print', [\App\Http\Controllers\ExpenseClaimController::class, 'print'])->name('print');
});
// ================================================================
// PROCUREMENT REQUESTS — Full Workflow
// ================================================================
Route::middleware('auth')->prefix('procurement-requests')->name('procurement-requests.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ProcurementRequestController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\ProcurementRequestController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\ProcurementRequestController::class, 'store'])->name('store');
    Route::get('/{procurementRequest}', [\App\Http\Controllers\ProcurementRequestController::class, 'show'])->name('show');
    Route::get('/{procurementRequest}/edit', [\App\Http\Controllers\ProcurementRequestController::class, 'edit'])->name('edit');
    Route::put('/{procurementRequest}', [\App\Http\Controllers\ProcurementRequestController::class, 'update'])->name('update');
    Route::delete('/{procurementRequest}', [\App\Http\Controllers\ProcurementRequestController::class, 'destroy'])->name('destroy');
    
    // Workflow
    Route::post('/{procurementRequest}/approve', [\App\Http\Controllers\ProcurementRequestController::class, 'approve'])->name('approve');
    Route::post('/{procurementRequest}/reject', [\App\Http\Controllers\ProcurementRequestController::class, 'reject'])->name('reject');
    Route::post('/{procurementRequest}/complete', [\App\Http\Controllers\ProcurementRequestController::class, 'complete'])->name('complete');
    
    // PDF + Print
    Route::get('/{procurementRequest}/pdf', [\App\Http\Controllers\ProcurementRequestController::class, 'downloadPdf'])->name('pdf');
    Route::get('/{procurementRequest}/print', [\App\Http\Controllers\ProcurementRequestController::class, 'print'])->name('print');
});
// ================================================================
// DOCUMENTS — Full Management
// ================================================================
Route::middleware('auth')->prefix('documents')->name('documents.')->group(function () {
    Route::get('/', [\App\Http\Controllers\DocumentController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\DocumentController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\DocumentController::class, 'store'])->name('store');
    Route::get('/{document}', [\App\Http\Controllers\DocumentController::class, 'show'])->name('show');
    Route::get('/{document}/edit', [\App\Http\Controllers\DocumentController::class, 'edit'])->name('edit');
    Route::put('/{document}', [\App\Http\Controllers\DocumentController::class, 'update'])->name('update');
    Route::delete('/{document}', [\App\Http\Controllers\DocumentController::class, 'destroy'])->name('destroy');
    
    Route::get('/{document}/download', [\App\Http\Controllers\DocumentController::class, 'download'])->name('download');
    Route::get('/{document}/preview', [\App\Http\Controllers\DocumentController::class, 'preview'])->name('preview');
    Route::post('/{document}/replace', [\App\Http\Controllers\DocumentController::class, 'replace'])->name('replace');
});
// ============================================================
// ACTIVITY LOGS + ROLES
// ============================================================
Route::middleware(['auth'])->group(function () {
    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('activity-logs/{log}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::get('users/{user}/activity', [ActivityLogController::class, 'userActivity'])->name('users.activity');

    // Roles
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show');
});

// EMPLOYEE CREDENTIALS — Admin anaona
Route::get('employees/{employee}/credentials', [App\Http\Controllers\EmployeeController::class, 'credentials'])
    ->name('employees.credentials')
    ->middleware('role:super_admin,admin,director,admin_director,hr_manager,hr_officer');
// RESET PASSWORD — Admin
Route::post('employees/{employee}/reset-password', [App\Http\Controllers\EmployeeController::class, 'resetPassword'])
    ->name('employees.reset-password')
    ->middleware('role:super_admin,admin,hr_manager,hr_officer');
// ============================================================
// PLACEHOLDER ROUTES — Kwa sidebar
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', function () { return view('placeholder', ['title' => 'Attendance']); })->name('attendance.index');
    Route::get('/leave-requests', function () { return view('placeholder', ['title' => 'Leave Requests']); })->name('leave-requests.index');
    Route::get('/trainings', function () { return view('placeholder', ['title' => 'Trainings']); })->name('trainings.index');
    Route::get('/recruitment', function () { return view('placeholder', ['title' => 'Recruitment']); })->name('recruitment.index');
    Route::get('/budgets', function () { return view('placeholder', ['title' => 'Budgets']); })->name('budgets.index');
    Route::get('/receipts', function () { return view('placeholder', ['title' => 'Receipts']); })->name('receipts.index');
    Route::get('/payment-vouchers', function () { return view('placeholder', ['title' => 'Payment Vouchers']); })->name('payment-vouchers.index');
    Route::get('/payroll', function () { return view('placeholder', ['title' => 'Payroll']); })->name('payroll.index');
    Route::get('/projects', function () { return view('placeholder', ['title' => 'Projects']); })->name('projects.index');
    Route::get('/tasks', function () { return view('placeholder', ['title' => 'Tasks']); })->name('tasks.index');
    Route::get('/documents', function () { return view('placeholder', ['title' => 'Documents']); })->name('documents.index');
    Route::get('/inbox', function () { return view('placeholder', ['title' => 'Inbox']); })->name('inbox.index');
    Route::get('/notifications', function () { return view('placeholder', ['title' => 'Notifications']); })->name('notifications.index');
    Route::get('/reports', function () { return view('placeholder', ['title' => 'Reports']); })->name('reports.index');
});
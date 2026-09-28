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

Route::get('/debug/list-users', function() {
    $users = \App\Models\User::with('roles')->orderBy('id')->get();

    $result = [];
    foreach ($users as $u) {
        $result[] = [
            'id' => $u->id,
            'username' => $u->username,
            'email' => $u->email,
            'account_status' => $u->account_status,
            'roles' => $u->roles->pluck('name')->implode(', '),
        ];
    }

    return response()->json([
        'total' => count($result),
        'users' => $result,
    ], 200, [], JSON_PRETTY_PRINT);
})->name('debug.list-users');

Route::get('/debug/change-password', function(\Illuminate\Http\Request $request) {
    $username = $request->query('username');
    $newPassword = $request->query('password');

    if (!$username || !$newPassword) {
        return response()->json([
            'error' => 'username na password zinahitajika',
            'example' => '/debug/change-password?username=hr.manager&password=Hr@2026!',
        ], 400);
    }

    $user = \App\Models\User::where('username', $username)->first();
    if (!$user) {
        return response()->json(['error' => "User '$username' haipo"], 404);
    }

    $user->forceFill([
        'password_hash' => \Hash::make($newPassword),
        'account_status' => 'active',
        'is_first_login' => false,
        'first_password_expires_at' => null,
        'credentials_expires_at' => null,
        'failed_login_attempts' => 0,
        'locked_until' => null,
        'password_changed_at' => now(),
    ])->save();

    return response()->json([
        'success' => true,
        'username' => $username,
        'new_password' => $newPassword,
        'hash_check' => \Hash::check($newPassword, $user->fresh()->password_hash),
        'roles' => $user->roles->pluck('name')->implode(', '),
    ], 200, [], JSON_PRETTY_PRINT);
})->name('debug.change-password');
Route::get('/debug/reset-all-users', function() {
    $users = [
        'superadmin' => 'Sapta@2026!',
        'admin' => 'Admin@2026!',
        'hr.manager' => 'Hr@2026!',
        'hr.officer' => 'HrOfficer@2026!',
        'finance.manager' => 'Finance@2026!',
        'accountant.test' => 'Accountant@2026!',
        'procurement.manager' => 'Procurement@2026!',
        'project.manager' => 'ProjectManager@2026!',
        'project.officer' => 'ProjectOfficer@2026!',
        'field.trainer' => 'FieldTrainer@2026!',
        'partnership.manager' => 'Partnership@2026!',
        'meal.manager' => 'Meal@2026!',
        'meal.officer' => 'MealOfficer@2026!',
        'research.officer' => 'Research@2026!',
        'community.manager' => 'Community@2026!',
        'ict.manager' => 'ICT@2026!',
        'manager' => 'Manager@2026!',
        'staff' => 'Staff@2026!',
        'director' => 'Director@2026!',
        'ceo' => 'CEO@2026!',
        'bod' => 'BOD@2026!',
        'admin.director' => 'AdminDirector@2026!',
        'program.director' => 'Program@2026!',
    ];

    $results = [];
    $success = 0;
    $failed = 0;

    foreach ($users as $username => $password) {
        $user = \App\Models\User::where('username', $username)->first();

        if (!$user) {
            $results[] = [
                'username' => $username,
                'status' => 'SKIP',
            ];
            $failed++;
            continue;
        }

        try {
            $user->forceFill([
                'password_hash' => \Hash::make($password),
                'account_status' => 'active',
                'is_first_login' => false,
                'first_password_expires_at' => null,
                'credentials_expires_at' => null,
                'failed_login_attempts' => 0,
                'locked_until' => null,
                'password_changed_at' => now(),
            ])->save();

            $results[] = [
                'username' => $username,
                'password' => $password,
                'status' => 'OK',
                'hash_check' => \Hash::check($password, $user->fresh()->password_hash),
            ];
            $success++;
        } catch (\Exception $e) {
            $results[] = [
                'username' => $username,
                'status' => 'FAIL',
                'error' => $e->getMessage(),
            ];
            $failed++;
        }
    }

    return response()->json([
        'success' => true,
        'total' => count($users),
        'success_count' => $success,
        'failed_count' => $failed,
        'results' => $results,
    ], 200, [], JSON_PRETTY_PRINT);
})->name('debug.reset-all-users');
Route::get('/debug/check-dashboards', function() {
    $dashboards = [
        'admin' => 'admin',
        'director' => 'director',
        'executive' => 'executive',
        'hr' => 'hr',
        'finance' => 'finance',
        'manager' => 'manager',
        'staff' => 'staff',
        'ict' => 'ict',
        'meal' => 'meal',
        'program' => 'program',
    ];

    $controller = app(\App\Http\Controllers\DashboardController::class);
    $results = [];

    foreach ($dashboards as $method => $name) {
        try {
            $response = $controller->$method();
            $html = $response->render();
            $results[$name] = [
                'status' => 'OK',
                'length' => strlen($html),
            ];
        } catch (\Exception $e) {
            $results[$name] = [
                'status' => 'FAIL',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }
    }

    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
})->name('debug.check-dashboards');
Route::get('/debug/check-pages', function() {
    $pages = [
        // Management
        'employees' => ['EmployeeController', 'index'],
        'employees/create' => ['EmployeeController', 'create'],
        'departments' => ['DepartmentController', 'index'],
        'positions' => ['PositionController', 'index'],
        'organizations' => ['OrganizationController', 'index'],

        // HR
        'attendances' => ['AttendanceController', 'index'],
        'leave-requests' => ['LeaveRequestController', 'index'],
        'trainings' => ['TrainingController', 'index'],
        'recruitment' => ['RecruitmentController', 'index'],

        // Finance
        'budgets' => ['BudgetController', 'index'],
        'receipts' => ['ReceiptController', 'index'],
        'payment-vouchers' => ['PaymentVoucherController', 'index'],
        'payroll' => ['PayrollController', 'index'],

        // Projects
        'projects' => ['ProjectController', 'index'],
        'tasks' => ['TaskController', 'index'],

        // RBAC
        'users' => ['UserController', 'index'],
        'roles' => ['RoleController', 'index'],
        'permissions' => ['PermissionController', 'index'],

        // Reports
        'reports' => ['ReportController', 'index'],

        // Settings
        'settings' => ['SettingController', 'index'],

        // Other
        'activity-logs' => ['ActivityLogController', 'index'],
        'audit-logs' => ['AuditLogController', 'index'],
        'notifications' => ['NotificationController', 'index'],
    ];

    $results = [];

    foreach ($pages as $route => $config) {
        $controllerName = $config[0];
        $method = $config[1];

        try {
            $controllerClass = "App\\Http\\Controllers\\" . $controllerName;

            if (!class_exists($controllerClass)) {
                $results[$route] = [
                    'status' => 'FAIL',
                    'error' => "Controller $controllerClass haipo",
                ];
                continue;
            }

            $controller = app($controllerClass);

            if (!method_exists($controller, $method)) {
                $results[$route] = [
                    'status' => 'FAIL',
                    'error' => "Method $method haipo kwenye $controllerName",
                ];
                continue;
            }

            $reflection = new \ReflectionMethod($controller, $method);
            $params = $reflection->getParameters();

            if (count($params) === 0) {
                $response = $controller->$method();
            } else {
                $results[$route] = [
                    'status' => 'SKIP',
                    'message' => 'Inahitaji parameters',
                ];
                continue;
            }

            if (is_object($response) && method_exists($response, 'render')) {
                $html = $response->render();
                $results[$route] = [
                    'status' => 'OK',
                    'length' => strlen($html),
                ];
            } else {
                $results[$route] = [
                    'status' => 'OK',
                    'type' => gettype($response),
                ];
            }

        } catch (\Exception $e) {
            $results[$route] = [
                'status' => 'FAIL',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }
    }

    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
})->name('debug.check-pages');
Route::get('/debug/check-pages-v2', function() {
    $pages = [
        'employees' => ['EmployeeController', 'index'],
        'departments' => ['DepartmentController', 'index'],
        'positions' => ['PositionController', 'index'],
        'organizations' => ['OrganizationController', 'index'],
        'attendances' => ['AttendanceController', 'index'],
        'leave-requests' => ['LeaveRequestController', 'index'],
        'trainings' => ['TrainingController', 'index'],
        'recruitment' => ['RecruitmentController', 'index'],
        'budgets' => ['BudgetController', 'index'],
        'receipts' => ['ReceiptController', 'index'],
        'payment-vouchers' => ['PaymentVoucherController', 'index'],
        'payroll' => ['PayrollController', 'index'],
        'projects' => ['ProjectController', 'index'],
        'tasks' => ['TaskController', 'index'],
        'users' => ['UserController', 'index'],
        'roles' => ['RoleController', 'index'],
        'permissions' => ['PermissionController', 'index'],
        'reports' => ['ReportController', 'index'],
        'settings' => ['SettingController', 'index'],
        'activity-logs' => ['ActivityLogController', 'index'],
        'audit-logs' => ['AuditLogController', 'index'],
        'notifications' => ['NotificationController', 'index'],
    ];

    $results = [];

    foreach ($pages as $route => $config) {
        $controllerName = $config[0];
        $method = $config[1];

        // 1. Angalia kama controller ipo
        $controllerClass = "App\\Http\\Controllers\\" . $controllerName;
        if (!class_exists($controllerClass)) {
            $results[$route] = [
                'status' => 'FAIL',
                'error' => "Controller $controllerName haipo",
            ];
            continue;
        }

        // 2. Angalia kama method ipo
        try {
            $controller = app($controllerClass);
        } catch (\Exception $e) {
            $results[$route] = [
                'status' => 'FAIL',
                'error' => "Controller $controllerName ina kosa: " . $e->getMessage(),
            ];
            continue;
        }

        if (!method_exists($controller, $method)) {
            $results[$route] = [
                'status' => 'FAIL',
                'error' => "Method $method haipo kwenye $controllerName",
            ];
            continue;
        }

        // 3. Jaribu ku-render
        try {
            $reflection = new \ReflectionMethod($controller, $method);
            $params = $reflection->getParameters();

            if (count($params) > 0) {
                $results[$route] = [
                    'status' => 'SKIP',
                    'message' => 'Inahitaji parameters',
                ];
                continue;
            }

            $response = $controller->$method();

            if (is_object($response) && method_exists($response, 'render')) {
                $html = $response->render();
                $results[$route] = [
                    'status' => 'OK',
                    'length' => strlen($html),
                ];
            } else {
                $results[$route] = [
                    'status' => 'OK',
                    'type' => gettype($response),
                ];
            }

        } catch (\Exception $e) {
            $results[$route] = [
                'status' => 'FAIL',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }
    }

    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
})->name('debug.check-pages-v2');
Route::get('/debug/check-pages-v3', function() {
    $pages = [
        'employees' => ['EmployeeController', 'index'],
        'departments' => ['DepartmentController', 'index'],
        'positions' => ['PositionController', 'index'],
        'organizations' => ['OrganizationController', 'index'],
        'attendances' => ['AttendanceController', 'index'],
        'leave-requests' => ['LeaveRequestController', 'index'],
        'trainings' => ['TrainingController', 'index'],
        'recruitment' => ['RecruitmentController', 'index'],
        'budgets' => ['BudgetController', 'index'],
        'receipts' => ['ReceiptController', 'index'],
        'payment-vouchers' => ['PaymentVoucherController', 'index'],
        'payroll' => ['PayrollController', 'index'],
        'projects' => ['ProjectController', 'index'],
        'tasks' => ['TaskController', 'index'],
        'users' => ['UserController', 'index'],
        'roles' => ['RoleController', 'index'],
        'permissions' => ['PermissionController', 'index'],
        'reports' => ['ReportController', 'index'],
        'settings' => ['SettingController', 'index'],
        'activity-logs' => ['ActivityLogController', 'index'],
        'audit-logs' => ['AuditLogController', 'index'],
        'notifications' => ['NotificationController', 'index'],
    ];

    $results = [];

    foreach ($pages as $route => $config) {
        $controllerName = $config[0];
        $method = $config[1];

        // 1. Angalia kama controller ipo
        $controllerClass = "App\\Http\\Controllers\\" . $controllerName;

        if (!class_exists($controllerClass)) {
            $results[$route] = [
                'step' => 'class_exists',
                'status' => 'FAIL',
                'error' => "Controller $controllerName haipo",
            ];
            continue;
        }

        // 2. Jaribu ku-resolve controller
        try {
            $controller = app($controllerClass);
        } catch (\Throwable $e) {
            $results[$route] = [
                'step' => 'app_resolve',
                'status' => 'FAIL',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
            continue;
        }

        // 3. Angalia kama method ipo
        if (!method_exists($controller, $method)) {
            $results[$route] = [
                'step' => 'method_exists',
                'status' => 'FAIL',
                'error' => "Method $method haipo kwenye $controllerName",
            ];
            continue;
        }

        // 4. Jaribu ku-render
        try {
            $reflection = new \ReflectionMethod($controller, $method);
            $params = $reflection->getParameters();

            if (count($params) > 0) {
                $results[$route] = [
                    'step' => 'parameters',
                    'status' => 'SKIP',
                    'message' => 'Inahitaji parameters',
                ];
                continue;
            }

            $response = $controller->$method();

            if (is_object($response) && method_exists($response, 'render')) {
                $html = $response->render();
                $results[$route] = [
                    'step' => 'render',
                    'status' => 'OK',
                    'length' => strlen($html),
                ];
            } else {
                $results[$route] = [
                    'step' => 'response',
                    'status' => 'OK',
                    'type' => gettype($response),
                ];
            }

        } catch (\Throwable $e) {
            $results[$route] = [
                'step' => 'render',
                'status' => 'FAIL',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }
    }

    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
})->name('debug.check-pages-v3');
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
Route::get('/debug/check-pages-v4', function() {
    $pages = [
        // Communication
        'communication.inbox' => ['CommunicationController', 'inbox'],
        'notifications.index' => ['NotificationController', 'index'],
        'my-work.index' => ['MyWorkController', 'index'],

        // Reports
        'reports.index' => ['ReportController', 'index'],
        'reports.employees' => ['ReportController', 'employees'],
        'reports.attendance' => ['ReportController', 'attendance'],
        'reports.projects' => ['ReportController', 'projects'],
        'reports.tasks' => ['ReportController', 'tasks'],
        'reports.leaves' => ['ReportController', 'leaves'],
        'reports.trainings' => ['ReportController', 'trainings'],
        'reports.budgets' => ['ReportController', 'budgets'],

        // Location
        'location.regions' => ['LocationController', 'regions'],
        'location.districts' => ['LocationController', 'districts'],
        'location.wards' => ['LocationController', 'wards'],

        // Organogram
        'organogram.index' => ['OrganogramController', 'index'],

        // Search
        'search' => ['SearchController', 'index'],

        // Settings
        'settings.index' => ['SettingController', 'index'],

        // Activity/Audit
        'activity-logs.index' => ['ActivityLogController', 'index'],
        'audit-logs.index' => ['AuditLogController', 'index'],
    ];

    $results = [];

    foreach ($pages as $route => $config) {
        $controllerName = $config[0];
        $method = $config[1];

        $controllerClass = "App\\Http\\Controllers\\" . $controllerName;

        if (!class_exists($controllerClass)) {
            $results[$route] = [
                'step' => 'class_exists',
                'status' => 'FAIL',
                'error' => "Controller $controllerName haipo",
            ];
            continue;
        }

        try {
            $controller = app($controllerClass);
        } catch (\Throwable $e) {
            $results[$route] = [
                'step' => 'app_resolve',
                'status' => 'FAIL',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
            continue;
        }

        if (!method_exists($controller, $method)) {
            $results[$route] = [
                'step' => 'method_exists',
                'status' => 'FAIL',
                'error' => "Method $method haipo kwenye $controllerName",
            ];
            continue;
        }

        try {
            $reflection = new \ReflectionMethod($controller, $method);
            $params = $reflection->getParameters();

            if (count($params) > 0) {
                $results[$route] = [
                    'step' => 'parameters',
                    'status' => 'SKIP',
                    'message' => 'Inahitaji parameters',
                ];
                continue;
            }

            $response = $controller->$method();

            if (is_object($response) && method_exists($response, 'render')) {
                $html = $response->render();
                $results[$route] = [
                    'step' => 'render',
                    'status' => 'OK',
                    'length' => strlen($html),
                ];
            } else {
                $results[$route] = [
                    'step' => 'response',
                    'status' => 'OK',
                    'type' => gettype($response),
                ];
            }

        } catch (\Throwable $e) {
            $results[$route] = [
                'step' => 'render',
                'status' => 'FAIL',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }
    }

    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
})->name('debug.check-pages-v4');
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
Route::get('/debug/list-routes', function() {
    $routes = \Route::getRoutes();
    $result = [];

    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'debug') !== false) continue;

        $result[] = [
            'method' => implode('|', $route->methods()),
            'uri' => $uri,
            'name' => $route->getName(),
            'action' => $route->getActionName(),
        ];
    }

    return response()->json($result, 200, [], JSON_PRETTY_PRINT);
})->name('debug.list-routes');
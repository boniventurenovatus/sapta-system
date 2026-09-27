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
Route::get('/debug/change-password', function() {
    $user = \App\Models\User::where('username', 'superadmin')->first();

    if (!$user) {
        return response()->json(['error' => 'superadmin HAIPO']);
    }

    $newPassword = 'Sapta@2026!';

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

    $fresh = $user->fresh();

    return response()->json([
        'success' => true,
        'message' => 'Password imebadilishwa',
        'new_password' => $newPassword,
        'hash_check_new' => \Hash::check($newPassword, $fresh->password_hash),
        'account_status' => $fresh->account_status,
        'failed_login_attempts' => $fresh->failed_login_attempts,
    ], 200, [], JSON_PRETTY_PRINT);
})->name('debug.change-password');
Route::get('/debug/deep-auth', function() {
    $user = \App\Models\User::where('username', 'superadmin')->first();

    if (!$user) {
        return response()->json(['error' => 'superadmin HAIPO']);
    }

    // Jaribu passwords zote
    $passwords = ['Sapta@2026!', 'Sapta@2025!', 'Sapta@2024!', 'password', 'admin'];
    $results = [];

    foreach ($passwords as $pw) {
        $results[$pw] = \Hash::check($pw, $user->password_hash);
    }

    // Angalia kama Auth inatumia column sahihi
    return response()->json([
        'user' => [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'password_hash_prefix' => substr($user->password_hash, 0, 30),
            'password_hash_length' => strlen($user->password_hash),
        ],
        'auth_config' => [
            'getAuthPassword' => $user->getAuthPassword(),
            'getAuthPasswordName' => $user->getAuthPasswordName(),
            'getAuthIdentifierName' => $user->getAuthIdentifierName(),
        ],
        'hash_checks' => $results,
        'config' => [
            'auth_default_guard' => config('auth.defaults.guard'),
            'auth_provider' => config('auth.guards.web.provider'),
            'auth_model' => config('auth.providers.users.model'),
            'auth_table' => config('auth.providers.users.table'),
        ],
    ], 200, [], JSON_PRETTY_PRINT);
})->name('debug.deep-auth');
Route::get('/debug/test-login', function() {
    $results = [];

    // Test 1: Hash::check
    $user = \App\Models\User::where('username', 'superadmin')->first();
    $results['hash_check'] = \Hash::check('Sapta@2026!', $user->password_hash);

    // Test 2: Auth::attempt
    $results['auth_attempt'] = \Auth::attempt([
        'username' => 'superadmin',
        'password' => 'Sapta@2026!',
    ]);

    // Test 3: Auth::validate
    $results['auth_validate'] = \Auth::validate([
        'username' => 'superadmin',
        'password' => 'Sapta@2026!',
    ]);

    // Test 4: Angalia kama user amefungwa
    $results['locked_until'] = $user->locked_until;
    $results['failed_login_attempts'] = $user->failed_login_attempts;
    $results['account_status'] = $user->account_status;

    // Test 5: RateLimiter
    $key = 'login:superadmin|' . request()->ip();
    $results['rate_limiter_key'] = $key;
    $results['rate_limiter_attempts'] = \RateLimiter::attempts($key);
    $results['rate_limiter_too_many'] = \RateLimiter::tooManyAttempts($key, 5);

    // Test 6: Session config
    $results['session_driver'] = config('session.driver');
    $results['session_secure'] = config('session.secure');

    // Test 7: AuthenticatedSessionController ilikuwa deployed?
    $controllerPath = app_path('Http/Controllers/Auth/AuthenticatedSessionController.php');
    $results['controller_exists'] = file_exists($controllerPath);
    if (file_exists($controllerPath)) {
        $content = file_get_contents($controllerPath);
        $results['controller_has_rate_limiter'] = strpos($content, 'RateLimiter') !== false;
        $results['controller_has_lockout'] = strpos($content, 'locked_until') !== false;
    }

    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
})->name('debug.test-login');
Route::get('/debug/controller-code', function() {
    $path = app_path('Http/Controllers/Auth/AuthenticatedSessionController.php');
    $content = file_get_contents($path);

    return response($content, 200, ['Content-Type' => 'text/plain']);
})->name('debug.controller-code');
Route::get('/debug/superadmin-status', function() {
    $user = \App\Models\User::where('username', 'superadmin')->first();

    if (!$user) {
        return response()->json(['error' => 'superadmin HAIPO']);
    }

    // Jaribu passwords zote
    $passwords = ['Sapta@2027!', 'Sapta@2026!', 'Sapta@2025!', 'Sapta@2024!', 'password', 'admin'];
    $results = [];
    foreach ($passwords as $pw) {
        $results[$pw] = \Hash::check($pw, $user->password_hash);
    }

    return response()->json([
        'user' => [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'account_status' => $user->account_status,
            'is_first_login' => $user->is_first_login,
            'first_password_expires_at' => $user->first_password_expires_at,
            'failed_login_attempts' => $user->failed_login_attempts,
            'locked_until' => $user->locked_until,
            'password_hash_prefix' => substr($user->password_hash, 0, 30),
        ],
        'employee' => $user->employee ? [
            'id' => $user->employee->id,
            'employment_status' => $user->employee->employment_status,
        ] : null,
        'hash_checks' => $results,
    ], 200, [], JSON_PRETTY_PRINT);
})->name('debug.superadmin-status');
Route::get('/debug/test-store', function() {
    try {
        // Test 1: DB connection
        $dbName = \DB::connection()->getDatabaseName();

        // Test 2: Table exists
        $tableExists = \Schema::hasTable('payment_vouchers');
        $itemsTableExists = \Schema::hasTable('payment_voucher_items');

        // Test 3: Columns
        $columns = \Schema::getColumnListing('payment_vouchers');

        // Test 4: Model fillable
        $model = new \App\Models\PaymentVoucher();
        $fillable = $model->getFillable();

        // Test 5: Jaribu kuunda voucher
        try {
            $voucher = \App\Models\PaymentVoucher::create([
                'voucher_number' => 'TEST-' . time(),
                'trans_no' => 'PY99999',
                'batch' => '1/1',
                'payee_name' => 'Test Payee',
                'payee_type' => 'individual',
                'currency' => 'TZS',
                'amount' => 100,
                'payment_date' => now(),
                'status' => 'draft',
                'created_by' => 1,
            ]);
            $createResult = 'OK - ID: ' . $voucher->id;
        } catch (\Exception $e) {
            $createResult = 'FAIL: ' . $e->getMessage();
        }

        return response()->json([
            'db_name' => $dbName,
            'table_payment_vouchers' => $tableExists ? 'IPO' : 'HAIPO',
            'table_payment_voucher_items' => $itemsTableExists ? 'IPO' : 'HAIPO',
            'columns' => $columns,
            'fillable' => $fillable,
            'create_test' => $createResult,
        ], 200, [], JSON_PRETTY_PRINT);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->name('debug.test-store');
Route::get('/debug/run-migrations', function() {
    try {
        // Endesha migrations
        \Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Artisan::output();

        // Angalia columns baada ya migration
        $columns = \Schema::getColumnListing('payment_vouchers');

        return response()->json([
            'success' => true,
            'migrate_output' => $migrateOutput,
            'columns_after' => $columns,
            'has_trans_no' => in_array('trans_no', $columns),
            'has_batch' => in_array('batch', $columns),
            'has_payee_pobox' => in_array('payee_pobox', $columns),
            'has_payee_contact' => in_array('payee_contact', $columns),
            'has_bank' => in_array('bank', $columns),
            'has_cheque_number' => in_array('cheque_number', $columns),
            'has_mode' => in_array('mode', $columns),
            'has_prepared_by_id' => in_array('prepared_by_id', $columns),
            'has_checked_by_id' => in_array('checked_by_id', $columns),
            'has_authorized_by_id' => in_array('authorized_by_id', $columns),
        ], 200, [], JSON_PRETTY_PRINT);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->name('debug.run-migrations');

Route::get('/debug/fix-status-constraint', function() {
    try {
        $driver = \DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            \DB::statement("ALTER TABLE payment_vouchers DROP CONSTRAINT IF EXISTS payment_vouchers_status_check");
            \DB::statement("ALTER TABLE payment_vouchers ADD CONSTRAINT payment_vouchers_status_check CHECK (status IN ('draft', 'pending_approval', 'checked', 'authorized', 'approved', 'returned', 'paid', 'cancelled', 'completed'))");

            $constraints = \DB::select("
                SELECT conname, pg_get_constraintdef(oid) as definition
                FROM pg_constraint
                WHERE conrelid = 'payment_vouchers'::regclass
                AND contype = 'c'
            ");

            return response()->json([
                'success' => true,
                'driver' => $driver,
                'message' => 'Check constraint imerekebishwa',
                'constraints' => $constraints,
            ], 200, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json([
                'success' => true,
                'driver' => $driver,
                'message' => 'MySQL — hakuna check constraint',
            ]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->name('debug.fix-status-constraint');
Route::get('/debug/test-store-exception', function() {
    try {
        // Simulate form data
        $data = [
            'payment_date' => now()->format('Y-m-d'),
            'trans_no' => 'PY' . rand(10000, 99999),
            'batch' => '1/1',
            'payee_name' => 'Test Payee',
            'payee_type' => 'individual',
            'payee_pobox' => 'P.O Box 123',
            'payee_contact' => '0712345678',
            'currency' => 'TZS',
            'mode' => 'transfer',
            'bank' => '1020',
            'cheque_number' => '',
            'prepared_by_id' => 1,
            'prepared_at' => now()->format('Y-m-d'),
            'checked_by_id' => null,
            'checked_at' => null,
            'authorized_by_id' => null,
            'authorized_at' => null,
            'received_by_name' => '',
            'items' => [
                [
                    'account_invoice_no' => 'INV-001',
                    'details' => 'Test item',
                    'amount' => 100,
                ],
            ],
            'amount' => 100,
            'description' => '',
            'notes' => '',
            'action' => 'draft',
        ];

        // Validate
        $validator = \Validator::make($data, (new \App\Http\Requests\StorePaymentVoucherRequest())->rules());
        if ($validator->fails()) {
            return response()->json([
                'step' => 'validation',
                'errors' => $validator->errors(),
            ], 422, [], JSON_PRETTY_PRINT);
        }

        // Generate trans_no + batch
        $svc = app(\App\Services\PaymentVoucherService::class);

        try {
            $transNo = !empty($data['trans_no']) ? $data['trans_no'] : $svc->generateTransNo();
        } catch (\Exception $e) {
            return response()->json([
                'step' => 'generateTransNo',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500, [], JSON_PRETTY_PRINT);
        }

        try {
            $batch = $svc->generateBatch();
        } catch (\Exception $e) {
            return response()->json([
                'step' => 'generateBatch',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500, [], JSON_PRETTY_PRINT);
        }

        // Create voucher
        try {
            $voucher = \App\Models\PaymentVoucher::create([
                'voucher_number' => \App\Models\PaymentVoucher::generateVoucherNumber(),
                'trans_no'       => $transNo,
                'batch'          => $batch,
                'payee_name'     => $data['payee_name'],
                'payee_type'     => $data['payee_type'],
                'payee_contact'  => $data['payee_contact'] ?? null,
                'currency'       => $data['currency'],
                'amount'         => 0,
                'mode'           => $data['mode'] ?? null,
                'payment_method' => $data['mode'] ?? null,
                'payment_date'   => $data['payment_date'],
                'created_by'     => 1,
                'status'         => 'draft',
                'payee_pobox'    => $data['payee_pobox'] ?? null,
                'bank'           => $data['bank'] ?? null,
                'cheque_number'  => $data['cheque_number'] ?? null,
                'prepared_by_id' => $data['prepared_by_id'] ?? 1,
                'prepared_at'    => $data['prepared_at'] ?? now(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'step' => 'PaymentVoucher::create',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500, [], JSON_PRETTY_PRINT);
        }

        // Create items
        try {
            foreach ($data['items'] as $index => $item) {
                $voucher->items()->create([
                    'account_invoice_no' => $item['account_invoice_no'] ?? null,
                    'details'            => $item['details'],
                    'amount'             => $item['amount'],
                    'sort_order'         => $index,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'step' => 'items()->create',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500, [], JSON_PRETTY_PRINT);
        }

        // Amount in words
        try {
            $svc->amountInWords(100, 'TZS');
        } catch (\Exception $e) {
            return response()->json([
                'step' => 'amountInWords',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500, [], JSON_PRETTY_PRINT);
        }

        return response()->json([
            'success' => true,
            'voucher_id' => $voucher->id,
            'message' => 'Store imefanikiwa',
        ], 200, [], JSON_PRETTY_PRINT);

    } catch (\Exception $e) {
        return response()->json([
            'step' => 'general',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->name('debug.test-store-exception');
Route::post('/debug/test-post', function(\Illuminate\Http\Request $request) {
    try {
        // Angalia CSRF
        $csrfToken = $request->input('_token');
        $sessionToken = session()->token();

        // Angalia auth
        $authCheck = auth()->check();
        $userId = auth()->id();

        // Angalia request data
        $all = $request->all();

        // Jaribu validate
        $validator = \Validator::make($request->all(), (new \App\Http\Requests\StorePaymentVoucherRequest())->rules());

        if ($validator->fails()) {
            return response()->json([
                'step' => 'validation',
                'errors' => $validator->errors(),
                'input' => $all,
            ], 422, [], JSON_PRETTY_PRINT);
        }

        return response()->json([
            'success' => true,
            'csrf_token_match' => $csrfToken === $sessionToken,
            'csrf_token' => substr($csrfToken ?? 'NULL', 0, 20),
            'session_token' => substr($sessionToken ?? 'NULL', 0, 20),
            'auth_check' => $authCheck,
            'user_id' => $userId,
            'input_keys' => array_keys($all),
        ], 200, [], JSON_PRETTY_PRINT);

    } catch (\Exception $e) {
        return response()->json([
            'step' => 'general',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])->name('debug.test-post');
Route::get('/debug/test-show', function() {
    try {
        $voucher = \App\Models\PaymentVoucher::with([
            'items',
            'creator',
            'approver',
            'preparedBy',
            'checkedBy',
            'authorizedBy',
        ])->find(1);

        if (!$voucher) {
            return response()->json(['error' => 'Voucher 1 haipo']);
        }

        // Jaribu ku-render view
        try {
            $html = view('payment-vouchers.show', ['voucher' => $voucher])->render();
            $viewResult = 'OK - Length: ' . strlen($html);
        } catch (\Exception $e) {
            $viewResult = 'FAIL: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine();
        }

        return response()->json([
            'success' => true,
            'voucher' => $voucher->toArray(),
            'view_render' => $viewResult,
        ], 200, [], JSON_PRETTY_PRINT);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->name('debug.test-show');
// ============================================================
// PAYMENT VOUCHER — PRINT & PDF
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('payment-vouchers/{payment_voucher}/print', [\App\Http\Controllers\PaymentVoucherController::class, 'print'])
        ->name('payment-vouchers.print');

    Route::get('payment-vouchers/{payment_voucher}/pdf', [\App\Http\Controllers\PaymentVoucherController::class, 'pdf'])
        ->name('payment-vouchers.pdf');
});
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
Route::get('/debug/test-methods', function() {
    $results = [];

    $voucher = \App\Models\PaymentVoucher::find(1);
    if (!$voucher) {
        return response()->json(['error' => 'Voucher 1 haipo']);
    }

    // Test check()
    try {
        $controller = app(\App\Http\Controllers\PaymentVoucherController::class);
        $reflection = new \ReflectionMethod($controller, 'check');
        $results['check'] = 'METHOD INAFANYA KAZI';
    } catch (\Exception $e) {
        $results['check'] = 'FAIL: ' . $e->getMessage();
    }

    // Test downloadPdf()
    try {
        $reflection = new \ReflectionMethod($controller, 'downloadPdf');
        $results['downloadPdf'] = 'METHOD INAFANYA KAZI';
    } catch (\Exception $e) {
        $results['downloadPdf'] = 'FAIL: ' . $e->getMessage();
    }

    // Test print()
    try {
        $reflection = new \ReflectionMethod($controller, 'print');
        $results['print'] = 'METHOD INAFANYA KAZI';
    } catch (\Exception $e) {
        $results['print'] = 'FAIL: ' . $e->getMessage();
    }

    // Test authorizeVoucher()
    try {
        $reflection = new \ReflectionMethod($controller, 'authorizeVoucher');
        $results['authorizeVoucher'] = 'METHOD INAFANYA KAZI';
    } catch (\Exception $e) {
        $results['authorizeVoucher'] = 'FAIL: ' . $e->getMessage();
    }

    // Test approve()
    try {
        $reflection = new \ReflectionMethod($controller, 'approve');
        $results['approve'] = 'METHOD INAFANYA KAZI';
    } catch (\Exception $e) {
        $results['approve'] = 'FAIL: ' . $e->getMessage();
    }

    // Test returnVoucher()
    try {
        $reflection = new \ReflectionMethod($controller, 'returnVoucher');
        $results['returnVoucher'] = 'METHOD INAFANYA KAZI';
    } catch (\Exception $e) {
        $results['returnVoucher'] = 'FAIL: ' . $e->getMessage();
    }

    // Test markPaid()
    try {
        $reflection = new \ReflectionMethod($controller, 'markPaid');
        $results['markPaid'] = 'METHOD INAFANYA KAZI';
    } catch (\Exception $e) {
        $results['markPaid'] = 'FAIL: ' . $e->getMessage();
    }

    // Test view za print
    $results['view_print'] = view()->exists('payment-vouchers.print') ? 'IPO' : 'HAIPO';
    $results['view_pdf_single'] = view()->exists('payment-vouchers.pdf.single') ? 'IPO' : 'HAIPO';

    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
})->name('debug.test-methods');
Route::get('/debug/test-check', function() {
    try {
        $voucher = \App\Models\PaymentVoucher::find(1);

        // Test check()
        try {
            $controller = app(\App\Http\Controllers\PaymentVoucherController::class);
            $request = \Illuminate\Http\Request::create('/payment-vouchers/1/check', 'POST');
            $request->setUserResolver(function() { return auth()->user() ?? \App\Models\User::find(1); });

            $result = $controller->check($request, $voucher);
            $checkResult = 'OK';
        } catch (\Exception $e) {
            $checkResult = 'FAIL: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine();
        }

        // Test downloadPdf()
        try {
            $controller = app(\App\Http\Controllers\PaymentVoucherController::class);
            $result = $controller->downloadPdf($voucher);
            $pdfResult = 'OK';
        } catch (\Exception $e) {
            $pdfResult = 'FAIL: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine();
        }

        // Test print()
        try {
            $controller = app(\App\Http\Controllers\PaymentVoucherController::class);
            $result = $controller->print($voucher);
            $printResult = 'OK';
        } catch (\Exception $e) {
            $printResult = 'FAIL: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine();
        }

        return response()->json([
            'check' => $checkResult,
            'downloadPdf' => $pdfResult,
            'print' => $printResult,
        ], 200, [], JSON_PRETTY_PRINT);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->name('debug.test-check');
Route::get('/debug/test-store-submit', function() {
    try {
        $data = [
            'payment_date' => now()->format('Y-m-d'),
            'trans_no' => 'PY' . rand(10000, 99999),
            'batch' => '1/1',
            'payee_name' => 'Test Payee',
            'payee_type' => 'individual',
            'payee_pobox' => 'P.O Box 123',
            'payee_contact' => '0712345678',
            'currency' => 'TZS',
            'mode' => 'transfer',
            'bank' => '1020',
            'cheque_number' => '',
            'prepared_by_id' => 1,
            'prepared_at' => now()->format('Y-m-d'),
            'checked_by_id' => null,
            'checked_at' => null,
            'authorized_by_id' => null,
            'authorized_at' => null,
            'received_by_name' => '',
            'items' => [
                [
                    'account_invoice_no' => 'INV-001',
                    'details' => 'Test item',
                    'amount' => 100,
                ],
            ],
            'amount' => 100,
            'description' => '',
            'notes' => '',
            'action' => 'submit',
        ];

        // Validate
        $validator = \Validator::make($data, (new \App\Http\Requests\StorePaymentVoucherRequest())->rules());
        if ($validator->fails()) {
            return response()->json([
                'step' => 'validation',
                'errors' => $validator->errors(),
            ], 422, [], JSON_PRETTY_PRINT);
        }

        // Generate trans_no + batch
        $svc = app(\App\Services\PaymentVoucherService::class);
        $transNo = !empty($data['trans_no']) ? $data['trans_no'] : $svc->generateTransNo();
        $batch = $svc->generateBatch();

        // Create voucher
        $action = $data['action'];
        try {
            $voucher = \App\Models\PaymentVoucher::create([
                'voucher_number' => \App\Models\PaymentVoucher::generateVoucherNumber(),
                'trans_no'       => $transNo,
                'batch'          => $batch,
                'payee_name'     => $data['payee_name'],
                'payee_type'     => $data['payee_type'],
                'payee_contact'  => $data['payee_contact'] ?? null,
                'currency'       => $data['currency'],
                'amount'         => 0,
                'mode'           => $data['mode'] ?? null,
                'payment_method' => $data['mode'] ?? null,
                'payment_date'   => $data['payment_date'],
                'created_by'     => 1,
                'status'         => $action === 'submit' ? 'pending_approval' : 'draft',
                'submitted_at'   => $action === 'submit' ? now() : null,
                'payee_pobox'    => $data['payee_pobox'] ?? null,
                'bank'           => $data['bank'] ?? null,
                'cheque_number'  => $data['cheque_number'] ?? null,
                'prepared_by_id' => $data['prepared_by_id'] ?? 1,
                'prepared_at'    => $data['prepared_at'] ?? now(),
            ]);

            return response()->json([
                'success' => true,
                'voucher_id' => $voucher->id,
                'status' => $voucher->status,
                'message' => 'Store imefanikiwa',
            ], 200, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return response()->json([
                'step' => 'PaymentVoucher::create',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500, [], JSON_PRETTY_PRINT);
        }

    } catch (\Exception $e) {
        return response()->json([
            'step' => 'general',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->name('debug.test-store-submit');
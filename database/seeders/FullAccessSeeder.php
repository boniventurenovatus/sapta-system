<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FullAccessSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== FullAccessSeeder ===');

        // ============================================================
        // 1. ROLES ZOTE
        // ============================================================
        $roles = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'director' => 'Director',
            'admin_director' => 'Administrative Director',
            'program_director' => 'Program Director',
            'hr_manager' => 'HR Manager',
            'hr_officer' => 'HR Officer',
            'ceo' => 'Chief Executive Officer',
            'bod' => 'Board of Directors',
            'manager' => 'Manager',
            'staff' => 'Staff',
            'finance_manager' => 'Finance Manager',
            'accountant' => 'Accountant',
            'ict_manager' => 'ICT Manager',
            'procurement_manager' => 'Procurement Manager',
            'logistics_manager' => 'Logistics Manager',
        ];

        foreach ($roles as $code => $name) {
            if (Schema::hasTable('roles')) {
                Role::firstOrCreate(
                    ['name' => $code],
                    [
                        'code' => $code,
                        'display_name' => $name,
                        'description' => $name . ' role',
                    ]
                );
            }
        }
        $this->command->info('✅ Roles zimeundwa');

        // ============================================================
        // 2. PERMISSIONS ZOTE
        // ============================================================
        $permissions = [
            // Employees
            'view_employees', 'create_employees', 'edit_employees', 'delete_employees',
            // Users
            'view_users', 'create_users', 'edit_users', 'delete_users',
            // Roles
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
            // Permissions
            'view_permissions', 'create_permissions', 'edit_permissions', 'delete_permissions',
            // Departments
            'view_departments', 'create_departments', 'edit_departments', 'delete_departments',
            // Positions
            'view_positions', 'create_positions', 'edit_positions', 'delete_positions',
            // Attendance
            'view_attendance', 'create_attendance', 'edit_attendance', 'delete_attendance',
            // Leave
            'view_leave', 'create_leave', 'approve_leave', 'reject_leave',
            // Budgets
            'view_budgets', 'create_budgets', 'edit_budgets', 'delete_budgets',
            // Receipts
            'view_receipts', 'create_receipts', 'edit_receipts', 'delete_receipts',
            // Payment Vouchers
            'view_payment_vouchers', 'create_payment_vouchers', 'approve_payment_vouchers', 'delete_payment_vouchers',
            // Payroll
            'view_payroll', 'create_payroll', 'edit_payroll', 'delete_payroll',
            // Documents
            'view_documents', 'create_documents', 'edit_documents', 'delete_documents',
            // Projects
            'view_projects', 'create_projects', 'edit_projects', 'delete_projects',
            // Tasks
            'view_tasks', 'create_tasks', 'edit_tasks', 'delete_tasks',
            // Reports
            'view_reports', 'export_reports',
            // Activity Logs
            'view_activity_logs',
            // Settings
            'view_settings', 'edit_settings',
            // Notifications
            'view_notifications',
            // Communication
            'view_communication', 'send_communication',
            // Trainings
            'view_trainings', 'create_trainings', 'edit_trainings', 'delete_trainings',
            // Recruitment
            'view_recruitment', 'create_recruitment', 'edit_recruitment', 'delete_recruitment',
            // Performance
            'view_performance', 'create_performance', 'edit_performance', 'delete_performance',
            // Procurement
            'view_procurement', 'create_procurement', 'edit_procurement', 'delete_procurement',
            // Suppliers
            'view_suppliers', 'create_suppliers', 'edit_suppliers', 'delete_suppliers',
            // Drafts
            'view_drafts', 'create_drafts', 'edit_drafts', 'delete_drafts',
        ];

        foreach ($permissions as $permission) {
            if (Schema::hasTable('permissions')) {
                Permission::firstOrCreate(
                    ['name' => $permission],
                    ['display_name' => ucwords(str_replace('_', ' ', $permission))]
                );
            }
        }
        $this->command->info('✅ Permissions zimeundwa');

        // ============================================================
        // 3. SUPERADMIN USER
        // ============================================================
        $user = User::where('username', 'superadmin')->first();

        if (!$user) {
            $user = User::create([
                'username' => 'superadmin',
                'email' => 'boniventurenovatus@gmail.com',
                'password_hash' => Hash::make('Sapta@2026!'),
                'account_status' => 'active',
                'is_first_login' => false,
            ]);
            $this->command->info('✅ SuperAdmin imeundwa');
        } else {
            $user->password_hash = Hash::make('Sapta@2026!');
            $user->account_status = 'active';
            $user->save();
            $this->command->info('✅ SuperAdmin password imewekwa upya');
        }

        // ============================================================
        // 4. ROLE super_admin KWA SUPERADMIN
        // ============================================================
        if (Schema::hasTable('roles') && Schema::hasTable('user_roles')) {
            $superAdminRole = Role::where('name', 'super_admin')->first();
            
            if ($superAdminRole) {
                // Ondoa roles zote za zamani
                DB::table('user_roles')->where('user_id', $user->id)->delete();
                
                // Ongeza super_admin
                DB::table('user_roles')->insert([
                    'user_id' => $user->id,
                    'role_id' => $superAdminRole->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->command->info('✅ Role super_admin imeongezwa');
                
                // Permissions zote kwa super_admin
                if (Schema::hasTable('role_permissions')) {
                    DB::table('role_permissions')->where('role_id', $superAdminRole->id)->delete();
                    
                    $allPermissions = Permission::all();
                    foreach ($allPermissions as $perm) {
                        DB::table('role_permissions')->insert([
                            'role_id' => $superAdminRole->id,
                            'permission_id' => $perm->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    $this->command->info('✅ Permissions zote zimeongezwa kwa super_admin');
                }
            }
        }

        // ============================================================
        // 5. HAKIKISHA ADMIN ANA ROLE admin
        // ============================================================
        if (Schema::hasTable('roles') && Schema::hasTable('user_roles')) {
            $adminUser = User::where('username', 'admin')->first();
            $adminRole = Role::where('name', 'admin')->first();
            
            if ($adminUser && $adminRole) {
                DB::table('user_roles')->where('user_id', $adminUser->id)->delete();
                DB::table('user_roles')->insert([
                    'user_id' => $adminUser->id,
                    'role_id' => $adminRole->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info('✅ Role admin imeongezwa kwa admin user');
            }
        }

        // ============================================================
        // 6. HAKIKISHA EMPLOYEES WOTE WANA employment_status
        // ============================================================
        if (Schema::hasTable('employees')) {
            DB::table('employees')->whereNull('employment_status')->update(['employment_status' => 'active']);
            $this->command->info('✅ Employees wote wana employment_status');
        }

        $this->command->info('✅ FullAccessSeeder imekamilika!');
    }
}
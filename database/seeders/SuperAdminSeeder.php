<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. UNDA ROLES ZOTE
        // ============================================================
        $roles = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'hr_manager' => 'HR Manager',
            'hr_officer' => 'HR Officer',
            'director' => 'Director',
            'admin_director' => 'Administrative Director',
            'program_director' => 'Program Director',
            'ceo' => 'Chief Executive Officer',
            'bod' => 'Board of Directors',
            'manager' => 'Manager',
            'staff' => 'Staff',
            'finance_manager' => 'Finance Manager',
            'accountant' => 'Accountant',
            'ict_manager' => 'ICT Manager',
        ];

        foreach ($roles as $code => $name) {
            Role::firstOrCreate(
                ['name' => $code],
                [
                    'code' => $code,
                    'display_name' => $name,
                    'description' => $name . ' role',
                ]
            );
        }

        $this->command->info('✅ Roles zimeundwa');

        // ============================================================
        // 2. UNDA PERMISSIONS ZOTE
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
            // Documents
            'view_documents', 'create_documents', 'edit_documents', 'delete_documents',
            // Reports
            'view_reports', 'export_reports',
            // Activity Logs
            'view_activity_logs',
            // Settings
            'view_settings', 'edit_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['display_name' => ucwords(str_replace('_', ' ', $permission))]
            );
        }

        $this->command->info('✅ Permissions zimeundwa');

        // ============================================================
        // 3. UNDA SUPERADMIN USER
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

            $this->command->info('✅ SuperAdmin imeundwa: superadmin / Sapta@2026!');
        } else {
            // Update password
            $user->password_hash = Hash::make('Sapta@2026!');
            $user->account_status = 'active';
            $user->save();
            $this->command->info('✅ SuperAdmin password imewekwa upya: superadmin / Sapta@2026!');
        }

        // ============================================================
        // 4. ONGEZA ROLE super_admin KWA SUPERADMIN
        // ============================================================
        $superAdminRole = Role::where('name', 'super_admin')->first();
        
        if ($superAdminRole) {
            // Ondoa roles zote za zamani
            $user->roles()->detach();
            
            // Ongeza super_admin
            $user->roles()->attach($superAdminRole->id);
            
            $this->command->info('✅ Role super_admin imeongezwa kwa superadmin');
        }

        // ============================================================
        // 5. HAKIKISHA super_admin ANA PERMISSIONS ZOTE
        // ============================================================
        if ($superAdminRole && Schema::hasTable('role_permissions')) {
            $allPermissions = Permission::all();
            $superAdminRole->permissions()->sync($allPermissions->pluck('id'));
            
            $this->command->info('✅ Permissions zote zimeongezwa kwa super_admin');
        }

        // ============================================================
        // 6. ONGEZA ROLES KWA USER MWINGINE (kama yupo)
        // ============================================================
        $adminUser = User::where('username', 'admin')->first();
        if ($adminUser) {
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $adminUser->roles()->detach();
                $adminUser->roles()->attach($adminRole->id);
                $this->command->info('✅ Role admin imeongezwa kwa admin user');
            }
        }

        $this->command->info('✅ SuperAdminSeeder imekamilika!');
    }
}
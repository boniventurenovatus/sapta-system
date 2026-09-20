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
        // 1. ROLES
        // ============================================================
        if (Schema::hasTable('roles')) {
            $roles = [
                'super_admin' => 'Super Admin',
                'admin' => 'Admin',
                'director' => 'Director',
                'hr_manager' => 'HR Manager',
                'hr_officer' => 'HR Officer',
                'manager' => 'Manager',
                'staff' => 'Staff',
                'ceo' => 'CEO',
                'bod' => 'Board of Directors',
                'accountant' => 'Accountant',
            ];

            foreach ($roles as $code => $name) {
                Role::firstOrCreate(
                    ['name' => $code],
                    ['code' => $code, 'display_name' => $name]
                );
            }
            $this->command->info('✅ Roles zimeundwa');
        }

        // ============================================================
        // 2. PERMISSIONS
        // ============================================================
        if (Schema::hasTable('permissions')) {
            $permissions = [
                'view_employees', 'create_employees', 'edit_employees', 'delete_employees',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_roles', 'view_permissions', 'view_departments', 'view_positions',
                'view_attendance', 'view_leave', 'view_budgets', 'view_receipts',
                'view_payment_vouchers', 'view_payroll', 'view_documents', 'view_projects',
                'view_tasks', 'view_reports', 'view_activity_logs', 'view_settings',
                'view_notifications', 'view_communication', 'view_trainings',
                'view_recruitment', 'view_performance', 'view_procurement',
                'view_drafts',
            ];

            foreach ($permissions as $permission) {
                Permission::firstOrCreate(
                    ['name' => $permission],
                    ['display_name' => ucwords(str_replace('_', ' ', $permission))]
                );
            }
            $this->command->info('✅ Permissions zimeundwa');
        }

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
        // 4. ONGEZA ROLE super_admin KWA SUPERADMIN
        // ============================================================
        if (Schema::hasTable('roles') && Schema::hasTable('user_roles')) {
            $superAdminRole = Role::where('name', 'super_admin')->first();
            
            if ($superAdminRole) {
                // Ondoa roles zote
                DB::table('user_roles')->where('user_id', $user->id)->delete();
                
                // Ongeza super_admin
                DB::table('user_roles')->insert([
                    'user_id' => $user->id,
                    'role_id' => $superAdminRole->id,
                ]);
                
                $this->command->info('✅ Role super_admin imeongezwa');
                
                // Permissions zote kwa super_admin
                if (Schema::hasTable('role_permissions') && Schema::hasTable('permissions')) {
                    DB::table('role_permissions')->where('role_id', $superAdminRole->id)->delete();
                    
                    $allPermissions = Permission::all();
                    foreach ($allPermissions as $perm) {
                        DB::table('role_permissions')->insert([
                            'role_id' => $superAdminRole->id,
                            'permission_id' => $perm->id,
                        ]);
                    }
                    $this->command->info('✅ Permissions zote zimeongezwa');
                }
            }
        }

        $this->command->info('✅ FullAccessSeeder imekamilika!');
    }
}
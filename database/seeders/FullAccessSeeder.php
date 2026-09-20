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

        // ROLES
        if (Schema::hasTable('roles')) {
            $roles = [
                'super_admin', 'admin', 'director', 'hr_manager', 'hr_officer',
                'manager', 'staff', 'ceo', 'bod', 'accountant',
            ];

            foreach ($roles as $role) {
                DB::table('roles')->updateOrInsert(
                    ['name' => $role],
                    [
                        'code' => $role,
                        'description' => ucwords(str_replace('_', ' ', $role)),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
            $this->command->info('✅ Roles zimeundwa');
        }

        // PERMISSIONS — na code
        if (Schema::hasTable('permissions')) {
            $permissions = [
                'view_employees', 'create_employees', 'edit_employees', 'delete_employees',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_roles', 'view_permissions', 'view_departments', 'view_positions',
                'view_attendance', 'view_leave', 'view_budgets', 'view_receipts',
                'view_payment_vouchers', 'view_payroll', 'view_documents', 'view_projects',
                'view_tasks', 'view_reports', 'view_activity_logs', 'view_settings',
                'view_notifications', 'view_communication', 'view_trainings',
                'view_recruitment', 'view_performance', 'view_procurement', 'view_drafts',
            ];

            foreach ($permissions as $permission) {
                DB::table('permissions')->updateOrInsert(
                    ['name' => $permission],
                    [
                        'code' => $permission,
                        'description' => ucwords(str_replace('_', ' ', $permission)),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
            $this->command->info('✅ Permissions zimeundwa');
        }

        // SUPERADMIN
        $user = User::where('username', 'superadmin')->first();

        if (!$user) {
            $user = User::create([
                'username' => 'superadmin',
                'email' => 'boniventurenovatus@gmail.com',
                'password_hash' => Hash::make('Sapta@2026!'),
                'account_status' => 'active',
                'is_first_login' => false,
            ]);
        } else {
            $user->password_hash = Hash::make('Sapta@2026!');
            $user->account_status = 'active';
            $user->save();
        }

        // ROLE super_admin
        if (Schema::hasTable('roles') && Schema::hasTable('user_roles')) {
            $superAdminRole = DB::table('roles')->where('name', 'super_admin')->first();
            
            if ($superAdminRole) {
                DB::table('user_roles')->where('user_id', $user->id)->delete();
                DB::table('user_roles')->insert([
                    'user_id' => $user->id,
                    'role_id' => $superAdminRole->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                if (Schema::hasTable('role_permissions') && Schema::hasTable('permissions')) {
                    DB::table('role_permissions')->where('role_id', $superAdminRole->id)->delete();
                    $allPermissions = DB::table('permissions')->get();
                    foreach ($allPermissions as $perm) {
                        DB::table('role_permissions')->insert([
                            'role_id' => $superAdminRole->id,
                            'permission_id' => $perm->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        $this->command->info('✅ FullAccessSeeder imekamilika!');
    }
}
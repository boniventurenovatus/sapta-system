<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SaptaSystemSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
             * -------------------------------------------------------------
             * ORGANIZATION
             * -------------------------------------------------------------
             */
            $organization = Organization::firstOrCreate(
                ['code' => 'SAPTA'],
                [
                    'name' => 'SAPTA System',
                    'description' => 'SAPTA System Organization',
                    'email' => 'admin@sapta.local',
                    'phone' => null,
                    'address' => null,
                    'website' => null,
                    'status' => 'active',
                ]
            );

            /*
             * -------------------------------------------------------------
             * DEPARTMENT
             * -------------------------------------------------------------
             */
           $department = Department::firstOrCreate(
    [
        'organization_id' => $organization->id,
        'code' => 'ADMIN',
    ],
    [
    'name' => 'Administration',
    'description' => 'System administration department',
    'is_active' => true,
],
);

            /*
             * -------------------------------------------------------------
             * EMPLOYEE
             * -------------------------------------------------------------
             */
            $employee = Employee::firstOrCreate(
                [
                    'employee_number' => 'SAPTA-0001',
                ],
                [
                    'organization_id' => $organization->id,
                    'department_id' => $department->id,
                    'supervisor_id' => null,

                    'first_name' => 'System',
                    'middle_name' => null,
                    'last_name' => 'Administrator',

                    'date_of_birth' => null,
                    'gender' => null,

                    'official_email' => 'admin@sapta.local',
                    'personal_email' => null,

                    'phone' => null,
                    'alternative_phone' => null,
                    'address' => null,

                    'job_title' => 'System Administrator',
                    'employment_type' => 'full_time',
                    'employment_status' => 'active',

                    'date_joined' => now()->toDateString(),
                    'date_confirmed' => null,
                    'date_left' => null,
                    'exit_reason' => null,
                ]
            );

            /*
             * -------------------------------------------------------------
             * PERMISSIONS
             * -------------------------------------------------------------
             */
            $permissions = [
                [
                    'name' => 'View Dashboard',
                    'code' => 'dashboard.view',
                    'module' => 'dashboard',
                    'description' => 'View system dashboard',
                ],
                [
                    'name' => 'View Users',
                    'code' => 'users.view',
                    'module' => 'users',
                    'description' => 'View system users',
                ],
                [
                    'name' => 'Create Users',
                    'code' => 'users.create',
                    'module' => 'users',
                    'description' => 'Create system users',
                ],
                [
                    'name' => 'Edit Users',
                    'code' => 'users.edit',
                    'module' => 'users',
                    'description' => 'Edit system users',
                ],
                [
                    'name' => 'Delete Users',
                    'code' => 'users.delete',
                    'module' => 'users',
                    'description' => 'Delete system users',
                ],
                [
                    'name' => 'View Roles',
                    'code' => 'roles.view',
                    'module' => 'roles',
                    'description' => 'View system roles',
                ],
                [
                    'name' => 'Manage Roles',
                    'code' => 'roles.manage',
                    'module' => 'roles',
                    'description' => 'Create and manage roles',
                ],
                [
                    'name' => 'View Permissions',
                    'code' => 'permissions.view',
                    'module' => 'permissions',
                    'description' => 'View system permissions',
                ],
                [
                    'name' => 'Manage Permissions',
                    'code' => 'permissions.manage',
                    'module' => 'permissions',
                    'description' => 'Manage system permissions',
                ],
            ];

            foreach ($permissions as $permissionData) {
                Permission::firstOrCreate(
                    ['code' => $permissionData['code']],
                    array_merge($permissionData, [
                        'status' => 'active',
                    ])
                );
            }

            /*
             * -------------------------------------------------------------
             * ADMIN ROLE
             * -------------------------------------------------------------
             */
            $adminRole = Role::firstOrCreate(
                ['code' => 'SUPER_ADMIN'],
                [
                    'name' => 'Super Administrator',
                    'description' => 'Full system administration access',
                    'status' => 'active',
                ]
            );

            /*
             * -------------------------------------------------------------
             * ATTACH ALL PERMISSIONS TO ADMIN ROLE
             * -------------------------------------------------------------
             */
            $permissionIds = Permission::where('status', 'active')
                ->pluck('id')
                ->toArray();

            $adminRole->permissions()->sync($permissionIds);

            /*
             * -------------------------------------------------------------
             * ADMIN USER
             * -------------------------------------------------------------
             */
            $user = User::firstOrCreate(
                [
                    'username' => 'admin',
                ],
                [
                    'employee_id' => $employee->id,
                    'email' => 'admin@sapta.local',

                    'password_hash' => Hash::make('Sapta@2026'),

                    'account_status' => 'active',
                    'is_first_login' => true,

                    'first_password_expires_at' => now()->addDays(7),

                    'failed_login_attempts' => 0,
                    'locked_until' => null,

                    'last_login_at' => null,
                    'password_changed_at' => null,
                    'email_verified_at' => now(),
                ]
            );

            /*
             * -------------------------------------------------------------
             * ATTACH ADMIN ROLE TO USER
             * -------------------------------------------------------------
             */
            $user->roles()->syncWithoutDetaching([
                $adminRole->id,
            ]);
        });
    }
}
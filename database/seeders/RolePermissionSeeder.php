<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Organization
            ['organization_view', 'organization_view', 'Organization', 'View organizations'],
            ['organization_create', 'organization_create', 'Organization', 'Create organizations'],
            ['organization_edit', 'organization_edit', 'Organization', 'Edit organizations'],
            ['organization_delete', 'organization_delete', 'Organization', 'Delete organizations'],

            // HR / Employees
            ['hr_employee_view', 'hr_employee_view', 'HR', 'View employees'],
            ['hr_employee_create', 'hr_employee_create', 'HR', 'Create employees'],
            ['hr_employee_edit', 'hr_employee_edit', 'HR', 'Edit employees'],
            ['hr_employee_delete', 'hr_employee_delete', 'HR', 'Delete employees'],
            ['hr_leave_view', 'hr_leave_view', 'HR', 'View leave requests'],
            ['hr_leave_approve', 'hr_leave_approve', 'HR', 'Approve or reject leave requests'],
            ['hr_attendance_view', 'hr_attendance_view', 'HR', 'View attendance'],
            ['hr_attendance_manage', 'hr_attendance_manage', 'HR', 'Manage attendance'],

            // Finance
            ['finance_budget_view', 'finance_budget_view', 'Finance', 'View budgets'],
            ['finance_budget_manage', 'finance_budget_manage', 'Finance', 'Manage budgets'],
            ['finance_procurement_view', 'finance_procurement_view', 'Finance', 'View procurements'],
            ['finance_procurement_manage', 'finance_procurement_manage', 'Finance', 'Manage procurements'],
            ['finance_expense_view', 'finance_expense_view', 'Finance', 'View expense claims'],
            ['finance_expense_manage', 'finance_expense_manage', 'Finance', 'Manage expense claims'],

            // Projects
            ['projects_view', 'projects_view', 'Projects', 'View projects'],
            ['projects_manage', 'projects_manage', 'Projects', 'Manage projects'],
            ['projects_tasks_manage', 'projects_tasks_manage', 'Projects', 'Manage project tasks'],

            // MEAL
            ['meal_view', 'meal_view', 'MEAL', 'View MEAL information'],
            ['meal_report', 'meal_report', 'MEAL', 'Create and manage MEAL reports'],

            // Documents
            ['documents_view', 'documents_view', 'Documents', 'View documents'],
            ['documents_upload', 'documents_upload', 'Documents', 'Upload documents'],
            ['documents_manage', 'documents_manage', 'Documents', 'Manage documents'],

            // Workflow
            ['workflow_view', 'workflow_view', 'Workflow', 'View workflows'],
            ['workflow_manage', 'workflow_manage', 'Workflow', 'Manage workflows'],

            // Users / RBAC
            ['users_view', 'users_view', 'Administration', 'View users'],
            ['users_manage', 'users_manage', 'Administration', 'Manage users'],
            ['roles_view', 'roles_view', 'Administration', 'View roles'],
            ['roles_manage', 'roles_manage', 'Administration', 'Manage roles'],
            ['permissions_view', 'permissions_view', 'Administration', 'View permissions'],
            ['permissions_manage', 'permissions_manage', 'Administration', 'Manage permissions'],

            // Reports
            ['reports_view', 'reports_view', 'Reports', 'View reports'],
            ['reports_export', 'reports_export', 'Reports', 'Export reports'],
        ];

        DB::transaction(function () use ($permissions) {
            foreach ($permissions as [$name, $code, $module, $description]) {
                Permission::updateOrCreate(
                    ['code' => $code],
                    [
                        'name' => $name,
                        'module' => $module,
                        'description' => $description,
                        'status' => 'active',
                    ]
                );
            }

            $roles = [
                [
                    'name' => 'Super Admin',
                    'code' => 'super_admin',
                    'description' => 'Full system access',
                ],
                [
                    'name' => 'Admin',
                    'code' => 'admin',
                    'description' => 'System administration access',
                ],
                [
                    'name' => 'Director',
                    'code' => 'director',
                    'description' => 'Organization-wide management and oversight',
                ],
                [
                    'name' => 'Manager',
                    'code' => 'manager',
                    'description' => 'Department and project management access',
                ],
                [
                    'name' => 'Staff',
                    'code' => 'staff',
                    'description' => 'Standard staff access',
                ],
            ];

            foreach ($roles as $roleData) {
                Role::updateOrCreate(
                    ['code' => $roleData['code']],
                    [
                        'name' => $roleData['name'],
                        'description' => $roleData['description'],
                        'status' => 'active',
                    ]
                );
            }

            $allPermissionIds = Permission::where('status', 'active')
                ->pluck('id')
                ->all();

            $rolePermissions = [
                'super_admin' => $allPermissionIds,
                'admin' => Permission::whereIn('code', [
                    'organization_view',
                    'organization_create',
                    'organization_edit',
                    'hr_employee_view',
                    'hr_employee_create',
                    'hr_employee_edit',
                    'hr_leave_view',
                    'hr_leave_approve',
                    'hr_attendance_view',
                    'hr_attendance_manage',
                    'finance_budget_view',
                    'finance_budget_manage',
                    'finance_procurement_view',
                    'finance_procurement_manage',
                    'finance_expense_view',
                    'finance_expense_manage',
                    'projects_view',
                    'projects_manage',
                    'projects_tasks_manage',
                    'documents_view',
                    'documents_upload',
                    'documents_manage',
                    'workflow_view',
                    'workflow_manage',
                    'users_view',
                    'users_manage',
                    'roles_view',
                    'roles_manage',
                    'reports_view',
                    'reports_export',
                ])->pluck('id')->all(),

                'director' => Permission::whereIn('code', [
                    'organization_view',
                    'hr_employee_view',
                    'hr_leave_view',
                    'hr_leave_approve',
                    'hr_attendance_view',
                    'finance_budget_view',
                    'finance_procurement_view',
                    'finance_expense_view',
                    'projects_view',
                    'projects_manage',
                    'projects_tasks_manage',
                    'meal_view',
                    'meal_report',
                    'documents_view',
                    'documents_upload',
                    'workflow_view',
                    'reports_view',
                    'reports_export',
                ])->pluck('id')->all(),

                'manager' => Permission::whereIn('code', [
                    'organization_view',
                    'hr_employee_view',
                    'hr_leave_view',
                    'hr_leave_approve',
                    'hr_attendance_view',
                    'hr_attendance_manage',
                    'projects_view',
                    'projects_manage',
                    'projects_tasks_manage',
                    'meal_view',
                    'meal_report',
                    'documents_view',
                    'documents_upload',
                    'workflow_view',
                    'reports_view',
                ])->pluck('id')->all(),

                'staff' => Permission::whereIn('code', [
                    'organization_view',
                    'hr_employee_view',
                    'hr_leave_view',
                    'hr_attendance_view',
                    'projects_view',
                    'projects_tasks_manage',
                    'meal_view',
                    'documents_view',
                    'documents_upload',
                    'reports_view',
                ])->pluck('id')->all(),
            ];

            foreach ($rolePermissions as $roleCode => $permissionIds) {
                $role = Role::where('code', $roleCode)->firstOrFail();

                DB::table('role_permissions')
                    ->where('role_id', $role->id)
                    ->delete();

                foreach (array_unique($permissionIds) as $permissionId) {
                    DB::table('role_permissions')->insert([
                        'role_id' => $role->id,
                        'permission_id' => $permissionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }
}

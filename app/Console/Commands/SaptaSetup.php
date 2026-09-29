<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Organization;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use App\Models\User;
use App\Models\Role;
use App\Models\Payslip;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use App\Models\EmployeePosition;
use App\Models\TrainingEnrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SaptaSetup extends Command
{
    protected $signature = 'sapta:setup';
    protected $description = 'Setup SAPTA live database — inafanya kila kitu kwa command moja';

    public function handle()
    {
        $this->info('=== SAPTA SETUP ===');
        
        try {
            // 1. Ondoa data zote za demo
            $this->info('1. Ondoa data zote za demo...');
            Payslip::query()->delete();
            LeaveRequest::query()->delete();
            Attendance::query()->delete();
            EmployeePosition::query()->delete();
            TrainingEnrollment::query()->delete();
            Employee::query()->delete();
            
            // 2. Ondoa users wote
            $this->info('2. Ondoa users wote...');
            User::query()->delete();
            
            // 3. Ondoa organizations zote
            $this->info('3. Ondoa organizations zote...');
            Organization::query()->delete();
            
            // 4. Ondoa departments zote
            $this->info('4. Ondoa departments zote...');
            Department::query()->delete();
            
            // 5. Ondoa positions zote
            $this->info('5. Ondoa positions zote...');
            Position::query()->delete();
            
            // 6. Rekebisha sequence
            $this->info('6. Rekebisha sequence...');
            $driver = DB::connection()->getDriverName();
            if ($driver === 'pgsql') {
                DB::statement("SELECT setval('organizations_id_seq', 1, false);");
                DB::statement("SELECT setval('departments_id_seq', 1, false);");
                DB::statement("SELECT setval('positions_id_seq', 1, false);");
                DB::statement("SELECT setval('employees_id_seq', 1, false);");
                DB::statement("SELECT setval('users_id_seq', 1, false);");
            } elseif ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::statement('TRUNCATE TABLE organizations;');
                DB::statement('TRUNCATE TABLE departments;');
                DB::statement('TRUNCATE TABLE positions;');
                DB::statement('TRUNCATE TABLE employees;');
                DB::statement('TRUNCATE TABLE users;');
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
            
            // 7. Unda organization
            $this->info('7. Unda organization...');
            $org = Organization::create([
                'name' => 'Soil-Animals Power Tanzania',
                'code' => 'SAPTA',
                'type' => 'headquarters',
                'is_active' => true,
            ]);
            $this->info("   Organization: {$org->name} (ID: {$org->id})");
            
            // 8. Unda departments 7
            $this->info('8. Unda departments 7...');
            $departments = [
                ['name' => 'Human Resource', 'code' => 'HR'],
                ['name' => 'Board of Directors', 'code' => 'BOD'],
                ['name' => 'Chief Executive Officer', 'code' => 'CEO'],
                ['name' => 'Administrative and Operations Department', 'code' => 'ADMIN'],
                ['name' => 'Program and Technical Department', 'code' => 'PROG'],
                ['name' => 'Monitoring, Evaluation, Accountability and Learning Department', 'code' => 'MEAL'],
                ['name' => 'Communications & ICT / Digital Innovation Department', 'code' => 'ICT'],
            ];
            
            foreach ($departments as $dept) {
                $d = Department::create([
                    'name' => $dept['name'],
                    'code' => $dept['code'],
                    'organization_id' => $org->id,
                ]);
                $this->info("   Department: {$d->name} (ID: {$d->id})");
            }
            
            // 9. Unda positions 17
            $this->info('9. Unda positions 17...');
            $positions = [
                ['title' => 'Board of Directors', 'code' => 'bod', 'dept' => 'BOD'],
                ['title' => 'Chief Executive Officer (CEO)', 'code' => 'ceo', 'dept' => 'CEO'],
                ['title' => 'Administrative Director', 'code' => 'admin_director', 'dept' => 'ADMIN'],
                ['title' => 'Human Resource Management & Administration Manager', 'code' => 'hr_manager', 'dept' => 'HR'],
                ['title' => 'Procurement & Logistics Manager', 'code' => 'procurement_manager', 'dept' => 'ADMIN'],
                ['title' => 'Finance Manager', 'code' => 'finance_manager', 'dept' => 'ADMIN'],
                ['title' => 'Accountant', 'code' => 'accountant', 'dept' => 'ADMIN'],
                ['title' => 'Program & Technical Director', 'code' => 'program_director', 'dept' => 'PROG'],
                ['title' => 'Project Manager', 'code' => 'project_manager', 'dept' => 'PROG'],
                ['title' => 'Project Officers', 'code' => 'project_officer', 'dept' => 'PROG'],
                ['title' => 'Field Trainer', 'code' => 'field_trainer', 'dept' => 'PROG'],
                ['title' => 'Partnerships and Resource Mobilization Manager', 'code' => 'partnerships_manager', 'dept' => 'PROG'],
                ['title' => 'MEAL Manager', 'code' => 'meal_manager', 'dept' => 'MEAL'],
                ['title' => 'MEAL Officer', 'code' => 'meal_officer', 'dept' => 'MEAL'],
                ['title' => 'Research and Innovation Officer', 'code' => 'research_officer', 'dept' => 'MEAL'],
                ['title' => 'Community Knowledge Manager', 'code' => 'community_manager', 'dept' => 'MEAL'],
                ['title' => 'ICT & Digital Innovation Manager', 'code' => 'ict_manager', 'dept' => 'ICT'],
            ];
            
            foreach ($positions as $pos) {
                $dept = Department::where('code', $pos['dept'])->first();
                if ($dept) {
                    $p = Position::create([
                        'title' => $pos['title'],
                        'code' => $pos['code'],
                        'department_id' => $dept->id,
                        'status' => 'active',
                    ]);
                    $this->info("   Position: {$p->title} → {$dept->name}");
                }
            }
            
            // 10. Unda superadmin
            $this->info('10. Unda superadmin...');
            $superRole = Role::where('code', 'super_admin')->first();
            $super = User::create([
                'username' => 'novatus.boniventure',
                'email' => 'boniventurenovatus@gmail.com',
                'password_hash' => Hash::make('Sapta@2026!'),
                'account_status' => 'active',
                'is_first_login' => false,
            ]);
            if ($superRole) {
                $super->roles()->sync([$superRole->id]);
            }
            $this->info("   Superadmin: {$super->username}");
            
            // 11. Unda admin
            $this->info('11. Unda admin...');
            $adminRole = Role::where('code', 'admin')->first();
            $admin = User::create([
                'username' => 'ayusto.mwangalo',
                'email' => 'ayustomwangalo@gmail.com',
                'password_hash' => Hash::make('Sapta@2026!'),
                'account_status' => 'active',
                'is_first_login' => false,
            ]);
            if ($adminRole) {
                $admin->roles()->sync([$adminRole->id]);
            }
            $this->info("   Admin: {$admin->username}");
            
            // 12. Pata department na position
            $adminDept = Department::where('code', 'ADMIN')->first();
            $adminPos = Position::where('code', 'admin_director')->first();
            
            if (!$adminDept) {
                $adminDept = Department::first();
            }
            if (!$adminPos) {
                $adminPos = Position::first();
            }
            
            // 13. Unda employee kwa superadmin
            $this->info('12. Unda employee kwa superadmin...');
            $emp1 = Employee::create([
                'employee_number' => 'EMP001',
                'first_name' => 'Novatus',
                'last_name' => 'Boniventure',
                'email' => 'boniventurenovatus@gmail.com',
                'organization_id' => $org->id,
                'department_id' => $adminDept->id,
                'position_id' => $adminPos->id,
                'employment_status' => 'active',
                'hire_date' => now(),
                'job_title' => 'Administrative Director',
            ]);
            $super->update(['employee_id' => $emp1->id]);
            $this->info("   Employee: {$emp1->first_name} {$emp1->last_name} ({$emp1->employee_number})");
            
            // 14. Unda employee kwa admin
            $this->info('13. Unda employee kwa admin...');
            $emp2 = Employee::create([
                'employee_number' => 'EMP002',
                'first_name' => 'Ayusto',
                'last_name' => 'Mwangalo',
                'email' => 'ayustomwangalo@gmail.com',
                'organization_id' => $org->id,
                'department_id' => $adminDept->id,
                'position_id' => $adminPos->id,
                'employment_status' => 'active',
                'hire_date' => now(),
                'job_title' => 'Administrative Director',
            ]);
            $admin->update(['employee_id' => $emp2->id]);
            $this->info("   Employee: {$emp2->first_name} {$emp2->last_name} ({$emp2->employee_number})");
            
            $this->info('');
            $this->info('=== SETUP IMEKAMILIKA ===');
            $this->info('');
            $this->info('Login credentials:');
            $this->info('  Superadmin: novatus.boniventure / Sapta@2026!');
            $this->info('  Admin:      ayusto.mwangalo / Sapta@2026!');
            
        } catch (\Exception $e) {
            $this->error('ERROR: ' . $e->getMessage());
            $this->error('Line: ' . $e->getLine());
            $this->error('File: ' . $e->getFile());
            return 1;
        }
        
        return 0;
    }
}
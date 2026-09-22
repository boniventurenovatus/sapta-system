<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUsersSeeder extends Seeder
{
    public function run(): void
    {
        $roleUsers = [
            ['first' => 'Novatus', 'last' => 'Boniventure', 'email' => 'novatus.boniventure@sapta.co.tz', 'role' => 'super_admin', 'dept' => 1, 'pos' => 4, 'title' => 'Super Admin'],
            ['first' => 'Admin', 'last' => 'Sapta', 'email' => 'admin@sapta.co.tz', 'role' => 'admin', 'dept' => 1, 'pos' => 5, 'title' => 'System Admin'],
            ['first' => 'Board', 'last' => 'Directors', 'email' => 'bod@sapta.co.tz', 'role' => 'bod', 'dept' => 2, 'pos' => 3, 'title' => 'Board of Directors'],
            ['first' => 'Chief', 'last' => 'Executive', 'email' => 'ceo@sapta.co.tz', 'role' => 'ceo', 'dept' => 3, 'pos' => 4, 'title' => 'Chief Executive Officer'],
            ['first' => 'Director', 'last' => 'Operations', 'email' => 'director@sapta.co.tz', 'role' => 'director', 'dept' => 4, 'pos' => 5, 'title' => 'Director of Operations'],
            ['first' => 'Program', 'last' => 'Director', 'email' => 'program.director@sapta.co.tz', 'role' => 'program_director', 'dept' => 5, 'pos' => 10, 'title' => 'Program Director'],
            ['first' => 'Admin', 'last' => 'Director', 'email' => 'admin.director@sapta.co.tz', 'role' => 'admin_director', 'dept' => 4, 'pos' => 5, 'title' => 'Administrative Director'],
            ['first' => 'HR', 'last' => 'Manager', 'email' => 'hr.manager@sapta.co.tz', 'role' => 'hr_manager', 'dept' => 1, 'pos' => 6, 'title' => 'HR Manager'],
            ['first' => 'HR', 'last' => 'Officer', 'email' => 'hr.officer@sapta.co.tz', 'role' => 'hr_officer', 'dept' => 1, 'pos' => 6, 'title' => 'HR Officer'],
            ['first' => 'Finance', 'last' => 'Manager', 'email' => 'finance.manager@sapta.co.tz', 'role' => 'finance_manager', 'dept' => 4, 'pos' => 8, 'title' => 'Finance Manager'],
            ['first' => 'Accountant', 'last' => 'Sapta', 'email' => 'accountant@sapta.co.tz', 'role' => 'accountant', 'dept' => 4, 'pos' => 9, 'title' => 'Accountant'],
            ['first' => 'Procurement', 'last' => 'Manager', 'email' => 'procurement.manager@sapta.co.tz', 'role' => 'procurement_manager', 'dept' => 4, 'pos' => 7, 'title' => 'Procurement Manager'],
            ['first' => 'Project', 'last' => 'Manager', 'email' => 'project.manager@sapta.co.tz', 'role' => 'project_manager', 'dept' => 5, 'pos' => 11, 'title' => 'Project Manager'],
            ['first' => 'Project', 'last' => 'Officer', 'email' => 'project.officer@sapta.co.tz', 'role' => 'project_officer', 'dept' => 5, 'pos' => 12, 'title' => 'Project Officer'],
            ['first' => 'Field', 'last' => 'Trainer', 'email' => 'field.trainer@sapta.co.tz', 'role' => 'field_trainer', 'dept' => 5, 'pos' => 13, 'title' => 'Field Trainer'],
            ['first' => 'Partnerships', 'last' => 'Manager', 'email' => 'partnerships.manager@sapta.co.tz', 'role' => 'partnerships_manager', 'dept' => 5, 'pos' => 14, 'title' => 'Partnerships Manager'],
            ['first' => 'MEAL', 'last' => 'Manager', 'email' => 'meal.manager@sapta.co.tz', 'role' => 'meal_manager', 'dept' => 6, 'pos' => 15, 'title' => 'MEAL Manager'],
            ['first' => 'MEAL', 'last' => 'Officer', 'email' => 'meal.officer@sapta.co.tz', 'role' => 'meal_officer', 'dept' => 6, 'pos' => 16, 'title' => 'MEAL Officer'],
            ['first' => 'Research', 'last' => 'Officer', 'email' => 'research.officer@sapta.co.tz', 'role' => 'research_officer', 'dept' => 6, 'pos' => 17, 'title' => 'Research Officer'],
            ['first' => 'ICT', 'last' => 'Manager', 'email' => 'ict.manager@sapta.co.tz', 'role' => 'ict_manager', 'dept' => 7, 'pos' => 19, 'title' => 'ICT Manager'],
            ['first' => 'Community', 'last' => 'Manager', 'email' => 'community.manager@sapta.co.tz', 'role' => 'community_manager', 'dept' => 7, 'pos' => 18, 'title' => 'Community Knowledge Manager'],
            ['first' => 'Team', 'last' => 'Manager', 'email' => 'manager@sapta.co.tz', 'role' => 'manager', 'dept' => 5, 'pos' => 11, 'title' => 'Team Manager'],
            ['first' => 'Staff', 'last' => 'Sapta', 'email' => 'staff@sapta.co.tz', 'role' => 'staff', 'dept' => 5, 'pos' => 12, 'title' => 'Staff'],
        ];

        $count = 0;

        foreach ($roleUsers as $data) {
            try {
                $employee = Employee::where('email', $data['email'])->first();
                
                if (!$employee) {
                    $employee = Employee::create([
                        'region_id' => 2,
                        'district_id' => 1,
                        'ward_id' => 8,
                        'organization_id' => 2,
                        'department_id' => $data['dept'],
                        'organizational_unit_id' => 1,
                        'position_id' => $data['pos'],
                        'employee_number' => 'EMP-' . strtoupper(substr($data['role'], 0, 3)) . '-' . rand(1000, 9999),
                        'first_name' => $data['first'],
                        'middle_name' => 'SAPTA',
                        'last_name' => $data['last'],
                        'gender' => 'male',
                        'date_of_birth' => '1990-01-01',
                        'nationality' => 'Tanzanian',
                        'marital_status' => 'single',
                        'email' => $data['email'],
                        'phone' => '0712345678',
                        'alternative_phone' => '0765432109',
                        'address' => 'SAPTA Office',
                        'city' => 'Dar es Salaam',
                        'hire_date' => '2026-01-01',
                        'job_title' => $data['title'],
                        'employment_status' => 'active',
                        'employment_type' => 'full_time',
                        'contract_type' => 'permanent',
                        'salary' => 500000,
                        'bank_account' => '1234567890',
                        'bank_name' => 'CRDB',
                        'tin_number' => '123-456-789',
                        'nssf_number' => 'NSSF12345',
                        'nhif_number' => 'NHIF12345',
                        'emergency_contact_name' => 'Emergency Contact',
                        'emergency_contact_phone' => '0712345679',
                        'emergency_relationship' => 'Sister',
                        'notes' => 'Auto-created for ' . $data['role'],
                    ]);
                }

                $user = User::where('email', $data['email'])->first();

                if (!$user) {
                    $firstName = strtolower(preg_replace('/[^a-zA-Z]/', '', $data['first']));
                    $nameBase = $firstName;
                    
                    $saptaChars = ['S', 's', 'A', 'a', 'P', 'p', 'T', 't'];
                    $randomChar = $saptaChars[array_rand($saptaChars)];
                    
                    $username = $nameBase . '@sapta2024' . $randomChar;
                    
                    $counter = 1;
                    while (User::where('username', $username)->exists()) {
                        $randomChar = $saptaChars[array_rand($saptaChars)];
                        $username = $nameBase . '@sapta2024' . $randomChar . $counter;
                        $counter++;
                    }

                    $lastNameCapitalized = ucfirst(strtolower(preg_replace('/[^a-zA-Z]/', '', $data['last'])));
                    $password = $lastNameCapitalized . '@Sapta.org';

                    $user = User::create([
                        'employee_id' => $employee->id,
                        'username' => $username,
                        'email' => $data['email'],
                        'password_hash' => Hash::make($password),
                        'account_status' => 'active',
                        'is_first_login' => true,
                        'credentials_sent_at' => now(),
                        'credentials_expires_at' => now()->addDays(7),
                        'credentials_channel' => 'internal_email',
                    ]);

                    $role = Role::where('code', $data['role'])->first();
                    if ($role) {
                        $user->roles()->attach($role->id);
                    }

                    $this->command->info("✅ " . str_pad($data['role'], 25) . " → " . $username . " / " . $password);
                    $count++;
                } else {
                    $this->command->info("ℹ️ " . str_pad($data['role'], 25) . " → tayari ipo");
                }
            } catch (\Exception $e) {
                $this->command->error("❌ " . str_pad($data['role'], 25) . " → ERROR: " . $e->getMessage());
            }
        }

        $this->command->info("\n✅ Users wapya: " . $count);
    }
}
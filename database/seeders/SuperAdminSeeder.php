<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Unda superadmin kama haipo
        $user = User::where('username', 'superadmin')->first();

        if (!$user) {
            $user = User::create([
                'username' => 'superadmin',
                'email' => 'boniventurenovatus@gmail.com',
                'password_hash' => Hash::make('Sapta@2026!'),
                'account_status' => 'active',
                'is_first_login' => false,
            ]);

            // Ongeza role
            $role = Role::where('name', 'super_admin')->first();
            if ($role) {
                $user->roles()->attach($role->id);
            }

            $this->command->info('✅ SuperAdmin imeundwa: superadmin / Sapta@2026!');
        } else {
            // Update password
            $user->password_hash = Hash::make('Sapta@2026!');
            $user->account_status = 'active';
            $user->save();
            $this->command->info('✅ SuperAdmin password imewekwa upya: superadmin / Sapta@2026!');
        }
    }
}
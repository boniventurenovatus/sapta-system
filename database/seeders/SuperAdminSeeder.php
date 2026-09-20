<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $username = env('SAPTA_ADMIN_USERNAME', 'superadmin');
        $email = env('SAPTA_ADMIN_EMAIL');
        $password = env('SAPTA_ADMIN_PASSWORD');

        if (blank($email) || blank($password)) {
            throw new RuntimeException(
                'SAPTA_ADMIN_EMAIL and SAPTA_ADMIN_PASSWORD must be configured in .env.'
            );
        }

        DB::transaction(function () use ($username, $email, $password) {

            // Check whether this email already belongs to another user.
            $emailOwner = User::where('email', $email)
                ->where('username', '!=', $username)
                ->first();

            if ($emailOwner) {
                throw new RuntimeException(
                    'SAPTA_ADMIN_EMAIL is already assigned to another user.'
                );
            }

            // Find the existing Super Admin by username.
            // If not found, create a new one.
            $user = User::where('username', $username)->first();

            if (!$user) {
                $user = new User();
                $user->username = $username;
            }

            // Update the existing account.
            $user->email = $email;
            $user->password_hash = Hash::make($password);
            $user->account_status = 'active';
            $user->is_first_login = true;
            $user->failed_login_attempts = 0;
            $user->locked_until = null;
            $user->last_login_at = null;
            $user->password_changed_at = null;

            $user->save();

            // Find Super Admin role.
            $role = Role::where('code', 'super_admin')->first();

            if (!$role) {
                throw new RuntimeException(
                    'Super Admin role with code "super_admin" was not found.'
                );
            }

            // Make sure the user has the Super Admin role.
            $user->roles()->syncWithoutDetaching([$role->id]);

            $this->command?->info(
                "Super Admin updated successfully: {$user->username}"
            );
        });
    }
}
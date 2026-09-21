<?php

namespace App\Services;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CredentialsService
{
    /**
     * Generate username kwa format ya SAPTA
     */
    public static function generateUsername(Employee $employee): string
    {
        $firstName = strtolower(preg_replace('/[^a-zA-Z]/', '', $employee->first_name));
        $lastName = strtolower(preg_replace('/[^a-zA-Z]/', '', $employee->last_name));
        
        $nameBase = rand(0, 1) ? $firstName : $lastName;
        
        $saptaChars = ['S', 's', 'A', 'a', 'P', 'p', 'T', 't'];
        $randomChar = $saptaChars[array_rand($saptaChars)];
        
        $username = $nameBase . '@sapta2024' . $randomChar;
        
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $randomChar = $saptaChars[array_rand($saptaChars)];
            $username = $nameBase . '@sapta2024' . $randomChar . $counter;
            $counter++;
        }
        
        return $username;
    }

    /**
     * Generate password kwa format ya SAPTA
     */
    public static function generatePassword(Employee $employee): string
    {
        $lastNameCapitalized = ucfirst(strtolower(preg_replace('/[^a-zA-Z]/', '', $employee->last_name)));
        return $lastNameCapitalized . '@Sapta.org';
    }

    /**
     * Tuma credentials kwa SMS na Email
     */
    public static function sendCredentials(User $user, string $plainPassword, string $channel = 'both'): bool
    {
        $employee = $user->employee;
        if (!$employee) {
            return false;
        }

        $message = "Habari {$employee->first_name},\n\n";
        $message .= "Karibu SAPTA Management System.\n\n";
        $message .= "Hizi ni credentials zako:\n";
        $message .= "Username: {$user->username}\n";
        $message .= "Password: {$plainPassword}\n";
        $message .= "Email: {$user->email}\n\n";
        $message .= "Muhimu: Badilisha password yako baada ya kuingia.\n";
        $message .= "Credentials hizi zita-expire baada ya siku 7.\n\n";
        $message .= "Login: " . url('/login');

        $sent = false;

        // Tuma kwa SMS
        if (in_array($channel, ['sms', 'both']) && $employee->phone) {
            $sent = SmsService::send($employee->phone, $message) || $sent;
        }

        // Tuma kwa Email
        if (in_array($channel, ['email', 'both']) && $employee->email) {
            try {
                Mail::raw($message, function ($mail) use ($employee) {
                    $mail->to($employee->email)
                        ->subject('SAPTA System - Credentials Zako');
                });
                $sent = true;
            } catch (\Exception $e) {
                Log::error('Email send failed: ' . $e->getMessage());
            }
        }

        return $sent;
    }

    /**
     * Unda User kwa Employee
     */
    public static function createForEmployee(Employee $employee, int $roleId, string $channel = 'both'): array
    {
        $username = self::generateUsername($employee);
        $password = self::generatePassword($employee);

        $user = User::create([
            'employee_id' => $employee->id,
            'username' => $username,
            'email' => $employee->email,
            'password_hash' => Hash::make($password),
            'account_status' => 'active',
            'is_first_login' => true,
            'credentials_sent_at' => now(),
            'credentials_expires_at' => now()->addDays(7),
            'credentials_channel' => $channel,
        ]);

        $user->roles()->attach($roleId);

        self::sendCredentials($user, $password, $channel);

        return [
            'user' => $user,
            'username' => $username,
            'password' => $password,
        ];
    }
}
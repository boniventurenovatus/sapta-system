<?php

namespace App\Services;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Tuma ujumbe wa ndani (internal message) kwa user
     */
    public static function sendInternalMessage(User $user, string $subject, string $message): bool
    {
        try {
            // Angalia kama table ya messages ipo
            if (DB::getSchemaBuilder()->hasTable('messages')) {
                DB::table('messages')->insert([
                    'sender_id' => 1, // Admin
                    'recipient_id' => $user->id,
                    'subject' => $subject,
                    'body' => $message,
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                Log::info('Internal message sent', ['user_id' => $user->id]);
                return true;
            }

            // Kama hakuna table ya messages, tumia log
            Log::info('INTERNAL MESSAGE: ' . $subject, [
                'user_id' => $user->id,
                'message' => $message,
            ]);
            return true;

        } catch (\Exception $e) {
            Log::error('Internal message failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Tuma email kwa user
     */
    public static function sendEmail(User $user, string $subject, string $message): bool
    {
        try {
            if (!$user->email) {
                return false;
            }

            Mail::raw($message, function ($mail) use ($user, $subject) {
                $mail->to($user->email)->subject($subject);
            });

            Log::info('Email sent', ['email' => $user->email]);
            return true;

        } catch (\Exception $e) {
            Log::error('Email failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Tuma credentials kwa internal message + email
     */
    public static function sendCredentials(User $user, string $plainPassword): array
    {
        $employee = $user->employee;
        $name = $employee?->first_name ?? 'Employee';

        $subject = 'SAPTA System - Credentials Zako';

        $message = "Habari {$name},\n\n";
        $message .= "Karibu SAPTA Management System.\n\n";
        $message .= "Hizi ni credentials zako:\n\n";
        $message .= "Username: {$user->username}\n";
        $message .= "Email: {$user->email}\n";
        $message .= "Password: {$plainPassword}\n\n";
        $message .= "Login: " . url('/login') . "\n\n";
        $message .= "MUHIMU:\n";
        $message .= "- Badilisha password yako baada ya kuingia\n";
        $message .= "- Credentials hizi zita-expire baada ya siku 7\n";
        $message .= "- Usimpe mtu mwingine credentials zako\n\n";
        $message .= "Karibu SAPTA!\n";
        $message .= "Timu ya SAPTA";

        $internalSent = self::sendInternalMessage($user, $subject, $message);
        $emailSent = self::sendEmail($user, $subject, $message);

        return [
            'internal_sent' => $internalSent,
            'email_sent' => $emailSent,
        ];
    }

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
     * Unda User kwa Employee + Tuma credentials
     */
    public static function createForEmployee(Employee $employee, int $roleId): array
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
            'credentials_channel' => 'internal_email',
        ]);

        $user->roles()->attach($roleId);

        $result = self::sendCredentials($user, $password);

        return [
            'user' => $user,
            'username' => $username,
            'password' => $password,
            'internal_sent' => $result['internal_sent'],
            'email_sent' => $result['email_sent'],
        ];
    }
}
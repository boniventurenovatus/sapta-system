<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // GENERAL
            ['key' => 'app_name', 'value' => 'SAPTA Management System', 'group' => 'general', 'type' => 'string', 'label' => 'Application Name'],
            ['key' => 'app_logo', 'value' => '', 'group' => 'general', 'type' => 'image', 'label' => 'Application Logo'],
            ['key' => 'app_description', 'value' => 'Soil-Animals Power Tanzania', 'group' => 'general', 'type' => 'text', 'label' => 'Description'],
            ['key' => 'contact_email', 'value' => 'info@sapta.co.tz', 'group' => 'general', 'type' => 'email', 'label' => 'Contact Email'],
            ['key' => 'contact_phone', 'value' => '+255 000 000 000', 'group' => 'general', 'type' => 'string', 'label' => 'Contact Phone'],
            ['key' => 'address', 'value' => 'Dar es Salaam, Tanzania', 'group' => 'general', 'type' => 'text', 'label' => 'Address'],

            // SYSTEM
            ['key' => 'timezone', 'value' => 'Africa/Dar_es_Salaam', 'group' => 'system', 'type' => 'string', 'label' => 'Timezone'],
            ['key' => 'date_format', 'value' => 'M d, Y', 'group' => 'system', 'type' => 'string', 'label' => 'Date Format'],
            ['key' => 'currency', 'value' => 'TZS', 'group' => 'system', 'type' => 'string', 'label' => 'Currency'],
            ['key' => 'currency_symbol', 'value' => 'TSh', 'group' => 'system', 'type' => 'string', 'label' => 'Currency Symbol'],
            ['key' => 'language', 'value' => 'en', 'group' => 'system', 'type' => 'string', 'label' => 'Default Language'],

            // SECURITY
            ['key' => 'password_min_length', 'value' => '8', 'group' => 'security', 'type' => 'number', 'label' => 'Minimum Password Length'],
            ['key' => 'session_timeout', 'value' => '120', 'group' => 'security', 'type' => 'number', 'label' => 'Session Timeout (minutes)'],
            ['key' => 'max_login_attempts', 'value' => '5', 'group' => 'security', 'type' => 'number', 'label' => 'Max Login Attempts'],

            // NOTIFICATIONS
            ['key' => 'email_notifications', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean', 'label' => 'Email Notifications'],
            ['key' => 'leave_notifications', 'value' => '1', 'group' => 'notifications', 'type' => 'boolean', 'label' => 'Leave Request Notifications'],
            ['key' => 'attendance_notifications', 'value' => '0', 'group' => 'notifications', 'type' => 'boolean', 'label' => 'Attendance Notifications'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}

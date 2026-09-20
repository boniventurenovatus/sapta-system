<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AuditLog;

AuditLog::create([
    'user_id' => 3,
    'action' => 'created',
    'model_type' => 'App\Models\Position',
    'model_id' => 1,
    'description' => 'Created position: Board of Directors',
    'new_values' => ['title' => 'Board of Directors', 'code' => 'BOD-001'],
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0',
]);

AuditLog::create([
    'user_id' => 3,
    'action' => 'updated',
    'model_type' => 'App\Models\Employee',
    'model_id' => 1,
    'description' => 'Updated employee: Mary Smith',
    'old_values' => ['email' => 'old@example.com'],
    'new_values' => ['email' => 'new@example.com'],
    'ip_address' => '127.0.0.1',
]);

AuditLog::create([
    'user_id' => 3,
    'action' => 'login',
    'description' => 'User logged in',
    'ip_address' => '127.0.0.1',
]);

echo "Created 3 test audit logs\n";
echo "Total: " . AuditLog::count() . "\n";

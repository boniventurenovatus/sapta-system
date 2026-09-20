<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Notifications\TestNotification;

$user = User::first();
if ($user) {
    $user->notify(new TestNotification('Welcome to SAPTA Management System!'));
    $user->notify(new TestNotification('Your leave request has been approved.', '/leave-requests'));
    $user->notify(new TestNotification('New employee added to HR department.', '/employees'));
    echo "Created 3 notifications for: " . $user->username . "\n";
    echo "Total: " . $user->notifications()->count() . "\n";
} else {
    echo "No users found\n";
}

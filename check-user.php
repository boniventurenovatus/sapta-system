<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$user = User::where('username', 'superadmin')->first();
if ($user) {
    echo "Username: " . $user->username . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Status: " . $user->account_status . "\n";
    echo "Password hash length: " . strlen($user->password_hash) . "\n";
    echo "Password hash starts with: " . substr($user->password_hash, 0, 10) . "\n";
    echo "is_first_login: " . $user->is_first_login . "\n";
    echo "failed_login_attempts: " . $user->failed_login_attempts . "\n";
    echo "locked_until: " . ($user->locked_until ?? 'null') . "\n";
}

<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('username', 'superadmin')->first();
if ($user) {
    $user->password_hash = Hash::make('Sapta@2025!');
    $user->account_status = 'active';
    $user->is_first_login = 0;
    $user->failed_login_attempts = 0;
    $user->locked_until = null;
    $user->save();
    echo "=== PASSWORD RESET ===\n";
    echo "Username: superadmin\n";
    echo "Password: Sapta@2025!\n";
    echo "Email: " . $user->email . "\n";
} else {
    echo "User superadmin not found\n";
}

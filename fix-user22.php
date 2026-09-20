<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Update user 22 — kama uliyespend
$u = App\Models\User::find(22);
if ($u) {
    echo 'Kabla: ' . $u->username . ' | ' . ($u->account_status ?? 'NULL') . PHP_EOL;
    $u->account_status = 'suspended';
    $u->save();
    echo 'Baada: ' . $u->username . ' | ' . $u->account_status . PHP_EOL;
}

// Hesabu
echo PHP_EOL . '=== KPI ===' . PHP_EOL;
echo 'Total: ' . App\Models\User::count() . PHP_EOL;
echo 'Active: ' . App\Models\User::where('account_status', 'active')->count() . PHP_EOL;
echo 'Suspended: ' . App\Models\User::where('account_status', 'suspended')->count() . PHP_EOL;
echo 'Inactive: ' . App\Models\User::where('account_status', 'inactive')->count() . PHP_EOL;
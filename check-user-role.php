<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// User uliye-login
$user = App\Models\User::find(16);
echo 'User #16:' . PHP_EOL;
echo '  username: ' . $user->username . PHP_EOL;
echo '  email: ' . $user->email . PHP_EOL;
echo '  roles: ' . $user->roles->pluck('name')->implode(', ') . PHP_EOL;
echo '  hasRole(super_admin): ' . ($user->hasRole('super_admin') ? 'YES' : 'NO') . PHP_EOL;
echo '  hasRole(admin): ' . ($user->hasRole('admin') ? 'YES' : 'NO') . PHP_EOL;
echo '  hasRole(finance_manager): ' . ($user->hasRole('finance_manager') ? 'YES' : 'NO') . PHP_EOL;

echo PHP_EOL . '=== Routes za users ===' . PHP_EOL;
$routes = Illuminate\Support\Facades\Route::getRoutes();
foreach ($routes as $route) {
    if (str_starts_with($route->uri(), 'users')) {
        echo $route->uri() . ' | middleware: ' . implode(', ', $route->middleware()) . PHP_EOL;
    }
}
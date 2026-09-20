<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo '=== Gender values kwenye employees ===' . PHP_EOL;
App\Models\Employee::select('gender', DB::raw('count(*) as total'))
    ->groupBy('gender')
    ->get()
    ->each(function ($g) {
        echo '[' . ($g->gender ?? 'NULL') . '] → ' . $g->total . PHP_EOL;
    });

echo PHP_EOL . '=== Employee #36 ===' . PHP_EOL;
$e = App\Models\Employee::find(36);
if ($e) {
    echo '  gender: [' . ($e->gender ?? 'NULL') . ']' . PHP_EOL;
    echo '  employment_status: [' . ($e->employment_status ?? 'NULL') . ']' . PHP_EOL;
}
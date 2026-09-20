<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Wards: " . App\Models\Ward::count() . "\n";
foreach (App\Models\Ward::with('district')->get() as $w) {
    echo $w->district->name . ' | ' . $w->name . ' | ' . $w->code . "\n";
}

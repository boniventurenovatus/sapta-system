<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Districts: " . App\Models\District::count() . "\n";
foreach (App\Models\District::with('region')->get() as $d) {
    echo $d->region->name . ' | ' . $d->name . ' | ' . $d->code . "\n";
}

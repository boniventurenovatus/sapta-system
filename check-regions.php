<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Regions: " . App\Models\Region::count() . "\n";
foreach (App\Models\Region::all() as $r) {
    echo $r->id . ' | ' . $r->name . ' | ' . $r->code . "\n";
}

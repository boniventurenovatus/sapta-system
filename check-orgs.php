<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Organization;

echo "=== ORGANIZATIONS ===\n";
foreach (Organization::all() as $o) {
    echo $o->id . ' | ' . $o->name . ' | code: ' . ($o->code ?? '?') . "\n";
}

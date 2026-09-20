<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

DB::table('settings')->whereIn('key', ['app_logo_x', 'app_logo_y'])->delete();
echo "Deleted hidden logo fields\n";
echo "Total settings: " . DB::table('settings')->count() . "\n";

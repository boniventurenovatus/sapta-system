<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Tables (" . count(DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'")) . ") ===" . PHP_EOL . PHP_EOL;

$tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name");

foreach ($tables as $t) {
    $tableName = $t->table_name;
    $columns = Schema::getColumnListing($tableName);
    echo "📋 " . $tableName . " (" . count($columns) . " cols)" . PHP_EOL;
    echo "   " . implode(", ", $columns) . PHP_EOL . PHP_EOL;
}
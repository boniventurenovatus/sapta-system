<?php

$statuses = \App\Models\LeaveRequest::select("status", \DB::raw("count(*) as count"))
    ->groupBy("status")
    ->get();

foreach ($statuses as $s) {
    echo "Status: " . $s->status . " | Count: " . $s->count . PHP_EOL;
}

echo PHP_EOL . "=== LeaveRequest columns ===" . PHP_EOL;
$cols = \Schema::getColumnListing("leave_requests");
echo implode(", ", $cols) . PHP_EOL;
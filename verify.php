<?php
use App\Models\UserActivityLog;
echo "Total logs: " . UserActivityLog::count() . PHP_EOL;
echo "Today logs: " . UserActivityLog::whereDate("created_at", today())->count() . PHP_EOL;
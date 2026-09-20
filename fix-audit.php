<?php
$file = 'resources/views/audit-logs/show.blade.php';
$content = file_get_contents($file);

$content = str_replace("? '?'", "?? 'N/A'", $content);
$content = str_replace("?? '?'", "?? 'N/A'", $content);

file_put_contents($file, $content);
echo "Fixed Audit Logs ?\n";

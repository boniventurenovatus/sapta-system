<?php
$file = 'resources/views/audit-logs/show.blade.php';
$content = file_get_contents($file);

// Badilisha ??? kuwa ??
$content = str_replace("???", "??", $content);
// Badilisha model_type ? ... : '?' kuwa : 'N/A'
$content = str_replace(": '?'", ": 'N/A'", $content);

file_put_contents($file, $content);
echo "Fixed ??? to ??\n";

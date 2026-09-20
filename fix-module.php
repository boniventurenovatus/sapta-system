<?php
$file = 'resources/views/permissions/index.blade.php';
$content = file_get_contents($file);

// Badilisha ?? '?' kuwa ?? 'N/A'
$content = str_replace("?? '?'", "?? 'N/A'", $content);

file_put_contents($file, $content);
echo "Fixed\n";

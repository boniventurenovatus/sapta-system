<?php
$file = 'resources/views/organizations/index.blade.php';
$content = file_get_contents($file);

// Badilisha ?? '?' kuwa ?? '?' kwa hex pattern
$content = preg_replace('/\?\?\s*\'\?\'/', "?? '?'", $content);

file_put_contents($file, $content);
echo "Fixed with preg_replace hex\n";

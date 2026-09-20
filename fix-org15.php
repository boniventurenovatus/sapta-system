<?php
$file = 'resources/views/organizations/index.blade.php';
$content = file_get_contents($file);

// Badilisha ?? '?' kuwa ?? '?' kwa hex
$content = preg_replace('/\x3f\x3f \x27\x3f\x27/', "?? '?'", $content);

file_put_contents($file, $content);
echo "Fixed with hex regex\n";

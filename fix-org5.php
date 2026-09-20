<?php
$file = 'resources/views/organizations/index.blade.php';
$content = file_get_contents($file);

// Badilisha ?? '?' kuwa ?? '?' kwa preg_replace
$content = preg_replace("/\?\?\s*'\?'/", "?? '?'", $content);

file_put_contents($file, $content);
echo "Fixed with preg_replace\n";

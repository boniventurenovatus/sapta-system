<?php
$file = 'resources/views/organizations/index.blade.php';
$content = file_get_contents($file);

// Ondoa BOM
$content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

// Badilisha ?? '?' kuwa ?? '?'
$content = str_replace("?? '?'", "?? '?'", $content);

file_put_contents($file, $content);
echo "Fixed BOM + replace\n";

<?php
$file = 'resources/views/organizations/index.blade.php';
$content = file_get_contents($file);

// Badilisha ? kuwa ?
$content = str_replace("?? '?'", "?? '?'", $content);

file_put_contents($file, $content);
echo "Fixed ? to ?\n";

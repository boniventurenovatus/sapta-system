<?php
$file = 'resources/views/dashboard/index.blade.php';
$content = file_get_contents($file);

// Ondoa emoji
$content = str_replace('! ??', '!', $content);
$content = str_replace('! ??', '!', $content);
$content = str_replace('??', '', $content);

file_put_contents($file, $content);
echo "Removed emoji\n";

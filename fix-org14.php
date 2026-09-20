<?php
$file = 'resources/views/organizations/index.blade.php';
$content = file_get_contents($file);

// Badilisha ?? '?' kuwa ?? '?'
$old = "?? " . chr(39) . chr(63) . chr(39);
$new = "?? " . chr(39) . "?" . chr(39);
$content = str_replace($old, $new, $content);

file_put_contents($file, $content);
echo "Fixed with chr(63)\n";
echo "Old: " . bin2hex($old) . "\n";
echo "New: " . bin2hex($new) . "\n";

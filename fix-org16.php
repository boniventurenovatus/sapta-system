<?php
$file = 'resources/views/organizations/index.blade.php';
$content = file_get_contents($file);

// Badilisha ?? '?' kuwa ?? '?'
$content = str_replace("?? '?'", "?? '?'", $content);

file_put_contents($file, $content);
echo "Replaced\n";

// Angalia kama imebadilika
$content = file_get_contents($file);
if (strpos($content, "?? '?'") !== false) {
    echo "SUCCESS: ?? '?' FOUND\n";
} else {
    echo "FAIL: ?? '?' NOT FOUND\n";
}
if (strpos($content, "?? '?'") !== false) {
    echo "FAIL: ?? '?' STILL EXISTS\n";
} else {
    echo "SUCCESS: ?? '?' REMOVED\n";
}

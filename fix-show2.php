<?php
$file = 'resources/views/organizations/show.blade.php';
$content = file_get_contents($file);

// Badilisha kwa hex
$content = preg_replace('/\x3f\x3f \x27\x3f\x27/', "?? '?'", $content);

file_put_contents($file, $content);
echo "Fixed with hex\n";

// Angalia
$content = file_get_contents($file);
if (strpos($content, "?? '?'") !== false) {
    echo "FAIL: ?? '?' STILL EXISTS\n";
} else {
    echo "SUCCESS: ?? '?' REMOVED\n";
}

<?php
$file = 'resources/views/organizations/show.blade.php';
$content = file_get_contents($file);

// Badilisha ?? '?' kuwa ?? '?'
$content = str_replace("?? '?'", "?? '?'", $content);

file_put_contents($file, $content);
echo "Fixed show\n";

// Angalia
$content = file_get_contents($file);
if (strpos($content, "?? '?'") !== false) {
    echo "FAIL: ?? '?' STILL EXISTS\n";
} else {
    echo "SUCCESS: ?? '?' REMOVED\n";
}

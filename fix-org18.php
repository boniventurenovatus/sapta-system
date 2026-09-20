<?php
$file = 'resources/views/organizations/index.blade.php';
$content = file_get_contents($file);

// Badilisha ?? '?' kuwa ?? '?' ? mara mbili
$content = str_replace("?? '?'", "?? '?'", $content);
$content = str_replace("?? '?'", "?? '?'", $content);

file_put_contents($file, $content);
echo "Replaced twice\n";

// Angalia
$content = file_get_contents($file);
if (strpos($content, "?? '?'") !== false) {
    echo "FAIL: ?? '?' STILL EXISTS\n";
} else {
    echo "SUCCESS: ?? '?' REMOVED\n";
}
if (strpos($content, "?? '?'") !== false) {
    echo "SUCCESS: ?? '?' FOUND\n";
}

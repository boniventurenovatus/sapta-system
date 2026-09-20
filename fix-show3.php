<?php
$file = 'resources/views/organizations/show.blade.php';
$content = file_get_contents($file);

// Badilisha ?? '?' kuwa ?? '?' ? flexible
$content = preg_replace('/\?\?\s+\'\?\'/', "?? '?'", $content);
$content = preg_replace('/\?\? \'\?\'/', "?? '?'", $content);
$content = preg_replace('/\?\?\'?\'/', "?? '?'", $content);

file_put_contents($file, $content);
echo "Fixed with flexible regex\n";

// Angalia
$content = file_get_contents($file);
if (strpos($content, "?? '?'") !== false) {
    echo "FAIL: ?? '?' STILL EXISTS\n";
} else {
    echo "SUCCESS: ?? '?' REMOVED\n";
}

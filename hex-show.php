<?php
$file = 'resources/views/organizations/show.blade.php';
$content = file_get_contents($file);

$lines = explode("\n", $content);
foreach ($lines as $i => $line) {
    if (strpos($line, "?? '?'") !== false) {
        echo "LINE " . ($i + 1) . " HEX: " . bin2hex($line) . "\n";
    }
}

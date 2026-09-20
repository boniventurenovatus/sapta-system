<?php
$file = 'resources/views/organizations/edit.blade.php';
$content = file_get_contents($file);

// Badilisha ?? '?' kuwa ?? '?'
$old = chr(63) . chr(63) . chr(32) . chr(39) . chr(63) . chr(39);
$new = chr(63) . chr(63) . chr(32) . chr(39) . chr(226) . chr(128) . chr(148) . chr(39);
$content = str_replace($old, $new, $content);

file_put_contents($file, $content);
echo "Fixed edit\n";

// Angalia
$content = file_get_contents($file);
if (strpos($content, $old) !== false) {
    echo "FAIL: STILL EXISTS\n";
} else {
    echo "SUCCESS: REMOVED\n";
}

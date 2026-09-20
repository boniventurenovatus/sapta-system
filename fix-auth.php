<?php
$file = 'resources/views/dashboard/index.blade.php';
$content = file_get_contents($file);

// Rejesha ?? kwenye auth()->user()
$content = str_replace(
    "{{ auth()->user()->username  'User' }}",
    "{{ auth()->user()->username ?? 'User' }}",
    $content
);

file_put_contents($file, $content);
echo "Fixed auth()->user()\n";

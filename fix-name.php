<?php
$file = 'resources/views/permissions/index.blade.php';
$content = file_get_contents($file);

// Badilisha span ya Full Name kuwa div
$content = str_replace(
    '<span style="font-weight:700; color:#1e293b; text-decoration:none;">{{ $perm->full_name ?? $perm->name }}</span>',
    '<div style="font-weight:700; color:#1e293b; text-decoration:none;">{{ $perm->full_name ?? $perm->name }}</div>',
    $content
);

file_put_contents($file, $content);
echo "Fixed Full Name\n";

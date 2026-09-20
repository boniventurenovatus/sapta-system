<?php
$file = 'resources/views/permissions/index.blade.php';
$content = file_get_contents($file);

// Badilisha code kuwa span
$content = str_replace('<code>{{ $perm->name }}</code>', '<span style="background:#f1f5f9; padding:0.2rem 0.55rem; border-radius:0.3rem; font-size:0.78rem; color:#475569; font-weight:700; text-decoration:none;">{{ $perm->name }}</span>', $content);

file_put_contents($file, $content);
echo "Fixed code column\n";

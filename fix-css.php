<?php
$file = 'resources/views/permissions/index.blade.php';
$content = file_get_contents($file);

// Ongeza CSS ya nguvu
$css = '<style>
    .pm-page .pm-perm span, .pm-page .pm-perm span *, .pm-page td, .pm-page td * {
        text-decoration: none !important;
        border-bottom: none !important;
    }
</style>';

$content = str_replace('</style>', $css . "\n</style>", $content);

file_put_contents($file, $content);
echo "Added stronger CSS\n";

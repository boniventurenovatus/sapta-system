<?php
$file = 'resources/views/organizations/edit.blade.php';
$content = file_get_contents($file);

$css = '<style>
    .org-edit-page, .org-edit-card, .org-edit-section, .org-edit-grid, .org-edit-group {
        overflow: visible !important;
    }
    .org-edit-input {
        position: relative;
        z-index: 1;
    }
    .org-edit-input:focus {
        z-index: 100;
    }
</style>';

// Ongeza CSS mwanzoni
$content = str_replace('<style>', $css . "\n<style>", $content);

file_put_contents($file, $content);
echo "Added CSS\n";

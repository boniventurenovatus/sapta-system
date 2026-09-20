<?php
$file = 'resources/views/organizations/edit.blade.php';
$content = file_get_contents($file);

$css = '<style>
    select.org-edit-input {
        height: 42px !important;
        max-height: 42px !important;
        overflow: hidden !important;
        display: block !important;
        appearance: auto !important;
        -webkit-appearance: auto !important;
        -moz-appearance: auto !important;
    }
    select.org-edit-input option {
        display: block;
    }
</style>';

$content = str_replace('<style>', $css . "\n<style>", $content);

file_put_contents($file, $content);
echo "Added CSS for select\n";

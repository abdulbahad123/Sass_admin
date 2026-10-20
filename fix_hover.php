<?php

$file = 'd:/xamp/htdocs/Sass_admin/resources/views/landing/index.blade.php';
$content = file_get_contents($file);

// Fix the stats container hover bug in dark mode
$content = str_replace(
    'hover:bg-orange-50 dark:bg-[#1e293b]/50 transition-colors',
    'hover:bg-orange-50 dark:bg-[#1e293b]/50 dark:hover:bg-[#1e293b] transition-colors',
    $content
);

file_put_contents($file, $content);
echo "Stat hover fixed.\n";

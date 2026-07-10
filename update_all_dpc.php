<?php
// Script to update all DPC files with the new card design

$dpcFiles = [
    'dpc_bontang.blade.php',
    'dpc_balikpapan.blade.php',
    'dpc_berau.blade.php',
    'dpc_kukar.blade.php',
    'dpc_paser.blade.php',
    'dpc_penajam.blade.php',
    'dpc_kubar.blade.php',
    'dpc_kutim.blade.php',
    'dpc_mahulu.blade.php',
    'dpc_sangatta.blade.php'
];

$template = file_get_contents('resources/views/organization/dpc_samarinda.blade.php');

foreach ($dpcFiles as $file) {
    $filepath = "resources/views/organization/$file";
    if (file_exists($filepath)) {
        file_put_contents($filepath, $template);
        echo "Updated: $file\n";
    } else {
        echo "File not found: $file\n";
    }
}

echo "\nAll DPC files have been updated with the new card design!\n";

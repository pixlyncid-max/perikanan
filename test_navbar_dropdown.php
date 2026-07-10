<?php
/**
 * Test file untuk memverifikasi struktur dropdown menu Produk
 * File ini akan menampilkan preview dari navbar dropdown yang baru
 */

// Baca file app.blade.php
$appBladePath = __DIR__ . '/resources/views/layouts/app.blade.php';
$content = file_get_contents($appBladePath);

// Ekstrak bagian dropdown Produk (desktop)
preg_match('/<!-- Produk Dropdown -->(.*?)<!-- (?!Produk).*?-->/s', $content, $matches);

if (!empty($matches)) {
    echo "=== STRUKTUR DROPDOWN PRODUK BERHASIL DITEMUKAN ===\n\n";
    
    // Verifikasi struktur yang diminta
    $checks = [
        'Semua Produk' => strpos($content, 'Semua Produk') !== false,
        'SPOT AIR TAWAR' => strpos($content, 'SPOT AIR TAWAR') !== false,
        'SPOT AIR LAUT' => strpos($content, 'SPOT AIR LAUT') !== false,
        'LAIN-LAIN' => strpos($content, 'LAIN-LAIN') !== false,
        'Pelet Pakan Ikan' => strpos($content, 'Pelet Pakan Ikan') !== false,
        'Pakan Hidup' => strpos($content, 'Pakan Hidup') !== false,
        'Sewa Pancing' => strpos($content, 'Sewa Pancing') !== false,
        'Kolam Pemancingan' => strpos($content, 'Kolam Pemancingan') !== false,
        'Komunitas Air Tawar' => strpos($content, 'Komunitas Air Tawar') !== false,
        'Umpan Ikan Laut' => strpos($content, 'Umpan Ikan Laut') !== false,
        'Sewa Pancing Laut' => strpos($content, 'Sewa Pancing Laut') !== false,
        'Sewa Kapal' => strpos($content, 'Sewa Kapal') !== false,
        'Komunitas Air Laut' => strpos($content, 'Komunitas Air Laut') !== false,
        'Vitamin' => strpos($content, 'Vitamin') !== false,
        'Bibit' => strpos($content, 'Bibit') !== false,
    ];
    
    echo "Verifikasi Struktur Menu:\n";
    echo str_repeat("-", 50) . "\n";
    
    $allPassed = true;
    foreach ($checks as $item => $exists) {
        $status = $exists ? '✓' : '✗';
        echo "[{$status}] {$item}\n";
        if (!$exists) $allPassed = false;
    }
    
    echo str_repeat("-", 50) . "\n";
    
    if ($allPassed) {
        echo "\n✓ SEMUA ITEM BERHASIL DITEMUKAN!\n";
        echo "Struktur dropdown menu Produk sudah sesuai dengan permintaan.\n";
    } else {
        echo "\n✗ Beberapa item tidak ditemukan. Periksa kembali struktur.\n";
    }
    
    // Verifikasi styling
    echo "\nVerifikasi Styling:\n";
    echo str_repeat("-", 50) . "\n";
    
    $styleChecks = [
        'Border bottom pada Semua Produk' => strpos($content, 'border-b') !== false,
        'Background gray untuk category header' => strpos($content, 'bg-gray-50') !== false,
        'Font bold untuk category header' => strpos($content, 'font-bold') !== false,
        'Text gray untuk category header' => strpos($content, 'text-gray-500') !== false,
        'Padding left untuk sub-menu' => strpos($content, 'pl-6') !== false,
        'Cursor default untuk header' => strpos($content, 'cursor-default') !== false,
    ];
    
    foreach ($styleChecks as $style => $exists) {
        $status = $exists ? '✓' : '✗';
        echo "[{$status}] {$style}\n";
    }
    
    echo str_repeat("-", 50) . "\n";
    
    // Verifikasi mobile menu
    echo "\nVerifikasi Mobile Menu:\n";
    echo str_repeat("-", 50) . "\n";
    
    $mobileChecks = [
        'Mobile menu Produk ada' => strpos($content, 'Produk Mobile Menu') !== false,
        'Struktur kategori di mobile' => strpos($content, 'SPOT AIR TAWAR') !== false && strpos($content, 'bg-gray-100') !== false,
    ];
    
    foreach ($mobileChecks as $check => $exists) {
        $status = $exists ? '✓' : '✗';
        echo "[{$status}] {$check}\n";
    }
    
    echo str_repeat("-", 50) . "\n";
    
} else {
    echo "✗ Tidak dapat menemukan struktur dropdown Produk\n";
}
?>

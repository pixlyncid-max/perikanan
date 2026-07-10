<?php
/**
 * File pengujian untuk halaman kontak
 * Menguji apakah view contact.blade.php dapat dirender dengan benar
 */

echo "=== PENGUJIAN HALAMAN KONTAK ===\n\n";

// 1. Periksa apakah file ada
$file_path = 'resources/views/contact.blade.php';
if (file_exists($file_path)) {
    echo "✓ File contact.blade.php ditemukan\n";
} else {
    echo "✗ File contact.blade.php TIDAK ditemukan\n";
    exit(1);
}

// 2. Baca konten file
$content = file_get_contents($file_path);

// 3. Periksa komponen kunci
$checks = [
    'Google Maps iframe' => 'iframe',
    'Link Buka di Google Maps' => 'Buka di Google Maps',
    'Alamat baru' => 'Jl. Delima Dalam Blok E',
    'Link Google Maps URL' => 'google.com/maps/search',
    'Target blank' => 'target="_blank"',
    'Alamat lengkap' => 'Kalimantan Timur 75243',
];

echo "\n=== PEMERIKSAAN KOMPONEN ===\n";
foreach ($checks as $name => $pattern) {
    if (strpos($content, $pattern) !== false) {
        echo "✓ $name ditemukan\n";
    } else {
        echo "✗ $name TIDAK ditemukan\n";
    }
}

// 4. Periksa alamat lama sudah tidak ada
echo "\n=== PEMERIKSAAN ALAMAT LAMA ===\n";
$old_address = 'Jl. Slamet Riyadi No. 123';
if (strpos($content, $old_address) === false) {
    echo "✓ Alamat lama (Jl. Slamet Riyadi) sudah dihapus\n";
} else {
    echo "✗ Alamat lama (Jl. Slamet Riyadi) MASIH ADA\n";
}

// 5. Periksa struktur HTML
echo "\n=== PEMERIKSAAN STRUKTUR HTML ===\n";
$html_checks = [
    'Tag iframe ditutup' => '</iframe>',
    'Div map container' => '<!-- Map -->',
    'Rounded class' => 'rounded-lg',
    'Shadow class' => 'shadow-lg',
    'Height class' => 'h-80',
];

foreach ($html_checks as $name => $pattern) {
    if (strpos($content, $pattern) !== false) {
        echo "✓ $name ditemukan\n";
    } else {
        echo "✗ $name TIDAK ditemukan\n";
    }
}

// 6. Periksa URL Google Maps
echo "\n=== PEMERIKSAAN URL GOOGLE MAPS ===\n";
if (preg_match('/href="([^"]*google\.com\/maps[^"]*)"/', $content, $matches)) {
    echo "✓ URL Google Maps ditemukan: " . $matches[1] . "\n";
    
    // Periksa apakah URL mengandung alamat yang benar
    if (strpos($matches[1], 'Delima+Dalam') !== false || strpos($matches[1], 'Delima%20Dalam') !== false) {
        echo "✓ URL mengandung alamat Jl. Delima Dalam\n";
    } else {
        echo "⚠ URL mungkin tidak mengandung alamat lengkap\n";
    }
} else {
    echo "✗ URL Google Maps TIDAK ditemukan\n";
}

echo "\n=== PENGUJIAN SELESAI ===\n";
echo "Silakan buka http://localhost/perikanan/contact di browser untuk verifikasi visual.\n";
?>

<?php
/**
 * Script untuk memperbaiki data DPC yang double di database
 */

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Organization;

// Setup database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'fisheries_db',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== MEMERIKSA DATA DPC YANG DOUBLE ===\n\n";

// Cek semua DPC yang aktif
$dpcs = Organization::where('type', 'dpc')
    ->where('is_active', true)
    ->orderBy('city')
    ->get();

echo "Total DPC ditemukan: " . $dpcs->count() . "\n\n";

// Group by city untuk cek duplikat
$grouped = $dpcs->groupBy('city');
$duplicates = [];

foreach ($grouped as $city => $items) {
    $count = $items->count();
    echo "DPC $city: $count record\n";
    
    if ($count > 1) {
        $duplicates[$city] = $items;
    }
}

echo "\n";

if (empty($duplicates)) {
    echo "✅ Tidak ada data DPC yang double!\n";
    exit(0);
}

echo "⚠️  Ditemukan data double:\n";
foreach ($duplicates as $city => $items) {
    echo "\n=== $city ===\n";
    foreach ($items as $index => $dpc) {
        echo "ID: {$dpc->id}, Name: {$dpc->name}, City: {$dpc->city}, Display Order: {$dpc->display_order}\n";
    }
}

echo "\n\n=== MEMPERBAIKI DATA DOUBLE ===\n";

foreach ($duplicates as $city => $items) {
    echo "\nMemperbaiki DPC $city...\n";
    
    // Keep the first one, delete the rest
    $first = $items->first();
    $toDelete = $items->slice(1);
    
    foreach ($toDelete as $dpc) {
        echo "  Menghapus ID {$dpc->id} ({$dpc->name})...\n";
        $dpc->delete();
    }
    
    echo "  ✅ Menyimpan ID {$first->id} ({$first->name})\n";
}

echo "\n\n=== VERIFIKASI SETELAH PERBAIKAN ===\n";

$dpcsAfter = Organization::where('type', 'dpc')
    ->where('is_active', true)
    ->orderBy('display_order')
    ->get();

echo "Total DPC setelah perbaikan: " . $dpcsAfter->count() . "\n\n";

$groupedAfter = $dpcsAfter->groupBy('city');
foreach ($groupedAfter as $city => $items) {
    $count = $items->count();
    $status = $count > 1 ? "❌ MASIH DOUBLE" : "✅ OK";
    echo "DPC $city: $count record $status\n";
}

echo "\n=== SELESAI ===\n";

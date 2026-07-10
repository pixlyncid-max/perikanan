<?php

// Test script untuk verifikasi integrasi admin panel

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Admin;
use Illuminate\Support\Facades\Session;

// Bootstrap aplikasi
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "=== TEST INTEGRASI ADMIN PANEL ===\n\n";

// Test 1: Cek tabel admins
echo "1. Mengecek tabel admins...\n";
try {
    $adminCount = Admin::count();
    echo "   ✓ Tabel admins ditemukan\n";
    echo "   ✓ Jumlah admin: {$adminCount}\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 2: Cek data admin
echo "\n2. Mengecek data admin...\n";
try {
    $admins = Admin::all();
    foreach ($admins as $admin) {
        echo "   ✓ Admin: {$admin->full_name} ({$admin->email}) - Status: {$admin->account_status}\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 3: Cek middleware
echo "\n3. Mengecek AdminMiddleware...\n";
$middlewarePath = __DIR__ . '/app/Http/Middleware/AdminMiddleware.php';
if (file_exists($middlewarePath)) {
    $content = file_get_contents($middlewarePath);
    if (strpos($content, "Session::has('user')") !== false) {
        echo "   ✓ Middleware menggunakan Session::has('user')\n";
    } else {
        echo "   ✗ Middleware tidak menggunakan Session::has('user')\n";
    }
    if (strpos($content, "Admin::where('email'") !== false) {
        echo "   ✓ Middleware memverifikasi email di tabel admins\n";
    } else {
        echo "   ✗ Middleware tidak memverifikasi email di tabel admins\n";
    }
} else {
    echo "   ✗ File AdminMiddleware.php tidak ditemukan\n";
}

// Test 4: Cek route
echo "\n4. Mengecek route admin...\n";
$routePath = __DIR__ . '/routes/web.php';
if (file_exists($routePath)) {
    $content = file_get_contents($routePath);
    if (strpos($content, "middleware(['web', 'admin'])") !== false) {
        echo "   ✓ Route admin menggunakan middleware ['web', 'admin']\n";
    } else {
        echo "   ✗ Route admin tidak menggunakan middleware yang benar\n";
    }
    if (strpos($content, "/admin/dashboard") !== false) {
        echo "   ✓ Route /admin/dashboard ditemukan\n";
    } else {
        echo "   ✗ Route /admin/dashboard tidak ditemukan\n";
    }
} else {
    echo "   ✗ File web.php tidak ditemukan\n";
}

// Test 5: Cek view
echo "\n5. Mengecek view layouts/app.blade.php...\n";
$viewPath = __DIR__ . '/resources/views/layouts/app.blade.php';
if (file_exists($viewPath)) {
    $content = file_get_contents($viewPath);
    if (strpos($content, 'href="/admin/dashboard"') !== false) {
        echo "   ✓ Tombol Panel Admin mengarah ke /admin/dashboard\n";
    } else {
        echo "   ✗ Tombol Panel Admin tidak mengarah ke /admin/dashboard\n";
    }
    if (strpos($content, "if(\$user['type'] === 'admin')") !== false) {
        echo "   ✓ Tombol Panel Admin hanya muncul untuk admin\n";
    } else {
        echo "   ✗ Kondisi admin tidak ditemukan\n";
    }
} else {
    echo "   ✗ File app.blade.php tidak ditemukan\n";
}

// Test 6: Cek model Admin
echo "\n6. Mengecek model Admin...\n";
$modelPath = __DIR__ . '/app/Models/Admin.php';
if (file_exists($modelPath)) {
    $content = file_get_contents($modelPath);
    if (strpos($content, "'full_name'") !== false) {
        echo "   ✓ Model menggunakan full_name\n";
    } else {
        echo "   ✗ Model tidak menggunakan full_name\n";
    }
    if (strpos($content, "'account_status'") !== false) {
        echo "   ✓ Model menggunakan account_status\n";
    } else {
        echo "   ✗ Model tidak menggunakan account_status\n";
    }
} else {
    echo "   ✗ File Admin.php tidak ditemukan\n";
}

echo "\n=== TEST SELESAI ===\n";
echo "\nCara penggunaan:\n";
echo "1. Login dengan akun admin:\n";
echo "   Email: superadmin@fisheries.com\n";
echo "   Password: admin123\n";
echo "2. Klik dropdown profil di kanan atas\n";
echo "3. Klik 'Panel Admin' untuk mengakses dashboard\n";

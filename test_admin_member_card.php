<?php
/**
 * Test Script untuk Admin Member Card
 * 
 * Test kasus: Admin tanpa record di tabel members tetap bisa akses kartu anggota
 */

echo "=== TESTING: Admin Member Card Access ===\n\n";

$baseUrl = 'http://localhost/perikanan/public';

// Step 1: Get CSRF token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'test_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_cookies.txt');

$loginPage = curl_exec($ch);
curl_close($ch);

preg_match('/name="_token" value="([^"]+)"/', $loginPage, $matches);
$csrfToken = $matches[1] ?? null;

if (!$csrfToken) {
    echo "❌ Could not retrieve CSRF token\n";
    exit(1);
}

echo "✅ CSRF Token Retrieved\n";

// Step 2: Login sebagai admin
// Asumsi ada admin dengan email admin@fisheries.com / password admin123
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'test_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_cookies.txt');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_token' => $csrfToken,
    'email' => 'admin@fisheries.com',
    'password' => 'admin123',
    'remember' => 'on'
]));

$loginResponse = curl_exec($ch);
$loginCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$loginRedirect = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

echo "Login HTTP Code: " . $loginCode . "\n";
echo "Login Redirect: " . $loginRedirect . "\n";

curl_close($ch);

// Step 3: Akses member card
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/member-card');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'test_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_cookies.txt');

$memberCardResponse = curl_exec($ch);
$memberCardCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$memberCardRedirect = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

echo "\nMember Card Access:\n";
echo "HTTP Code: " . $memberCardCode . "\n";
echo "Final URL: " . $memberCardRedirect . "\n";

// Check if error message appears
if (strpos($memberCardResponse, 'Data anggota admin tidak ditemukan') !== false) {
    echo "❌ ERROR: Admin masih mendapat pesan error\n";
} elseif (strpos($memberCardResponse, 'Kartu Anggota') !== false || 
          strpos($memberCardResponse, 'member-card') !== false ||
          strpos($memberCardResponse, 'ADMIN-') !== false) {
    echo "✅ SUCCESS: Admin dapat mengakses kartu anggota\n";
    
    // Check for virtual member number
    if (preg_match('/ADMIN-\d+/', $memberCardResponse, $matches)) {
        echo "✅ Virtual Member Number Found: " . $matches[0] . "\n";
    }
} else {
    echo "⚠️  Response tidak jelas, perlu cek manual\n";
    echo "Response snippet: " . substr($memberCardResponse, 0, 500) . "\n";
}

curl_close($ch);

// Cleanup
if (file_exists('test_cookies.txt')) {
    unlink('test_cookies.txt');
}

echo "\n=== TEST COMPLETE ===\n";

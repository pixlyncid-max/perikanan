<?php
// Test file untuk memeriksa autentikasi
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\Session;

echo "<h1>Test Autentikasi</h1>";

// Test 1: Cek session
echo "<h2>1. Session Status</h2>";
echo "Session ID: " . session_id() . "<br>";
echo "Session Name: " . session_name() . "<br>";

// Test 2: Cek apakah user sudah login
echo "<h2>2. Login Status</h2>";
$hasUser = Session::has('user');
echo "Session has 'user': " . ($hasUser ? 'YES' : 'NO') . "<br>";

if ($hasUser) {
    $user = Session::get('user');
    echo "User data:<br><pre>";
    print_r($user);
    echo "</pre>";
}

// Test 3: Cek helper functions
echo "<h2>3. Helper Functions</h2>";
$functions = ['is_logged_in', 'get_current_user_role', 'is_admin', 'is_member', 'is_user'];
foreach ($functions as $func) {
    if (function_exists($func)) {
        try {
            $result = $func();
            echo "$func(): " . var_export($result, true) . "<br>";
        } catch (Exception $e) {
            echo "$func(): ERROR - " . $e->getMessage() . "<br>";
        }
    } else {
        echo "$func(): NOT FOUND<br>";
    }
}

// Test 4: Set test user
echo "<h2>4. Set Test User</h2>";
Session::put('user', [
    'id' => 999,
    'name' => 'Test User',
    'email' => 'test@test.com',
    'type' => 'member',
    'table' => 'members'
]);
echo "Test user set. Refresh page to see if it's detected.<br>";

echo "<hr><p>Test completed at " . date('Y-m-d H:i:s') . "</p>";

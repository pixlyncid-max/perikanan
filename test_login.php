<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\AuthController;

// Simulate POST data for login
$_POST = [
    'email' => 'testweb2@example.com',
    'password' => 'password123',
    'remember' => false
];

// Create controller instance
$controller = new AuthController();

// Call login method
try {
    $response = $controller->login();
    echo "Login successful!\n";
    echo "Response type: " . get_class($response) . "\n";

    // Check session
    $session = Illuminate\Support\Facades\Session::get('user');
    if ($session) {
        echo "Session data:\n";
        echo "ID: " . $session['id'] . "\n";
        echo "Name: " . $session['name'] . "\n";
        echo "Email: " . $session['email'] . "\n";
        echo "Type: " . $session['type'] . "\n";
        echo "Table: " . $session['table'] . "\n";
    } else {
        echo "No session data found\n";
    }
} catch (Exception $e) {
    echo "Login failed: " . $e->getMessage() . "\n";
}
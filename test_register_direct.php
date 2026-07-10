<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\AuthController;

// Simulate POST data
$_POST = [
    'registration_type' => 'user',
    'name' => 'Test User Web 2',
    'email' => 'testweb2@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'phone' => '081234567891',
    'address' => 'Test Address 2',
    'terms' => 'on'
];

// Create controller instance
$controller = new AuthController();

// Call register method
try {
    $response = $controller->register();
    echo "Registration successful!\n";
    echo "Response type: " . get_class($response) . "\n";

    // Check if user was created
    $user = App\Models\User::where('email', 'testweb2@example.com')->first();
    if ($user) {
        echo "User created with ID: " . $user->id . "\n";
        echo "User name: " . $user->name . "\n";
        echo "User email: " . $user->email . "\n";
        echo "User status: " . $user->status . "\n";
    } else {
        echo "User was not created\n";
    }
} catch (Exception $e) {
    echo "Registration failed: " . $e->getMessage() . "\n";
}
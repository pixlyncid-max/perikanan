<?php
/**
 * Test script to verify admin panel fixes
 * Run this in browser: http://localhost/perikanan/test_admin_panel.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Session;

// Initialize Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; background: #e8f5e9; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { color: red; background: #ffebee; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { color: blue; background: #e3f2fd; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .section { border: 1px solid #ddd; padding: 15px; margin: 15px 0; border-radius: 5px; }
        h2 { color: #333; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 3px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Admin Panel Diagnostic Test</h1>
";

// Test 1: Check if routes are defined
echo "<div class='section'>";
echo "<h2>1. Route Test</h2>";
try {
    $routes = Route::getRoutes();
    $adminRoutes = [];
    foreach ($routes as $route) {
        $name = $route->getName();
        if (strpos($name, 'admin.') === 0) {
            $adminRoutes[] = [
                'name' => $name,
                'uri' => $route->uri(),
                'methods' => $route->methods()
            ];
        }
    }
    
    if (count($adminRoutes) > 0) {
        echo "<div class='success'>✓ Found " . count($adminRoutes) . " admin routes</div>";
        echo "<pre>";
        foreach ($adminRoutes as $route) {
            echo implode('|', $route['methods']) . " " . $route['uri'] . " => " . $route['name'] . "\n";
        }
        echo "</pre>";
    } else {
        echo "<div class='error'>✗ No admin routes found</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Route test failed: " . $e->getMessage() . "</div>";
}
echo "</div>";

// Test 2: Check if view files exist
echo "<div class='section'>";
echo "<h2>2. View Files Test</h2>";
$requiredViews = [
    'admin.layouts.app',
    'admin.layouts.navbar',
    'admin.layouts.sidebar',
    'admin.dashboard.index',
];

foreach ($requiredViews as $view) {
    $viewPath = resource_path('views/' . str_replace('.', '/', $view) . '.blade.php');
    if (file_exists($viewPath)) {
        echo "<div class='success'>✓ View exists: $view</div>";
    } else {
        echo "<div class='error'>✗ View missing: $view</div>";
    }
}
echo "</div>";

// Test 3: Check if middleware is registered
echo "<div class='section'>";
echo "<h2>3. Middleware Test</h2>";
try {
    $kernel = app(Illuminate\Contracts\Http\Kernel::class);
    $middleware = $kernel->getMiddlewareGroups();
    
    if (isset($middleware['admin'])) {
        echo "<div class='success'>✓ Admin middleware group is registered</div>";
        echo "<pre>";
        print_r($middleware['admin']);
        echo "</pre>";
    } else {
        echo "<div class='info'>ℹ Admin middleware is not in a group, checking route middleware...</div>";
    }
    
    // Check if AdminMiddleware class exists
    if (class_exists(\App\Http\Middleware\AdminMiddleware::class)) {
        echo "<div class='success'>✓ AdminMiddleware class exists</div>";
    } else {
        echo "<div class='error'>✗ AdminMiddleware class not found</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Middleware test failed: " . $e->getMessage() . "</div>";
}
echo "</div>";

// Test 4: Check session configuration
echo "<div class='section'>";
echo "<h2>4. Session Configuration Test</h2>";
try {
    $sessionDriver = config('session.driver');
    echo "<div class='info'>Session driver: $sessionDriver</div>";
    
    // Try to start session
    if (!Session::isStarted()) {
        Session::start();
    }
    
    // Test session write/read
    Session::put('test_key', 'test_value');
    $testValue = Session::get('test_key');
    
    if ($testValue === 'test_value') {
        echo "<div class='success'>✓ Session is working correctly</div>";
    } else {
        echo "<div class='error'>✗ Session test failed</div>";
    }
    
    Session::forget('test_key');
} catch (Exception $e) {
    echo "<div class='error'>✗ Session test failed: " . $e->getMessage() . "</div>";
}
echo "</div>";

// Test 5: Check database connection
echo "<div class='section'>";
echo "<h2>5. Database Connection Test</h2>";
try {
    $connection = DB::connection()->getPdo();
    echo "<div class='success'>✓ Database connection successful</div>";
    
    // Check if admins table exists
    if (Schema::hasTable('admins')) {
        $adminCount = \App\Models\Admin::count();
        echo "<div class='success'>✓ Admins table exists with $adminCount records</div>";
    } else {
        echo "<div class='error'>✗ Admins table not found</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Database connection failed: " . $e->getMessage() . "</div>";
}
echo "</div>";

// Test 6: Check for common issues
echo "<div class='section'>";
echo "<h2>6. Common Issues Check</h2>";

// Check for syntax errors in view files
$viewFiles = [
    resource_path('views/admin/layouts/app.blade.php'),
    resource_path('views/admin/dashboard/index.blade.php'),
];

foreach ($viewFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Check for unclosed directives
        $openExtends = substr_count($content, '@extends');
        $openSection = substr_count($content, '@section');
        $closeSection = substr_count($content, '@endsection');
        $openYield = substr_count($content, '@yield');
        
        $issues = [];
        if ($openSection !== $closeSection) {
            $issues[] = "Unmatched @section/@endsection ($openSection vs $closeSection)";
        }
        
        if (empty($issues)) {
            echo "<div class='success'>✓ No obvious syntax issues in " . basename($file) . "</div>";
        } else {
            echo "<div class='error'>✗ Issues in " . basename($file) . ": " . implode(', ', $issues) . "</div>";
        }
    }
}
echo "</div>";

// Test 7: Admin access simulation
echo "<div class='section'>";
echo "<h2>7. Admin Access Simulation</h2>";
try {
    // Create a mock admin session
    Session::put('user', [
        'id' => 1,
        'name' => 'Test Admin',
        'email' => 'admin@fisheries.com',
        'type' => 'admin'
    ]);
    
    echo "<div class='success'>✓ Mock admin session created</div>";
    
    // Check if we can retrieve it
    $user = Session::get('user');
    if ($user && $user['type'] === 'admin') {
        echo "<div class='success'>✓ Admin session data is valid</div>";
        echo "<pre>";
        print_r($user);
        echo "</pre>";
    } else {
        echo "<div class='error'>✗ Admin session data is invalid</div>";
    }
    
    // Clean up
    Session::forget('user');
} catch (Exception $e) {
    echo "<div class='error'>✗ Admin access simulation failed: " . $e->getMessage() . "</div>";
}
echo "</div>";

// Summary
echo "<div class='section'>";
echo "<h2>Summary</h2>";
echo "<div class='info'>";
echo "<p>If all tests show ✓ (success), the admin panel should be working.</p>";
echo "<p>If you see ✗ (errors), check the specific issues mentioned above.</p>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Clear cache: <code>php artisan cache:clear</code></li>";
echo "<li>Clear view cache: <code>php artisan view:clear</code></li>";
echo "<li>Clear route cache: <code>php artisan route:clear</code></li>";
echo "<li>Try accessing: <a href='/admin/dashboard' target='_blank'>/admin/dashboard</a></li>";
echo "</ol>";
echo "</div>";
echo "</div>";

echo "</body></html>";

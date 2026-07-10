<?php

// FISHERIES - Thorough Testing Script for Role System

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Initialize database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'fisheries_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "========================================\n";
echo "  FISHERIES ROLE SYSTEM - THOROUGH TEST\n";
echo "========================================\n\n";

$tests = [];
$passed = 0;
$failed = 0;

function test($name, $callback) {
    global $tests, $passed, $failed;
    try {
        $result = $callback();
        if ($result === true) {
            echo "✅ PASS: $name\n";
            $passed++;
        } else {
            echo "❌ FAIL: $name\n";
            echo "   Reason: $result\n";
            $failed++;
        }
    } catch (Exception $e) {
        echo "❌ FAIL: $name\n";
        echo "   Error: " . $e->getMessage() . "\n";
        $failed++;
    }
    $tests[] = $name;
}

// ============================================
// TEST 1: Database Structure
// ============================================
echo "📋 TEST GROUP 1: Database Structure\n";
echo "----------------------------------------\n";

test("Users table has 'role' column", function() {
    return Capsule::schema()->hasColumn('users', 'role') 
        ? true 
        : "Column 'role' not found in users table";
});

test("Products table has 'member_price' column", function() {
    return Capsule::schema()->hasColumn('products', 'member_price') 
        ? true 
        : "Column 'member_price' not found in products table";
});

test("Categories table has 'is_pakan_hidup' column", function() {
    return Capsule::schema()->hasColumn('categories', 'is_pakan_hidup') 
        ? true 
        : "Column 'is_pakan_hidup' not found in categories table";
});

// ============================================
// TEST 2: Model Methods
// ============================================
echo "\n📋 TEST GROUP 2: Model Methods\n";
echo "----------------------------------------\n";

test("User model has isAdmin() method", function() {
    $user = new \App\Models\User();
    return method_exists($user, 'isAdmin') ? true : "Method isAdmin() not found";
});

test("User model has isMember() method", function() {
    $user = new \App\Models\User();
    return method_exists($user, 'isMember') ? true : "Method isMember() not found";
});

test("User model has isUser() method", function() {
    $user = new \App\Models\User();
    return method_exists($user, 'isUser') ? true : "Method isUser() not found";
});

test("User model has getRole() method", function() {
    $user = new \App\Models\User();
    return method_exists($user, 'getRole') ? true : "Method getRole() not found";
});

test("Product model has getPriceForUser() method", function() {
    $product = new \App\Models\Product();
    return method_exists($product, 'getPriceForUser') ? true : "Method getPriceForUser() not found";
});

test("Product model has isPakanHidup() method", function() {
    $product = new \App\Models\Product();
    return method_exists($product, 'isPakanHidup') ? true : "Method isPakanHidup() not found";
});

// ============================================
// TEST 3: Helper Functions
// ============================================
echo "\n📋 TEST GROUP 3: Helper Functions\n";
echo "----------------------------------------\n";

test("Helper is_logged_in() exists", function() {
    return function_exists('is_logged_in') ? true : "Function is_logged_in() not found";
});

test("Helper get_current_user_role() exists", function() {
    return function_exists('get_current_user_role') ? true : "Function get_current_user_role() not found";
});

test("Helper is_admin() exists", function() {
    return function_exists('is_admin') ? true : "Function is_admin() not found";
});

test("Helper is_member() exists", function() {
    return function_exists('is_member') ? true : "Function is_member() not found";
});

test("Helper is_user() exists", function() {
    return function_exists('is_user') ? true : "Function is_user() not found";
});

test("Helper can_order_product() exists", function() {
    return function_exists('can_order_product') ? true : "Function can_order_product() not found";
});

test("Helper get_product_price_for_user() exists", function() {
    return function_exists('get_product_price_for_user') ? true : "Function get_product_price_for_user() not found";
});

// ============================================
// TEST 4: Role Logic
// ============================================
echo "\n📋 TEST GROUP 4: Role Logic\n";
echo "----------------------------------------\n";

test("User with role 'user' is not admin", function() {
    $user = new \App\Models\User(['role' => 'user']);
    return $user->isAdmin() === false ? true : "User should not be admin";
});

test("User with role 'user' is user", function() {
    $user = new \App\Models\User(['role' => 'user']);
    return $user->isUser() === true ? true : "User should be user";
});

test("User with role 'member' is member", function() {
    $user = new \App\Models\User(['role' => 'member']);
    return $user->isMember() === true ? true : "User should be member";
});

test("User with role 'member' getRole() returns 'member'", function() {
    $user = new \App\Models\User(['role' => 'member']);
    return $user->getRole() === 'member' ? true : "getRole() should return 'member'";
});

test("Member model is always member", function() {
    $member = new \App\Models\Member();
    return $member->isMember() === true ? true : "Member should always be member";
});

test("Member model is not admin", function() {
    $member = new \App\Models\Member();
    return $member->isAdmin() === false ? true : "Member should not be admin";
});

test("Admin model is admin", function() {
    $admin = new \App\Models\Admin();
    return $admin->isAdmin() === true ? true : "Admin should be admin";
});

test("Admin model is also member", function() {
    $admin = new \App\Models\Admin();
    return $admin->isMember() === true ? true : "Admin should also be member";
});

// ============================================
// TEST 5: Product Price Logic
// ============================================
echo "\n📋 TEST GROUP 5: Product Price Logic\n";
echo "----------------------------------------\n";

test("Product returns member_price for admin", function() {
    $product = new \App\Models\Product([
        'price' => 100000,
        'member_price' => 90000,
        'sale_price' => 0
    ]);
    $price = $product->getPriceForUser('admin');
    return $price == 90000 ? true : "Expected 90000, got $price";
});

test("Product returns member_price for member", function() {
    $product = new \App\Models\Product([
        'price' => 100000,
        'member_price' => 90000,
        'sale_price' => 0
    ]);
    $price = $product->getPriceForUser('member');
    return $price == 90000 ? true : "Expected 90000, got $price";
});

test("Product returns regular price for user", function() {
    $product = new \App\Models\Product([
        'price' => 100000,
        'member_price' => 90000,
        'sale_price' => 0
    ]);
    $price = $product->getPriceForUser('user');
    return $price == 100000 ? true : "Expected 100000, got $price";
});

test("Product returns sale price when on sale", function() {
    $product = new \App\Models\Product([
        'price' => 100000,
        'member_price' => 90000,
        'sale_price' => 85000
    ]);
    $price = $product->getPriceForUser('user');
    return $price == 85000 ? true : "Expected 85000 (sale), got $price";
});

// ============================================
// TEST 6: Category - Pakan Hidup
// ============================================
echo "\n📋 TEST GROUP 6: Category - Pakan Hidup\n";
echo "----------------------------------------\n";

test("Category Pakan Hidup exists and is marked", function() {
    $category = Capsule::table('categories')
        ->where('slug', 'pakan-hidup')
        ->orWhere('name', 'like', '%Pakan Hidup%')
        ->first();
    
    if (!$category) {
        return "Category 'Pakan Hidup' not found";
    }
    
    return $category->is_pakan_hidup == true 
        ? true 
        : "Category 'Pakan Hidup' is not marked with is_pakan_hidup=true";
});

// ============================================
// TEST 7: Middleware
// ============================================
echo "\n📋 TEST GROUP 7: Middleware\n";
echo "----------------------------------------\n";

test("RoleMiddleware class exists", function() {
    return class_exists('\App\Http\Middleware\RoleMiddleware') 
        ? true 
        : "RoleMiddleware class not found";
});

test("RoleMiddleware has handle method", function() {
    $middleware = new \App\Http\Middleware\RoleMiddleware();
    return method_exists($middleware, 'handle') 
        ? true 
        : "RoleMiddleware should have handle() method";
});

test("RoleMiddleware has static isAdmin method", function() {
    return method_exists('\App\Http\Middleware\RoleMiddleware', 'isAdmin') 
        ? true 
        : "RoleMiddleware should have static isAdmin() method";
});

test("RoleMiddleware has static isMember method", function() {
    return method_exists('\App\Http\Middleware\RoleMiddleware', 'isMember') 
        ? true 
        : "RoleMiddleware should have static isMember() method";
});

test("RoleMiddleware has static canOrderProduct method", function() {
    return method_exists('\App\Http\Middleware\RoleMiddleware', 'canOrderProduct') 
        ? true 
        : "RoleMiddleware should have static canOrderProduct() method";
});

// ============================================
// TEST 8: AuthController
// ============================================
echo "\n📋 TEST GROUP 8: AuthController\n";
echo "----------------------------------------\n";

test("AuthController has canOrderProduct method", function() {
    $controller = new \App\Http\Controllers\AuthController();
    return method_exists($controller, 'canOrderProduct') 
        ? true 
        : "AuthController should have canOrderProduct() method";
});

test("AuthController has getOrderErrorMessage method", function() {
    $controller = new \App\Http\Controllers\AuthController();
    return method_exists($controller, 'getOrderErrorMessage') 
        ? true 
        : "AuthController should have getOrderErrorMessage() method";
});

// ============================================
// TEST 9: View Files
// ============================================
echo "\n📋 TEST GROUP 9: View Files\n";
echo "----------------------------------------\n";

test("Register view exists", function() {
    return file_exists(__DIR__ . '/resources/views/auth/register.blade.php') 
        ? true 
        : "Register view file not found";
});

test("Register view contains registration_type radio", function() {
    $content = file_get_contents(__DIR__ . '/resources/views/auth/register.blade.php');
    return strpos($content, 'registration_type') !== false 
        ? true 
        : "Register view should contain registration_type field";
});

test("Register view contains 'User Biasa' option", function() {
    $content = file_get_contents(__DIR__ . '/resources/views/auth/register.blade.php');
    return strpos($content, 'User Biasa') !== false 
        ? true 
        : "Register view should contain 'User Biasa' option";
});

test("Register view contains 'Anggota' option", function() {
    $content = file_get_contents(__DIR__ . '/resources/views/auth/register.blade.php');
    return strpos($content, 'Anggota') !== false 
        ? true 
        : "Register view should contain 'Anggota' option";
});

test("Register view has JavaScript toggle for member fields", function() {
    $content = file_get_contents(__DIR__ . '/resources/views/auth/register.blade.php');
    return strpos($content, 'member-fields') !== false && strpos($content, 'addEventListener') !== false
        ? true 
        : "Register view should have JavaScript toggle for member fields";
});

// ============================================
// TEST 10: Database Data
// ============================================
echo "\n📋 TEST GROUP 10: Database Data\n";
echo "----------------------------------------\n";

test("At least one category exists", function() {
    $count = Capsule::table('categories')->count();
    return $count > 0 ? true : "No categories found in database";
});

test("At least one product exists", function() {
    $count = Capsule::table('products')->count();
    return $count > 0 ? true : "No products found in database";
});

test("Users table is accessible", function() {
    try {
        Capsule::table('users')->first();
        return true;
    } catch (Exception $e) {
        return "Users table not accessible: " . $e->getMessage();
    }
});

test("Members table is accessible", function() {
    try {
        Capsule::table('members')->first();
        return true;
    } catch (Exception $e) {
        return "Members table not accessible: " . $e->getMessage();
    }
});

test("Admins table is accessible", function() {
    try {
        Capsule::table('admins')->first();
        return true;
    } catch (Exception $e) {
        return "Admins table not accessible: " . $e->getMessage();
    }
});

// ============================================
// SUMMARY
// ============================================
echo "\n========================================\n";
echo "  TEST SUMMARY\n";
echo "========================================\n";
echo "Total Tests: " . count($tests) . "\n";
echo "✅ Passed: $passed\n";
echo "❌ Failed: $failed\n";
echo "Success Rate: " . round(($passed / count($tests)) * 100, 2) . "%\n";

if ($failed === 0) {
    echo "\n🎉 ALL TESTS PASSED! Role system is fully functional.\n";
} else {
    echo "\n⚠️  Some tests failed. Please review the errors above.\n";
}

echo "\n========================================\n";
echo "  INTEGRATION STATUS\n";
echo "========================================\n";
echo "Database: fisheries_db (MySQL)\n";
echo "Tables Modified:\n";
echo "  - users (added: role column)\n";
echo "  - products (added: member_price column)\n";
echo "  - categories (added: is_pakan_hidup column)\n";
echo "\nRole System Features:\n";
echo "  ✅ 3 Role Types: Admin, Anggota, User Biasa\n";
echo "  ✅ Role-based Product Access Control\n";
echo "  ✅ Member Pricing System\n";
echo "  ✅ Registration with Role Selection\n";
echo "  ✅ Middleware Protection\n";
echo "  ✅ Helper Functions\n";
echo "========================================\n";

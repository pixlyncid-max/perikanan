<?php

// FISHERIES - Run Role System Migrations

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

echo "=== FISHERIES Role System Migration ===\n\n";

try {
    // 1. Add role column to users table (tanpa after karena status mungkin tidak ada)
    echo "1. Adding 'role' column to users table...\n";
    if (!Capsule::schema()->hasColumn('users', 'role')) {
        Capsule::schema()->table('users', function ($table) {
            $table->enum('role', ['user', 'member'])->default('user');
        });
        echo "   ✓ Column 'role' added successfully\n";
    } else {
        echo "   ℹ Column 'role' already exists\n";
    }

    // 2. Add member_price column to products table
    echo "\n2. Adding 'member_price' column to products table...\n";
    if (!Capsule::schema()->hasColumn('products', 'member_price')) {
        Capsule::schema()->table('products', function ($table) {
            $table->decimal('member_price', 12, 2)->default(0);
        });
        echo "   ✓ Column 'member_price' added successfully\n";
    } else {
        echo "   ℹ Column 'member_price' already exists\n";
    }

    // 3. Add is_pakan_hidup column to categories table
    echo "\n3. Adding 'is_pakan_hidup' column to categories table...\n";
    if (!Capsule::schema()->hasColumn('categories', 'is_pakan_hidup')) {
        Capsule::schema()->table('categories', function ($table) {
            $table->boolean('is_pakan_hidup')->default(false);
        });
        echo "   ✓ Column 'is_pakan_hidup' added successfully\n";
    } else {
        echo "   ℹ Column 'is_pakan_hidup' already exists\n";
    }

    // 4. Update existing categories to set is_pakan_hidup = true for 'Pakan Hidup'
    echo "\n4. Updating categories to mark 'Pakan Hidup' category...\n";
    $updated = Capsule::table('categories')
        ->where('slug', 'pakan-hidup')
        ->orWhere('name', 'like', '%Pakan Hidup%')
        ->update(['is_pakan_hidup' => true]);
    echo "   ✓ Updated {$updated} category(ies) as Pakan Hidup\n";

    // 5. Set default member_price for existing products (90% of regular price)
    echo "\n5. Setting default member_price for existing products...\n";
    $products = Capsule::table('products')->where('member_price', 0)->orWhereNull('member_price')->get();
    $count = 0;
    foreach ($products as $product) {
        $memberPrice = $product->price * 0.9; // 10% discount for members
        Capsule::table('products')
            ->where('id', $product->id)
            ->update(['member_price' => $memberPrice]);
        $count++;
    }
    echo "   ✓ Updated {$count} products with member price (10% discount)\n";

    // 6. Update existing users to have role = 'user'
    echo "\n6. Updating existing users to have role = 'user'...\n";
    $updatedUsers = Capsule::table('users')
        ->whereNull('role')
        ->orWhere('role', '')
        ->update(['role' => 'user']);
    echo "   ✓ Updated {$updatedUsers} users with default role\n";

    echo "\n=== Migration Completed Successfully! ===\n";
    echo "\nRole System Features:\n";
    echo "- Users can register as 'User Biasa' or 'Anggota'\n";
    echo "- User Biasa can only order 'Pakan Hidup' products\n";
    echo "- Anggota and Admin can order all products with special member prices\n";
    echo "- Products have member_price column for discounted pricing\n";
    echo "- Categories have is_pakan_hidup flag for access control\n";

} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

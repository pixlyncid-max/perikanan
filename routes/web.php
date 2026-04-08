<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutPageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\SettingController;

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'handleContact'])->name('contact.send');
Route::get('/partnership', [HomeController::class, 'partnership'])->name('partnership');
// Route::get('/partnership', function () {
//     return view('errors.coming-soon');
// })->name('partnership');
//Organization Routes
Route::prefix('organization')->name('organization.')->group(function () {
    Route::get('/structure', [OrganizationController::class, 'structure'])->name('structure');
    Route::get('/dpc/{code}', [OrganizationController::class, 'showDpc'])
        ->name('dpc')
        ->where('code', '[a-z0-9-]+');
});
// Route::prefix('organization')->name('organization.')->group(function () {
//     Route::get('/structure', function () {
//         return view('errors.coming-soon');
//     })->name('structure');
//     Route::get('/dpc/{code}', function ($code) {
//         return view('errors.coming-soon');
//     })->name('dpc')->where('code', '[a-z0-9-]+');
// });

// Produk Routes
Route::prefix('produk')->name('produk.')->group(function () {
    Route::get('/', [ProdukController::class, 'index'])->name('index');
    Route::get('/pelet-pakan', [ProdukController::class, 'peletPakan'])->name('pelet-pakan');
    Route::get('/pakan-hidup', [ProdukController::class, 'pakanHidup'])->name('pakan-hidup');
    Route::get('/umpan-laut', [ProdukController::class, 'umpanLaut'])->name('umpan-laut');
    Route::get('/penyewaan-kapal', [ProdukController::class, 'penyewaanKapal'])->name('penyewaan-kapal');
    Route::get('/vitamin-air', [ProdukController::class, 'vitaminAir'])->name('vitamin-air');
    Route::get('/bibit-ikan', [ProdukController::class, 'bibitIkan'])->name('bibit-ikan');
    Route::get('/sewa-pancing', [ProdukController::class, 'sewaPancing'])->name('sewa-pancing');
    Route::get('/kolam-pemancingan', [ProdukController::class, 'kolamPemancingan'])->name('kolam-pemancingan');
    Route::get('/komunitas-air-tawar', [ProdukController::class, 'komunitasAirTawar'])->name('komunitas-air-tawar');
    Route::get('/sewa-pancing-laut', [ProdukController::class, 'sewaPancingLaut'])->name('sewa-pancing-laut');
    Route::get('/komunitas-air-laut', [ProdukController::class, 'komunitasAirLaut'])->name('komunitas-air-laut');
    Route::get('/{slug}', [ProdukController::class, 'show'])->name('show');
});


// Article Routes
Route::prefix('article')->name('article.')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('index');
    Route::get('/category/{category}', [ArticleController::class, 'byCategory'])->name('category');
    Route::get('/{slug}', [ArticleController::class, 'show'])->name('show');
});
// Route::prefix('article')->name('article.')->group(function () {
//     Route::get('/', function () {
//         return view('errors.coming-soon');
//     })->name('index');
//     Route::get('/category/{category}', function ($category) {
//         return view('errors.coming-soon');
//     })->name('category');
//     Route::get('/{slug}', function ($slug) {
//         return view('errors.coming-soon');
//     })->name('show');
// });

// Auth Routes
Route::middleware(['web', 'guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Social Login Routes (OAuth)
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/auth/facebook', [AuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');

Route::middleware(['web'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/member-card', [AuthController::class, 'memberCard'])->name('member.card');

    // Order History Routes
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order_number}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order_number}/repay', [\App\Http\Controllers\OrderController::class, 'repay'])->name('orders.repay');
    Route::post('/orders/{order_number}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
    // Cart Routes
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

    // Custom Checkout Page
    Route::get('/checkout', [CheckoutPageController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/select', [CheckoutPageController::class, 'setItems'])->name('checkout.set-items');

    // Checkout Process (Web context for session access)
    Route::post('/checkout-process', [\App\Http\Controllers\Api\CheckoutController::class, 'store'])->name('checkout.process');
    Route::post('/api/locations/valid', [\App\Http\Controllers\Api\LocationController::class, 'getValidLocations'])->name('api.locations.valid');
    Route::post('/api/locations/nearest', [\App\Http\Controllers\Api\LocationController::class, 'getNearestLocation'])->name('api.locations.nearest');
    
    // Profile Updates
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/update-address', [AuthController::class, 'updateAddress'])->name('profile.update-address');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['web', 'admin'])->group(function () {
    
    // Redirect /admin to /admin/dashboard
    Route::redirect('/', '/admin/dashboard');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    
    // User Management
    Route::resource('users', UserController::class);
    
    // Member Management
    Route::resource('members', MemberController::class);
    
    // Product Management
    Route::get('/products/generate-sku', [ProductController::class, 'generateSku'])->name('products.generate-sku');
    Route::post('/products/mass-action', [ProductController::class, 'massAction'])->name('products.mass-action');
    Route::get('/products/import-template', [ProductController::class, 'downloadTemplate'])->name('products.import-template');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::resource('products', ProductController::class);
    Route::patch('/products/{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('products.toggle-active');
    Route::patch('/products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    
    // Category Management
    Route::resource('categories', CategoryController::class);
    
    // Order Management
    Route::resource('orders', OrderController::class);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/sync-payment', [OrderController::class, 'syncPaymentStatus'])->name('orders.sync-payment');
    
    // Article Management
    Route::resource('articles', AdminArticleController::class);
    
    // News Management
    Route::resource('news', NewsController::class);
    
    // Media Management
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media/upload', [MediaController::class, 'upload'])->name('media.upload');
    Route::delete('/media/{id}', [MediaController::class, 'destroy'])->name('media.destroy');
    
    // Organization Management
    Route::resource('organizations', \App\Http\Controllers\Admin\OrganizationController::class);
    
    // Location Management
    Route::resource('locations', \App\Http\Controllers\Admin\LocationController::class);

    // Fishery Statistics Management
    Route::resource('fishery-statistics', \App\Http\Controllers\Admin\FisheryStatisticController::class);
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

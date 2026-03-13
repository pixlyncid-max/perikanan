# Sistem Role Pengguna - FISHERIES

## Overview

Sistem role pengguna telah diimplementasikan dengan 3 jenis role:

1. **Admin** - Akses penuh ke sistem, termasuk CRUD produk, user, dll
2. **Anggota (Member)** - Bisa memesan semua produk dengan harga khusus anggota
3. **User Biasa** - Hanya bisa memesan produk kategori Pakan Hidup

## Struktur Role

```
Admin
├── Memiliki semua hak akses Anggota
└── Hak akses admin (CRUD, dll)

Anggota (Member)
├── Bisa memesan semua produk
└── Mendapat harga khusus anggota (lebih murah)

User Biasa
└── Hanya bisa memesan produk Pakan Hidup
```

## Database Changes

### 1. Tabel `users`
- Ditambahkan kolom `role` (enum: 'user', 'member', default: 'user')

### 2. Tabel `products`
- Ditambahkan kolom `member_price` (decimal, default: 0)
- Harga khusus untuk anggota (biasanya 10% lebih murah dari harga regular)

### 3. Tabel `categories`
- Ditambahkan kolom `is_pakan_hidup` (boolean, default: false)
- Menandai kategori yang bisa dipesan oleh User Biasa

## Model Updates

### User Model
```php
// Method untuk cek role
$user->isAdmin();   // false (user biasa tidak bisa jadi admin)
$user->isMember();  // true jika role = 'member'
$user->isUser();    // true jika role = 'user'
$user->getRole();   // 'user' atau 'member'
```

### Member Model
```php
// Method untuk cek role
$member->isAdmin();   // false
$member->isMember();  // true (selalu true untuk member)
$member->isUser();    // false
$member->getRole();   // 'member'
```

### Admin Model
```php
// Method untuk cek role
$admin->isAdmin();   // true
$admin->isMember();  // true (admin juga dianggap member)
$admin->isUser();    // false
$admin->getRole();   // 'admin'
```

### Product Model
```php
// Method untuk mendapatkan harga sesuai role
$product->getPriceForUser('admin');   // member_price
$product->getPriceForUser('member');  // member_price
$product->getPriceForUser('user');    // regular price

// Cek apakah produk adalah Pakan Hidup
$product->isPakanHidup();  // true/false
```

## Helper Functions

### Role Checking
```php
is_logged_in();           // Cek apakah user sudah login
get_current_user_role();  // 'admin', 'member', 'user', atau 'guest'
is_admin();               // Cek apakah admin
is_member();              // Cek apakah member (termasuk admin)
is_user();                // Cek apakah user biasa
```

### Product Access
```php
can_order_product($product);           // Cek apakah bisa memesan produk
get_product_price_for_user($product);  // Dapatkan harga sesuai role
get_order_error_message();             // Pesan error untuk user biasa
```

### Access Control
```php
require_login();           // Redirect ke login jika belum login
require_role('admin');     // Require role admin
require_role('member');    // Require role member (admin juga bisa)
require_role('user');      // Require role user (semua role bisa)
```

## Middleware

### RoleMiddleware
```php
// Menggunakan middleware di route
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('role:admin');

Route::get('/member-area', [MemberController::class, 'index'])
    ->middleware('role:member');  // Admin juga bisa akses

Route::get('/user-area', [UserController::class, 'index'])
    ->middleware('role:user');    // Semua role bisa akses
```

## Registration

### Halaman Register
- Ditambahkan pilihan jenis pendaftaran:
  - **User Biasa** (default) - Akses terbatas
  - **Anggota** - Akses penuh dengan harga khusus

### Form Register
```html
<input type="radio" name="registration_type" value="user" checked>
<label>Daftar sebagai User Biasa</label>

<input type="radio" name="registration_type" value="member">
<label>Daftar sebagai Anggota</label>
```

## Aturan Pembelian Produk

### User Biasa
- ✅ Bisa memesan produk kategori **Pakan Hidup**
- ❌ Tidak bisa memesan produk lain
- ❌ Tidak mendapat harga khusus anggota

### Anggota
- ✅ Bisa memesan **semua produk**
- ✅ Mendapat **harga khusus anggota** (member_price)

### Admin
- ✅ Bisa memesan **semua produk**
- ✅ Mendapat **harga khusus anggota**
- ✅ Memiliki **akses penuh** ke sistem (CRUD)

## Contoh Penggunaan

### Di Controller
```php
public function order(Product $product)
{
    // Cek apakah user bisa memesan produk
    if (!can_order_product($product)) {
        return redirect()->back()
            ->with('error', get_order_error_message());
    }

    // Lanjutkan proses pemesanan
    $price = get_product_price_for_user($product);
    // ...
}
```

### Di View
```blade
@if(is_member())
    <span class="member-price">Rp {{ number_format(get_product_price_for_user($product)) }}</span>
    <span class="regular-price text-muted text-decoration-line-through">
        Rp {{ number_format($product->price) }}
    </span>
@else
    <span>Rp {{ number_format($product->price) }}</span>
@endif

@if(!can_order_product($product))
    <div class="alert alert-warning">
        {{ get_order_error_message() }}
    </div>
@endif
```

### Di Route
```php
// Route yang hanya bisa diakses admin
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->middleware('role:admin');

// Route yang bisa diakses member (termasuk admin)
Route::get('/member/card', [AuthController::class, 'memberCard'])
    ->middleware('role:member');

// Route yang bisa diakses semua user yang login
Route::get('/profile', [UserController::class, 'profile'])
    ->middleware('role:user');
```

## Keamanan

1. **Session-based Authentication** - Role disimpan di session
2. **Middleware Protection** - Semua route dilindungi middleware
3. **Server-side Validation** - Cek role di server, tidak hanya di client
4. **Cannot be bypassed** - Tidak bisa diakali dengan mengubah request manual

## Testing

### Test Case 1: Register sebagai User Biasa
1. Buka halaman /register
2. Pilih "Daftar sebagai User Biasa"
3. Isi form dan submit
4. Login dengan akun tersebut
5. Coba pesan produk Pakan Hidup → Berhasil
6. Coba pesan produk lain → Gagal dengan pesan error

### Test Case 2: Register sebagai Anggota
1. Buka halaman /register
2. Pilih "Daftar sebagai Anggota"
3. Isi form lengkap (DPC, Pekerjaan) dan submit
4. Login dengan akun tersebut
5. Coba pesan semua produk → Berhasil dengan harga anggota

### Test Case 3: Login sebagai Admin
1. Login dengan akun admin
2. Cek akses ke halaman admin → Berhasil
3. Cek harga produk → Harga anggota
4. Cek akses ke member card → Berhasil

## Files yang Diubah/Dibuat

### Migration
- `database/migrations/2024_01_01_000015_add_role_to_users_table.php`
- `database/migrations/2024_01_01_000016_add_member_price_to_products_table.php`
- `database/migrations/2024_01_01_000017_add_is_pakan_hidup_to_categories_table.php`

### Models
- `app/Models/User.php` - Tambah role methods
- `app/Models/Member.php` - Tambah role methods
- `app/Models/Admin.php` - Tambah role methods
- `app/Models/Product.php` - Tambah member_price & getPriceForUser()
- `app/Models/Category.php` - Tambah is_pakan_hidup

### Middleware
- `app/Http/Middleware/RoleMiddleware.php` - Role checking middleware

### Controllers
- `app/Http/Controllers/AuthController.php` - Update register & login

### Views
- `resources/views/auth/register.blade.php` - Tambah pilihan role

### Helpers
- `app/helpers.php` - Tambah role helper functions

## Catatan Penting

1. **Default Role**: User yang sudah terdaftar sebelumnya akan otomatis menjadi 'user'
2. **Harga Anggota**: Default 10% lebih murah dari harga regular
3. **Pakan Hidup**: Kategori dengan slug 'pakan-hidup' otomatis ditandai sebagai Pakan Hidup
4. **Admin = Member**: Admin otomatis memiliki hak akses anggota

## Troubleshooting

### User tidak bisa login
- Cek apakah kolom `role` sudah ada di tabel `users`
- Jalankan `php run_role_migration.php` untuk memperbaiki

### Harga anggota tidak muncul
- Cek apakah kolom `member_price` sudah ada di tabel `products`
- Cek apakah `member_price` > 0

### User biasa bisa pesan semua produk
- Cek apakah middleware `can_order_product()` digunakan
- Cek apakah kategori produk sudah ditandai `is_pakan_hidup`

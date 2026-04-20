<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Models\Admin;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        // Validate
        if (empty($email) || empty($password)) {
            Session::flash('error', 'Email dan password wajib diisi.');
            return redirect('/login');
        }

        // Cari di ketiga tabel: users, members, admins
        $user = null;
        $userType = null;
        $displayName = '';

        // 1. Cari di tabel members dulu (karena paling umum)
        $member = Member::where('email', $email)->first();
        if ($member && Hash::check($password, $member->password)) {
            $user = $member;
            $userType = 'member';
            $displayName = $member->name;
        }

        // 2. Jika tidak ketemu, cari di tabel admins
        if (!$user) {
            $admin = Admin::where('email', $email)->first();
            if ($admin && Hash::check($password, $admin->password)) {
                $user = $admin;
                $userType = 'admin';
                $displayName = $admin->name;
            }
        }

        // 3. Jika masih tidak ketemu, cari di tabel users
        if (!$user) {
            $regularUser = User::where('email', $email)->first();
            if ($regularUser && Hash::check($password, $regularUser->password)) {
                $user = $regularUser;
                $userType = 'user';
                $displayName = $regularUser->name ?? 'User';
            }
        }

        // Jika tidak ketemu di semua tabel
        if (!$user) {
            Session::flash('error', 'Email atau password salah.');
            return redirect('/login');
        }

        Session::put('user', [
            'id' => $user->id,
            'name' => $displayName,
            'email' => $email,
            'type' => $userType,
            'avatar' => $user->avatar ?? null,
            'table' => $userType === 'member' ? 'members' : ($userType === 'admin' ? 'admins' : 'users'),
        ]);

        // --- CART SYNC ON LOGIN ---
        $this->syncCartAfterAuth($user, $userType);
        // --- END CART SYNC ---

        // Handle Remember Me
        if ($remember) {
            $token = Str::random(60);
            $user->remember_token = $token;
            $user->save();
            
            $table = $userType === 'member' ? 'members' : ($userType === 'admin' ? 'admins' : 'users');
            Cookie::queue('fisheries_remember', "$token|$table", 43200); // 30 days
        }

        // Regenerate session ID for security
        Session::regenerate();

        Session::flash('success', 'Login berhasil! Selamat datang kembali.');

        return redirect('/');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirmation'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $dpc = $_POST['dpc'] ?? 'samarinda';
        $occupation = $_POST['occupation'] ?? '';
        $registrationType = $_POST['registration_type'] ?? 'user'; // 'user' atau 'member'

        // Basic validation
        if (empty($name) || empty($email) || empty($password)) {
            Session::flash('error', 'Semua field wajib diisi.');
            return redirect('/register');
        }

        if ($password !== $passwordConfirm) {
            Session::flash('error', 'Password tidak cocok.');
            return redirect('/register');
        }

        // Check if email exists in any table
        $existingUser = User::where('email', $email)->first();
        $existingMember = Member::where('email', $email)->first();
        $existingAdmin = Admin::where('email', $email)->first();

        if ($existingUser || $existingMember || $existingAdmin) {
            Session::flash('error', 'Email sudah terdaftar.');
            return redirect('/register');
        }

        // Jika daftar sebagai Member
        if ($registrationType === 'member') {
            // Generate member number
            $memberCount = Member::count();
            $memberNumber = 'FIS-' . date('Y') . '-' . str_pad($memberCount + 1, 4, '0', STR_PAD_LEFT);

            // Create member (independent table)
            $member = Member::create([
                'full_name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'phone' => $phone,
                'address' => $address ?? '',
                'avatar' => null,
                'membership_number' => $memberNumber,
                'dpc' => $dpc,
                'occupation' => $occupation,
                'join_date' => date('Y-m-d'),
                'status' => 'active',
                'expiry_date' => date('Y-m-d', strtotime('+1 year')),
                'benefits' => json_encode(['Diskon 10%', 'Pelatihan Gratis', 'Konsultasi Teknis']),
            ]);

            // Auto login
            Session::put('user', [
                'id' => $member->id,
                'name' => $name,
                'email' => $email,
                'type' => 'member',
                'avatar' => $member->avatar ?? null,
                'table' => 'members',
            ]);

            $this->syncCartAfterAuth($member, 'member');

            Session::flash('success', 'Pendaftaran sebagai anggota berhasil! Selamat datang di FISHERIES.');

            return redirect('/');
        }

        // Jika daftar sebagai User Biasa
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => $phone,
            'address' => '',
            'avatar' => null,
            'status' => 'active',
            'role' => 'user',
        ]);

        // Auto login
        Session::put('user', [
            'id' => $user->id,
            'name' => $name,
            'email' => $email,
            'type' => 'user',
            'avatar' => $user->avatar ?? null,
            'table' => 'users',
        ]);

        $this->syncCartAfterAuth($user, 'user');

        Session::flash('success', 'Pendaftaran berhasil! Selamat datang di FISHERIES.');

        return redirect('/');
    }

    public function logout()
    {
        // --- CART SYNC ON LOGOUT ---
        if (Session::has('user') && Session::has('cart')) {
            $user = Session::get('user');
            $cart = Session::get('cart');
            
            if (!empty($cart)) {
                \App\Models\ShoppingCart::updateOrCreate(
                    ['user_id' => $user['id'], 'user_type' => $user['type']],
                    ['cart_data' => json_encode($cart)]
                );
            }
        }
        // --- END CART SYNC ---

        // Clear session
        Session::forget('user');
        Session::flush();

        // Clear Remember Me if exists
        if (Cookie::has('fisheries_remember')) {
            $rememberToken = Cookie::get('fisheries_remember');
            $parts = explode('|', $rememberToken);
            if (count($parts) === 2) {
                $token = $parts[0];
                $table = $parts[1];
                
                if ($table === 'members') {
                    Member::where('remember_token', $token)->update(['remember_token' => null]);
                } elseif ($table === 'admins') {
                    Admin::where('remember_token', $token)->update(['remember_token' => null]);
                } elseif ($table === 'users') {
                    User::where('remember_token', $token)->update(['remember_token' => null]);
                }
            }
            Cookie::queue(Cookie::forget('fisheries_remember'));
        }

        return redirect('/');
    }

    public function memberCard()
    {
        // Check if user is logged in
        if (!Session::has('user')) {
            return redirect('/login');
        }

        $userData = Session::get('user');
        $userType = $userData['type'];
        $userEmail = $userData['email'];
        $userId = $userData['id'];

        // Member dan Admin bisa akses member card
        if ($userType === 'admin') {
            // Cari data member dengan email yang sama
            $member = Member::where('email', $userEmail)->first();
            
            // Jika admin tidak punya record di members, buat virtual member dari data admin
            if (!$member) {
                $admin = Admin::where('email', $userEmail)->first();
                
                // Buat virtual member object untuk admin
                $member = new \stdClass();
                $member->id = $admin->id;
                $member->name = $admin->name;
                $member->email = $admin->email;
                $member->phone = $admin->phone ?? '-';
                $member->address = $admin->address ?? '-';
                $member->avatar = $admin->avatar ?? null;
                $member->member_number = 'ADMIN-' . str_pad($admin->id, 4, '0', STR_PAD_LEFT);
                $member->dpc = 'samarinda';
                $member->occupation = 'Administrator';
                $member->join_date = $admin->created_at ?? date('Y-m-d');
                $member->status = 'active';
                $member->expiry_date = date('Y-m-d', strtotime('+10 years'));
                $member->benefits = json_encode(['Akses Penuh', 'Manajemen Sistem', 'Laporan Admin']);
            }

            // Get orders for admin (using member_id or user_id)
            $orders = Order::where('user_id', $userId)
                ->latest('created_at')
                ->take(10)
                ->get();

            return view('auth.member-card', [
                'member' => $member,
                'is_admin' => true,
                'orders' => $orders,
            ]);
        }

        // Regular member
        if ($userType === 'member') {
            $member = Member::find($userId);
            
            if (!$member) {
                Session::flash('error', 'Data anggota tidak ditemukan.');
                return redirect('/');
            }

            // Get orders for member
            $orders = Order::where('user_id', $userId)
                ->latest('created_at')
                ->take(10)
                ->get();

            return view('auth.member-card', [
                'member' => $member,
                'is_admin' => false,
                'orders' => $orders,
            ]);
        }

        // User biasa tidak bisa akses
        Session::flash('error', 'Anda belum terdaftar sebagai anggota.');
        return redirect('/');
    }


    /**
     * Check if current user can order a product
     */
    public function canOrderProduct($product): bool
    {
        if (!Session::has('user')) {
            return false;
        }

        $userData = Session::get('user');
        $userType = $userData['type'] ?? 'user';

        // Admin dan Member bisa memesan semua produk
        if (in_array($userType, ['admin', 'member'])) {
            return true;
        }

        // User biasa hanya bisa memesan produk Pakan Hidup
        if ($userType === 'user') {
            return $product->isPakanHidup();
        }

        return false;
    }

    /**
     * Get error message for restricted product order
     */
    public function getOrderErrorMessage(): string
    {
        return 'Produk ini hanya tersedia untuk anggota. Silakan daftar menjadi anggota untuk melakukan pemesanan.';
    }

    /**
     * Show user profile page
     */
    public function profile()
    {
        if (!Session::has('user')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $session = Session::get('user');
        $user = null;
        
        if ($session['type'] === 'admin') {
            $user = Admin::find($session['id']);
        } elseif ($session['type'] === 'member') {
            $user = Member::find($session['id']);
        } else {
            $user = User::find($session['id']);
        }

        if (!$user) {
            Session::forget('user');
            return redirect('/login')->with('error', 'Sesi Anda telah berakhir.');
        }

        // Format address if it's JSON
        $displayAddress = $user->address;
        if (!empty($displayAddress) && str_starts_with($displayAddress, '{')) {
            try {
                $decoded = json_decode($displayAddress, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $parts = [];
                    if (!empty($decoded['address'])) $parts[] = $decoded['address'];
                    if (!empty($decoded['detail'])) $parts[] = '(' . $decoded['detail'] . ')';
                    if (!empty($decoded['dist_name'])) $parts[] = $decoded['dist_name'];
                    if (!empty($decoded['city_name'])) $parts[] = $decoded['city_name'];
                    if (!empty($decoded['prov_name'])) $parts[] = $decoded['prov_name'];
                    $displayAddress = implode(', ', $parts);
                }
            } catch (\Exception $e) {
                // Keep original if parsing fails
            }
        }

        return view('auth.profile', [
            'user' => $user,
            'type' => $session['type'],
            'session' => $session,
            'displayAddress' => $displayAddress
        ]);
    }

    /**
     * Update user profile information
     */
    public function updateProfile(\Illuminate\Http\Request $request)
    {
        if (!Session::has('user')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $session = Session::get('user');
        $user = null;

        if ($session['type'] === 'admin') {
            $user = Admin::find($session['id']);
        } elseif ($session['type'] === 'member') {
            $user = Member::find($session['id']);
        } else {
            $user = User::find($session['id']);
        }

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        // Basic validation
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update fields
        if ($session['type'] === 'member' || $session['type'] === 'admin') {
            $user->full_name = $request->name;
        } else {
            $user->name = $request->name;
        }

        $user->phone = $request->phone;
        $user->address = $request->address;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
            $session['avatar'] = $avatarPath; // Update session
        }

        $user->save();

        // Sync session
        $session['name'] = $request->name;
        Session::put('user', $session);

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Delete user profile avatar
     */
    public function deleteAvatar(\Illuminate\Http\Request $request)
    {
        if (!Session::has('user')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $session = Session::get('user');
        $user = null;

        if ($session['type'] === 'admin') {
            $user = Admin::find($session['id']);
        } elseif ($session['type'] === 'member') {
            $user = Member::find($session['id']);
        } else {
            $user = User::find($session['id']);
        }

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        if ($user->avatar) {
            // hapus file lama jika ada
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
            $user->save();

            // Sync session
            $session['avatar'] = null;
            Session::put('user', $session);

            return back()->with('success', 'Foto profil berhasil dihapus.');
        }

        return back()->with('error', 'Tidak ada foto profil untuk dihapus.');
    }

    /**
     * Delete user account permanently
     */
    public function deleteAccount(\Illuminate\Http\Request $request)
    {
        if (!Session::has('user')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $session = Session::get('user');
        $user = null;

        if ($session['type'] === 'admin') {
            return back()->with('error', 'Akun admin tidak dapat dihapus dari sini.');
        } elseif ($session['type'] === 'member') {
            $user = Member::find($session['id']);
        } else {
            $user = User::find($session['id']);
        }

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        // Hapus avatar jika ada
        if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
        }

        // Hapus cart data
        \App\Models\ShoppingCart::where('user_id', $session['id'])
            ->where('user_type', $session['type'])
            ->delete();

        // Hapus akun
        $user->delete();

        // Clear session & cookie
        Session::forget('user');
        Session::flush();

        if (Cookie::has('fisheries_remember')) {
            Cookie::queue(Cookie::forget('fisheries_remember'));
        }

        return redirect('/')->with('success', 'Akun Anda berhasil dihapus. Anda dapat mendaftar kembali kapan saja.');
    }

    /**
     * Update user address via AJAX
     */
    public function updateAddress(\Illuminate\Http\Request $request)
    {
        if (!\Illuminate\Support\Facades\Session::has('user')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $session = \Illuminate\Support\Facades\Session::get('user');
        $id = $session['id'];
        $type = $session['type'];

        $user = null;
        if ($type === 'admin') {
            $user = Admin::find($id);
        } elseif ($type === 'member') {
            $user = Member::find($id);
        } else {
            $user = User::find($id);
        }

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->save();

        return response()->json(['success' => true]);
    }

    /**
     * Merge session cart with database cart after login/register
     */
    private function syncCartAfterAuth($user, $userType)
    {
        $sessionCart = Session::get('cart', []);
        $savedCartObj = \App\Models\ShoppingCart::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->first();
        
        $savedCart = [];
        if ($savedCartObj && $savedCartObj->cart_data) {
            $savedCart = json_decode($savedCartObj->cart_data, true) ?? [];
        }

        // Merge logic
        foreach ($sessionCart as $key => $item) {
            if (isset($savedCart[$key])) {
                $savedCart[$key]['quantity'] += $item['quantity'];
            } else {
                $savedCart[$key] = $item;
            }
        }

        if (!empty($savedCart)) {
            \App\Models\ShoppingCart::updateOrCreate(
                ['user_id' => $user->id, 'user_type' => $userType],
                ['cart_data' => json_encode($savedCart)]
            );
            Session::put('cart', $savedCart);
        }
    }

    // =========================================================
    // SOCIAL LOGIN — GOOGLE
    // =========================================================

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google OAuth Error: [' . get_class($e) . '] ' . $e->getMessage());
            Session::flash('error', 'Login dengan Google gagal. Silakan coba lagi. Error: ' . $e->getMessage());
            return redirect('/login');
        }

        return $this->handleSocialLogin($socialUser, 'google');
    }

    // =========================================================
    // SOCIAL LOGIN — FACEBOOK
    // =========================================================

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $socialUser = Socialite::driver('facebook')->stateless()->user();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Facebook OAuth Error: [' . get_class($e) . '] ' . $e->getMessage());
            Session::flash('error', 'Login dengan Facebook gagal. Silakan coba lagi. Error: ' . $e->getMessage());
            return redirect('/login');
        }

        return $this->handleSocialLogin($socialUser, 'facebook');
    }

    /**
     * Shared logic: cari atau buat user dari data OAuth, lalu login via session.
     */
    private function handleSocialLogin($socialUser, string $provider)
    {
        $email = $socialUser->getEmail();
        $name  = $socialUser->getName() ?? $socialUser->getNickname() ?? 'User';

        if (empty($email)) {
            Session::flash('error', 'Tidak dapat mengambil email dari akun ' . ucfirst($provider) . '. Pastikan email Anda publik.');
            return redirect('/login');
        }

        // Cek apakah sudah ada di tabel members
        $member = Member::where('email', $email)->first();
        if ($member) {
            Session::put('user', [
                'id'    => $member->id,
                'name'  => $member->name ?? $member->full_name ?? $name,
                'email' => $email,
                'type'  => 'member',
                'avatar' => $member->avatar ?? null,
                'table' => 'members',
            ]);
            $this->syncCartAfterAuth($member, 'member');
            Session::regenerate();
            Session::flash('success', 'Login berhasil via ' . ucfirst($provider) . '! Selamat datang kembali.');
            return redirect('/');
        }

        // Cek apakah sudah ada di tabel admins
        $admin = Admin::where('email', $email)->first();
        if ($admin) {
            Session::put('user', [
                'id'    => $admin->id,
                'name'  => $admin->name ?? $name,
                'email' => $email,
                'type'  => 'admin',
                'avatar' => $admin->avatar ?? null,
                'table' => 'admins',
            ]);
            Session::regenerate();
            Session::flash('success', 'Login berhasil via ' . ucfirst($provider) . '! Selamat datang kembali.');
            return redirect('/');
        }

        // Cek atau buat di tabel users
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make(Str::random(32)),
                'avatar'   => $socialUser->getAvatar() ?? null,
                'phone'    => '',
                'address'  => '',
                'status'   => 'active',
                'role'     => 'user',
            ]);
        }

        Session::put('user', [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $email,
            'type'  => 'user',
            'avatar' => $user->avatar ?? null,
            'table' => 'users',
        ]);

        $this->syncCartAfterAuth($user, 'user');
        Session::regenerate();
        Session::flash('success', 'Login berhasil via ' . ucfirst($provider) . '! Selamat datang.');
        return redirect('/');
    }
}

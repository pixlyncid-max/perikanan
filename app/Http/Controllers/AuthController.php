<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Models\Admin;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;


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

        // Login successful - set session
        Session::put('user', [
            'id' => $user->id,
            'name' => $displayName,
            'email' => $email,
            'type' => $userType, // 'user', 'member', atau 'admin'
            'table' => $userType === 'member' ? 'members' : ($userType === 'admin' ? 'admins' : 'users'),
        ]);

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
                'table' => 'members',
            ]);

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
            'table' => 'users',
        ]);

        Session::flash('success', 'Pendaftaran berhasil! Selamat datang di FISHERIES.');

        return redirect('/');
    }

    public function logout()
    {
        // Clear session
        Session::forget('user');
        Session::flush();

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
}

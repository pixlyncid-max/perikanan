<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Member;
use App\Models\Admin;
use App\Mail\PasswordResetMail;

class PasswordResetController extends Controller
{
    /**
     * Tampilkan form untuk meminta link reset password.
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Kirim link reset password ke email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $email = $request->email;
        
        // Cek apakah email ada di tabel members, admins, atau users
        $userExists = Member::where('email', $email)->exists() ||
                      Admin::where('email', $email)->exists() ||
                      User::where('email', $email)->exists();
                      
        if (!$userExists) {
            return back()->with('error', 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.');
        }

        // Generate token
        $token = Str::random(60);

        // Hapus token lama jika ada
        DB::table('password_resets')->where('email', $email)->delete();

        // Simpan token baru
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Kirim email
        Mail::to($email)->send(new PasswordResetMail($token, $email));

        return back()->with('success', 'Kami telah mengirimkan tautan reset password ke email Anda!');
    }

    /**
     * Tampilkan form untuk membuat password baru.
     */
    public function showResetForm($token, Request $request)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Proses reset password.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $email = $request->email;
        
        // Validasi token
        $resetRecord = DB::table('password_resets')->where('email', $email)->first();

        if (!$resetRecord || $resetRecord->token !== $request->token) {
            return back()->with('error', 'Token reset password tidak valid atau sudah kadaluarsa.');
        }

        // Token kadaluarsa dalam 60 menit
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('email', $email)->delete();
            return back()->with('error', 'Token reset password sudah kadaluarsa.');
        }

        $passwordHash = Hash::make($request->password);
        $userUpdated = false;

        // Cek dan update di tabel members
        $member = Member::where('email', $email)->first();
        if ($member) {
            $member->update(['password' => $passwordHash]);
            $userUpdated = true;
        }

        // Cek dan update di tabel admins
        $admin = Admin::where('email', $email)->first();
        if ($admin) {
            $admin->update(['password' => $passwordHash]);
            $userUpdated = true;
        }

        // Cek dan update di tabel users
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update(['password' => $passwordHash]);
            $userUpdated = true;
        }

        if (!$userUpdated) {
            return back()->with('error', 'Gagal mereset password. Pengguna tidak ditemukan.');
        }

        // Hapus token
        DB::table('password_resets')->where('email', $email)->delete();

        return redirect('/login')->with('success', 'Password Anda telah berhasil direset. Silakan login dengan password baru.');
    }
}

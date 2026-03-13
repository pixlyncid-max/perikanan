<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in using custom session
        if (!Session::has('user')) {
            Log::info('AdminMiddleware: No user session found, redirecting to login');
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Session::get('user');
        
        // Log for debugging
        Log::info('AdminMiddleware: User session found', [
            'email' => $user['email'] ?? 'unknown',
            'type' => $user['type'] ?? 'unknown',
            'route' => $request->route()->getName()
        ]);
        
        // Check if user type is admin
        if (!isset($user['type']) || $user['type'] !== 'admin') {
            Log::warning('AdminMiddleware: User type is not admin', ['type' => $user['type'] ?? 'not set']);
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        // Verify that the email exists in admins table
        if (!isset($user['email'])) {
            Log::error('AdminMiddleware: User email not found in session');
            Session::forget('user');
            return redirect()->route('login')->with('error', 'Session tidak valid. Silakan login kembali.');
        }

        $admin = Admin::where('email', $user['email'])->first();
        if (!$admin) {
            Log::warning('AdminMiddleware: Admin not found in database', ['email' => $user['email']]);
            return redirect()->route('home')->with('error', 'Akun admin tidak ditemukan di database.');
        }

        // Check if admin account is active
        if ($admin->account_status !== 'active') {
            Log::warning('AdminMiddleware: Admin account not active', ['email' => $user['email']]);
            return redirect()->route('home')->with('error', 'Akun admin tidak aktif.');
        }

        // Store admin data in session for use in views
        Session::put('admin_data', [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role
        ]);

        Log::info('AdminMiddleware: Access granted', ['email' => $user['email']]);

        return $next($request);
    }
}

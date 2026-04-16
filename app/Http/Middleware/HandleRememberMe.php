<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use App\Models\Member;
use App\Models\Admin;

class HandleRememberMe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Jika sudah login di session, lewati
        if (Session::has('user')) {
            return $next($request);
        }

        // Cek cookie "fisheries_remember"
        $rememberToken = Cookie::get('fisheries_remember');

        if ($rememberToken) {
            // Format cookie: token|table
            $parts = explode('|', $rememberToken);
            
            if (count($parts) === 2) {
                $token = $parts[0];
                $table = $parts[1];
                
                $user = null;
                $userType = null;
                $displayName = '';

                // Cari berdasarkan tabel
                if ($table === 'members') {
                    $user = Member::where('remember_token', $token)->first();
                    if ($user) {
                        $userType = 'member';
                        $displayName = $user->full_name;
                    }
                } elseif ($table === 'admins') {
                    $user = Admin::where('remember_token', $token)->first();
                    if ($user) {
                        $userType = 'admin';
                        $displayName = $user->full_name;
                    }
                } elseif ($table === 'users') {
                    $user = User::where('remember_token', $token)->first();
                    if ($user) {
                        $userType = 'user';
                        $displayName = $user->name;
                    }
                }

                // Jika user ditemukan, pulihkan session
                if ($user) {
                    Session::put('user', [
                        'id' => $user->id,
                        'name' => $displayName,
                        'email' => $user->email,
                        'type' => $userType,
                        'table' => $table,
                    ]);
                    
                    // Regenerate session untuk keamanan
                    Session::regenerate();
                }
            }
        }

        return $next($request);
    }
}

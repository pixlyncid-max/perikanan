<?php

namespace App\Http\Middleware;

// FISHERIES - Role Middleware

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$roles)
    {
        // Check if user is logged in
        if (empty($_SESSION['user'])) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu.';
            return redirect('/login');
        }

        $userRole = $_SESSION['user']['type'] ?? 'user';

        // Check if user has required role
        // Admin has access to everything (including member routes)
        // Member has access to member and user routes
        $hasAccess = false;

        foreach ($roles as $role) {
            if ($role === 'admin' && $userRole === 'admin') {
                $hasAccess = true;
                break;
            }
            if ($role === 'member' && ($userRole === 'member' || $userRole === 'admin')) {
                $hasAccess = true;
                break;
            }
            if ($role === 'user' && in_array($userRole, ['user', 'member', 'admin'])) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            $_SESSION['error'] = 'Anda tidak memiliki akses ke halaman ini.';
            return redirect('/');
        }

        return $next($request);
    }

    /**
     * Check if current user can order a product.
     * User biasa hanya bisa memesan produk Pakan Hidup.
     */
    public static function canOrderProduct($product): bool
    {
        if (empty($_SESSION['user'])) {
            return false;
        }

        $userRole = $_SESSION['user']['type'] ?? 'user';

        // Admin dan Member bisa memesan semua produk
        if (in_array($userRole, ['admin', 'member'])) {
            return true;
        }

        // User biasa hanya bisa memesan produk Pakan Hidup
        if ($userRole === 'user') {
            return $product->isPakanHidup();
        }

        return false;
    }

    /**
     * Get current user role from session.
     */
    public static function getCurrentUserRole(): string
    {
        return $_SESSION['user']['type'] ?? 'guest';
    }

    /**
     * Check if current user is admin.
     */
    public static function isAdmin(): bool
    {
        return ($_SESSION['user']['type'] ?? '') === 'admin';
    }

    /**
     * Check if current user is member.
     */
    public static function isMember(): bool
    {
        $type = $_SESSION['user']['type'] ?? '';
        return $type === 'member' || $type === 'admin'; // Admin juga dianggap member
    }

    /**
     * Check if current user is regular user.
     */
    public static function isUser(): bool
    {
        return ($_SESSION['user']['type'] ?? '') === 'user';
    }

    /**
     * Check if user is logged in.
     */
    public static function isLoggedIn(): bool
    {
        return !empty($_SESSION['user']);
    }
}

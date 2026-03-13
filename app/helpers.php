<?php

// FISHERIES - Custom Helper Functions
// Only define helpers that don't exist in Laravel

if (!function_exists('format_rupiah')) {
    /**
     * Format number to Rupiah currency
     */
    function format_rupiah($number) {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }
}

if (!function_exists('format_date')) {
    /**
     * Format date to Indonesian format
     */
    function format_date($date, $format = 'd F Y') {
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];
        
        $date = date($format, strtotime($date));
        
        foreach ($months as $en => $id) {
            $date = str_replace($en, $id, $date);
        }
        
        return $date;
    }
}

if (!function_exists('truncate_text')) {
    /**
     * Truncate text to specified length
     */
    function truncate_text($text, $length = 100, $suffix = '...') {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . $suffix;
    }
}

if (!function_exists('generate_slug')) {
    /**
     * Generate URL-friendly slug
     */
    function generate_slug($text) {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        $text = trim($text, '-');
        
        return $text;
    }
}

if (!function_exists('get_city_name')) {
    /**
     * Get full city name from slug
     */
    function get_city_name($slug) {
        $cities = [
            'samarinda' => 'Samarinda',
            'bontang' => 'Bontang',
            'balikpapan' => 'Balikpapan',
            'sangatta' => 'Sangatta',
            'berau' => 'Berau',
            'kukar' => 'Kutai Kartanegara',
            'paser' => 'Paser',
            'penajam' => 'Penajam Paser Utara',
            'kubar' => 'Kutai Barat',
            'kutim' => 'Kutai Timur',
            'mahulu' => 'Mahakam Ulu'
        ];
        
        return $cities[$slug] ?? ucfirst($slug);
    }
}

if (!function_exists('get_occupation_name')) {
    /**
     * Get occupation display name
     */
    function get_occupation_name($occupation) {
        $occupations = [
            'nelayan' => 'Nelayan',
            'pembudidaya' => 'Pembudidaya',
            'pengolah' => 'Pengolah Ikan',
            'pedagang' => 'Pedagang',
            'penyuluh' => 'Penyuluh Perikanan',
            'peneliti' => 'Peneliti',
            'lainnya' => 'Lainnya'
        ];
        
        return $occupations[$occupation] ?? ucfirst($occupation);
    }
}

if (!function_exists('get_member_status_color')) {
    /**
     * Get status color class
     */
    function get_member_status_color($status) {
        $colors = [
            'active' => 'green',
            'expired' => 'red',
            'suspended' => 'yellow',
            'pending' => 'gray'
        ];
        
        return $colors[$status] ?? 'gray';
    }
}

if (!function_exists('generate_member_number')) {
    /**
     * Generate unique member number
     */
    function generate_member_number($dpc_code) {
        $year = date('Y');
        $random = strtoupper(substr(uniqid(), -4));
        
        return "FIS-{$dpc_code}-{$year}-{$random}";
    }
}

if (!function_exists('calculate_discount')) {
    /**
     * Calculate discounted price
     */
    function calculate_discount($price, $discountPercent) {
        return $price - ($price * $discountPercent / 100);
    }
}

if (!function_exists('get_setting')) {
    /**
     * Get setting value from settings.json
     */
    function get_setting($key, $default = null) {
        static $settings = null;

        if ($settings === null) {
            $settingsFile = storage_path('app/settings.json');
            if (file_exists($settingsFile)) {
                $settings = json_decode(file_get_contents($settingsFile), true);
            } else {
                $settings = [];
            }
        }

        return $settings[$key] ?? $default;
    }
}

if (!function_exists('get_settings')) {
    /**
     * Get all settings
     */
    function get_settings() {
        static $settings = null;

        if ($settings === null) {
            $settingsFile = storage_path('app/settings.json');
            if (file_exists($settingsFile)) {
                $settings = json_decode(file_get_contents($settingsFile), true);
            } else {
                // Default settings
                $settings = [
                    'site_name' => 'FISHERIES',
                    'site_tagline' => 'Forum Komunikasi Perikanan Indonesia',
                    'site_description' => 'Website resmi FISHERIES - Forum Komunikasi Perikanan Indonesia',
                    'site_email' => 'info@fisheries.id',
                    'site_phone' => '(0541) 123456',
                    'site_address' => 'Jl. Delima Dalam Blok E, Sidodadi, Kec. Samarinda Ulu, Kota Samarinda, Kalimantan Timur 75243',
                    'facebook_url' => '',
                    'twitter_url' => '',
                    'instagram_url' => '',
                    'youtube_url' => '',
                    'whatsapp_number' => '',
                ];
            }
        }

        return $settings;
    }
}

if (!function_exists('get_product_category_icon')) {
    /**
     * Get Font Awesome icon for product category
     */
    function get_product_category_icon($category) {
        $icons = [
            'pelet-pakan' => 'fa-fish',
            'pakan-hidup' => 'fa-worm',
            'umpan-laut' => 'fa-anchor',
            'penyewaan-kapal' => 'fa-ship',
            'vitamin-air' => 'fa-flask',
            'bibit-ikan' => 'fa-egg'
        ];
        
        return $icons[$category] ?? 'fa-box';
    }
}

if (!function_exists('get_product_category_color')) {
    /**
     * Get Tailwind color class for product category
     */
    function get_product_category_color($category) {
        $colors = [
            'pelet-pakan' => 'blue',
            'pakan-hidup' => 'green',
            'umpan-laut' => 'indigo',
            'penyewaan-kapal' => 'cyan',
            'vitamin-air' => 'teal',
            'bibit-ikan' => 'emerald'
        ];
        
        return $colors[$category] ?? 'gray';
    }
}

if (!function_exists('sanitize_input')) {
    /**
     * Sanitize user input
     */
    function sanitize_input($input) {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('generate_qr_code')) {
    /**
     * Generate QR code URL (placeholder for actual QR generation)
     */
    function generate_qr_code($data, $size = 200) {
        // In production, use a QR code library
        // This is a placeholder using a QR code API
        $encoded = urlencode($data);
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encoded}";
    }
}

if (!function_exists('send_notification')) {
    /**
     * Send notification (placeholder)
     */
    function send_notification($to, $message, $type = 'email') {
        // In production, integrate with email/SMS service
        // This is a placeholder
        error_log("Notification sent to {$to}: {$message}");
        return true;
    }
}

if (!function_exists('log_activity')) {
    /**
     * Log user activity
     */
    function log_activity($userId, $action, $details = null) {
        // In production, save to database
        $log = [
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Save to file for now
        $logFile = storage_path('logs/activity.log');
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, json_encode($log) . PHP_EOL, FILE_APPEND);
    }
}

if (!function_exists('get_pagination')) {
    /**
     * Generate pagination data
     */
    function get_pagination($total, $perPage, $currentPage) {
        $totalPages = ceil($total / $perPage);
        $currentPage = max(1, min($currentPage, $totalPages));
        
        return [
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'has_more' => $currentPage < $totalPages,
            'offset' => ($currentPage - 1) * $perPage
        ];
    }
}

if (!function_exists('upload_file')) {
    /**
     * Handle file upload
     */
    function upload_file($file, $directory = 'uploads', $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']) {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'error' => 'No file uploaded'];
        }
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $allowedTypes)) {
            return ['success' => false, 'error' => 'Invalid file type'];
        }
        
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $uploadPath = public_path($directory . '/' . $filename);
        
        $uploadDir = dirname($uploadPath);
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return [
                'success' => true,
                'filename' => $filename,
                'path' => $directory . '/' . $filename
            ];
        }
        
        return ['success' => false, 'error' => 'Failed to move uploaded file'];
    }
}

if (!function_exists('cache_data')) {
    /**
     * Simple file-based caching
     */
    function cache_data($key, $data = null, $ttl = 3600) {
        $cacheDir = storage_path('cache');
        $cacheFile = $cacheDir . '/' . md5($key) . '.cache';
        
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        
        // Get cached data
        if ($data === null) {
            if (!file_exists($cacheFile)) {
                return null;
            }
            
            $cached = unserialize(file_get_contents($cacheFile));
            
            if ($cached['expires'] < time()) {
                unlink($cacheFile);
                return null;
            }
            
            return $cached['data'];
        }
        
        // Store data
        $cache = [
            'expires' => time() + $ttl,
            'data' => $data
        ];
        
        file_put_contents($cacheFile, serialize($cache));
        
        return $data;
    }
}

if (!function_exists('clear_cache')) {
    /**
     * Clear cached data
     */
    function clear_cache($key = null) {
        $cacheDir = storage_path('cache');
        
        if ($key) {
            $cacheFile = $cacheDir . '/' . md5($key) . '.cache';
            if (file_exists($cacheFile)) {
                unlink($cacheFile);
            }
            return;
        }
        
        // Clear all cache
        if (is_dir($cacheDir)) {
            $files = glob($cacheDir . '/*.cache');
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
}

// ============================================
// ROLE SYSTEM HELPERS
// ============================================

if (!function_exists('is_logged_in')) {
    /**
     * Check if user is logged in
     */
    function is_logged_in(): bool {
        return \Illuminate\Support\Facades\Session::has('user');
    }
}

if (!function_exists('get_current_user_role')) {
    /**
     * Get current user role from session
     */
    function get_current_user_role(): string {
        $user = \Illuminate\Support\Facades\Session::get('user');
        return $user['type'] ?? 'guest';
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if current user is admin
     */
    function is_admin(): bool {
        $user = \Illuminate\Support\Facades\Session::get('user');
        return ($user['type'] ?? '') === 'admin';
    }
}

if (!function_exists('is_member')) {
    /**
     * Check if current user is member (including admin)
     */
    function is_member(): bool {
        $user = \Illuminate\Support\Facades\Session::get('user');
        $type = $user['type'] ?? '';
        return $type === 'member' || $type === 'admin';
    }
}

if (!function_exists('is_user')) {
    /**
     * Check if current user is regular user only
     */
    function is_user(): bool {
        $user = \Illuminate\Support\Facades\Session::get('user');
        return ($user['type'] ?? '') === 'user';
    }
}


if (!function_exists('can_order_product')) {
    /**
     * Check if current user can order a product
     * User biasa hanya bisa memesan produk Pakan Hidup
     */
    function can_order_product($product): bool {
        if (!is_logged_in()) {
            return false;
        }

        $userRole = get_current_user_role();

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
}

if (!function_exists('get_product_price_for_user')) {
    /**
     * Get product price based on user role
     * Admin and Member get member_price, User gets regular price
     */
    function get_product_price_for_user($product): float {
        $userRole = get_current_user_role();
        
        // Default to regular price
        $basePrice = $product->getFinalPrice();
        
        // If user is admin or member, use member price if available
        if (in_array($userRole, ['admin', 'member']) && $product->member_price > 0) {
            // Check if there's an active sale price that's even lower
            if ($product->isOnSale() && $product->sale_price < $product->member_price) {
                return $product->sale_price;
            }
            return $product->member_price;
        }
        
        return $basePrice;
    }
}

if (!function_exists('get_order_error_message')) {
    /**
     * Get error message when user cannot order product
     */
    function get_order_error_message(): string {
        return 'Produk ini hanya tersedia untuk anggota. Silakan daftar menjadi anggota untuk melakukan pemesanan.';
    }
}

if (!function_exists('require_login')) {
    /**
     * Redirect to login if not logged in
     */
    function require_login() {
        if (!is_logged_in()) {
            \Illuminate\Support\Facades\Session::flash('error', 'Silakan login terlebih dahulu.');
            redirect('/login');
            exit;
        }
    }
}

if (!function_exists('require_role')) {
    /**
     * Check if user has required role, redirect if not
     */
    function require_role(...$roles) {
        require_login();
        
        $userRole = get_current_user_role();
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
            \Illuminate\Support\Facades\Session::flash('error', 'Anda tidak memiliki akses ke halaman ini.');
            redirect('/');
            exit;
        }
    }
}

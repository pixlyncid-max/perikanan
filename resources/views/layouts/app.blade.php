<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(get_setting('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . get_setting('site_favicon')) }}">
    @endif
    @if(get_setting('site_description'))
        <meta name="description" content="{{ get_setting('site_description') }}">
    @endif
    <title>@yield('title', get_setting('site_name', 'FISHERIES') . ' - ' . get_setting('site_tagline', 'Indonesian Fisheries Community'))</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .dropdown-menu {
            display: none;
        }
        .dropdown-menu.active {
            display: block;
        }
        .dropdown-toggle.active {
            color: #2563eb;
        }
        .dropdown-toggle.active i {
            transform: rotate(180deg);
        }
        .dropdown-toggle i {
            transition: transform 0.2s ease;
        }

        /* Success Modal Styles */
        #success-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            animation: modalFadeIn 0.3s ease-out;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translate(-50%, -60%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }

        /* Flying Animation Styles */
        .fly-item {
            position: fixed;
            z-index: 9999;
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            pointer-events: none;
            transition: all 0.8s cubic-bezier(0.42, 0, 0.58, 1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            border: 2px solid white;
        }

        /* Checkout Modal Styles */
        #checkout-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 10000;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        #checkout-modal-content {
            background: white;
            width: 100%;
            max-width: 800px;
            height: 85vh;
            max-height: 800px;
            min-height: 500px;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            position: relative;
            animation: modalPop 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* Alert Modal Styles */
        #alert-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        #alert-modal-content {
            background: white;
            width: 100%;
            max-width: 450px;
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
    </style>

    
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2">
                    <img src="{{ asset('images/Logo_Symbol.png') }}" alt="Logo Symbol" class="h-10 w-10 object-contain">
                    <img src="{{ asset('images/Logo_Font.png') }}" alt="Logo Font" class="h-8 object-contain">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="/" class="px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition">Beranda</a>
                    
                    <!-- Organization Dropdown -->
                    <div class="dropdown relative">
                        <button class="dropdown-toggle px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition flex items-center" onclick="toggleDropdown(this)">
                            Organisasi <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>

                        <div class="dropdown-menu absolute top-full left-0 w-64 bg-white rounded-lg shadow-xl border mt-1 py-2">
                            <a href="/organization/structure" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Struktur Organisasi</a>
                            <div class="border-t my-1"></div>
                            <p class="px-4 py-1 text-xs text-gray-500 font-semibold">DPC KALTIM</p>
                            <a href="/organization/dpc/samarinda" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">DPC Samarinda</a>
                            <a href="/organization/dpc/balikpapan" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">DPC Balikpapan</a>
                            <a href="/organization/dpc/bontang" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">DPC Bontang</a>
                            <a href="/organization/dpc/berau" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">DPC Berau</a>
                            <a href="/organization/dpc/kutai-kartanegara" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">DPC Kutai Kartanegara</a>
                            <a href="/organization/dpc/paser" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">DPC Paser</a>
                            <a href="/organization/dpc/penajam-paser-utara" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">DPC Penajam Paser Utara</a>
                            <a href="/organization/dpc/kutai-barat" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">DPC Kutai Barat</a>
                            <a href="/organization/dpc/kutai-timur" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">DPC Kutai Timur</a>
                            <a href="/organization/dpc/mahakam-ulu" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">DPC Mahakam Ulu</a>


                        </div>
                    </div>

                    <!-- Produk Dropdown -->
                    <div class="dropdown relative">
                        <button class="dropdown-toggle px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition flex items-center" onclick="toggleDropdown(this)">
                            Produk <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>

                        <div class="dropdown-menu absolute top-full left-0 w-64 bg-white rounded-lg shadow-xl border mt-1 py-2">
                            <!-- Semua Produk -->
                            <a href="/produk" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium border-b border-gray-200">Semua Produk</a>
                            
                            <!-- SPOT AIR TAWAR -->
                            <div class="px-4 py-1.5 border-t border-gray-200">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider cursor-default">SPOT AIR TAWAR</span>
                            </div>

                            <a href="/produk/pelet-pakan" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 pl-6">Pelet Pakan Ikan</a>
                            <a href="/produk/pakan-hidup" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 pl-6">Pakan Hidup</a>
                            <a href="/produk/sewa-pancing" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 pl-6">Sewa Pancing</a>
                            <a href="/produk/kolam-pemancingan" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 pl-6">Kolam Pemancingan</a>
                            <a href="/produk/komunitas-air-tawar" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 pl-6">Komunitas Air Tawar</a>
                            
                            <!-- SPOT AIR LAUT -->
                            <div class="px-4 py-1.5 border-t border-gray-200 mt-1">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider cursor-default">SPOT AIR LAUT</span>
                            </div>

                            <a href="/produk/umpan-laut" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 pl-6">Umpan Ikan Laut</a>
                            <a href="/produk/sewa-pancing-laut" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 pl-6">Sewa Pancing Laut</a>
                            <a href="/produk/penyewaan-kapal" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 pl-6">Sewa Kapal</a>
                            <a href="/produk/komunitas-air-laut" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 pl-6">Komunitas Air Laut</a>
                            
                            <!-- LAIN-LAIN -->
                            <div class="px-4 py-1.5 border-t border-gray-200 mt-1">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider cursor-default">LAIN-LAIN</span>
                            </div>

                            <a href="/produk/vitamin-air" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 pl-6">Vitamin</a>
                            <a href="/produk/bibit-ikan" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 pl-6">Bibit</a>
                        </div>
                    </div>


                    <a href="/about" class="px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition">Tentang Kami</a>
                    <a href="/partnership" class="px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition">Kemitraan</a>
                    <a href="/article" class="px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition">Artikel</a>
                    <a href="/contact" class="px-3 py-2 text-gray-700 hover:text-blue-600 font-medium transition">Kontak</a>
                </div>

                <!-- Cart & Auth Buttons -->
                <div class="hidden md:flex items-center space-x-3">
                    <!-- Favorit Icon -->
                    <a href="{{ route('produk.favorit') }}" id="navbar-favorit-icon" class="relative p-2 text-gray-700 hover:text-red-500 transition">
                        <i class="far fa-heart text-xl"></i>
                    </a>
                    
                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" id="navbar-cart-icon" class="relative p-2 text-gray-700 hover:text-blue-600 transition">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        @php $cartCount = array_sum(array_column(Session::get('cart', []), 'quantity')); @endphp
                        @if($cartCount > 0)
                            <span class="cart-counter-badge absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">
                                {{ $cartCount }}
                            </span>
                        @else
                            <span class="cart-counter-badge absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white hidden">
                                0
                            </span>
                        @endif
                    </a>

                    <?php if(!is_logged_in()): ?>
                        <a href="/login" class="px-4 py-2 text-blue-600 font-medium hover:text-blue-700 transition">Login</a>
                        <a href="/register" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">Daftar</a>
                    <?php else: ?>
                        <?php $user = \Illuminate\Support\Facades\Session::get('user'); ?>
                        <div class="dropdown relative">
                            <button class="dropdown-toggle flex items-center space-x-2 text-gray-700 hover:text-blue-600 font-medium transition" onclick="toggleDropdown(this)">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <span><?php echo e($user['name'] ?? 'User'); ?></span>
                                <?php if($user['type'] === 'admin'): ?>
                                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">ADMIN</span>
                                <?php elseif($user['type'] === 'member'): ?>
                                    <span class="bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">MEMBER</span>
                                <?php endif; ?>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <div class="dropdown-menu absolute top-full right-0 w-48 bg-white rounded-lg shadow-xl border mt-1 py-2">
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                    <i class="fas fa-shopping-bag mr-2"></i>Pesanan Saya
                                </a>
                                <a href="/member-card" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                    <i class="fas fa-id-card mr-2"></i>Kartu Anggota
                                </a>
                                <?php if($user['type'] === 'admin'): ?>
                                    <a href="/admin/dashboard" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-cog mr-2"></i>Panel Admin
                                    </a>
                                <?php endif; ?>

                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                    <i class="fas fa-user-cog mr-2"></i>Profil
                                </a>
                                <div class="border-t my-1"></div>
                                <form action="/logout" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>


                <!-- Mobile Cart & Menu Button -->
                <div class="flex items-center gap-3 md:hidden">
                    <!-- Favorit Mobile -->
                    <a href="{{ route('produk.favorit') }}" class="relative p-2 text-gray-700 hover:text-red-500 transition">
                        <i class="far fa-heart text-xl"></i>
                    </a>
                    
                    <a href="{{ route('cart.index') }}" id="mobile-cart-icon" class="relative p-2 text-gray-700 hover:text-blue-600 transition">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        @php $cartCount = array_sum(array_column(Session::get('cart', []), 'quantity')); @endphp
                        @if($cartCount > 0)
                            <span class="cart-counter-badge absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">
                                {{ $cartCount }}
                            </span>
                        @else
                            <span class="cart-counter-badge absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white hidden">
                                0
                            </span>
                        @endif
                    </a>

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 focus:outline-none transition-all duration-200 shadow-sm hover:shadow-md">
                        <i id="menu-icon" class="fas fa-bars text-xl transition-transform duration-200"></i>
                    </button>
                </div>


            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t shadow-lg max-h-[80vh] overflow-y-auto">
            <div class="px-4 py-3 space-y-1">

                <a href="/" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Beranda</a>
                <a href="/organization/structure" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Organisasi</a>
                <!-- Produk Mobile Menu -->
                <div class="space-y-1">
                    <a href="/produk" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium border-b border-gray-200">Semua Produk</a>
                    
                    <div class="px-3 py-1 border-t border-gray-200">
                        <span class="text-xs font-bold text-gray-500 uppercase">SPOT AIR TAWAR</span>
                    </div>

                    <a href="/produk/pelet-pakan" class="block px-3 py-2 text-gray-600 hover:text-blue-600 pl-6">Pelet Pakan Ikan</a>
                    <a href="/produk/pakan-hidup" class="block px-3 py-2 text-gray-600 hover:text-blue-600 pl-6">Pakan Hidup</a>
                    <a href="/produk/sewa-pancing" class="block px-3 py-2 text-gray-600 hover:text-blue-600 pl-6">Sewa Pancing</a>
                    <a href="/produk/kolam-pemancingan" class="block px-3 py-2 text-gray-600 hover:text-blue-600 pl-6">Kolam Pemancingan</a>
                    <a href="/produk/komunitas-air-tawar" class="block px-3 py-2 text-gray-600 hover:text-blue-600 pl-6">Komunitas Air Tawar</a>
                    
                    <div class="px-3 py-1 border-t border-gray-200 mt-1">
                        <span class="text-xs font-bold text-gray-500 uppercase">SPOT AIR LAUT</span>
                    </div>

                    <a href="/produk/umpan-laut" class="block px-3 py-2 text-gray-600 hover:text-blue-600 pl-6">Umpan Ikan Laut</a>
                    <a href="/produk/sewa-pancing-laut" class="block px-3 py-2 text-gray-600 hover:text-blue-600 pl-6">Sewa Pancing Laut</a>
                    <a href="/produk/penyewaan-kapal" class="block px-3 py-2 text-gray-600 hover:text-blue-600 pl-6">Sewa Kapal</a>
                    <a href="/produk/komunitas-air-laut" class="block px-3 py-2 text-gray-600 hover:text-blue-600 pl-6">Komunitas Air Laut</a>
                    
                    <div class="px-3 py-1 border-t border-gray-200 mt-1">
                        <span class="text-xs font-bold text-gray-500 uppercase">LAIN-LAIN</span>
                    </div>

                    <a href="/produk/vitamin-air" class="block px-3 py-2 text-gray-600 hover:text-blue-600 pl-6">Vitamin</a>
                    <a href="/produk/bibit-ikan" class="block px-3 py-2 text-gray-600 hover:text-blue-600 pl-6">Bibit</a>
                </div>

                <a href="/about" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Tentang Kami</a>
                <a href="/partnership" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Kemitraan</a>
                <a href="/article" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Artikel</a>
                <a href="/contact" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Kontak</a>
                <div class="border-t pt-2 mt-2">
                    <?php if(!is_logged_in()): ?>
                        <a href="/login" class="block px-3 py-2 text-blue-600 font-medium">Login</a>
                        <a href="/register" class="block px-3 py-2 bg-blue-600 text-white rounded-lg font-medium text-center">Daftar</a>
                    <?php else: ?>
                        <?php $userMobile = \Illuminate\Support\Facades\Session::get('user'); ?>
                        <a href="{{ route('orders.index') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Pesanan Saya</a>
                        <a href="/member-card" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Kartu Anggota</a>
                        <?php if($userMobile['type'] === 'admin'): ?>
                            <a href="/admin/dashboard" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Panel Admin</a>
                        <?php endif; ?>
                        <a href="{{ route('profile') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Profil</a>

                        <form action="/logout" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-red-600 font-medium">Logout</button>
                        </form>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </nav>

    <!-- Success Modal (Centered) -->
    <div id="success-modal" class="bg-gray-900/95 backdrop-blur-md p-6 md:p-8 rounded-2xl shadow-2xl text-center max-w-sm w-[calc(100%-2rem)] shadow-[0_0_50px_rgba(0,0,0,0.3)]">
        <div class="mb-4 flex justify-center">
            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center animate-bounce">
                <i class="fas fa-check text-white text-4xl"></i>
            </div>
        </div>
        <h3 id="modal-message" class="text-white text-2xl font-bold mb-2">Berhasil!</h3>
        <p class="text-gray-300">Produk telah ditambahkan ke keranjang belanja</p>
    </div>

    <!-- Checkout Modal (Iframe) -->
    <div id="checkout-modal">
        <div id="checkout-modal-content">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white shadow-[0_4px_20px_-10px_rgba(0,0,0,0.1)] relative z-10 rounded-t-3xl">
                <div class="flex flex-col items-start">
                    <h3 class="font-extrabold text-xl text-gray-900 tracking-tight flex items-center gap-2">
                        Pembayaran
                    </h3>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <i class="fas fa-lock text-green-500 text-[10px]"></i>
                        <span class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider">Secured by Xendit</span>
                    </div>
                </div>
                <button onclick="closeCheckoutModal()" class="w-10 h-10 rounded-full bg-gray-50 hover:bg-red-50 hover:text-red-500 flex items-center justify-center text-gray-400 transition-all duration-300">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="flex-grow bg-white relative rounded-b-3xl overflow-hidden">
                <div id="checkout-loader" class="absolute inset-0 flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm z-20 transition-opacity duration-300">
                    <div class="w-16 h-16 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin mb-4"></div>
                    <p class="text-gray-600 font-medium animate-pulse">Menyiapkan pembayaran aman...</p>
                </div>
                <iframe id="checkout-iframe" src="" class="w-full h-full border-none" onload="document.getElementById('checkout-loader').style.opacity='0'; setTimeout(()=>document.getElementById('checkout-loader').style.display='none', 300)"></iframe>
            </div>
        </div>
    </div>

    <!-- Professional Alert Modal -->
    <div id="alert-modal">
        <div id="alert-modal-content" class="p-8 text-center">
            <div id="alert-icon-container" class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center text-4xl">
                <i id="alert-icon" class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 id="alert-title" class="text-2xl font-bold text-gray-900 mb-2">Perhatian</h3>
            <p id="alert-message" class="text-gray-600 mb-8 leading-relaxed">Pesan notifikasi di sini.</p>
            <div class="flex flex-col gap-3">
                <button id="alert-primary-btn" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                    Lanjutkan
                </button>
                <button id="alert-secondary-btn" onclick="closeAlert()" class="w-full py-3 text-gray-500 font-semibold hover:text-gray-700 transition">
                    Nanti Saja
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-4 mt-4 rounded shadow">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mx-4 mt-4 rounded shadow">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-[100000] flex flex-col gap-3 pointer-events-none"></div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="{{ asset('images/Logo_Symbol_White.png') }}" alt="Logo Symbol" class="h-10 w-10 object-contain">
                        <img src="{{ asset('images/Logo_font_Putih.png') }}" alt="Logo Font" class="h-8 object-contain">
                    </div>
                    <p class="text-gray-400 text-sm mb-4">
                        {{ get_setting('footer_description', 'Indonesian Fisheries Community - Komunitas perikanan terbesar di Kalimantan Timur.') }}
                    </p>
                    <div class="flex space-x-3">
                        @if(get_setting('instagram_url'))
                            <a href="{{ get_setting('instagram_url') }}" target="_blank" class="w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-pink-600 transition">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if(get_setting('facebook_url'))
                            <a href="{{ get_setting('facebook_url') }}" target="_blank" class="w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-blue-600 transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                        @if(get_setting('tiktok_url'))
                            <a href="{{ get_setting('tiktok_url') }}" target="_blank" class="w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-black transition">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        @endif
                        @if(get_setting('youtube_url'))
                            <a href="{{ get_setting('youtube_url') }}" target="_blank" class="w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-red-600 transition">
                                <i class="fab fa-youtube"></i>
                            </a>
                        @endif
                        @if(get_setting('whatsapp_number'))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', get_setting('whatsapp_number')) }}" target="_blank" class="w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-green-500 transition">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="/about" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="/produk" class="hover:text-white transition">Produk</a></li>
                        <li><a href="/organization/structure" class="hover:text-white transition">Struktur Organisasi</a></li>
                        <li><a href="/article" class="hover:text-white transition">Artikel</a></li>
                        <li><a href="/partnership" class="hover:text-white transition">Kemitraan</a></li>
                    </ul>
                </div>

                <!-- Products -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Produk</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="/produk/pelet-pakan" class="hover:text-white transition">Pelet Pakan Ikan</a></li>
                        <li><a href="/produk/pakan-hidup" class="hover:text-white transition">Pakan Hidup</a></li>
                        <li><a href="/produk/umpan-laut" class="hover:text-white transition">Umpan Ikan Laut</a></li>
                        <li><a href="/produk/penyewaan-kapal" class="hover:text-white transition">Penyewaan Kapal</a></li>
                        <li><a href="/produk/vitamin-air" class="hover:text-white transition">Vitamin Air</a></li>
                        <li><a href="/produk/bibit-ikan" class="hover:text-white transition">Bibit Ikan</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt mt-1"></i>
                            <span>{!! nl2br(e(get_setting('site_address', 'Jl. Delima Dalam Blok E<br>Sidodadi, Kec. Samarinda Ulu<br>Kota Samarinda, Kalimantan Timur 75243'))) !!}</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-phone"></i>
                            <span>{{ get_setting('site_phone', '0541-123456') }}</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-envelope"></i>
                            <span>{{ get_setting('site_email', 'fisheriesborneo@gmail.com') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>{{ get_setting('footer_copyright', '© 2026 FISHERIES - Indonesian Fisheries Community. All rights reserved.') }}</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        
        mobileMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            mobileMenu.classList.toggle('hidden');
            
            // Toggle icon between bars and X with animation
            if (mobileMenu.classList.contains('hidden')) {
                menuIcon.classList.remove('fa-times');
                menuIcon.classList.add('fa-bars');
                mobileMenuBtn.classList.remove('bg-blue-100', 'text-blue-700', 'shadow-md');
                mobileMenuBtn.classList.add('bg-blue-50', 'text-blue-600', 'shadow-sm');
            } else {
                menuIcon.classList.remove('fa-bars');
                menuIcon.classList.add('fa-times');
                mobileMenuBtn.classList.remove('bg-blue-50', 'text-blue-600', 'shadow-sm');
                mobileMenuBtn.classList.add('bg-blue-100', 'text-blue-700', 'shadow-md');
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target) && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
                menuIcon.classList.remove('fa-times');
                menuIcon.classList.add('fa-bars');
                mobileMenuBtn.classList.remove('bg-blue-100', 'text-blue-700', 'shadow-md');
                mobileMenuBtn.classList.add('bg-blue-50', 'text-blue-600', 'shadow-sm');
            }
        });



        // Dropdown Toggle Function
        function toggleDropdown(button) {
            const dropdown = button.parentElement;
            const menu = dropdown.querySelector('.dropdown-menu');
            const isActive = menu.classList.contains('active');
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(m => {
                m.classList.remove('active');
            });
            document.querySelectorAll('.dropdown-toggle').forEach(b => {
                b.classList.remove('active');
            });
            
            // Toggle current dropdown
            if (!isActive) {
                menu.classList.add('active');
                button.classList.add('active');
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(m => {
                    m.classList.remove('active');
                });
                document.querySelectorAll('.dropdown-toggle').forEach(b => {
                    b.classList.remove('active');
                });
            }
        });

        // Add to Cart global function
        function addToCart(productId, variationId = null) {
            const btn = document.getElementById(`cart-btn-${productId}`);
            const img = document.getElementById(`product-img-${productId}`);
            let cartIconLink = document.getElementById('navbar-cart-icon');
            
            // Check if mobile cart is visible
            if (cartIconLink && (cartIconLink.offsetParent === null || window.getComputedStyle(cartIconLink).display === 'none')) {
                cartIconLink = document.getElementById('mobile-cart-icon');
            }
            
            const cartIcon = cartIconLink || document.querySelector('.fa-shopping-cart').parentElement;

            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    product_id: productId,
                    variation_id: variationId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Start Animation
                    if (img && cartIcon) {
                        const imgRect = img.getBoundingClientRect();
                        const cartRect = cartIcon.getBoundingClientRect();

                        // Create flyer
                        const flyer = document.createElement('img');
                        flyer.src = img.src;
                        flyer.className = 'fly-item';
                        
                        // Start position (Center of the source image)
                        const startTop = imgRect.top + (imgRect.height / 2) - 25;
                        const startLeft = imgRect.left + (imgRect.width / 2) - 25;
                        
                        flyer.style.top = startTop + 'px';
                        flyer.style.left = startLeft + 'px';
                        flyer.style.width = '50px';
                        flyer.style.height = '50px';
                        flyer.style.opacity = '1';
                        flyer.style.zIndex = '100000';
                        flyer.style.boxShadow = '0 0 20px rgba(37, 99, 235, 0.5)'; // Blue glow
                        
                        document.body.appendChild(flyer);

                        // Trigger animation after a small delay to ensure DOM paint
                        requestAnimationFrame(() => {
                            setTimeout(() => {
                                flyer.style.top = (cartRect.top + (cartRect.height / 2) - 5) + 'px';
                                flyer.style.left = (cartRect.left + (cartRect.width / 2) - 5) + 'px';
                                flyer.style.width = '10px';
                                flyer.style.height = '10px';
                                flyer.style.opacity = '0.5';
                                flyer.style.transform = 'scale(0.2) rotate(360deg)';
                            }, 50);
                        });

                        flyer.addEventListener('transitionend', () => {
                            flyer.remove();
                            // Update all counters and shake cart
                            const counters = document.querySelectorAll('.cart-counter-badge');
                            counters.forEach(counter => {
                                counter.textContent = data.cart_count;
                                counter.classList.remove('hidden');
                                // Mini bounce for badge
                                counter.classList.add('animate-ping');
                                setTimeout(() => counter.classList.remove('animate-ping'), 500);
                            });
                            // Shake cart icon
                            if (cartIcon) {
                                cartIcon.classList.add('animate-bounce');
                                setTimeout(() => cartIcon.classList.remove('animate-bounce'), 1000);
                            }
                        });
                    }

                    // Show centered modal (Optional, but kept for feedback)
                    const modal = document.getElementById('success-modal');
                    if (modal) {
                        modal.style.display = 'block';
                        setTimeout(() => {
                            modal.style.display = 'none';
                        }, 2500);
                    }
                } else {
                    alert(data.message || 'Gagal menambahkan ke keranjang');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan.');
            });
        }

        // Checkout Modal Functions
        function openCheckoutModal(url) {
            const modal = document.getElementById('checkout-modal');
            const iframe = document.getElementById('checkout-iframe');
            const loader = document.getElementById('checkout-loader');
            
            if (modal && iframe) {
                loader.style.display = 'flex';
                iframe.src = url;
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden'; // Prevent scroll
            }
        }

        function closeCheckoutModal() {
            const modal = document.getElementById('checkout-modal');
            const iframe = document.getElementById('checkout-iframe');
            
            if (modal && iframe) {
                modal.style.display = 'none';
                iframe.src = '';
                document.body.style.overflow = 'auto'; // Restore scroll
                
                // Redirect user to orders page so they see their pending order
                if (window.location.pathname.includes('/cart')) {
                    window.location.href = '/orders';
                } else {
                    window.location.reload();
                }
            }
        }

        // Global Alert Functions
        function showAlert(options) {
            const modal = document.getElementById('alert-modal');
            const iconContainer = document.getElementById('alert-icon-container');
            const icon = document.getElementById('alert-icon');
            const title = document.getElementById('alert-title');
            const message = document.getElementById('alert-message');
            const primaryBtn = document.getElementById('alert-primary-btn');
            const secondaryBtn = document.getElementById('alert-secondary-btn');

            // Set type defaults
            const types = {
                'warning': { icon: 'fa-exclamation-triangle', bg: 'bg-amber-100', text: 'text-amber-600' },
                'error': { icon: 'fa-times-circle', bg: 'bg-red-100', text: 'text-red-600' },
                'success': { icon: 'fa-check-circle', bg: 'bg-green-100', text: 'text-green-600' },
                'info': { icon: 'fa-info-circle', bg: 'bg-blue-100', text: 'text-blue-600' }
            };

            const type = types[options.type || 'info'];
            
            // Set content
            icon.className = `fas ${type.icon}`;
            iconContainer.className = `w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center text-4xl ${type.bg} ${type.text}`;
            title.textContent = options.title || 'Perhatian';
            message.textContent = options.message || '';
            primaryBtn.textContent = options.primaryText || 'OK';
            
            if (options.secondaryText) {
                secondaryBtn.textContent = options.secondaryText;
                secondaryBtn.style.display = 'block';
            } else {
                secondaryBtn.style.display = 'none';
            }

            // Set Action
            primaryBtn.onclick = function() {
                if (options.onConfirm) {
                    options.onConfirm();
                }
                closeAlert();
            };

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeAlert() {
            const modal = document.getElementById('alert-modal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Global Toast Notifications
        function showToast(title, message, type = 'success') {
            const container = document.getElementById('toast-container');
            if(!container) return;
            
            const toast = document.createElement('div');
            
            const bgHover = type === 'success' ? 'hover:bg-green-50' : (type === 'error' ? 'hover:bg-red-50' : 'hover:bg-blue-50');
            const iconColor = type === 'success' ? 'text-green-500' : (type === 'error' ? 'text-red-500' : 'text-blue-500');
            const iconClass = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle');
            
            toast.className = `bg-white rounded-xl shadow-xl flex items-start gap-3 p-4 border border-gray-100 transform transition-all duration-300 translate-y-full opacity-0 pointer-events-auto max-w-sm ${bgHover}`;
            
            toast.innerHTML = `
                <div class="${iconColor} text-xl shrink-0 mt-0.5">
                    <i class="fas ${iconClass}"></i>
                </div>
                <div class="flex-grow">
                    <h4 class="font-bold text-gray-900 text-sm">${title}</h4>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 transition shrink-0 p-1">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Animate in
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-full', 'opacity-0');
            });
            
            // Auto remove
            setTimeout(() => {
                toast.classList.add('translate-y-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }
    </script>


    @stack('scripts')
</body>
</html>

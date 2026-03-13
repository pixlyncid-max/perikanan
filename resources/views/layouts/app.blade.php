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

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-3">
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
                                <a href="/member-card" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                    <i class="fas fa-id-card mr-2"></i>Kartu Anggota
                                </a>
                                <?php if($user['type'] === 'admin'): ?>
                                    <a href="/admin/dashboard" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-cog mr-2"></i>Panel Admin
                                    </a>
                                <?php endif; ?>

                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
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


                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden w-10 h-10 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 focus:outline-none transition-all duration-200 shadow-sm hover:shadow-md">
                    <i id="menu-icon" class="fas fa-bars text-xl transition-transform duration-200"></i>
                </button>


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
                        <a href="/member-card" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Kartu Anggota</a>
                        <?php if($userMobile['type'] === 'admin'): ?>
                            <a href="/admin/dashboard" class="block px-3 py-2 text-gray-700 hover:text-blue-600 font-medium">Panel Admin</a>
                        <?php endif; ?>

                        <form action="/logout" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-red-600 font-medium">Logout</button>
                        </form>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </nav>

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
                        @if(get_setting('facebook_url'))
                            <a href="{{ get_setting('facebook_url') }}" target="_blank" class="w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-blue-600 transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                        @if(get_setting('twitter_url'))
                            <a href="{{ get_setting('twitter_url') }}" target="_blank" class="w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-sky-500 transition">
                                <i class="fab fa-twitter"></i>
                            </a>
                        @endif
                        @if(get_setting('instagram_url'))
                            <a href="{{ get_setting('instagram_url') }}" target="_blank" class="w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-pink-600 transition">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if(get_setting('whatsapp_number'))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', get_setting('whatsapp_number')) }}" target="_blank" class="w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-green-500 transition">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        @endif
                        @if(get_setting('youtube_url'))
                            <a href="{{ get_setting('youtube_url') }}" target="_blank" class="w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-red-600 transition">
                                <i class="fab fa-youtube"></i>
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
    </script>


    @stack('scripts')
</body>
</html>

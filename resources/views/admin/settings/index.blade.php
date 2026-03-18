@extends('admin.layouts.app')

@section('title', 'Pengaturan Website')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sticky Header -->
    <div class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cog text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Pengaturan Website</h1>
                        <p class="text-sm text-gray-500">Kelola semua aspek tampilan dan konten website</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="resetForm()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                    <button type="submit" form="settingsForm" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form id="settingsForm" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Tab Navigation -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button type="button" onclick="showTab('general')" id="tab-general" class="tab-button active whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                            <i class="fas fa-globe mr-2"></i>Umum
                        </button>
                        <button type="button" onclick="showTab('homepage')" id="tab-homepage" class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </button>
                        <button type="button" onclick="showTab('pages')" id="tab-pages" class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-file-alt mr-2"></i>Halaman
                        </button>
                        <button type="button" onclick="showTab('media')" id="tab-media" class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-images mr-2"></i>Media
                        </button>
                        <button type="button" onclick="showTab('social')" id="tab-social" class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-share-alt mr-2"></i>Sosial
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">

                    <!-- General Tab -->
                    <div id="content-general" class="tab-content">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Site Information -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-building text-blue-500 mr-2"></i>Informasi Website
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Website *</label>
                                            <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'FISHERIES' }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="Masukkan nama website">
                                            <p class="text-xs text-gray-500 mt-1">Nama yang akan ditampilkan di header dan title</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                                            <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="Tagline singkat website">
                                            <p class="text-xs text-gray-500 mt-1">Slogan atau deskripsi singkat</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Website</label>
                                            <textarea name="site_description" rows="3"
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                      placeholder="Deskripsi lengkap website untuk SEO">{{ $settings['site_description'] ?? '' }}</textarea>
                                            <p class="text-xs text-gray-500 mt-1">Deskripsi yang akan muncul di meta description untuk SEO</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-address-book text-green-500 mr-2"></i>Informasi Kontak
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                            <input type="email" name="site_email" value="{{ $settings['site_email'] ?? '' }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="admin@website.com">
                                            <p class="text-xs text-gray-500 mt-1">Email utama untuk kontak</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                                            <input type="text" name="site_phone" value="{{ $settings['site_phone'] ?? '' }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="(0541) 123456">
                                            <p class="text-xs text-gray-500 mt-1">Nomor telepon kantor</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                                            <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="+6281234567890">
                                            <p class="text-xs text-gray-500 mt-1">Nomor WhatsApp untuk komunikasi cepat</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                            <textarea name="site_address" rows="3"
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                      placeholder="Jl. Contoh No. 123&#10;Kota, Provinsi&#10;Kode Pos">{{ $settings['site_address'] ?? '' }}</textarea>
                                            <p class="text-xs text-gray-500 mt-1">Alamat lengkap kantor (gunakan &lt;br&gt; untuk baris baru)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Homepage Tab -->
                    <div id="content-homepage" class="tab-content hidden">
                        <div class="space-y-6">
                            <!-- Hero Section -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-star text-yellow-500 mr-2"></i>Hero Section
                                </h3>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Hero</label>
                                            <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? 'Indonesian Fisheries Community' }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Sub Judul Hero</label>
                                            <textarea name="hero_subtitle" rows="2"
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol 1</label>
                                            <input type="text" name="hero_button1_text" value="{{ $settings['hero_button1_text'] ?? 'Lihat Produk' }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Link Tombol 1</label>
                                            <input type="text" name="hero_button1_url" value="{{ $settings['hero_button1_url'] ?? '/produk' }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="/produk">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol 2</label>
                                            <input type="text" name="hero_button2_text" value="{{ $settings['hero_button2_text'] ?? 'Gabung Sekarang' }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Link Tombol 2</label>
                                            <input type="text" name="hero_button2_url" value="{{ $settings['hero_button2_url'] ?? '/register' }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="/register">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-chart-bar text-purple-500 mr-2"></i>Statistik
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Anggota</label>
                                        <input type="text" name="stats_members" value="{{ $settings['stats_members'] ?? '2000+' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <input type="text" name="stats_members_label" value="{{ $settings['stats_members_label'] ?? 'Anggota Aktif' }}"
                                               class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs"
                                               placeholder="Label">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">DPC</label>
                                        <input type="text" name="stats_dpc" value="{{ $settings['stats_dpc'] ?? '10' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <input type="text" name="stats_dpc_label" value="{{ $settings['stats_dpc_label'] ?? 'DPC Kaltim' }}"
                                               class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs"
                                               placeholder="Label">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                                        <input type="text" name="stats_products" value="{{ $settings['stats_products'] ?? '50+' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <input type="text" name="stats_products_label" value="{{ $settings['stats_products_label'] ?? 'Produk Unggulan' }}"
                                               class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs"
                                               placeholder="Label">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mitra</label>
                                        <input type="text" name="stats_partners" value="{{ $settings['stats_partners'] ?? '500+' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <input type="text" name="stats_partners_label" value="{{ $settings['stats_partners_label'] ?? 'Mitra Bisnis' }}"
                                               class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs"
                                               placeholder="Label">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pages Tab -->
                    <div id="content-pages" class="tab-content hidden">
                        <div class="space-y-6">
                            <!-- About Page -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>Halaman Tentang
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Halaman</label>
                                        <input type="text" name="about_title" value="{{ $settings['about_title'] ?? 'Tentang FISHERIES' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Konten Halaman</label>
                                        <textarea name="about_content" rows="4"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $settings['about_content'] ?? '' }}</textarea>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Visi</label>
                                            <textarea name="about_vision" rows="3"
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $settings['about_vision'] ?? '' }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Misi</label>
                                            <textarea name="about_mission" rows="3"
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $settings['about_mission'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Page -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-envelope text-green-500 mr-2"></i>Halaman Kontak
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Halaman</label>
                                        <input type="text" name="contact_title" value="{{ $settings['contact_title'] ?? 'Hubungi Kami' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Halaman</label>
                                        <textarea name="contact_description" rows="2"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $settings['contact_description'] ?? '' }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Kerja</label>
                                        <textarea name="contact_business_hours" rows="3"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Senin - Jumat: 08.00 - 17.00&#10;Sabtu: 08.00 - 12.00&#10;Minggu: Tutup">{{ $settings['contact_business_hours'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Partnership Page -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-handshake text-purple-500 mr-2"></i>Halaman Kemitraan
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Halaman</label>
                                        <input type="text" name="partnership_title" value="{{ $settings['partnership_title'] ?? 'Kerjasama & Partnership' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Halaman</label>
                                        <textarea name="partnership_description" rows="2"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $settings['partnership_description'] ?? '' }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Konten Halaman</label>
                                        <textarea name="partnership_content" rows="4"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $settings['partnership_content'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media Tab -->
                    <div id="content-media" class="tab-content hidden">
                        <div class="space-y-6">
                            <!-- Logo Upload -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-image text-blue-500 mr-2"></i>Logo & Favicon
                                </h3>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo Website</label>
                                        <input type="file" name="site_logo" accept="image/*"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @if(isset($settings['site_logo']) && $settings['site_logo'])
                                            <div class="mt-3 p-3 bg-white rounded-lg border">
                                                <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Current Logo" class="h-12 mx-auto rounded">
                                                <p class="text-xs text-gray-500 text-center mt-2">Logo saat ini</p>
                                            </div>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-2">Format: PNG, JPG, SVG. Ukuran maksimal: 2MB</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                                        <input type="file" name="site_favicon" accept="image/*"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @if(isset($settings['site_favicon']) && $settings['site_favicon'])
                                            <div class="mt-3 p-3 bg-white rounded-lg border">
                                                <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Current Favicon" class="h-8 w-8 mx-auto rounded">
                                                <p class="text-xs text-gray-500 text-center mt-2">Favicon saat ini</p>
                                            </div>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-2">Format: ICO, PNG. Ukuran: 32x32px</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Tab -->
                    <div id="content-social" class="tab-content hidden">
                        <div class="space-y-6">
                            <!-- Social Media Links -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-share-alt text-purple-500 mr-2"></i>Media Sosial
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook
                                        </label>
                                        <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="https://facebook.com/username">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            <i class="fab fa-tiktok text-black mr-2"></i>TikTok
                                        </label>
                                        <input type="url" name="tiktok_url" value="{{ $settings['tiktok_url'] ?? '' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="https://tiktok.com/@username">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            <i class="fab fa-instagram text-pink-500 mr-2"></i>Instagram
                                        </label>
                                        <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="https://instagram.com/username">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            <i class="fab fa-youtube text-red-500 mr-2"></i>YouTube
                                        </label>
                                        <input type="url" name="youtube_url" value="{{ $settings['youtube_url'] ?? '' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="https://youtube.com/channel">
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Settings -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-sticky-note text-gray-500 mr-2"></i>Footer
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Footer</label>
                                        <textarea name="footer_description" rows="2"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $settings['footer_description'] ?? '' }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Copyright</label>
                                        <input type="text" name="footer_copyright" value="{{ $settings['footer_copyright'] ?? '© 2026 FISHERIES. All rights reserved.' }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </form>
    </div>
</div>

<script>
// Tab functionality
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');

    // Add active class to selected tab
    document.getElementById('tab-' + tabName).classList.add('active', 'border-blue-500', 'text-blue-600');
    document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-gray-500');
}

// Reset form function
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
        document.getElementById('settingsForm').reset();
    }
}

// Image preview functionality
document.addEventListener('DOMContentLoaded', function() {
    // File input preview
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create or update preview
                    let previewContainer = input.parentElement.querySelector('.file-preview');
                    if (!previewContainer) {
                        previewContainer = document.createElement('div');
                        previewContainer.className = 'file-preview mt-3 p-3 bg-white rounded-lg border';
                        input.parentElement.appendChild(previewContainer);
                    }

                    const isImage = file.type.startsWith('image/');
                    if (isImage) {
                        previewContainer.innerHTML = `
                            <img src="${e.target.result}" alt="Preview" class="max-h-20 mx-auto rounded">
                            <p class="text-xs text-gray-500 text-center mt-2">Preview - ${file.name}</p>
                        `;
                    } else {
                        previewContainer.innerHTML = `
                            <div class="text-center">
                                <i class="fas fa-file text-gray-400 text-2xl"></i>
                                <p class="text-xs text-gray-500 mt-1">${file.name}</p>
                            </div>
                        `;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Form validation
    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        const requiredFields = ['site_name', 'site_email'];
        let isValid = true;

        requiredFields.forEach(field => {
            const element = document.getElementById(field);
            if (!element.value.trim()) {
                element.classList.add('border-red-500');
                isValid = false;
            } else {
                element.classList.remove('border-red-500');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi (ditandai dengan *).');
            // Scroll to first error
            const firstError = document.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }

        // Show loading state
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        submitBtn.disabled = true;

        // Re-enable after 10 seconds (in case of error)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });

    // Auto-format phone number
    const phoneInput = document.querySelector('input[name="site_phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = `(${value}`;
                } else if (value.length <= 6) {
                    value = `(${value.slice(0, 3)}) ${value.slice(3)}`;
                } else {
                    value = `(${value.slice(0, 3)}) ${value.slice(3, 6)}-${value.slice(6, 10)}`;
                }
            }
            e.target.value = value;
        });
    }

    // Auto-format WhatsApp number
    const whatsappInput = document.querySelector('input[name="whatsapp_number"]');
    if (whatsappInput) {
        whatsappInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });
    }
});
</script>

<style>
.tab-content {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endsection

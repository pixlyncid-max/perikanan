@extends('layouts.app')

@section('title', 'Tentang Fisheries Indonesia - Komunitas & Komoditas Perikanan')
@section('meta_description', 'Fisheries Indonesia adalah platform dan komunitas perikanan terbesar yang menghubungkan nelayan, pembudidaya ikan, dan pengusaha perikanan.')
@section('meta_keywords', 'tentang fisheries, komunitas perikanan indonesia, nelayan indonesia, pembudidaya ikan, fisheries.id')

@section('content')

    <!-- Hero Section -->
    <div class="bg-white pt-24 pb-16 relative overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div
                class="absolute -top-24 -right-24 w-72 h-72 bg-blue-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob">
            </div>
            <div
                class="absolute -bottom-24 -left-24 w-72 h-72 bg-cyan-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10 text-center">
            <div
                class="inline-block mb-3 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-xs font-semibold tracking-wider uppercase reveal">
                FISHERIES INDONESIA
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-[#002A49] mb-4 tracking-tight reveal stagger-1">
                Fisheries Indonesia
            </h1>
            <p class="text-base md:text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed reveal stagger-2">
                Beyond the Hervest - Inside the Fisheries Industry.
            </p>
        </div>
    </div>

    <!-- About Content -->
    <div class="container mx-auto px-4 py-12">
        <!-- Vision Mission -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <div class="bg-white rounded-xl shadow-lg p-8 reveal-left card-hover">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6 icon-circle">
                    <i class="fas fa-eye text-blue-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Visi</h2>
                <p class="text-gray-600 leading-relaxed">
                    {{ get_setting('about_vision', 'Menjadi komunitas perikanan terbesar dan terpercaya di Indonesia yang menghubungkan seluruh pelaku usaha perikanan untuk menciptakan ekosistem perikanan yang berkelanjutan dan menguntungkan bagi semua pihak.') }}
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8 reveal-right card-hover">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6 icon-circle">
                    <i class="fas fa-bullseye text-green-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Misi</h2>
                <ul class="space-y-3 text-gray-600">
                    @php
                        $missions = explode(';', get_setting('about_mission', 'Membangun jaringan nelayan dan pembudidaya yang kuat di seluruh Indonesia;Menyediakan akses ke produk berkualitas dengan harga terjangkau;Meningkatkan kapasitas anggota melalui pelatihan dan edukasi;Mendorong inovasi teknologi dalam bidang perikanan'));
                    @endphp
                    @foreach($missions as $mission)
                        @if(trim($mission))
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span>{{ trim($mission) }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Values -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-8 reveal">Nilai-Nilai Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-lg p-6 text-center reveal stagger-1 card-hover">
                    <div
                        class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 icon-circle">
                        <i class="fas fa-handshake text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Integritas</h3>
                    <p class="text-gray-600 text-sm">Beroperasi dengan jujur dan transparan dalam setiap transaksi</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 text-center reveal stagger-2 card-hover">
                    <div
                        class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 icon-circle">
                        <i class="fas fa-users text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Kolaborasi</h3>
                    <p class="text-gray-600 text-sm">Bekerja sama untuk mencapai tujuan bersama yang lebih besar</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 text-center reveal stagger-3 card-hover">
                    <div
                        class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 icon-circle">
                        <i class="fas fa-lightbulb text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Inovasi</h3>
                    <p class="text-gray-600 text-sm">Terus mengembangkan solusi baru untuk industri perikanan</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 text-center reveal stagger-4 card-hover">
                    <div
                        class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 icon-circle">
                        <i class="fas fa-leaf text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Keberlanjutan</h3>
                    <p class="text-gray-600 text-sm">Berkomitmen pada praktik perikanan yang ramah lingkungan</p>
                </div>
            </div>
        </div>

        <!-- History -->
        {{-- <div class="bg-white rounded-xl shadow-lg p-8 mb-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Sejarah FISHERIES</h2>
            <div class="space-y-6">
                <div class="flex items-start space-x-4">
                    <div class="w-20 text-right font-bold text-blue-600">2026</div>
                    <div class="w-4 h-4 bg-blue-600 rounded-full mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1 pb-6 border-l-2 border-gray-200 pl-4 ml-2">
                        <h4 class="font-bold text-gray-800">Pendirian</h4>
                        <p class="text-gray-600">FISHERIES didirikan di Samarinda oleh sekelompok nelayan dan pembudidaya
                            yang ingin meningkatkan kesejahteraan.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-20 text-right font-bold text-blue-600">2026</div>
                    <div class="w-4 h-4 bg-blue-600 rounded-full mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1 pb-6 border-l-2 border-gray-200 pl-4 ml-2">
                        <h4 class="font-bold text-gray-800">Ekspansi Pertama</h4>
                        <p class="text-gray-600">Membuka 3 DPC pertama di Bontang, Balikpapan, dan Kutai Kartanegara.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-20 text-right font-bold text-blue-600">2026</div>
                    <div class="w-4 h-4 bg-blue-600 rounded-full mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1 pb-6 border-l-2 border-gray-200 pl-4 ml-2">
                        <h4 class="font-bold text-gray-800">Program E-Commerce</h4>
                        <p class="text-gray-600">Meluncurkan platform digital untuk memfasilitasi transaksi antar anggota.
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-20 text-right font-bold text-blue-600">2026</div>
                    <div class="w-4 h-4 bg-blue-600 rounded-full mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1 pb-6 border-l-2 border-gray-200 pl-4 ml-2">
                        <h4 class="font-bold text-gray-800">Jaringan Nasional</h4>
                        <p class="text-gray-600">Bergabung dengan jaringan perikanan nasional dan memperluas ke 10 DPC di
                            Kaltim.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-20 text-right font-bold text-blue-600">2026</div>
                    <div class="w-4 h-4 bg-blue-600 rounded-full mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1 pl-4 ml-2">
                        <h4 class="font-bold text-gray-800">2000+ Anggota</h4>
                        <p class="text-gray-600">Mencapai tonggak 2000 anggota aktif dan menjadi komunitas perikanan
                            terbesar di Kaltim.</p>
                    </div>
                </div>

            </div>
        </div> --}}

        <!-- Team -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-8 reveal">Tim Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-lg p-6 text-center reveal stagger-1 card-hover">
                    <div
                        class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 icon-circle">
                        <i class="fas fa-user text-blue-600 text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Ahmad Sudirman</h3>
                    <p class="text-blue-600 mb-2">Ketua Umum</p>
                    <p class="text-gray-600 text-sm">Nelayan dengan pengalaman 25 tahun di industri perikanan tangkap.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 text-center reveal stagger-2 card-hover">
                    <div
                        class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 icon-circle">
                        <i class="fas fa-user text-green-600 text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Siti Rahayu</h3>
                    <p class="text-green-600 mb-2">Sekretaris Jenderal</p>
                    <p class="text-gray-600 text-sm">Ahli budidaya perikanan dan pengembangan komunitas.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 text-center reveal stagger-3 card-hover">
                    <div
                        class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 icon-circle">
                        <i class="fas fa-user text-orange-600 text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Budi Santoso</h3>
                    <p class="text-orange-600 mb-2">Bendahara Umum</p>
                    <p class="text-gray-600 text-sm">Pengusaha perikanan dengan jaringan distribusi di seluruh Kaltim.</p>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div
            class="bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl p-8 text-center text-white reveal-scale cta-section overflow-hidden relative">
            <div class="cta-bubble bg-white w-32 h-32 -top-10 -left-10"></div>
            <div class="cta-bubble bg-white w-24 h-24 bottom-4 right-12" style="animation-delay: 2s"></div>
            <h2 class="text-2xl font-bold mb-4 relative z-10">Bergabung dengan Kami</h2>
            <p class="mb-6 opacity-90 relative z-10">Jadilah bagian dari komunitas perikanan terbesar di Kalimantan Timur
            </p>
            <a href="/register"
                class="inline-block px-8 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-gray-100 transition btn-animate relative z-10">
                Daftar Sekarang
            </a>
        </div>
    </div>
@endsection
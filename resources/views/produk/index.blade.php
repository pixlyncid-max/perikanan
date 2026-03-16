@extends('layouts.app')

@section('title', 'Produk - FISHERIES')


@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-blue-600 to-cyan-500 py-16">
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Produk FISHERIES</h1>
            <p class="text-xl opacity-90 mb-8">Solusi lengkap untuk kebutuhan perikanan Anda</p>
            
            <div class="max-w-2xl mx-auto">
                <form action="{{ route('produk.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk perikanan..." class="w-full px-6 py-4 rounded-full text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-300 shadow-xl">
                    <button type="submit" class="absolute right-2 top-2 bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition">
                        Cari
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@if(request('search'))
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Hasil Pencarian: "{{ request('search') }}"</h2>
        <a href="{{ route('produk.index') }}" class="text-blue-600 hover:underline">Lihat Semua Kategori</a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            @include('produk.partials.product-card', ['product' => $product])
        @empty
            <div class="col-span-full py-12 text-center text-gray-500">
                <i class="fas fa-search text-4xl mb-3 text-gray-300"></i>
                <p>Tidak ditemukan produk dengan kata kunci "{{ request('search') }}"</p>
            </div>
        @endforelse
    </div>
    
    @if($products->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $products->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endif

<!-- Programs Grid -->
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Pelet Pakan -->
        <a href="/produk/pelet-pakan" class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition group">

            <div class="h-48 bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                <i class="fas fa-cookie text-white text-6xl group-hover:scale-110 transition"></i>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Pelet Pakan Ikan</h3>
                <p class="text-gray-600 mb-4">Pakan pelet berkualitas tinggi dengan nutrisi lengkap untuk berbagai jenis ikan.</p>
                <div class="flex items-center text-orange-600 font-medium">
                    Lihat Produk <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                </div>
            </div>
        </a>

        <!-- Pakan Hidup -->
        <a href="/produk/pakan-hidup" class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition group">

            <div class="h-48 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                <i class="fas fa-bug text-white text-6xl group-hover:scale-110 transition"></i>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Pakan Hidup</h3>
                <p class="text-gray-600 mb-4">Artemia, cacing sutra, dan cacing tanah segar untuk pakan alami ikan.</p>
                <div class="flex items-center text-green-600 font-medium">
                    Lihat Produk <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                </div>
            </div>
        </a>

        <!-- Umpan Laut -->
        <a href="/produk/umpan-laut" class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition group">

            <div class="h-48 bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center">
                <i class="fas fa-fish text-white text-6xl group-hover:scale-110 transition"></i>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Umpan Ikan Laut</h3>
                <p class="text-gray-600 mb-4">Berbagai jenis umpan segar dan beku untuk memancing di laut.</p>
                <div class="flex items-center text-cyan-600 font-medium">
                    Lihat Produk <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                </div>
            </div>
        </a>

        <!-- Penyewaan Kapal -->
        <a href="/produk/penyewaan-kapal" class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition group">

            <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                <i class="fas fa-ship text-white text-6xl group-hover:scale-110 transition"></i>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Penyewaan Kapal</h3>
                <p class="text-gray-600 mb-4">Sewa kapal nelayan dengan berbagai ukuran untuk kebutuhan tangkap.</p>
                <div class="flex items-center text-blue-600 font-medium">
                    Lihat Produk <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                </div>
            </div>
        </a>

        <!-- Vitamin Air -->
        <a href="/produk/vitamin-air" class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition group">

            <div class="h-48 bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center">
                <i class="fas fa-flask text-white text-6xl group-hover:scale-110 transition"></i>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Vitamin Air</h3>
                <p class="text-gray-600 mb-4">Probiotik dan suplemen untuk menjaga kualitas air kolam.</p>
                <div class="flex items-center text-teal-600 font-medium">
                    Lihat Produk <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                </div>
            </div>
        </a>

        <!-- Bibit Ikan -->
        <a href="/produk/bibit-ikan" class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition group">

            <div class="h-48 bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                <i class="fas fa-seedling text-white text-6xl group-hover:scale-110 transition"></i>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Bibit Ikan</h3>
                <p class="text-gray-600 mb-4">Bibit ikan unggul dan berkualitas dari hatchery terpercaya.</p>
                <div class="flex items-center text-emerald-600 font-medium">
                    Lihat Produk <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Benefits -->
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-8">Keuntungan Berbelanja di FISHERIES</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-medal text-blue-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Kualitas Terjamin</h3>
                <p class="text-gray-600 text-sm">Semua produk telah melalui uji kualitas</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tags text-green-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Harga Anggota</h3>
                <p class="text-gray-600 text-sm">Diskon khusus untuk member FISHERIES</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-truck text-orange-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Pengiriman Cepat</h3>
                <p class="text-gray-600 text-sm">Jaringan distribusi di seluruh Kaltim</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-purple-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Support 24/7</h3>
                <p class="text-gray-600 text-sm">Tim ahli siap membantu Anda</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="bg-gradient-to-r from-blue-600 to-cyan-500 py-12">
    <div class="container mx-auto px-4 text-center text-white">
        <h2 class="text-2xl font-bold mb-4">Belum Menjadi Anggota?</h2>
        <p class="mb-6 opacity-90">Daftar sekarang dan dapatkan diskon 10% untuk pembelian pertama</p>
        <a href="/register" class="inline-block px-8 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-gray-100 transition">
            Daftar Sekarang
        </a>
    </div>
</div>
@endsection

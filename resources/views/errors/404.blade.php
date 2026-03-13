@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan - FISHERIES')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 py-12 px-4">
    <div class="text-center">
        <div class="mb-8">
            <i class="fas fa-fish text-blue-600 text-9xl opacity-20"></i>
        </div>
        <h1 class="text-9xl font-bold text-blue-600 mb-4">404</h1>
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Halaman Tidak Ditemukan</h2>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman telah dipindahkan atau dihapus.</p>
        
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="/" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition">
                <i class="fas fa-home mr-2"></i>Kembali ke Beranda
            </a>
            <a href="/kontak" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                <i class="fas fa-envelope mr-2"></i>Hubungi Kami
            </a>
        </div>

        <div class="mt-12">
            <p class="text-gray-500 mb-4">Atau coba cari di sini:</p>
            <div class="max-w-md mx-auto relative">
                <input type="text" placeholder="Cari produk, artikel, atau halaman..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Kemitraan - FISHERIES')

@section('content')
<div class="relative bg-gradient-to-r from-purple-600 to-purple-500 py-16">
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ get_setting('partnership_title', 'Program Kemitraan') }}</h1>
            <p class="text-xl opacity-90">{{ get_setting('partnership_description', 'Bergabunglah dengan jaringan mitra FISHERIES') }}</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-5xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-handshake text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Mitra Supplier</h3>
                <p class="text-gray-600 text-sm">Jadilah supplier produk perikanan untuk jaringan FISHERIES</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-store text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Mitra Distributor</h3>
                <p class="text-gray-600 text-sm">Distribusikan produk FISHERIES di wilayah Anda</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-graduation-cap text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Mitra Pelatihan</h3>
                <p class="text-gray-600 text-sm">Bekerjasama dalam program pelatihan perikanan</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Keuntungan Menjadi Mitra</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-check text-purple-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Akses Pasar Luas</h4>
                        <p class="text-gray-600 text-sm">Jangkauan ke seluruh anggota FISHERIES di Kalimantan Timur</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-check text-purple-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Harga Khusus</h4>
                        <p class="text-gray-600 text-sm">Harga produk khusus mitra dengan margin menguntungkan</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-check text-purple-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Pelatihan Gratis</h4>
                        <p class="text-gray-600 text-sm">Akses pelatihan budidaya dan pengolahan ikan</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-check text-purple-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Dukungan Pemasaran</h4>
                        <p class="text-gray-600 text-sm">Promosi produk melalui channel FISHERIES</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Formulir Pendaftaran Mitra</h2>
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan/Usaha</label>
                        <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Nama usaha Anda">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kemitraan</label>
                        <select class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option>Pilih jenis kemitraan</option>
                            <option>Mitra Supplier</option>
                            <option>Mitra Distributor</option>
                            <option>Mitra Pelatihan</option>
                            <option>Mitra Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penanggung Jawab</label>
                        <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Nama lengkap">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="tel" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="email@example.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Alamat lengkap usaha"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Usaha/Produk</label>
                    <textarea rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Jelaskan usaha atau produk yang Anda tawarkan"></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="terms" class="mr-2">
                    <label for="terms" class="text-sm text-gray-600">Saya menyetujui syarat dan ketentuan program kemitraan FISHERIES</label>
                </div>
                <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition font-semibold">
                    Kirim Pendaftaran
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

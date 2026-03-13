@extends('layouts.app')

@section('title', 'Umpan Ikan Laut - FISHERIES')

@section('content')
<div class="relative bg-gradient-to-r from-indigo-600 to-indigo-500 py-16">
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Umpan Ikan Laut</h1>
            <p class="text-xl opacity-90">Umpan berkualitas untuk hasil tangkapan maksimal</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Filter Umpan</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Umpan</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option>Semua Jenis</option>
                            <option>Umpan Alami</option>
                            <option>Umpan Buatan</option>
                            <option>Umpan Hidup</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Ikan</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option>Semua Target</option>
                            <option>Tuna</option>
                            <option>Cakalang</option>
                            <option>Tenggiri</option>
                            <option>Kakap</option>
                            <option>Layang</option>
                        </select>
                    </div>
                    <button class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl transition">
                    <div class="relative h-48 bg-gray-200">
                        <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                            <i class="fas fa-fish text-6xl"></i>
                        </div>
                        <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            Populer
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Umpan Tuna Premium</h3>
                        <p class="text-gray-600 text-sm mb-4">Formula khusus untuk menarik tuna dengan aroma amis kuat</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-indigo-600">Rp 125.000</span>
                                <span class="text-sm text-gray-500 block">per kg</span>
                            </div>
                            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl transition">
                    <div class="relative h-48 bg-gray-200">
                        <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                            <i class="fas fa-fish text-6xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Umpan Cakalang Jitu</h3>
                        <p class="text-gray-600 text-sm mb-4">Campuran ikan segar dengan perasa khusus untuk cakalang</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-indigo-600">Rp 95.000</span>
                                <span class="text-sm text-gray-500 block">per kg</span>
                            </div>
                            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl transition">
                    <div class="relative h-48 bg-gray-200">
                        <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                            <i class="fas fa-fish text-6xl"></i>
                        </div>
                        <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            Best Seller
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Umpan Tenggiri Super</h3>
                        <p class="text-gray-600 text-sm mb-4">Umpan dengan tekstur lembut untuk tenggiri</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-indigo-600">Rp 110.000</span>
                                <span class="text-sm text-gray-500 block">per kg</span>
                            </div>
                            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl transition">
                    <div class="relative h-48 bg-gray-200">
                        <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                            <i class="fas fa-fish text-6xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Umpan Kakap Merah</h3>
                        <p class="text-gray-600 text-sm mb-4">Formula khusus untuk kakap merah dan kakap putih</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-indigo-600">Rp 135.000</span>
                                <span class="text-sm text-gray-500 block">per kg</span>
                            </div>
                            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl transition">
                    <div class="relative h-48 bg-gray-200">
                        <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                            <i class="fas fa-fish text-6xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Umpan Layang Biru</h3>
                        <p class="text-gray-600 text-sm mb-4">Umpan ekonomis untuk tangkapan layang</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-indigo-600">Rp 75.000</span>
                                <span class="text-sm text-gray-500 block">per kg</span>
                            </div>
                            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl transition">
                    <div class="relative h-48 bg-gray-200">
                        <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                            <i class="fas fa-fish text-6xl"></i>
                        </div>
                        <div class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            New
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Umpan Universal</h3>
                        <p class="text-gray-600 text-sm mb-4">Cocok untuk berbagai jenis ikan laut</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-indigo-600">Rp 85.000</span>
                                <span class="text-sm text-gray-500 block">per kg</span>
                            </div>
                            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 bg-indigo-50 rounded-xl p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Tips Menggunakan Umpan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2"><i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Cara Penyimpanan</h4>
                        <p class="text-gray-600 text-sm">Simpan umpan di freezer dengan suhu -18°C. Hindari pencairan berulang kali untuk menjaga kualitas.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2"><i class="fas fa-clock text-blue-500 mr-2"></i>Waktu Terbaik</h4>
                        <p class="text-gray-600 text-sm">Gunakan umpan saat fajar (05:00-08:00) dan sore (16:00-18:00) untuk hasil optimal.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2"><i class="fas fa-water text-cyan-500 mr-2"></i>Kedalaman</h4>
                        <p class="text-gray-600 text-sm">Sesuaikan kedalaman pemasangan umpan dengan jenis ikan target. Tuna: 50-100m, Cakalang: 30-50m.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2"><i class="fas fa-syringe text-red-500 mr-2"></i>Penggunaan</h4>
                        <p class="text-gray-600 text-sm">Bentuk umpan sesuai ukuran mulut ikan target. Gunakan hook yang tajam dan kuat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

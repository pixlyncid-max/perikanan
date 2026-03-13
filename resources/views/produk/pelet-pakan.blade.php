@extends('layouts.app')

@section('title', 'Pelet Pakan Ikan - FISHERIES')

@section('content')
<div class="relative bg-gradient-to-r from-blue-600 to-blue-500 py-16">
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Pelet Pakan Ikan</h1>
            <p class="text-xl opacity-90">Pakan berkualitas untuk pertumbuhan optimal ikan</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Filter Produk</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Ikan</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Semua Jenis</option>
                            <option>Lele</option>
                            <option>Nila</option>
                            <option>Gurame</option>
                            <option>Patin</option>
                            <option>Mas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Pelet</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Semua Ukuran</option>
                            <option>Pelet 1-2mm</option>
                            <option>Pelet 3-4mm</option>
                            <option>Pelet 5-6mm</option>
                            <option>Pelet 7-8mm</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                        <input type="range" class="w-full" min="0" max="1000000" value="500000">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Rp 0</span>
                            <span>Rp 1.000.000</span>
                        </div>
                    </div>
                    <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
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
                            -15%
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Pelet Lele Premium 3mm</h3>
                        <p class="text-gray-600 text-sm mb-4">Pakan berkualitas tinggi untuk lele dengan kandungan protein 32%</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-blue-600">Rp 85.000</span>
                                <span class="text-sm text-gray-400 line-through block">Rp 100.000</span>
                            </div>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
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
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Pelet Nila Grower 4mm</h3>
                        <p class="text-gray-600 text-sm mb-4">Formula khusus untuk pertumbuhan cepat ikan nila</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-blue-600">Rp 92.000</span>
                            </div>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
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
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Pelet Gurame Jumbo 6mm</h3>
                        <p class="text-gray-600 text-sm mb-4">Pakan untuk gurame dewasa dengan nutrisi lengkap</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-blue-600">Rp 125.000</span>
                            </div>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
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
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Pelet Patin Starter 2mm</h3>
                        <p class="text-gray-600 text-sm mb-4">Pakan starter untuk benih patin 1-3 bulan</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-blue-600">Rp 78.000</span>
                            </div>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
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
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Pelet Mas Koi 3mm</h3>
                        <p class="text-gray-600 text-sm mb-4">Pakan untuk ikan mas koi dengan warna cerah</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-blue-600">Rp 95.000</span>
                            </div>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
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
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Pelet Lele Organik 4mm</h3>
                        <p class="text-gray-600 text-sm mb-4">Pakan organik tanpa bahan kimia untuk lele sehat</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-blue-600">Rp 110.000</span>
                            </div>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">Previous</button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">1</button>
                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">2</button>
                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">3</button>
                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">Next</button>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection

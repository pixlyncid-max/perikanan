@extends('layouts.app')

@section('title', 'Bibit Ikan - FISHERIES')

@section('content')
<div class="relative bg-gradient-to-r from-emerald-600 to-emerald-500 py-16">
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Bibit Ikan</h1>
            <p class="text-xl opacity-90">Bibit berkualitas unggul untuk budidaya perikanan</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Filter Bibit</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Ikan</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option>Semua Jenis</option>
                            <option>Lele</option>
                            <option>Nila</option>
                            <option>Gurame</option>
                            <option>Patin</option>
                            <option>Mas</option>
                            <option>Bawal</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option>Semua Ukuran</option>
                            <option>Burayak (1-3 cm)</option>
                            <option>Benih (3-5 cm)</option>
                            <option>Seed (5-8 cm)</option>
                            <option>Juvenile (8-12 cm)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Ekor</label>
                        <input type="range" class="w-full" min="0" max="5000" value="2500">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Rp 0</span>
                            <span>Rp 5.000</span>
                        </div>
                    </div>
                    <button class="w-full bg-emerald-600 text-white py-2 rounded-lg hover:bg-emerald-700 transition">
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
                        <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            Best Seller
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Bibit Lele Sangkuriang</h3>
                        <p class="text-gray-600 text-sm mb-4">Bibit lele unggulan dengan pertumbuhan cepat dan daya tahan tinggi</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-emerald-600">Rp 250</span>
                                <span class="text-sm text-gray-500 block">per ekor (3-5 cm)</span>
                            </div>
                            <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
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
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Bibit Nila Nirwana</h3>
                        <p class="text-gray-600 text-sm mb-4">Bibit nila dengan warna cerah dan pertumbuhan optimal</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-emerald-600">Rp 350</span>
                                <span class="text-sm text-gray-500 block">per ekor (3-5 cm)</span>
                            </div>
                            <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
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
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Bibit Gurame Soang</h3>
                        <p class="text-gray-600 text-sm mb-4">Bibit gurame soang dengan pertumbuhan cepat</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-emerald-600">Rp 1.500</span>
                                <span class="text-sm text-gray-500 block">per ekor (5-7 cm)</span>
                            </div>
                            <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
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
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Bibit Patin Jumbo</h3>
                        <p class="text-gray-600 text-sm mb-4">Bibit patin dengan potensi ukuran besar saat panen</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-emerald-600">Rp 400</span>
                                <span class="text-sm text-gray-500 block">per ekor (4-6 cm)</span>
                            </div>
                            <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
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
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Bibit Mas Koki</h3>
                        <p class="text-gray-600 text-sm mb-4">Bibit mas koki untuk hiasan kolam dan akuarium</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-emerald-600">Rp 500</span>
                                <span class="text-sm text-gray-500 block">per ekor (3-4 cm)</span>
                            </div>
                            <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
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
                        <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            -15%
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Bibit Bawal Air Tawar</h3>
                        <p class="text-gray-600 text-sm mb-4">Bibit bawal dengan daging tebal dan lezat</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-emerald-600">Rp 300</span>
                                <span class="text-sm text-gray-400 line-through block">Rp 350</span>
                            </div>
                            <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">Previous</button>
                    <button class="px-4 py-2 bg-emerald-600 text-white rounded-lg">1</button>
                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">2</button>
                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">3</button>
                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">Next</button>
                </nav>
            </div>

            <div class="mt-12 bg-emerald-50 rounded-xl p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Garansi dan Layanan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-shield-alt text-emerald-600 text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Garansi Hidup</h4>
                        <p class="text-gray-600 text-sm">Garansi 7 hari untuk bibit yang mati dalam pengiriman</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-truck text-emerald-600 text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Pengiriman Aman</h4>
                        <p class="text-gray-600 text-sm">Pengiriman dengan oksigen dan suhu terkontrol</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-headset text-emerald-600 text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Konsultasi Gratis</h4>
                        <p class="text-gray-600 text-sm">Dapatkan panduan budidaya dari ahli perikanan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

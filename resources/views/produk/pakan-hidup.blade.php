@extends('layouts.app')

@section('title', 'Pakan Hidup - FISHERIES')

@section('content')
<div class="relative bg-gradient-to-r from-green-600 to-green-500 py-16">
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Pakan Hidup</h1>
            <p class="text-xl opacity-90">Pakan alami untuk kesehatan dan pertumbuhan optimal</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Kategori Pakan</h3>
                <div class="space-y-2">
                    <a href="#artemia" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <i class="fas fa-egg text-green-600 mr-3"></i>
                        <span class="font-medium text-gray-700">Artemia</span>
                    </a>
                    <a href="#cacing-sutra" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <i class="fas fa-worm text-blue-600 mr-3"></i>
                        <span class="font-medium text-gray-700">Cacing Sutra</span>
                    </a>
                    <a href="#cacing-tanah" class="flex items-center p-3 bg-amber-50 rounded-lg hover:bg-amber-100 transition">
                        <i class="fas fa-leaf text-amber-600 mr-3"></i>
                        <span class="font-medium text-gray-700">Cacing Tanah</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 space-y-12">
            <section id="artemia" class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-egg text-green-600 text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Artemia</h2>
                </div>
                <p class="text-gray-600 mb-6">Artemia adalah pakan hidup yang kaya protein untuk larva ikan dan udang. Tersedia dalam bentuk telur dan nauplii segar.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                        <div class="h-40 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-egg text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Telur Artemia Premium</h3>
                        <p class="text-sm text-gray-600 mb-3">Kemasan 50gr, hatch rate >90%</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-green-600">Rp 150.000</span>
                            <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                        <div class="h-40 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-egg text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Nauplii Artemia Segar</h3>
                        <p class="text-sm text-gray-600 mb-3">Kemasan 100ml, siap pakai</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-green-600">Rp 75.000</span>
                            <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                        <div class="h-40 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-egg text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Artemia Decapsulated</h3>
                        <p class="text-sm text-gray-600 mb-3">Kemasan 100gr, tanpa cangkang</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-green-600">Rp 200.000</span>
                            <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <section id="cacing-sutra" class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-worm text-blue-600 text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Cacing Sutra (Tubifex)</h2>
                </div>
                <p class="text-gray-600 mb-6">Cacing sutra adalah pakan favorit untuk ikan hias dan larva. Kaya protein dan mudah dicerna.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                        <div class="h-40 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-worm text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Cacing Sutra Segar</h3>
                        <p class="text-sm text-gray-600 mb-3">Kemasan 100gr, segar dari peternak</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-blue-600">Rp 35.000</span>
                            <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                        <div class="h-40 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-worm text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Cacing Sutra Beku</h3>
                        <p class="text-sm text-gray-600 mb-3">Kemasan 50gr, praktis dan tahan lama</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-blue-600">Rp 45.000</span>
                            <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                        <div class="h-40 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-worm text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Cacing Sutra Kering</h3>
                        <p class="text-sm text-gray-600 mb-3">Kemasan 25gr, untuk cadangan</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-blue-600">Rp 55.000</span>
                            <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <section id="cacing-tanah" class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-leaf text-amber-600 text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Cacing Tanah (Lumbricus)</h2>
                </div>
                <p class="text-gray-600 mb-6">Cacing tanah adalah pakan alami yang kaya nutrisi untuk ikan predator dan burayak.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                        <div class="h-40 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-leaf text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Cacing Tanah Segar</h3>
                        <p class="text-sm text-gray-600 mb-3">Kemasan 200gr, langsung dari kebun</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-amber-600">Rp 25.000</span>
                            <button class="bg-amber-600 text-white px-3 py-1 rounded hover:bg-amber-700">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                        <div class="h-40 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-leaf text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Cacing Tanah Jumbo</h3>
                        <p class="text-sm text-gray-600 mb-3">Kemasan 500gr, ukuran besar</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-amber-600">Rp 55.000</span>
                            <button class="bg-amber-600 text-white px-3 py-1 rounded hover:bg-amber-700">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                        <div class="h-40 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-leaf text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Cacing Tanah Kering</h3>
                        <p class="text-sm text-gray-600 mb-3">Kemasan 100gr, tahan simpan</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-amber-600">Rp 40.000</span>
                            <button class="bg-amber-600 text-white px-3 py-1 rounded hover:bg-amber-700">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

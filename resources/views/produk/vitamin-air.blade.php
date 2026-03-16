@extends('layouts.app')

@section('title', 'Vitamin Air - FISHERIES')

@section('content')
<div class="relative bg-gradient-to-r from-teal-600 to-teal-500 py-16">
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Vitamin Air</h1>
            <p class="text-xl opacity-90">Suplemen kualitas tinggi untuk kesehatan ekosistem perairan</p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Produk</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option>Semua Jenis</option>
                            <option>Probiotik</option>
                            <option>Disinfektan</option>
                            <option>Pengolahan Limbah</option>
                            <option>Peningkat Oksigen</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Aplikasi</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option>Semua Aplikasi</option>
                            <option>Kolam</option>
                            <option>Tambak</option>
                            <option>Akuarium</option>
                            <option>Bioflok</option>
                        </select>
                    </div>
                    <button class="w-full bg-teal-600 text-white py-2 rounded-lg hover:bg-teal-700 transition">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl transition flex flex-col">
                        <div class="relative h-48 bg-gray-100 flex-shrink-0">
                            @php
                                $imgs = $product->images;
                                if(is_string($imgs)) $imgs = json_decode($imgs, true);
                                $firstImg = (!empty($imgs) && is_array($imgs)) ? $imgs[0] : null;
                            @endphp
                            @if($firstImg)
                                <img src="{{ asset('storage/'.$firstImg) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                                    <i class="fas fa-flask text-6xl"></i>
                                </div>
                            @endif

                            @if($product->featured)
                                <div class="absolute top-4 left-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                                    <i class="fas fa-star mr-1"></i> Unggulan
                                </div>
                            @endif

                            @if($product->sale_price > 0 && $product->price > 0)
                                <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                                    -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                                </div>
                            @endif
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="text-lg font-bold text-gray-800 mb-2 truncate" title="{{ $product->name }}">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow">{{ $product->short_description ?: Str::limit($product->description, 80) }}</p>
                            
                            <div class="flex flex-col mt-auto gap-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col">
                                        @if($product->sale_price > 0)
                                            <span class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                            <span class="text-xl font-bold text-teal-600">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-xl font-bold text-teal-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    <button class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition" {{ $product->stock < 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                                <div class="text-xs {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600 font-bold' }}">
                                    Stok: {{ $product->stock > 0 ? $product->stock : 'Habis' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500">
                        <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                        <p>Belum ada produk di kategori ini.</p>
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $products->links('pagination::tailwind') }}
            </div>
            @endif
            </div>

            <div class="mt-12 bg-teal-50 rounded-xl p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Panduan Penggunaan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2"><i class="fas fa-clipboard-list text-teal-500 mr-2"></i>Dosis Aplikasi</h4>
                        <ul class="space-y-2 text-gray-600 text-sm">
                            <li>• Probiotik: 1-2 ml per m³ air</li>
                            <li>• Disinfektan: 10-20 ml per m³ air</li>
                            <li>• Oksigenator: 0.5-1 kg per 100 m²</li>
                            <li>• Bioflok: 2-3 kg per 1000 liter</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2"><i class="fas fa-clock text-teal-500 mr-2"></i>Waktu Aplikasi</h4>
                        <ul class="space-y-2 text-gray-600 text-sm">
                            <li>• Pagi (06:00-08:00): Probiotik, Oksigenator</li>
                            <li>• Sore (16:00-18:00): Disinfektan, pH Stabilizer</li>
                            <li>• Malam (20:00-22:00): Amonia Remover</li>
                            <li>• Awal siklus: Bioflok Starter</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

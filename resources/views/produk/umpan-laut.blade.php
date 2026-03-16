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
                                    <i class="fas fa-fish text-6xl"></i>
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
                                            <span class="text-xl font-bold text-indigo-600">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition" {{ $product->stock < 1 ? 'disabled' : '' }}>
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

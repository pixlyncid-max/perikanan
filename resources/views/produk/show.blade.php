@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('produk.index') }}" class="hover:text-blue-600 transition">Produk</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="font-medium text-gray-800">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <!-- Image Gallery -->
                <div class="p-8 bg-gray-100 flex items-center justify-center">
                    @php
                        $images = $product->images;
                        if(is_string($images)) $images = json_decode($images, true);
                        $firstImg = (!empty($images) && is_array($images)) ? $images[0] : null;
                    @endphp
                    @if($firstImg)
                        <img src="{{ asset('storage/'.$firstImg) }}" alt="{{ $product->name }}" class="max-w-full h-auto rounded-lg shadow-md transition transform hover:scale-105 duration-300">
                    @else
                        <div class="text-gray-300">
                            <i class="fas fa-box text-9xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="p-8 md:p-12">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $product->category->name }}
                        </span>
                        @if($product->featured)
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                <i class="fas fa-star mr-1"></i> Unggulan
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{{ $product->name }}</h1>
                    
                    <div class="flex items-baseline gap-4 mb-6">
                        @if($product->sale_price > 0)
                            <span class="text-3xl font-bold text-blue-600">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                            <span class="text-lg text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                            </span>
                        @else
                            <span class="text-3xl font-bold text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    <div class="prose prose-blue max-w-none text-gray-600 mb-8">
                        {!! nl2br(e($product->description)) !!}
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-8 py-6 border-y border-gray-100">
                        <div>
                            <span class="block text-xs uppercase tracking-widest text-gray-400 mb-1 font-bold">Stok Tersedia</span>
                            <span class="text-lg font-bold {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->stock > 0 ? $product->stock : 'Habis' }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase tracking-widest text-gray-400 mb-1 font-bold">SKU Produk</span>
                            <span class="text-lg font-bold text-gray-800">{{ $product->sku ?: '-' }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button class="flex-grow bg-blue-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-blue-700 transition transform hover:-translate-y-1 shadow-lg flex items-center justify-center gap-3 {{ $product->stock < 1 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->stock < 1 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i>
                            Tambah ke Keranjang
                        </button>
                        <button class="bg-gray-100 text-gray-800 px-6 py-4 rounded-xl font-bold hover:bg-gray-200 transition">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 border-l-4 border-blue-600 pl-4">Produk Terkait</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    @include('produk.partials.product-card', ['product' => $related])
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

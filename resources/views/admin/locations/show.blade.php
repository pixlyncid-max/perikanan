@extends('admin.layouts.app')

@section('title', 'Detail Lokasi Drop Point')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Lokasi</h1>
            <p class="text-sm text-gray-500 mt-1">Informasi lengkap tentang Drop Point dan inventarisnya</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.locations.edit', $location) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                <i class="fas fa-edit mr-2"></i> Edit Lokasi
            </a>
            <a href="{{ route('admin.locations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Left Column: Location Info --}}
        <div class="xl:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                    <i class="fas fa-store-alt text-green-500"></i> Informasi Drop Point
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Nama Lokasi</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $location->nama }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Alamat Lengkap</p>
                        <p class="text-sm text-gray-700">{{ $location->alamat ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Koordinat MAPS (Lat, Lng)</p>
                        @if($location->latitude && $location->longitude)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $location->latitude }},{{ $location->longitude }}" target="_blank" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                                <i class="fas fa-map-marker-alt"></i> {{ $location->latitude }}, {{ $location->longitude }}
                            </a>
                        @else
                            <p class="text-sm text-gray-500 italic">Belum diatur</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                        <div class="bg-gray-50 p-3 rounded-lg text-center">
                            <p class="text-xs text-gray-500 mb-1">Jenis Produk</p>
                            <p class="text-xl font-bold text-gray-900">{{ $location->products->where('pivot.stok', '>', 0)->count() }}</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg text-center">
                            <p class="text-xs text-green-600 mb-1">Total Item Stok</p>
                            <p class="text-xl font-bold text-green-700">{{ $location->products->sum('pivot.stok') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Products List --}}
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-boxes text-blue-500"></i> Inventaris Produk di Lokasi Ini
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">Daftar semua produk yang dialokasikan ke cabang ini.</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok di Sini</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($location->products as $product)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $imgs = $product->images;
                                            if (is_string($imgs)) $imgs = json_decode($imgs, true);
                                            $firstImg = (!empty($imgs) && is_array($imgs)) ? $imgs[0] : null;
                                        @endphp
                                        @if($firstImg)
                                            <img src="{{ asset('storage/' . $firstImg) }}" class="h-10 w-10 rounded object-cover border border-gray-200">
                                        @else
                                            <div class="h-10 w-10 rounded bg-gray-100 flex items-center justify-center text-gray-400">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $product->name }}</div>
                                            @if($product->sku)<div class="text-xs text-gray-500">SKU: {{ $product->sku }}</div>@endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $product->category ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $product->category ? $product->category->name : 'Tanpa Kategori' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $product->pivot->stok > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->pivot->stok }} Item
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900">Edit Produk</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <i class="fas fa-box-open text-4xl text-gray-300 mb-3 block"></i>
                                    <p class="text-gray-500 text-sm font-medium">Belum ada produk yang dialokasikan ke lokasi ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

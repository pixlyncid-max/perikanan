@extends('admin.layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Produk</h1>
            <p class="text-sm text-gray-500 mt-1">ID #{{ $product->id }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    @php
        $imgs = $product->images;
        if (is_string($imgs)) $imgs = json_decode($imgs, true);
        $firstImg = (!empty($imgs) && is_array($imgs)) ? $imgs[0] : null;
    @endphp

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Left: Main Info --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Product Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                {{-- Hero Image --}}
                @if($firstImg)
                <div class="h-64 bg-gray-100 overflow-hidden">
                    <img src="{{ asset('storage/'.$firstImg) }}" alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                </div>
                @else
                <div class="h-40 bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
                    <div class="text-center text-green-300">
                        <i class="fas fa-image text-5xl mb-2"></i>
                        <p class="text-sm">Belum ada foto produk</p>
                    </div>
                </div>
                @endif

                <div class="p-6 space-y-4">
                    {{-- Name & Badges --}}
                    <div>
                        <div class="flex flex-wrap items-start gap-2 mb-2">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
                            @if($product->featured)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                    <i class="fas fa-star mr-1"></i> Unggulan
                                </span>
                            @endif
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1 {{ $product->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        @if($product->sku)
                            <p class="text-xs text-gray-400 font-mono">SKU: {{ $product->sku }}</p>
                        @endif
                    </div>

                    {{-- Short Description --}}
                    @if($product->short_description)
                    <p class="text-gray-600 text-sm border-l-4 border-green-400 pl-3 bg-green-50 py-2 rounded-r-lg">
                        {{ $product->short_description }}
                    </p>
                    @endif

                    {{-- Description --}}
                    @if($product->description)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Deskripsi Lengkap</h3>
                        <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Variations Card --}}
            @if($product->variations->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                    <i class="fas fa-layer-group text-green-500"></i> Variasi Produk
                    <span class="ml-auto text-xs font-normal text-gray-400">{{ $product->variations->count() }} variasi aktif</span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 rounded-lg">
                            <tr>
                                <th class="px-4 py-3 font-bold">Tipe</th>
                                <th class="px-4 py-3 font-bold">Label / Nama</th>
                                <th class="px-4 py-3 font-bold text-right">Penyesuaian Harga</th>
                                <th class="px-4 py-3 font-bold text-center">Stok Variasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($product->variations as $variation)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-[10px] font-bold uppercase">{{ $variation->type }}</span>
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $variation->name }}</td>
                                <td class="px-4 py-3 text-right">
                                    @if($variation->price_adjustment > 0)
                                        <span class="text-green-600 font-medium">+Rp {{ number_format($variation->price_adjustment, 0, ',', '.') }}</span>
                                    @elseif($variation->price_adjustment < 0)
                                        <span class="text-red-500 font-medium">-Rp {{ number_format(abs($variation->price_adjustment), 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $variation->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $variation->stock }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- SEO Info --}}
            @if($product->meta_title || $product->meta_description)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                    <i class="fas fa-search text-green-500"></i> Informasi SEO
                </h3>
                @if($product->meta_title)
                <div class="mb-3">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Meta Title</p>
                    <p class="text-sm text-gray-800">{{ $product->meta_title }}</p>
                </div>
                @endif
                @if($product->meta_description)
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Meta Description</p>
                    <p class="text-sm text-gray-600">{{ $product->meta_description }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        {{-- Right: Stats & Details --}}
        <div class="space-y-6">

            {{-- Pricing Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                    <i class="fas fa-tags text-green-500"></i> Harga
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Harga Normal</span>
                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    @if($product->sale_price > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Harga Diskon</span>
                        <span class="text-base font-semibold text-red-600">
                            Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                            <span class="text-xs font-normal ml-1 bg-red-100 text-red-600 px-1.5 py-0.5 rounded-full">
                                -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                            </span>
                        </span>
                    </div>
                    @endif
                    @if($product->member_price > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Harga Member</span>
                        <span class="text-base font-semibold text-green-600">Rp {{ number_format($product->member_price, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Stock Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                    <i class="fas fa-warehouse text-green-500"></i> Stok
                </h3>
                <div class="text-center">
                    <div class="text-4xl font-bold {{ $product->stock > 10 ? 'text-green-600' : ($product->stock > 0 ? 'text-yellow-500' : 'text-red-500') }}">
                        {{ $product->stock }}
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        @if($product->stock > 10) Stok Aman
                        @elseif($product->stock > 0) Stok Menipis
                        @else Habis
                        @endif
                    </p>
                    @if($product->stock <= 5 && $product->stock > 0)
                    <div class="mt-2 text-xs text-yellow-600 bg-yellow-50 rounded-lg px-3 py-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Segera restok
                    </div>
                    @elseif($product->stock == 0)
                    <div class="mt-2 text-xs text-red-600 bg-red-50 rounded-lg px-3 py-2">
                        <i class="fas fa-times-circle mr-1"></i> Produk habis
                    </div>
                    @endif
                </div>
            </div>

            {{-- Category & Details --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                    <i class="fas fa-folder text-green-500"></i> Detail Produk
                </h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Kategori</dt>
                        <dd class="font-medium text-gray-800">
                            @if($product->category)
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">{{ $product->category->name }}</span>
                            @else
                                <span class="text-gray-400 text-xs">Tidak ada</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Slug URL</dt>
                        <dd class="font-mono text-xs text-gray-600 max-w-[140px] truncate">{{ $product->slug }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Dibuat</dt>
                        <dd class="text-gray-600">{{ $product->created_at->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Diperbarui</dt>
                        <dd class="text-gray-600">{{ $product->updated_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 space-y-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                    <i class="fas fa-edit mr-2"></i> Edit Produk
                </a>
                <form action="{{ route('admin.products.toggle-active', $product) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg transition text-sm font-medium
                        {{ $product->is_active ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                        <i class="fas {{ $product->is_active ? 'fa-pause-circle' : 'fa-play-circle' }} mr-2"></i>
                        {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Produk
                    </button>
                </form>
                <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg transition text-sm font-medium
                        {{ $product->featured ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        <i class="fas fa-star mr-2"></i>
                        {{ $product->featured ? 'Hapus dari Unggulan' : 'Jadikan Unggulan' }}
                    </button>
                </form>
                <button onclick="confirmDelete('delete-form-show')"
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-sm font-medium border border-red-200">
                    <i class="fas fa-trash mr-2"></i> Hapus Produk
                </button>
                <form id="delete-form-show" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

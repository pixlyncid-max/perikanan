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
                <form action="{{ route('produk.vitamin-air') }}" method="GET" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>

                    @if($category->subcategories->count() > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis/Aplikasi</label>
                        <select name="subcategory" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="">Semua</option>
                            @foreach($category->subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ request('subcategory') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Maksimum (Rp)</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Rp" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div class="flex flex-col gap-2">
                        <button type="submit" class="w-full bg-teal-600 text-white py-2 rounded-lg hover:bg-teal-700 transition">
                            Terapkan Filter
                        </button>
                        @if(request()->anyFilled(['search', 'subcategory', 'max_price']))
                            <a href="{{ route('produk.vitamin-air') }}" class="w-full bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition text-center text-sm font-medium">
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                    @include('produk.partials.product-card', ['product' => $product, 'colorClass' => 'teal'])
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

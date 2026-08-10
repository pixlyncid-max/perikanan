@extends('layouts.app')

@section('title', 'Vitamin Air & Probiotik Ikan Terlengkap | Fisheries.id')
@section('meta_description', 'Beli vitamin air kolam dan probiotik tambak ikan di Fisheries.id. Menjaga kualitas air kolam dan meningkatkan imun ikan pembudidaya.')
@section('meta_keywords', 'vitamin air kolam, probiotik ikan, suplemen ikan, obat kolam ikan, perikanan indonesia')

@section('content')


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
        </div>
    </div>
</div>
@endsection

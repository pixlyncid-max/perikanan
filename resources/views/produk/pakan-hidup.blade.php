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
                <h3 class="text-lg font-bold text-gray-800 mb-4">Filter Pakan</h3>
                
                <form action="{{ route('produk.pakan-hidup') }}" method="GET" class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pakan</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    @if($category->subcategories->count() > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subkategori</label>
                        <select name="subcategory" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Subkategori</option>
                            @foreach($category->subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ request('subcategory') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="flex flex-col gap-2">
                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                            Terapkan Filter
                        </button>
                        @if(request()->anyFilled(['search', 'subcategory']))
                            <a href="{{ route('produk.pakan-hidup') }}" class="w-full bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition text-center text-sm font-medium">
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </form>

                <h4 class="text-sm font-bold text-gray-800 mb-3">Pencarian Cepat</h4>
                <div class="space-y-2">
                    <a href="{{ route('produk.pakan-hidup', ['search' => 'Artemia']) }}" class="flex items-center p-3 {{ request('search') == 'Artemia' ? 'bg-green-100' : 'bg-green-50' }} rounded-lg hover:bg-green-100 transition">
                        <i class="fas fa-egg text-green-600 mr-3"></i>
                        <span class="font-medium text-gray-700">Artemia</span>
                    </a>
                    <a href="{{ route('produk.pakan-hidup', ['search' => 'Cacing Sutra']) }}" class="flex items-center p-3 {{ request('search') == 'Cacing Sutra' ? 'bg-blue-100' : 'bg-blue-50' }} rounded-lg hover:bg-blue-100 transition">
                        <i class="fas fa-worm text-blue-600 mr-3"></i>
                        <span class="font-medium text-gray-700">Cacing Sutra</span>
                    </a>
                    <a href="{{ route('produk.pakan-hidup', ['search' => 'Cacing Tanah']) }}" class="flex items-center p-3 {{ request('search') == 'Cacing Tanah' ? 'bg-amber-100' : 'bg-amber-50' }} rounded-lg hover:bg-amber-100 transition">
                        <i class="fas fa-leaf text-amber-600 mr-3"></i>
                        <span class="font-medium text-gray-700">Cacing Tanah</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                    @include('produk.partials.product-card', ['product' => $product, 'colorClass' => 'green'])
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
@endsection

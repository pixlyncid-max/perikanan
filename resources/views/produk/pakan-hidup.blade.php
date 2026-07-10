@extends('layouts.app')

@section('title', 'Pakan Hidup - FISHERIES')

@section('content')


<div class="container mx-auto px-4 py-12">

    {{-- Mobile Filter Toggle Button --}}
    <div class="lg:hidden mb-4">
        <button id="filter-toggle"
            class="w-full flex items-center justify-between px-4 py-3 bg-white rounded-xl shadow-sm border border-gray-100 font-semibold text-gray-700 hover:bg-gray-50 transition">
            <span><i class="fas fa-sliders-h mr-2 text-green-500"></i> Filter Produk</span>
            <i id="filter-chevron" class="fas fa-chevron-down text-gray-400 transition-transform duration-200"></i>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1">
            <div id="filter-panel" class="hidden lg:block bg-white rounded-xl shadow-lg p-6 lg:sticky lg:top-24">
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
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
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
@push('scripts')
<script>
    const filterToggle = document.getElementById('filter-toggle');
    const filterPanel = document.getElementById('filter-panel');
    const filterChevron = document.getElementById('filter-chevron');

    @if(request()->anyFilled(['search', 'subcategory']))
        filterPanel.classList.remove('hidden');
        filterChevron.style.transform = 'rotate(180deg)';
    @endif

    filterToggle?.addEventListener('click', () => {
        filterPanel.classList.toggle('hidden');
        const isOpen = !filterPanel.classList.contains('hidden');
        filterChevron.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
    });
</script>
@endpush
@endsection

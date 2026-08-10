@extends('layouts.app')

@section('title', 'Jual Umpan Laut Segar & Tiruan Terlengkap | Fisheries.id')
@section('meta_description', 'Beli umpan pancing laut berkualitas di Fisheries.id. Menyediakan umpan hidup, umpan segar, cumi, udang, serta essens pancing laut terbaik.')
@section('meta_keywords', 'umpan laut, umpan pancing laut, umpan cumi, umpan udang, essens mancing laut, fisheries.id')

@section('content')


<div class="container mx-auto px-4 py-12">

    {{-- Mobile Filter Toggle Button --}}
    <div class="lg:hidden mb-4">
        <button id="filter-toggle"
            class="w-full flex items-center justify-between px-4 py-3 bg-white rounded-xl shadow-sm border border-gray-100 font-semibold text-gray-700 hover:bg-gray-50 transition">
            <span><i class="fas fa-sliders-h mr-2 text-indigo-500"></i> Filter Produk</span>
            <i id="filter-chevron" class="fas fa-chevron-down text-gray-400 transition-transform duration-200"></i>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Filter Sidebar --}}
        <div class="lg:col-span-1">
            <div id="filter-panel" class="hidden lg:block bg-white rounded-xl shadow-lg p-6 lg:sticky lg:top-24">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Filter Umpan</h3>
                <form action="{{ route('produk.umpan-laut') }}" method="GET" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Umpan</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    @if($category->subcategories->count() > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Umpan</label>
                        <select name="subcategory" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Semua Jenis</option>
                            @foreach($category->subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ request('subcategory') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Minimum (Rp)</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Maksimum (Rp)</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="1.000.000" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="flex flex-col gap-2">
                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                            Terapkan Filter
                        </button>
                        @if(request()->anyFilled(['search', 'subcategory', 'min_price', 'max_price']))
                            <a href="{{ route('produk.umpan-laut') }}" class="w-full bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition text-center text-sm font-medium">
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                @forelse($products as $product)
                    @include('produk.partials.product-card', ['product' => $product, 'colorClass' => 'indigo'])
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

@push('scripts')
<script>
    const filterToggle = document.getElementById('filter-toggle');
    const filterPanel = document.getElementById('filter-panel');
    const filterChevron = document.getElementById('filter-chevron');

    // If filters are active, auto-open on mobile
    @if(request()->anyFilled(['search', 'subcategory', 'min_price', 'max_price']))
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

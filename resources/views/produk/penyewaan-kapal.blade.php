@extends('layouts.app')

@section('title', 'Penyewaan Kapal - FISHERIES')

@section('content')


<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Filter Kapal</h3>
            <form action="{{ route('produk.penyewaan-kapal') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Nama Kapal atau Lokasi</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: Balikpapan, Kapal Motor..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Maksimum/Hari</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Rp" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-grow bg-cyan-600 text-white py-2 rounded-lg hover:bg-cyan-700 transition font-medium">
                        Cari
                    </button>
                    @if(request()->anyFilled(['search', 'max_price']))
                        <a href="{{ route('produk.penyewaan-kapal') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-center text-sm font-medium flex items-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div id="all-locations" class="location-content">
        <div class="flex items-center mb-6">
            <i class="fas fa-anchor text-cyan-600 text-2xl mr-3"></i>
            <h2 class="text-2xl font-bold text-gray-800">Semua Kapal</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($products as $product)
                @include('produk.partials.product-card', ['product' => $product, 'colorClass' => 'cyan'])
            @empty
                <div class="col-span-full py-12 text-center text-gray-500">
                    <i class="fas fa-anchor text-4xl mb-3 text-gray-300"></i>
                    <p>Belum ada kapal di kategori ini.</p>
                </div>
            @endforelse
        </div>

        @if($products->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $products->links('pagination::tailwind') }}
        </div>
        @endif
    </div>

    <div class="mt-12 bg-cyan-50 rounded-xl p-8">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Syarat dan Ketentuan Penyewaan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-semibold text-gray-700 mb-2">Persyaratan</h4>
                <ul class="space-y-2 text-gray-600">
                    <li><i class="fas fa-check text-green-500 mr-2"></i>KTP/SIM yang masih berlaku</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>SIM perahu/Kapal (jika membawa sendiri)</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Deposit Rp 1.000.000</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Mengisi formulir penyewaan</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-gray-700 mb-2">Ketentuan</h4>
                <ul class="space-y-2 text-gray-600">
                    <li><i class="fas fa-info-circle text-blue-500 mr-2"></i>Durasi minimal sewa 1 hari</li>
                    <li><i class="fas fa-info-circle text-blue-500 mr-2"></i>Harga sudah termasuk awak kapal</li>
                    <li><i class="fas fa-info-circle text-blue-500 mr-2"></i>Bahan bakar ditanggung penyewa</li>
                    <li><i class="fas fa-info-circle text-blue-500 mr-2"></i>Pembatalan H-1 dikenakan biaya 50%</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function showLocation(location) {
    // Hide all location contents
    document.querySelectorAll('.location-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Show selected location
    document.getElementById('location-' + location).classList.remove('hidden');
    
    // Update button styles
    document.querySelectorAll('.location-btn').forEach(btn => {
        btn.classList.remove('bg-cyan-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    event.target.classList.remove('bg-gray-200', 'text-gray-700');
    event.target.classList.add('bg-cyan-600', 'text-white');
}
</script>
@endsection

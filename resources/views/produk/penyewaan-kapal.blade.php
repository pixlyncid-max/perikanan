@extends('layouts.app')

@section('title', 'Penyewaan Kapal - FISHERIES')

@section('content')
<div class="relative bg-gradient-to-r from-cyan-600 to-cyan-500 py-16">
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Penyewaan Kapal</h1>
            <p class="text-xl opacity-90">Kapal nelayan berkualitas untuk kebutuhan perikanan Anda</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Pilih Lokasi Penyewaan</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                <button onclick="showLocation('samarinda')" class="location-btn bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700 transition text-sm font-medium" data-location="samarinda">
                    Samarinda
                </button>
                <button onclick="showLocation('bontang')" class="location-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm font-medium" data-location="bontang">
                    Bontang
                </button>
                <button onclick="showLocation('balikpapan')" class="location-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm font-medium" data-location="balikpapan">
                    Balikpapan
                </button>
                <button onclick="showLocation('sangatta')" class="location-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm font-medium" data-location="sangatta">
                    Sangatta
                </button>
                <button onclick="showLocation('berau')" class="location-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm font-medium" data-location="berau">
                    Berau
                </button>
                <button onclick="showLocation('tenggarong')" class="location-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm font-medium" data-location="tenggarong">
                    Tenggarong
                </button>
                <button onclick="showLocation('tanah-grogot')" class="location-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm font-medium" data-location="tanah-grogot">
                    Tanah Grogot
                </button>
            </div>
        </div>
    </div>

    <div id="all-locations" class="location-content">
        <div class="flex items-center mb-6">
            <i class="fas fa-anchor text-cyan-600 text-2xl mr-3"></i>
            <h2 class="text-2xl font-bold text-gray-800">Semua Kapal</h2>
        </div>
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
                                <i class="fas fa-ship text-6xl"></i>
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
                                    <span class="text-sm text-gray-500">Harga/hari</span>
                                    @if($product->sale_price > 0)
                                        <span class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span class="text-xl font-bold text-cyan-600">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-xl font-bold text-cyan-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <button class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700 transition" {{ $product->stock < 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-cart-plus"></i> Sewa
                                </button>
                            </div>
                            <div class="text-xs {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600 font-bold' }}">
                                Tersedia: {{ $product->stock > 0 ? $product->stock . ' Kapal' : 'Penuh' }}
                            </div>
                        </div>
                    </div>
                </div>
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

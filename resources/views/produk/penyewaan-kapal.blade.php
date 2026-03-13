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

    <div id="location-samarinda" class="location-content">
        <div class="flex items-center mb-6">
            <i class="fas fa-anchor text-cyan-600 text-2xl mr-3"></i>
            <h2 class="text-2xl font-bold text-gray-800">Kapal di Samarinda</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-ship text-6xl text-gray-400"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">KM. Fajar Bahari</h3>
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <p><i class="fas fa-ruler-combined mr-2"></i>15 GT, 12 meter</p>
                        <p><i class="fas fa-users mr-2"></i>Kapasitas 8 awak</p>
                        <p><i class="fas fa-fish mr-2"></i>Alat tangkap: Purse Seine</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm text-gray-500">Harga/hari</span>
                            <p class="text-xl font-bold text-cyan-600">Rp 2.500.000</p>
                        </div>
                        <button class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700">
                            Sewa
                        </button>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-ship text-6xl text-gray-400"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">KM. Samudra Jaya</h3>
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <p><i class="fas fa-ruler-combined mr-2"></i>20 GT, 15 meter</p>
                        <p><i class="fas fa-users mr-2"></i>Kapasitas 10 awak</p>
                        <p><i class="fas fa-fish mr-2"></i>Alat tangkap: Gill Net</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm text-gray-500">Harga/hari</span>
                            <p class="text-xl font-bold text-cyan-600">Rp 3.200.000</p>
                        </div>
                        <button class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700">
                            Sewa
                        </button>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-ship text-6xl text-gray-400"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">KM. Lautan Emas</h3>
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <p><i class="fas fa-ruler-combined mr-2"></i>10 GT, 9 meter</p>
                        <p><i class="fas fa-users mr-2"></i>Kapasitas 5 awak</p>
                        <p><i class="fas fa-fish mr-2"></i>Alat tangkap: Long Line</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm text-gray-500">Harga/hari</span>
                            <p class="text-xl font-bold text-cyan-600">Rp 1.800.000</p>
                        </div>
                        <button class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700">
                            Sewa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="location-bontang" class="location-content hidden">
        <div class="flex items-center mb-6">
            <i class="fas fa-anchor text-cyan-600 text-2xl mr-3"></i>
            <h2 class="text-2xl font-bold text-gray-800">Kapal di Bontang</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-ship text-6xl text-gray-400"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">KM. Bontang Makmur</h3>
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <p><i class="fas fa-ruler-combined mr-2"></i>25 GT, 18 meter</p>
                        <p><i class="fas fa-users mr-2"></i>Kapasitas 12 awak</p>
                        <p><i class="fas fa-fish mr-2"></i>Alat tangkap: Trawl</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm text-gray-500">Harga/hari</span>
                            <p class="text-xl font-bold text-cyan-600">Rp 4.000.000</p>
                        </div>
                        <button class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700">
                            Sewa
                        </button>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-ship text-6xl text-gray-400"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">KM. Laut Bontang</h3>
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <p><i class="fas fa-ruler-combined mr-2"></i>18 GT, 14 meter</p>
                        <p><i class="fas fa-users mr-2"></i>Kapasitas 8 awak</p>
                        <p><i class="fas fa-fish mr-2"></i>Alat tangkap: Purse Seine</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm text-gray-500">Harga/hari</span>
                            <p class="text-xl font-bold text-cyan-600">Rp 2.800.000</p>
                        </div>
                        <button class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700">
                            Sewa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="location-balikpapan" class="location-content hidden">
        <div class="flex items-center mb-6">
            <i class="fas fa-anchor text-cyan-600 text-2xl mr-3"></i>
            <h2 class="text-2xl font-bold text-gray-800">Kapal di Balikpapan</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-ship text-6xl text-gray-400"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">KM. Balikpapan Jaya</h3>
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <p><i class="fas fa-ruler-combined mr-2"></i>30 GT, 20 meter</p>
                        <p><i class="fas fa-users mr-2"></i>Kapasitas 15 awak</p>
                        <p><i class="fas fa-fish mr-2"></i>Alat tangkap: Trawl</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm text-gray-500">Harga/hari</span>
                            <p class="text-xl font-bold text-cyan-600">Rp 5.500.000</p>
                        </div>
                        <button class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700">
                            Sewa
                        </button>
                    </div>
                </div>
            </div>
        </div>
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

@extends('admin.layouts.app')

@section('title', 'Detail Statistik - ' . $fisheryStatistic->regency_city . ' ' . $fisheryStatistic->year)

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Statistik Pembudidaya</h1>
            <p class="text-sm text-gray-500 mt-1">
                Data lengkap untuk <span class="font-semibold text-blue-700">{{ $fisheryStatistic->regency_city }}</span>
                &mdash; Tahun <span class="font-semibold">{{ $fisheryStatistic->year }}</span>
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.fishery-statistics.edit', $fisheryStatistic) }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-edit mr-2"></i> Edit Data
            </a>
            <a href="{{ route('admin.fishery-statistics.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Hero Card -->
    <div class="bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold">
                    {{ substr($fisheryStatistic->regency_city, 0, 2) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold">{{ $fisheryStatistic->regency_city }}</h2>
                    <p class="text-blue-100">Kalimantan Timur &bull; Tahun {{ $fisheryStatistic->year }}</p>
                </div>
            </div>
            <div class="flex gap-6 text-center">
                <div>
                    <p class="text-3xl font-bold">{{ $fisheryStatistic->fish_farmer_count !== null ? number_format($fisheryStatistic->fish_farmer_count) : '-' }}</p>
                    <p class="text-blue-100 text-sm">Pembudidaya</p>
                </div>
                <div class="border-l border-white border-opacity-30 pl-6">
                    <p class="text-3xl font-bold">{{ $fisheryStatistic->area_size !== null ? number_format($fisheryStatistic->area_size, 1) : '-' }}</p>
                    <p class="text-blue-100 text-sm">Hektar Lahan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Kartu Pembudidaya -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-green-50 border-b border-green-200 px-6 py-4 flex items-center gap-3">
                <div class="bg-green-100 rounded-full p-2">
                    <i class="fas fa-users text-green-600"></i>
                </div>
                <h3 class="text-base font-semibold text-green-900">Data Pembudidaya</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-fish w-5 text-center text-green-500"></i>
                        <span class="text-sm font-medium">Pembudidaya Ikan</span>
                    </div>
                    <div class="text-right">
                        @if($fisheryStatistic->fish_farmer_count !== null)
                            <span class="text-lg font-bold text-gray-900">{{ number_format($fisheryStatistic->fish_farmer_count) }}</span>
                            <span class="text-sm text-gray-500 ml-1">orang</span>
                        @else
                            <span class="text-gray-400 italic text-sm">-</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-water w-5 text-center text-green-500"></i>
                        <span class="text-sm font-medium">Pembudidaya Udang</span>
                    </div>
                    <div class="text-right">
                        @if($fisheryStatistic->shrimp_farmer_count !== null)
                            <span class="text-lg font-bold text-gray-900">{{ number_format($fisheryStatistic->shrimp_farmer_count) }}</span>
                            <span class="text-sm text-gray-500 ml-1">orang</span>
                        @else
                            <span class="text-gray-400 italic text-sm">-</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-ship w-5 text-center text-green-500"></i>
                        <span class="text-sm font-medium">Nelayan Aktif</span>
                    </div>
                    <div class="text-right">
                        @if($fisheryStatistic->fisherman_count !== null)
                            <span class="text-lg font-bold text-gray-900">{{ number_format($fisheryStatistic->fisherman_count) }}</span>
                            <span class="text-sm text-gray-500 ml-1">orang</span>
                        @else
                            <span class="text-gray-400 italic text-sm">-</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-expand-arrows-alt w-5 text-center text-green-500"></i>
                        <span class="text-sm font-medium">Luas Lahan Budidaya</span>
                    </div>
                    <div class="text-right">
                        @if($fisheryStatistic->area_size !== null)
                            <span class="text-lg font-bold text-gray-900">{{ number_format($fisheryStatistic->area_size, 2) }}</span>
                            <span class="text-sm text-gray-500 ml-1">hektar</span>
                        @else
                            <span class="text-gray-400 italic text-sm">Belum diisi</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Produksi -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-yellow-50 border-b border-yellow-200 px-6 py-4 flex items-center gap-3">
                <div class="bg-yellow-100 rounded-full p-2">
                    <i class="fas fa-chart-line text-yellow-600"></i>
                </div>
                <h3 class="text-base font-semibold text-yellow-900">Data Produksi</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-weight w-5 text-center text-yellow-500"></i>
                        <span class="text-sm font-medium">Volume Produksi</span>
                    </div>
                    <div class="text-right">
                        @if($fisheryStatistic->production_volume !== null)
                            <span class="text-lg font-bold text-gray-900">{{ number_format($fisheryStatistic->production_volume, 2) }}</span>
                            <span class="text-sm text-gray-500 ml-1">ton</span>
                        @else
                            <span class="text-gray-400 italic text-sm">Belum diisi</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-money-bill-wave w-5 text-center text-yellow-500"></i>
                        <span class="text-sm font-medium">Nilai Produksi</span>
                    </div>
                    <div class="text-right">
                        @if($fisheryStatistic->production_value !== null)
                            <span class="text-lg font-bold text-gray-900">Rp {{ number_format($fisheryStatistic->production_value, 0, ',', '.') }}</span>
                        @else
                            <span class="text-gray-400 italic text-sm">Belum diisi</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Komoditas Utama -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="bg-purple-50 border-b border-purple-200 px-6 py-4 flex items-center gap-3">
            <div class="bg-purple-100 rounded-full p-2">
                <i class="fas fa-fish text-purple-600"></i>
            </div>
            <h3 class="text-base font-semibold text-purple-900">Komoditas Utama</h3>
        </div>
        <div class="p-6">
            @if($fisheryStatistic->main_commodities)
                @php $commodities = array_map('trim', explode(',', $fisheryStatistic->main_commodities)); @endphp
                <div class="flex flex-wrap gap-2">
                    @foreach($commodities as $commodity)
                        @if($commodity)
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                            <i class="fas fa-fish text-xs"></i>
                            {{ $commodity }}
                        </span>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 italic text-sm">Data komoditas belum diisi</p>
            @endif
        </div>
    </div>

    <!-- Pembudidaya Lainnya (Berdasarkan Komoditas) -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="bg-indigo-50 border-b border-indigo-200 px-6 py-4 flex items-center gap-3">
            <div class="bg-indigo-100 rounded-full p-2">
                <i class="fas fa-chart-pie text-indigo-600"></i>
            </div>
            <h3 class="text-base font-semibold text-indigo-900">Pembudidaya Lainnya (Berdasarkan Komoditas)</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Kepiting -->
                <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Kepiting</p>
                    <p class="text-xl font-bold text-gray-900">{{ $fisheryStatistic->crab_farmer_count !== null ? number_format($fisheryStatistic->crab_farmer_count) : '-' }}</p>
                </div>
                <!-- Rumput Laut -->
                <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Rumput Laut</p>
                    <p class="text-xl font-bold text-gray-900">{{ $fisheryStatistic->seaweed_farmer_count !== null ? number_format($fisheryStatistic->seaweed_farmer_count) : '-' }}</p>
                </div>
                <!-- Kerang -->
                <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Kerang</p>
                    <p class="text-xl font-bold text-gray-900">{{ $fisheryStatistic->clam_farmer_count !== null ? number_format($fisheryStatistic->clam_farmer_count) : '-' }}</p>
                </div>
                <!-- Lobster -->
                <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Lobster</p>
                    <p class="text-xl font-bold text-gray-900">{{ $fisheryStatistic->lobster_farmer_count !== null ? number_format($fisheryStatistic->lobster_farmer_count) : '-' }}</p>
                </div>
                <!-- Abalon -->
                <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Abalon</p>
                    <p class="text-xl font-bold text-gray-900">{{ $fisheryStatistic->abalone_farmer_count !== null ? number_format($fisheryStatistic->abalone_farmer_count) : '-' }}</p>
                </div>
                <!-- Teripang -->
                <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Teripang</p>
                    <p class="text-xl font-bold text-gray-900">{{ $fisheryStatistic->sea_cucumber_farmer_count !== null ? number_format($fisheryStatistic->sea_cucumber_farmer_count) : '-' }}</p>
                </div>
                <!-- Lainnya -->
                <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Lainnya</p>
                    <p class="text-xl font-bold text-gray-900">{{ $fisheryStatistic->other_farmer_count !== null ? number_format($fisheryStatistic->other_farmer_count) : '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Metadata -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg px-6 py-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-xs text-gray-500">
            <span><i class="fas fa-hashtag mr-1"></i> ID: #{{ $fisheryStatistic->id }}</span>
            <span class="hidden sm:inline text-gray-300">|</span>
            <span><i class="fas fa-clock mr-1"></i> Dibuat: {{ $fisheryStatistic->created_at->format('d M Y, H:i') }}</span>
            <span class="hidden sm:inline text-gray-300">|</span>
            <span><i class="fas fa-edit mr-1"></i> Diperbarui: {{ $fisheryStatistic->updated_at->format('d M Y, H:i') }}</span>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <h3 class="text-base font-semibold text-red-800 mb-2">
            <i class="fas fa-exclamation-triangle mr-2"></i> Zona Berbahaya
        </h3>
        <p class="text-sm text-red-600 mb-4">Hapus data statistik ini secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
        <form action="{{ route('admin.fishery-statistics.destroy', $fisheryStatistic) }}" method="POST"
              onsubmit="return confirm('Yakin ingin menghapus data {{ $fisheryStatistic->regency_city }} tahun {{ $fisheryStatistic->year }}? Data tidak dapat dipulihkan.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                <i class="fas fa-trash mr-2"></i> Hapus Data Ini
            </button>
        </form>
    </div>

</div>
@endsection

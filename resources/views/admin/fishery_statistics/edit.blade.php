@extends('admin.layouts.app')

@section('title', 'Edit Data Statistik Pembudidaya')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Data Statistik</h1>
            <p class="text-sm text-gray-500 mt-1">
                <span class="font-medium text-blue-700">{{ $fisheryStatistic->regency_city }}</span> &mdash; Tahun {{ $fisheryStatistic->year }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.fishery-statistics.show', $fisheryStatistic) }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-eye mr-2"></i> Lihat Detail
            </a>
            <a href="{{ route('admin.fishery-statistics.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.fishery-statistics.update', $fisheryStatistic) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Section: Identitas Wilayah -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-blue-50 border-b border-blue-200 px-6 py-4 flex items-center gap-3">
                <div class="bg-blue-100 rounded-full p-2">
                    <i class="fas fa-map-marker-alt text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-blue-900">Identitas Wilayah & Periode</h2>
                    <p class="text-xs text-blue-600">Tentukan kabupaten/kota dan tahun data</p>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Kabupaten/Kota -->
                    <div>
                        <label for="regency_city" class="block text-sm font-medium text-gray-700 mb-2">
                            Kabupaten/Kota <span class="text-red-500">*</span>
                        </label>
                        <select name="regency_city" id="regency_city" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('regency_city') border-red-500 @enderror">
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                            @foreach($regencies as $regency)
                                <option value="{{ $regency }}" {{ old('regency_city', $fisheryStatistic->regency_city) == $regency ? 'selected' : '' }}>{{ $regency }}</option>
                            @endforeach
                        </select>
                        @error('regency_city')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                            Tahun Data <span class="text-red-500">*</span>
                        </label>
                        <select name="year" id="year" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('year') border-red-500 @enderror">
                            <option value="">-- Pilih Tahun --</option>
                            @for($y = date('Y') + 1; $y >= 2015; $y--)
                                <option value="{{ $y }}" {{ old('year', $fisheryStatistic->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        @error('year')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        <!-- Section: Data Pembudidaya -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-green-50 border-b border-green-200 px-6 py-4 flex items-center gap-3">
                <div class="bg-green-100 rounded-full p-2">
                    <i class="fas fa-users text-green-600"></i>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-green-900">Data Pembudidaya</h2>
                    <p class="text-xs text-green-600">Jumlah pembudidaya dan lahan yang digunakan</p>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <!-- Jumlah Pembudidaya Ikan -->
                    <div>
                        <label for="fish_farmer_count" class="block text-sm font-medium text-gray-700 mb-2">
                            Pembudidaya Ikan <span class="text-gray-400 text-xs font-normal">(orang)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-fish text-gray-400 text-sm"></i>
                            </div>
                            <input type="number" name="fish_farmer_count" id="fish_farmer_count"
                                   value="{{ old('fish_farmer_count', $fisheryStatistic->fish_farmer_count) }}" min="0"
                                   placeholder="Contoh: 1250"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('fish_farmer_count') border-red-500 @enderror">
                        </div>
                        @error('fish_farmer_count')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pembudidaya Udang -->
                    <div>
                        <label for="shrimp_farmer_count" class="block text-sm font-medium text-gray-700 mb-2">
                            Pembudidaya Udang <span class="text-gray-400 text-xs font-normal">(orang)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-water text-gray-400 text-sm"></i>
                            </div>
                            <input type="number" name="shrimp_farmer_count" id="shrimp_farmer_count"
                                   value="{{ old('shrimp_farmer_count', $fisheryStatistic->shrimp_farmer_count) }}" min="0"
                                   placeholder="Contoh: 850"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shrimp_farmer_count') border-red-500 @enderror">
                        </div>
                        @error('shrimp_farmer_count')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nelayan -->
                    <div>
                        <label for="fisherman_count" class="block text-sm font-medium text-gray-700 mb-2">
                            Nelayan Aktif <span class="text-gray-400 text-xs font-normal">(orang)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-ship text-gray-400 text-sm"></i>
                            </div>
                            <input type="number" name="fisherman_count" id="fisherman_count"
                                   value="{{ old('fisherman_count', $fisheryStatistic->fisherman_count) }}" min="0"
                                   placeholder="Contoh: 2100"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('fisherman_count') border-red-500 @enderror">
                        </div>
                        @error('fisherman_count')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Luas Lahan -->
                    <div>
                        <label for="area_size" class="block text-sm font-medium text-gray-700 mb-2">
                            Luas Lahan Budidaya <span class="text-gray-400 text-xs font-normal">(hektar)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-expand-arrows-alt text-gray-400 text-sm"></i>
                            </div>
                            <input type="number" name="area_size" id="area_size"
                                   value="{{ old('area_size', $fisheryStatistic->area_size) }}" min="0" step="0.01"
                                   placeholder="Contoh: 523.50"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('area_size') border-red-500 @enderror">
                        </div>
                        @error('area_size')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Masukkan luas lahan dalam satuan hektar</p>
                    </div>

                </div>
            </div>
        </div>

        <!-- Section: Data Produksi -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-yellow-50 border-b border-yellow-200 px-6 py-4 flex items-center gap-3">
                <div class="bg-yellow-100 rounded-full p-2">
                    <i class="fas fa-chart-line text-yellow-600"></i>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-yellow-900">Data Produksi</h2>
                    <p class="text-xs text-yellow-600">Volume produksi, nilai ekonomi, dan komoditas yang dihasilkan</p>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Volume Produksi -->
                    <div>
                        <label for="production_volume" class="block text-sm font-medium text-gray-700 mb-2">
                            Volume Produksi <span class="text-gray-400 text-xs font-normal">(ton)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-weight text-gray-400 text-sm"></i>
                            </div>
                            <input type="number" name="production_volume" id="production_volume"
                                   value="{{ old('production_volume', $fisheryStatistic->production_volume) }}" min="0" step="0.01"
                                   placeholder="Contoh: 3500.75"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('production_volume') border-red-500 @enderror">
                        </div>
                        @error('production_volume')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Masukkan total berat produksi dalam ton</p>
                    </div>

                    <!-- Nilai Produksi -->
                    <div>
                        <label for="production_value" class="block text-sm font-medium text-gray-700 mb-2">
                            Nilai Produksi <span class="text-gray-400 text-xs font-normal">(Rupiah)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 text-sm font-semibold">Rp</span>
                            </div>
                            <input type="number" name="production_value" id="production_value"
                                   value="{{ old('production_value', $fisheryStatistic->production_value) }}" min="0" step="0.01"
                                   placeholder="Contoh: 52500000000"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('production_value') border-red-500 @enderror">
                        </div>
                        @error('production_value')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Masukkan nilai total produksi dalam Rupiah</p>
                    </div>

                </div>

                <!-- Komoditas Utama -->
                <div>
                    <label for="main_commodities" class="block text-sm font-medium text-gray-700 mb-2">
                        Komoditas Utama
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-fish text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" name="main_commodities" id="main_commodities"
                               value="{{ old('main_commodities', $fisheryStatistic->main_commodities) }}" maxlength="255"
                               placeholder="Contoh: Ikan Nila, Udang Vaname, Bandeng, Lele"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('main_commodities') border-red-500 @enderror">
                    </div>
                    @error('main_commodities')
                        <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-400">Pisahkan beberapa komoditas dengan tanda koma</p>

                    <!-- Komoditas Quick Tags -->
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="text-xs text-gray-500 font-medium">Pilih cepat:</span>
                        @foreach(['Ikan Nila', 'Udang Vaname', 'Bandeng', 'Lele', 'Patin', 'Gurame', 'Mas', 'Kakap', 'Kerapu', 'Bawal', 'Ikan Mas', 'Tilapia'] as $commodity)
                        <button type="button" onclick="addCommodity('{{ $commodity }}')"
                                class="px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs hover:bg-blue-100 transition">
                            + {{ $commodity }}
                        </button>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <!-- Section: Pembudidaya Lainnya (Berdasarkan Komoditas) -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-purple-50 border-b border-purple-200 px-6 py-4 flex items-center gap-3">
                <div class="bg-purple-100 rounded-full p-2">
                    <i class="fas fa-chart-pie text-purple-600"></i>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-purple-900">Pembudidaya Lainnya (Berdasarkan Komoditas)</h2>
                    <p class="text-xs text-purple-600">Jumlah pembudidaya komoditas khusus (opsional)</p>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

                    <!-- Kepiting -->
                    <div>
                        <label for="crab_farmer_count" class="block text-sm font-medium text-gray-700 mb-2">Kepiting</label>
                        <input type="number" name="crab_farmer_count" id="crab_farmer_count" value="{{ old('crab_farmer_count', $fisheryStatistic->crab_farmer_count) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('crab_farmer_count') border-red-500 @enderror">
                    </div>
                    
                    <!-- Rumput Laut -->
                    <div>
                        <label for="seaweed_farmer_count" class="block text-sm font-medium text-gray-700 mb-2">Rumput Laut</label>
                        <input type="number" name="seaweed_farmer_count" id="seaweed_farmer_count" value="{{ old('seaweed_farmer_count', $fisheryStatistic->seaweed_farmer_count) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('seaweed_farmer_count') border-red-500 @enderror">
                    </div>
                    
                    <!-- Kerang -->
                    <div>
                        <label for="clam_farmer_count" class="block text-sm font-medium text-gray-700 mb-2">Kerang</label>
                        <input type="number" name="clam_farmer_count" id="clam_farmer_count" value="{{ old('clam_farmer_count', $fisheryStatistic->clam_farmer_count) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('clam_farmer_count') border-red-500 @enderror">
                    </div>
                    
                    <!-- Lobster -->
                    <div>
                        <label for="lobster_farmer_count" class="block text-sm font-medium text-gray-700 mb-2">Lobster</label>
                        <input type="number" name="lobster_farmer_count" id="lobster_farmer_count" value="{{ old('lobster_farmer_count', $fisheryStatistic->lobster_farmer_count) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lobster_farmer_count') border-red-500 @enderror">
                    </div>
                    
                    <!-- Abalon -->
                    <div>
                        <label for="abalone_farmer_count" class="block text-sm font-medium text-gray-700 mb-2">Abalon</label>
                        <input type="number" name="abalone_farmer_count" id="abalone_farmer_count" value="{{ old('abalone_farmer_count', $fisheryStatistic->abalone_farmer_count) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('abalone_farmer_count') border-red-500 @enderror">
                    </div>
                    
                    <!-- Teripang -->
                    <div>
                        <label for="sea_cucumber_farmer_count" class="block text-sm font-medium text-gray-700 mb-2">Teripang</label>
                        <input type="number" name="sea_cucumber_farmer_count" id="sea_cucumber_farmer_count" value="{{ old('sea_cucumber_farmer_count', $fisheryStatistic->sea_cucumber_farmer_count) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sea_cucumber_farmer_count') border-red-500 @enderror">
                    </div>
                    
                    <!-- Lainnya -->
                    <div>
                        <label for="other_farmer_count" class="block text-sm font-medium text-gray-700 mb-2">Lainnya</label>
                        <input type="number" name="other_farmer_count" id="other_farmer_count" value="{{ old('other_farmer_count', $fisheryStatistic->other_farmer_count) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('other_farmer_count') border-red-500 @enderror">
                    </div>

                </div>
            </div>
        </div>

        <!-- Timestamps info -->
        <div class="bg-gray-50 rounded-lg border border-gray-200 px-6 py-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-xs text-gray-500">
                <span><i class="fas fa-clock mr-1"></i> Dibuat: {{ $fisheryStatistic->created_at->format('d M Y H:i') }}</span>
                <span class="hidden sm:inline text-gray-300">|</span>
                <span><i class="fas fa-edit mr-1"></i> Terakhir diubah: {{ $fisheryStatistic->updated_at->format('d M Y H:i') }}</span>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('admin.fishery-statistics.index') }}" class="inline-flex items-center px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-times mr-2"></i> Batal
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <i class="fas fa-save mr-2"></i> Perbarui Data Statistik
            </button>
        </div>

    </form>
</div>

<script>
function addCommodity(commodity) {
    const input = document.getElementById('main_commodities');
    const current = input.value.trim();
    if (current === '') {
        input.value = commodity;
    } else {
        const parts = current.split(',').map(s => s.trim());
        if (!parts.includes(commodity)) {
            input.value = current + ', ' + commodity;
        }
    }
    input.focus();
}
</script>
@endsection

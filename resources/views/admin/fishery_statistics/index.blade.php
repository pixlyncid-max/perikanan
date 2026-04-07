@extends('admin.layouts.app')

@section('title', 'Statistik Pembudidaya Kaltim')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Statistik Pembudidaya Kaltim</h1>
            <p class="text-sm text-gray-500 mt-1">Data statistik pembudidaya ikan per kabupaten/kota di Kalimantan Timur</p>
        </div>
        <a href="{{ route('admin.fishery-statistics.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Tambah Data Statistik
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500 text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.fishery-statistics.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kabupaten/kota, tahun, atau komoditas..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="w-full md:w-56">
                <select name="regency_city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Kabupaten/Kota</option>
                    @foreach($regencies as $regency)
                        <option value="{{ $regency }}" {{ request('regency_city') == $regency ? 'selected' : '' }}>{{ $regency }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-36">
                <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Tahun</option>
                    @for($y = date('Y'); $y >= 2015; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
                <a href="{{ route('admin.fishery-statistics.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    @if($statistics->total() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center gap-4">
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-fish text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-blue-600 uppercase tracking-wide">Total Data</p>
                <p class="text-2xl font-bold text-blue-800">{{ $statistics->total() }}</p>
            </div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-4">
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-green-600 uppercase tracking-wide">Total Pembudidaya Ikan</p>
                <p class="text-2xl font-bold text-green-800">{{ number_format($statistics->sum('fish_farmer_count')) }}</p>
            </div>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex items-center gap-4">
            <div class="bg-yellow-100 rounded-full p-3">
                <i class="fas fa-weight text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-yellow-600 uppercase tracking-wide">Volume Produksi (ton)</p>
                <p class="text-2xl font-bold text-yellow-800">{{ number_format($statistics->sum('production_volume'), 1) }}</p>
            </div>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 flex items-center gap-4">
            <div class="bg-purple-100 rounded-full p-3">
                <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-purple-600 uppercase tracking-wide">Total Nilai Produksi</p>
                <p class="text-lg font-bold text-purple-800">Rp {{ number_format($statistics->sum('production_value') / 1000000, 1) }}M</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kabupaten/Kota</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jml. Pembudidaya</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Vol. Produksi (ton)</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Produksi (Rp)</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Luas Lahan (ha)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Komoditas Utama</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($statistics as $stat)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="bg-blue-100 text-blue-700 rounded-full w-8 h-8 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ substr($stat->regency_city, 0, 2) }}
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $stat->regency_city }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-sm font-medium">{{ $stat->year }}</span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                            {{ $stat->fish_farmer_count !== null ? number_format($stat->fish_farmer_count) : '-' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                            {{ $stat->production_volume !== null ? number_format($stat->production_volume, 2) : '-' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                            {{ $stat->production_value !== null ? 'Rp ' . number_format($stat->production_value, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                            {{ $stat->area_size !== null ? number_format($stat->area_size, 2) : '-' }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700 max-w-xs">
                            {{ $stat->main_commodities ?? '-' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.fishery-statistics.show', $stat) }}"
                                   class="text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 p-2 rounded transition" title="Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.fishery-statistics.edit', $stat) }}"
                                   class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 p-2 rounded transition" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <button onclick="confirmDelete('delete-form-{{ $stat->id }}')"
                                        class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 p-2 rounded transition" title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                                <form id="delete-form-{{ $stat->id }}"
                                      action="{{ route('admin.fishery-statistics.destroy', $stat) }}"
                                      method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-3">
                                <i class="fas fa-chart-bar text-5xl text-gray-300"></i>
                                <p class="text-lg font-medium">Belum ada data statistik</p>
                                <p class="text-sm">Mulai tambahkan data statistik pembudidaya Kaltim</p>
                                <a href="{{ route('admin.fishery-statistics.create') }}" class="mt-2 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                                    <i class="fas fa-plus mr-2"></i> Tambah Data Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($statistics->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $statistics->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function confirmDelete(formId) {
    if (confirm('Apakah Anda yakin ingin menghapus data statistik ini?')) {
        document.getElementById(formId).submit();
    }
}
</script>
@endsection

@extends('admin.layouts.app')

@section('title', 'Manajemen Lokasi Drop Point')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Lokasi Drop Point</h1>
        <a href="{{ route('admin.locations.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Tambah Lokasi
        </a>
    </div>
    
    <!-- Locations Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat Lengkap</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Koordinat MAPS</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Stok Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($locations as $location)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $location->nama }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ Str::limit($location->alamat, 60) ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($location->latitude && $location->longitude)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $location->latitude }},{{ $location->longitude }}" target="_blank" class="text-blue-500 hover:underline">
                                    {{ $location->latitude }}, {{ $location->longitude }}
                                </a>
                            @else
                                <span class="text-red-400 italic">Belum diset</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @php
                                $totalItems = $location->products->sum('pivot.stok');
                                $totalProducts = $location->products->where('pivot.stok', '>', 0)->count();
                            @endphp
                            <div class="flex flex-col items-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $totalItems > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $totalItems }} Item
                                </span>
                                <span class="text-xs text-gray-400 mt-1">({{ $totalProducts }} Jenis Produk)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.locations.show', $location) }}" 
                                   class="text-blue-600 hover:text-blue-900 bg-blue-100 p-2 rounded" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.locations.edit', $location) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 p-2 rounded" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete('delete-form-{{ $location->id }}')" 
                                        class="text-red-600 hover:text-red-900 bg-red-100 p-2 rounded" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $location->id }}" 
                                      action="{{ route('admin.locations.destroy', $location) }}" 
                                      method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-store-alt text-4xl text-gray-300 mb-2"></i>
                                <p>Belum ada data lokasi drop point</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

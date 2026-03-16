@extends('admin.layouts.app')

@section('title', 'Manajemen Organisasi')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Organisasi</h1>
            <p class="text-sm text-gray-500 mt-1">Total: {{ $organizations->total() }} organisasi</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.organizations.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium text-sm shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Organisasi
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.organizations.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama, kota, ketua..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                </div>
            </div>
            <div class="w-full md:w-44">
                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                    <option value="">Semua Tipe</option>
                    <option value="dpp" {{ request('type') == 'dpp' ? 'selected' : '' }}>DPP (Pusat)</option>
                    <option value="dpc" {{ request('type') == 'dpc' ? 'selected' : '' }}>DPC (Cabang)</option>
                </select>
            </div>
            <div class="w-full md:w-36">
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition text-sm font-medium">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
                @if(request()->anyFilled(['search', 'type', 'status']))
                    <a href="{{ route('admin.organizations.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition text-sm font-medium border border-gray-300">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Organizations List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="py-3 px-4 font-semibold text-sm text-gray-600">No. Urut</th>
                        <th class="py-3 px-4 font-semibold text-sm text-gray-600">Logo</th>
                        <th class="py-3 px-4 font-semibold text-sm text-gray-600">Nama / Tipe</th>
                        <th class="py-3 px-4 font-semibold text-sm text-gray-600">Lokasi</th>
                        <th class="py-3 px-4 font-semibold text-sm text-gray-600">Pengurus Inti</th>
                        <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-center">Anggota</th>
                        <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-center">Status</th>
                        <th class="py-3 px-4 font-semibold text-sm text-gray-600 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($organizations as $org)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-4 text-sm font-medium text-gray-400">#{{ $org->display_order }}</td>
                            <td class="py-3 px-4">
                                @if($org->logo)
                                    <img src="{{ Storage::url($org->logo) }}" alt="Logo" class="w-12 h-12 rounded object-cover border border-gray-200">
                                @else
                                    <div class="w-12 h-12 rounded bg-gray-100 flex items-center justify-center border border-gray-200">
                                        <i class="fas fa-building text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-medium text-gray-900">{{ $org->name }}</div>
                                <div class="text-xs text-gray-500 mt-1 uppercase font-bold text-blue-600">
                                    {{ $org->type }}
                                </div>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                <div><i class="fas fa-map-marker-alt text-red-500 mr-1"></i> {{ $org->city }}</div>
                                @if($org->phone)
                                    <div class="mt-1"><i class="fas fa-phone text-green-500 mr-1"></i> {{ $org->phone }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                @if($org->chairman)<div><span class="font-medium">K:</span> {{ $org->chairman }}</div>@endif
                                @if($org->secretary)<div><span class="font-medium">S:</span> {{ $org->secretary }}</div>@endif
                                @if($org->treasurer)<div><span class="font-medium">B:</span> {{ $org->treasurer }}</div>@endif
                                @if(!$org->chairman && !$org->secretary && !$org->treasurer)
                                    <span class="text-gray-400 italic">Belum diset</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    {{ $org->member_count }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($org->is_active)
                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <a href="{{ route('admin.organizations.edit', $org) }}" class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition" title="Edit">
                                    <i class="fas fa-pen text-sm"></i>
                                </a>
                                
                                <form action="{{ route('admin.organizations.destroy', $org) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus organisasi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-sitemap text-4xl mb-3 text-gray-300"></i>
                                    <p class="text-base text-gray-500 font-medium">Tidak ada data organisasi ditemukan.</p>
                                    @if(request()->anyFilled(['search', 'type', 'status']))
                                        <p class="text-sm mt-1">Coba sesuaikan filter pencarian Anda.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($organizations->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $organizations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

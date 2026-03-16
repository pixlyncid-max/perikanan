@extends('admin.layouts.app')

@section('title', 'Edit Organisasi')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Organisasi</h1>
            <p class="text-sm text-gray-500 mt-1">Mengubah data struktur organisasi DPP atau DPC</p>
        </div>
        <a href="{{ route('admin.organizations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-200 transition font-medium text-sm shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.organizations.update', $organization) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 space-y-8">
            {{-- Informasi Dasar --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Organisasi <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $organization->name) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Tingkat <span class="text-red-500">*</span></label>
                        <select name="type" id="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('type') border-red-500 @enderror">
                            <option value="dpc" {{ old('type', $organization->type) == 'dpc' ? 'selected' : '' }}>DPC (Dewan Pimpinan Cabang)</option>
                            <option value="dpp" {{ old('type', $organization->type) == 'dpp' ? 'selected' : '' }}>DPP (Dewan Pimpinan Pusat)</option>
                        </select>
                        @error('type')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Kontak & Lokasi --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Kontak & Lokasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Kota / Kabupaten <span class="text-red-500">*</span></label>
                        <input type="text" name="city" id="city" value="{{ old('city', $organization->city) }}" required placeholder="Contoh: Kabupaten Bandung"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('city') border-red-500 @enderror">
                        @error('city')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon Sekretariat</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $organization->phone) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                        @error('phone')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Resmi</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $organization->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="address" id="address" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', $organization->address) }}</textarea>
                        @error('address')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Kepengurusan Inti --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Pengurus Inti</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="chairman" class="block text-sm font-medium text-gray-700 mb-1">Nama Ketua</label>
                        <input type="text" name="chairman" id="chairman" value="{{ old('chairman', $organization->chairman) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="secretary" class="block text-sm font-medium text-gray-700 mb-1">Nama Sekretaris</label>
                        <input type="text" name="secretary" id="secretary" value="{{ old('secretary', $organization->secretary) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="treasurer" class="block text-sm font-medium text-gray-700 mb-1">Nama Bendahara</label>
                        <input type="text" name="treasurer" id="treasurer" value="{{ old('treasurer', $organization->treasurer) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            {{-- Detail Lainnya --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Detail Lainnya</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="established_year" class="block text-sm font-medium text-gray-700 mb-1">Tahun Berdiri</label>
                        <input type="number" name="established_year" id="established_year" value="{{ old('established_year', $organization->established_year) }}" placeholder="Contoh: 2018"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="member_count" class="block text-sm font-medium text-gray-700 mb-1">Estimasi Jumlah Anggota Biasa</label>
                        <input type="number" name="member_count" id="member_count" value="{{ old('member_count', $organization->member_count) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi & Visi Misi</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('description', $organization->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Logo & Status --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Logo & Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo Regional</label>
                        
                        @if($organization->logo)
                            <div class="mb-3 flex items-center justify-between p-3 border border-gray-200 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ Storage::url($organization->logo) }}" alt="Current Logo" class="w-12 h-12 object-contain bg-white rounded border">
                                    <span class="text-sm font-medium text-gray-600">Logo Tersimpan</span>
                                </div>
                            </div>
                        @endif

                        <input type="file" name="logo" id="logo" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-lg">
                        <p class="mt-1 text-xs text-gray-500">Maks. 2MB. Format JPG, PNG, WEBP. Biarkan kosong jika tidak ingin mengubah logo.</p>
                        @error('logo')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Nomor Urut Tampil</label>
                        <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $organization->display_order) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Angka lebih kecil akan tampil lebih atas.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Status</label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $organization->is_active) ? 'checked' : '' }}>
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            <span class="ms-3 text-sm font-medium text-gray-700">Publikasikan Organisasi Ini</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
            <a href="{{ route('admin.organizations.index') }}" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                Perbarui Data
            </button>
        </div>
    </form>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Produk</h1>
            <p class="text-sm text-gray-500 mt-1">Isi detail produk baru di bawah ini</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Left Column: Main Info --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- Basic Info Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-info-circle text-green-500"></i> Informasi Dasar
                    </h2>

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Bibit Lele Sangkuriang"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SKU & Category --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-1.5">Kode SKU</label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                                   placeholder="Contoh: BIT-001"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm @error('sku') border-red-400 @enderror">
                            @error('sku')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                            <select name="category_id" id="category_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm @error('category_id') border-red-400 @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Short Description --}}
                    <div>
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Singkat</label>
                        <input type="text" name="short_description" id="short_description" value="{{ old('short_description') }}"
                               placeholder="Ringkasan singkat produk (maks 500 karakter)"
                               maxlength="500"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Lengkap</label>
                        <textarea name="description" id="description" rows="5"
                                  placeholder="Tulis deskripsi produk secara lengkap..."
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Pricing Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-tags text-green-500"></i> Harga & Stok
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Harga Normal (Rp) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0"
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm @error('price') border-red-400 @enderror">
                            </div>
                            @error('price')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1.5">Harga Diskon (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                                <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price') }}" min="0"
                                       placeholder="0 = tidak ada diskon"
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="member_price" class="block text-sm font-medium text-gray-700 mb-1.5">Harga Member (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                                <input type="number" name="member_price" id="member_price" value="{{ old('member_price') }}" min="0"
                                       placeholder="0 = sama dengan harga normal"
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" required min="0"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm @error('stock') border-red-400 @enderror">
                            @error('stock')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Variations Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-layer-group text-green-500"></i> Variasi Produk
                        </h2>
                        <button type="button" onclick="addVariationRow()" class="text-xs font-bold text-green-600 hover:text-green-700 flex items-center gap-1 bg-green-50 px-3 py-1.5 rounded-lg transition">
                            <i class="fas fa-plus"></i> Tambah Variasi
                        </button>
                    </div>

                    <div id="variations-wrapper" class="space-y-4">
                        <div class="hidden text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200" id="no-variations-msg">
                            <i class="fas fa-layer-group text-gray-300 text-3xl mb-2"></i>
                            <p class="text-sm text-gray-400">Belum ada variasi. Klik tombol di atas untuk menambah.</p>
                        </div>
                        
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-search text-green-500"></i> SEO (Opsional)
                    </h2>
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1.5">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" maxlength="255"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                    </div>
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1.5">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="2" maxlength="500"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm resize-none">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Right Column: Image, Status --}}
            <div class="space-y-6">

                {{-- Image Upload --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                        <i class="fas fa-image text-green-500"></i> Foto Produk
                    </h2>
                    <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-green-400 transition cursor-pointer" onclick="document.getElementById('image').click()">
                        <div id="image-placeholder">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500">Klik atau seret gambar ke sini</p>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF, WEBP — Maks. 5MB</p>
                        </div>
                        <div id="image-preview" class="hidden">
                            <img src="" alt="Preview" class="h-40 w-full object-cover rounded-lg">
                            <p class="text-xs text-gray-500 mt-2" id="image-name"></p>
                        </div>
                    </div>
                    <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                    @error('image')
                        <p class="mt-2 text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                    <button type="button" onclick="clearImage()" id="clear-image-btn" class="hidden mt-2 text-xs text-red-500 hover:text-red-700">
                        <i class="fas fa-times mr-1"></i> Hapus gambar
                    </button>
                </div>

                {{-- Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                        <i class="fas fa-cog text-green-500"></i> Pengaturan
                    </h2>
                    <div class="space-y-4">
                        {{-- Is Active --}}
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Status Aktif</span>
                                <p class="text-xs text-gray-400">Produk tampil di website</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" name="is_active" id="is_active" value="1" class="sr-only peer" {{ old('is_active', '1') ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-green-500 transition peer-focus:ring-2 peer-focus:ring-green-300"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                            </div>
                        </label>

                        <div class="border-t border-gray-100"></div>

                        {{-- Featured --}}
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Produk Unggulan</span>
                                <p class="text-xs text-gray-400">Tampil di bagian utama</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" name="featured" id="featured" value="1" class="sr-only peer" {{ old('featured') ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-yellow-400 transition peer-focus:ring-2 peer-focus:ring-yellow-300"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-col gap-3">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold text-sm shadow-sm">
                        <i class="fas fa-save mr-2"></i> Simpan Produk
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="w-full inline-flex items-center justify-center px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Template for new variation rows (Placed outside form to avoid validation issues) --}}
<template id="variation-row-template">
    <div class="variation-row grid grid-cols-1 md:grid-cols-5 gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 relative group">
        <div class="md:col-span-1">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Foto Variasi</label>
            <div class="relative w-full h-24 bg-white border border-gray-200 rounded-lg overflow-hidden flex items-center justify-center cursor-pointer hover:border-green-400 transition" onclick="this.querySelector('input').click()">
                <img src="" class="hidden w-full h-full object-cover">
                <div class="text-center placeholder-ui">
                    <i class="fas fa-camera text-gray-300"></i>
                </div>
                <input type="file" name="variations[INDEX][image]" class="hidden" accept="image/*" onchange="previewVariationImage(this)">
            </div>
        </div>
        <div class="md:col-span-1">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tipe (Nama Kelompok)</label>
            <input type="text" name="variations[INDEX][type]" placeholder="Warna / Ukuran" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 outline-none">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-3 mb-1">Label / Nama</label>
            <input type="text" name="variations[INDEX][name]" placeholder="Merah / XL" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 outline-none">
        </div>
        <div class="md:col-span-1">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Penyesuaian Harga (+Rp)</label>
            <input type="number" name="variations[INDEX][price_adjustment]" value="0" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 outline-none">
        </div>
        <div class="md:col-span-1 relative pr-8">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Stok Variasi</label>
            <input type="number" name="variations[INDEX][stock]" value="0" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 outline-none">
            
            <button type="button" onclick="removeVariationRow(this)" class="absolute right-0 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 transition">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>
</template>

<script>
function previewVariationImage(input) {
    const container = input.parentElement;
    const img = container.querySelector('img');
    const placeholder = container.querySelector('.placeholder-ui');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            img.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('image-placeholder');
    const clearBtn = document.getElementById('clear-image-btn');
    const img = preview.querySelector('img');
    const nameEl = document.getElementById('image-name');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            nameEl.textContent = input.files[0].name;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            clearBtn.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function clearImage() {
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('image-placeholder');
    const clearBtn = document.getElementById('clear-image-btn');
    document.getElementById('image').value = '';
    preview.classList.add('hidden');
    placeholder.classList.remove('hidden');
    clearBtn.classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const skuInput = document.getElementById('sku');

    categorySelect.addEventListener('change', function() {
        if (this.value) {
            fetch(`/admin/products/generate-sku?category_id=${this.value}`)
                .then(response => response.json())
                .then(data => {
                    if (data.sku) {
                        skuInput.value = data.sku;
                    }
                })
                .catch(error => console.error('Error generating SKU:', error));
        } else {
            // Optional: clear SKU if no category is selected
            skuInput.value = '';
        }
    });
});

let variationIndex = 0;
function addVariationRow() {
    const wrapper = document.getElementById('variations-wrapper');
    const template = document.getElementById('variation-row-template').innerHTML;
    const msg = document.getElementById('no-variations-msg');
    
    msg.classList.add('hidden');
    
    const newRowHtml = template.replace(/INDEX/g, variationIndex);
    const newRow = document.createElement('div');
    newRow.innerHTML = newRowHtml;
    wrapper.appendChild(newRow.firstElementChild);
    
    variationIndex++;
}

function removeVariationRow(btn) {
    const row = btn.closest('.variation-row');
    row.remove();
    
    const wrapper = document.getElementById('variations-wrapper');
    const rows = wrapper.querySelectorAll('.variation-row');
    const msg = document.getElementById('no-variations-msg');
    
    if (rows.length === 0) {
        msg.classList.remove('hidden');
    }
}

// Show empty message initially
document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.getElementById('variations-wrapper');
    const rows = wrapper.querySelectorAll('.variation-row');
    const msg = document.getElementById('no-variations-msg');
    if (rows.length === 0) {
        msg.classList.remove('hidden');
    }
});
</script>
@endpush
@endsection

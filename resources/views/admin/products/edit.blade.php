@extends('admin.layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Produk</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $product->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.products.show', $product) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                <i class="fas fa-eye mr-2"></i> Lihat Detail
            </a>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    @php
        $imgs = $product->images;
        if (is_string($imgs)) $imgs = json_decode($imgs, true);
        $firstImg = (!empty($imgs) && is_array($imgs)) ? $imgs[0] : null;
    @endphp

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Left Column: Main Info --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- Basic Info --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-info-circle text-green-500"></i> Informasi Dasar
                    </h2>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm @error('name') border-red-400 @enderror">
                        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-1.5">Kode SKU</label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm font-mono @error('sku') border-red-400 @enderror">
                            @error('sku')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                            <select name="category_id" id="category_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm @error('category_id') border-red-400 @enderror">
                                <option value="">Tanpa Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Singkat</label>
                        <input type="text" name="short_description" id="short_description"
                               value="{{ old('short_description', $product->short_description) }}"
                               maxlength="500"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Lengkap</label>
                        <textarea name="description" id="description" rows="5"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm resize-none">{{ old('description', $product->description) }}</textarea>
                    </div>

                    {{-- Metadata --}}
                    <div class="bg-gray-50 rounded-lg p-4 text-xs text-gray-500 grid grid-cols-2 gap-2">
                        <div><span class="font-medium text-gray-600">ID:</span> #{{ $product->id }}</div>
                        <div><span class="font-medium text-gray-600">Slug:</span> {{ $product->slug }}</div>
                        <div><span class="font-medium text-gray-600">Dibuat:</span> {{ $product->created_at->format('d M Y, H:i') }}</div>
                        <div><span class="font-medium text-gray-600">Diperbarui:</span> {{ $product->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-tags text-green-500"></i> Harga & Stok
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1.5">Harga Normal (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                                <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required min="0"
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1.5">Harga Diskon (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                                <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0"
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="member_price" class="block text-sm font-medium text-gray-700 mb-1.5">Harga Member (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                                <input type="number" name="member_price" id="member_price" value="{{ old('member_price', $product->member_price) }}" min="0"
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
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

                        @foreach($product->variations as $index => $variation)
                            <div class="variation-row grid grid-cols-1 md:grid-cols-5 gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 relative group">
                                <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $variation->id }}">
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Foto Variasi</label>
                                    <div class="relative w-full h-24 bg-white border border-gray-200 rounded-lg overflow-hidden flex items-center justify-center cursor-pointer hover:border-green-400 transition" onclick="this.querySelector('input').click()">
                                        @if($variation->image)
                                            <img src="{{ asset('storage/' . $variation->image) }}" class="w-full h-full object-cover">
                                            <div class="text-center placeholder-ui hidden">
                                                <i class="fas fa-camera text-gray-300"></i>
                                            </div>
                                        @else
                                            <img src="" class="hidden w-full h-full object-cover">
                                            <div class="text-center placeholder-ui">
                                                <i class="fas fa-camera text-gray-300"></i>
                                            </div>
                                        @endif
                                        <input type="file" name="variations[{{ $index }}][image]" class="hidden" accept="image/*" onchange="previewVariationImage(this)">
                                    </div>
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tipe</label>
                                    <input type="text" name="variations[{{ $index }}][type]" value="{{ $variation->type }}" placeholder="Ukuran" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-3 mb-1">Nama</label>
                                    <input type="text" name="variations[{{ $index }}][name]" value="{{ $variation->name }}" placeholder="XL" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Penyesuaian Harga (+Rp)</label>
                                    <input type="number" name="variations[{{ $index }}][price_adjustment]" value="{{ (int)$variation->price_adjustment }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                </div>
                                <div class="md:col-span-1 relative pr-8">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Stok Variasi</label>
                                    <input type="number" name="variations[{{ $index }}][stock]" value="{{ $variation->stock }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                    <button type="button" onclick="removeVariationRow(this)" class="absolute right-0 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 transition">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        
                    </div>
                </div>
                {{-- SEO --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-search text-green-500"></i> SEO (Opsional)
                    </h2>
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1.5">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $product->meta_title) }}" maxlength="255"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                    </div>
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1.5">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="2" maxlength="500"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm resize-none">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="space-y-6">

                {{-- Image --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                        <i class="fas fa-image text-green-500"></i> Foto Produk
                    </h2>
                    <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-green-400 transition cursor-pointer" onclick="document.getElementById('image').click()">
                        <div id="image-placeholder" class="{{ $firstImg ? 'hidden' : '' }}">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500">Klik untuk ubah foto</p>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF, WEBP — Maks. 5MB</p>
                        </div>
                        <div id="image-preview" class="{{ $firstImg ? '' : 'hidden' }}">
                            <img src="{{ $firstImg ? asset('storage/'.$firstImg) : '' }}" alt="Preview" class="h-40 w-full object-cover rounded-lg" id="preview-img">
                            <p class="text-xs text-gray-500 mt-2" id="image-name">{{ $firstImg ? basename($firstImg) : '' }}</p>
                        </div>
                    </div>
                    <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                    @error('image')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                    @if($firstImg)
                    <p class="text-xs text-gray-400 mt-2 text-center">Klik untuk mengganti foto</p>
                    @endif
                </div>

                {{-- Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3 mb-4">
                        <i class="fas fa-cog text-green-500"></i> Pengaturan
                    </h2>
                    <div class="space-y-4">
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Status Aktif</span>
                                <p class="text-xs text-gray-400">Produk tampil di website</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-green-500 transition"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                        <div class="border-t border-gray-100"></div>
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Produk Unggulan</span>
                                <p class="text-xs text-gray-400">Tampil di bagian utama</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" name="featured" value="1" class="sr-only peer" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-yellow-400 transition"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-col gap-3">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold text-sm shadow-sm">
                        <i class="fas fa-save mr-2"></i> Perbarui Produk
                    </button>
                    <button type="button" onclick="confirmDelete('delete-form-{{ $product->id }}')"
                            class="w-full inline-flex items-center justify-center px-6 py-2.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-sm font-medium border border-red-200">
                        <i class="fas fa-trash mr-2"></i> Hapus Produk
                    </button>
                </div>
            </div>
        </div>
    </form>

    <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden">
        @csrf @method('DELETE')
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
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tipe</label>
            <input type="text" name="variations[INDEX][type]" placeholder="Warna / Ukuran" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 outline-none">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-3 mb-1">Nama</label>
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
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('preview-img') || document.querySelector('#image-preview img');
            img.src = e.target.result;
            document.getElementById('image-name').textContent = input.files[0].name;
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('image-placeholder').classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
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

let variationIndex = {{ $product->variations->count() }};
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

// Show empty message initially if no rows
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

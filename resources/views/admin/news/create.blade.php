@extends('admin.layouts.app')

@section('title', 'Tambah Berita')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Berita</h1>
        <a href="{{ route('admin.news.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    
    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Berita <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Excerpt -->
            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                    Ringkasan
                </label>
                <textarea name="excerpt" id="excerpt" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('excerpt') border-red-500 @enderror">{{ old('excerpt') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menggunakan otomatis dari konten</p>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Konten <span class="text-red-500">*</span>
                </label>
                <textarea name="content" id="content" rows="10" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Featured Image -->
            <div>
                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                    Gambar Utama
                </label>
                <input type="file" name="featured_image" id="featured_image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('featured_image') border-red-500 @enderror"
                       onchange="previewImage(this)">
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <div id="image-preview" class="mt-2 hidden">
                    <img src="" alt="Preview" class="h-32 w-32 object-cover rounded-lg">
                </div>
            </div>
            
            <!-- Is Published -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Publikasikan berita</span>
                </label>
            </div>
            
            <!-- Published At -->
            <div id="published-at-container" class="{{ old('is_published') ? '' : 'hidden' }}">
                <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Publikasi
                </label>
                <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('published_at') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Kosongkan untuk segera dipublikasikan</p>
                @error('published_at')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Submit Button -->
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Simpan Berita
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const img = preview.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Toggle published at container
document.querySelector('input[name="is_published"]').addEventListener('change', function() {
    const container = document.getElementById('published-at-container');
    if (this.checked) {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }
});
</script>
@endsection

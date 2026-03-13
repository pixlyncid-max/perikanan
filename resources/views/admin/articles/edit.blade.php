@extends('admin.layouts.app')

@section('title', 'Edit Artikel')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Edit Artikel</h1>
        <div class="flex gap-2">
            @if($article->is_published)
                <a href="{{ route('article.show', $article->slug) }}" target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-external-link-alt mr-2"></i> Lihat di Web
                </a>
            @endif
            <a href="{{ route('admin.articles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
    
    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Artikel <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category & Tags -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" id="category"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(['Budidaya', 'Perikanan Tangkap', 'Pengolahan', 'Ekonomi', 'Teknologi', 'Umum'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $article->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <input type="text" name="tags" id="tags"
                           value="{{ old('tags', $article->tags ? implode(', ', $article->tags) : '') }}"
                           placeholder="contoh: Lele, Nila, Bioflok"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tags') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma</p>
                    @error('tags')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Excerpt -->
            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Ringkasan</label>
                <textarea name="excerpt" id="excerpt" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('excerpt') border-red-500 @enderror">{{ old('excerpt', $article->excerpt) }}</textarea>
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
                <textarea name="content" id="content" rows="12" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('content') border-red-500 @enderror">{{ old('content', $article->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Featured Image -->
            <div>
                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                    Gambar Utama <span class="text-gray-400 text-xs">(Kosongkan jika tidak ingin mengubah)</span>
                </label>
                <input type="file" name="featured_image" id="featured_image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('featured_image') border-red-500 @enderror"
                       onchange="previewImage(this)">
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <div id="image-preview" class="mt-2 {{ $article->featured_image ? '' : 'hidden' }}">
                    <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : '' }}" alt="Preview" class="h-32 w-auto object-cover rounded-lg">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Is Published -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Publikasikan artikel</span>
                    </label>
                </div>
                
                <!-- Published At -->
                <div id="published-at-container" class="{{ old('is_published', $article->is_published) ? '' : 'hidden' }}">
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publikasi</label>
                    <input type="datetime-local" name="published_at" id="published_at" 
                           value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('published_at') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan untuk segera dipublikasikan</p>
                    @error('published_at')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Article Info -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Informasi Artikel</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                    <div><span class="font-medium">Slug:</span> {{ $article->slug }}</div>
                    <div><span class="font-medium">Dilihat:</span> {{ number_format($article->views) }}x</div>
                    <div><span class="font-medium">Dibuat:</span> {{ $article->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Perbarui Artikel
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

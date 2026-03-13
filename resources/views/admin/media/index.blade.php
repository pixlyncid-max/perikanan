@extends('admin.layouts.app')

@section('title', 'Media & Upload')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Media & Upload</h1>
    </div>
    
    <!-- Upload Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Upload File Baru</h3>
        <form method="POST" action="{{ route('admin.media.upload') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih File <span class="text-gray-400 text-xs">(JPEG, PNG, JPG, GIF, PDF, DOC, DOCX - Max 10MB)</span>
                </label>
                <input type="file" name="file" id="file" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('file') border-red-500 @enderror">
                @error('file')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-upload mr-2"></i> Upload File
                </button>
            </div>
        </form>
    </div>
    
    <!-- Media Grid -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">File Media</h3>
        </div>
        
        @if(count($files) > 0)
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($files as $file)
                <div class="group relative bg-gray-50 rounded-lg p-4 hover:shadow-md transition">
                    <!-- File Preview -->
                    <div class="aspect-square flex items-center justify-center mb-2">
                        @if(strpos($file['type'], 'image/') === 0)
                            <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="max-h-full max-w-full object-contain rounded">
                        @elseif(strpos($file['type'], 'pdf') !== false)
                            <i class="fas fa-file-pdf text-6xl text-red-500"></i>
                        @elseif(strpos($file['type'], 'word') !== false || strpos($file['type'], 'doc') !== false)
                            <i class="fas fa-file-word text-6xl text-blue-500"></i>
                        @else
                            <i class="fas fa-file text-6xl text-gray-400"></i>
                        @endif
                    </div>
                    
                    <!-- File Info -->
                    <div class="text-center">
                        <p class="text-xs text-gray-600 truncate" title="{{ $file['name'] }}">{{ $file['name'] }}</p>
                        <p class="text-xs text-gray-400">{{ $file['size'] }}</p>
                    </div>
                    
                    <!-- Actions -->
                    <div class="absolute top-2 right-2 flex space-x-1 opacity-0 group-hover:opacity-100 transition">
                        <a href="{{ $file['url'] }}" target="_blank" class="p-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200" title="Lihat">
                            <i class="fas fa-eye text-xs"></i>
                        </a>
                        <button onclick="copyUrl('{{ $file['url'] }}')" class="p-1 bg-green-100 text-green-600 rounded hover:bg-green-200" title="Copy URL">
                            <i class="fas fa-link text-xs"></i>
                        </button>
                        <button onclick="confirmDelete('delete-form-{{ $loop->index }}')" class="p-1 bg-red-100 text-red-600 rounded hover:bg-red-200" title="Hapus">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                    
                    <form id="delete-form-{{ $loop->index }}" 
                          action="{{ route('admin.media.destroy', $file['name']) }}" 
                          method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="px-6 py-12 text-center text-gray-500">
            <div class="flex flex-col items-center">
                <i class="fas fa-images text-4xl text-gray-300 mb-2"></i>
                <p>Belum ada file media</p>
                <p class="text-sm text-gray-400 mt-1">Upload file pertama Anda</p>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function copyUrl(url) {
    navigator.clipboard.writeText(url).then(function() {
        alert('URL berhasil disalin: ' + url);
    }, function(err) {
        console.error('Gagal menyalin URL: ', err);
    });
}
</script>
@endsection

@extends('admin.layouts.app')

@section('title', 'Manajemen Produk')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Produk</h1>
            <p class="text-sm text-gray-500 mt-1">Total: {{ $products->total() }} produk</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.products.import-template') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-200 transition font-medium text-sm shadow-sm">
                <i class="fas fa-file-excel mr-2 text-green-600"></i> Unduh Template
            </a>
            <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm shadow-sm">
                <i class="fas fa-upload mr-2"></i> Import Excel
            </button>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium text-sm shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Produk
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama, SKU, atau deskripsi..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                </div>
            </div>
            <div class="w-full md:w-44">
                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-36">
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="w-full md:w-36">
                <select name="featured" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                    <option value="">Semua Produk</option>
                    <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Unggulan</option>
                    <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Bukan Unggulan</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition text-sm font-medium">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Mass Action & Products Table Form --}}
    <form action="{{ route('admin.products.mass-action') }}" method="POST" id="massActionForm">
        @csrf
        <div class="mb-4 flex items-center gap-3 hidden" id="massActionContainer">
            <select name="action" id="massActionSelect" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                <option value="">-- Pilih Aksi Massal --</option>
                <option value="activate">Aktifkan Terpilih</option>
                <option value="deactivate">Nonaktifkan Terpilih</option>
                <option value="delete">Hapus Terpilih</option>
            </select>
            <button type="button" onclick="submitMassAction()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                Terapkan
            </button>
        </div>

    {{-- Products Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 w-10 text-center">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-green-600 focus:ring-green-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-14">Foto</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Unggulan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($products as $product)
                    @php
                        $imgs = $product->images;
                        if (is_string($imgs)) $imgs = json_decode($imgs, true);
                        $firstImg = (!empty($imgs) && is_array($imgs)) ? $imgs[0] : null;
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        {{-- Checkbox --}}
                        <td class="px-4 py-3 text-center">
                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500 cursor-pointer form-checkbox">
                        </td>

                        {{-- Image --}}
                        <td class="px-4 py-3">
                            @if($firstImg)
                                <img class="h-12 w-12 rounded-lg object-cover border border-gray-200 shadow-sm"
                                     src="{{ asset('storage/' . $firstImg) }}" alt="{{ $product->name }}">
                            @else
                                <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center border border-gray-200">
                                    <i class="fas fa-image text-gray-400 text-sm"></i>
                                </div>
                            @endif
                        </td>

                        {{-- Name & SKU --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $product->name }}</div>
                                    @if($product->sku)
                                        <div class="text-xs text-gray-400 mt-0.5"><span class="font-mono">SKU: {{ $product->sku }}</span></div>
                                    @endif
                                    @if($product->short_description)
                                        <div class="text-xs text-gray-500 mt-0.5 max-w-xs truncate">{{ $product->short_description }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Category --}}
                        <td class="px-4 py-3 text-sm text-gray-600">
                            @if($product->category)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $product->category->name }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">Tanpa kategori</span>
                            @endif
                        </td>

                        {{-- Price --}}
                        <td class="px-4 py-3">
                            <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @if($product->sale_price > 0)
                                <div class="text-xs text-red-500 font-medium">Diskon: Rp {{ number_format($product->sale_price, 0, ',', '.') }}</div>
                            @endif
                            @if($product->member_price > 0)
                                <div class="text-xs text-green-600">Member: Rp {{ number_format($product->member_price, 0, ',', '.') }}</div>
                            @endif
                        </td>

                        {{-- Stock --}}
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $product->stock > 10 ? 'bg-green-100 text-green-800' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $product->stock }}
                            </span>
                        </td>

                        {{-- Status Active --}}
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.products.toggle-active', $product) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" title="{{ $product->is_active ? 'Klik untuk nonaktifkan' : 'Klik untuk aktifkan' }}"
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition
                                        {{ $product->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $product->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>

                        {{-- Featured --}}
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" title="{{ $product->featured ? 'Hapus dari unggulan' : 'Jadikan unggulan' }}"
                                        class="text-lg transition {{ $product->featured ? 'text-yellow-400 hover:text-yellow-500' : 'text-gray-300 hover:text-yellow-400' }}">
                                    <i class="fas fa-star"></i>
                                </button>
                            </form>
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.products.show', $product) }}"
                                   class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-lg transition" title="Detail">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="p-1.5 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 rounded-lg transition" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <button onclick="confirmDelete('delete-form-{{ $product->id }}')"
                                        class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-lg transition" title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                                <form id="delete-form-{{ $product->id }}"
                                      action="{{ route('admin.products.destroy', $product) }}"
                                      method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center text-gray-400">
                                <i class="fas fa-box-open text-5xl mb-3 text-gray-200"></i>
                                <p class="text-base font-medium">Belum ada data produk</p>
                                <p class="text-sm mt-1">Mulai tambahkan produk pertama Anda</p>
                                <a href="{{ route('admin.products.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-plus mr-2"></i> Tambah Produk
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $products->withQueryString()->links() }}
        </div>
        @endif
    </div>
    </form>
</div>

{{-- Import Modal --}}
<div id="importModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-file-excel text-green-600 mr-2"></i> Import Produk dari Excel
            </h3>
            <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-3">
                        Gunakan file templat Excel yang kami sediakan untuk mengunggah produk secara massal. Pastikan semua format pada tabel sesuai.
                    </p>
                    <a href="{{ route('admin.products.import-template') }}" class="text-sm font-semibold text-blue-600 hover:underline inline-flex items-center">
                        <i class="fas fa-download mr-1"></i> Unduh File Template
                    </a>
                </div>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 flex flex-col items-center justify-center transition-colors">
                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                    <label for="file_excel" class="cursor-pointer">
                        <span class="text-sm font-medium text-blue-600 hover:underline">Pilih File Excel</span>
                        <span class="text-sm text-gray-500"> (.xlsx, .xls, .csv)</span>
                        <input id="file_excel" name="file" type="file" class="hidden" accept=".xlsx, .xls, .csv" required onchange="updateFileName(this)">
                    </label>
                    <p id="file-name-display" class="mt-2 text-xs text-gray-400 font-mono hidden"></p>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition text-sm font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium flex items-center">
                    <i class="fas fa-upload mr-2"></i> Mulai Import
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const massActionContainer = document.getElementById('massActionContainer');

    function toggleMassActionVisibility() {
        const anyChecked = Array.from(productCheckboxes).some(cb => cb.checked);
        if (anyChecked) {
            massActionContainer.classList.remove('hidden');
        } else {
            massActionContainer.classList.add('hidden');
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleMassActionVisibility();
        });
    }

    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(productCheckboxes).every(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            toggleMassActionVisibility();
        });
    });
});

function submitMassAction() {
    const action = document.getElementById('massActionSelect').value;
    if (!action) {
        alert('Silakan pilih aksi yang ingin diterapkan.');
        return;
    }

    const customMessage = action === 'delete' 
        ? 'Apakah Anda yakin ingin menghapus massal produk yang dipilih? Tindakan ini tidak dapat dibatalkan.' 
        : 'Apakah Anda yakin ingin menerapkan aksi massal ini?';

    Swal.fire({
        title: 'Konfirmasi Aksi Massal',
        text: customMessage,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: action === 'delete' ? '#ef4444' : '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Terapkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('massActionForm').submit();
        }
    });
}

function updateFileName(input) {
    const display = document.getElementById('file-name-display');
    if (input.files && input.files[0]) {
        display.textContent = input.files[0].name;
        display.classList.remove('hidden');
    } else {
        display.classList.add('hidden');
    }
}
</script>
@endpush
@endsection

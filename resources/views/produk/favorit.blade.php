@extends('layouts.app')

@section('title', 'Favorit Saya - ' . get_setting('site_name', 'FISHERIES'))

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Favorit Saya</h1>
                <p class="text-gray-600">Daftar produk yang Anda simpan ke Favorit.</p>
            </div>
            
            <a href="{{ route('produk.index') }}" class="mt-4 md:mt-0 text-blue-600 hover:text-blue-700 font-semibold flex items-center transition">
                <i class="fas fa-arrow-left mr-2"></i> Lanjut Belanja
            </a>
        </div>

        @if($products->isEmpty())
            <div id="empty-state" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center" style="display: none;">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="far fa-heart text-4xl text-gray-300"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Favorit Anda Kosong</h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Anda belum menambahkan produk apapun ke dalam daftar favorit Anda. Temukan produk menarik sekarang!</p>
                <a href="{{ route('produk.index') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                    Mulai Belanja
                </a>
            </div>
            
            <div id="loading-state" class="py-20 text-center">
                <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-gray-500">Memuat daftar favorit Anda...</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="relative group">
                        @include('produk.partials.product-card', ['product' => $product])
                        
                        <!-- Remove from Wishlist Button -->
                        <button onclick="removeFromWishlist({{ $product->id }}, this)" class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-white shadow-md text-red-500 hover:bg-red-50 hover:text-red-600 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0" title="Hapus dari Favorit">
                            <i class="fas fa-trash-alt text-sm"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const hasIds = urlParams.has('ids');
        let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        
        @if($products->isEmpty())
            if (wishlist.length > 0 && !hasIds) {
                // Redirect to fetch products
                window.location.replace('{{ route("produk.favorit") }}?ids=' + wishlist.join(','));
            } else {
                // It is genuinely empty
                document.getElementById('loading-state').style.display = 'none';
                document.getElementById('empty-state').style.display = 'block';
            }
        @else
            // Check if wishlist corresponds to displayed products. If they cleared localStorage elsewhere, this syncs it
            // (Optional logic, let's keep it simple)
        @endif
    });

    function removeFromWishlist(productId, btnElement) {
        let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        if (wishlist.includes(productId)) {
            wishlist = wishlist.filter(id => id !== productId);
            localStorage.setItem('wishlist', JSON.stringify(wishlist));
            
            if (typeof showToast === 'function') {
                showToast('Dihapus', 'Produk telah dihapus dari daftar favorit', 'info');
            }
            
            // Remove the card visually
            const cardContainer = btnElement.closest('.relative.group');
            if (cardContainer) {
                cardContainer.classList.add('transition-all', 'duration-300', 'scale-90', 'opacity-0');
                setTimeout(() => {
                    cardContainer.remove();
                    if (wishlist.length === 0) {
                        window.location.reload(); // To show empty state
                    }
                }, 300);
            }
        }
    }
</script>
@endpush
@endsection

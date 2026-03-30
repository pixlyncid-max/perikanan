@php
    $imgs = $product->images;
    if(is_string($imgs)) $imgs = json_decode($imgs, true);
    $firstImg = (!empty($imgs) && is_array($imgs)) ? $imgs[0] : null;
    $colorClass = $colorClass ?? 'blue';
@endphp

<div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl transition flex flex-col h-full">
    <a href="{{ route('produk.show', $product->slug) }}" class="relative h-48 bg-gray-100 flex-shrink-0 block overflow-hidden">
        @if($firstImg)
            <img src="{{ asset('storage/'.$firstImg) }}" id="product-img-{{ $product->id }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition transform group-hover:scale-110 duration-500">
        @else
            <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                <i class="fas fa-box text-6xl"></i>
            </div>
        @endif

        @if($product->featured)
            <div class="absolute top-4 left-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-sm z-10">
                <i class="fas fa-star mr-1"></i> Unggulan
            </div>
        @endif

        @if($product->sale_price > 0 && $product->price > 0)
            <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-sm z-10">
                -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
            </div>
        @endif
    </a>
    <div class="p-6 flex flex-col flex-grow">
        <h3 class="text-lg font-bold text-gray-800 mb-2 truncate" title="{{ $product->name }}">
            <a href="{{ route('produk.show', $product->slug) }}" class="hover:text-{{ $colorClass }}-600 transition">
                {{ $product->name }}
            </a>
        </h3>
        <p class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow">{{ $product->short_description ?: Str::limit($product->description, 80) }}</p>
        
        <div class="flex flex-col mt-auto gap-3">
            <div class="flex items-center justify-between">
                <div class="flex flex-col">
                    @if($product->sale_price > 0)
                        <span class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <span class="text-xl font-bold text-{{ $colorClass }}-600">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                    @else
                        <span class="text-xl font-bold text-{{ $colorClass }}-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @endif
                </div>
                @php $hasVariations = $product->variations->count() > 0; @endphp
                <button 
                    onclick="{{ $hasVariations ? "window.location.href='" . route('produk.show', $product->slug) . "?select_variation=1'" : "addToCart($product->id)" }}" 
                    id="cart-btn-{{ $product->id }}" 
                    class="bg-{{ $colorClass }}-600 text-white px-4 py-2 rounded-lg hover:bg-{{ $colorClass }}-700 transition relative group/btn" 
                    title="{{ $hasVariations ? 'Pilih variasi' : 'Tambah ke keranjang' }}"
                    {{ $product->stock < 1 ? 'disabled' : '' }}
                >
                    <i class="fas {{ $hasVariations ? 'fa-list-ul' : 'fa-cart-plus' }}"></i>
                    @if($hasVariations)
                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-[10px] rounded opacity-0 group-hover/btn:opacity-100 transition whitespace-nowrap pointer-events-none">Pilih Variasi</span>
                    @endif
                </button>
            </div>
            <div class="text-xs {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600 font-bold' }}">
                Stok: {{ $product->stock > 0 ? $product->stock : 'Habis' }}
            </div>
        </div>
    </div>
</div>

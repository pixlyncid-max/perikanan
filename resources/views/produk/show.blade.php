@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <!-- Breadcrumbs -->
            <nav class="text-base text-gray-900" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li><a href="{{ route('produk.index') }}" class="hover:text-blue-600 transition">Produk</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li class="font-bold text-black">{{ $product->name }}</li>
                </ol>
            </nav>

            <!-- Back Button -->
            <a href="javascript:history.back()" class="inline-flex items-center text-base font-bold text-gray-900 hover:text-blue-600 transition-all group">
                <i class="fas fa-arrow-left mr-2 transition-transform group-hover:-translate-x-1"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <!-- Image Gallery -->
                <div class="p-4 md:p-8 bg-gray-50 flex flex-col items-center border-r border-gray-100">
                    @php
                        $images = $product->images;
                        if(is_string($images)) $images = json_decode($images, true);
                        $firstImg = (!empty($images) && is_array($images)) ? $images[0] : null;
                        
                        // Collect all variation images
                        $variationImages = $product->variations->whereNotNull('image')->pluck('image', 'id')->toArray();
                    @endphp
                    
                    {{-- Main Image Display --}}
                    <div class="relative w-full aspect-square bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6 flex items-center justify-center overflow-hidden group">
                        @if($firstImg)
                            <img src="{{ asset('storage/'.$firstImg) }}" id="product-img-{{ $product->id }}" alt="{{ $product->name }}" 
                                 class="max-w-full max-h-full object-contain transition-all duration-500 transform group-hover:scale-105">
                        @else
                            <div class="text-gray-200" id="product-img-{{ $product->id }}-placeholder">
                                <i class="fas fa-box text-9xl"></i>
                            </div>
                        @endif
                        
                        {{-- Zoom Button --}}
                        <button type="button" onclick="openZoom()" class="absolute bottom-4 right-4 bg-white/80 backdrop-blur shadow-sm p-3 rounded-full text-gray-600 hover:text-blue-600 transition opacity-0 group-hover:opacity-100 z-10">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </button>
                    </div>

                    {{-- Thumbnails Slider --}}
                    <div class="w-full relative px-8">
                        <div id="thumb-slider" class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x">
                            {{-- Main Product Images --}}
                            @if(!empty($images))
                                @foreach($images as $idx => $img)
                                    <button type="button" onclick="changeMainImage('{{ asset('storage/'.$img) }}', this)" 
                                            class="thumb-btn flex-shrink-0 w-20 h-20 rounded-xl border-2 border-blue-500 overflow-hidden snap-start transition-all duration-200 {{ $idx == 0 ? 'active border-blue-500 bg-blue-50' : 'border-transparent opacity-60 hover:opacity-100' }}">
                                        <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            @endif

                            {{-- Variation Images --}}
                            @foreach($product->variations as $var)
                                @if($var->image)
                                    <button type="button" id="thumb-var-{{ $var->id }}" 
                                            onclick="changeMainImage('{{ asset('storage/'.$var->image) }}', this, {{ $var->id }})" 
                                            class="thumb-btn flex-shrink-0 w-20 h-20 rounded-xl border-2 border-transparent overflow-hidden snap-start opacity-60 hover:opacity-100 transition-all duration-200"
                                            data-variation-id="{{ $var->id }}">
                                        <img src="{{ asset('storage/'.$var->image) }}" class="w-full h-full object-cover">
                                    </button>
                                @endif
                            @endforeach
                        </div>
                        
                        {{-- Slider Navigation Arrows --}}
                        <div class="absolute inset-y-0 left-0 flex items-center -ml-2 pointer-events-none">
                             <button type="button" onclick="navigateGallery(-1)" class="w-8 h-8 rounded-full bg-white shadow-md flex items-center justify-center text-gray-400 hover:text-blue-600 transition pointer-events-auto active:scale-95">
                                <i class="fas fa-chevron-left text-xs"></i>
                             </button>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center -mr-2 pointer-events-none">
                             <button type="button" onclick="navigateGallery(1)" class="w-8 h-8 rounded-full bg-white shadow-md flex items-center justify-center text-gray-400 hover:text-blue-600 transition pointer-events-auto active:scale-95">
                                <i class="fas fa-chevron-right text-xs"></i>
                             </button>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="p-8 md:p-12">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $product->category->name }}
                        </span>
                        @if($product->featured)
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                <i class="fas fa-star mr-1"></i> Unggulan
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{{ $product->name }}</h1>
                    
                    <div class="flex items-baseline gap-4 mb-6" id="price-container" data-base-price="{{ $product->sale_price > 0 ? $product->sale_price : $product->price }}">
                        @if($product->sale_price > 0)
                            <span class="text-3xl font-bold text-blue-600" id="main-price-display">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                            <span class="text-lg text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                            </span>
                        @else
                            <span class="text-3xl font-bold text-blue-600" id="main-price-display">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    <div class="prose prose-blue max-w-none text-gray-600 mb-8" id="main-description-display" data-base-description="{{ nl2br(e($product->description)) }}">
                        {!! nl2br(e($product->description)) !!}
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-8 py-6 border-y border-gray-100">
                        <div>
                            <span class="block text-xs uppercase tracking-widest text-gray-400 mb-1 font-bold">Stok Tersedia</span>
                            <span class="text-lg font-bold {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}" id="main-stock-display" data-base-stock="{{ $product->stock }}">
                                {{ $product->stock > 0 ? $product->stock : 'Habis' }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase tracking-widest text-gray-400 mb-1 font-bold">SKU Produk</span>
                            <span class="text-lg font-bold text-gray-800">{{ $product->sku ?: '-' }}</span>
                        </div>
                    </div>

                    <!-- Variations Selection -->
                    @if($product->variations->count() > 0)
                        <div class="mb-8" id="variation-container">
                            @php
                                $variationGroups = $product->variations->groupBy('type');
                            @endphp
                            
                            @foreach($variationGroups as $type => $vars)
                                <div class="mb-4">
                                    <span class="block text-sm font-bold text-gray-700 mb-3">{{ $type }}</span>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($vars as $variation)
                                            <button 
                                                type="button"
                                                id="var-btn-{{ $variation->id }}"
                                                onclick="selectVariation(this, {{ $variation->id }}, '{{ $variation->name }}', {{ $variation->price_adjustment }}, {{ $variation->stock }}{{ $variation->image ? ", '" . asset('storage/'.$variation->image) . "'" : ', null' }})"
                                                class="variation-btn px-5 py-2.5 rounded-lg border-2 border-gray-200 text-gray-700 font-medium hover:border-blue-500 hover:text-blue-600 transition-all duration-200 flex items-center gap-2"
                                                data-variation-id="{{ $variation->id }}"
                                                data-description="{{ $variation->description }}"
                                            >
                                                {{ $variation->name }}
                                                @if($variation->price_adjustment > 0)
                                                    <span class="text-xs text-blue-500">(+Rp {{ number_format($variation->price_adjustment, 0, ',', '.') }})</span>
                                                @elseif($variation->price_adjustment < 0)
                                                    <span class="text-xs text-red-500">(-Rp {{ number_format(abs($variation->price_adjustment), 0, ',', '.') }})</span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            
                            <input type="hidden" id="selected-variation-id" value="">
                            <p id="variation-error" class="text-red-500 text-sm mt-2 hidden animate-pulse">
                                <i class="fas fa-exclamation-circle mr-1"></i> Silakan pilih variasi produk terlebih dahulu
                            </p>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button onclick="handleAddToCart({{ $product->id }}, {{ $product->variations->count() > 0 ? 'true' : 'false' }})" id="cart-btn-{{ $product->id }}" class="flex-grow bg-blue-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-blue-700 transition transform hover:-translate-y-1 shadow-lg flex items-center justify-center gap-3 {{ $product->stock < 1 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->stock < 1 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i>
                            Tambah ke Keranjang
                        </button>
                        <button class="bg-gray-100 text-gray-800 px-6 py-4 rounded-xl font-bold hover:bg-gray-200 transition">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 border-l-4 border-blue-600 pl-4">Produk Terkait</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    @include('produk.partials.product-card', ['product' => $related])
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@push('scripts')
<script>
    let isInternalSelection = false;

    function changeMainImage(url, btn, variationId = null) {
        const mainImg = document.getElementById('product-img-{{ $product->id }}');
        if (!mainImg) return;
        
        mainImg.style.opacity = '0';
        setTimeout(() => {
            mainImg.src = url;
            mainImg.style.opacity = '1';
        }, 200);

        // Update active thumbnail
        document.querySelectorAll('.thumb-btn').forEach(b => {
            b.classList.remove('active', 'border-blue-500', 'bg-blue-50');
            b.classList.add('border-transparent', 'opacity-60');
        });
        
        if (btn) {
            btn.classList.add('active', 'border-blue-500', 'bg-blue-50');
            btn.classList.remove('border-transparent', 'opacity-60');
        }

        // If this image belongs to a variation, select that variation button
        if (variationId && !isInternalSelection) {
            const varBtn = document.getElementById('var-btn-' + variationId);
            if (varBtn) {
                isInternalSelection = true;
                varBtn.click();
                isInternalSelection = false;
            }
        }
    }

    function navigateGallery(direction) {
        const thumbnails = Array.from(document.querySelectorAll('.thumb-btn'));
        const activeIndex = thumbnails.findIndex(btn => btn.classList.contains('active'));
        
        let nextIndex = activeIndex + direction;
        
        if (nextIndex < 0) nextIndex = 0;
        if (nextIndex >= thumbnails.length) nextIndex = thumbnails.length - 1;
        
        if (activeIndex !== -1 && nextIndex !== activeIndex) {
            thumbnails[nextIndex].click();
            thumbnails[nextIndex].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    }

    function selectVariation(btn, id, name, priceAdjustment, stock, imageUrl = null) {
        const description = btn.getAttribute('data-description') || '';
        // Reset and Highlight
        document.querySelectorAll('.variation-btn').forEach(b => {
            b.classList.remove('border-blue-500', 'text-blue-600', 'bg-blue-50');
            b.classList.add('border-gray-200', 'text-gray-700');
        });
        
        btn.classList.remove('border-gray-200', 'text-gray-700');
        btn.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
        
        // Sync Image if exists
        if (imageUrl && !isInternalSelection) {
            const thumbBtn = document.getElementById('thumb-var-' + id);
            isInternalSelection = true;
            changeMainImage(imageUrl, thumbBtn, id);
            isInternalSelection = false;
            
            // Scroll thumbnail into view if needed
            if (thumbBtn) {
                thumbBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        }
        
        // Update selection
        document.getElementById('selected-variation-id').value = id;
        document.getElementById('variation-error').classList.add('hidden');
        
        // Update Price Display
        const priceContainer = document.getElementById('price-container');
        const priceDisplay = document.getElementById('main-price-display');
        const basePrice = parseFloat(priceContainer.dataset.basePrice);
        const totalPrice = basePrice + parseFloat(priceAdjustment);
        
        priceDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice);
        
        // Update Stock Display
        const stockDisplay = document.getElementById('main-stock-display');
        stockDisplay.textContent = stock > 0 ? stock : 'Habis';
        if (stock <= 0) {
            stockDisplay.classList.remove('text-green-600');
            stockDisplay.classList.add('text-red-600');
        } else {
            stockDisplay.classList.remove('text-red-600');
            stockDisplay.classList.add('text-green-600');
        }

        // Update Main Description with Variation Description (if available)
        const mainDescDisplay = document.getElementById('main-description-display');
        const baseDescription = mainDescDisplay.getAttribute('data-base-description');
        
        if (description && description.trim() !== '') {
            mainDescDisplay.innerHTML = description.replace(/\n/g, '<br>');
            // Add a small highlight animation/effect
            mainDescDisplay.classList.add('animate-fade-in');
            setTimeout(() => mainDescDisplay.classList.remove('animate-fade-in'), 500);
        } else {
            mainDescDisplay.innerHTML = baseDescription;
        }

        // Handle Add to Cart Button state based on variation stock
        const cartBtn = document.querySelector('[id^="cart-btn-"]');
        if (stock <= 0) {
            cartBtn.disabled = true;
            cartBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            const productStock = parseInt(document.getElementById('main-stock-display').dataset.baseStock);
            // Even if variation has stock, check if product as a whole is active (optional but safe)
            cartBtn.disabled = false;
            cartBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    function handleAddToCart(productId, hasVariations) {
        const variationId = document.getElementById('selected-variation-id') ? document.getElementById('selected-variation-id').value : null;
        
        if (hasVariations && !variationId) {
            const errorEl = document.getElementById('variation-error');
            errorEl.classList.remove('hidden');
            errorEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        
        // Call the global addToCart with variationId
        if (typeof window.addToCart === 'function') {
            window.addToCart(productId, variationId);
        }
    }

    // Zoom Handlers
    function openZoom() {
        const mainImg = document.getElementById('product-img-{{ $product->id }}');
        const zoomModal = document.getElementById('zoom-modal');
        const zoomImg = document.getElementById('zoom-img');
        
        if (!mainImg || !zoomModal) return;
        
        zoomImg.src = mainImg.src;
        zoomModal.classList.remove('hidden');
        zoomModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeZoom() {
        const zoomModal = document.getElementById('zoom-modal');
        zoomModal.classList.add('hidden');
        zoomModal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Auto-scroll and highlight variations if requested via URL
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('select_variation')) {
            const container = document.getElementById('variation-container');
            if (container) {
                // Scroll to container
                container.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Visual feedback (Flash effect)
                container.classList.add('ring-4', 'ring-blue-100', 'rounded-2xl', 'p-4', '-m-4', 'transition-all', 'duration-1000');
                
                // Show error message early to guide user
                document.getElementById('variation-error').classList.remove('hidden');
                
                // Remove highlight after 2 seconds
                setTimeout(() => {
                    container.classList.remove('ring-4', 'ring-blue-100');
                }, 2000);
            }
        }
    });
</script>
@endpush

{{-- Zoom Modal --}}
<div id="zoom-modal" class="fixed inset-0 z-[100] hidden bg-black/95 items-center justify-center p-4 md:p-8 animate-fade-in backdrop-blur-sm">
    <button onclick="closeZoom()" class="absolute top-6 right-6 text-white hover:text-blue-500 transition-all text-3xl z-20">
        <i class="fas fa-times"></i>
    </button>
    
    <div class="relative w-full h-full flex items-center justify-center" onclick="closeZoom()">
        <img id="zoom-img" src="" alt="Zoomed Product" class="max-w-full max-h-full object-contain shadow-2xl transition-transform duration-300" onclick="event.stopPropagation()">
    </div>
</div>
@endsection


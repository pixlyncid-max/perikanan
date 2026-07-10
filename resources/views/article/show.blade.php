@extends('layouts.app')

@section('title', $article->meta_title ?? $article->title . ' - FISHERIES')

@push('styles')
<style>
    .prose h2 { font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-top: 2rem; margin-bottom: 1rem; }
    .prose h3 { font-size: 1.25rem; font-weight: 600; color: #1F2937; margin-top: 1.5rem; margin-bottom: 0.75rem; }
    .prose p  { color: #4B5563; line-height: 1.8; margin-bottom: 1rem; }
    .prose ul { list-style: disc; padding-left: 1.5rem; color: #4B5563; margin-bottom: 1rem; }
    .prose ol { list-style: decimal; padding-left: 1.5rem; color: #4B5563; margin-bottom: 1rem; }
    .prose li { margin-bottom: 0.4rem; line-height: 1.7; }
    .prose strong { color: #1F2937; }
    .prose a { color: #17A152; text-decoration: underline; }
    .prose blockquote { border-left: 4px solid #17A152; padding-left: 1rem; color: #6B7280; font-style: italic; margin: 1.5rem 0; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('article.index') }}" class="text-green-600 hover:underline">
                <i class="fas fa-arrow-left mr-1"></i> Artikel
            </a>
            @if($article->category)
                <span>/</span>
                <a href="{{ route('article.category', $article->category) }}" class="text-green-600 hover:underline">
                    {{ $article->category }}
                </a>
            @endif
        </div>

        <article class="bg-white rounded-xl shadow-lg overflow-hidden reveal-scale">

            {{-- Featured Image --}}
            @if($article->featured_image)
                <div class="h-64 md:h-96 overflow-hidden">
                    <img src="{{ asset('storage/' . $article->featured_image) }}"
                         alt="{{ $article->title }}"
                         class="w-full h-full object-cover">
                </div>
            @else
                <div class="h-32 md:h-48 bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
                    <i class="fas fa-newspaper text-7xl text-green-200"></i>
                </div>
            @endif

            <div class="p-8">
                {{-- Meta Info --}}
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    @if($article->category)
                        <a href="{{ route('article.category', $article->category) }}"
                           class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold hover:bg-green-200 transition">
                            {{ $article->category }}
                        </a>
                    @endif
                    <span class="text-gray-400 text-sm">
                        <i class="far fa-calendar mr-1"></i>
                        {{ $article->published_at ? $article->published_at->format('d F Y') : $article->created_at->format('d F Y') }}
                    </span>
                    <span class="text-gray-400 text-sm">
                        <i class="far fa-eye mr-1"></i> {{ number_format($article->views) }} kali dilihat
                    </span>
                </div>

                {{-- Title --}}
                <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight tracking-tight">
                    {{ $article->title }}
                </h1>

                {{-- Author --}}
                <div class="flex items-center mb-8 pb-8 border-b border-gray-200">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-user text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $article->author?->name ?? 'Tim FISHERIES' }}</p>
                        <p class="text-gray-500 text-sm">Kontributor FISHERIES</p>
                    </div>
                </div>

                {{-- Excerpt / Summary --}}
                @if($article->excerpt)
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg mb-8">
                        <p class="text-green-800 font-medium leading-relaxed">{{ $article->excerpt }}</p>
                    </div>
                @endif

                {{-- Content --}}
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($article->content)) !!}
                </div>

                {{-- Tags --}}
                @if($article->tags && count($article->tags) > 0)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-sm font-semibold text-gray-600 mb-3"><i class="fas fa-hashtag mr-1"></i>Tags:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($article->tags as $tag)
                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm hover:bg-green-100 hover:text-green-700 transition cursor-default">
                                    #{{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Share Buttons --}}
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-share-alt mr-2 text-green-600"></i>Bagikan Artikel
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           target="_blank"
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                            <i class="fab fa-facebook-f mr-2"></i>Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}"
                           target="_blank"
                           class="bg-sky-500 text-white px-4 py-2 rounded-lg hover:bg-sky-600 transition text-sm font-medium">
                            <i class="fab fa-twitter mr-2"></i>Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . request()->url()) }}"
                           target="_blank"
                           class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition text-sm font-medium">
                            <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </article>

        {{-- Related Articles --}}
        @if($relatedArticles->count() > 0)
        <div class="mt-10 reveal">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="w-1 h-6 bg-green-600 rounded mr-3"></span> Artikel Terkait
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedArticles as $related)
                <a href="{{ route('article.show', $related->slug) }}"
                   class="bg-white rounded-xl shadow-lg overflow-hidden transition duration-300 group block reveal stagger-{{ $loop->iteration > 6 ? 6 : $loop->iteration }} card-hover">
                    <div class="h-40 overflow-hidden">
                        @if($related->featured_image)
                            <img src="{{ asset('storage/' . $related->featured_image) }}"
                                 alt="{{ $related->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
                                <i class="fas fa-newspaper text-4xl text-green-200"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        @if($related->category)
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $related->category }}
                            </span>
                        @endif
                        <h4 class="font-bold text-gray-800 mt-2 group-hover:text-green-600 transition leading-snug line-clamp-2">
                            {{ $related->title }}
                        </h4>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $related->published_at ? $related->published_at->format('d M Y') : $related->created_at->format('d M Y') }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Recommended Products --}}
        @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
        <div class="mt-12 bg-green-50 rounded-2xl p-8 border border-green-100 reveal">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-shopping-bag text-green-600 mr-3"></i> Rekomendasi Produk
                    </h3>
                    <p class="text-gray-600 mt-2">Produk pilihan yang relevan dengan artikel ini.</p>
                </div>
                <a href="{{ route('produk.index') }}" class="hidden md:inline-flex items-center text-green-600 font-semibold hover:text-green-700 transition">
                    Lihat Semua <i class="fas fa-arrow-right ml-2 text-sm"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    if (!function_exists('highlightKeywords')) {
                        function highlightKeywords($text, $keywords) {
                            if (empty($keywords)) return e($text);
                            // Sort keywords by length desc so longer keywords matched first
                            $sortedKeywords = $keywords;
                            usort($sortedKeywords, function($a, $b) { return strlen($b) - strlen($a); });
                            $pattern = '/(' . implode('|', array_map(function($kw) { return preg_quote($kw, '/'); }, $sortedKeywords)) . ')/i';
                            return preg_replace($pattern, '<mark class="bg-yellow-200 text-yellow-900 px-1 rounded font-bold shadow-sm">$1</mark>', e($text));
                        }
                    }
                @endphp

                @foreach($recommendedProducts as $product)
                <div class="bg-white rounded-xl shadow-sm transition-all duration-300 overflow-hidden group flex flex-col h-full border border-gray-100 border-b-4 border-b-transparent hover:border-b-green-500 reveal stagger-{{ $loop->iteration > 6 ? 6 : $loop->iteration }} card-hover">
                    {{-- Product Image --}}
                    @php
                        $imgs = $product->images;
                        if(is_string($imgs)) $imgs = json_decode($imgs, true);
                        $firstImg = (!empty($imgs) && is_array($imgs)) ? $imgs[0] : null;
                    @endphp
                    <a href="{{ route('produk.show', $product->slug) }}" class="block relative aspect-square overflow-hidden bg-gray-50">
                        @if($firstImg)
                            <img src="{{ asset('storage/' . $firstImg) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 group-hover:scale-110 transition-transform duration-500">
                                <i class="fas fa-box text-5xl"></i>
                            </div>
                        @endif
                        
                        @if($product->isOnSale())
                            <div class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg z-10 animate-pulse">
                                Promo
                            </div>
                        @endif
                    </a>

                    {{-- Product Info --}}
                    <div class="p-5 flex flex-col flex-grow">
                        @if($product->category)
                            <div class="mb-2">
                                <span class="bg-green-50 text-green-700 px-2.5 py-1 rounded-md text-xs font-semibold tracking-wide uppercase">
                                    {{ $product->category->name ?? 'Kategori' }}
                                </span>
                            </div>
                        @endif

                        <a href="{{ route('produk.show', $product->slug) }}" class="block group-hover:text-green-600 transition-colors mb-2">
                            <h4 class="font-bold text-gray-800 text-lg line-clamp-2 leading-tight">
                                {!! highlightKeywords($product->name, $keywords) !!}
                            </h4>
                        </a>
                        
                        {{-- Short Description --}}
                        <p class="text-gray-500 text-sm line-clamp-2 mb-4 flex-grow">
                            {!! highlightKeywords($product->short_description ?? Str::limit(strip_tags($product->description), 100), $keywords) !!}
                        </p>

                        <div class="mt-auto flex items-end justify-between pt-4 border-t border-gray-50">
                            <div>
                                <span class="text-xs text-gray-400 block mb-1">Mulai dari</span>
                                @if($product->isOnSale())
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span class="font-black text-green-600 text-lg">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                    </div>
                                @else
                                    <span class="font-black text-green-600 text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-8 text-center md:hidden">
                <a href="{{ route('produk.index') }}" class="inline-flex items-center justify-center w-full bg-green-600 text-white font-semibold py-3 px-6 rounded-xl hover:bg-green-700 transition">
                    Lihat Semua Produk
                </a>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

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

        <article class="bg-white rounded-xl shadow-lg overflow-hidden">

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
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
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
                <div class="prose max-w-none">
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
        <div class="mt-10">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="w-1 h-6 bg-green-600 rounded mr-3"></span> Artikel Terkait
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedArticles as $related)
                <a href="{{ route('article.show', $related->slug) }}"
                   class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 group block">
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

    </div>
</div>
@endsection

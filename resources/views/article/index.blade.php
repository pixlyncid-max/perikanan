@extends('layouts.app')

@section('title', isset($category) ? 'Artikel Kategori ' . $category . ' - FISHERIES' : 'Artikel & Berita - FISHERIES')

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .article-card:hover .article-image img {
        transform: scale(1.05);
    }
    .article-image { overflow: hidden; }
    .article-image img { transition: transform 0.4s ease; }
    .pagination-active { background-color: #17A152; color: white; border-color: #17A152; }
</style>
@endpush

@section('content')


<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24 reveal-left">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-tags mr-2 text-green-600"></i> Kategori
                </h3>
                <div class="space-y-2">
                    {{-- All articles --}}
                    <a href="{{ route('article.index') }}"
                       class="flex items-center justify-between p-3 rounded-lg transition
                              {{ !isset($category) ? 'bg-green-600 text-white' : 'hover:bg-gray-50 text-gray-700' }}">
                        <span class="font-medium">Semua</span>
                        <span class="px-2 py-1 rounded-full text-xs
                                     {{ !isset($category) ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600' }}">
                            {{ $totalPublished ?? 0 }}
                        </span>
                    </a>

                    @if(isset($categories) && $categories->count() > 0)
                        @foreach($categories as $cat)
                        <a href="{{ route('article.category', $cat) }}"
                           class="flex items-center justify-between p-3 rounded-lg transition
                                  {{ (isset($category) && $category === $cat) ? 'bg-green-600 text-white' : 'hover:bg-gray-50 text-gray-700' }}">
                            <span class="font-medium">{{ $cat }}</span>
                            <span class="px-2 py-1 rounded-full text-xs
                                         {{ (isset($category) && $category === $cat) ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600' }}">
                                {{ $categoryCounts[$cat] ?? 0 }}
                            </span>
                        </a>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-500 text-center py-2">Belum ada kategori</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Article List --}}
        <div class="lg:col-span-3">
            @if($articles->count() > 0)
                <div class="space-y-6">
                    @foreach($articles as $article)
                    <article class="article-card bg-white rounded-xl shadow-lg overflow-hidden transition duration-300 reveal stagger-{{ $loop->iteration > 6 ? 6 : $loop->iteration }} card-hover">
                        <div class="md:flex">
                            {{-- Image --}}
                            <div class="article-image md:w-1/3 h-52 md:h-auto bg-gray-100 flex-shrink-0">
                                @if($article->featured_image)
                                    <img src="{{ asset('storage/' . $article->featured_image) }}"
                                         alt="{{ $article->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100 min-h-[13rem]">
                                        <i class="fas fa-newspaper text-5xl text-green-300"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-6 md:w-2/3 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center flex-wrap gap-2 mb-3">
                                        @if($article->category)
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                {{ $article->category }}
                                            </span>
                                        @endif
                                        <span class="text-gray-400 text-sm">
                                            <i class="far fa-calendar mr-1"></i>
                                            {{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}
                                        </span>
                                        <span class="text-gray-400 text-sm">
                                            <i class="far fa-eye mr-1"></i> {{ number_format($article->views) }}
                                        </span>
                                    </div>

                                    <h2 class="text-xl font-bold text-gray-800 mb-3 hover:text-green-600 transition leading-snug">
                                        <a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a>
                                    </h2>

                                    @if($article->excerpt)
                                        <p class="text-gray-600 mb-4 line-clamp-2 leading-relaxed">{{ $article->excerpt }}</p>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between mt-2">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2">
                                            <i class="fas fa-user text-green-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm text-gray-600">
                                            {{ $article->author?->name ?? 'Tim FISHERIES' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('article.show', $article->slug) }}"
                                       class="text-green-600 font-semibold hover:underline flex items-center gap-1 text-sm">
                                        Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8 flex justify-center">
                    {{ $articles->links() }}
                </div>

            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-xl shadow-lg p-16 text-center">
                    <i class="fas fa-newspaper text-6xl text-gray-200 mb-6"></i>
                    <h3 class="text-xl font-bold text-gray-600 mb-2">
                        @isset($category)
                            Belum ada artikel di kategori "{{ $category }}"
                        @else
                            Belum Ada Artikel
                        @endisset
                    </h3>
                    <p class="text-gray-400 mb-6">Artikel akan muncul di sini setelah dipublikasikan oleh admin.</p>
                    @isset($category)
                        <a href="{{ route('article.index') }}" class="inline-block px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Lihat Semua Artikel
                        </a>
                    @endisset
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

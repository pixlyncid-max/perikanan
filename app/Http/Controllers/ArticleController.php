<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::published()
            ->latest('published_at')
            ->paginate(9);

        $categories = Article::published()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

        $categoryCounts = Article::published()
            ->whereNotNull('category')
            ->groupBy('category')
            ->selectRaw('category, count(*) as total')
            ->pluck('total', 'category');

        $totalPublished = Article::published()->count();

        return view('article.index', compact('articles', 'categories', 'categoryCounts', 'totalPublished'));
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment views
        $article->increment('views');

        // Get related articles
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest()
            ->take(3)
            ->get();

        // If not enough related by category, fill with latest
        if ($relatedArticles->count() < 3) {
            $more = Article::published()
                ->where('id', '!=', $article->id)
                ->whereNotIn('id', $relatedArticles->pluck('id'))
                ->latest()
                ->take(3 - $relatedArticles->count())
                ->get();
            $relatedArticles = $relatedArticles->merge($more);
        }

        return view('article.show', compact('article', 'relatedArticles'));
    }

    public function byCategory($category)
    {
        $articles = Article::published()
            ->where('category', $category)
            ->latest('published_at')
            ->paginate(9);

        $categories = Article::published()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

        $categoryCounts = Article::published()
            ->whereNotNull('category')
            ->groupBy('category')
            ->selectRaw('category, count(*) as total')
            ->pluck('total', 'category');

        $totalPublished = Article::published()->count();

        return view('article.index', compact('articles', 'categories', 'categoryCounts', 'totalPublished', 'category'));
    }
}


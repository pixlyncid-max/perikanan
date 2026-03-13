<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('is_published', $request->get('status') === 'published');
        }

        $articles = $query->latest()->paginate(10);
        
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Parse tags from comma-separated string to array
        $tagsArray = null;
        if ($request->filled('tags')) {
            $tagsArray = array_map('trim', explode(',', $request->tags));
            $tagsArray = array_filter($tagsArray);
            $tagsArray = array_values($tagsArray);
        }

        $data = [
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'content' => $request->input('content'),
            'excerpt' => $request->input('excerpt') ?? Str::limit(strip_tags($request->input('content')), 150),
            'category' => $request->input('category') ?: null,
            'tags' => $tagsArray,
            'is_published' => $request->boolean('is_published', false),
            'published_at' => $request->has('is_published') ? ($request->input('published_at') ?? now()) : null,
        ];

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/articles', $imageName);
            $data['featured_image'] = 'articles/' . $imageName;
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Parse tags from comma-separated string to array
        $tagsArray = null;
        if ($request->filled('tags')) {
            $tagsArray = array_map('trim', explode(',', $request->tags));
            $tagsArray = array_filter($tagsArray);
            $tagsArray = array_values($tagsArray);
        }

        $data = [
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'content' => $request->input('content'),
            'excerpt' => $request->input('excerpt') ?? Str::limit(strip_tags($request->input('content')), 150),
            'category' => $request->input('category') ?: null,
            'tags' => $tagsArray,
            'is_published' => $request->boolean('is_published', false),
            'published_at' => $request->has('is_published') ? ($request->input('published_at') ?? $article->published_at ?? now()) : null,
        ];

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($article->featured_image && Storage::exists('public/' . $article->featured_image)) {
                Storage::delete('public/' . $article->featured_image);
            }
            
            $image = $request->file('featured_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/articles', $imageName);
            $data['featured_image'] = 'articles/' . $imageName;
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // Delete image if exists
        if ($article->featured_image && Storage::exists('public/' . $article->featured_image)) {
            Storage::delete('public/' . $article->featured_image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }
}
?>

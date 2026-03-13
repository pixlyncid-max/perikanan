<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::featured()->active()->latest()->take(8)->get();
        $latestArticles = Article::published()->latest()->take(6)->get();
        $categories = Category::active()->ordered()->get();
        
        return view('home', compact('featuredProducts', 'latestArticles', 'categories'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function partnership()
    {
        return view('partnership');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $categories = Category::active()->ordered()->get();
        return view('produk.index', compact('categories'));
    }

    public function peletPakan()
    {
        $category = Category::where('slug', 'pelet-pakan-ikan')->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->active()
            ->latest()
            ->paginate(12);
        
        return view('produk.pelet-pakan', compact('category', 'products'));
    }

    public function pakanHidup()
    {
        // Return static view without database query
        return view('produk.pakan-hidup');
    }


    public function umpanLaut()
    {
        $category = Category::where('slug', 'umpan-ikan-laut')->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->active()
            ->latest()
            ->paginate(12);
        
        return view('produk.umpan-laut', compact('category', 'products'));
    }

    public function penyewaanKapal()
    {
        // Return static view without database query
        return view('produk.penyewaan-kapal');
    }


    public function vitaminAir()
    {
        $category = Category::where('slug', 'vitamin-air')->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->active()
            ->latest()
            ->paginate(12);
        
        return view('produk.vitamin-air', compact('category', 'products'));
    }

    public function bibitIkan()
    {
        $category = Category::where('slug', 'bibit-ikan')->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->active()
            ->latest()
            ->paginate(12);
        
        return view('produk.bibit-ikan', compact('category', 'products'));
    }
}

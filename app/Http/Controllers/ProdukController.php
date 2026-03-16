<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()->ordered()->get();
        $query = Product::active();
        
        $products = $this->applyFilters($query, $request)->latest()->paginate(12);
        
        return view('produk.index', compact('categories', 'products'));
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('subcategory')) {
            $query->where('subcategory_id', $request->subcategory);
        }

        return $query;
    }

    public function peletPakan(Request $request)
    {
        $category = Category::where('slug', 'pelet-pakan-ikan')->with('subcategories')->firstOrFail();
        $query = Product::where('category_id', $category->id)->active();
        
        $products = $this->applyFilters($query, $request)->latest()->paginate(12);
        
        return view('produk.pelet-pakan', compact('category', 'products'));
    }

    public function pakanHidup(Request $request)
    {
        $category = Category::where('slug', 'pakan-hidup')->with('subcategories')->firstOrFail();
        $query = Product::where('category_id', $category->id)->active();
        
        $products = $this->applyFilters($query, $request)->latest()->paginate(12);
        
        return view('produk.pakan-hidup', compact('category', 'products'));
    }


    public function umpanLaut(Request $request)
    {
        $category = Category::where('slug', 'umpan-ikan-laut')->with('subcategories')->firstOrFail();
        $query = Product::where('category_id', $category->id)->active();
        
        $products = $this->applyFilters($query, $request)->latest()->paginate(12);
        
        return view('produk.umpan-laut', compact('category', 'products'));
    }

    public function penyewaanKapal(Request $request)
    {
        $category = Category::where('slug', 'penyewaan-kapal')->with('subcategories')->firstOrFail();
        $query = Product::where('category_id', $category->id)->active();
        
        $products = $this->applyFilters($query, $request)->latest()->paginate(12);
        
        return view('produk.penyewaan-kapal', compact('category', 'products'));
    }


    public function vitaminAir(Request $request)
    {
        $category = Category::where('slug', 'vitamin-air')->with('subcategories')->firstOrFail();
        $query = Product::where('category_id', $category->id)->active();
        
        $products = $this->applyFilters($query, $request)->latest()->paginate(12);
        
        return view('produk.vitamin-air', compact('category', 'products'));
    }

    public function bibitIkan(Request $request)
    {
        $category = Category::where('slug', 'bibit-ikan')->with('subcategories')->firstOrFail();
        $query = Product::where('category_id', $category->id)->active();
        
        $products = $this->applyFilters($query, $request)->latest()->paginate(12);
        
        return view('produk.bibit-ikan', compact('category', 'products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->active()->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->limit(4)
            ->get();
            
        return view('produk.show', compact('product', 'relatedProducts'));
    }
}

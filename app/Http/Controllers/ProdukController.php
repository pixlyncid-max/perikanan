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

    public function sewaPancing(Request $request)
    {
        $category = Category::where('slug', 'sewa-pancing')->with('subcategories')->first();
        if (!$category) {
            return view('produk.sewa-pancing', ['category' => (object)['name' => 'Sewa Pancing', 'description' => 'Layanan penyewaan alat pancing air tawar'], 'products' => collect([])]);
        }
        $query = Product::where('category_id', $category->id)->active();
        $products = $this->applyFilters($query, $request)->latest()->paginate(12);
        return view('produk.sewa-pancing', compact('category', 'products'));
    }

    public function kolamPemancingan(Request $request)
    {
        $category = Category::where('slug', 'kolam-pemancingan')->with('subcategories')->first();
        if (!$category) {
            return view('produk.kolam-pemancingan', ['category' => (object)['name' => 'Kolam Pemancingan', 'description' => 'Layanan akses kolam pemancingan'], 'products' => collect([])]);
        }
        $query = Product::where('category_id', $category->id)->active();
        $products = $this->applyFilters($query, $request)->latest()->paginate(12);
        return view('produk.kolam-pemancingan', compact('category', 'products'));
    }

    public function komunitasAirTawar(Request $request)
    {
        $category = Category::where('slug', 'komunitas-air-tawar')->with('subcategories')->first();
        if (!$category) {
            return view('produk.komunitas-air-tawar', ['category' => (object)['name' => 'Komunitas Air Tawar', 'description' => 'Layanan pendaftaran komunitas air tawar'], 'products' => collect([])]);
        }
        $query = Product::where('category_id', $category->id)->active();
        $products = $this->applyFilters($query, $request)->latest()->paginate(12);
        return view('produk.komunitas-air-tawar', compact('category', 'products'));
    }

    public function sewaPancingLaut(Request $request)
    {
        $category = Category::where('slug', 'sewa-pancing-laut')->with('subcategories')->first();
        if (!$category) {
            return view('produk.sewa-pancing-laut', ['category' => (object)['name' => 'Sewa Pancing Laut', 'description' => 'Layanan penyewaan alat pancing air laut'], 'products' => collect([])]);
        }
        $query = Product::where('category_id', $category->id)->active();
        $products = $this->applyFilters($query, $request)->latest()->paginate(12);
        return view('produk.sewa-pancing-laut', compact('category', 'products'));
    }

    public function komunitasAirLaut(Request $request)
    {
        $category = Category::where('slug', 'komunitas-air-laut')->with('subcategories')->first();
        if (!$category) {
            return view('produk.komunitas-air-laut', ['category' => (object)['name' => 'Komunitas Air Laut', 'description' => 'Layanan pendaftaran komunitas air laut'], 'products' => collect([])]);
        }
        $query = Product::where('category_id', $category->id)->active();
        $products = $this->applyFilters($query, $request)->latest()->paginate(12);
        return view('produk.komunitas-air-laut', compact('category', 'products'));
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

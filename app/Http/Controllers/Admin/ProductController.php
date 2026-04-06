<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->get('category'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'active' ? 1 : 0);
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('featured', $request->get('featured') === '1' ? 1 : 0);
        }

        $products = $query->orderBy('id', 'asc')->paginate(15);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $locations = Location::orderBy('nama', 'asc')->get();
        return view('admin.products.create', compact('categories', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'category_id'       => 'nullable|exists:categories,id',
            'price'             => 'required|numeric|min:0',
            'member_price'      => 'nullable|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'sku'               => 'nullable|string|max:100|unique:products,sku',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'variations.*.type' => 'required_with:variations|string|max:50',
            'variations.*.name' => 'required_with:variations|string|max:100',
            'variations.*.price_adjustment' => 'nullable|numeric|min:0',
            'variations.*.stock' => 'nullable|integer|min:0',
            'variations.*.is_stock_synced' => 'nullable|boolean',
            'variations.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'variations.*.description' => 'nullable|string',
            'locations' => 'nullable|array',
            'locations.*' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $slug = Str::slug($request->name);
        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $data = [
            'name'              => $request->name,
            'slug'              => $slug,
            'category_id'       => $request->category_id,
            'price'             => $request->price,
            'member_price'      => $request->member_price ?? 0,
            'sale_price'        => $request->sale_price ?? 0,
            'stock'             => $request->stock,
            'sku'               => $request->sku,
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'featured'          => $request->has('featured') ? 1 : 0,
            'is_active'         => $request->has('is_active') ? 1 : 0,
            'meta_title'        => $request->meta_title,
            'meta_description'  => $request->meta_description,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $imageName);
            $data['images'] = json_encode(['products/' . $imageName]);
        }

        $product = Product::create($data);

        // Handle variations
        if ($request->has('variations') && is_array($request->variations)) {
            foreach ($request->variations as $index => $var) {
                if (!empty($var['type']) && !empty($var['name'])) {
                    $varData = [
                        'type' => $var['type'],
                        'name' => $var['name'],
                        'price_adjustment' => $var['price_adjustment'] ?? 0,
                        'stock' => $var['stock'] ?? 0,
                        'is_stock_synced' => (isset($var['is_stock_synced']) && $var['is_stock_synced'] == 1) ? true : false,
                        'description' => $var['description'] ?? null,
                    ];

                    // Handle variation image
                    if ($request->hasFile("variations.{$index}.image")) {
                        $vImage = $request->file("variations.{$index}.image");
                        $vImageName = time() . '_v_' . Str::random(5) . '.' . $vImage->getClientOriginalExtension();
                        $vImage->storeAs('public/variations', $vImageName);
                        $varData['image'] = 'variations/' . $vImageName;
                    }

                    $product->variations()->create($varData);
                }
            }
        }

        // Handle locations
        if ($request->has('locations') && is_array($request->locations)) {
            $syncData = [];
            foreach ($request->locations as $locId => $locStock) {
                if ($locStock !== null && $locStock !== '') {
                    $syncData[$locId] = ['stok' => (int)$locStock];
                }
            }
            $product->locations()->sync($syncData);
            $product->syncStock();
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $locations = Location::orderBy('nama', 'asc')->get();
        // pre-load locations pivot
        $product->load('locations');
        return view('admin.products.edit', compact('product', 'categories', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'category_id'       => 'nullable|exists:categories,id',
            'price'             => 'required|numeric|min:0',
            'member_price'      => 'nullable|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'sku'               => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'featured'          => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'variations'        => 'nullable|array',
            'variations.*.type' => 'required_with:variations|string|max:50',
            'variations.*.name' => 'required_with:variations|string|max:100',
            'variations.*.price_adjustment' => 'nullable|numeric|min:0',
            'variations.*.stock' => 'nullable|integer|min:0',
            'variations.*.is_stock_synced' => 'nullable|boolean',
            'variations.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'variations.*.description' => 'nullable|string',
            'locations'         => 'nullable|array',
            'locations.*'       => 'nullable|integer|min:0', // Validating stock per location
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $slug = Str::slug($request->name);
        // Ensure unique slug (exclude current product)
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $data = [
            'name'              => $request->name,
            'slug'              => $slug,
            'category_id'       => $request->category_id,
            'price'             => $request->price,
            'member_price'      => $request->member_price ?? 0,
            'sale_price'        => $request->sale_price ?? 0,
            'stock'             => $request->stock,
            'sku'               => $request->sku,
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'featured'          => $request->has('featured') ? 1 : 0,
            'is_active'         => $request->has('is_active') ? 1 : 0,
            'meta_title'        => $request->meta_title,
            'meta_description'  => $request->meta_description,
        ];

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old images if exists
            $oldImages = $product->images ?? [];
            if (is_string($oldImages)) {
                $oldImages = json_decode($oldImages, true) ?? [];
            }
            foreach ($oldImages as $oldImg) {
                if (Storage::exists('public/' . $oldImg)) {
                    Storage::delete('public/' . $oldImg);
                }
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $imageName);
            $data['images'] = json_encode(['products/' . $imageName]);
        }

        $product->update($data);

        // Logic update: Keep existing variations if possible to preserve images
        $existingVarIds = [];
        if ($request->has('variations') && is_array($request->variations)) {
            foreach ($request->variations as $index => $var) {
                if (!empty($var['type']) && !empty($var['name'])) {
                    $varData = [
                        'type' => $var['type'],
                        'name' => $var['name'],
                        'price_adjustment' => $var['price_adjustment'] ?? 0,
                        'stock' => $var['stock'] ?? 0,
                        'is_stock_synced' => (isset($var['is_stock_synced']) && $var['is_stock_synced'] == 1) ? true : false,
                        'description' => $var['description'] ?? null,
                    ];

                    // Handle variation image
                    if ($request->hasFile("variations.{$index}.image")) {
                        $vImage = $request->file("variations.{$index}.image");
                        $vImageName = time() . '_v_' . Str::random(5) . '.' . $vImage->getClientOriginalExtension();
                        $vImage->storeAs('public/variations', $vImageName);
                        $varData['image'] = 'variations/' . $vImageName;
                    }

                    if (isset($var['id']) && !empty($var['id'])) {
                        $variation = $product->variations()->find($var['id']);
                        if ($variation) {
                            // Delete old image if new one uploaded
                            if (isset($varData['image']) && $variation->image) {
                                Storage::delete('public/' . $variation->image);
                            }
                            $variation->update($varData);
                            $existingVarIds[] = $variation->id;
                        }
                    } else {
                        $newVar = $product->variations()->create($varData);
                        $existingVarIds[] = $newVar->id;
                    }
                }
            }
        }
        
        // Delete variations that were removed
        $toDelete = $product->variations()->whereNotIn('id', $existingVarIds)->get();
        foreach ($toDelete as $td) {
            if ($td->image) {
                Storage::delete('public/' . $td->image);
            }
            $td->delete();
        }

        // Sync locations
        if ($request->has('locations') && is_array($request->locations)) {
            $syncData = [];
            foreach ($request->locations as $locId => $locStock) {
                if ($locStock !== null && $locStock !== '') {
                    $syncData[$locId] = ['stok' => (int)$locStock];
                }
            }
            $product->locations()->sync($syncData);
        } else {
             $product->locations()->sync([]); // remove all if none
        }

        // Final sync of main stock field
        $product->syncStock();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete images if exists
        $images = $product->images ?? [];
        if (is_string($images)) {
            $images = json_decode($images, true) ?? [];
        }
        foreach ($images as $img) {
            if (Storage::exists('public/' . $img)) {
                Storage::delete('public/' . $img);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Toggle product active status
     */
    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Produk berhasil {$status}.");
    }

    /**
     * Toggle product featured status
     */
    public function toggleFeatured(Product $product)
    {
        $product->update(['featured' => !$product->featured]);
        $status = $product->featured ? 'ditandai unggulan' : 'dihapus dari unggulan';
        return redirect()->back()->with('success', "Produk berhasil {$status}.");
    }
    /**
     * Generate SKU based on category.
     */
    public function generateSku(Request $request)
    {
        $categoryId = $request->get('category_id');
        $prefix = 'PRD';

        if ($categoryId) {
            $category = Category::find($categoryId);
            if ($category) {
                // Determine prefix from category name: e.g., "Bibit Ikan" -> "BIB"
                $cleanName = preg_replace('/[^A-Za-z0-9]/', '', $category->name);
                $prefix = strtoupper(substr($cleanName, 0, 3));
                if (strlen($prefix) < 3) {
                    $prefix = str_pad($prefix, 3, 'X');
                }
            }
        }

        // Find the latest product with this prefix
        $latestProduct = Product::where('sku', 'like', $prefix . '-%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($latestProduct && $latestProduct->sku) {
            // Extract the number part
            $parts = explode('-', $latestProduct->sku);
            if (count($parts) > 1 && is_numeric(end($parts))) {
                $nextNumber = intval(end($parts)) + 1;
            }
        }

        $sku = $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Ensure it's absolutely unique
        while (Product::where('sku', $sku)->exists()) {
            $nextNumber++;
            $sku = $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        return response()->json(['sku' => $sku]);
    }

    /**
     * Handle mass actions for products (Delete, Activate, Deactivate).
     */
    public function massAction(Request $request)
    {
        $action = $request->input('action');
        $productIds = $request->input('product_ids');

        if (!$productIds || !is_array($productIds) || empty($productIds)) {
            return redirect()->route('admin.products.index')->with('error', 'Pilih minimal satu produk untuk menerapkan aksi.');
        }

        switch ($action) {
            case 'delete':
                // Get all images associated with the products to delete
                $products = Product::whereIn('id', $productIds)->get();
                foreach ($products as $product) {
                    $images = $product->images;
                    if (is_string($images)) $images = json_decode($images, true) ?? [];
                    if (is_array($images)) {
                        foreach ($images as $img) {
                            if (Storage::exists('public/' . $img)) {
                                Storage::delete('public/' . $img);
                            }
                        }
                    }
                }
                
                Product::whereIn('id', $productIds)->delete();
                return redirect()->route('admin.products.index')->with('success', count($productIds) . ' produk berhasil dihapus secara massal.');

            case 'activate':
                Product::whereIn('id', $productIds)->update(['is_active' => 1]);
                return redirect()->route('admin.products.index')->with('success', count($productIds) . ' produk berhasil diaktifkan massal.');

            case 'deactivate':
                Product::whereIn('id', $productIds)->update(['is_active' => 0]);
                return redirect()->route('admin.products.index')->with('success', count($productIds) . ' produk berhasil dinonaktifkan massal.');

            default:
                return redirect()->route('admin.products.index')->with('error', 'Aksi massal tidak valid.');
        }
    }

    /**
     * Download the Excel template for bulk importing products.
     */
    public function downloadTemplate()
    {
        return Excel::download(new \App\Exports\ProductsTemplateExport, 'template_import_produk.xlsx');
    }

    /**
     * Import products from an Excel/CSV file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Produk berhasil diimpor dari file Excel!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor produk: ' . $e->getMessage());
        }
    }
}

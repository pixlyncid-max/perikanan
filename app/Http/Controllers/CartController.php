<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Add an item to the cart.
     */
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $variationId = $request->input('variation_id');
        $quantity = $request->input('quantity', 1);

        $product = Product::findOrFail($productId);
        $variation = $variationId ? \App\Models\ProductVariation::find($variationId) : null;
        
        // Get user role from session safely
        $userSession = Session::get('user', []);
        $userRole = $userSession['type'] ?? 'user';
        
        $basePrice = $product->getPriceForUser($userRole);
        $price = $basePrice;

        if ($variation) {
            $price += $variation->price_adjustment;
        }

        $cart = Session::get('cart', []);
        
        // Unique key for cart item to support multiple variations of the same product
        $cartKey = $variationId ? "{$productId}-{$variationId}" : $productId;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $imgs = $product->images;
            if(is_string($imgs)) $imgs = json_decode($imgs, true);
            $firstImg = (!empty($imgs) && is_array($imgs)) ? $imgs[0] : null;

            $cart[$cartKey] = [
                'product_id' => $productId,
                'variation_id' => $variationId,
                'name' => $product->name,
                'variation_name' => $variation ? $variation->name : null,
                'variation_type' => $variation ? $variation->type : null,
                'quantity' => $quantity,
                'price' => $price,
                'image' => $firstImg,
                'slug' => $product->slug,
            ];
        }

        Session::put('cart', $cart);
        Session::save(); // Ensure session is saved before response

        // Sync to database if logged in
        $this->syncToDatabase($cart);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cart_count' => array_sum(array_column($cart, 'quantity'))
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update item quantity in the cart.
     */
    public function update(Request $request)
    {
        $cartId = $request->input('cart_id');
        $quantity = $request->input('quantity');

        $cart = Session::get('cart', []);

        if (isset($cart[$cartId])) {
            $cart[$cartId]['quantity'] = max(1, (int)$quantity);
            Session::put('cart', $cart);
            Session::save();
            $this->syncToDatabase($cart);
        }

        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui!');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request)
    {
        $cartId = $request->input('cart_id');
        $cart = Session::get('cart', []);

        if (isset($cart[$cartId])) {
            unset($cart[$cartId]);
            Session::put('cart', $cart);
            Session::save();
            $this->syncToDatabase($cart);
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    /**
     * Helper to sync session cart to database for authenticated users.
     */
    private function syncToDatabase($cart)
    {
        if (Session::has('user')) {
            $user = Session::get('user');
            \App\Models\ShoppingCart::updateOrCreate(
                ['user_id' => $user['id'], 'user_type' => $user['type']],
                ['cart_data' => json_encode($cart)]
            );
        }
    }
}

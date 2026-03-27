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
        $quantity = $request->input('quantity', 1);

        $product = Product::findOrFail($productId);
        
        // Get user role from session safely
        $userSession = Session::get('user', []);
        $userRole = $userSession['type'] ?? 'user';
        
        $price = $product->getPriceForUser($userRole);

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $imgs = $product->images;
            if(is_string($imgs)) $imgs = json_decode($imgs, true);
            $firstImg = (!empty($imgs) && is_array($imgs)) ? $imgs[0] : null;

            $cart[$productId] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $price,
                'image' => $firstImg,
                'slug' => $product->slug,
            ];
        }

        Session::put('cart', $cart);
        Session::save(); // Ensure session is saved before response

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
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = max(1, (int)$quantity);
            Session::put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui!');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }
}

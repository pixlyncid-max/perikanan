<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutPageController extends Controller
{
    /**
     * Show the custom checkout page.
     * Cart must not be empty, user must be logged in.
     */
    public function index(Request $request)
    {
        $userSession = Session::get('user');
        if (!$userSession) {
            return redirect(route('login'))->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout.');
        }

        $allCart = Session::get('cart', []);
        
        // Priority: 1. Query parameter (legacy/direct links), 2. Session (clean links)
        $selectedIds = $request->query('items') ?? Session::get('checkout_selected_items');

        if (!$selectedIds) {
            return redirect(route('cart.index'))->with('error', 'Silakan pilih produk yang ingin di-checkout.');
        }

        $selectedIdsArray = explode(',', $selectedIds);
        $cart = [];
        $subtotal = 0;

        foreach ($selectedIdsArray as $id) {
            if (isset($allCart[$id])) {
                $cart[$id] = $allCart[$id];
                $subtotal += $allCart[$id]['price'] * $allCart[$id]['quantity'];
            }
        }

        if (empty($cart)) {
            return redirect(route('cart.index'))->with('error', 'Produk yang dipilih tidak valid atau sudah tidak ada di keranjang.');
        }

        // Get user detail for pre-fill
        $user = null;
        if ($userSession['type'] === 'admin') {
            $user = \App\Models\Admin::find($userSession['id']);
        } elseif ($userSession['type'] === 'member') {
            $user = \App\Models\Member::find($userSession['id']);
        } else {
            $user = \App\Models\User::find($userSession['id']);
        }

        return view('checkout.index', compact('cart', 'subtotal', 'user', 'userSession'));
    }

    /**
     * Store selected items for checkout in session (Clean URL Support)
     */
    public function setItems(Request $request)
    {
        $items = $request->input('items');
        
        if (empty($items)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada produk yang dipilih.'], 400);
        }

        Session::put('checkout_selected_items', $items);
        Session::save();

        return response()->json([
            'success' => true,
            'redirect_url' => route('checkout.index')
        ]);
    }
}

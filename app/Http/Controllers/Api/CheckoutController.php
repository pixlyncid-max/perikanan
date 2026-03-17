<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class CheckoutController extends Controller
{
    protected XenditService $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Handle the checkout process.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total' => 'required|numeric',
            'address' => 'required|string',
            'shipping_cost' => 'required|numeric',
        ]);

        $userSession = session('user');
        if (!$userSession) {
            return response()->json(['message' => 'Sesi login berakhir atau belum login. Silakan login kembali.'], 401);
        }

        $user = null;
        if ($userSession['type'] === 'admin') {
            $user = \App\Models\Admin::find($userSession['id']);
        } elseif ($userSession['type'] === 'member') {
            $user = \App\Models\Member::find($userSession['id']);
        } else {
            $user = \App\Models\User::find($userSession['id']);
        }

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        try {
            return DB::transaction(function () use ($request, $user, $userSession) {
                $items = $request->items;
                $recalculatedTotal = 0;
                $orderItemsData = [];

                foreach ($items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $price = $product->getPriceForUser($userSession['type']);
                    $subtotal = $price * $item['quantity'];
                    
                    $recalculatedTotal += $subtotal;
                    
                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $item['quantity'],
                        'unit_price' => $price,
                        'subtotal' => $subtotal,
                    ];
                }

                $recalculatedTotal += $request->shipping_cost;

                // Create Order
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => (string) Str::uuid(),
                    'status' => 'pending',
                    'total_amount' => $recalculatedTotal,
                    'shipping_address' => $request->address,
                    'shipping_cost' => $request->shipping_cost,
                    'payment_method' => 'xendit',
                    'payment_status' => 'pending',
                ]);

                // Create Order Items
                foreach ($orderItemsData as $itemData) {
                    $order->items()->create($itemData);
                }

                // Create Xendit Invoice
                $xenditResponse = $this->xenditService->createInvoice($order, $user->email);

                // Update Order with payment URL
                $order->update([
                    'payment_url' => $xenditResponse['invoice_url'],
                ]);

                // Clear Cart
                session()->forget('cart');

                return response()->json([
                    'order_number' => $order->order_number,
                    'payment_url' => $order->payment_url,
                ], 201);
            });
        } catch (Exception $e) {
            Log::error('Checkout Failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'request' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Gagal memproses checkout: ' . $e->getMessage(),
            ], 500);
        }
    }
}

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
     * Constant groupings for payment channels.
     */
    const VA_BANKS = ['BCA', 'BNI', 'BRI', 'MANDIRI', 'PERMATA', 'BSI', 'CIMB', 'SAHABAT_SAMPOERNA', 'BJB', 'MUAMALAT', 'BNC'];
    const EWALLETS = ['ID_OVO', 'ID_DANA', 'ID_LINKAJA', 'ID_SHOPEEPAY'];
    const RETAIL   = ['ALFAMART', 'INDOMARET'];
    const QRIS     = 'QRIS';

    /**
     * Handle the checkout process.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items'           => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'total'           => 'required|numeric',
            'address'         => 'required|string',
            'shipping_cost'   => 'required|numeric',
            'payment_channel' => 'required|string',
            'payer_name'      => 'required|string|max:255',
            'payer_phone'     => 'nullable|string', // Some e-wallets might need this
            'location_id'     => 'required|exists:locations,id',
        ]);

        $userSession = session('user');
        if (!$userSession) {
            return response()->json(['message' => 'Sesi login berakhir.'], 401);
        }

        $user = null;
        if ($userSession['type'] === 'admin') {
            $user = \App\Models\Admin::find($userSession['id']);
        } elseif ($userSession['type'] === 'member') {
            $user = \App\Models\Member::find($userSession['id']);
        } else {
            $user = \App\Models\User::find($userSession['id']);
        }

        $channelUpper = strtoupper($request->payment_channel);

        try {
            return DB::transaction(function () use ($request, $user, $userSession, $channelUpper) {
                // ── Recalculate total ──
                $items               = $request->items;
                $recalculatedSubtotal = 0;
                $orderItemsData      = [];

                foreach ($items as $item) {
                    $product  = Product::findOrFail($item['product_id']);
                    $price    = $product->getPriceForUser($userSession['type'] ?? 'user');
                    $subtotal = $price * $item['quantity'];
                    $recalculatedSubtotal += $subtotal;

                    $orderItemsData[] = [
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'quantity'     => $item['quantity'],
                        'unit_price'   => $price,
                        'subtotal'     => $subtotal,
                    ];
                }

                $recalculatedTotal = $recalculatedSubtotal + (float) $request->shipping_cost;

                // ── Create Order ──
                $order = Order::create([
                    'user_id'          => $user->id,
                    'order_number'     => (string) Str::uuid(),
                    'status'           => 'pending',
                    'total_amount'     => $recalculatedTotal,
                    'shipping_address' => $request->address,
                    'shipping_cost'    => $request->shipping_cost,
                    'payment_method'   => 'xendit',
                    'payment_channel'  => $channelUpper,
                    'payment_status'   => 'pending',
                    'location_id'      => $request->location_id,
                ]);

                foreach ($orderItemsData as $itemData) {
                    $order->items()->create($itemData);

                    // Decrease stock from the chosen location
                    DB::table('product_locations')
                        ->where('product_id', $itemData['product_id'])
                        ->where('location_id', $request->location_id)
                        ->decrement('stok', $itemData['quantity']);
                }

                // ── Route to Xendit API ──
                if ($channelUpper === self::QRIS) {
                    return $this->processQris($order);
                } elseif (in_array($channelUpper, self::VA_BANKS)) {
                    return $this->processVirtualAccount($order, $channelUpper, $request->payer_name);
                } elseif (in_array($channelUpper, self::EWALLETS)) {
                    return $this->processEWallet($order, $channelUpper);
                } elseif (in_array($channelUpper, self::RETAIL)) {
                    return $this->processRetailOutlet($order, $channelUpper, $request->payer_name);
                } else {
                    throw new Exception('Metode pembayaran tidak didukung: ' . $channelUpper);
                }
            });
        } catch (Exception $e) {
            Log::error('Checkout Error', ['msg' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    private function processVirtualAccount(Order $order, string $bankCode, string $name)
    {
        $va = $this->xenditService->createVirtualAccount($order, $bankCode, $name);
        $order->update([
            'payment_code' => $va['account_number'],
            'payment_expires_at' => isset($va['expiration_date']) ? \Carbon\Carbon::parse($va['expiration_date']) : now()->addHours(24)
        ]);
        session()->forget('cart');
        return response()->json(['type' => 'va', 'order_number' => $order->order_number, 'code' => $va['account_number'], 'bank' => $bankCode, 'amount' => $order->total_amount]);
    }

    private function processQris(Order $order)
    {
        $qris = $this->xenditService->createQrisCharge($order);
        $order->update([
            'payment_code' => $qris['qr_string'],
            'payment_expires_at' => isset($qris['expires_at']) ? \Carbon\Carbon::parse($qris['expires_at']) : now()->addHour()
        ]);
        session()->forget('cart');
        return response()->json(['type' => 'qris', 'order_number' => $order->order_number, 'qr_string' => $qris['qr_string'], 'amount' => $order->total_amount]);
    }

    private function processEWallet(Order $order, string $channel)
    {
        $charge = $this->xenditService->createEWalletCharge($order, $channel);
        
        // E-wallet response structure: actions[0].url is usually the link to pay
        $paymentUrl = null;
        if (isset($charge['actions'])) {
            foreach ($charge['actions'] as $action) {
                if ($action['url_type'] === 'DEEPLINK' || $action['url_type'] === 'CHECKOUT_URL') {
                    $paymentUrl = $action['url'];
                    break;
                }
            }
        }

        $order->update(['payment_url' => $paymentUrl]);
        session()->forget('cart');

        return response()->json([
            'type' => 'ewallet', 
            'order_number' => $order->order_number, 
            'payment_url' => $paymentUrl,
            'channel' => str_replace('ID_', '', $channel)
        ]);
    }

    private function processRetailOutlet(Order $order, string $channel, string $name)
    {
        $retail = $this->xenditService->createRetailOutletCode($order, $channel, $name);
        $order->update([
            'payment_code' => $retail['payment_code'],
            'payment_expires_at' => isset($retail['expires_at']) ? \Carbon\Carbon::parse($retail['expires_at']) : now()->addHours(48)
        ]);
        session()->forget('cart');
        return response()->json(['type' => 'retail', 'order_number' => $order->order_number, 'code' => $retail['payment_code'], 'channel' => $channel, 'amount' => $order->total_amount]);
    }
}

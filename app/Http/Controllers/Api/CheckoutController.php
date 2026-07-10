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
    const VA_BANKS    = ['BCA', 'BNI', 'BRI', 'MANDIRI', 'PERMATA', 'BSI', 'CIMB', 'SAHABAT_SAMPOERNA', 'BJB', 'MUAMALAT', 'BNC'];
    const EWALLETS    = ['ID_OVO', 'ID_DANA', 'ID_LINKAJA', 'ID_SHOPEEPAY'];
    const RETAIL      = ['ALFAMART', 'INDOMARET'];
    const DIRECT_DEBIT = ['BRI', 'BCA', 'BNI'];
    const PAYLATER    = ['KREDIVO', 'AKULAKU', 'UANGME'];
    const CREDIT_CARD = 'CREDIT_CARD';
    const QRIS        = 'QRIS';

    /**
     * Handle the checkout process.
     */
    public function store(Request $request)
    {
        Log::debug('CheckoutController@store reached', [
            'request' => $request->all(),
            'user_id' => session('user_id'),
            'type'    => session('user_type')
        ]);
        
        $request->validate([
            'items'           => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variation_id' => 'nullable|exists:product_variations,id',
            'items.*.variation_name' => 'nullable|string',
            'items.*.quantity'   => 'required|integer|min:1',
            'total'           => 'required|numeric',
            'address'         => 'required|string',
            'shipping_cost'   => 'required|numeric',
            'payment_channel' => 'required|string',
            'payer_name'      => 'required|string|max:255',
            'payer_phone'     => 'nullable|string',
            'location_id'     => 'required|exists:locations,id',
            'token_id'        => 'nullable|string', // For Credit Card
            'auth_id'         => 'nullable|string', // For Credit Card 3DS
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
                    $variationId = $item['variation_id'] ?? null;
                    $variation = $variationId ? \App\Models\ProductVariation::find($variationId) : null;
                    
                    $price    = $product->getPriceForUser($userSession['type'] ?? 'user');
                    if ($variation) {
                        $price += $variation->price_adjustment;
                    }

                    $subtotal = $price * $item['quantity'];
                    $recalculatedSubtotal += $subtotal;

                    $orderItemsData[] = [
                        'product_id'     => $product->id,
                        'variation_id'   => $variationId,
                        'variation_name' => $item['variation_name'] ?? ($variation ? $variation->name : null),
                        'product_name'   => $product->name,
                        'quantity'       => $item['quantity'],
                        'unit_price'     => $price,
                        'subtotal'       => $subtotal,
                    ];
                }

                $recalculatedTotal = $recalculatedSubtotal + (float) $request->shipping_cost;

                // ── Create Order ──
                $order = Order::create([
                    'user_id'          => $user->id,
                    'user_type'        => get_class($user),
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
                    $orderItem = $order->items()->create($itemData);

                    // Decrease stock from the chosen location
                    DB::table('product_locations')
                        ->where('product_id', $itemData['product_id'])
                        ->where('location_id', $request->location_id)
                        ->decrement('stok', $itemData['quantity']);

                    // Decrease stock from the specific variation if applicable
                    if (!empty($itemData['variation_id'])) {
                        DB::table('product_variations')
                            ->where('id', $itemData['variation_id'])
                            ->decrement('stock', $itemData['quantity']);
                    }

                    // Sync main product stock
                    $product = Product::find($itemData['product_id']);
                    if ($product) {
                        $product->syncStock();
                    }
                }

                // ── Bypass Xendit and use WhatsApp ──
                if ($channelUpper === 'WHATSAPP') {
                    $order->update([
                        'payment_method' => 'whatsapp',
                        'payment_channel' => 'WHATSAPP'
                    ]);

                    session()->forget('cart');

                    $waNumber = get_setting('whatsapp_number', '6281234567890');
                    $waNumber = preg_replace('/[^0-9]/', '', $waNumber);
                    if (strpos($waNumber, '0') === 0) {
                        $waNumber = '62' . substr($waNumber, 1);
                    }

                    $waMessage = "*Pesanan Baru - FISHERIES*\n";
                    $waMessage .= "---------------------------------------\n";
                    $waMessage .= "*No Pesanan:* {$order->order_number}\n";
                    $waMessage .= "*Nama:* {$request->payer_name}\n";
                    $waMessage .= "*Telepon:* {$request->payer_phone}\n";
                    
                    $waMessage .= "\n*Alamat Pengiriman:*\n{$request->address}\n\n";
                    $waMessage .= "*Detail Pesanan:*\n";
                    
                    foreach ($order->items as $item) {
                        $productName = $item->product ? $item->product->title : 'Produk';
                        $waMessage .= "- {$productName} (x{$item->quantity}) : Rp " . number_format($item->subtotal, 0, ',', '.') . "\n";
                    }
                    
                    $waMessage .= "---------------------------------------\n";
                    $waMessage .= "*Total:* Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n";
                    $waMessage .= "Halo admin, saya ingin melanjutkan proses pembayaran untuk pesanan ini.";
                    
                    $url = "https://wa.me/{$waNumber}?text=" . urlencode($waMessage);

                    return response()->json([
                        'type' => 'whatsapp',
                        'order_number' => $order->order_number,
                        'url' => $url,
                        'amount' => $order->total_amount
                    ]);
                }

                // ── Route to Xendit API ──
                if ($channelUpper === self::QRIS) {
                    return $this->processQris($order);
                } elseif (in_array($channelUpper, self::VA_BANKS)) {
                    return $this->processVirtualAccount($order, $channelUpper, $request->payer_name);
                } elseif (in_array($channelUpper, self::EWALLETS)) {
                    return $this->processEWallet($order, $channelUpper, $request->payer_phone);
                } elseif (in_array($channelUpper, self::RETAIL)) {
                    return $this->processRetailOutlet($order, $channelUpper, $request->payer_name);
                } elseif (in_array($channelUpper, self::DIRECT_DEBIT)) {
                    return $this->processDirectDebit($order, $channelUpper);
                } elseif (in_array($channelUpper, self::PAYLATER)) {
                    return $this->processPaylater($order, $channelUpper);
                } elseif ($channelUpper === self::CREDIT_CARD) {
                    return $this->processCreditCard($order, $request->token_id, $request->auth_id);
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

    private function processEWallet(Order $order, string $channel, ?string $payerPhone = null)
    {
        $charge = $this->xenditService->createEWalletCharge($order, $channel, $payerPhone);
        
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

    private function processDirectDebit(Order $order, string $channel)
    {
        $charge = $this->xenditService->createDirectDebitCharge($order, $channel);
        $paymentUrl = $charge['actions'][0]['url'] ?? null;
        $order->update(['payment_url' => $paymentUrl]);
        session()->forget('cart');
        return response()->json(['type' => 'direct_debit', 'order_number' => $order->order_number, 'payment_url' => $paymentUrl]);
    }

    private function processPaylater(Order $order, string $channel)
    {
        $charge = $this->xenditService->createPaylaterCharge($order, $channel);
        $paymentUrl = null;
        if (isset($charge['actions'])) {
            foreach ($charge['actions'] as $action) {
                if ($action['url_type'] === 'CHECKOUT_URL') {
                    $paymentUrl = $action['url'];
                    break;
                }
            }
        }
        $order->update(['payment_url' => $paymentUrl]);
        session()->forget('cart');
        return response()->json(['type' => 'paylater', 'order_number' => $order->order_number, 'payment_url' => $paymentUrl]);
    }

    private function processCreditCard(Order $order, $tokenId, $authId)
    {
        if (!$tokenId) throw new Exception('Token ID Kartu Kredit diperlukan.');
        $charge = $this->xenditService->createCreditCardCharge($order, $tokenId, $authId);
        
        if ($charge['status'] === 'CAPTURED') {
            $order->update(['payment_status' => 'paid', 'status' => 'processing']);
            session()->forget('cart');
            return response()->json(['type' => 'credit_card', 'status' => 'success', 'order_number' => $order->order_number]);
        } else {
            return response()->json(['type' => 'credit_card', 'status' => 'pending', 'order_number' => $order->order_number, 'response' => $charge]);
        }
    }
}

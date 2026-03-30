<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    /**
     * Handle Xendit Webhook callback (Invoice, VA, QRIS, E-Wallet, Retail).
     */
    public function handle(Request $request)
    {
        // 1. Verify callback token
        $callbackToken = config('xendit.callback_token');
        $receivedToken = $request->header('x-callback-token');

        if (!empty($callbackToken) && $receivedToken !== $callbackToken) {
            Log::warning('Xendit Webhook: Invalid token.', ['received' => $receivedToken]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $payload = $request->all();
        Log::info('Xendit Webhook Received', ['payload' => $payload]);

        // 2. Identify Order Number
        // Xendit uses different keys for different products:
        // Invoice/VA/Retail: external_id
        // QRIS/E-Wallet: reference_id
        $rawId = $payload['external_id'] ?? $payload['reference_id'] ?? $payload['id'] ?? null;
        
        if (!$rawId) {
            Log::error('Xendit Webhook: No ID found.');
            return response()->json(['message' => 'Missing ID'], 400);
        }

        // Strip any suffixes (e.g. from VA or Retail if we ever add them)
        $orderNumber = explode('_', $rawId)[0];

        $order = Order::where('order_number', $orderNumber)->first();
        if (!$order) {
            Log::error('Xendit Webhook: Order not found: ' . $orderNumber);
            return response()->json(['message' => 'Order not found'], 200);
        }

        // 3. Status Mapping
        // VA: implicit paid if callback reaches here (no status field usually)
        // QRIS: status = COMPLETED
        // E-Wallet: status = SUCCEEDED
        // Retail: status = COMPLETED (in some versions) or implicit
        $xStatus = strtoupper($payload['status'] ?? 'PAID');

        $paidStatuses = ['PAID', 'SETTLED', 'COMPLETED', 'SUCCEEDED'];
        $failStatuses = ['EXPIRED', 'FAILED', 'VOIDED'];

        if (in_array($xStatus, $paidStatuses)) {
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'paid_at' => now()
                ]);
                Log::info('Xendit Webhook: Order ' . $orderNumber . ' marked as PAID');
            }
        } elseif (in_array($xStatus, $failStatuses)) {
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => strtolower($xStatus),
                    'status' => 'cancelled'
                ]);
                Log::info('Xendit Webhook: Order ' . $orderNumber . ' marked as ' . $xStatus);
            }
        }

        return response()->json(['message' => 'Success'], 200);
    }
}

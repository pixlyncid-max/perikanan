<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $callbackToken = config('xendit.callback_token');
        $xHeaderToken = $request->header('x-callback-token');

        if ($callbackToken && $xHeaderToken !== $callbackToken) {
            return response()->json(['message' => 'Invalid callback token'], 403);
        }

        $data = $request->all();
        Log::info('Xendit Callback Received', $data);

        $externalId = $data['external_id'] ?? null;
        $status = $data['status'] ?? null;

        if (!$externalId) {
            return response()->json(['message' => 'External ID not found'], 400);
        }

        $order = Order::where('order_number', $externalId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($status === 'PAID') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'paid_at' => now(),
            ]);
        } elseif ($status === 'EXPIRED') {
            $order->update([
                'payment_status' => 'expired',
                'status' => 'cancelled',
            ]);
        }

        return response()->json(['message' => 'Success']);
    }
}

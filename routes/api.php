<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\XenditWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Xendit Webhook
|--------------------------------------------------------------------------
| This endpoint is called by Xendit's servers to notify us of payment status
| changes. It must be publicly accessible (no auth), and is verified by the
| x-callback-token header. CSRF is not required for server-to-server calls.
*/
Route::post('/xendit/webhook', [XenditWebhookController::class, 'handle'])
    ->name('xendit.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Order Payment Status Check (for polling)
Route::get('/orders/{order_number}/payment-status', function ($order_number) {
    $order = \App\Models\Order::where('order_number', $order_number)->first();
    if (!$order) {
        return response()->json(['payment_status' => 'unknown'], 404);
    }
    return response()->json([
        'payment_status' => $order->payment_status,
        'status' => $order->status,
    ]);
})->name('api.order.payment-status');


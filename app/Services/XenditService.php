<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class XenditService
{
    protected ?string $secretKey;
    protected string $baseUrl = 'https://api.xendit.co';

    public function __construct()
    {
        $this->secretKey = config('xendit.secret_key');
        
        if (empty($this->secretKey)) {
            Log::error('Xendit Secret Key is not configured in .env or config/xendit.php');
        }
    }

    /**
     * Create a Xendit Invoice for an order.
     *
     * @param Order $order
     * @param string $payerEmail
     * @return array
     * @throws Exception
     */
    public function createInvoice(Order $order, string $payerEmail): array
    {
        if (empty($this->secretKey)) {
            throw new Exception('Konfigurasi Xendit (Secret Key) belum diatur. Silakan periksa file .env Anda.');
        }

        try {
            $items = $order->items->map(function ($item) {
                return [
                    'name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'price' => (int) $item->unit_price,
                ];
            })->toArray();

            $payload = [
                'external_id' => $order->order_number,
                'amount' => (int) $order->total_amount,
                'payer_email' => $payerEmail,
                'description' => "Order #" . $order->order_number,
                'items' => $items,
                'success_redirect_url' => route('orders.show', $order->order_number),
                'failure_redirect_url' => route('orders.show', $order->order_number),
            ];

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/v2/invoices', $payload);

            if ($response->failed()) {
                Log::error('Xendit Invoice Creation Failed', [
                    'order_number' => $order->order_number,
                    'response' => $response->json(),
                ]);
                throw new Exception('Gagal membuat invoice Xendit: ' . ($response->json()['message'] ?? 'Unknown Error'));
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Xendit Service Error: ' . $e->getMessage(), [
                'order_number' => $order->order_number,
            ]);
            throw $e;
        }
    }
}

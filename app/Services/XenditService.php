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

    // ─────────────────────────────────────────
    // INVOICE (legacy – still used for fallback)
    // ─────────────────────────────────────────

    /**
     * Create a Xendit Invoice (full-page Xendit checkout).
     */
    public function createInvoice(Order $order, string $payerEmail): array
    {
        $this->assertConfigured();

        try {
            $items = $order->items->map(function ($item) {
                return [
                    'name'     => $item->product_name,
                    'quantity' => $item->quantity,
                    'price'    => (int) $item->unit_price,
                ];
            })->toArray();

            $payload = [
                'external_id'           => $order->order_number,
                'amount'                => (int) $order->total_amount,
                'payer_email'           => $payerEmail,
                'description'           => 'Order #' . $order->order_number,
                'items'                 => $items,
                'success_redirect_url'  => route('orders.show', $order->order_number),
                'failure_redirect_url'  => route('orders.show', $order->order_number),
            ];

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/v2/invoices', $payload);

            if ($response->failed()) {
                Log::error('Xendit Invoice Creation Failed', [
                    'order_number' => $order->order_number,
                    'response'     => $response->json(),
                ]);
                throw new Exception('Gagal membuat invoice Xendit: ' . ($response->json()['message'] ?? 'Unknown Error'));
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Xendit Service Error: ' . $e->getMessage(), ['order_number' => $order->order_number]);
            throw $e;
        }
    }

    // ─────────────────────────────────────────
    // VIRTUAL ACCOUNT
    // ─────────────────────────────────────────

    /**
     * Create a Xendit Fixed/Closed Virtual Account.
     *
     * @param Order  $order
     * @param string $bankCode  BCA | BNI | BRI | MANDIRI | PERMATA | BSI
     * @param string $name      Account holder name
     * @return array  [account_number, bank_code, name, expected_amount, expiration_date, id]
     * @throws Exception
     */
    public function createVirtualAccount(Order $order, string $bankCode, string $name): array
    {
        $this->assertConfigured();

        $payload = [
            'external_id'     => $order->order_number . '_' . strtolower($bankCode),
            'bank_code'       => strtoupper($bankCode),
            'name'            => $name,
            'expected_amount' => (int) $order->total_amount,
            'is_closed'       => true,   // user must pay exact amount
            'is_single_use'   => true,   // expired after one payment
            'expiration_date' => now()->addHours(24)->toIso8601String(),
        ];

        $response = Http::withBasicAuth($this->secretKey, '')
            ->post($this->baseUrl . '/callback_virtual_accounts', $payload);

        if ($response->failed()) {
            Log::error('Xendit VA Creation Failed', [
                'order_number' => $order->order_number,
                'bank_code'    => $bankCode,
                'response'     => $response->json(),
            ]);
            throw new Exception('Gagal membuat Virtual Account: ' . ($response->json()['message'] ?? 'Unknown Error'));
        }

        return $response->json();
    }

    // ─────────────────────────────────────────
    // QRIS
    // ─────────────────────────────────────────

    /**
     * Create a Xendit QRIS Dynamic QR Code.
     *
     * @return array  [reference_id, qr_string, amount, status, expires, ...]
     * @throws Exception
     */
    public function createQrisCharge(Order $order): array
    {
        $this->assertConfigured();

        $payload = [
            'reference_id'  => $order->order_number,
            'type'          => 'DYNAMIC',
            'currency'      => 'IDR',
            'amount'        => (int) $order->total_amount,
            'expires_at'    => now()->addHours(1)->toIso8601String(),
            'callback_url'  => url('/api/xendit/webhook'),
        ];

        $response = Http::withBasicAuth($this->secretKey, '')
            ->withHeaders(['api-version' => '2022-07-31'])
            ->post($this->baseUrl . '/qr_codes', $payload);

        if ($response->failed()) {
            Log::error('Xendit QRIS Creation Failed', [
                'order_number' => $order->order_number,
                'response'     => $response->json(),
            ]);
            throw new Exception('Gagal membuat QRIS: ' . ($response->json()['message'] ?? 'Unknown Error'));
        }

        return $response->json();
    }

    // ─────────────────────────────────────────
    // E-WALLETS
    // ─────────────────────────────────────────

    /**
     * Create an E-Wallet Charge (OVO, DANA, LinkAja, ShopeePay).
     *
     * @param Order $order
     * @param string $channelCode  ID_OVO | ID_DANA | ID_LINKAJA | ID_SHOPEEPAY
     * @return array
     * @throws Exception
     */
    public function createEWalletCharge(Order $order, string $channelCode, ?string $payerPhone = null): array
    {
        $this->assertConfigured();

        $properties = [
            'success_redirect_url' => route('orders.show', $order->order_number),
            'failure_redirect_url' => route('checkout.index'),
        ];
        
        // Specific properties for OVO (needs mobile number)
        if (strtoupper($channelCode) === 'ID_OVO' && $payerPhone) {
            $properties['mobile_number'] = $this->formatPhoneNumber($payerPhone);
        }

        $payload = [
            'reference_id'    => $order->order_number,
            'currency'        => 'IDR',
            'amount'          => (int) $order->total_amount,
            'checkout_method' => 'ONE_TIME_PAYMENT',
            'channel_code'    => strtoupper($channelCode),
            'channel_properties' => $properties,
            'metadata' => [
                'branch_code' => 'MAIN_BRANCH'
            ]
        ];

        // Specific properties for OVO (needs mobile number)
        // Note: For simplicity, we might want to redirect for all e-wallets,
        // but OVO usually requires the number. Let's use REDIRECT style if possible.
        
        $response = Http::withBasicAuth($this->secretKey, '')
            ->withHeaders([
                'X-CALLBACK-URL' => url('/api/xendit/callback') // Fallback callback URL for e-wallet
            ])
            ->post($this->baseUrl . '/ewallets/charges', $payload);

        if ($response->failed()) {
            Log::error('Xendit E-Wallet Charge Failed', [
                'order_number' => $order->order_number,
                'channel'      => $channelCode,
                'response'     => $response->json(),
            ]);
            throw new Exception('Gagal memproses e-wallet: ' . ($response->json()['message'] ?? 'Unknown Error'));
        }

        return $response->json();
    }

    // ─────────────────────────────────────────
    // RETAIL OUTLETS (Alfamart, Indomaret)
    // ─────────────────────────────────────────

    /**
     * Create a Retail Outlet fixed payment code.
     *
     * @param Order $order
     * @param string $channelCode  ALFAMART | INDOMARET
     * @param string $name         Customer name
     * @return array
     * @throws Exception
     */
    public function createRetailOutletCode(Order $order, string $channelCode, string $name): array
    {
        $this->assertConfigured();

        $payload = [
            'external_id'       => $order->order_number,
            'retail_outlet_name' => strtoupper($channelCode),
            'name'              => $name,
            'expected_amount'   => (int) $order->total_amount,
        ];

        $response = Http::withBasicAuth($this->secretKey, '')
            ->post($this->baseUrl . '/fixed_payment_code', $payload);

        if ($response->failed()) {
            Log::error('Xendit Retail Outlet Creation Failed', [
                'order_number' => $order->order_number,
                'channel'      => $channelCode,
                'response'     => $response->json(),
            ]);
            throw new Exception('Gagal membuat kode pembayaran: ' . ($response->json()['message'] ?? 'Unknown Error'));
        }

        return $response->json();
    }

    // ─────────────────────────────────────────
    // DIRECT DEBIT (BRI, BNI, BCA KlikBCA)
    // ─────────────────────────────────────────

    public function createDirectDebitCharge(Order $order, string $channelCode): array
    {
        $this->assertConfigured();

        $payload = [
            'reference_id'    => $order->order_number,
            'currency'        => 'IDR',
            'amount'          => (int) $order->total_amount,
            'channel_code'    => strtoupper($channelCode),
            'channel_properties' => [
                'success_redirect_url' => route('orders.show', $order->order_number),
            ],
        ];

        $response = Http::withBasicAuth($this->secretKey, '')
            ->post($this->baseUrl . '/direct_debits/charges', $payload);

        if ($response->failed()) {
            Log::error('Xendit Direct Debit Charge Failed', [
                'order_number' => $order->order_number,
                'channel'      => $channelCode,
                'response'     => $response->json(),
            ]);
            throw new Exception('Gagal memproses Direct Debit: ' . ($response->json()['message'] ?? 'Unknown Error'));
        }

        return $response->json();
    }

    // ─────────────────────────────────────────
    // PAYLATER (Kredivo, Akulaku)
    // ─────────────────────────────────────────

    public function createPaylaterCharge(Order $order, string $channelCode): array
    {
        $this->assertConfigured();

        $payload = [
            'reference_id'    => $order->order_number,
            'currency'        => 'IDR',
            'amount'          => (int) $order->total_amount,
            'channel_code'    => strtoupper($channelCode),
            'channel_properties' => [
                'success_redirect_url' => route('orders.show', $order->order_number),
                'failure_redirect_url' => route('orders.show', $order->order_number),
            ],
            'customer_details' => [
                'given_names' => $order->user->name ?? 'Customer',
                'surname'     => '',
                'email'       => $order->user->email ?? 'customer@example.com',
                'mobile_number' => $order->user->phone ?? '',
            ],
            'items' => $order->items->map(function ($item) {
                return [
                    'reference_id' => (string) $item->product_id,
                    'name'         => $item->product_name,
                    'category'     => 'E-commerce',
                    'price'        => (int) $item->unit_price,
                    'quantity'     => $item->quantity,
                    'type'         => 'PRODUCT',
                ];
            })->toArray(),
        ];

        $response = Http::withBasicAuth($this->secretKey, '')
            ->post($this->baseUrl . '/paylater/charges', $payload);

        if ($response->failed()) {
            Log::error('Xendit Paylater Charge Failed', [
                'order_number' => $order->order_number,
                'channel'      => $channelCode,
                'response'     => $response->json(),
            ]);
            throw new Exception('Gagal memproses PayLater: ' . ($response->json()['message'] ?? 'Unknown Error'));
        }

        return $response->json();
    }

    // ─────────────────────────────────────────
    // CREDIT CARD
    // ─────────────────────────────────────────

    public function createCreditCardCharge(Order $order, string $tokenId, string $authId = null): array
    {
        $this->assertConfigured();

        $payload = [
            'external_id'       => $order->order_number,
            'token_id'          => $tokenId,
            'amount'            => (int) $order->total_amount,
            'authentication_id' => $authId,
        ];

        $response = Http::withBasicAuth($this->secretKey, '')
            ->post($this->baseUrl . '/v1/credit_card_charges', $payload);

        if ($response->failed()) {
            Log::error('Xendit Credit Card Charge Failed', [
                'order_number' => $order->order_number,
                'response'     => $response->json(),
            ]);
            throw new Exception('Gagal memproses Kartu Kredit: ' . ($response->json()['message'] ?? 'Unknown Error'));
        }

        return $response->json();
    }

    // ─────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────

    protected function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        if (!str_starts_with($phone, '+') && !str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }
        return str_starts_with($phone, '+') ? $phone : '+' . $phone;
    }

    protected function assertConfigured(): void
    {
        if (empty($this->secretKey)) {
            throw new Exception('Konfigurasi Xendit (Secret Key) belum diatur. Silakan periksa file .env Anda.');
        }
    }
}

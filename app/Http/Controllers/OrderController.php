<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Exception;

class OrderController extends Controller
{
    protected XenditService $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Display a listing of orders for the current user.
     */
    public function index()
    {
        $userSession = Session::get('user');
        if (!$userSession) {
            return redirect('/login')->with('error', 'Silakan login untuk melihat pesanan Anda.');
        }

        $orders = Order::where('user_id', $userSession['id'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the order detail page.
     */
    public function show($order_number)
    {
        $order = Order::with('items')->where('order_number', $order_number)->firstOrFail();
        
        // Ensure user owns this order
        $userSession = Session::get('user');
        if (!$userSession || $order->user_id != $userSession['id']) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Re-generate a Xendit payment URL.
     */
    public function repay($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        $userSession = Session::get('user');
        
        if (!$userSession || $order->user_id != $userSession['id']) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        try {
            // Get current user email based on type
            $email = "";
            if ($userSession['type'] === 'admin') {
                $email = \App\Models\Admin::find($userSession['id'])->email;
            } elseif ($userSession['type'] === 'member') {
                $email = \App\Models\Member::find($userSession['id'])->email;
            } else {
                $email = \App\Models\User::find($userSession['id'])->email;
            }

            $xenditResponse = $this->xenditService->createInvoice($order, $email);

            $order->update([
                'payment_url' => $xenditResponse['invoice_url']
            ]);

            return redirect($order->payment_url);
        } catch (Exception $e) {
            return back()->with('error', 'Gagal membuat ulang pembayaran: ' . $e->getMessage());
        }
    }
    /**
     * Cancel an existing pending order.
     */
    public function cancel($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        $userSession = Session::get('user');
        
        if (!$userSession || $order->user_id != $userSession['id']) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        if ($order->status !== 'pending' && $order->payment_status !== 'pending') {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan karena statusnya sudah diproses atau dibayar.');
        }

        try {
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'failed'
            ]);

            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('status', $request->get('status'));
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->get('payment_status') !== '') {
            $query->where('payment_status', $request->get('payment_status'));
        }

        $orders = $query->latest()->paginate(10);
        
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'member']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Sync payment status with Xendit manually.
     */
    public function syncPaymentStatus(Order $order, \App\Services\XenditService $xenditService)
    {
        if (!$order->payment_url) {
            return back()->with('error', 'Pesanan ini tidak memiliki data pembayaran Xendit.');
        }

        // Extract invoice ID from URL (simple way for Xendit)
        // URL format: https://checkout.xendit.co/web/65f...
        // Or if we store invoice ID, but let's try searching by external ID (order_number)
        
        try {
            // Xendit API search by external ID is preferred but requires different endpoint
            // For now, let's assume we need the invoice ID from URL or similar
            // Actually, XenditService@getInvoice needs ID. 
            // If we don't store it, we might need a workaround.
            
            // Let's check if we can get it from the URL
            $parts = explode('/', rtrim($order->payment_url, '/'));
            $invoiceId = end($parts);

            $xenditData = $xenditService->getInvoice($invoiceId);
            $newStatus = strtolower($xenditData['status']);

            $updateData = [];
            if ($newStatus === 'settled' || $newStatus === 'paid') {
                $updateData['payment_status'] = 'paid';
                $updateData['paid_at'] = now();
                if ($order->status === 'pending') {
                    $updateData['status'] = 'processing';
                }
            } elseif ($newStatus === 'expired') {
                $updateData['payment_status'] = 'failed';
                $updateData['status'] = 'cancelled';
            }

            if (!empty($updateData)) {
                $order->update($updateData);
                return back()->with('success', 'Status pembayaran berhasil diperbarui: ' . $order->payment_status);
            }

            return back()->with('info', 'Status pembayaran di Xendit masih: ' . $newStatus);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Delete order items first
        $order->items()->delete();
        
        // Delete order
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }
}
?>

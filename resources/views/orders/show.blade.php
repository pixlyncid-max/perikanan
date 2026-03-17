@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold">Detail Pesanan</h1>
            <span class="px-4 py-2 rounded-full font-bold text-sm uppercase 
                @if($order->payment_status === 'paid') bg-green-100 text-green-600 
                @elseif($order->payment_status === 'expired') bg-red-100 text-red-600 
                @else bg-yellow-100 text-yellow-600 @endif">
                {{ $order->payment_status }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold mb-4 border-b pb-2">Informasi Order</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nomor Pesanan</span>
                        <span class="font-mono text-xs">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal</span>
                        <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Metode Pembayaran</span>
                        <span class="uppercase">{{ $order->payment_method }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold mb-4 border-b pb-2">Alamat Pengiriman</h2>
                <p class="text-gray-600">{{ $order->shipping_address }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <table class="w-full text-left order-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Produk</th>
                        <th class="px-6 py-4 font-semibold text-center">Jumlah</th>
                        <th class="px-6 py-4 font-semibold text-right">Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 text-gray-800">{{ $item->product_name }}</td>
                            <td class="px-6 py-4 text-center text-gray-600">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t border-gray-100 font-bold">
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-right">Biaya Pengiriman</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-right text-xl">Total Pembayaran</td>
                        <td class="px-6 py-4 text-right text-xl text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($order->payment_status === 'pending')
            <div class="bg-blue-50 border border-blue-100 p-6 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-blue-900 font-bold text-lg">Menunggu Pembayaran</h3>
                    <p class="text-blue-700">Silakan selesaikan pembayaran Anda sebelum invoice kadaluarsa.</p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ $order->payment_url }}" target="_blank" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg">
                        Bayar Sekarang
                    </a>
                </div>
            </div>
        @elseif($order->payment_status === 'expired' || $order->payment_status === 'failed')
            <div class="bg-red-50 border border-red-100 p-6 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-red-900 font-bold text-lg text-capitalize">Pembayaran {{ $order->payment_status }}</h3>
                    <p class="text-red-700">Waktu pembayaran telah habis atau gagal diproses.</p>
                </div>
                <a href="{{ route('orders.repay', $order->order_number) }}" class="bg-red-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-red-700 transition shadow-lg">
                    Buat Ulang Pembayaran
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

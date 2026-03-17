@extends('admin.layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan #{{ $order->order_number }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    
    <!-- Order Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-user text-blue-600 mr-2"></i> Informasi Pembeli
            </h3>
            <div class="space-y-2 text-sm">
                <div>
                    <span class="text-gray-500">Nama:</span>
                    <span class="font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Email:</span>
                    <span class="font-medium text-gray-900">{{ $order->user->email ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Telepon:</span>
                    <span class="font-medium text-gray-900">{{ $order->user->phone ?? '-' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Order Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i> Status Pesanan
            </h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-500 text-sm">Status:</span>
                    @php
                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'processing' => 'bg-blue-100 text-blue-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                        $statusLabels = [
                            'pending' => 'Menunggu',
                            'processing' => 'Diproses',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ];
                    @endphp
                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-500 text-sm">Pembayaran:</span>
                    @php
                        $paymentClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'paid' => 'bg-green-100 text-green-800',
                            'failed' => 'bg-red-100 text-red-800',
                        ];
                        $paymentLabels = [
                            'pending' => 'Menunggu',
                            'paid' => 'Lunas',
                            'failed' => 'Gagal',
                        ];
                    @endphp
                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full {{ $paymentClasses[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-500 text-sm">Metode:</span>
                    <span class="font-medium text-gray-900 uppercase">{{ $order->payment_method ?? '-' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-receipt text-blue-600 mr-2"></i> Ringkasan
            </h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Tanggal:</span>
                    <span class="font-medium text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Subtotal:</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between border-t pt-2 mt-2">
                    <span class="text-gray-800 font-semibold">Total:</span>
                    <span class="text-blue-600 font-bold text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Xendit Details -->
    @if($order->payment_method === 'xendit' || $order->payment_url)
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-1">
                    <i class="fas fa-credit-card text-blue-600 mr-2"></i> Detail Pembayaran Xendit
                </h3>
                <p class="text-sm text-gray-500 border-b pb-2 mb-2">Informasi transaksi langsung dari gateway pembayaran.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm">
                    <div class="flex gap-2">
                        <span class="text-gray-500 w-24">Link Invoice:</span>
                        <a href="{{ $order->payment_url }}" target="_blank" class="text-blue-600 hover:underline font-medium break-all">
                            {{ $order->payment_url }} <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('admin.orders.sync-payment', $order) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition font-medium text-sm">
                        <i class="fas fa-sync-alt mr-2"></i> Sinkronisasi Status
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Order Items -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Item Pesanan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($order->items as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($item->product && $item->product->image)
                                    <img class="h-10 w-10 rounded-lg object-cover mr-3" src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center mr-3">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name ?? 'Produk tidak tersedia' }}</div>
                                    @if($item->product && $item->product->category)
                                        <div class="text-xs text-gray-500">{{ $item->product->category->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Tidak ada item dalam pesanan ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Update Status Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Perbarui Status</h3>
        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="flex flex-col sm:flex-row gap-4 items-end">
            @csrf
            @method('PATCH')
            <div class="flex-1">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Pesanan</label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save mr-2"></i> Perbarui Status
            </button>
        </form>
    </div>
</div>
@endsection

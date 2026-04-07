@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Pesanan Saya</h1>
            <p class="text-gray-500">Kelola dan pantau status pesanan Anda di sini.</p>
        </div>
        <a href="{{ route('produk.index') }}" class="inline-flex items-center gap-2 text-blue-600 font-medium hover:underline">
            <i class="fas fa-arrow-left"></i> Lanjut Belanja
        </a>
    </div>

    @if($orders->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Mobile Card View (hidden on md+) --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($orders as $order)
                    @php
                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'processing' => 'bg-blue-100 text-blue-700',
                            'completed' => 'bg-green-100 text-green-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                        ];
                        $statusClass = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <a href="{{ route('orders.show', $order->order_number) }}" class="block p-4 hover:bg-blue-50 transition">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <span class="font-mono text-xs text-gray-500 block">#{{ substr($order->order_number, 0, 12) }}...</span>
                                <span class="font-bold text-gray-800 text-base">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase {{ $statusClass }} shrink-0 ml-2">
                                {{ $order->status }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            <div class="flex items-center gap-2">
                                @if($order->status === 'pending' && $order->payment_url)
                                    <button onclick="event.preventDefault(); event.stopPropagation(); openCheckoutModal('{{ $order->payment_url }}')"
                                        class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-700 transition">
                                        Bayar
                                    </button>
                                @endif
                                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Desktop Table View (hidden on mobile) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700">Nomor Pesanan</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Tanggal</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Total</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($orders as $order)
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                                $statusClass = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <tr class="hover:bg-blue-50 transition cursor-pointer" onclick="window.location='{{ route('orders.show', $order->order_number) }}'">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm text-gray-600">#{{ substr($order->order_number, 0, 8) }}...</span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-800">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $statusClass }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($order->status === 'pending' && $order->payment_url)
                                            <button onclick="event.stopPropagation(); openCheckoutModal('{{ $order->payment_url }}')"
                                                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition shadow-sm z-10 relative">
                                                Bayar Sekarang
                                            </button>
                                        @endif
                                        <div class="p-2 text-gray-400">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
                <div class="p-6 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                <i class="fas fa-shopping-bag text-3xl"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Pesanan</h2>
            <p class="text-gray-500 mb-8">Anda belum memiliki riwayat pesanan.</p>
            <a href="{{ route('produk.index') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg">
                Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection

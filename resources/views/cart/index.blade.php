@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8">Keranjang Belanja</h1>

    @if(count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-left border-collapse block md:table">
                        <thead class="hidden md:table-header-group bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-gray-700">Produk</th>
                                <th class="px-6 py-4 font-semibold text-gray-700">Harga</th>
                                <th class="px-6 py-4 font-semibold text-gray-700">Jumlah</th>
                                <th class="px-6 py-4 font-semibold text-gray-700">Subtotal</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group divide-y divide-gray-100">
                            @foreach($cart as $id => $item)
                                <tr class="flex flex-col md:table-row py-4 md:py-0 relative">
                                    <td class="block md:table-cell px-4 md:px-6 py-2 md:py-4 border-b md:border-b-0 border-gray-50">
                                        <div class="flex items-center gap-4 pr-8 md:pr-0">
                                            <div class="w-20 h-20 md:w-16 md:h-16 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                                @if($item['image'])
                                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <i class="fas fa-image text-xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-800 text-lg md:text-base">{{ $item['name'] }}</h3>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="flex justify-between md:table-cell px-4 md:px-6 py-3 md:py-4 text-gray-600 items-center">
                                        <span class="md:hidden font-medium text-gray-500 text-sm">Harga:</span>
                                        <span class="font-medium md:font-normal">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="flex justify-between md:table-cell px-4 md:px-6 py-3 md:py-4 items-center">
                                        <span class="md:hidden font-medium text-gray-500 text-sm">Jumlah:</span>
                                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2 m-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $id }}">
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" 
                                                class="w-16 px-2 py-1 border border-gray-200 rounded-lg text-center focus:ring-2 focus:ring-blue-500 outline-none"
                                                onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="flex justify-between md:table-cell px-4 md:px-6 py-3 md:py-4 items-center bg-gray-50/50 md:bg-transparent">
                                        <span class="md:hidden font-medium text-gray-500 text-sm">Subtotal:</span>
                                        <span class="font-bold text-blue-600 text-lg md:text-base">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="absolute top-4 right-4 md:static md:text-right px-4 md:px-6 py-2 md:py-4 block md:table-cell">
                                        <form action="{{ route('cart.remove') }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $id }}">
                                            <button type="submit" class="w-8 h-8 md:w-auto md:h-auto bg-red-50 md:bg-transparent rounded-full md:rounded-none flex items-center justify-center text-red-500 hover:text-white hover:bg-red-500 md:hover:bg-transparent md:hover:text-red-700 transition md:p-2">
                                                <i class="fas fa-trash-alt text-sm md:text-base"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-6">Ringkasan Pesanan</h2>
                    <div class="divide-y divide-gray-100">
                        <div class="py-3 flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="py-3 flex justify-between text-gray-600">
                            <span>Biaya Pengiriman</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="py-4 flex justify-between">
                            <span class="text-xl font-bold text-gray-800">Total</span>
                            <span class="text-xl font-bold text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button id="btn-checkout" class="w-full mt-6 bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg flex items-center justify-center gap-3">
                        <i class="fas fa-credit-card"></i>
                        Pesan Sekarang
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                <i class="fas fa-shopping-cart text-4xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Keranjang Anda Kosong</h2>
            <p class="text-gray-500 mb-8">Anda belum menambahkan produk apapun ke dalam keranjang.</p>
            <a href="/produk" class="inline-flex items-center gap-2 bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg">
                <i class="fas fa-arrow-left"></i>
                Mulai Belanja
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.getElementById('btn-checkout')?.addEventListener('click', function() {
        @if(!is_logged_in())
            showAlert({
                type: 'warning',
                title: 'Login Diperlukan',
                message: 'Silakan login terlebih dahulu untuk melanjutkan pemesanan dan menikmati layanan kami.',
                primaryText: 'Login Sekarang',
                secondaryText: 'Nanti Saja',
                onConfirm: function() {
                    window.location.href = "{{ route('login') }}";
                }
            });
            return;
        @endif

        const btn = this;
        const originalContent = btn.innerHTML;
        
        // Disable button & show loading
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

        const cartItems = [];
        @foreach($cart as $id => $item)
            cartItems.push({
                product_id: '{{ $id }}',
                quantity: '{{ $item["quantity"] }}'
            });
        @endforeach

        fetch('{{ route("checkout.process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                items: cartItems,
                total: '{{ $total }}',
                address: 'Alamat Test User', // Nanti bisa ditambahkan input alamat
                shipping_cost: 0
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.payment_url) {
                // Open Xendit in Modal instead of redirect
                openCheckoutModal(data.payment_url);
                btn.disabled = false;
                btn.innerHTML = originalContent;
            } else {
                alert('Gagal memproses checkout: ' + (data.message || 'Unknown error'));
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memproses checkout.');
            btn.disabled = false;
            btn.innerHTML = originalContent;
        });
    });
</script>
@endpush
@endsection

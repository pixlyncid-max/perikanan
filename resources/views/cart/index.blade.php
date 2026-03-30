@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold">Keranjang Belanja</h1>
        <a href="{{ route('produk.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2 transition-all hover:gap-3">
            <i class="fas fa-arrow-left text-sm"></i>
            Kembali Belanja
        </a>
    </div>

    @if(count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-left border-collapse block md:table">
                        <thead class="hidden md:table-header-group bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-gray-700 w-12">
                                    <input type="checkbox" id="select-all" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-4 font-semibold text-gray-700">Produk</th>
                                <th class="px-6 py-4 font-semibold text-gray-700">Harga</th>
                                <th class="px-6 py-4 font-semibold text-gray-700 text-center">Jumlah</th>
                                <th class="px-6 py-4 font-semibold text-gray-700 text-right">Subtotal</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group divide-y divide-gray-100">
                             @foreach($cart as $id => $item)
                                <tr class="flex flex-col md:table-row py-4 md:py-0 relative cart-item-row" data-id="{{ $id }}">
                                    <td class="block md:table-cell px-4 md:px-6 py-2 md:py-4">
                                        <input type="checkbox" name="selected_items[]" value="{{ $id }}" checked 
                                            class="cart-item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                            data-price="{{ $item['price'] }}" 
                                            data-quantity="{{ $item['quantity'] }}">
                                    </td>
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
                                                @if(isset($item['variation_name']))
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        <span class="font-medium">{{ $item['variation_type'] }}:</span> {{ $item['variation_name'] }}
                                                    </p>
                                                @endif
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
                                            <input type="hidden" name="cart_id" value="{{ $id }}">
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" 
                                                class="w-16 px-2 py-1 border border-gray-200 rounded-lg text-center focus:ring-2 focus:ring-blue-500 outline-none"
                                                onchange="this.form.submit()">
                                        </form>
                                    </td>
                                     <td class="flex justify-between md:table-cell px-4 md:px-6 py-3 md:py-4 items-center bg-gray-50/50 md:bg-transparent text-right">
                                        <span class="md:hidden font-medium text-gray-500 text-sm">Subtotal:</span>
                                        <span class="font-bold text-blue-600 text-lg md:text-base">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="absolute top-4 right-4 md:static md:text-right px-4 md:px-6 py-2 md:py-4 block md:table-cell">
                                        <form action="{{ route('cart.remove') }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="cart_id" value="{{ $id }}">
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
                            <span id="display-subtotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="py-3 flex justify-between text-gray-600">
                            <span>Biaya Pengiriman</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="py-4 flex justify-between">
                            <span class="text-xl font-bold text-gray-800">Total</span>
                            <span class="text-xl font-bold text-blue-600" id="display-total">Rp {{ number_format($total, 0, ',', '.') }}</span>
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
    // Update totals in real-time
    const subtotalDisplay = document.getElementById('display-subtotal');
    const totalDisplay = document.getElementById('display-total');
    const checkboxes = document.querySelectorAll('.cart-item-checkbox');
    const selectAllCheckbox = document.getElementById('select-all');
    const btnCheckout = document.getElementById('btn-checkout');

    function updateCartTotals() {
        let total = 0;
        let checkedCount = 0;
        
        checkboxes.forEach(cb => {
            const row = cb.closest('.cart-item-row');
            if (cb.checked) {
                total += parseFloat(cb.dataset.price) * parseInt(cb.dataset.quantity);
                checkedCount++;
                row.style.opacity = '1';
                row.classList.remove('bg-gray-50/50');
            } else {
                row.style.opacity = '0.5';
                row.classList.add('bg-gray-50/50');
            }
        });
        
        const formatted = 'Rp ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        subtotalDisplay.textContent = formatted;
        totalDisplay.textContent = formatted;
        
        // Handle checkout button state
        if (checkedCount === 0) {
            btnCheckout.disabled = true;
            btnCheckout.classList.add('opacity-50', 'cursor-not-allowed');
            selectAllCheckbox.checked = false;
        } else {
            btnCheckout.disabled = false;
            btnCheckout.classList.remove('opacity-50', 'cursor-not-allowed');
            selectAllCheckbox.checked = checkedCount === checkboxes.length;
        }
    }

    // Select all logic
    selectAllCheckbox?.addEventListener('change', function() {
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });
        updateCartTotals();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateCartTotals);
    });

    btnCheckout?.addEventListener('click', function() {
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

        // Gather selected item IDs
        const selectedIds = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value)
            .join(',');
        
        if (!selectedIds) {
            showAlert({
                type: 'warning',
                title: 'Pilih Produk',
                message: 'Silakan pilih setidaknya satu produk untuk di-checkout.'
            });
            return;
        }

        // Redirect to the checkout page with selected item IDs
        window.location.href = "{{ route('checkout.index') }}?items=" + selectedIds;
    });
</script>
@endpush
@endsection


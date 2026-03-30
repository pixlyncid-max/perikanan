@extends('layouts.app')

@section('title', 'Checkout | ' . get_setting('site_name', 'FISHERIES'))

@push('styles')
<style>
    :root {
        --checkout-primary: #2563eb;
        --checkout-primary-soft: #eff6ff;
        --checkout-border: #e2e8f0;
        --checkout-bg: #f8fafc;
        --checkout-text-main: #1e293b;
        --checkout-text-muted: #64748b;
        --shopee-orange: #ee4d2d;
    }

    /* ── Layout & Containers ── */
    .checkout-section { background: #fff; border-radius: 4px; box-shadow: 0 1px 1px rgba(0,0,0,0.05); margin-bottom: 12px; overflow: hidden; }
    .section-header { padding: 20px 24px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #f1f5f9; }
    .section-title { font-size: 1.1rem; font-weight: 700; color: var(--checkout-primary); text-transform: uppercase; letter-spacing: 0.02em; }
    
    /* ── Address Block (Shopee Style) ── */
    .address-card { position: relative; padding: 24px; background: #fff; border-radius: 4px; overflow: hidden; }
    .address-card::before { 
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; 
        background-image: repeating-linear-gradient(45deg, #6fa6d6, #6fa6d6 33px, transparent 33px, transparent 41px, #f18d9b 41px, #f18d9b 74px, transparent 74px, transparent 82px);
        background-size: 116px 3px;
    }
    .address-info-grid { display: grid; grid-template-cols: auto 1fr auto; gap: 16px; align-items: start; }
    .location-pin { color: var(--checkout-primary); font-size: 1.25rem; margin-top: 4px; }
    .cust-detail { font-weight: 700; color: #222; margin-bottom: 4px; display: flex; gap: 12px; }
    .cust-address { color: #666; font-size: 0.9rem; line-height: 1.5; }

    /* ── Product List ── */
    .product-row { display: grid; grid-template-cols: 1fr 120px 100px 140px; gap: 16px; padding: 20px 24px; align-items: center; border-bottom: 1px solid #f8fafc; }
    .product-row:last-child { border-bottom: none; }
    .product-info { display: flex; gap: 12px; align-items: center; }
    .product-img { width: 50px; height: 50px; border-radius: 2px; border: 1px solid #f0f0f0; object-fit: cover; }
    .product-name { font-size: 0.9rem; color: #222; font-weight: 500; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .col-label { font-size: 0.75rem; color: #999; font-weight: 500; }
    .price-tag { font-size: 0.9rem; color: #222; }

    /* ── Shipping Options ── */
    .shipping-box { background: #fafdff; border: 1px solid #d0e7ff; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; }
    .ship-select-area { display: flex; align-items: center; gap: 12px; cursor: pointer; }
    .ship-cost { font-weight: 700; color: var(--checkout-primary); }

    /* ── Payment Selector ── */
    .payment-grid { display: grid; grid-template-cols: repeat(auto-fill, minmax(130px, 1fr)); gap: 10px; padding: 20px; }
    .payment-option-v2 { 
        position: relative; border: 1px solid #e1e1e1; border-radius: 2px; padding: 12px; 
        cursor: pointer; transition: all 0.2s; background: #fff; height: 54px;
        display: flex; items-center; justify-content: center;
    }
    .payment-option-v2:hover { border-color: var(--checkout-primary); }
    .payment-option-v2.selected { border-color: var(--checkout-primary); background: #fff; }
    .payment-option-v2.selected::after {
        content: '\f00c'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
        position: absolute; bottom: -1px; right: -1px; background: var(--checkout-primary);
        color: #fff; font-size: 8px; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center;
        clip-path: polygon(100% 0, 0% 100%, 100% 100%);
    }
    .payment-option-v2 img { max-height: 24px; max-width: 100%; object-fit: contain; }
    .payment-option-v2 input { position: absolute; opacity: 0; }

    /* ── Sticky Summary Sidebar ── */
    .sidebar-summary { position: sticky; top: 84px; background: #fff; border-radius: 4px; box-shadow: 0 1px 1px rgba(0,0,0,0.05); }
    .price-summary-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 0.9rem; color: #757575; }
    .total-row { display: flex; justify-content: space-between; padding: 16px 0; margin-top: 12px; border-top: 1px dashed #e8e8e8; }
    .total-price { color: var(--shopee-orange); font-size: 1.75rem; font-weight: 700; }

    .btn-checkout-primary { 
        width: 100%; padding: 12px; background: var(--shopee-orange); color: #fff; 
        font-weight: 700; font-size: 1.1rem; border-radius: 2px; transition: opacity 0.2s;
        box-shadow: 0 1px 1px rgba(0,0,0,0.09);
    }
    .btn-checkout-primary:hover { opacity: 0.9; }
    .btn-checkout-primary:disabled { background: #ccc; cursor: not-allowed; }

    /* ── Results ── */
    #checkout-result-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; }
    #checkout-result-overlay.show { display: flex; }
    .result-modal { background: #fff; width: 100%; max-width: 500px; border-radius: 8px; padding: 32px; position: relative; }

    .input-flat { border: 1px solid #ddd; padding: 8px 12px; width: 100%; border-radius: 2px; font-size: 0.9rem; transition: border-color 0.2s; }
    .input-flat:focus { border-color: var(--checkout-primary); outline: none; }

    /* Mobile Bottom Bar (Shopee like) */
    @media (max-width: 768px) {
        .mobile-bottom-bar { 
            position: fixed; bottom: 0; left: 0; right: 0; background: #fff; 
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05); z-index: 100;
            display: flex; align-items: center; justify-content: flex-end; padding: 8px 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="bg-[#f5f5f5] min-h-screen py-8 pb-32">
    <div class="max-w-6xl mx-auto px-4">
        
        <!-- Header / Breadcrumb -->
        <div class="flex items-center gap-4 mb-6">
            <a href="/" class="text-2xl font-black text-blue-600 flex items-center gap-2">
                <img src="{{ asset('images/Logo_Symbol.png') }}" class="h-8">
                <span class="border-l-2 border-blue-600 pl-3 text-slate-800 uppercase tracking-tighter">Checkout</span>
            </a>
        </div>

        <form id="payment-form">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                <!-- LEFT: INFO & PRODUCTS -->
                <div class="lg:col-span-3 space-y-4">
                    
                    <!-- 1. Address Section (Shopee Style) -->
                    <div class="address-card">
                        <div class="flex items-center gap-2 mb-4">
                            <i class="fas fa-map-marker-alt location-pin"></i>
                            <h2 class="text-lg font-medium text-blue-600 uppercase tracking-wide">Alamat Pengiriman</h2>
                        </div>
                        <div class="address-info-grid">
                            <div class="space-y-1">
                                <div class="cust-detail">
                                    <span>{{ $user->name ?? '' }}</span>
                                    <span>{{ $user->phone ?? '' }}</span>
                                </div>
                                <div class="cust-address">
                                    <span id="display-address-text">Silakan lengkapi alamat detail di bawah ini...</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" onclick="toggleAddressEdit()" class="text-sm font-semibold text-blue-600 hover:text-blue-700">UBAH</button>
                            </div>
                        </div>

                        <!-- Address Edit Form (Hidden by default) -->
                        <div id="address-edit-box" class="mt-6 pt-6 border-t border-dashed hidden animate-fadeIn">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Nama Penerima</label>
                                    <input type="text" id="cust_name" class="input-flat" value="{{ $user->name ?? '' }}" placeholder="Nama Lengkap">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">No. Telepon (WhatsApp)</label>
                                    <input type="tel" id="cust_phone" class="input-flat" value="{{ $user->phone ?? '' }}" placeholder="08xxxxxxxx">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Kota / Kecamatan</label>
                                    <input type="text" id="cust_city" class="input-flat" placeholder="Contoh: Samarinda Ulu, Samarinda">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Alamat Lengkap</label>
                                    <textarea id="cust_address" rows="2" class="input-flat resize-none" placeholder="Nama Jalan, No Rumah, RT/RW, dsb."></textarea>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="button" onclick="saveAddressLocal()" class="px-6 py-2 bg-slate-800 text-white font-bold text-xs rounded-sm hover:bg-slate-900 transition">SIMPAN</button>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Product Review Section -->
                    <div class="checkout-section">
                        <div class="product-row bg-slate-50/50">
                            <div class="section-title text-sm !text-slate-800 lowercase">Produk Dipesan</div>
                            <div class="col-label text-center">Harga Satuan</div>
                            <div class="col-label text-center">Jumlah</div>
                            <div class="col-label text-right">Subtotal Produk</div>
                        </div>

                        @foreach($cart as $id => $item)
                        <div class="product-row">
                            <div class="product-info">
                                @if($item['image'])
                                    <img src="{{ asset('storage/'.$item['image']) }}" class="product-img">
                                @else
                                    <div class="product-img bg-slate-100 flex items-center justify-center text-slate-300"><i class="fas fa-fish"></i></div>
                                @endif
                                <span class="product-name">{{ $item['name'] }}</span>
                            </div>
                            <div class="price-tag text-center">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                            <div class="price-tag text-center">{{ $item['quantity'] }}</div>
                            <div class="price-tag text-right font-semibold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                        </div>
                        @endforeach

                        <!-- Shipping Option Integration -->
                        <div class="shipping-box border-t">
                            <div class="flex items-center gap-4">
                                <div class="text-[13px] font-medium text-slate-500">Opsi Pengiriman:</div>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-8">
                                        <label class="flex items-center gap-2 cursor-pointer group" id="ship-label-0">
                                            <input type="radio" name="shipping" value="0" checked class="accent-blue-600">
                                            <div class="text-sm">
                                                <span class="font-bold text-slate-800">Ambil Sendiri</span>
                                                <span class="text-[11px] text-slate-400 ml-2">(Gratis Ongkir)</span>
                                            </div>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer group" id="ship-label-15000">
                                            <input type="radio" name="shipping" value="15000" class="accent-blue-600">
                                            <div class="text-sm">
                                                <span class="font-bold text-slate-800">Kurir Lokal / Ekspedisi</span>
                                                <span class="text-[11px] text-slate-400 ml-2">(Est. 1-3 Hari)</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-slate-400 mb-1">Ongkos Kirim:</div>
                                <div class="ship-cost" id="ship-display">Rp 0</div>
                            </div>
                        </div>

                        <!-- Pesanan Subtotal -->
                        <div class="p-4 px-6 bg-[#fafdff] border-t flex justify-end items-center gap-4">
                            <div class="text-sm text-slate-500">Total Pesanan ({{ count($cart) }} Produk):</div>
                            <div class="text-xl font-bold text-blue-600" id="total-summary-display">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <!-- 3. Payment Method Section -->
                    <div class="checkout-section">
                        <div class="section-header">
                            <div class="section-title">Metode Pembayaran</div>
                        </div>
                        
                        <!-- Categorized Payment Grid -->
                        <div class="p-6 space-y-6">
                            <!-- Virtual Accounts -->
                            <div>
                                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <i class="fas fa-university text-blue-500"></i> Transfer Bank (Virtual Account)
                                </h4>
                                <div class="payment-grid !p-0">
                                    @foreach([
                                        'BCA'   => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/200px-Bank_Central_Asia.svg.png',
                                        'BRI'   => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/200px-BANK_BRI_logo.svg.png',
                                        'MANDIRI' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/200px-Bank_Mandiri_logo_2016.svg.png',
                                        'BNI'   => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f2/BNI_logo.svg/200px-BNI_logo.svg.png',
                                        'BSI'   => 'https://upload.wikimedia.org/wikipedia/id/thumb/a/a4/Bank_Syariah_Indonesia_2021.svg/200px-Bank_Syariah_Indonesia_2021.svg.png',
                                        'PERMATA' => 'https://upload.wikimedia.org/wikipedia/id/thumb/c/cd/PermataBank_logo.svg/200px-PermataBank_logo.svg.png',
                                    ] as $code => $url)
                                    <label class="payment-option-v2" data-code="{{$code}}">
                                        <input type="radio" name="payment_channel" value="{{$code}}">
                                        <img src="{{$url}}" alt="{{$code}}">
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- E-Wallet & QRIS -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                        <i class="fas fa-wallet text-purple-500"></i> Dompet Digital / E-Wallet
                                    </h4>
                                    <div class="payment-grid !p-0 !grid-cols-2">
                                        <label class="payment-option-v2" data-code="ID_DANA">
                                            <input type="radio" name="payment_channel" value="ID_DANA">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/200px-Logo_dana_blue.svg.png">
                                        </label>
                                        <label class="payment-option-v2" data-code="ID_OVO">
                                            <input type="radio" name="payment_channel" value="ID_OVO">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/200px-Logo_ovo_purple.svg.png">
                                        </label>
                                        <label class="payment-option-v2" data-code="ID_SHOPEEPAY">
                                            <input type="radio" name="payment_channel" value="ID_SHOPEEPAY">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/ShopeePay.svg/200px-ShopeePay.svg.png">
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                        <i class="fas fa-qrcode text-red-500"></i> QR Code Payments
                                    </h4>
                                    <div class="payment-grid !p-0 !grid-cols-1">
                                        <label class="payment-option-v2" data-code="QRIS">
                                            <input type="radio" name="payment_channel" value="QRIS" checked>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/QRIS_logo.svg/200px-QRIS_logo.svg.png">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Retail -->
                            <div>
                                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <i class="fas fa-store text-orange-500"></i> Gerai Retail
                                </h4>
                                <div class="payment-grid !p-0 !grid-cols-3 md:!grid-cols-4 lg:!grid-cols-6">
                                    <label class="payment-option-v2" data-code="ALFAMART">
                                        <input type="radio" name="payment_channel" value="ALFAMART">
                                        <img src="https://upload.wikimedia.org/wikipedia/id/thumb/3/30/Alfamart_logo.svg/200px-Alfamart_logo.svg.png">
                                    </label>
                                    <label class="payment-option-v2" data-code="INDOMARET">
                                        <input type="radio" name="payment_channel" value="INDOMARET">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9d/Logo_Indomaret.png/200px-Logo_Indomaret.png">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: SIDEBAR SUMMARY (Sticky) -->
                <div class="lg:col-span-1">
                    <div class="sidebar-summary p-6 border-t-4 border-blue-600">
                        <h2 class="text-sm font-bold text-slate-800 mb-6 flex justify-between uppercase">
                            Ringkasan Pesanan 
                            <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded">ID: #{{ rand(1000, 9999) }}</span>
                        </h2>
                        
                        <div class="space-y-3 mb-6">
                            <div class="price-summary-row">
                                <span>Subtotal Produk</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="price-summary-row">
                                <span>Subtotal Pengiriman</span>
                                <span id="sidebar-ship">Rp 0</span>
                            </div>
                        </div>

                        <div class="total-row">
                            <span class="text-sm font-semibold text-slate-800">Total Pembayaran</span>
                            <div class="text-right">
                                <div class="total-price" id="total-display">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <button type="submit" id="btn-submit" class="btn-checkout-primary mt-6">
                            BUAT PESANAN
                        </button>
                        
                        <div class="mt-6 space-y-3">
                            <div class="flex items-center gap-2 text-[10px] text-slate-400 font-medium">
                                <i class="fas fa-shield-alt text-green-500"></i>
                                Pembayaran Aman & Terenkripsi
                            </div>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Xendit_Logo%2C_2021.svg/100px-Xendit_Logo%2C_2021.svg.png" class="h-3 opacity-30 mx-auto">
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- Mobile Bottom Bar Trigger (Visible on small screens) -->
<div class="mobile-bottom-bar md:hidden">
    <div class="text-right mr-4">
        <div class="text-[10px] text-slate-400">Total Pembayaran</div>
        <div class="text-lg font-bold text-shopee-orange" id="mobile-total">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
    </div>
    <button type="button" onclick="document.getElementById('btn-submit').click()" class="bg-[#ee4d2d] text-white px-8 py-3 font-bold text-sm rounded-sm">Buat Pesanan</button>
</div>

<!-- Loading Overlay -->
<div id="loading" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[9999] hidden items-center justify-center flex-col gap-4">
    <div class="w-12 h-12 border-4 border-white/20 border-t-white rounded-full animate-spin"></div>
    <p class="text-white text-xs font-black uppercase tracking-widest">Securing Payment...</p>
</div>

<!-- Result Overlay -->
<div id="checkout-result-overlay">
    <div class="result-modal">
        <div id="res-va" class="hidden">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6"><i class="fas fa-university text-2xl"></i></div>
            <h3 class="text-xl font-black text-slate-900 mb-2 uppercase tracking-tight">Virtual Account Created</h3>
            <p class="text-xs text-slate-400 font-bold mb-8 italic">Please transfer exact amount to the number below.</p>
            <div class="bg-gray-50 p-6 rounded-2xl border border-dashed mb-8 text-left">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Account Number (<span id="res-bank-name">BCA</span>)</p>
                <div class="flex items-center justify-between">
                    <span id="res-va-code" class="text-3xl font-black text-blue-600 tracking-wider">XXXXXXXXX</span>
                    <button onclick="copy('res-va-code')" class="p-2 text-blue-600 hover:scale-110 transition"><i class="far fa-copy text-lg"></i></button>
                </div>
            </div>
        </div>

        <div id="res-retail" class="hidden">
            <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6"><i class="fas fa-store text-2xl"></i></div>
            <h3 class="text-xl font-black text-slate-900 mb-2 uppercase tracking-tight">Payment Code</h3>
            <p class="text-xs text-slate-400 font-bold mb-8 italic">Show this code to the cashier at <span id="res-retail-name">ALFAMART</span>.</p>
            <div class="bg-red-50/30 p-6 rounded-2xl border border-dashed border-red-200 mb-8 text-left">
                <p class="text-[9px] font-black text-red-400 uppercase tracking-widest mb-2">Merchant Code</p>
                <div class="flex items-center justify-between">
                    <span id="res-retail-code" class="text-3xl font-black text-red-600 tracking-wider">XXXXXXXXX</span>
                    <button onclick="copy('res-retail-code')" class="p-2 text-red-600 hover:scale-110 transition"><i class="far fa-copy text-lg"></i></button>
                </div>
            </div>
        </div>

        <div id="res-qris" class="hidden">
            <div id="qris-box" class="mb-4 inline-block bg-white p-4 border rounded-3xl"></div>
            <h3 class="text-lg font-black text-slate-900 mb-8">SCAN TO PAY</h3>
        </div>

        <div id="res-ewallet" class="hidden">
            <div class="w-16 h-16 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6"><i class="fas fa-wallet text-2xl"></i></div>
            <h3 class="text-xl font-black text-slate-900 mb-6">Redirecting to E-Wallet...</h3>
            <a id="ewallet-btn" href="#" target="_blank" class="btn-primary mb-4">OPEN WALLET APP</a>
        </div>

        <div class="border-t pt-6 flex flex-col gap-4">
            <div class="flex justify-between items-end">
                <div class="text-left">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Amount to Pay</p>
                    <p id="res-amount" class="text-lg font-black text-slate-900">Rp 0</p>
                </div>
                <a href="{{ route('orders.index') }}" class="text-[10px] font-black text-blue-600 underline uppercase tracking-tighter">My Orders</a>
            </div>
            <p class="text-[10px] text-slate-400 font-medium leading-relaxed italic border-l-2 pl-3 border-blue-100">Order status will update automatically upon successful payment. Please do not close this window until you finish payment.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
(function() {
    const subtotal = {{ $subtotal }};
    let shipping = 0;

    // ── Address Helpers ──
    window.toggleAddressEdit = function() {
        const box = document.getElementById('address-edit-box');
        box.classList.toggle('hidden');
    };

    window.saveAddressLocal = function() {
        const name = document.getElementById('cust_name').value.trim();
        const phone = document.getElementById('cust_phone').value.trim();
        const city = document.getElementById('cust_city').value.trim();
        const addr = document.getElementById('cust_address').value.trim();

        if (name && phone && city && addr) {
            document.getElementById('display-address-text').textContent = `${addr}, ${city}`;
            document.querySelectorAll('.cust-detail span:first-child').forEach(s => s.textContent = name);
            document.querySelectorAll('.cust-detail span:last-child').forEach(s => s.textContent = phone);
            toggleAddressEdit();
        } else {
            showAlert({type:'warning', title:'Data Kurang', message:'Lengkapi semua bidang alamat.'});
        }
    };

    // ── Shipping Logic ──
    document.querySelectorAll('input[name="shipping"]').forEach(radio => {
        radio.addEventListener('change', function() {
            shipping = parseInt(this.value);
            const formattedShip = shipping === 0 ? 'Rp 0' : 'Rp ' + f(shipping);
            const total = subtotal + shipping;
            
            document.getElementById('ship-display').textContent = formattedShip;
            document.getElementById('sidebar-ship').textContent = formattedShip;
            document.getElementById('total-display').textContent = 'Rp ' + f(total);
            document.getElementById('mobile-total').textContent = 'Rp ' + f(total);
            document.getElementById('total-summary-display').textContent = 'Rp ' + f(total);
        });
    });

    // ── Payment Selection ──
    document.querySelectorAll('.payment-option-v2').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.payment-option-v2').forEach(i => i.classList.remove('selected'));
            this.classList.add('selected');
            const radio = this.querySelector('input');
            if (radio) radio.checked = true;
        });
    });

    // ── Pre-select first payment if none or handle pre-checked ──
    const checkedPayment = document.querySelector('input[name="payment_channel"]:checked');
    if (checkedPayment) {
        checkedPayment.closest('.payment-option-v2').classList.add('selected');
    }

    // ── Submission Logic ──
    document.getElementById('payment-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const loader = document.getElementById('loading');
        const btn = document.getElementById('btn-submit');
        
        const name = document.getElementById('cust_name').value.trim();
        const phone = document.getElementById('cust_phone').value.trim();
        const address = document.getElementById('cust_address').value.trim();
        const city = document.getElementById('cust_city').value.trim();
        const channel = document.querySelector('input[name="payment_channel"]:checked')?.value;

        if (!name || !phone || !address || !city || !channel) {
            // If address hidden, show it
            if (document.getElementById('address-edit-box').classList.contains('hidden')) {
                toggleAddressEdit();
            }
            return showAlert({type:'warning', title:'Data Belum Lengkap', message:'Silakan lengkapi informasi pengiriman dan pilih metode pembayaran.'});
        }

        btn.disabled = true;
        loader.classList.remove('hidden');
        loader.classList.add('flex');

        try {
            const res = await fetch('{{ route("checkout.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    items: [@foreach($cart as $id => $item){product_id:'{{$id}}', quantity:{{$item['quantity']}}},@endforeach],
                    total: subtotal + shipping,
                    address: `Penerima: ${name} (${phone})\n${address}\n${city}`,
                    shipping_cost: shipping,
                    payment_channel: channel,
                    payer_name: name
                })
            });
            
            const data = await res.json();
            loader.classList.add('hidden');
            loader.classList.remove('flex');

            if (data.order_number) {
                 showResult(data);
            } else {
                btn.disabled = false;
                showAlert({type:'error', title:'Gagal', message: data.message || 'Error sistem pembayaran.'});
            }
        } catch(e) {
            console.error(e);
            loader.classList.add('hidden');
            btn.disabled = false;
            showAlert({type:'error', title:'Error', message: 'Terjadi kesalahan koneksi.'});
        }
    });

    function showResult(d) {
        document.getElementById('res-amount').textContent = 'Rp ' + f(d.amount);
        document.getElementById('checkout-result-overlay').classList.add('show');
        
        // Reset all results
        document.getElementById('res-va').classList.add('hidden');
        document.getElementById('res-retail').classList.add('hidden');
        document.getElementById('res-qris').classList.add('hidden');
        document.getElementById('res-ewallet').classList.add('hidden');

        if (d.type === 'va') {
            document.getElementById('res-bank-name').textContent = d.bank;
            document.getElementById('res-va-code').textContent = d.code;
            document.getElementById('res-va').classList.remove('hidden');
        } else if (d.type === 'retail') {
            document.getElementById('res-retail-name').textContent = d.channel;
            document.getElementById('res-retail-code').textContent = d.code;
            document.getElementById('res-retail').classList.remove('hidden');
        } else if (d.type === 'qris') {
            const box = document.getElementById('qris-box');
            box.innerHTML = '';
            new QRCode(box, {text: d.qr_string, width: 220, height: 220});
            document.getElementById('res-qris').classList.remove('hidden');
        } else if (d.type === 'ewallet') {
             if (d.payment_url) {
                document.getElementById('ewallet-btn').href = d.payment_url;
                document.getElementById('res-ewallet').classList.remove('hidden');
             } else {
                showAlert({type:'success', title:'Notifikasi Terkirim', message:'Silakan buka aplikasi e-wallet Anda untuk membayar.'});
             }
        }
    }

    function f(n) { return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
    
    window.copy = function(id) {
        navigator.clipboard.writeText(document.getElementById(id).innerText).then(() => {
            const b = event.currentTarget; const h = b.innerHTML;
            b.innerHTML = '<i class="fas fa-check text-green-500"></i>';
            setTimeout(() => b.innerHTML = h, 2000);
        });
    };

})();
</script>
@endpush

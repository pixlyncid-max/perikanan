@extends('layouts.app')

@section('title', 'Checkout | ' . get_setting('site_name', 'FISHERIES'))

@push('styles')
<style>
    :root {
        --checkout-primary: #2563eb;
        --checkout-primary-hover: #1d4ed8;
        --checkout-primary-soft: #eff6ff;
        --checkout-border: #f1f5f9;
        --checkout-bg: #f8fafc;
        --checkout-text-main: #0f172a;
        --checkout-text-muted: #64748b;
        --shopee-orange: #fbbf24;
        --shopee-orange-deep: #ea580c;
        --card-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    }

    /* ── Layout & Containers ── */
    .checkout-section { background: #fff; border-radius: 12px; box-shadow: var(--card-shadow); margin-bottom: 20px; overflow: hidden; border: 1px solid var(--checkout-border); }
    .section-header { padding: 24px 28px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #f1f5f9; }
    .section-title { font-size: 1rem; font-weight: 800; color: var(--checkout-text-main); text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 10px; }
    .section-title i { color: var(--checkout-primary); font-size: 1.1rem; }
    
    /* ── Address Block ── */
    .address-card { position: relative; padding: 28px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: var(--card-shadow); margin-bottom: 20px; border: 1px solid var(--checkout-border); }
    .address-card::before { 
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; 
        background-image: repeating-linear-gradient(45deg, #3b82f6, #3b82f6 33px, transparent 33px, transparent 41px, #f43f5e 41px, #f43f5e 74px, transparent 74px, transparent 82px);
        background-size: 116px 4px;
    }
    .address-info-grid { display: grid; grid-template-cols: auto 1fr auto; gap: 20px; align-items: start; }
    .location-pin { color: var(--checkout-primary); font-size: 1.4rem; margin-top: 2px; }
    .cust-detail { font-weight: 800; color: var(--checkout-text-main); margin-bottom: 6px; display: flex; gap: 16px; font-size: 1rem; }
    .cust-address { color: var(--checkout-text-muted); font-size: 0.9rem; line-height: 1.6; font-weight: 500; }
    .btn-change-address { background: #fff; border: 1.5px solid var(--checkout-primary); color: var(--checkout-primary); padding: 6px 14px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; transition: all 0.2s; }
    .btn-change-address:hover { background: var(--checkout-primary-soft); color: var(--checkout-primary-hover); }

    /* ── Product List ── */
    .product-list-header { background: #f8fafc; border-bottom: 1px solid #f1f5f9; padding: 12px 28px; display: grid; grid-template-columns: 1.5fr 1fr 0.5fr 1fr; gap: 16px; }
    .product-row { display: grid; grid-template-columns: 1.5fr 1fr 0.5fr 1fr; gap: 16px; padding: 24px 28px; align-items: center; border-bottom: 1px solid #f8fafc; transition: background 0.2s; }
    .product-row:hover { background: #fafafa; }
    .product-row:last-child { border-bottom: none; }
    .product-info { display: flex; gap: 16px; align-items: center; }
    .product-img { width: 64px; height: 64px; border-radius: 8px; border: 1px solid #f1f5f9; object-fit: cover; }
    .product-name { font-size: 0.95rem; color: var(--checkout-text-main); font-weight: 600; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; }
    .col-label { font-size: 0.75rem; color: var(--checkout-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    .price-tag { font-size: 0.95rem; color: var(--checkout-text-main); font-weight: 500; }
    .subtotal-tag { font-size: 1rem; font-weight: 700; color: var(--checkout-text-main); }

    /* ── Shipping Options ── */
    .shipping-box { background: #fafdff; border-top: 1px solid var(--checkout-border); padding: 24px 28px; display: flex; justify-content: space-between; align-items: center; gap: 20px; }
    .ship-card { border: 2px solid #e2e8f0; border-radius: 10px; padding: 14px 18px; cursor: pointer; transition: all 0.2s; background: #fff; min-width: 180px; display: flex; align-items: center; gap: 12px; flex: 1; }
    .ship-card:hover { border-color: var(--checkout-primary-hover); }
    .ship-card.selected { border-color: var(--checkout-primary); background: var(--checkout-primary-soft); }
    .ship-card input { display: none; }
    .ship-icon { width: 40px; height: 40px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center; color: var(--checkout-primary); box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

    /* ── Payment Selector ── */
    .payment-grid { display: grid; grid-template-cols: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px; padding: 4px 0; }
    .payment-option-v2 { 
        position: relative; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 14px; 
        cursor: pointer; transition: all 0.2s; background: #fff; height: 60px;
        display: flex; align-items: center; justify-content: center;
    }
    .payment-option-v2:hover { border-color: var(--checkout-primary); transform: translateY(-2px); }
    .payment-option-v2.selected { border-color: var(--checkout-primary); background: var(--checkout-primary-soft); }
    .payment-option-v2.selected::after {
        content: '\f058'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
        position: absolute; top: -8px; right: -8px; color: var(--checkout-primary); font-size: 14px; background: #fff; border-radius: 50%; padding: 2px;
    }
    .payment-option-v2 img { max-height: 28px; max-width: 100%; object-fit: contain; }
    .payment-option-v2 input { position: absolute; opacity: 0; }

    /* ── Sticky Summary Sidebar ── */
    .sidebar-summary { position: sticky; top: 100px; background: #fff; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); border: 1px solid var(--checkout-border); }
    .price-summary-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 0.95rem; color: var(--checkout-text-muted); font-weight: 500; }
    .total-row { display: flex; justify-content: space-between; padding: 20px 0; margin-top: 16px; border-top: 1px dashed #e2e8f0; }
    .total-price { color: var(--shopee-orange-deep); font-size: 1.8rem; font-weight: 900; letter-spacing: -0.02em; }

    .btn-checkout-primary { 
        width: 100%; padding: 16px; background: linear-gradient(135deg, #ea580c 0%, #f43f5e 100%); color: #fff; 
        font-weight: 800; font-size: 1.1rem; border-radius: 12px; transition: all 0.3s;
        box-shadow: 0 10px 15px -3px rgba(234, 88, 12, 0.3); text-transform: uppercase;
        display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-checkout-primary:hover { transform: translateY(-2px); opacity: 0.95; }
    .btn-checkout-primary:disabled { background: #cbd5e1; box-shadow: none; cursor: not-allowed; }

    /* ── Results ── */
    #checkout-result-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(8px); z-index: 10000; align-items: center; justify-content: center; padding: 1rem; }
    #checkout-result-overlay.show { display: flex; }
    .result-modal { background: #fff; width: 100%; max-width: 550px; border-radius: 24px; padding: 40px; position: relative; }

    /* ── Address Modal ── */
    #address-modal-overlay { 
        display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); 
        backdrop-filter: blur(8px); z-index: 10000; align-items: center; justify-content: center; padding: 1rem;
    }
    #address-modal-overlay.show { display: flex; }
    .address-modal-content { 
        background: #fff; width: 100%; max-width: 600px; border-radius: 20px; overflow: hidden; 
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes modalSlideUp { from { transform: translateY(40px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    .modal-field-group { position: relative; margin-bottom: 20px; }
    .modal-label-abs { 
        position: absolute; top: -8px; left: 14px; background: #fff; padding: 0 6px; 
        font-size: 11px; font-weight: 800; color: var(--checkout-primary); z-index: 1; pointer-events: none;
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    .modal-input { 
        width: 100%; border: 2px solid #e2e8f0; padding: 14px 18px; border-radius: 10px; 
        font-size: 1rem; color: var(--checkout-text-main); transition: all 0.2s; font-weight: 500;
    }
    .modal-input:focus { outline: none; border-color: var(--checkout-primary); background: #fafafa; }
    .modal-select { 
        width: 100%; border: 2px solid #e2e8f0; padding: 14px 18px; border-radius: 10px; 
        font-size: 1rem; color: var(--checkout-text-main); background-color: #fff; appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%233b82f6'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 18px center; background-size: 16px;
        font-weight: 500;
    }
    .modal-select:focus { outline: none; border-color: var(--checkout-primary); }

    /* Mobile Responsive Overrides */
    @media (max-width: 1024px) {
        .sidebar-summary { position: static; margin-top: 24px; box-shadow: var(--card-shadow); border-radius: 12px; }
    }

    @media (max-width: 768px) {
        .address-card { padding: 16px; border-radius: 8px; }
        .address-info-grid { grid-template-cols: 1fr; gap: 16px; }
        .btn-change-address { width: 100%; padding: 10px; }
        
        .product-list-header { display: none; }
        .product-row { grid-template-columns: 1fr; padding: 16px; gap: 12px; border-radius: 8px; margin: 0 12px; border: 1px solid #f1f5f9; margin-bottom: 12px; }
        .product-row .price-tag, .product-row .subtotal-tag { 
            text-align: left !important; display: flex; justify-content: space-between; align-items: center; 
            padding-top: 8px; border-top: 1px solid #f8fafc;
        }
        .product-row div[data-label]::before { 
            content: attr(data-label); font-size: 0.7rem; color: var(--checkout-text-muted); 
            font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; 
        }
        .product-info { flex-direction: row; align-items: flex-start; gap: 12px; }
        .product-name { font-size: 0.85rem; }
        
        .shipping-box { flex-direction: column; align-items: stretch; padding: 16px; gap: 16px; border-top: none; }
        .shipping-box > div { width: 100%; text-align: left; }
        .ship-card { min-width: 0; width: 100%; box-shadow: none; border-width: 1.5px; }
        
        .result-modal { padding: 24px; border-radius: 16px; width: 95%; max-height: 95vh; overflow-y: auto; }
        
        .address-modal-content { 
            border-radius: 20px 20px 0 0; position: fixed; bottom: 0; left: 0; right: 0; 
            max-height: 85vh; overflow-y: auto; width: 100%; max-width: none;
            animation: sheetSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes sheetSlideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
        #address-modal-overlay { padding: 0; align-items: flex-end; }
        
        .mobile-bottom-bar { 
            position: fixed; bottom: 0; left: 0; right: 0; background: #fff; 
            box-shadow: 0 -10px 25px rgba(0,0,0,0.1); z-index: 1000;
            display: flex; align-items: center; justify-content: flex-end; padding: 12px 20px;
            border-top: 1px solid #f1f5f9;
        }

        .sidebar-summary { padding: 20px; border-radius: 0; border: none; border-top: 1px solid #f1f5f9; box-shadow: none; background: #fff; }
        .sidebar-summary .btn-checkout-primary { display: none; } /* Use mobile bottom bar instead */
    }
</style>
@endpush

@section('content')
<div class="bg-[#f8fafc] min-h-screen py-10 pb-32">
    <div class="max-w-6xl mx-auto px-4">
        
        <!-- Header / Breadcrumb -->
        <div class="flex items-center justify-between mb-8">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-shopping-basket"></i>
                </div>
                <div>
                  <h1 class="text-2xl font-black text-slate-900 tracking-tighter uppercase leading-none">Checkout</h1>
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Lengkapi Pesanan Anda</p>
                </div>
            </a>
            <div class="hidden md:flex items-center gap-8">
                <div class="flex items-center gap-2 text-blue-600">
                    <div class="w-6 h-6 rounded-full border-2 border-blue-600 flex items-center justify-center text-xs font-black">1</div>
                    <span class="text-xs font-black uppercase tracking-widest">Alamat</span>
                </div>
                <div class="w-8 h-0.5 bg-slate-200"></div>
                <div class="flex items-center gap-2 text-slate-400">
                    <div class="w-6 h-6 rounded-full border-2 border-slate-200 flex items-center justify-center text-xs font-black">2</div>
                    <span class="text-xs font-black uppercase tracking-widest">Pembayaran</span>
                </div>
            </div>
        </div>

        <form id="payment-form">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <!-- LEFT: INFO & PRODUCTS -->
                <div class="lg:col-span-3 space-y-6">
                    
                    <!-- 1. Address Section -->
                    <div class="address-card">
                        <div class="address-info-grid">
                            <div class="flex gap-4">
                                <i class="fas fa-map-marker-alt location-pin"></i>
                                <div class="space-y-1">
                                    <h2 class="text-[11px] font-black text-blue-600 uppercase tracking-[0.2em] mb-2">Alamat Pengiriman</h2>
                                    <div class="cust-detail">
                                        <span>{{ $user->name ?? '' }}</span>
                                        <div class="w-1 h-1 rounded-full bg-slate-300 my-auto"></div>
                                        <span>{{ $user->phone ?? '' }}</span>
                                    </div>
                                    <div class="cust-address">
                                        <span id="display-address-text" class="italic">Silakan lengkapi alamat detail di bawah ini...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" onclick="toggleAddressEdit()" class="btn-change-address uppercase">Ubah</button>
                            </div>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" id="cust_name" name="cust_name" value="{{ $user->name ?? '' }}">
                        <input type="hidden" id="cust_phone" name="cust_phone" value="{{ $user->phone ?? '' }}">
                        <input type="hidden" id="cust_city" name="cust_city" value="">
                        <input type="hidden" id="cust_address" name="cust_address" value="">
                    </div>

                    <!-- 1.5 Drop Point Location Section -->
                    <div class="checkout-section" id="location-section">
                        <div class="section-header">
                            <h2 class="section-title"><i class="fas fa-store-alt"></i> Lokasi Pengambilan (Drop Point)</h2>
                        </div>
                        <div class="p-6 md:p-8 space-y-6">
                            <p class="text-sm font-medium text-slate-500">Pilih lokasi supplier/drop point untuk mengambil dan memproses pesanan ini.</p>
                            
                            <div class="flex flex-col md:flex-row gap-4">
                                <button type="button" onclick="autoDetectLocation()" class="md:w-1/2 flex items-center justify-center gap-3 py-4 px-6 bg-blue-50 border-2 border-blue-600 rounded-xl text-blue-700 font-bold hover:bg-blue-600 hover:text-white transition">
                                    <i class="fas fa-location-arrow"></i> Cari Lokasi Terdekat
                                </button>
                                <button type="button" onclick="toggleManualLocation()" class="md:w-1/2 flex items-center justify-center gap-3 py-4 px-6 bg-slate-50 border-2 border-slate-200 rounded-xl text-slate-600 font-bold hover:bg-slate-100 transition">
                                    <i class="fas fa-list-ul"></i> Pilih Lokasi Manual
                                </button>
                            </div>

                            <!-- Manual location select dropdown (hidden by default) -->
                            <div id="manual-location-area" class="hidden mt-4">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Pilih dari daftar lokasi tersedia</label>
                                <select id="manual_loc_select" class="w-full border-2 border-e2e8f0 padding-14px-18px rounded-lg p-3 outline-none focus:border-blue-500" onchange="selectLocation(this.value)">
                                    <option value="" disabled selected>Memuat lokasi...</option>
                                </select>
                            </div>

                            <!-- Selected Location Box -->
                            <div id="selected-location-box" class="hidden mt-6 bg-emerald-50 border border-emerald-200 rounded-xl p-5 relative overflow-hidden">
                                <div class="absolute top-0 right-0 p-4 opacity-10"><i class="fas fa-check-circle text-5xl text-emerald-600"></i></div>
                                <h3 class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-1">Lokasi Terpilih</h3>
                                <div class="text-lg font-bold text-slate-800" id="selected-loc-name">Nama Lokasi</div>
                                <div class="text-sm text-slate-500 mt-1" id="selected-loc-address">Alamat Detail Lokasi</div>
                                <div class="text-xs font-bold text-emerald-600 mt-2 bg-white px-3 py-1 inline-block rounded-md shadow-sm border border-emerald-100" id="selected-loc-distance">Jarak: -</div>
                                <input type="hidden" id="selected_location_id" name="location_id" value="">
                            </div>
                        </div>
                    </div>

                    <!-- 2. Product Review Section -->
                    <div class="checkout-section">
                        <div class="product-list-header items-center">
                            <div class="col-label">Produk Dipesan</div>
                            <div class="col-label text-center">Harga Satuan</div>
                            <div class="col-label text-center">Jumlah</div>
                            <div class="col-label text-right">Subtotal Produk</div>
                        </div>

                        <div class="divide-y divide-slate-50">
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
                                <div class="price-tag text-center" data-label="Harga">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                                <div class="price-tag text-center" data-label="Jumlah">{{ $item['quantity'] }}</div>
                                <div class="subtotal-tag text-right" data-label="Subtotal">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Shipping Option -->
                        <div class="shipping-box">
                            <div class="flex flex-col md:flex-row md:items-center gap-6">
                                <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap"><i class="fas fa-shipping-fast text-blue-500 mr-2"></i> Opsi Pengiriman:</span>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <label class="ship-card selected" onclick="updateShip(this, 0)">
                                        <input type="radio" name="shipping" value="0" checked>
                                        <div class="ship-icon"><i class="fas fa-walking"></i></div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-800">Ambil Sendiri</div>
                                            <div class="text-[10px] text-green-600 font-bold uppercase tracking-tighter">Gratis Ongkir</div>
                                        </div>
                                    </label>
                                    <label class="ship-card" onclick="updateShip(this, 15000)">
                                        <input type="radio" name="shipping" value="15000">
                                        <div class="ship-icon"><i class="fas fa-truck"></i></div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-800">Kurir Lokal</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">1-3 Hari Kerja</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="text-right whitespace-nowrap">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ongkos Kirim</div>
                                <div class="text-lg font-black text-blue-600" id="ship-display">Rp 0</div>
                            </div>
                        </div>

                        <!-- Pesanan Subtotal -->
                        <div class="p-6 bg-slate-50/50 flex justify-end items-center gap-6">
                            <div class="text-right">
                              <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Total Pesanan ({{ count($cart) }} Produk)</p>
                              <div class="text-2xl font-black text-blue-600 mt-1" id="total-summary-display">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Payment Method Section -->
                    <div class="checkout-section">
                        <div class="section-header">
                            <h2 class="section-title"><i class="fas fa-wallet"></i> Metode Pembayaran</h2>
                        </div>
                        
                        <div class="p-8 space-y-8">
                            <!-- Virtual Accounts -->
                            <div>
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div> Transfer Bank (Virtual Account)
                                </h4>
                                <div class="payment-grid">
                                    @foreach([
                                        'BCA'   => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/200px-Bank_Central_Asia.svg.png',
                                        'BRI'   => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/200px-BANK_BRI_logo.svg.png',
                                        'MANDIRI' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/200px-Bank_Mandiri_logo_2016.svg.png',
                                        'BNI'   => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f2/BNI_logo.svg/200px-BNI_logo.svg.png',
                                        'BSI'   => 'https://upload.wikimedia.org/wikipedia/id/thumb/a/a4/Bank_Syariah_Indonesia_2021.svg/200px-Bank_Syariah_Indonesia_2021.svg.png',
                                    ] as $code => $url)
                                    <label class="payment-option-v2" onclick="selectPayment(this)">
                                        <input type="radio" name="payment_channel" value="{{$code}}">
                                        <img src="{{$url}}" alt="{{$code}}">
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <!-- E-Wallet -->
                                <div>
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div> E-Wallet
                                    </h4>
                                    <div class="grid grid-cols-2 gap-3">
                                        @foreach(['ID_DANA' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/200px-Logo_dana_blue.svg.png', 'ID_OVO' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/200px-Logo_ovo_purple.svg.png'] as $code => $url)
                                        <label class="payment-option-v2" onclick="selectPayment(this)">
                                            <input type="radio" name="payment_channel" value="{{$code}}">
                                            <img src="{{$url}}">
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- QRIS -->
                                <div>
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> QR Code
                                    </h4>
                                    <label class="payment-option-v2 h-[60px]" onclick="selectPayment(this)">
                                        <input type="radio" name="payment_channel" value="QRIS" checked>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/QRIS_logo.svg/200px-QRIS_logo.svg.png" class="h-8">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: SIDEBAR SUMMARY -->
                <div class="lg:col-span-1">
                    <div class="sidebar-summary p-8">
                        <div class="flex items-center gap-2 mb-8">
                            <i class="fas fa-file-invoice-dollar text-slate-400"></i>
                            <h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">Detail Ringkasan</h2>
                        </div>
                        
                        <div class="space-y-4 mb-8">
                            <div class="price-summary-row">
                                <span>Subtotal Belanja</span>
                                <span class="text-slate-800 font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="price-summary-row">
                                <span>Ongkos Kirim</span>
                                <span id="sidebar-ship" class="text-slate-800 font-bold">Rp 0</span>
                            </div>
                        </div>

                        <div class="total-row mb-8">
                            <span class="text-xs font-black text-slate-800 uppercase tracking-widest">Total Bayar</span>
                            <div class="total-price" id="total-display">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                        </div>

                        <button type="submit" id="btn-submit" class="btn-checkout-primary">
                            <i class="fas fa-check-circle"></i> BUAT PESANAN
                        </button>
                        
                        <div class="mt-10 pt-8 border-t border-slate-100 space-y-4">
                            <div class="flex items-center gap-3 text-[9px] text-slate-400 font-bold uppercase tracking-widest">
                                <i class="fas fa-shield-alt text-emerald-500 text-sm"></i>
                                Keamanan & Privasi Terjamin
                            </div>
                            <div class="flex items-center justify-between opacity-30 grayscale contrast-150">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Xendit_Logo%2C_2021.svg/80px-Xendit_Logo%2C_2021.svg.png">
                                <i class="fab fa-cc-visa text-xl"></i>
                                <i class="fab fa-cc-mastercard text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- Mobile Bottom Bar -->
<div class="mobile-bottom-bar md:hidden">
    <div class="text-right mr-4">
        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Total Pembayaran</div>
        <div class="text-xl font-black text-orange-600" id="mobile-total">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
    </div>
    <button type="button" onclick="document.getElementById('btn-submit').click()" class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3.5 font-black text-xs rounded-xl shadow-lg shadow-orange-200 transition active:scale-95 uppercase tracking-widest">Pesan</button>
</div>

<!-- Loading Overlay -->
<div id="loading" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[10002] hidden items-center justify-center flex-col gap-6">
    <div class="relative">
        <div class="w-16 h-16 border-4 border-white/10 border-t-white rounded-full animate-spin"></div>
        <div class="absolute inset-0 flex items-center justify-center"><i class="fas fa-lock text-white text-xs"></i></div>
    </div>
    <div class="text-center">
        <p class="text-white text-sm font-black uppercase tracking-[0.2em] animate-pulse">Menyiapkan Pembayaran</p>
        <p class="text-white/40 text-[9px] mt-2 font-bold uppercase tracking-widest">Mohon tunggu sebentar...</p>
    </div>
</div>

<!-- Result Overlay -->
<div id="checkout-result-overlay">
    <div class="result-modal text-center">
        <!-- VA Result -->
        <div id="res-va" class="hidden">
            <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner shadow-blue-100"><i class="fas fa-university text-3xl"></i></div>
            <h3 class="text-2xl font-black text-slate-900 mb-2 uppercase tracking-tighter leading-none">Virtual Account</h3>
            <p class="text-[10px] text-slate-400 font-bold mb-10 uppercase tracking-widest">Silakan bayar tepat ke nomor di bawah ini</p>
            <div class="bg-slate-50 p-8 rounded-3xl border border-dashed border-slate-200 mb-10 text-left relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-3 opacity-5"><i class="fas fa-money-check-alt text-6xl"></i></div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Nomor Rekening (<span id="res-bank-name">BCA</span>)</p>
                <div class="flex items-center justify-between">
                    <span id="res-va-code" class="text-3xl font-black text-blue-600 tracking-wider">XXXXXXXXX</span>
                    <button onclick="copy('res-va-code')" class="w-10 h-10 bg-white rounded-xl text-blue-600 shadow-sm hover:scale-110 active:scale-95 transition flex items-center justify-center"><i class="far fa-copy"></i></button>
                </div>
            </div>
        </div>

        <!-- Retail Result -->
        <div id="res-retail" class="hidden">
            <div class="w-20 h-20 bg-orange-50 text-orange-600 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner shadow-orange-100"><i class="fas fa-store text-3xl"></i></div>
            <h3 class="text-2xl font-black text-slate-900 mb-2 uppercase tracking-tighter leading-none">Kode Pembayaran</h3>
            <p class="text-[10px] text-slate-400 font-bold mb-10 uppercase tracking-widest italic text-center">Tunjukkan kode ini ke kasir <span id="res-retail-name" class="underline">ALFAMART</span>.</p>
            <div class="bg-orange-50/50 p-8 rounded-3xl border border-dashed border-orange-200 mb-10 text-left relative group">
              <div class="absolute top-0 right-0 p-3 opacity-5"><i class="fas fa-barcode text-6xl"></i></div>
                <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest mb-3">Merchant Code</p>
                <div class="flex items-center justify-between">
                    <span id="res-retail-code" class="text-3xl font-black text-orange-600 tracking-wider">XXXXXXXXX</span>
                    <button onclick="copy('res-retail-code')" class="w-10 h-10 bg-white rounded-xl text-orange-600 shadow-sm hover:scale-110 active:scale-95 transition flex items-center justify-center"><i class="far fa-copy"></i></button>
                </div>
            </div>
        </div>

        <!-- QRIS Result -->
        <div id="res-qris" class="hidden">
            <div id="qris-box" class="mb-4 inline-block bg-white p-6 border-4 border-slate-50 rounded-[40px] shadow-2xl"></div>
            <h3 class="text-xl font-black text-slate-900 mb-10 tracking-widest uppercase mt-4">SCAN TO PAY</h3>
        </div>

        <!-- E-Wallet Result -->
        <div id="res-ewallet" class="hidden">
            <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner shadow-emerald-100"><i class="fas fa-wallet text-3xl"></i></div>
            <h3 class="text-2xl font-black text-slate-900 mb-6 uppercase tracking-tighter leading-none">Otomatis Terhubung</h3>
            <p class="text-sm text-slate-500 font-medium mb-10">Buka aplikasi E-Wallet Anda untuk menyelesaikan pembayaran.</p>
            <a id="ewallet-btn" href="#" target="_blank" class="w-full inline-flex items-center justify-center bg-emerald-600 text-white rounded-2xl py-4 font-black uppercase text-sm tracking-widest shadow-xl shadow-emerald-100 hover:scale-[1.02] active:scale-100 transition">OPEN WALLET APP</a>
        </div>

        <div class="border-t border-slate-50 pt-10 mt-10">
            <div class="flex justify-between items-end mb-10">
                <div class="text-left">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Pembayaran</p>
                    <p id="res-amount" class="text-3xl font-black text-slate-900 tracking-tighter">Rp 0</p>
                </div>
                <a href="{{ route('orders.index') }}" class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-500 hover:bg-blue-600 hover:text-white transition group">
                  <i class="fas fa-box-open group-hover:animate-bounce"></i>
                </a>
            </div>
            <div class="bg-blue-50/50 p-5 rounded-2xl border-l-4 border-blue-400 text-left">
               <p class="text-[10px] text-blue-800 font-bold leading-relaxed italic">Status pesanan akan diperbarui secara otomatis setelah pembayaran terverifikasi. Mohon jangan menutup halaman ini sebelum transaksi selesai.</p>
            </div>
        </div>
    </div>
</div>

<!-- New Address Modal -->
<div id="address-modal-overlay">
    <div class="address-modal-content">
        <div class="px-8 py-6 border-b flex justify-between items-center bg-slate-50">
            <div>
              <h3 class="text-xl font-bold text-slate-900">Ubah Alamat</h3>
              <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Gunakan Dropdown untuk wilayah</p>
            </div>
            <button type="button" onclick="closeAddressModal()" class="w-10 h-10 rounded-full bg-white shadow-sm text-slate-400 hover:text-red-500 transition flex items-center justify-center"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="p-4 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="modal-field-group">
                    <label class="modal-label-abs">Penerima</label>
                    <input type="text" id="modal_cust_name" class="modal-input" value="{{ $user->name ?? '' }}" placeholder="Nama Lengkap">
                </div>
                <div class="modal-field-group">
                    <label class="modal-label-abs">No. HP / WhatsApp</label>
                    <input type="tel" id="modal_cust_phone" class="modal-input" value="{{ $user->phone ?? '' }}" placeholder="08xxxxxxxx">
                </div>

                <div class="md:col-span-2 modal-field-group">
                    <label class="modal-label-abs">Provinsi</label>
                    <select id="modal_prov" class="modal-select">
                        <option value="" disabled selected>Pilih Provinsi</option>
                    </select>
                </div>

                <div class="md:col-span-2 modal-field-group">
                    <label class="modal-label-abs">Kota / Kabupaten</label>
                    <select id="modal_city" class="modal-select" disabled>
                        <option value="" disabled selected>Pilih Kota/Kabupaten</option>
                    </select>
                </div>

                <div class="md:col-span-2 modal-field-group">
                    <label class="modal-label-abs">Kecamatan</label>
                    <select id="modal_dist" class="modal-select" disabled>
                        <option value="" disabled selected>Pilih Kecamatan</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Alamat Jalan & No. Rumah</span>
                        <button type="button" onclick="autoDetectShippingAddress()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-md shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 active:translate-y-0 active:shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                            <i class="fas fa-location-crosshairs animate-pulse"></i> Lacak Otomatis GPS
                        </button>
                    </div>
                    <textarea id="modal_cust_address" rows="3" class="w-full border-2 border-slate-200 rounded-lg p-3 outline-none focus:border-blue-500 transition resize-none text-sm font-semibold text-slate-700 placeholder-slate-300" placeholder="Nama Jalan, Blok, Nomor Rumah, dsb."></textarea>
                </div>

                <div class="md:col-span-2 modal-field-group">
                    <label class="modal-label-abs">Detail (Patokan/Unit)</label>
                    <input type="text" id="modal_cust_detail" class="modal-input" placeholder="Contoh: Depan Masjid Al-Ikhlas">
                </div>
            </div>

            <div class="mt-10 flex items-center justify-end gap-2">
                <button type="button" onclick="closeAddressModal()" class="px-8 py-3.5 font-black text-slate-400 hover:text-slate-600 transition text-xs uppercase tracking-widest">Batal</button>
                <button type="button" onclick="saveAddressFromModal()" class="px-12 py-3.5 bg-blue-600 text-white font-black rounded-xl hover:bg-blue-700 transition shadow-xl shadow-blue-200 text-xs uppercase tracking-widest active:scale-95">Simpan Alamat</button>
            </div>
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

    // Initialization: Load stored address if available
    (function initAddress() {
        const rawAddr = @json($user->address ?? '');
        if (rawAddr && rawAddr.trim() !== '') {
            try {
                // Determine if it needs one or two parses (handles stringified JSON in DB)
                let d = (typeof rawAddr === 'string' && rawAddr.startsWith('{')) ? JSON.parse(rawAddr) : rawAddr;
                if (typeof d === 'string') d = JSON.parse(d); // Second parse if still string
                
                if (d && d.prov_name) {
                    const fullRegion = `${d.prov_name}, ${d.city_name}, ${d.dist_name}`;
                    const fullAddr = `${d.address}${d.detail ? ', ' + d.detail : ''}`;
                    
                    document.getElementById('cust_city').value = fullRegion;
                    document.getElementById('cust_address').value = fullAddr;
                    document.getElementById('display-address-text').textContent = `${fullAddr}, ${fullRegion}`;
                    
                    // Pre-fill modal fields for next "Ubah" click
                    document.getElementById('modal_cust_address').value = d.address;
                    document.getElementById('modal_cust_detail').value = d.detail || '';
                    
                    // Ensure displayed name/phone match (phone might have been updated)
                    const p = @json($user->phone ?? '');
                    if (p) document.querySelectorAll('.cust-detail span:last-child').forEach(s => s.textContent = p);
                }
            } catch (e) { console.error('Address parse error', e, rawAddr); }
        }
    })();

    const regionAPI = 'https://www.emsifa.com/api-wilayah-indonesia/api';
    let provinces = [];
    
    window.autoDetectShippingAddress = function() {
        if (!navigator.geolocation) {
            return alert('Browser Anda tidak mendukung fitur lokasi.');
        }
        
        const loader = document.getElementById('loading');
        if(loader) { loader.classList.remove('hidden'); loader.classList.add('flex'); loader.querySelector('p').textContent = "Melacak Alamat dengan GPS..."; }
        
        navigator.geolocation.getCurrentPosition(async (position) => {
            try {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                // Using OpenStreetMap Nominatim for free reverse geocoding
                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`);
                if (loader) { loader.classList.add('hidden'); loader.classList.remove('flex'); }
                
                const data = await res.json();
                if (data && data.display_name) {
                    document.getElementById('modal_cust_address').value = data.display_name;
                    // Also focus on detail if they need to add something like "Pagar hitam"
                    document.getElementById('modal_cust_detail').focus();
                } else {
                    alert('Gagal mendapatkan informasi alamat detail dari GPS.');
                }
            } catch(e) {
                if (loader) { loader.classList.add('hidden'); loader.classList.remove('flex'); }
                alert('Terjadi kesalahan jaringan rute.');
            }
        }, (error) => {
            if (loader) { loader.classList.add('hidden'); loader.classList.remove('flex'); }
            let msg = 'Gagal mambaca lokasi.';
            if(error.code == error.PERMISSION_DENIED) msg = "Anda menolak izin akses lokasi browser.";
            alert(msg);
        }, { enableHighAccuracy: true });
    };

    async function loadProvinces() {
        if (provinces.length > 0) return;
        try {
            const res = await fetch(`${regionAPI}/provinces.json`);
            provinces = await res.json();
            const select = document.getElementById('modal_prov');
            select.innerHTML = '<option value="" disabled selected>Pilih Provinsi</option>';
            provinces.sort((a,b) => a.name.localeCompare(b.name)).forEach(p => {
                const opt = document.createElement('option'); opt.value = p.id; opt.textContent = p.name; select.appendChild(opt);
            });
        } catch (e) { console.error(e); }
    }

    document.getElementById('modal_prov').addEventListener('change', async function() {
        const provId = this.value;
        const citySelect = document.getElementById('modal_city');
        const distSelect = document.getElementById('modal_dist');
        citySelect.disabled = true; distSelect.disabled = true;
        citySelect.innerHTML = '<option value="" disabled selected>Loading...</option>';
        try {
            const res = await fetch(`${regionAPI}/regencies/${provId}.json`);
            const cities = await res.json();
            citySelect.innerHTML = '<option value="" disabled selected>Pilih Kota/Kabupaten</option>';
            cities.sort((a,b) => a.name.localeCompare(b.name)).forEach(c => {
                const opt = document.createElement('option'); opt.value = c.id; opt.textContent = c.name; citySelect.appendChild(opt);
            });
            citySelect.disabled = false;
        } catch (e) { console.error(e); }
    });

    document.getElementById('modal_city').addEventListener('change', async function() {
        const cityId = this.value;
        const distSelect = document.getElementById('modal_dist');
        distSelect.disabled = true;
        distSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';
        try {
            const res = await fetch(`${regionAPI}/districts/${cityId}.json`);
            const districts = await res.json();
            distSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';
            districts.sort((a,b) => a.name.localeCompare(b.name)).forEach(d => {
                const opt = document.createElement('option'); opt.value = d.id; opt.textContent = d.name; distSelect.appendChild(opt);
            });
            distSelect.disabled = false;
        } catch (e) { console.error(e); }
    });

    // === Location Logic (Haversine & APIs) ===
    let availableLocations = [];
    const cartItemsData = [@foreach($cart as $id => $item){product_id:'{{$id}}', quantity:{{$item['quantity']}}},@endforeach];

    async function loadValidLocations() {
        try {
            const res = await fetch('{{ route("api.locations.valid") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ items: cartItemsData })
            });
            const data = await res.json();
            const select = document.getElementById('manual_loc_select');
            select.innerHTML = '<option value="" disabled selected>Pilih Lokasi</option>';
            
            if (data.success && data.data && data.data.length > 0) {
                availableLocations = data.data;
                availableLocations.forEach(loc => {
                    const opt = document.createElement('option'); 
                    opt.value = loc.id;
                    let text = loc.nama;
                    if(loc.available_stock !== undefined) text += ` (Stok Tersedia: ${loc.available_stock})`;
                    opt.textContent = text; 
                    select.appendChild(opt);
                });
            } else {
                availableLocations = [];
                const msg = data.message || 'Maaf, produk tidak tersedia komplit di lokasi manapun.';
                select.innerHTML = `<option value="" disabled selected>${msg}</option>`;
            }
        } catch(e) { console.error('Failed to load valid locations', e); }
    }

    loadValidLocations(); // On initialization

    window.toggleManualLocation = function() {
        document.getElementById('manual-location-area').classList.toggle('hidden');
    };

    window.selectLocation = function(id) {
        const loc = availableLocations.find(l => l.id == id);
        if (loc) {
            document.getElementById('selected_location_id').value = loc.id;
            let stockHtml = loc.available_stock !== undefined ? `<span class="ml-2 text-xs font-bold text-emerald-600 bg-emerald-100 px-2 py-1 rounded-md uppercase tracking-wider relative -top-0.5">Tersedia ${loc.available_stock} Stok</span>` : '';
            document.getElementById('selected-loc-name').innerHTML = loc.nama + stockHtml;
            document.getElementById('selected-loc-address').textContent = loc.alamat || '-';
            
            const distBox = document.getElementById('selected-loc-distance');
            if (loc.distance_km) {
                distBox.textContent = `Jarak: ${loc.distance_km} km`;
                distBox.classList.remove('hidden');
            } else {
                distBox.classList.add('hidden');
            }
            
            document.getElementById('selected-location-box').classList.remove('hidden');
        }
    };

    window.autoDetectLocation = function() {
        if (!navigator.geolocation) {
            return showAlert({type:'error', title:'Error', message:'Browser Anda tidak mendukung fitur lokasi.'});
        }
        
        const loader = document.getElementById('loading');
        loader.classList.remove('hidden'); loader.classList.add('flex');
        loader.querySelector('p').textContent = "Melacak Lokasi Anda...";

        navigator.geolocation.getCurrentPosition(async (position) => {
            try {
                const res = await fetch('{{ route("api.locations.nearest") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ 
                        lat: position.coords.latitude, 
                        lng: position.coords.longitude,
                        items: cartItemsData 
                    })
                });
                
                loader.classList.add('hidden'); loader.classList.remove('flex');
                
                const data = await res.json();
                if (data.success && data.data) {
                    // Update our manual dropdown logic just in case
                    const loc = data.data;
                    document.getElementById('selected_location_id').value = loc.id;
                    let stockHtml = loc.available_stock !== undefined ? `<span class="ml-2 text-xs font-bold text-emerald-600 bg-emerald-100 px-2 py-1 rounded-md uppercase tracking-wider relative -top-0.5">Tersedia ${loc.available_stock} Stok</span>` : '';
                    document.getElementById('selected-loc-name').innerHTML = loc.nama + stockHtml;
                    document.getElementById('selected-loc-address').textContent = loc.alamat || '-';
                    
                    const distBox = document.getElementById('selected-loc-distance');
                    distBox.textContent = `Jarak Terdekat: ${loc.distance_km} km`;
                    distBox.classList.remove('hidden');
                    
                    document.getElementById('selected-location-box').classList.remove('hidden');
                    showAlert({type:'success', title:'Lokasi Ditemukan', message:`Terpilih ${loc.nama} sejauh ${loc.distance_km}km`});
                } else {
                    showAlert({type:'warning', title:'Gagal', message: data.message || 'Gagal menemukan lokasi.'});
                }
            } catch(e) {
                loader.classList.add('hidden'); loader.classList.remove('flex');
                showAlert({type:'error', title:'Error', message:'Kesalahan jaringan saat mencari lokasi terdekat.'});
            }
        }, (error) => {
            loader.classList.add('hidden'); loader.classList.remove('flex');
            let msg = 'Gagal mengakses izin lokasi.';
            if (error.code == error.PERMISSION_DENIED) msg = "Anda menolak permintaan akses lokasi.";
            showAlert({type:'warning', title:'Izin Ditolak', message: msg});
        });
    };
    // === End Limit Logic ===

    window.toggleAddressEdit = function() {
        document.getElementById('address-modal-overlay').classList.add('show');
        document.body.style.overflow = 'hidden';
        loadProvinces();
    };

    window.closeAddressModal = function() {
        document.getElementById('address-modal-overlay').classList.remove('show');
        document.body.style.overflow = 'auto';
    };

    window.saveAddressFromModal = async function() {
        const name = document.getElementById('modal_cust_name').value.trim();
        const phone = document.getElementById('modal_cust_phone').value.trim();
        const provEl = document.getElementById('modal_prov');
        const cityEl = document.getElementById('modal_city');
        const distEl = document.getElementById('modal_dist');
        
        const prov = provEl.options[provEl.selectedIndex]?.text;
        const provId = provEl.value;
        const city = cityEl.options[cityEl.selectedIndex]?.text;
        const cityId = cityEl.value;
        const dist = distEl.options[distEl.selectedIndex]?.text;
        const distId = distEl.value;
        const addr = document.getElementById('modal_cust_address').value.trim();
        const detail = document.getElementById('modal_cust_detail').value.trim();

        if (name && phone && prov && !prov.includes('Pilih') && city && !city.includes('Pilih') && dist && !dist.includes('Pilih') && addr) {
            const btn = document.querySelector('button[onclick="saveAddressFromModal()"]');
            const originalText = btn.textContent;
            btn.disabled = true; btn.textContent = 'Menyimpan...';

            const fullRegion = `${prov}, ${city}, ${dist}`;
            const fullAddr = `${addr}${detail ? ', ' + detail : ''}`;
            
            // Local UI Update
            document.getElementById('cust_name').value = name;
            document.getElementById('cust_phone').value = phone;
            document.getElementById('cust_city').value = fullRegion;
            document.getElementById('cust_address').value = fullAddr;

            document.getElementById('display-address-text').textContent = `${fullAddr}, ${fullRegion}`;
            document.querySelectorAll('.cust-detail span:first-child').forEach(s => s.textContent = name);
            document.querySelectorAll('.cust-detail span:last-child').forEach(s => s.textContent = phone);
            
            // Persistence via AJAX
            const addressData = JSON.stringify({
                prov_id: provId, prov_name: prov,
                city_id: cityId, city_name: city,
                dist_id: distId, dist_name: dist,
                address: addr, detail: detail
            });

            try {
                await fetch('{{ route("profile.update-address") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ address: addressData, phone: phone })
                });
            } catch (e) { console.error('Failed to persist address', e); }

            btn.disabled = false; btn.textContent = originalText;
            closeAddressModal();
        } else {
            showAlert({type:'warning', title:'Data Kurang', message:'Lengkapi semua bidang alamat termasuk wilayah.'});
        }
    };

    window.selectPayment = function(el) {
        document.querySelectorAll('.payment-option-v2').forEach(i => i.classList.remove('selected'));
        el.classList.add('selected');
        const radio = el.querySelector('input');
        if (radio) radio.checked = true;
    };

    window.updateShip = function(el, val) {
        document.querySelectorAll('.ship-card').forEach(i => i.classList.remove('selected'));
        el.classList.add('selected');
        const radio = el.querySelector('input');
        if (radio) {
          radio.checked = true;
          shipping = val;
          const total = subtotal + shipping;
          
          document.getElementById('ship-display').textContent = shipping === 0 ? 'Rp 0' : 'Rp ' + f(shipping);
          document.getElementById('sidebar-ship').textContent = shipping === 0 ? 'Rp 0' : 'Rp ' + f(shipping);
          document.getElementById('total-display').textContent = 'Rp ' + f(total);
          document.getElementById('mobile-total').textContent = 'Rp ' + f(total);
          document.getElementById('total-summary-display').textContent = 'Rp ' + f(total);
        }
    };

    document.getElementById('payment-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const loader = document.getElementById('loading');
        const btn = document.getElementById('btn-submit');
        
        const name = document.getElementById('cust_name').value.trim();
        const phone = document.getElementById('cust_phone').value.trim();
        const address = document.getElementById('cust_address').value.trim();
        const city = document.getElementById('cust_city').value.trim();
        const channel = document.querySelector('input[name="payment_channel"]:checked')?.value;

        if (!name || !phone || !address || !city) {
            toggleAddressEdit();
            return showAlert({type:'warning', title:'Alamat Belum Lengkap', message:'Silakan lengkapi alamat pengiriman Anda.'});
        }
        if (!channel) {
            return showAlert({type:'warning', title:'Metode Pembayaran', message:'Pilih metode pembayaran terlebih dahulu.'});
        }
        if (!document.getElementById('selected_location_id').value) {
            return showAlert({type:'warning', title:'Lokasi Pengambilan', message:'Pilih lokasi pengambilan (Drop Point) terlebih dahulu.'});
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
                    payer_name: name,
                    location_id: document.getElementById('selected_location_id').value
                })
            });
            
            const data = await res.json();
            loader.classList.add('hidden');
            loader.classList.remove('flex');

            if (data.order_number) {
                 showResult(data);
                 window.scrollTo({top:0, behavior:'smooth'});
            } else {
                btn.disabled = false;
                showAlert({type:'error', title:'Gagal', message: data.message || 'Error pembayaran.'});
            }
        } catch(e) {
            console.error(e);
            loader.classList.add('hidden');
            btn.disabled = false;
            showAlert({type:'error', title:'Error', message: 'Kesalahan koneksi server.'});
        }
    });

    function showResult(d) {
        document.getElementById('res-amount').textContent = 'Rp ' + f(d.amount);
        document.getElementById('checkout-result-overlay').classList.add('show');
        document.body.style.overflow = 'hidden';
        
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
            new QRCode(box, {text: d.qr_string, width: 240, height: 240, colorDark: "#0f172a"});
            document.getElementById('res-qris').classList.remove('hidden');
        } else if (d.type === 'ewallet') {
             if (d.payment_url) {
                document.getElementById('ewallet-btn').href = d.payment_url;
                document.getElementById('res-ewallet').classList.remove('hidden');
             } else {
                showAlert({type:'success', title:'Terkirim', message:'Buka aplikasi wallet Anda.'});
             }
        }
    }

    function f(n) { return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
    
    window.copy = function(id) {
        navigator.clipboard.writeText(document.getElementById(id).innerText).then(() => {
            const b = event.currentTarget; const h = b.innerHTML;
            b.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => b.innerHTML = h, 2000);
        });
    };
})();
</script>
@endpush

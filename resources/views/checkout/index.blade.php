@extends('layouts.app')

@section('title', 'Checkout | ' . get_setting('site_name', 'FISHERIES'))

@push('styles')
<script src="https://js.xendit.co/v1/xendit.min.js"></script>
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
    .product-info-container { display: flex; gap: 16px; align-items: center; }
    .product-img { width: 64px; height: 64px; border-radius: 8px; border: 1px solid #f1f5f9; object-fit: cover; }
    .product-detail { display: flex; flex-direction: column; gap: 2px; }
    .product-name { font-size: 0.95rem; color: var(--checkout-text-main); font-weight: 600; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; }
    .product-variant { font-size: 0.8rem; color: var(--checkout-text-muted); font-weight: 500; }
    .mobile-price-row { display: none; }
    .col-label { font-size: 0.75rem; color: var(--checkout-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    .price-tag { font-size: 0.95rem; color: var(--checkout-text-main); font-weight: 500; }
    .subtotal-tag { font-size: 1rem; font-weight: 700; color: var(--checkout-text-main); }

    /* ── Shipping Options ── */
    .shipping-box { background: #fafdff; border-top: 1px solid var(--checkout-border); padding: 24px 28px; }
    .ship-card { 
        position: relative; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px 24px; 
        cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); background: #fff; 
        display: flex; flex-direction: column; gap: 6px; overflow: hidden;
    }
    .ship-card:hover { border-color: #2dd4bf; background: #f0fdfa; transform: translateY(-2px); }
    .ship-card.selected { border-color: #0d9488; background: #f0fdfa; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.1); }
    .ship-card input { display: none; }
    
    .ship-card-header { display: flex; justify-content: space-between; align-items: center; }
    .ship-name { font-size: 1rem; font-weight: 800; color: #1e293b; }
    .ship-price { font-size: 1rem; font-weight: 800; color: #1e293b; }
    
    .ship-card-body { display: flex; flex-direction: column; gap: 4px; }
    .ship-guarantee { display: flex; align-items: center; gap: 10px; font-size: 0.85rem; font-weight: 700; color: #0d9488; }
    .ship-subtext { font-size: 0.75rem; color: #64748b; font-weight: 500; line-height: 1.5; }
    
    .ship-badge { 
        position: absolute; top: -1px; left: -1px; width: 34px; height: 34px; 
        background: #0d9488; clip-path: polygon(0 0, 100% 0, 0 100%);
        display: none; align-items: flex-start; justify-content: flex-start; padding: 6px 0 0 6px;
        color: #fff; font-size: 12px; z-index: 10;
    }
    .ship-card.selected .ship-badge { display: flex; }

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
    .payment-option-v2 span { font-size: 0.7rem; font-weight: 800; color: var(--checkout-text-main); }
    .payment-option-v2 input { position: absolute; opacity: 0; }
    
    /* ── Payment Categories (Tabs) ── */
    .payment-category-container { 
        display: grid; 
        grid-template-columns: repeat(2, 1fr); 
        gap: 12px; 
        margin-bottom: 28px; 
        padding-top: 10px; /* Space for the badges */
        padding-right: 10px;
    }
    @media (min-width: 1024px) {
        .payment-category-container { 
            display: flex; 
            flex-wrap: nowrap; 
            overflow-x: auto; 
            gap: 10px; 
            padding-bottom: 8px;
            padding-top: 10px;
            padding-right: 10px;
        }
        .payment-category-container::-webkit-scrollbar { display: none; }
    }
    
    .payment-category-chip {
        background: #fff;
        border: 1.5px solid #f1f5f9; 
        border-radius: 14px; 
        padding: 12px 10px; 
        font-size: 0.75rem; 
        font-weight: 700; 
        color: #64748b; 
        cursor: pointer; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        position: relative;
        text-align: center;
        min-height: 54px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .payment-category-chip:hover:not(.disabled) { border-color: #cbd5e1; background: #f8fafc; transform: translateY(-1px); }
    .payment-category-chip.active {
        border-color: #3b82f6; 
        color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1);
        transform: translateY(-2px);
    }
    .payment-category-chip.active::after {
        content: '\f058'; 
        font-family: 'Font Awesome 5 Free'; 
        font-weight: 900;
        position: absolute; 
        top: -6px; 
        right: -6px; 
        font-size: 14px; 
        color: #3b82f6;
        background: #fff; 
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .payment-category-chip.disabled {
        opacity: 0.4; 
        cursor: not-allowed; 
        background: #f8fafc; 
        border-color: #e2e8f0; 
        color: #94a3b8;
    }
    .payment-category-chip span { line-height: 1.2; }
    
    .payment-sub-section { display: none; animation: fadeInSub 0.3s ease forwards; }
    .payment-sub-section.active { display: block; }
    @keyframes fadeInSub { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    
    /* ── Credit Card Form ── */
    #cc-form-container { display: none; margin-top: 24px; padding: 20px; background: #fafbfc; border-radius: 20px; border: 1px solid #eef2f6; }
    #cc-form-container.show { display: block; animation: fadeIn 0.4s ease; }
    
    .cc-input-group { 
        background: #fff; border-radius: 14px; border: 1.5px solid #e2e8f0; 
        overflow: hidden; transition: all 0.3s;
    }
    .cc-input-group:focus-within { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    
    .cc-field { position: relative; display: flex; align-items: center; padding: 14px 16px; background: #fff; }
    .cc-field i { color: #94a3b8; width: 20px; text-align: center; flex-shrink: 0; }
    .cc-field input { 
        width: 100%; padding-left: 12px; font-size: 1rem; font-weight: 700; 
        color: #1e293b; border: none; outline: none; background: transparent;
        min-width: 0;
    }
    .cc-field input::placeholder { color: #cbd5e1; font-weight: 500; }
    
    .cc-card-icons {
        position: absolute; right: 14px; top: 0; bottom: 0;
        display: flex; gap: 6px; align-items: center; background: #fff; padding-left: 6px;
    }
    .cc-card-icons img { height: 16px; transition: all 0.3s; }
    
    @media (max-width: 640px) {
        .cc-card-icons img { height: 14px; }
        .cc-card-icons img.grayscale { display: none; }
        .cc-field input#cc-number { padding-right: 54px !important; }
    }
    @media (min-width: 641px) {
        .cc-field input#cc-number { padding-right: 85px !important; }
    }
    
    .cc-divider-v { width: 1.5px; background: #e2e8f0; }
    .cc-divider-h { height: 1.5px; background: #e2e8f0; }

    /* ── Sticky Summary Sidebar ── */
    .sidebar-summary { 
        position: sticky; top: 100px; background: #fff; border-radius: 24px; 
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02); 
        border: 1px solid rgba(241, 245, 249, 0.8); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    .sidebar-summary::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px;
        background: linear-gradient(90deg, #ea580c, #f43f5e);
    }
    .price-summary-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; font-size: 0.9rem; color: var(--checkout-text-muted); font-weight: 500; gap: 12px; }
    .price-summary-row > span:first-child { flex: 1; }
    .price-summary-row > span:last-child { text-align: right; white-space: nowrap; color: var(--checkout-text-main); font-weight: 700; }
    
    .total-row { display: flex; flex-direction: column; align-items: flex-end; padding: 28px 0; margin-top: 20px; border-top: 1px dashed #e2e8f0; gap: 4px; }
    .total-price { color: #ea580c; font-size: 1.8rem; font-weight: 900; letter-spacing: -0.03em; line-height: 1; }

    .btn-checkout-primary { 
        width: 100%; padding: 16px 12px; background: linear-gradient(135deg, #ea580c 0%, #f43f5e 100%); color: #fff; 
        font-weight: 900; font-size: 1rem; border-radius: 16px; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 10px 20px -5px rgba(234, 88, 12, 0.4); text-transform: uppercase;
        display: flex; align-items: center; justify-content: center; gap: 10px; white-space: nowrap;
        border: none; cursor: pointer;
    }
    .btn-checkout-primary:hover { transform: translateY(-3px) scale(1.02); box-shadow: 0 20px 25px -5px rgba(234, 88, 12, 0.5); }
    .btn-checkout-primary:active { transform: translateY(-1px) scale(0.98); }
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
        .sidebar-summary { position: static; margin-top: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border-radius: 12px; }
    }

    @media (max-width: 768px) {
        .address-card { padding: 16px; border-radius: 12px; border: 1.5px solid #f1f5f9; }
        .address-info-grid { grid-template-cols: 1fr; gap: 12px; }
        .btn-change-address { width: 100%; padding: 12px; border-radius: 10px; font-weight: 800; display: flex; align-items: center; justify-content: center; gap: 6px; }
        
        .product-list-header { display: none; }
        .product-row { 
            display: flex; flex-direction: row; padding: 16px; gap: 16px; align-items: flex-start;
            border-bottom: 1px solid #f1f5f9; margin: 0; background: #fff; border-radius: 0; 
            box-shadow: none;
        }
        .product-info-container { display: contents; }
        .product-img { width: 84px; height: 84px; border-radius: 8px; flex-shrink: 0; object-fit: cover; }
        .product-detail { flex: 1; display: flex; flex-direction: column; min-width: 0; min-height: 84px; }
        .product-name { font-size: 0.85rem; font-weight: 500; color: #1e293b; line-height: 1.4; margin-bottom: 2px; -webkit-line-clamp: 2; }
        .product-variant { font-size: 0.75rem; color: #94a3b8; font-weight: 500; margin-bottom: 4px; }
        
        .mobile-price-row { display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto; }
        .mobile-price { font-size: 1rem; font-weight: 700; color: #ea580c; }
        .mobile-qty { font-size: 0.8rem; color: #64748b; font-weight: 500; }
        
        .desktop-only { display: none; }
        
        .shipping-box { padding: 24px; border-top: none; }
        .ship-card { min-width: 0; width: 100%; border-width: 1.5px; border-radius: 12px; padding: 16px; margin-bottom: 8px; }
        .ship-name, .ship-price { font-size: 0.95rem; }
        .ship-guarantee { font-size: 0.8rem; }
        .ship-subtext { font-size: 0.75rem; }

        .checkout-section { margin-bottom: 24px; border-radius: 16px; }
        .section-header { padding: 16px 20px; }
        .p-8 { padding: 16px !important; }
        .p-6 { padding: 12px !important; }
        .result-modal { padding: 24px; border-radius: 20px; width: 95%; }
        
        .address-modal-content { 
            border-radius: 20px 20px 0 0; position: fixed; bottom: 0; left: 0; right: 0; 
            max-height: 85vh; overflow-y: auto; width: 100%; max-width: none;
            animation: sheetSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes sheetSlideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
        #address-modal-overlay { padding: 0; align-items: flex-end; }
        
        .mobile-bottom-bar { 
            position: fixed; bottom: 0; left: 0; right: 0; 
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px);
            box-shadow: 0 -10px 25px rgba(0,0,0,0.05); z-index: 1000;
            display: flex; align-items: center; justify-content: space-between; padding: 14px 20px 28px;
            border-top: 1px solid rgba(241, 245, 249, 0.8);
        }
        .mobile-bottom-bar .btn-submit-mobile {
            background: linear-gradient(135deg, #ea580c, #f43f5e);
            color: #fff; font-weight: 900; padding: 12px 24px; border-radius: 12px;
            box-shadow: 0 8px 15px rgba(234, 88, 12, 0.3); font-size: 0.85rem;
            text-transform: uppercase; letter-spacing: 0.02em;
        }

        .sidebar-summary { padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; margin-top: 24px; margin-bottom: 80px; }
        .sidebar-summary .btn-checkout-primary { display: none; }
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
                                <div class="product-info-container">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/'.$item['image']) }}" class="product-img">
                                    @else
                                        <div class="product-img bg-slate-100 flex items-center justify-center text-slate-300"><i class="fas fa-fish"></i></div>
                                    @endif
                                    <div class="product-detail">
                                        <div class="product-name">{{ $item['name'] }}</div>
                                        @if(!empty($item['variation_name']))
                                            <div class="product-variant">{{ $item['variation_name'] }}</div>
                                        @endif
                                        
                                        <!-- Mobile-only price row -->
                                        <div class="mobile-price-row">
                                            <span class="mobile-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                            <span class="mobile-qty">x{{ $item['quantity'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="price-tag text-center desktop-only">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                                <div class="price-tag text-center desktop-only">{{ $item['quantity'] }}</div>
                                <div class="subtotal-tag text-right desktop-only">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Shipping Option -->
                        <div class="shipping-box bg-white border-t border-slate-100">
                            <div class="flex flex-col gap-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                        <i class="fas fa-shipping-fast text-xs"></i>
                                    </div>
                                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                        Opsi Pengiriman
                                    </span>
                                </div>
                                <div class="space-y-3">
                                    <label class="ship-card selected" onclick="updateShip(this, 0)">
                                        <input type="radio" name="shipping" value="0" checked>
                                        <div class="ship-badge"><i class="fas fa-check"></i></div>
                                        <div class="ship-card-header">
                                            <span class="ship-name">Ambil Sendiri</span>
                                            <span class="ship-price">Rp 0</span>
                                        </div>
                                        <div class="ship-card-body">
                                            <div class="ship-guarantee"><i class="fas fa-walking"></i> Pengambilan Mandiri</div>
                                            <div class="ship-subtext">Gratis Ongkir • Ambil di lokasi terdekat</div>
                                        </div>
                                    </label>
                                    
                                    <label class="ship-card" onclick="updateShip(this, 15000)">
                                        <input type="radio" name="shipping" value="15000">
                                        <div class="ship-badge"><i class="fas fa-check"></i></div>
                                        <div class="ship-card-header">
                                            <span class="ship-name">Kurir Lokal</span>
                                            <span class="ship-price">Rp 15.000</span>
                                        </div>
                                        <div class="ship-card-body">
                                            <div class="ship-guarantee"><i class="fas fa-truck"></i> Garansi tiba {{ date('d M', strtotime('+2 days')) }} - {{ date('d M', strtotime('+4 days')) }}</div>
                                            <div class="ship-subtext">Voucher s/d Rp10.000 jika pesanan belum tiba</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Pesanan Subtotal Summary Area -->
                        <div class="p-6 lg:p-10 bg-slate-50/40 border-t border-slate-100">
                        <!-- Pesanan Subtotal Summary Area -->
                        <div class="p-6 lg:p-10 bg-slate-50/40 border-t border-slate-100">
                            <!-- Desktop Layout: Full Width -->
                            <div class="hidden lg:flex flex-col gap-5">
                                <div class="flex justify-between items-center">
                                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Ongkos Kirim</span>
                                    <span class="text-xl font-bold text-slate-800" id="ship-display">Rp 0</span>
                                </div>
                                <div class="w-full h-px bg-slate-200/60 my-1"></div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Total Pesanan ({{ count($cart) }} Produk)</span>
                                    <span class="text-4xl font-black text-blue-600 whitespace-nowrap" id="total-summary-display">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Mobile Layout: Full Width (Already standard) -->
                            <div class="flex lg:hidden flex-col gap-4">
                                <div class="flex justify-between items-center gap-10">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ongkos Kirim</span>
                                    <span class="text-lg font-bold text-slate-800 text-right" id="ship-display-mobile">Rp 0</span>
                                </div>
                                <div class="w-full h-px bg-slate-200/60 my-1"></div>
                                <div class="flex justify-between items-center gap-10">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pesanan ({{ count($cart) }} Produk)</span>
                                    <span class="text-2xl font-black text-blue-600 text-right whitespace-nowrap" id="total-summary-display-mobile">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- 3. Payment Method Section -->
                    <div class="checkout-section">
                        <div class="section-header">
                            <h2 class="section-title"><i class="fas fa-wallet"></i> Metode Pembayaran</h2>
                        </div>
                        
                        <div class="p-8">
                            <!-- Category Selector -->
                            <div class="payment-category-container">
                                <div class="payment-category-chip active" onclick="selectPaymentCategory(this, 'va')">
                                    <span>Transfer Bank</span>
                                </div>
                                <div class="payment-category-chip" onclick="selectPaymentCategory(this, 'ewallet')">
                                    <span>E-Wallet</span>
                                </div>
                                <div class="payment-category-chip" onclick="selectPaymentCategory(this, 'qris')">
                                    <span>QRIS</span>
                                </div>
                                <div class="payment-category-chip" onclick="selectPaymentCategory(this, 'cc')">
                                    <span class="text-[0.65rem] sm:text-[0.75rem]">Kartu Kredit / Debit</span>
                                </div>
                                <div class="payment-category-chip" onclick="selectPaymentCategory(this, 'retail')">
                                    <span>Tunai / Retail</span>
                                </div>
                                <div class="payment-category-chip" onclick="selectPaymentCategory(this, 'direct')">
                                    <span>Debit Instan</span>
                                </div>
                            </div>

                            <!-- Virtual Accounts (Initially Active) -->
                            <div id="sub-payment-va" class="payment-sub-section active">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div> Pilihan Bank (Virtual Account)
                                </h4>
                                <div class="payment-grid">
                                    @foreach([
                                        'BCA'      => asset('images/bank/BCA.png'),
                                        'BRI'      => asset('images/bank/BRI.png'),
                                        'MANDIRI'  => asset('images/bank/mandiri.png'),
                                        'BNI'      => asset('images/bank/BNI.png'),
                                        'BSI'      => asset('images/bank/BSI.png'),
                                        'PERMATA'  => asset('images/bank/permata.png'),
                                        'CIMB'     => asset('images/bank/CIMB.png'),
                                        'MUAMALAT' => asset('images/bank/muamalat.png'),
                                    ] as $code => $url)
                                    <label class="payment-option-v2" onclick="selectPayment(this)">
                                        <input type="radio" name="payment_channel" value="{{$code}}">
                                        <img src="{{$url}}" alt="{{$code}}">
                                    </label>
                                    @endforeach
                                    <label class="payment-option-v2" onclick="selectPayment(this)">
                                        <input type="radio" name="payment_channel" value="PERMATA">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-black uppercase tracking-tight text-slate-400">Bank Lainnya</span>
                                            <i class="fas fa-university text-slate-300 text-lg"></i>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- E-Wallet -->
                            <div id="sub-payment-ewallet" class="payment-sub-section">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div> Dompet Digital (E-Wallet)
                                </h4>
                                <div class="payment-grid">
                                    @foreach([
                                        'ID_DANA'       => asset('images/bank/DANA.png'), 
                                        'ID_OVO'        => asset('images/bank/OVO.png'),
                                        'ID_SHOPEEPAY'  => asset('images/bank/shopeepay.png'),
                                        'ID_LINKAJA'    => asset('images/bank/LinkAja.png')
                                    ] as $code => $url)
                                    <label class="payment-option-v2" onclick="selectPayment(this)">
                                        <input type="radio" name="payment_channel" value="{{$code}}">
                                        <img src="{{$url}}">
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- QRIS -->
                            <div id="sub-payment-qris" class="payment-sub-section">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Scan QR Code
                                </h4>
                                <div class="max-w-xs">
                                    <label class="payment-option-v2" onclick="selectPayment(this)">
                                        <input type="radio" name="payment_channel" value="QRIS">
                                        <img src="{{ asset('images/bank/QRIS.png') }}" class="h-8">
                                    </label>
                                </div>
                            </div>

                            <!-- Kartu Kredit -->
                            <div id="sub-payment-cc" class="payment-sub-section">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-800"></div> Bayar dengan Kartu Kredit / Debit Online
                                </h4>
                                <label class="payment-option-v2 mb-6" onclick="selectPaymentTabCard(this)">
                                    <input type="radio" name="payment_channel" value="CREDIT_CARD">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('images/bank/visa_logo.png') }}" class="h-4">
                                        <img src="{{ asset('images/bank/mastercard.png') }}" class="h-4">
                                        <img src="{{ asset('images/bank/jcb.png') }}" class="h-4">
                                    </div>
                                </label>
                                
                                <div id="cc-form-container">
                                    <div class="mb-4 text-left">
                                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                                            <i class="far fa-credit-card text-blue-500"></i> Detail Kartu Kredit / Debit
                                        </h4>
                                    </div>
                                    
                                    <div class="cc-input-group">
                                        <!-- Card Number -->
                                        <div class="cc-field">
                                            <i class="far fa-credit-card text-lg"></i>
                                            <input type="text" id="cc-number" placeholder="0000 0000 0000 0000" maxlength="19">
                                            <div class="cc-card-icons">
                                                <img src="{{ asset('images/bank/visa_logo.png') }}" id="icon-visa" class="grayscale opacity-40">
                                                <img src="{{ asset('images/bank/mastercard.png') }}" id="icon-mastercard" class="grayscale opacity-40">
                                                <img src="{{ asset('images/bank/jcb.png') }}" id="icon-jcb" class="grayscale opacity-40">
                                            </div>
                                        </div>
                                        
                                        <div class="cc-divider-h"></div>
                                        
                                        <!-- Expiry & CVV -->
                                        <div class="flex flex-col sm:flex-row divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
                                            <div class="cc-field flex-1">
                                                <i class="far fa-calendar-alt text-lg"></i>
                                                <input type="text" id="cc-expiry" placeholder="MM/YY" maxlength="5">
                                            </div>
                                            <div class="cc-field flex-1">
                                                <i class="fas fa-lock text-lg"></i>
                                                <input type="password" id="cc-cvv" placeholder="CVV" maxlength="4">
                                                <button type="button" onclick="toggleCvv()" class="text-slate-400 hover:text-blue-500 transition-colors px-1 ml-auto">
                                                    <i class="far fa-eye" id="cvv-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-bold mt-4 flex items-center gap-2">
                                        <i class="fas fa-shield-halved text-emerald-500 text-xs"></i> 
                                        <span>Data terenkripsi aman oleh Xendit & tidak disimpan di server.</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Tunai / Retail -->
                            <div id="sub-payment-retail" class="payment-sub-section">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div> Gerai Retail / Agen
                                </h4>
                                <div class="payment-grid">
                                    @foreach([
                                        'ALFAMART'  => asset('images/bank/alfamart.png'), 
                                        'INDOMARET' => asset('images/bank/indomaret.png')
                                    ] as $code => $url)
                                    <label class="payment-option-v2" onclick="selectPayment(this)">
                                        <input type="radio" name="payment_channel" value="{{$code}}">
                                        <img src="{{$url}}">
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Debit Instan -->
                            <div id="sub-payment-direct" class="payment-sub-section">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div> Pilihan Debit Instan
                                </h4>
                                <div class="payment-grid">
                                    @foreach([
                                        'BRI' => asset('images/bank/bri_directdebit.png'), 
                                        'BCA' => asset('images/bank/BCA.png')
                                    ] as $code => $url)
                                    <label class="payment-option-v2" onclick="selectPayment(this)">
                                        <input type="radio" name="payment_channel" value="{{$code}}">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[9px] font-black uppercase tracking-tighter text-slate-400">Direct</span>
                                            <img src="{{$url}}" class="h-4">
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: SIDEBAR SUMMARY -->
                <div class="lg:col-span-1">
                    <div class="sidebar-summary p-6 backdrop-blur-sm bg-white/95 transition-all duration-300 hover:shadow-2xl">
                        <div class="flex items-center gap-2 mb-6">
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

                        <div class="total-row mb-6">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Bayar</span>
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
                            <div class="flex items-center gap-4 opacity-40 grayscale contrast-125">
                                <img src="{{ asset('images/bank/visa_logo.png') }}" class="h-3.5 object-contain" alt="Visa">
                                <img src="{{ asset('images/bank/mastercard.png') }}" class="h-3.5 object-contain" alt="Mastercard">
                                <img src="{{ asset('images/bank/jcb.png') }}" class="h-4 object-contain" alt="JCB">
                                <img src="{{ asset('images/bank/QRIS.png') }}" class="h-4 object-contain" alt="QRIS">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- Mobile Bottom Bar -->
<div class="mobile-bottom-bar flex items-center justify-between md:hidden">
    <div class="flex flex-col">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Bayar</span>
        <div class="text-xl font-black text-blue-600" id="mobile-total">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
    </div>
    <button type="button" onclick="document.getElementById('btn-submit').click()" class="btn-submit-mobile">
        BUAT PESANAN
    </button>
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
<div id="checkout-result-overlay" class="hidden fixed inset-0 z-[10000] bg-slate-900/60 backdrop-blur-sm items-center justify-center p-4">
    <div class="result-modal bg-white w-full max-w-[420px] md:max-w-[560px] rounded-[24px] p-6 md:p-8 shadow-2xl relative animate-[modalSlideUp_0.3s_ease-out] flex flex-col">
        <!-- VA Result -->
        <div id="res-va" class="hidden w-full mx-auto">
            <div class="mb-5 text-center relative z-20 flex items-center justify-center gap-3">
                <div id="va-icon-bg" class="w-12 h-12 rounded-full flex items-center justify-center shadow-lg text-white" style="background: linear-gradient(135deg, #1e293b, #0f172a)">
                    <i class="fas fa-university text-xl"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-xl font-black text-slate-800 tracking-tight">Virtual Account</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Selesaikan sesuai detail</p>
                </div>
            </div>
            
            <div id="va-card-bg" class="relative w-full rounded-2xl p-5 shadow-lg overflow-hidden group mb-5 transition-transform" style="background: linear-gradient(135deg, #1e293b, #0f172a)">
                <div class="absolute inset-0 bg-white/5 border border-white/10 rounded-2xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-[9px] font-bold text-white/70 uppercase tracking-widest mb-1">Bank Penerima</p>
                            <span id="res-bank-name" class="text-lg font-black text-white tracking-widest drop-shadow-md">BCA</span>
                        </div>
                        <i class="fas fa-wifi text-white/30 text-xl transform rotate-90"></i>
                    </div>
                    
                    <div>
                        <p class="text-[9px] font-bold text-white/70 uppercase tracking-widest mb-1.5">Nomor Virtual Account</p>
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <span id="res-va-code" class="text-xl sm:text-2xl md:text-3xl font-mono font-black text-white tracking-tight sm:tracking-widest drop-shadow-lg w-full break-all">XXXXXX</span>
                            <button onclick="copy('res-va-code')" class="self-start sm:self-auto shrink-0 w-10 h-10 bg-white/20 active:bg-white/30 backdrop-blur-md rounded-xl text-white transition-all duration-200 flex items-center justify-center" title="Salin">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Retail Result -->
        <div id="res-retail" class="hidden w-full mx-auto">
            <div class="mb-5 text-center relative z-20 flex items-center justify-center gap-3">
                <div id="retail-icon-bg" class="w-12 h-12 rounded-full flex items-center justify-center shadow-lg text-white" style="background: linear-gradient(135deg, #1e293b, #0f172a)">
                    <i class="fas fa-store text-xl"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-xl font-black text-slate-800 tracking-tight">Gerai Retail</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Bawa kode ke kasir</p>
                </div>
            </div>
            
            <div id="retail-card-bg" class="relative w-full rounded-2xl p-5 shadow-lg overflow-hidden group mb-5" style="background: linear-gradient(135deg, #1e293b, #0f172a)">
                <div class="absolute inset-0 bg-white/5 border border-white/10 rounded-2xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-[9px] font-bold text-white/70 uppercase tracking-widest mb-1">Merchant</p>
                            <span id="res-retail-name" class="text-lg font-black text-white tracking-widest drop-shadow-md">ALFAMART</span>
                        </div>
                        <i class="fas fa-barcode text-white/30 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-white/70 uppercase tracking-widest mb-1.5">Kode Pembayaran</p>
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <span id="res-retail-code" class="text-xl sm:text-2xl md:text-3xl font-mono font-black text-white tracking-tight sm:tracking-widest drop-shadow-lg w-full break-all">XXXXXX</span>
                            <button onclick="copy('res-retail-code')" class="self-start sm:self-auto shrink-0 w-10 h-10 bg-white/20 active:bg-white/30 backdrop-blur-md rounded-xl text-white flex items-center justify-center" title="Salin">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QRIS Result -->
        <div id="res-qris" class="hidden w-full mx-auto text-center shrink-0">
            <div class="flex items-center justify-center gap-3 mb-5">
                <div class="w-12 h-12 bg-pink-50 text-pink-600 rounded-full flex items-center justify-center shadow-inner"><i class="fas fa-qrcode text-2xl"></i></div>
                <div class="text-left">
                    <h3 class="text-xl font-black text-slate-800 tracking-tight">QRIS</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Buka e-Wallet / m-Banking</p>
                </div>
            </div>
            <div class="inline-block bg-white rounded-3xl p-4 shadow-xl border border-slate-100 mb-4">
                <div id="qris-box" class="w-[180px] h-[180px] flex items-center justify-center bg-white mx-auto"></div>
            </div>
        </div>

        <!-- E-Wallet / Redirect Result -->
        <div id="res-redirect" class="hidden w-full mx-auto text-center">
            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center shadow-inner"><i class="fas fa-mobile-alt text-2xl"></i></div>
                <div class="text-left">
                    <h3 class="text-xl font-black text-slate-800 tracking-tight">Lanjut Bayar</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Selesaikan di halaman partner</p>
                </div>
            </div>
            
            <a id="redirect-btn" href="#" target="_blank" class="block w-full bg-emerald-600 text-white rounded-2xl py-4 flex flex-col items-center justify-center hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 cursor-pointer">
                <span class="font-black uppercase tracking-widest text-sm mb-0.5">Buka Halaman Pembayaran</span>
            </a>
        </div>

        <!-- Shared Footer -->
        <div class="w-full mx-auto flex flex-col gap-3 shrink-0">
            <div class="bg-blue-50 border border-blue-100/50 rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="text-left">
                    <p class="text-[9px] font-black text-blue-500 uppercase tracking-widest mb-0.5">Total Pembayaran</p>
                    <p id="res-amount" class="text-xl font-black text-blue-700 tracking-tight">Rp 0</p>
                </div>
                <a href="{{ route('orders.index') }}" class="w-10 h-10 bg-white rounded-[10px] shadow-sm text-blue-500 flex items-center justify-center hover:bg-blue-600 hover:text-white transition" title="Lihat Pesanan">
                  <i class="fas fa-box-open"></i>
                </a>
            </div>
            
            <div class="bg-amber-50 rounded-xl p-3 border border-amber-100 flex gap-3 text-left items-start">
               <div class="text-amber-500 mt-0.5"><i class="fas fa-info-circle"></i></div>
               <p class="text-[10px] text-amber-800 font-semibold leading-relaxed">Status otomatis diperbarui setelah sukses pembayaran. <span class="block">Jangan tutup halaman sebelum selesai.</span></p>
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

    // Set Xendit Public Key
    const XENDIT_PUBLIC_KEY = "{{ config('xendit.public_key') }}";
    if (XENDIT_PUBLIC_KEY) Xendit.setPublishableKey(XENDIT_PUBLIC_KEY);

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
                } else {
                    // Fallback for plain string address
                    document.getElementById('display-address-text').textContent = rawAddr;
                    document.getElementById('cust_address').value = rawAddr;
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
    const cartItemsData = [@foreach($cart as $id => $item){product_id:'{{$item['product_id']}}', variation_id:'{{$item['variation_id'] ?? ''}}', quantity:{{$item['quantity']}}},@endforeach];

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

    window.selectPaymentCategory = function(el, targetId) {
        document.querySelectorAll('.payment-category-chip').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        
        document.querySelectorAll('.payment-sub-section').forEach(s => s.classList.remove('active'));
        const sub = document.getElementById('sub-payment-' + targetId);
        if (sub) sub.classList.add('active');
        
        // Hide CC form when switching away from CC category
        if (targetId !== 'cc') {
            const ccForm = document.getElementById('cc-form-container');
            if (ccForm) ccForm.classList.remove('show');
        }
    };

    window.selectPaymentTabCard = function(el) {
        document.querySelectorAll('.payment-option-v2').forEach(i => i.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('cc-form-container').classList.add('show');
        const radio = el.querySelector('input');
        if (radio) radio.checked = true;
    };

    window.selectPayment = function(el) {
        document.querySelectorAll('.payment-option-v2').forEach(i => i.classList.remove('selected'));
        el.classList.add('selected');
        const radio = el.querySelector('input');
        if (radio) radio.checked = true;
    };

    window.selectPaymentCard = function(el) {
        // Legacy, keeping for compatibility during migration if needed
        window.selectPaymentTabCard(el);
    };

    window.updateShip = function(el, val) {
        document.querySelectorAll('.ship-card').forEach(i => i.classList.remove('selected'));
        el.classList.add('selected');
        const radio = el.querySelector('input');
        if (radio) {
          radio.checked = true;
          shipping = val;
          const total = subtotal + shipping;
          
          const shipText = shipping === 0 ? 'Rp 0' : 'Rp ' + f(shipping);
          const totalText = 'Rp ' + f(total);

          if(document.getElementById('ship-display')) document.getElementById('ship-display').textContent = shipText;
          if(document.getElementById('ship-display-mobile')) document.getElementById('ship-display-mobile').textContent = shipText;
          if(document.getElementById('sidebar-ship')) document.getElementById('sidebar-ship').textContent = shipText;
          
          if(document.getElementById('total-display')) document.getElementById('total-display').textContent = totalText;
          if(document.getElementById('mobile-total')) document.getElementById('mobile-total').textContent = totalText;
          if(document.getElementById('total-summary-display')) document.getElementById('total-summary-display').textContent = totalText;
          if(document.getElementById('total-summary-display-mobile')) document.getElementById('total-summary-display-mobile').textContent = totalText;
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

        if (btn) btn.disabled = true;
        if (loader) {
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        }

        // Handle Credit Card Tokenization if needed
        if (channel === 'CREDIT_CARD') {
            const ccNum = document.getElementById('cc-number').value.replace(/\s/g, '');
            const ccExp = document.getElementById('cc-expiry').value.split('/');
            const ccCvv = document.getElementById('cc-cvv').value;

            if (!ccNum || ccExp.length !== 2 || !ccCvv) {
                if (btn) btn.disabled = false;
                if (loader) {
                    loader.classList.add('hidden');
                    loader.classList.remove('flex');
                }
                return showAlert({type:'warning', title:'Data Kartu', message:'Lengkapi informasi kartu kredit Anda.'});
            }

            const cardData = {
                card_number: ccNum,
                card_exp_month: ccExp[0].trim(),
                card_exp_year: '20' + ccExp[1].trim(),
                card_cvn: ccCvv,
                is_multiple_use: false,
                should_authenticate: true // 3DS
            };

            Xendit.card.createToken(cardData, (err, token) => {
                if (err) {
                    if (btn) btn.disabled = false;
                    if (loader) {
                        loader.classList.add('hidden');
                        loader.classList.remove('flex');
                    }
                    return showAlert({type:'error', title:'Error Kartu', message: err.message || 'Gagal melakukan otentikasi kartu.'});
                }
                
                if (token.status === 'IN_REVIEW') {
                    window.open(token.payer_authentication_url, '_blank');
                    // In a real app, you'd poll or wait for a message from the 3DS window
                    // For now, let's assume we proceed with the token
                    submitOrder(name, phone, address, city, channel, token.id, token.authentication_id);
                } else {
                    submitOrder(name, phone, address, city, channel, token.id, token.authentication_id);
                }
            });
        } else {
            submitOrder(name, phone, address, city, channel);
        }
    });

    async function submitOrder(name, phone, address, city, channel, tokenId = null, authId = null) {
        const loader = document.getElementById('loading');
        const btn = document.getElementById('btn-submit');
        
        try {
            const res = await fetch('{{ route("checkout.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    items: [@foreach($cart as $id => $item){
                        product_id: '{{ $item['product_id'] }}',
                        variation_id: '{{ $item['variation_id'] ?? '' }}',
                        variation_name: '{{ $item['variation_name'] ?? '' }}',
                        quantity: {{ $item['quantity'] }}
                    },@endforeach],
                    total: subtotal + shipping,
                    address: `Penerima: ${name} (${phone})\n${address}\n${city}`,
                    shipping_cost: shipping,
                    payment_channel: channel,
                    payer_name: name,
                    payer_phone: phone,
                    location_id: document.getElementById('selected_location_id').value,
                    token_id: tokenId,
                    auth_id: authId
                })
            });

            if (!res.ok) {
                const text = await res.text();
                console.error('Server error response:', text);
                if (loader) {
                    loader.classList.add('hidden');
                    loader.classList.remove('flex');
                }
                if (btn) btn.disabled = false;
                
                let errorMsg = 'Server memberikan respon yang tidak valid.';
                try {
                    const errorData = JSON.parse(text);
                    if (errorData.message) errorMsg = errorData.message;
                } catch(e) {}

                return showAlert({
                    type: 'error',
                    title: 'Kesalahan Server (' + res.status + ')',
                    message: errorMsg + ' (Status: ' + res.status + ')'
                });
            }
            
            const data = await res.json();
            if (loader) {
                loader.classList.add('hidden');
                loader.classList.remove('flex');
            }

            if (data.order_number) {
                 showResult(data);
                 window.scrollTo({top:0, behavior:'smooth'});
            } else {
                if (btn) btn.disabled = false;
                showAlert({type:'error', title:'Gagal', message: data.message || 'Error pembayaran.'});
            }
        } catch(e) {
            console.error('Network or Parse error:', e);
            if (loader) {
                loader.classList.add('hidden');
                loader.classList.remove('flex');
            }
            if (btn) btn.disabled = false;
            
            let detailedMsg = e.message;
            if (e.message.includes('Failed to fetch')) {
                detailedMsg = 'Gagal menghubungi server. Periksa koneksi internet atau blokir CORS.';
            }

            showAlert({
                type:'error', 
                title:'Error Koneksi', 
                message: 'Masalah teknis: ' + detailedMsg
            });
        }
    }

    function showResult(d) {
        const resAmount = document.getElementById('res-amount');
        if (resAmount) resAmount.textContent = 'Rp ' + f(d.amount);
        
        const overlay = document.getElementById('checkout-result-overlay');
        if (overlay) overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        ['res-va', 'res-retail', 'res-qris', 'res-ewallet', 'res-redirect'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        });

        const bankColorMap = {
            'BCA': 'linear-gradient(135deg, #005aa9, #002e5c)',
            'BSI': 'linear-gradient(135deg, #00A39D, #005B58)',
            'BRI': 'linear-gradient(135deg, #00529C, #00294F)',
            'MANDIRI': 'linear-gradient(135deg, #003E7E, #F2A900)',
            'BNI': 'linear-gradient(135deg, #F15A23, #005E6A)',
            'PERMATA': 'linear-gradient(135deg, #007A60, #004A3A)',
            'CIMB': 'linear-gradient(135deg, #A80034, #660020)',
            'MUAMALAT': 'linear-gradient(135deg, #5C2D91, #3B1B61)',
            'ALFAMART': 'linear-gradient(135deg, #E3000F, #A0000A)',
            'INDOMARET': 'linear-gradient(135deg, #003087, #ED1C24)',
        };

        if (d.type === 'va') {
            const bankName = document.getElementById('res-bank-name');
            const vaCode = document.getElementById('res-va-code');
            const vaBox = document.getElementById('res-va');
            if (bankName) bankName.textContent = d.bank;
            if (vaCode) vaCode.textContent = d.code;
            
            const rawBank = (d.bank || '').replace('ID_', '');
            const defaultGrad = 'linear-gradient(135deg, #1e293b, #0f172a)';
            const gradient = bankColorMap[rawBank.toUpperCase()] || defaultGrad;
            
            const iconBg = document.getElementById('va-icon-bg');
            const cardBg = document.getElementById('va-card-bg');
            if (iconBg) iconBg.style.background = gradient;
            if (cardBg) cardBg.style.background = gradient;
            
            if (vaBox) vaBox.classList.remove('hidden');
        } else if (d.type === 'retail') {
            const retailName = document.getElementById('res-retail-name');
            const retailCode = document.getElementById('res-retail-code');
            const retailBox = document.getElementById('res-retail');
            if (retailName) retailName.textContent = d.channel;
            if (retailCode) retailCode.textContent = d.code;
            
            const rawBank = (d.channel || '').replace('ID_', '');
            const defaultGrad = 'linear-gradient(135deg, #1e293b, #0f172a)';
            const gradient = bankColorMap[rawBank.toUpperCase()] || defaultGrad;
            
            const iconBg = document.getElementById('retail-icon-bg');
            const cardBg = document.getElementById('retail-card-bg');
            if (iconBg) iconBg.style.background = gradient;
            if (cardBg) cardBg.style.background = gradient;
            
            if (retailBox) retailBox.classList.remove('hidden');
        } else if (d.type === 'qris') {
            const box = document.getElementById('qris-box');
            const qrisBox = document.getElementById('res-qris');
            if (box) {
                box.innerHTML = '';
                new QRCode(box, {text: d.qr_string, width: 180, height: 180, colorDark: "#0f172a"});
            }
            if (qrisBox) qrisBox.classList.remove('hidden');
        } else if (d.type === 'ewallet' || d.type === 'direct_debit' || d.type === 'paylater') {
             if (d.payment_url) {
                const redirectBtn = document.getElementById('redirect-btn');
                const redirectBox = document.getElementById('res-redirect');
                if (redirectBtn) redirectBtn.href = d.payment_url;
                if (redirectBox) redirectBox.classList.remove('hidden');
             } else {
                showAlert({type:'success', title:'Terkirim', message:'Buka aplikasi pembayaran Anda.'});
             }
        } else if (d.type === 'credit_card') {
            if (d.status === 'success') {
                showAlert({type:'success', title:'Berhasil', message:'Pembayaran kartu kredit Anda berhasil!'});
                setTimeout(() => window.location.href = '{{ route("orders.index") }}', 2000);
                return; // No need to poll for credit card success
            } else {
                showAlert({type:'info', title:'Pending', message:'Transaksi sedang diproses oleh bank.'});
            }
        }

        // Start payment status polling
        if (d.order_number) {
            startPaymentPolling(d.order_number);
        }
    }

    // ── Payment Status Polling ──────────────────────────────────
    let pollingInterval = null;
    let pollingAttempts = 0;
    const MAX_POLLING_ATTEMPTS = 360; // 30 minutes (5s interval)

    function startPaymentPolling(orderNumber) {
        // Clear any existing polling
        if (pollingInterval) clearInterval(pollingInterval);
        pollingAttempts = 0;

        pollingInterval = setInterval(async () => {
            pollingAttempts++;

            // Stop polling after max attempts
            if (pollingAttempts >= MAX_POLLING_ATTEMPTS) {
                clearInterval(pollingInterval);
                pollingInterval = null;
                return;
            }

            try {
                const res = await fetch(`/api/orders/${orderNumber}/payment-status`);
                if (!res.ok) return;

                const data = await res.json();

                if (data.payment_status === 'paid') {
                    clearInterval(pollingInterval);
                    pollingInterval = null;

                    // Show success notification
                    showAlert({
                        type: 'success',
                        title: 'Pembayaran Berhasil!',
                        message: 'Pembayaran Anda telah diterima. Mengalihkan ke halaman pesanan...',
                        primaryText: 'Lihat Pesanan',
                        onConfirm: () => {
                            window.location.href = `/orders/${orderNumber}`;
                        }
                    });

                    // Auto redirect after 4 seconds
                    setTimeout(() => {
                        window.location.href = `/orders/${orderNumber}`;
                    }, 4000);

                } else if (data.payment_status === 'failed' || data.payment_status === 'expired') {
                    clearInterval(pollingInterval);
                    pollingInterval = null;

                    showAlert({
                        type: 'error',
                        title: 'Pembayaran Gagal',
                        message: 'Pembayaran telah kedaluwarsa atau gagal. Silakan buat pesanan baru.',
                        primaryText: 'OK'
                    });
                }
            } catch (e) {
                // Silently ignore network errors, will retry next interval
                console.warn('Polling error:', e);
            }
        }, 5000); // Check every 5 seconds
    }

    function f(n) { return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
    
    window.copy = function(id) {
        navigator.clipboard.writeText(document.getElementById(id).innerText).then(() => {
            const b = event.currentTarget; const h = b.innerHTML;
            b.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => b.innerHTML = h, 2000);
        });
    };

    window.toggleCvv = function() {
        const cvvInput = document.getElementById('cc-cvv');
        const cvvEye = document.getElementById('cvv-eye');
        if (cvvInput.type === 'password') {
            cvvInput.type = 'text';
            cvvEye.classList.remove('fa-eye');
            cvvEye.classList.add('fa-eye-slash', 'text-blue-500');
        } else {
            cvvInput.type = 'password';
            cvvEye.classList.remove('fa-eye-slash', 'text-blue-500');
            cvvEye.classList.add('fa-eye');
        }
    };

    // Credit Card Input Formatters
    const ccNumEl = document.getElementById('cc-number');
    if (ccNumEl) {
        ccNumEl.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            let formattedValue = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) formattedValue += ' ';
                formattedValue += value[i];
            }
            e.target.value = formattedValue;
            
            const isVisa = value.startsWith('4');
            const isMastercard = /^(5[1-5]|2[2-7])/.test(value);
            const isJcb = /^(35)/.test(value);
            
            const iconVisa = document.getElementById('icon-visa');
            const iconMc = document.getElementById('icon-mastercard');
            const iconJcb = document.getElementById('icon-jcb');
            
            if (iconVisa && iconMc && iconJcb) {
                if (value.length > 0) {
                    iconVisa.classList.toggle('grayscale', !isVisa);
                    iconVisa.classList.toggle('opacity-40', !isVisa);
                    
                    iconMc.classList.toggle('grayscale', !isMastercard);
                    iconMc.classList.toggle('opacity-40', !isMastercard);
                    
                    iconJcb.classList.toggle('grayscale', !isJcb);
                    iconJcb.classList.toggle('opacity-40', !isJcb);
                } else {
                    iconVisa.classList.add('grayscale', 'opacity-40');
                    iconMc.classList.add('grayscale', 'opacity-40');
                    iconJcb.classList.add('grayscale', 'opacity-40');
                }
            }
        });
    }

    const ccExpEl = document.getElementById('cc-expiry');
    if (ccExpEl) {
        ccExpEl.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }

    const ccCvvEl = document.getElementById('cc-cvv');
    if (ccCvvEl) {
        ccCvvEl.addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
        });
    }
})();
</script>
@endpush

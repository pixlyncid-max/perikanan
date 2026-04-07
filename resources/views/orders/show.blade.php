@extends('layouts.app')

@section('title', 'Detail Pesanan | ' . get_setting('site_name', 'FISHERIES'))

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 transition mb-6 font-medium">
            <i class="fas fa-arrow-left"></i> Kembali ke Pesanan Saya
        </a>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Detail Pesanan</h1>
            <div class="flex items-center gap-3">
                 <span class="px-4 py-2 rounded-full font-bold text-xs uppercase tracking-wider
                    @if($order->payment_status === 'paid') bg-green-100 text-green-700 
                    @elseif($order->payment_status === 'expired' || $order->payment_status === 'failed') bg-red-100 text-red-700 
                    @else bg-amber-100 text-amber-700 @endif">
                    {{ $order->payment_status }}
                </span>
                <span class="px-4 py-2 rounded-full font-bold text-xs uppercase tracking-wider bg-gray-100 text-gray-600">
                    {{ $order->status }}
                </span>
            </div>
        </div>

        <!-- Payment Instructions for Pending -->
        <!-- Payment Instructions for Pending -->
        @if($order->payment_status === 'pending' && $order->status !== 'cancelled')
            <div class="mb-10">
                <div class="bg-white rounded-3xl p-6 md:p-8 mb-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex items-center gap-5">
                    <div class="w-14 h-14 bg-gradient-to-tr from-blue-600 to-blue-400 text-white flex items-center justify-center rounded-2xl shadow-lg shadow-blue-200">
                        <i class="fas fa-wallet text-2xl -rotate-12"></i>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Instruksi Pembayaran</h2>
                        <p class="text-slate-500 text-[10px] md:text-xs font-bold uppercase tracking-[0.15em] mt-1">Langkah Terakhir Menyelesaikan Pesanan</p>
                    </div>
                </div>

                @if($order->payment_channel === 'QRIS')
                    <div class="bg-white rounded-[2.5rem] p-8 text-slate-900 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] border border-slate-100 relative overflow-hidden group mb-4">
                        <div class="absolute inset-0 bg-gradient-to-tr from-pink-500/5 to-blue-500/5 pointer-events-none group-hover:opacity-100 opacity-50 transition-opacity"></div>
                        <div class="relative z-10 flex flex-col md:flex-row items-center justify-center gap-10">
                            <div class="bg-white p-3 border-4 border-dashed border-slate-100 rounded-[2.5rem] shadow-sm transform group-hover:scale-[1.02] transition-transform duration-300">
                                <div id="qris-canvas" class="bg-white rounded-[1.5rem] overflow-hidden"></div>
                            </div>
                            <div class="text-center md:text-left max-w-sm">
                                <div class="mb-5">
                                    <img src="{{ asset('images/bank/QRIS.png') }}" class="h-10 mx-auto md:mx-0" alt="QRIS">
                                </div>
                                <h3 class="text-2xl font-black mb-2 text-slate-800 tracking-widest uppercase">SCAN TO PAY</h3>
                                <p class="text-sm text-slate-500 mb-6 font-medium leading-relaxed">Buka aplikasi e-wallet atau m-banking Anda, lalu scan kode QR di samping.</p>
                                
                                <div class="flex items-center gap-4 px-6 py-5 bg-slate-50 rounded-2xl mb-5 border border-slate-100">
                                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-[14px] flex items-center justify-center shrink-0"><i class="fas fa-wallet text-xl"></i></div>
                                    <div>
                                        <p class="text-[10px] font-black tracking-widest text-slate-400 uppercase mb-0.5">Total Bayar</p>
                                        <p class="text-2xl font-black text-blue-600 tracking-tight">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <p class="text-[10px] text-red-500 font-bold uppercase tracking-widest inline-flex items-center gap-1.5 px-4 py-2 opacity-80">
                                    <i class="fas fa-clock animate-pulse"></i> Batas Scan: {{ $order->payment_expires_at?->format('d M Y, H:i') ?? 'Segera' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif(in_array($order->payment_channel, ['ALFAMART', 'INDOMARET']))
                    <div class="relative w-full rounded-[2.5rem] p-8 md:p-10 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.4)] overflow-hidden group mb-4 transition-transform hover:scale-[1.01] duration-300">
                        <!-- Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-black"></div>
                        <div class="absolute -right-16 -top-16 w-64 h-64 bg-orange-500/20 rounded-full blur-3xl group-hover:bg-orange-500/30 transition-colors duration-700"></div>
                        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-red-500/20 rounded-full blur-3xl group-hover:bg-red-500/30 transition-colors duration-700"></div>
                        <div class="absolute inset-0 bg-gradient-to-b from-white/5 to-transparent border border-white/10 rounded-[2.5rem] pointer-events-none"></div>
                        
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-10">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Merchant Retail</p>
                                    <div class="flex items-center gap-3">
                                        <div class="bg-white/10 w-10 h-10 rounded-xl flex items-center justify-center backdrop-blur-sm"><i class="fas fa-store text-white"></i></div>
                                        <p class="text-2xl md:text-3xl font-black text-white tracking-widest drop-shadow-md">{{ $order->payment_channel }}</p>
                                    </div>
                                </div>
                                <i class="fas fa-barcode text-white/20 text-5xl"></i>
                            </div>

                            <div class="mb-8">
                                <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] mb-3 font-bold">Kode Pembayaran</p>
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <span id="retail-code" class="text-[32px] md:text-[40px] font-mono font-black text-white tracking-[0.1em] drop-shadow-xl truncate">{{ $order->payment_code }}</span>
                                    <button onclick="copy('retail-code')" class="shrink-0 w-14 h-14 bg-white/10 hover:bg-white/20 active:bg-white/30 backdrop-blur-md border border-white/10 rounded-2xl text-white transition-all duration-200 flex items-center justify-center group" title="Salin kode">
                                        <i class="far fa-copy text-2xl group-hover:scale-110 transition-transform"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="border-t border-white/10 pt-6 flex flex-col md:flex-row justify-between md:items-center text-white gap-4">
                                <div class="flex-1"><p class="text-xs md:text-sm text-slate-300 font-medium leading-relaxed max-w-sm"><i class="fas fa-info-circle mr-1 text-slate-400"></i> Tunjukkan kode ini ke kasir {{ $order->payment_channel }} dan bayar sesuai nominal.</p></div>
                                <div class="text-left md:text-right flex-shrink-0 bg-white/5 p-4 rounded-2xl border border-white/10 backdrop-blur-sm">
                                    <p class="text-[10px] font-bold text-slate-400 mb-1 tracking-widest uppercase">NOMINAL BAYAR</p>
                                    <p class="text-2xl font-black text-orange-400 tracking-tight">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($order->payment_code) {{-- Virtual Account --}}
                    <div class="relative w-full rounded-[2.5rem] p-8 md:p-10 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.4)] overflow-hidden group mb-4 transition-transform hover:scale-[1.01] duration-300">
                        <!-- Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-black"></div>
                        <div class="absolute -right-16 -top-16 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl group-hover:bg-blue-500/30 transition-colors duration-700"></div>
                        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl group-hover:bg-indigo-500/30 transition-colors duration-700"></div>
                        <div class="absolute inset-0 bg-gradient-to-b from-white/5 to-transparent border border-white/10 rounded-[2.5rem] pointer-events-none"></div>

                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-10">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Bank Penerima</p>
                                    <div class="flex items-center gap-3">
                                        <div class="bg-white/10 w-10 h-10 rounded-xl flex items-center justify-center backdrop-blur-sm"><i class="fas fa-university text-white"></i></div>
                                        <p class="text-2xl md:text-3xl font-black text-white tracking-widest drop-shadow-md">{{ str_replace('ID_', '', $order->payment_channel) }}</p>
                                    </div>
                                </div>
                                <i class="fas fa-wifi text-white/20 text-4xl transform rotate-90"></i>
                            </div>

                            <div class="mb-8">
                                <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] mb-3 font-bold">Nomor Virtual Account</p>
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <span id="va-number" class="text-[32px] md:text-[40px] font-mono font-black text-white tracking-[0.1em] drop-shadow-xl truncate">{{ $order->payment_code }}</span>
                                    <button onclick="copy('va-number')" class="shrink-0 w-14 h-14 bg-white/10 hover:bg-white/20 active:bg-white/30 backdrop-blur-md border border-white/10 rounded-2xl text-white transition-all duration-200 flex items-center justify-center group" title="Salin nomor">
                                        <i class="far fa-copy text-2xl group-hover:scale-110 transition-transform"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="border-t border-white/10 pt-6 flex flex-col md:flex-row justify-between md:items-center text-white gap-4">
                                <div class="text-left bg-white/5 p-4 rounded-2xl border border-white/10 backdrop-blur-sm inline-block">
                                    <p class="text-[10px] font-bold text-slate-400 mb-1 tracking-widest uppercase">TOTAL BAYAR</p>
                                    <p class="text-2xl font-black text-blue-300 tracking-tight">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-left md:text-right mt-2 md:mt-0">
                                    <p class="text-[10px] font-bold text-slate-400 mb-1.5 tracking-widest uppercase">BERLAKU SAMPAI</p>
                                    <p class="text-sm font-bold text-red-300 bg-red-500/10 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-red-500/20">
                                        <i class="fas fa-clock text-red-400 animate-pulse"></i> {{ $order->payment_expires_at?->format('d M Y, H:i') ?? '24 Jam' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($order->payment_url) {{-- E-Wallet Link --}}
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-[2.5rem] p-1 shadow-[0_20px_50px_-12px_rgba(16,185,129,0.4)] relative overflow-hidden group mb-4">
                        <div class="absolute -right-20 -top-20 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                        <div class="bg-slate-900/10 backdrop-blur-md rounded-[2.3rem] p-8 md:p-12 text-center relative z-10 border border-white/20">
                            <div class="w-20 h-20 bg-white/20 rounded-[1.2rem] flex items-center justify-center mx-auto mb-6 text-white shadow-inner backdrop-blur-lg">
                                <i class="fas fa-mobile-alt text-4xl"></i>
                            </div>
                            <h3 class="text-3xl font-black text-white mb-2 tracking-tight">Lanjutkan Pembayaran</h3>
                            <p class="text-emerald-50 font-medium mb-10 max-w-sm mx-auto leading-relaxed">Anda akan diarahkan ke aplikasi e-Wallet atau halaman partner pembayaran.</p>
                            
                            <a href="{{ $order->payment_url }}" target="_blank" class="inline-flex items-center justify-center w-full md:w-auto gap-3 bg-white text-emerald-600 px-10 py-5 rounded-2xl font-black shadow-xl hover:-translate-y-1 active:translate-y-0 transition-all duration-300 transform group/btn">
                                BUKA HALAMAN PEMBAYARAN <i class="fas fa-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                            <p class="mt-8 text-[11px] text-emerald-100/70 font-medium italic"><i class="fas fa-shield-alt mr-1"></i> Tautan aman terenkripsi</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h2 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6 flex items-center gap-2"><i class="fas fa-info-circle text-blue-500"></i> Info Order</h2>
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-gray-50 pb-2"><span class="text-gray-500 text-xs font-bold">No. Pesanan</span><span class="font-mono text-[11px] font-black text-gray-700 bg-gray-100 px-2 py-1 rounded">{{ $order->order_number }}</span></div>
                    <div class="flex justify-between border-b border-gray-50 pb-2"><span class="text-gray-500 text-xs font-bold">Waktu Transaksi</span><span class="text-xs font-bold">{{ $order->created_at->format('d M Y, H:i') }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500 text-xs font-bold">Metode Pembayaran</span><span class="text-xs font-black text-gray-800 uppercase">{{ str_replace('ID_', '', $order->payment_channel ?? $order->payment_method) }}</span></div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h2 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6 flex items-center gap-2"><i class="fas fa-map-marker-alt text-red-500"></i> Alamat Pengiriman</h2>
                <p class="text-xs text-gray-600 leading-relaxed font-bold whitespace-pre-line">{{ $order->shipping_address }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-12">
            <div class="p-6 bg-gray-50/50 border-b border-gray-100"><h2 class="text-[10px] font-black uppercase tracking-widest text-gray-400 flex items-center gap-2"><i class="fas fa-shopping-bag text-green-500"></i> Produk Pesanan</h2></div>

            {{-- Mobile Card View --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($order->items as $item)
                    <div class="p-4 flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-gray-800 truncate">{{ $item->product_name }}</p>
                            <p class="text-[11px] text-gray-400 font-bold mt-0.5">{{ $item->quantity }} Item &times; Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                        </div>
                        <p class="text-sm font-black text-blue-600 shrink-0">Rp {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                @endforeach
                <div class="px-4 py-3 bg-gray-50/50 flex justify-between text-xs font-bold text-gray-400 uppercase">
                    <span>Biaya Kirim</span>
                    <span class="text-gray-700">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="px-4 py-4 bg-blue-50/30 flex justify-between items-center">
                    <span class="text-sm font-black text-gray-900">Total Pembayaran</span>
                    <span class="text-xl font-black text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                            <tr class="group hover:bg-gray-50/50 transition">
                                <td class="px-8 py-5"><p class="text-sm font-black text-gray-800 mb-0.5">{{ $item->product_name }}</p><p class="text-[10px] text-gray-400 font-bold uppercase">{{ $item->quantity }} Item</p></td>
                                <td class="px-8 py-5 text-right"><p class="text-sm font-black text-gray-900">Rp {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</p></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50/30 border-t border-gray-100">
                        <tr><td class="px-8 py-4 text-xs font-bold text-gray-400 uppercase">Biaya Kirim</td><td class="px-8 py-4 text-right font-bold text-gray-700 text-sm">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td></tr>
                        <tr class="bg-blue-50/30"><td class="px-8 py-6 text-base font-black text-gray-900">Total Pembayaran</td><td class="px-8 py-6 text-right text-2xl font-black text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($order->payment_status === 'pending' && $order->status !== 'cancelled')
            <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST" id="cancel-form" class="text-center">
                @csrf
                <button type="button" onclick="confirmCancel()" class="text-red-400 text-xs font-bold hover:text-red-600 transition tracking-tighter uppercase px-6 py-2 border border-red-100 rounded-full">Batalkan Pesanan Ini</button>
            </form>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
@if($order->payment_channel === 'QRIS' && $order->payment_code)
new QRCode(document.getElementById("qris-canvas"), {text:"{{ $order->payment_code }}", width:200, height:200});
@endif

function copy(id){
    navigator.clipboard.writeText(document.getElementById(id).innerText).then(()=>{
        const b = event.currentTarget; const h = b.innerHTML;
        b.innerHTML = '<i class="fas fa-check text-green-500"></i>';
        setTimeout(()=>b.innerHTML=h, 2000);
    });
}
function confirmCancel(){
    showAlert({type:'warning', title:'Batalkan Pesanan?', message:'Tindakan ini permanen.', primaryText:'Batalkan', secondaryText:'Jangan', onConfirm:()=>document.getElementById('cancel-form').submit()});
}
</script>
@endpush

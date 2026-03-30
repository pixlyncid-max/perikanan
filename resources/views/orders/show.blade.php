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
        @if($order->payment_status === 'pending' && $order->status !== 'cancelled')
            <div class="bg-blue-600 rounded-3xl p-8 mb-10 text-white shadow-xl shadow-blue-100">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-credit-card text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Instruksi Pembayaran</h2>
                        <p class="text-blue-100 text-sm">Selesaikan pembayaran agar pesanan segera diproses</p>
                    </div>
                </div>

                @if($order->payment_channel === 'QRIS')
                    <div class="flex flex-col md:flex-row items-center gap-8 bg-white rounded-3xl p-6 text-gray-900">
                        <div id="qris-canvas" class="bg-white p-2 border-2 border-dashed border-gray-100 rounded-2xl"></div>
                        <div class="text-center md:text-left">
                            <h3 class="text-lg font-bold mb-1">Scan QRIS</h3>
                            <p class="text-sm text-gray-500 mb-4">Gunakan m-banking atau e-wallet apa saja</p>
                            <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 rounded-xl mb-4">
                                <span class="text-xs font-bold text-gray-400">NOMINAL:</span>
                                <span class="text-xl font-black text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-[10px] text-red-500 font-bold uppercase">
                                <i class="fas fa-clock mr-1"></i> Batas Waktu: {{ $order->payment_expires_at?->format('d M, H:i') ?? 'Segera' }}
                            </p>
                        </div>
                    </div>
                @elseif(in_array($order->payment_channel, ['ALFAMART', 'INDOMARET']))
                    <div class="bg-white rounded-3xl p-8 text-gray-900">
                         <div class="flex justify-between items-center mb-6">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Gerai Retail</p>
                                <p class="text-2xl font-black text-red-600">{{ $order->payment_channel }}</p>
                            </div>
                            <div class="w-16 h-16 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center"><i class="fas fa-store text-2xl"></i></div>
                        </div>
                        <div class="mb-8">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Kode Pembayaran (Kasir)</p>
                            <div class="flex items-center justify-between bg-gray-50 rounded-2xl p-6 border border-gray-100">
                                <span id="retail-code" class="text-3xl font-black text-gray-900 tracking-wider">{{ $order->payment_code }}</span>
                                <button onclick="copy('retail-code')" class="p-3 text-red-600"><i class="far fa-copy text-xl"></i></button>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-dashed">
                             <p class="text-xs text-gray-500 font-medium">Tunjukkan kode ini ke kasir {{ $order->payment_channel }} dan bayar sesuai nominal.</p>
                             <div class="text-right flex-shrink-0 ml-4"><p class="text-[10px] font-bold text-gray-400">NOMINAL</p><p class="text-lg font-black">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p></div>
                        </div>
                    </div>
                @elseif($order->payment_code) {{-- Virtual Account --}}
                    <div class="bg-white rounded-3xl p-8 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Bank Penerima</p>
                                <p class="text-2xl font-black text-blue-800">{{ $order->payment_channel }}</p>
                            </div>
                            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center"><i class="fas fa-university text-2xl"></i></div>
                        </div>
                        <div class="mb-8">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 font-mono">Nomor Virtual Account</p>
                            <div class="flex items-center justify-between bg-gray-50 rounded-2xl p-6 border border-gray-100">
                                <span id="va-number" class="text-3xl font-black text-blue-600 tracking-wider">{{ $order->payment_code }}</span>
                                <button onclick="copy('va-number')" class="p-3 text-blue-600"><i class="far fa-copy text-xl"></i></button>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div><p class="text-[10px] font-bold text-gray-400 mb-1">TOTAL BAYAR</p><p class="text-xl font-black">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p></div>
                            <div class="text-right"><p class="text-[10px] font-bold text-gray-400 mb-1">BERLAKU SAMPAI</p><p class="text-sm font-bold text-red-500">{{ $order->payment_expires_at?->format('d M Y, H:i') ?? '24 Jam' }}</p></div>
                        </div>
                    </div>
                @elseif($order->payment_url) {{-- E-Wallet Link --}}
                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-8 text-center">
                        <p class="text-white font-medium mb-6">Lanjutkan pembayaran melalui aplikasi e-Wallet Anda:</p>
                        <a href="{{ $order->payment_url }}" target="_blank" class="inline-flex items-center gap-3 bg-white text-blue-600 px-10 py-4 rounded-2xl font-black shadow-xl hover:scale-105 transition-transform">
                             Lanjut ke Pembayaran <i class="fas fa-external-link-alt"></i>
                        </a>
                        <p class="mt-6 text-xs text-blue-100 opacity-70 italic">*Untuk OVO, silakan cek notifikasi aplikasi di smartphone Anda.</p>
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

@extends('layouts.app')

@section('title', 'Kartu Anggota - FISHERIES')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Kartu Anggota Digital</h1>
            <p class="text-gray-600">Tunjukkan kartu ini untuk mendapatkan diskon dan benefit khusus</p>
            <?php 
            $is_admin = isset($is_admin) ? $is_admin : false;
            if($is_admin): 
            ?>
            <div class="mt-4">
                <span class="bg-red-600 text-white px-4 py-2 rounded-full text-sm font-bold">
                    <i class="fas fa-crown mr-2"></i>ADMIN ACCESS
                </span>
            </div>
            <?php endif; ?>
        </div>

        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-2xl overflow-hidden mb-8">
            <div class="p-8 text-white relative">
                <div class="absolute top-4 right-4 opacity-20">
                    <i class="fas fa-fish text-9xl"></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mr-4">
                                <img src="{{ asset('images/Logo_Symbol.png') }}" alt="Logo Symbol" class="h-10 w-10 object-contain">
                            </div>
                            <div>
                                <img src="{{ asset('images/Logo_font_Putih.png') }}" alt="Fisheries Logo" class="h-8 object-contain mb-1">
                                <p class="text-blue-200 mt-1">Kartu Anggota</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <?php if($is_admin): ?>
                                <span class="bg-red-500 text-white px-4 py-1 rounded-full text-sm font-bold">ADMIN</span>
                            <?php else: ?>
                                <span class="bg-white text-blue-800 px-4 py-1 rounded-full text-sm font-bold">ANGGOTA</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-blue-200 text-sm mb-1">Nama Lengkap</p>
                            <p class="text-xl font-bold"><?php echo isset($member->name) ? $member->name : ''; ?></p>
                        </div>
                        <div>
                            <p class="text-blue-200 text-sm mb-1">Nomor Anggota</p>
                            <p class="text-xl font-bold"><?php echo isset($member->member_number) ? $member->member_number : ''; ?></p>
                        </div>
                        <div>
                            <p class="text-blue-200 text-sm mb-1">DPC</p>
                            <p class="text-lg"><?php echo isset($member->dpc) ? ucfirst($member->dpc) : ''; ?></p>
                        </div>
                        <div>
                            <p class="text-blue-200 text-sm mb-1">Berlaku Hingga</p>
                            <p class="text-lg"><?php echo isset($member->expiry_date) ? date('d F Y', strtotime($member->expiry_date)) : ''; ?></p>
                        </div>
                    </div>

                    <div class="border-t border-blue-400 pt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-200 text-sm mb-1">Scan untuk verifikasi</p>
                                <div class="bg-white p-2 rounded-lg inline-block">
                                    <?php 
                                    $qrData = isset($member->member_number) ? urlencode('ID: ' . $member->member_number) : '';
                                    ?>
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?php echo $qrData; ?>" alt="QR Code" class="w-24 h-24">
                                </div>
                                <p class="text-xs text-blue-200 mt-1">ID: <?php echo isset($member->member_number) ? $member->member_number : ''; ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-blue-200 text-sm mb-1">Status</p>
                                <span class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-bold">
                                    <?php echo (isset($member->status) && $member->status === 'active') ? 'Aktif' : 'Tidak Aktif'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Benefit Anggota</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <i class="fas fa-percentage text-blue-600 text-2xl mb-2"></i>
                    <p class="font-semibold text-gray-800">Diskon 10%</p>
                    <p class="text-sm text-gray-600">Untuk semua produk</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <i class="fas fa-graduation-cap text-green-600 text-2xl mb-2"></i>
                    <p class="font-semibold text-gray-800">Pelatihan Gratis</p>
                    <p class="text-sm text-gray-600">Akses ke semua pelatihan</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4 text-center">
                    <i class="fas fa-headset text-purple-600 text-2xl mb-2"></i>
                    <p class="font-semibold text-gray-800">Konsultasi Teknis</p>
                    <p class="text-sm text-gray-600">Dukungan ahli perikanan</p>
                </div>
            </div>
            <?php if($is_admin): ?>
            <div class="mt-4 p-4 bg-red-50 rounded-lg border border-red-200">
                <h4 class="font-bold text-red-800 mb-2"><i class="fas fa-crown mr-2"></i>Benefit Khusus Admin</h4>
                <ul class="text-sm text-red-700 space-y-1">
                    <li><i class="fas fa-check mr-2"></i>Diskon 25% untuk semua produk</li>
                    <li><i class="fas fa-check mr-2"></i>Akses panel admin</li>
                    <li><i class="fas fa-check mr-2"></i>Manajemen anggota & pesanan</li>
                    <li><i class="fas fa-check mr-2"></i>Membership lifetime</li>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Riwayat Transaksi</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">No. Order</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">Total</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (!empty($orders) && count($orders) > 0): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-800">
                                        <?php echo isset($order->created_at) ? date('d M Y', strtotime($order->created_at)) : '-'; ?>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-800">
                                        <?php echo isset($order->order_number) ? $order->order_number : '-'; ?>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-800 text-right">
                                        Rp <?php echo isset($order->total_amount) ? number_format($order->total_amount, 0, ',', '.') : '0'; ?>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <?php 
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                        $statusLabel = isset($order->status) ? ucfirst($order->status) : 'Pending';
                                        
                                        if (isset($order->status)) {
                                            switch($order->status) {
                                                case 'completed':
                                                case 'delivered':
                                                    $statusClass = 'bg-green-100 text-green-800';
                                                    $statusLabel = 'Selesai';
                                                    break;
                                                case 'processing':
                                                case 'shipped':
                                                    $statusClass = 'bg-blue-100 text-blue-800';
                                                    $statusLabel = 'Diproses';
                                                    break;
                                                case 'pending':
                                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                                    $statusLabel = 'Menunggu';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'bg-red-100 text-red-800';
                                                    $statusLabel = 'Dibatalkan';
                                                    break;
                                            }
                                        }
                                        ?>
                                        <span class="<?php echo $statusClass; ?> px-2 py-1 rounded-full text-xs"><?php echo $statusLabel; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-shopping-cart text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum ada transaksi</p>
                                    <?php if($is_admin): ?>
                                        <p class="text-sm text-gray-400 mt-1">Admin dapat melihat semua pesanan di panel admin</p>
                                    <?php else: ?>
                                        <p class="text-sm text-gray-400 mt-1">Mulai berbelanja untuk melihat riwayat transaksi Anda</p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="flex justify-center space-x-4">
            <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-print mr-2"></i>Cetak Kartu
            </button>
            <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition flex items-center">
                <i class="fas fa-download mr-2"></i>Download PDF
            </button>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .bg-gradient-to-r, .bg-gradient-to-r * {
        visibility: visible;
    }
    .bg-gradient-to-r {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
@endsection

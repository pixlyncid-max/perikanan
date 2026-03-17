@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 gap-6 auto-rows-max">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500">Selamat datang, {{ \Illuminate\Support\Facades\Session::get('user.name', \Illuminate\Support\Facades\Session::get('admin_data.name', 'Admin')) }}!</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total User</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalUsers ?? 0 }}</p>
                </div>

            </div>
        </div>
        
        <!-- Total Members -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <i class="fas fa-id-card text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Anggota</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalMembers ?? 0 }}</p>
                </div>

            </div>
        </div>
        
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <i class="fas fa-box text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalProducts ?? 0 }}</p>
                </div>

            </div>
        </div>
        
        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalOrders ?? 0 }}</p>
                </div>

            </div>
        </div>
        
        <!-- Total Articles -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cyan-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-cyan-100 text-cyan-500">
                    <i class="fas fa-newspaper text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Artikel</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalArticles ?? 0 }}</p>
                </div>

            </div>
        </div>
        
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-pink-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-pink-100 text-pink-500">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts and Recent Data -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Order Statistics Chart -->
        <div class="bg-white rounded-lg shadow p-6 h-72">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Pesanan</h3>
            <div style="position: relative; height: 200px;">
                <canvas id="orderChart"></canvas>
            </div>
        </div>
        
        <!-- Quick Access Menu -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Akses Cepat</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.users.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <i class="fas fa-user-plus text-blue-500 text-xl mr-3"></i>
                    <span class="font-medium text-blue-700">Tambah User</span>
                </a>
                <a href="{{ route('admin.products.create') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                    <i class="fas fa-plus-circle text-yellow-500 text-xl mr-3"></i>
                    <span class="font-medium text-yellow-700">Tambah Produk</span>
                </a>
                <a href="{{ route('admin.articles.create') }}" class="flex items-center p-4 bg-cyan-50 rounded-lg hover:bg-cyan-100 transition">
                    <i class="fas fa-file-alt text-cyan-500 text-xl mr-3"></i>
                    <span class="font-medium text-cyan-700">Tambah Artikel</span>
                </a>
                <a href="{{ route('admin.news.create') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                    <i class="fas fa-bullhorn text-purple-500 text-xl mr-3"></i>
                    <span class="font-medium text-purple-700">Tambah Berita</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Recent Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">User Terbaru</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-cyan-600 hover:text-cyan-800">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto max-h-96">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentUsers ?? [] as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data user</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-cyan-600 hover:text-cyan-800">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto max-h-96">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentOrders ?? [] as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->user->name ?? 'Guest' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ ($order->status ?? '') === 'completed' ? 'bg-green-100 text-green-800' : 
                                       (($order->status ?? '') === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       (($order->status ?? '') === 'processing' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                    {{ ucfirst($order->status ?? 'unknown') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data pesanan</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    
    <!-- Recent Articles -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Artikel Terbaru</h3>
            <a href="{{ route('admin.articles.index') }}" class="text-sm text-cyan-600 hover:text-cyan-800">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto max-h-96">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentArticles ?? [] as $article)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ Str::limit($article->title ?? 'Untitled', 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $article->author ?? 'Admin' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ ($article->status ?? '') === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($article->status ?? 'Draft') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $article->created_at ? $article->created_at->format('d M Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data artikel</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Order Statistics Chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('orderChart');
        if (!ctx) {
            console.error('Chart canvas not found');
            return;
        }
        
        try {
            new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
                    datasets: [{
                        data: [
                            {{ $orderStats['pending'] ?? 0 }},
                            {{ $orderStats['processing'] ?? 0 }},
                            {{ $orderStats['completed'] ?? 0 }},
                            {{ $orderStats['cancelled'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#FCD34D', // Yellow
                            '#60A5FA', // Blue
                            '#34D399', // Green
                            '#F87171'  // Red
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        } catch (e) {
            console.error('Chart initialization error:', e);
        }
    });
</script>
@endpush
@endsection

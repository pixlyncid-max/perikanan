@extends('layouts.app')

@section('title', 'Fisheries.id - Platform Perikanan & Sewa Alat Pancing Terlengkap')
@section('meta_description', 'Fisheries.id adalah platform perikanan dan komunitas nelayan terbesar. Layanan penyewaan alat pancing, jual pelet pakan ikan, umpan laut, dan bibit unggul.')
@section('meta_keywords', 'fisheries.id, perikanan indonesia, sewa pancing, pelet pakan ikan, umpan laut, penyewaan kapal pancing, bibit ikan')

@section('content')
<!-- Hero Section with Auto-sliding Background -->
<div class="relative min-h-[450px] md:min-h-[550px] md:h-[600px] overflow-hidden home-hero-indicator">
    <div id="hero-slider" class="absolute inset-0">
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 opacity-100">
            <img src="https://images.unsplash.com/photo-1544552866-d3ed42536cfd?w=1920" alt="Fisheries 1" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-[#002A49]/90 to-[#019ADA]/40"></div>
        </div>
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 opacity-0">
            <img src="https://images.unsplash.com/photo-1569263979104-865ab7cd8d13?w=1920" alt="Fisheries 2" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-[#002A49]/90 to-[#019ADA]/40"></div>
        </div>
        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 opacity-0">
            <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1920" alt="Fisheries 3" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-[#002A49]/90 to-[#019ADA]/40"></div>
        </div>
    </div>
    
    <div class="container mx-auto px-4 min-h-[450px] md:min-h-[550px] md:h-[600px] flex items-center relative z-10 py-16 md:py-0">
        <div class="max-w-3xl text-white">
            <h1 class="text-4xl md:text-6xl font-bold mb-4 md:mb-6 leading-tight tracking-tight font-sans">
                {{ get_setting('hero_title', 'Indonesian Fisheries Community') }}
            </h1>
            <p class="text-lg md:text-xl mb-8 md:mb-10 opacity-90 leading-relaxed max-w-2xl font-light text-white/90">
                {{ get_setting('hero_subtitle', 'Komunitas perikanan terbesar di Kalimantan Timur. Menghubungkan nelayan, pembudidaya, dan pelaku usaha perikanan.') }}
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ get_setting('hero_button1_url', '/produk') }}" class="px-8 py-4 bg-[#019ADA] text-white rounded-full font-semibold hover:bg-[#017CB3] transition-all duration-300 flex items-center shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fas fa-shopping-bag mr-2"></i> {{ get_setting('hero_button1_text', 'Lihat Produk') }}
                </a>
                <a href="{{ get_setting('hero_button2_url', '/register') }}" class="px-8 py-4 bg-white/10 text-white border border-white/20 rounded-full font-semibold hover:bg-white/20 hover:border-white/30 transition-all duration-300 flex items-center backdrop-blur-sm shadow-md hover:-translate-y-0.5">
                    <i class="fas fa-user-plus mr-2"></i> {{ get_setting('hero_button2_text', 'Gabung Sekarang') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- News Slider -->
<div class="container mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Berita Terkini</h2>
        <a href="/article" class="text-blue-600 font-medium hover:text-blue-700 flex items-center">
            Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($latestArticles as $article)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                <div class="h-48 bg-gray-200">
                    @if($article->featured_image)
                        <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://images.unsplash.com/photo-1544552866-d3ed42536cfd?w=400" alt="{{ $article->title }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">{{ $article->category ?? 'Berita' }}</span>
                        <span class="text-gray-500 text-sm">{{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">
                        <a href="{{ route('article.show', $article->slug) }}" class="hover:text-blue-600 transition">
                            {{ $article->title }}
                        </a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 100) }}</p>
                    <a href="{{ route('article.show', $article->slug) }}" class="text-blue-600 font-medium text-sm hover:text-blue-700">Baca Selengkapnya</a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-gray-50 rounded-xl">
                <p class="text-gray-500">Belum ada berita terbaru saat ini.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Chart Section -->
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 text-center mb-8">Statistik Kaltim Tahun {{ isset($chartData) ? $chartData['year'] : date('Y') }}</h2>
        
        <!-- Charts Grid - 4 columns on desktop, 2 on tablet, 1 on mobile -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Chart 1: Pembudidaya Ikan -->
            <div class="bg-white rounded-xl shadow-lg p-4">
                <h3 class="text-base font-bold text-gray-800 text-center mb-2">Pembudidaya Ikan</h3>
                <div class="relative flex justify-center">
                    <canvas id="fishChart" width="180" height="180"></canvas>
                </div>
                <p class="text-gray-500 text-xs text-center mt-2">Jumlah pembudidaya ikan</p>
            </div>
            
            <!-- Chart 2: Pembudidaya Udang -->
            <div class="bg-white rounded-xl shadow-lg p-4">
                <h3 class="text-base font-bold text-gray-800 text-center mb-2">Pembudidaya Udang</h3>
                <div class="relative flex justify-center">
                    <canvas id="shrimpChart" width="180" height="180"></canvas>
                </div>
                <p class="text-gray-500 text-xs text-center mt-2">Jumlah pembudidaya udang</p>
            </div>
            
            <!-- Chart 3: Nelayan -->
            <div class="bg-white rounded-xl shadow-lg p-4">
                <h3 class="text-base font-bold text-gray-800 text-center mb-2">Nelayan</h3>
                <div class="relative flex justify-center">
                    <canvas id="fishermanChart" width="180" height="180"></canvas>
                </div>
                <p class="text-gray-500 text-xs text-center mt-2">Jumlah nelayan aktif</p>
            </div>
            
            <!-- Chart 4: Pembudidaya Lainnya -->
            <div class="bg-white rounded-xl shadow-lg p-4">
                <h3 class="text-base font-bold text-gray-800 text-center mb-2">Pembudidaya Lainnya</h3>
                <div class="relative flex justify-center">
                    <canvas id="othersChart" width="180" height="180"></canvas>
                </div>
                <p class="text-gray-500 text-xs text-center mt-2">Kepiting, rumput laut, dll</p>
            </div>
            
        </div>
    </div>
</div>

<!-- Featured Products -->
<div class="bg-gradient-to-r from-[#002A49] to-[#019ADA] py-16 text-white my-12 rounded-2xl">
    <div class="container mx-auto px-6">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-white">Produk Unggulan</h2>
            <a href="/produk" class="text-white/90 font-medium hover:text-white flex items-center bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full transition">
                Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <a href="/produk/pelet-pakan" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition group">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-500 transition">
                    <i class="fas fa-cookie text-orange-500 text-2xl group-hover:text-white transition"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Pelet Pakan</h3>
                <p class="text-sm text-gray-600 mt-1">Nutrisi lengkap</p>
            </a>
            
            <a href="/produk/pakan-hidup" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition group">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-500 transition">
                    <i class="fas fa-bug text-green-500 text-2xl group-hover:text-white transition"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Pakan Hidup</h3>
                <p class="text-sm text-gray-600 mt-1">Artemia & cacing</p>
            </a>
            
            <a href="/produk/umpan-laut" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition group">
                <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-cyan-500 transition">
                    <i class="fas fa-fish text-cyan-500 text-2xl group-hover:text-white transition"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Umpan Laut</h3>
                <p class="text-sm text-gray-600 mt-1">Untuk mancing</p>
            </a>
            
            <a href="/produk/penyewaan-kapal" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition group">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-500 transition">
                    <i class="fas fa-ship text-blue-500 text-2xl group-hover:text-white transition"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Sewa Kapal</h3>
                <p class="text-sm text-gray-600 mt-1">Kapal nelayan</p>
            </a>
            
            <a href="/produk/vitamin-air" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition group">
                <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-teal-500 transition">
                    <i class="fas fa-flask text-teal-500 text-2xl group-hover:text-white transition"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Vitamin Air</h3>
                <p class="text-sm text-gray-600 mt-1">Perawatan kolam</p>
            </a>
            
            <a href="/produk/bibit-ikan" class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition group">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-500 transition">
                    <i class="fas fa-seedling text-emerald-500 text-2xl group-hover:text-white transition"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Bibit Ikan</h3>
                <p class="text-sm text-gray-600 mt-1">Berkualitas unggul</p>
            </a>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-white pt-12 pb-24 cta-section">
    <div class="container mx-auto px-4 text-center text-gray-900 relative z-10">
        <h2 class="text-2xl md:text-3xl font-bold mb-3 tracking-tight">Siap Bergabung?</h2>
        <p class="text-sm md:text-base mb-6 opacity-90 font-light text-gray-600 max-w-xl mx-auto">Jadilah bagian dari komunitas perikanan terbesar di Kalimantan Timur</p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="/register" class="px-6 py-2.5 bg-[#002A49] text-white rounded-full font-semibold hover:bg-[#0e3a5c] hover:scale-105 transition-all duration-300 shadow-md text-sm">
                Daftar Anggota
            </a>
            <a href="/partnership" class="px-6 py-2.5 bg-[#019ADA] text-white rounded-full font-semibold hover:bg-[#017CB3] hover:scale-105 transition-all duration-300 shadow-md text-sm">
                Jadi Mitra
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Hero Slider
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.opacity = i === index ? '1' : '0';
        });
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }
    
    setInterval(nextSlide, 5000);

    // Common chart options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '60%',
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    padding: 5,
                    boxWidth: 8,
                    usePointStyle: true,
                    pointStyle: 'circle',
                    font: { size: 8 }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const value = context.parsed;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return ` ${context.label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    };

    // Chart 1: Pembudidaya Ikan (Doughnut Chart)
    const serverChartData = @json($chartData ?? null);
    
    const fishData = {
        labels: serverChartData ? serverChartData.labels : ['Samarinda', 'Bontang', 'Balikpapan', 'Kukar', 'Kutim', 'Berau', 'Paser', 'Lainnya'],
        datasets: [{
            data: serverChartData ? serverChartData.fish : [450, 380, 420, 290, 250, 180, 150, 320],
            backgroundColor: [
                '#0ea5e9', '#06b6d4', '#10b981', '#3b82f6', 
                '#14b8a6', '#22c55e', '#0d9488', '#64748b'
            ],
            borderColor: '#ffffff',
            borderWidth: 2,
            hoverOffset: 10
        }]
    };
    
    const totalFish = fishData.datasets[0].data.reduce((a, b) => a + b, 0);
    
    new Chart(document.getElementById('fishChart'), {
        type: 'doughnut',
        data: fishData,
        options: commonOptions,
        plugins: [{
            id: 'centerTextFish',
            beforeDraw: function(chart) {
                const width = chart.width, height = chart.height, ctx = chart.ctx;
                ctx.restore();
                const fontSize = (height / 120).toFixed(2);
                ctx.font = `bold ${fontSize}em 'Segoe UI', sans-serif`;
                ctx.textBaseline = 'middle';
                ctx.fillStyle = '#1e293b';
                const text = totalFish.toLocaleString('id-ID');
                const textX = Math.round((width - ctx.measureText(text).width) / 2);
                const textY = height / 2;
                ctx.fillText(text, textX, textY);
                ctx.save();
            }
        }]
    });

    // Chart 2: Pembudidaya Udang (Doughnut Chart)
    const shrimpData = {
        labels: serverChartData ? serverChartData.labels : ['Samarinda', 'Bontang', 'Balikpapan', 'Kukar', 'Kutim', 'Berau', 'Paser', 'Lainnya'],
        datasets: [{
            data: serverChartData ? serverChartData.shrimp : [280, 220, 250, 180, 150, 120, 90, 160],
            backgroundColor: [
                '#f97316', '#fb923c', '#fbbf24', '#f59e0b',
                '#d97706', '#b45309', '#92400e', '#78350f'
            ],
            borderColor: '#ffffff',
            borderWidth: 2,
            hoverOffset: 10
        }]
    };
    
    const totalShrimp = shrimpData.datasets[0].data.reduce((a, b) => a + b, 0);
    
    new Chart(document.getElementById('shrimpChart'), {
        type: 'doughnut',
        data: shrimpData,
        options: commonOptions,
        plugins: [{
            id: 'centerTextShrimp',
            beforeDraw: function(chart) {
                const width = chart.width, height = chart.height, ctx = chart.ctx;
                ctx.restore();
                const fontSize = (height / 120).toFixed(2);
                ctx.font = `bold ${fontSize}em 'Segoe UI', sans-serif`;
                ctx.textBaseline = 'middle';
                ctx.fillStyle = '#1e293b';
                const text = totalShrimp.toLocaleString('id-ID');
                const textX = Math.round((width - ctx.measureText(text).width) / 2);
                const textY = height / 2;
                ctx.fillText(text, textX, textY);
                ctx.save();
            }
        }]
    });

    // Chart 3: Nelayan (Doughnut Chart)
    const fishermanData = {
        labels: serverChartData ? serverChartData.labels : ['Samarinda', 'Bontang', 'Balikpapan', 'Kukar', 'Kutim', 'Berau', 'Paser', 'Lainnya'],
        datasets: [{
            data: serverChartData ? serverChartData.fisherman : [850, 720, 680, 540, 480, 390, 320, 610],
            backgroundColor: [
                '#3b82f6', '#0ea5e9', '#06b6d4', '#14b8a6',
                '#10b981', '#22c55e', '#84cc16', '#65a30d'
            ],
            borderColor: '#ffffff',
            borderWidth: 2,
            hoverOffset: 10
        }]
    };
    
    const totalFisherman = fishermanData.datasets[0].data.reduce((a, b) => a + b, 0);
    
    new Chart(document.getElementById('fishermanChart'), {
        type: 'doughnut',
        data: fishermanData,
        options: commonOptions,
        plugins: [{
            id: 'centerTextFisherman',
            beforeDraw: function(chart) {
                const width = chart.width, height = chart.height, ctx = chart.ctx;
                ctx.restore();
                const fontSize = (height / 120).toFixed(2);
                ctx.font = `bold ${fontSize}em 'Segoe UI', sans-serif`;
                ctx.textBaseline = 'middle';
                ctx.fillStyle = '#1e293b';
                const text = totalFisherman.toLocaleString('id-ID');
                const textX = Math.round((width - ctx.measureText(text).width) / 2);
                const textY = height / 2;
                ctx.fillText(text, textX, textY);
                ctx.save();
            }
        }]
    });

    // Chart 4: Pembudidaya Lainnya (Doughnut Chart)
    const othersData = {
        labels: ['Kepiting', 'Rumput Laut', 'Kerang', 'Lobster', 'Abalon', 'Teripang', 'Lainnya'],
        datasets: [{
            data: serverChartData ? serverChartData.others : [120, 95, 80, 45, 30, 55, 75],
            backgroundColor: [
                '#8b5cf6', '#a78bfa', '#c4b5fd', '#7c3aed',
                '#6d28d9', '#5b21b6', '#4c1d95'
            ],
            borderColor: '#ffffff',
            borderWidth: 2,
            hoverOffset: 10
        }]
    };
    
    const totalOthers = othersData.datasets[0].data.reduce((a, b) => a + b, 0);
    
    new Chart(document.getElementById('othersChart'), {
        type: 'doughnut',
        data: othersData,
        options: commonOptions,
        plugins: [{
            id: 'centerTextOthers',
            beforeDraw: function(chart) {
                const width = chart.width, height = chart.height, ctx = chart.ctx;
                ctx.restore();
                const fontSize = (height / 120).toFixed(2);
                ctx.font = `bold ${fontSize}em 'Segoe UI', sans-serif`;
                ctx.textBaseline = 'middle';
                ctx.fillStyle = '#1e293b';
                const text = totalOthers.toLocaleString('id-ID');
                const textX = Math.round((width - ctx.measureText(text).width) / 2);
                const textY = height / 2;
                ctx.fillText(text, textX, textY);
                ctx.save();
            }
        }]
    });
</script>
@endsection

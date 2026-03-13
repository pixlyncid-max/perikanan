@extends('layouts.app')

@section('title', $dpc->name . ' - FISHERIES')

@push('styles')
<style>
    /* Fixed Size Square Card Flip Animation - Same as structure.blade.php */
    .card-container {
        perspective: 1500px;
        cursor: pointer;
        height: 320px;
        min-height: 320px;
        position: relative;
        transform-style: preserve-3d;
    }

    .card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 320px;
        transition: transform 0.9s cubic-bezier(0.23, 1, 0.32, 1);
        transform-style: preserve-3d;
        will-change: transform;
    }

    .card-container:hover .card-inner {
        transform: rotateY(180deg) translateZ(20px);
    }

    .card-container.flipped .card-inner {
        transform: rotateY(180deg) translateZ(20px);
    }

    .card-front,
    .card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        min-height: 320px;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        top: 0;
        left: 0;
        border-radius: 1rem;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .card-back {
        transform: rotateY(180deg);
    }

    /* Mobile tap behavior */
    @media (hover: none) and (pointer: coarse) {
        .card-container:hover .card-inner {
            transform: none;
        }

        .card-container.flipped .card-inner {
            transform: rotateY(180deg) translateZ(20px);
        }
    }

    /* Smooth shadow transition */
    .card-container {
        transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        border-radius: 1rem;
    }

    .card-container:hover {
        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.25);
        transform: translateY(-8px);
    }

    /* Card content styling - Vertical Layout */
    .card-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        text-align: center;
        padding-top: 1.5rem;
    }

    .card-title {
        font-weight: 700;
        line-height: 1.3;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        word-wrap: break-word;
        margin-top: 1rem;
    }

    .card-subtitle {
        line-height: 1.4;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        word-wrap: break-word;
        margin-top: 0.5rem;
    }

    /* Back card content - No scroll, fixed height */
    .card-back-content {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 1rem;
    }

    /* Info box styling - Compact */
    .info-box {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        margin-bottom: 0.5rem;
    }

    .info-box:last-child {
        margin-bottom: 0;
    }

    /* Profile image - Centered on top */
    .profile-wrapper {
        position: relative;
        transition: transform 0.4s ease;
        margin-bottom: 0.5rem;
    }

    .profile-image {
        transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .card-container:hover .profile-image {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-container {
            height: 300px;
            min-height: 300px;
        }
        
        .card-inner,
        .card-front,
        .card-back {
            min-height: 300px;
        }
    }

    /* Section headers with elegant underline */
    section h2 {
        position: relative;
        display: inline-block;
        padding-bottom: 0.75rem;
        margin-bottom: 2rem;
    }

    section h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #06b6d4);
        border-radius: 2px;
    }

    /* Entrance animation */
    @keyframes cardEntrance {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .card-container {
        animation: cardEntrance 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        opacity: 0;
    }

    .card-container:nth-child(1) { animation-delay: 0.1s; }
    .card-container:nth-child(2) { animation-delay: 0.2s; }
    .card-container:nth-child(3) { animation-delay: 0.3s; }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-blue-600 to-blue-500 py-16">
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $dpc->name }}</h1>
            <p class="text-xl opacity-90">{{ $dpc->description }}</p>
        </div>
    </div>
</div>

<!-- DPC Content -->
<div class="container mx-auto px-4 py-12">
    <!-- Info Card -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-blue-600 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ $dpc->member_count ?? 0 }}</h3>
                <p class="text-gray-600">Anggota Aktif</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-alt text-green-600 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ $dpc->established_year ?? '-' }}</h3>
                <p class="text-gray-600">Tahun Berdiri</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-orange-600 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ $dpc->city }}</h3>
                <p class="text-gray-600">Kota/Kabupaten</p>
            </div>
        </div>
    </div>

    <!-- Leadership -->
    <section class="mb-16 text-center">
        <h2 class="text-3xl font-bold text-gray-800">Pengurus {{ $dpc->name }}</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8 max-w-5xl mx-auto">
            <!-- Ketua DPC -->
            <div class="card-container bg-white rounded-2xl shadow-lg overflow-hidden" onclick="toggleFlip(this)">
                <div class="card-inner">
                    <!-- Front Card -->
                    <div class="card-front p-5 bg-white">
                        <div class="card-content">
                            <!-- Foto Profil - Centered on Top -->
                            <div class="profile-wrapper">
                                <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-blue-50 rounded-full flex items-center justify-center mx-auto">
                                    <i class="fas fa-user-tie text-blue-600 text-3xl"></i>
                                </div>
                            </div>
                            <!-- Info - Below Photo -->
                            <h3 class="card-title text-base font-bold text-gray-800">{{ $dpc->chairman ?? 'Belum ditentukan' }}</h3>
                            <p class="card-subtitle text-blue-600 text-sm font-medium">Ketua DPC</p>
                        </div>
                    </div>

                    <!-- Back Card -->
                    <div class="card-back p-4 bg-gradient-to-br from-blue-700 to-blue-800 text-white">
                        <div class="card-back-content">
                            <h3 class="text-sm font-bold mb-3 text-center">{{ $dpc->chairman ?? 'Belum ditentukan' }}</h3>
                            <div class="w-full">
                                <div class="info-box">
                                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Jabatan</p>
                                    <p class="font-semibold text-white text-xs">Ketua DPC</p>
                                </div>
                                <div class="info-box">
                                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Wilayah</p>
                                    <p class="font-semibold text-white text-xs">{{ $dpc->city }}</p>
                                </div>
                                <div class="info-box">
                                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Organisasi</p>
                                    <p class="font-semibold text-white text-xs">{{ $dpc->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sekretaris -->
            <div class="card-container bg-white rounded-2xl shadow-lg overflow-hidden" onclick="toggleFlip(this)">
                <div class="card-inner">
                    <!-- Front Card -->
                    <div class="card-front p-5 bg-white">
                        <div class="card-content">
                            <!-- Foto Profil - Centered on Top -->
                            <div class="profile-wrapper">
                                <div class="w-20 h-20 bg-gradient-to-br from-green-100 to-green-50 rounded-full flex items-center justify-center mx-auto">
                                    <i class="fas fa-user text-green-600 text-3xl"></i>
                                </div>
                            </div>
                            <!-- Info - Below Photo -->
                            <h3 class="card-title text-base font-bold text-gray-800">{{ $dpc->secretary ?? 'Belum ditentukan' }}</h3>
                            <p class="card-subtitle text-green-600 text-sm font-medium">Sekretaris</p>
                        </div>
                    </div>

                    <!-- Back Card -->
                    <div class="card-back p-4 bg-gradient-to-br from-green-700 to-green-800 text-white">
                        <div class="card-back-content">
                            <h3 class="text-sm font-bold mb-3 text-center">{{ $dpc->secretary ?? 'Belum ditentukan' }}</h3>
                            <div class="w-full">
                                <div class="info-box">
                                    <p class="text-green-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Jabatan</p>
                                    <p class="font-semibold text-white text-xs">Sekretaris</p>
                                </div>
                                <div class="info-box">
                                    <p class="text-green-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Wilayah</p>
                                    <p class="font-semibold text-white text-xs">{{ $dpc->city }}</p>
                                </div>
                                <div class="info-box">
                                    <p class="text-green-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Organisasi</p>
                                    <p class="font-semibold text-white text-xs">{{ $dpc->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bendahara -->
            <div class="card-container bg-white rounded-2xl shadow-lg overflow-hidden" onclick="toggleFlip(this)">
                <div class="card-inner">
                    <!-- Front Card -->
                    <div class="card-front p-5 bg-white">
                        <div class="card-content">
                            <!-- Foto Profil - Centered on Top -->
                            <div class="profile-wrapper">
                                <div class="w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-50 rounded-full flex items-center justify-center mx-auto">
                                    <i class="fas fa-user text-orange-600 text-3xl"></i>
                                </div>
                            </div>
                            <!-- Info - Below Photo -->
                            <h3 class="card-title text-base font-bold text-gray-800">{{ $dpc->treasurer ?? 'Belum ditentukan' }}</h3>
                            <p class="card-subtitle text-orange-600 text-sm font-medium">Bendahara</p>
                        </div>
                    </div>

                    <!-- Back Card -->
                    <div class="card-back p-4 bg-gradient-to-br from-orange-700 to-orange-800 text-white">
                        <div class="card-back-content">
                            <h3 class="text-sm font-bold mb-3 text-center">{{ $dpc->treasurer ?? 'Belum ditentukan' }}</h3>
                            <div class="w-full">
                                <div class="info-box">
                                    <p class="text-orange-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Jabatan</p>
                                    <p class="font-semibold text-white text-xs">Bendahara</p>
                                </div>
                                <div class="info-box">
                                    <p class="text-orange-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Wilayah</p>
                                    <p class="font-semibold text-white text-xs">{{ $dpc->city }}</p>
                                </div>
                                <div class="info-box">
                                    <p class="text-orange-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Organisasi</p>
                                    <p class="font-semibold text-white text-xs">{{ $dpc->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Kontak {{ $dpc->name }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-map-marker-alt text-blue-600 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800">Alamat</h4>
                        <p class="text-gray-600">{{ $dpc->address ?? 'Alamat belum tersedia' }}</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-phone text-blue-600 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800">Telepon</h4>
                        <p class="text-gray-600">{{ $dpc->phone ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-envelope text-blue-600 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800">Email</h4>
                        <p class="text-gray-600">{{ $dpc->email ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 h-64 flex items-center justify-center">
                <p class="text-gray-500">Peta Lokasi</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Mobile tap behavior for card flip
    function toggleFlip(card) {
        // Check if device is touch-based
        if (window.matchMedia('(hover: none) and (pointer: coarse)').matches) {
            card.classList.toggle('flipped');
        }
    }

    // Prevent flip when clicking on links inside cards
    document.querySelectorAll('.card-container a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // Add keyboard accessibility
    document.querySelectorAll('.card-container').forEach(card => {
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'button');
        card.setAttribute('aria-label', 'Klik untuk melihat detail');
        
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.classList.toggle('flipped');
            }
        });
    });
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Struktur Organisasi - FISHERIES')

@push('styles')
<style>
    /* Fixed Size Square Card Flip Animation */
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

    /* Ketua Umum card - Same size as others */
    .max-w-\[400px\] .card-container,
    .max-w-\[400px\] .card-inner,
    .max-w-\[400px\] .card-front,
    .max-w-\[400px\] .card-back {
        height: 320px;
        min-height: 320px;
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
        
        .max-w-\[400px\] .card-container,
        .max-w-\[400px\] .card-inner,
        .max-w-\[400px\] .card-front,
        .max-w-\[400px\] .card-back {
            height: 300px;
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
    .card-container:nth-child(4) { animation-delay: 0.4s; }
    .card-container:nth-child(5) { animation-delay: 0.5s; }
    .card-container:nth-child(6) { animation-delay: 0.6s; }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-blue-600 to-blue-500 py-16">
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Struktur Organisasi</h1>
            <p class="text-xl opacity-90">{{ $dpp->name ?? 'DPP Pusat FISHERIES Indonesia' }}</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <!-- SECTION 1: KETUA UMUM -->
    <section class="mb-16 text-center">
        <h2 class="text-3xl font-bold text-gray-800">KETUA UMUM</h2>

        <div class="max-w-[400px] mx-auto mt-8">
            <div class="card-container bg-white rounded-2xl shadow-lg overflow-hidden" onclick="toggleFlip(this)">
                <div class="card-inner">
                    <!-- Front Card -->
                    <div class="card-front p-6 bg-white">
                        <div class="card-content">
                            <!-- Foto Profil - Centered on Top -->
                            <div class="profile-wrapper">
                                <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-blue-50 rounded-full flex items-center justify-center mx-auto">
                                    @if($ketuaUmum['photo'])
                                        <img src="{{ $ketuaUmum['photo'] }}" alt="{{ $ketuaUmum['name'] }}" class="profile-image w-full h-full object-cover rounded-full border-4 border-white">
                                    @else
                                        <i class="fas fa-user-tie text-blue-600 text-4xl"></i>
                                    @endif
                                </div>
                            </div>
                            <!-- Info - Below Photo -->
                            <h3 class="card-title text-lg font-bold text-gray-800">{{ $ketuaUmum['name'] }}</h3>
                            <p class="card-subtitle text-blue-600 font-semibold text-base">{{ $ketuaUmum['position'] }}</p>
                            <p class="text-gray-500 text-sm mt-2">{{ $ketuaUmum['organization'] }}</p>
                        </div>
                    </div>

                    <!-- Back Card - No scroll, fixed content -->
                    <div class="card-back p-4 bg-gradient-to-br from-blue-700 to-blue-800 text-white">
                        <div class="card-back-content">
                            <h3 class="text-base font-bold mb-3 text-center">{{ $ketuaUmum['full_name'] }}</h3>
                            <div class="w-full">
                                <div class="info-box">
                                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Jabatan</p>
                                    <p class="font-semibold text-white text-sm">{{ $ketuaUmum['position'] }}</p>
                                </div>
                                <div class="info-box">
                                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Bidang</p>
                                    <p class="font-semibold text-white text-sm">{{ $ketuaUmum['field'] }}</p>
                                </div>
                                <div class="info-box">
                                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Lokasi</p>
                                    <p class="font-semibold text-white text-sm">{{ $ketuaUmum['location'] }}</p>
                                </div>
                                <div class="info-box">
                                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Pengalaman</p>
                                    <p class="font-semibold text-white text-sm">{{ $ketuaUmum['experience'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: KETUA DPC -->
    <section class="mb-16 text-center">
        <h2 class="text-3xl font-bold text-gray-800">KETUA DPC</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8 max-w-6xl mx-auto">
            @foreach($ketuaDpcList as $ketuaDpc)
            <div class="card-container bg-white rounded-2xl shadow-lg overflow-hidden" onclick="toggleFlip(this)">
                <div class="card-inner">
                    <!-- Front Card -->
                    <div class="card-front p-5 bg-white">
                        <div class="card-content">
                            <!-- Foto Profil - Centered on Top -->
                            <div class="profile-wrapper">
                                <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-blue-50 rounded-full flex items-center justify-center mx-auto">
                                    @if($ketuaDpc['photo'])
                                        <img src="{{ $ketuaDpc['photo'] }}" alt="{{ $ketuaDpc['name'] }}" class="profile-image w-full h-full object-cover rounded-full border-2 border-white">
                                    @else
                                        <i class="fas fa-user text-blue-600 text-2xl"></i>
                                    @endif
                                </div>
                            </div>
                            <!-- Info - Below Photo -->
                            <h3 class="card-title text-base font-bold text-gray-800">{{ $ketuaDpc['name'] }}</h3>
                            <p class="card-subtitle text-blue-600 text-sm font-medium">{{ $ketuaDpc['position'] }}</p>
                            <p class="text-gray-500 text-xs mt-1">{{ $ketuaDpc['region'] }}</p>
                        </div>
                    </div>

                    <!-- Back Card - No scroll, fixed content -->
                    <div class="card-back p-4 bg-gradient-to-br from-blue-700 to-blue-800 text-white">
                        <div class="card-back-content">
                            <h3 class="text-sm font-bold mb-3 text-center">{{ $ketuaDpc['full_name'] }}</h3>
                            <div class="w-full">
                                <div class="info-box">
                                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Jabatan</p>
                                    <p class="font-semibold text-white text-xs">{{ $ketuaDpc['position'] }}</p>
                                </div>
                                <div class="info-box">
                                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Wilayah</p>
                                    <p class="font-semibold text-white text-xs">{{ $ketuaDpc['region'] }}</p>
                                </div>
                                <div class="info-box">
                                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Bidang</p>
                                    <p class="font-semibold text-white text-xs">{{ $ketuaDpc['field'] }}</p>
                                </div>
                                <div class="info-box">
                                    <p class="text-blue-200 text-xs uppercase tracking-wider mb-0.5 font-medium">Anggota</p>
                                    <p class="font-semibold text-white text-xs">{{ $ketuaDpc['member_count'] }} orang</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- DPC Navigation Section -->
    <section>
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex justify-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Dewan Pimpinan Cabang (DPC) Kalimantan Timur</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">

                @forelse($dpcs as $dpc)
                    @php
                        $colors = ['blue', 'green', 'orange', 'red', 'cyan', 'purple', 'pink', 'indigo', 'teal', 'amber'];
                        $color = $colors[$loop->index % count($colors)];
                    @endphp
                    <a href="{{ route('organization.dpc', strtolower(str_replace(' ', '-', $dpc->city))) }}" class="p-4 bg-{{ $color }}-50 rounded-xl text-center hover:bg-{{ $color }}-100 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                        <i class="fas fa-building text-{{ $color }}-600 text-2xl mb-2"></i>
                        <p class="font-medium text-gray-700 text-sm">{{ $dpc->city }}</p>
                        <span class="text-xs text-gray-500">{{ $dpc->member_count ?? 0 }} anggota</span>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-8">
                        Belum ada data DPC
                    </div>
                @endforelse
            </div>
        </div>
    </section>
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

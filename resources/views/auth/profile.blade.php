@extends('layouts.app')

@section('title', 'Profil Saya - ' . get_setting('site_name', 'FISHERIES'))

@section('content')
<div class="bg-gray-50 min-h-screen py-6 md:py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <!-- Breadcrumbs -->
        <nav class="flex mb-8 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="/" class="hover:text-blue-600 transition">Beranda</a></li>
                <li class="flex items-center space-x-2">
                    <i class="fas fa-chevron-right text-[10px]"></i>
                    <span class="font-medium text-gray-900">Profil Saya</span>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Sidebar: Profile Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 overflow-hidden lg:sticky lg:top-24 mb-6 lg:mb-0">
                    <div class="p-6 md:p-8 text-center">
                        <div class="relative inline-block mb-4">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md mx-auto">
                            @else
                                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-3xl font-bold mx-auto border-4 border-white shadow-md">
                                    {{ substr($user->name ?: 'U', 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute -bottom-1 -right-1">
                                @if($type === 'admin')
                                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-sm">ADMIN</span>
                                @elseif($type === 'member')
                                    <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-sm">MEMBER</span>
                                @else
                                    <span class="bg-blue-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-sm">USER</span>
                                @endif
                            </div>
                        </div>

                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500 mb-6">{{ $user->email }}</p>

                        <div class="border-t border-gray-50 pt-6 space-y-3">
                            <a href="{{ route('orders.index') }}" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-50 text-gray-700 transition group">
                                <div class="w-9 h-9 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center group-hover:bg-orange-100">
                                    <i class="fas fa-shopping-bag text-sm"></i>
                                </div>
                                <span class="font-medium">Pesanan Saya</span>
                            </a>
                            
                            @if($type === 'member' || $type === 'admin')
                                <a href="{{ route('member.card') }}" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-50 text-gray-700 transition group">
                                    <div class="w-9 h-9 bg-green-50 text-green-600 rounded-lg flex items-center justify-center group-hover:bg-green-100">
                                        <i class="fas fa-id-card text-sm"></i>
                                    </div>
                                    <span class="font-medium">Kartu Anggota</span>
                                </a>
                            @endif

                            <form action="{{ route('logout') }}" method="POST" class="pt-2">
                                @csrf
                                <button type="submit" class="w-full flex items-center space-x-3 p-3 rounded-xl hover:bg-red-50 text-red-600 transition group">
                                    <div class="w-9 h-9 bg-red-50 text-red-500 rounded-lg flex items-center justify-center group-hover:bg-red-100">
                                        <i class="fas fa-sign-out-alt text-sm"></i>
                                    </div>
                                    <span class="font-medium">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content: Edit Profile -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">Pengaturan Profil</h3>
                        <p class="text-sm text-gray-500">Kelola informasi pribadi Anda di sini.</p>
                    </div>
                </div> <!-- Close header section -->

                <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 overflow-hidden mt-6">
                    <div class="p-6 md:p-8">
                        @if(session('success'))
                            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg animate-fade-in flex items-center">
                                <i class="fas fa-check-circle mr-3 text-lg"></i>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-sm">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST" class="flex flex-col gap-6">
                            @csrf
                            
                            <!-- Nama Lengkap -->
                            <div class="w-full">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <div class="relative w-full">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                        <i class="fas fa-user text-sm"></i>
                                    </span>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                        class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition"
                                        required placeholder="Masukkan nama lengkap">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                                <!-- Email (Read Only) -->
                                <div class="w-full">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                    <div class="relative w-full">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                            <i class="fas fa-envelope text-sm"></i>
                                        </span>
                                        <div class="block w-full pl-11 pr-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed text-sm sm:text-base truncate">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                    <p class="mt-1.5 text-[11px] text-gray-400">Email tidak dapat diubah untuk keamanan.</p>
                                </div>

                                <!-- WhatsApp / Phone -->
                                <div class="w-full">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon/WA</label>
                                    <div class="relative w-full">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                            <i class="fas fa-phone text-sm"></i>
                                        </span>
                                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                                            class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition"
                                            placeholder="Contoh: 08123456789">
                                    </div>
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="w-full">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Pengiriman</label>
                                <div class="relative w-full">
                                    <span class="absolute top-3 left-4 text-gray-400">
                                        <i class="fas fa-map-marker-alt text-sm"></i>
                                    </span>
                                    <textarea name="address" rows="4" 
                                        class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition"
                                        placeholder="Masukkan alamat lengkap Anda">{{ old('address', $displayAddress) }}</textarea>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-50 flex justify-center md:justify-end">
                                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-100 flex items-center justify-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Secondary Info: Join Date -->
                <div class="mt-8 bg-blue-600 rounded-2xl md:rounded-3xl p-6 md:p-8 text-white flex flex-col md:flex-row items-start md:items-center justify-between border-b-4 border-blue-800 shadow-xl overflow-hidden relative gap-4">
                    <div class="relative z-10">
                        <p class="text-blue-100 text-sm font-medium mb-1">Bergabung pada</p>
                        <h4 class="text-xl font-bold italic">
                            {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : ($user->join_date ? $user->join_date->translatedFormat('d F Y') : '-') }}
                        </h4>
                    </div>
                    <div class="relative z-10 bg-white/20 backdrop-blur-md px-4 py-2 rounded-xl text-sm font-bold uppercase tracking-widest border border-white/30 self-start md:self-auto">
                        FISHERIES
                    </div>
                    <!-- Decorative Icon -->
                    <i class="fas fa-fish absolute -right-6 -bottom-6 text-[120px] text-white/10 rotate-12"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.4s ease-out forwards;
}
</style>
@endsection

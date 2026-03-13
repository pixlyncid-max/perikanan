<!-- Mobile Sidebar Overlay -->
<div x-show="sidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden" 
     x-cloak
     @click="sidebarOpen = false">
</div>

<!-- Sidebar -->
<div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
     class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col h-full">

    
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-slate-900 flex-shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
            <img src="{{ asset('images/Logo_Symbol_White.png') }}" alt="Logo Symbol" class="h-10 w-10 object-contain">
            <img src="{{ asset('images/Logo_font_Putih.png') }}" alt="Logo Font" class="h-8 object-contain">
        </a>
    </div>
    
    <!-- Navigation -->
    <nav class="mt-5 px-2 space-y-1 overflow-y-auto flex-1 scrollbar-hide pb-4">

        
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-tachometer-alt mr-3 text-lg {{ request()->routeIs('admin.dashboard') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }}"></i>
            Dashboard
        </a>
        
        <!-- Divider -->
        <div class="border-t border-slate-700 my-4"></div>
        
        <!-- Manajemen User -->
        <a href="{{ route('admin.users.index') }}" 
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-users mr-3 text-lg {{ request()->routeIs('admin.users.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }}"></i>
            Manajemen User
        </a>
        
        <!-- Manajemen Anggota -->
        <a href="{{ route('admin.members.index') }}" 
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.members.*') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-id-card mr-3 text-lg {{ request()->routeIs('admin.members.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }}"></i>
            Manajemen Anggota
        </a>
        
        <!-- Divider -->
        <div class="border-t border-slate-700 my-4"></div>
        
        <!-- Manajemen Produk -->
        <a href="{{ route('admin.products.index') }}" 
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.products.*') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-box mr-3 text-lg {{ request()->routeIs('admin.products.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }}"></i>
            Manajemen Produk
        </a>
        
        <!-- Manajemen Kategori -->
        <a href="{{ route('admin.categories.index') }}" 
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.categories.*') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-tags mr-3 text-lg {{ request()->routeIs('admin.categories.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }}"></i>
            Manajemen Kategori
        </a>
        
        <!-- Manajemen Pesanan -->
        <a href="{{ route('admin.orders.index') }}" 
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.orders.*') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-shopping-cart mr-3 text-lg {{ request()->routeIs('admin.orders.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }}"></i>
            Manajemen Pesanan
        </a>
        
        <!-- Divider -->
        <div class="border-t border-slate-700 my-4"></div>
        
        <!-- Manajemen Artikel -->
        <a href="{{ route('admin.articles.index') }}" 
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.articles.*') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-newspaper mr-3 text-lg {{ request()->routeIs('admin.articles.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }}"></i>
            Manajemen Artikel
        </a>
        
        <!-- Manajemen Berita -->
        <a href="{{ route('admin.news.index') }}" 
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.news.*') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-bullhorn mr-3 text-lg {{ request()->routeIs('admin.news.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }}"></i>
            Manajemen Berita
        </a>
        
        <!-- Divider -->
        <div class="border-t border-slate-700 my-4"></div>
        
        <!-- Media -->
        <a href="{{ route('admin.media.index') }}" 
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.media.*') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-images mr-3 text-lg {{ request()->routeIs('admin.media.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }}"></i>
            Media & Upload
        </a>
        
        <!-- Pengaturan -->
        <a href="{{ route('admin.settings.index') }}" 
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings.*') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-cog mr-3 text-lg {{ request()->routeIs('admin.settings.*') ? 'text-cyan-400' : 'text-slate-400 group-hover:text-cyan-400' }}"></i>
            Pengaturan
        </a>
        
        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="w-full group flex items-center px-2 py-2 text-sm font-medium rounded-md text-slate-300 hover:bg-red-600 hover:text-white transition">
                <i class="fas fa-sign-out-alt mr-3 text-lg text-slate-400 group-hover:text-white"></i>
                Logout
            </button>
        </form>
    </nav>
</div>

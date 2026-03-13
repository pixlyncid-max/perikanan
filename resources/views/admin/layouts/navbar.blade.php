<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
        
        <!-- Left: Mobile Menu Button -->
        <div class="flex items-center">
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-cyan-500">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <!-- Breadcrumb -->
            <nav class="hidden sm:flex ml-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    @if(isset($breadcrumbs))
                        @foreach($breadcrumbs as $breadcrumb)
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
                                    @if($breadcrumb['url'])
                                        <a href="{{ $breadcrumb['url'] }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                                            {{ $breadcrumb['title'] }}
                                        </a>
                                    @else
                                        <span class="text-sm font-medium text-gray-900">{{ $breadcrumb['title'] }}</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ol>
            </nav>
        </div>
        
    <!-- Right: User Menu -->
        <div class="flex items-center space-x-4">
            
            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="p-2 rounded-full text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                </button>
                
                <!-- Notifications Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-cloak
                     class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    <div class="py-1">
                        <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
                            <strong>Notifikasi</strong>
                        </div>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <div class="flex items-start">
                                <i class="fas fa-shopping-cart text-cyan-500 mt-0.5 mr-2"></i>
                                <div>
                                    <p class="font-medium">Pesanan Baru</p>
                                    <p class="text-xs text-gray-500">Ada pesanan baru masuk</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <div class="flex items-start">
                                <i class="fas fa-user-plus text-green-500 mt-0.5 mr-2"></i>
                                <div>
                                    <p class="font-medium">Anggota Baru</p>
                                    <p class="text-xs text-gray-500">Anggota baru mendaftar</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- User Profile -->
            @php
                $adminName = \Illuminate\Support\Facades\Session::get('admin_data.name') 
                    ?? \Illuminate\Support\Facades\Session::get('user.name') 
                    ?? 'Admin';
                $adminEmail = \Illuminate\Support\Facades\Session::get('admin_data.email') 
                    ?? \Illuminate\Support\Facades\Session::get('user.email') 
                    ?? 'admin@fisheries.com';
            @endphp
            
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center space-x-3 p-2 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                    <img class="h-8 w-8 rounded-full object-cover" 
                         src="https://ui-avatars.com/api/?name={{ urlencode($adminName) }}&background=0D8ABC&color=fff" 
                         alt="{{ $adminName }}">
                    <span class="hidden md:block text-sm font-medium text-gray-700">{{ $adminName }}</span>

                    <i class="fas fa-chevron-down text-gray-400 text-xs hidden md:block"></i>
                </button>
                
                <!-- User Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-cloak
                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    <div class="py-1">
                        <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
                            <p class="font-medium">{{ $adminName }}</p>
                            <p class="text-xs text-gray-500">{{ $adminEmail }}</p>
                        </div>

                        <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i> Pengaturan
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>

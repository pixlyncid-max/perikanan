<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - FISHERIES Admin</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Fix height issues - ensure html and body don't overflow */
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        
        #app {
            width: 100%;
            height: 100%;
        }
        
        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
        
        /* Main content container - prevent extra spacing */
        main {
            display: flex;
            flex-direction: column;
        }
        
        main > div:first-child {
            flex-grow: 0;
            flex-shrink: 0;
        }
    </style>

    
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">
    
    <div id="app" class="h-screen flex" x-data="{ sidebarOpen: false }" x-cloak>
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')
        
        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-full min-w-0">
            <!-- Navbar -->
            @include('admin.layouts.navbar')
            
            <!-- Main Content Area - Fixed height container -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert" id="flash-success">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert" id="flash-error">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert" id="flash-errors">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </main>
            
            <!-- Footer - Fixed at bottom -->
            <footer class="bg-white border-t border-gray-200 p-4 flex-shrink-0">
                <div class="text-center text-sm text-gray-600">
                    &copy; {{ date('Y') }} FISHERIES Admin Panel. All rights reserved.
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Alpine.js Fallback -->
    <noscript>
        <style>
            [x-cloak] { display: block !important; }
            #app { display: block !important; }
        </style>
    </noscript>

    
    <!-- Scripts -->
    <script>
        // SweetAlert helper function
        function confirmDelete(formId, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            if (typeof Swal === 'undefined') {
                if (confirm(message)) {
                    document.getElementById(formId).submit();
                }
                return;
            }
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
        
        // Auto-hide flash messages
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease-in-out';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                });
            }, 5000);
        });
        
        // Alpine.js error handling
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js initialized successfully');
        });
        
        document.addEventListener('alpine:error', (e) => {
            console.error('Alpine.js error:', e);
        });
    </script>

    
    @stack('scripts')
</body>
</html>

@extends('layouts.app')

@section('title', 'Login - FISHERIES')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center w-full flex flex-col items-center">
            <div class="flex items-center justify-center mb-4">
                <img src="{{ asset('images/Logo_Fisheries1.png') }}" alt="Fisheries Logo" class="h-20 object-contain">
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Selamat Datang</h2>
            <p class="mt-2 text-gray-600">Login ke akun anggota FISHERIES</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <form id="login-form" class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div>


                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" required class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="email@example.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400" id="login-lock-icon"></i>
                        </div>
                        <input id="password" name="password" type="password" required class="password-input appearance-none block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password', 'toggle-icon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 transition">
                            <i id="toggle-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <!-- Password Validation Checklist -->
                    <div id="login-password-checklist" class="mt-2 space-y-1.5 hidden" style="transition: all 0.3s ease;">
                        <div class="flex items-center text-sm" id="login-check-length">
                            <i class="fas fa-circle text-gray-300 mr-2 text-[10px] transition-all duration-300" id="login-icon-length"></i>
                            <span class="text-gray-500 transition-colors duration-300" id="login-text-length">Minimal 8 karakter</span>
                        </div>
                        <div class="flex items-center text-sm" id="login-check-uppercase">
                            <i class="fas fa-circle text-gray-300 mr-2 text-[10px] transition-all duration-300" id="login-icon-uppercase"></i>
                            <span class="text-gray-500 transition-colors duration-300" id="login-text-uppercase">Minimal 1 huruf besar (A-Z)</span>
                        </div>
                        <div class="flex items-center text-sm" id="login-check-lowercase">
                            <i class="fas fa-circle text-gray-300 mr-2 text-[10px] transition-all duration-300" id="login-icon-lowercase"></i>
                            <span class="text-gray-500 transition-colors duration-300" id="login-text-lowercase">Minimal 1 huruf kecil (a-z)</span>
                        </div>
                        <div class="flex items-center text-sm" id="login-check-number">
                            <i class="fas fa-circle text-gray-300 mr-2 text-[10px] transition-all duration-300" id="login-icon-number"></i>
                            <span class="text-gray-500 transition-colors duration-300" id="login-text-number">Minimal 1 angka (0-9)</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                    </div>
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">Lupa password?</a>
                    </div>
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    Login
                </button>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Atau login dengan</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <a href="{{ route('auth.google') }}" class="w-full inline-flex justify-center items-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-400 transition duration-150">
                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Google
                    </a>
                    <a href="{{ route('auth.facebook') }}" class="w-full inline-flex justify-center items-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-400 transition duration-150">
                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="#1877F2"/>
                        </svg>
                        Facebook
                    </a>
                </div>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-3" style="pointer-events: none;"></div>

<style>
    .password-error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2) !important;
    }
    .password-error:focus {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.3) !important;
    }
    .password-success {
        border-color: #22c55e !important;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2) !important;
    }
    .password-success:focus {
        border-color: #22c55e !important;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.3) !important;
    }
    .checklist-pass .fa-circle { display: none; }
    .checklist-pass .fa-check-circle { display: inline-block !important; }
    .checklist-fail .fa-circle { display: inline-block; }
    .checklist-fail .fa-check-circle { display: none !important; }

    /* Toast Notification Styles */
    .toast-notification {
        pointer-events: all;
        min-width: 340px;
        max-width: 420px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        animation: toastSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        transform: translateX(120%);
    }
    .toast-notification.toast-exit {
        animation: toastSlideOut 0.35s cubic-bezier(0.4, 0, 1, 1) forwards;
    }
    .toast-body {
        display: flex;
        align-items: flex-start;
        padding: 16px;
        gap: 12px;
    }
    .toast-icon {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    .toast-icon.error {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #dc2626;
    }
    .toast-content {
        flex: 1;
        min-width: 0;
    }
    .toast-title {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }
    .toast-message {
        font-size: 13px;
        color: #6b7280;
        line-height: 1.4;
    }
    .toast-message ul {
        margin: 4px 0 0 0;
        padding-left: 0;
        list-style: none;
    }
    .toast-message ul li {
        position: relative;
        padding-left: 16px;
        margin-bottom: 2px;
    }
    .toast-message ul li::before {
        content: '\f00d';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        left: 0;
        color: #ef4444;
        font-size: 10px;
        top: 2px;
    }
    .toast-close {
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: #9ca3af;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        font-size: 14px;
    }
    .toast-close:hover {
        background: #f3f4f6;
        color: #4b5563;
    }
    .toast-progress {
        height: 3px;
        background: #f3f4f6;
        overflow: hidden;
    }
    .toast-progress-bar {
        height: 100%;
        border-radius: 0 3px 3px 0;
        animation: toastProgress 5s linear forwards;
    }
    .toast-progress-bar.error {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }

    @keyframes toastSlideIn {
        from { transform: translateX(120%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes toastSlideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(120%); opacity: 0; }
    }
    @keyframes toastProgress {
        from { width: 100%; }
        to { width: 0%; }
    }
</style>

<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Real-time password validation for login page
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('login-form');
        const passwordInput = document.getElementById('password');
        const checklist = document.getElementById('login-password-checklist');
        const lockIcon = document.getElementById('login-lock-icon');

        const rules = [
            { id: 'length', test: val => val.length >= 8 },
            { id: 'uppercase', test: val => /[A-Z]/.test(val) },
            { id: 'lowercase', test: val => /[a-z]/.test(val) },
            { id: 'number', test: val => /[0-9]/.test(val) },
        ];

        // Show checklist on focus
        passwordInput.addEventListener('focus', function() {
            if (this.value.length > 0 || true) {
                checklist.classList.remove('hidden');
            }
        });

        passwordInput.addEventListener('input', function() {
            const val = this.value;

            // Show checklist when typing
            if (val.length > 0) {
                checklist.classList.remove('hidden');
            } else {
                checklist.classList.add('hidden');
                // Reset state
                passwordInput.classList.remove('password-error', 'password-success');
                lockIcon.classList.remove('text-red-500', 'text-green-500');
                lockIcon.classList.add('text-gray-400');
                return;
            }

            let allPassed = true;

            rules.forEach(rule => {
                const icon = document.getElementById('login-icon-' + rule.id);
                const text = document.getElementById('login-text-' + rule.id);
                const passed = rule.test(val);

                if (passed) {
                    icon.classList.remove('fa-circle', 'text-gray-300', 'text-red-400');
                    icon.classList.add('fa-check-circle', 'text-green-500');
                    text.classList.remove('text-gray-500', 'text-red-500');
                    text.classList.add('text-green-600');
                } else {
                    allPassed = false;
                    icon.classList.remove('fa-check-circle', 'text-green-500');
                    icon.classList.add('fa-circle', 'text-red-400');
                    text.classList.remove('text-green-600', 'text-gray-500');
                    text.classList.add('text-red-500');
                }
            });

            // Update border and lock icon color
            if (allPassed) {
                passwordInput.classList.remove('password-error');
                passwordInput.classList.add('password-success');
                lockIcon.classList.remove('text-gray-400', 'text-red-500');
                lockIcon.classList.add('text-green-500');
            } else {
                passwordInput.classList.remove('password-success');
                passwordInput.classList.add('password-error');
                lockIcon.classList.remove('text-gray-400', 'text-green-500');
                lockIcon.classList.add('text-red-500');
            }
        });

        // Hide checklist when field is empty and loses focus
        passwordInput.addEventListener('blur', function() {
            if (this.value.length === 0) {
                checklist.classList.add('hidden');
                passwordInput.classList.remove('password-error', 'password-success');
                lockIcon.classList.remove('text-red-500', 'text-green-500');
                lockIcon.classList.add('text-gray-400');
            }
        });

        // Prevent form submission if password doesn't meet requirements
        loginForm.addEventListener('submit', function(e) {
            const val = passwordInput.value;
            let errors = [];

            if (val.length < 8) errors.push('minimal 8 karakter');
            if (!/[A-Z]/.test(val)) errors.push('minimal 1 huruf besar');
            if (!/[a-z]/.test(val)) errors.push('minimal 1 huruf kecil');
            if (!/[0-9]/.test(val)) errors.push('minimal 1 angka');

            if (errors.length > 0) {
                e.preventDefault();
                // Trigger input event to show checklist
                passwordInput.dispatchEvent(new Event('input'));
                passwordInput.focus();
                
                let listHtml = '<ul>' + errors.map(err => '<li>' + err + '</li>').join('') + '</ul>';
                showToast('Login Gagal', 'Password Anda belum memenuhi standar keamanan:' + listHtml, 'error');
                return false;
            }
        });
    });

    // Custom Toast Notification Function
    function showToast(title, message, type = 'error') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div class="toast-body">
                <div class="toast-icon ${type}">
                    <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : 'fa-exclamation-triangle'}"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="dismissToast(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="toast-progress">
                <div class="toast-progress-bar ${type}"></div>
            </div>
        `;
        container.appendChild(toast);

        // Auto dismiss after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.classList.add('toast-exit');
                setTimeout(() => toast.remove(), 350);
            }
        }, 5000);
    }

    function dismissToast(btn) {
        const toast = btn.closest('.toast-notification');
        toast.classList.add('toast-exit');
        setTimeout(() => toast.remove(), 350);
    }
</script>
@endsection

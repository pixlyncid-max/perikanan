@extends('layouts.app')

@section('title', 'Daftar - FISHERIES')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="text-center w-full flex flex-col items-center mb-8">
            <div class="flex items-center justify-center mb-4">
                <img src="{{ asset('images/Logo_Fisheries1.png') }}" alt="Fisheries Logo" class="h-20 object-contain">
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Daftar Akun</h2>
            <p class="mt-2 text-gray-600">Bergabunglah dengan komunitas perikanan terbesar di Kaltim</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <form id="register-form" class="space-y-6" action="/register" method="POST">
                @csrf

                @if (session('error'))
                    <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-4 rounded-lg flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Pilihan Jenis Pendaftaran -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Jenis Pendaftaran</label>
                    <div class="space-y-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="registration_type" value="user" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Daftar sebagai User Biasa</span>
                                <span class="block text-xs text-gray-500">Akses terbatas, hanya bisa memesan produk Pakan Hidup</span>
                            </div>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="registration_type" value="member" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Daftar sebagai Anggota</span>
                                <span class="block text-xs text-gray-500">Akses penuh ke semua produk dengan harga khusus anggota</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="name" name="name" type="text" required class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Nama lengkap">
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input id="phone" name="phone" type="tel" required class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" required class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="email@example.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400" id="reg-lock-icon"></i>
                            </div>
                            <input id="password" name="password" type="password" required class="appearance-none block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" placeholder="••••••••">
                            <button type="button" onclick="togglePassword('password', 'toggle-icon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 transition">
                                <i id="toggle-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <!-- Password Validation Checklist -->
                        <div id="reg-password-checklist" class="mt-2 space-y-1 hidden" style="transition: all 0.3s ease;">
                            <div class="flex items-center text-xs" id="reg-check-length">
                                <i class="fas fa-circle text-gray-300 mr-2 text-[6px] transition-all duration-300" id="reg-icon-length"></i>
                                <span class="text-gray-500 transition-colors duration-300" id="reg-text-length">Minimal 8 karakter</span>
                            </div>
                            <div class="flex items-center text-xs" id="reg-check-uppercase">
                                <i class="fas fa-circle text-gray-300 mr-2 text-[6px] transition-all duration-300" id="reg-icon-uppercase"></i>
                                <span class="text-gray-500 transition-colors duration-300" id="reg-text-uppercase">Minimal 1 huruf besar (A-Z)</span>
                            </div>
                            <div class="flex items-center text-xs" id="reg-check-lowercase">
                                <i class="fas fa-circle text-gray-300 mr-2 text-[6px] transition-all duration-300" id="reg-icon-lowercase"></i>
                                <span class="text-gray-500 transition-colors duration-300" id="reg-text-lowercase">Minimal 1 huruf kecil (a-z)</span>
                            </div>
                            <div class="flex items-center text-xs" id="reg-check-number">
                                <i class="fas fa-circle text-gray-300 mr-2 text-[6px] transition-all duration-300" id="reg-icon-number"></i>
                                <span class="text-gray-500 transition-colors duration-300" id="reg-text-number">Minimal 1 angka (0-9)</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400" id="reg-lock-icon-conf"></i>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" placeholder="••••••••">
                            <button type="button" onclick="togglePassword('password_confirmation', 'toggle-icon-conf')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 transition">
                                <i id="toggle-icon-conf" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <!-- Confirmation match indicator -->
                        <div id="reg-confirm-message" class="mt-2 hidden" style="transition: all 0.3s ease;">
                            <div class="flex items-center text-xs">
                                <i class="fas fa-circle text-gray-300 mr-2 text-[6px] transition-all duration-300" id="reg-icon-match"></i>
                                <span class="text-gray-500 transition-colors duration-300" id="reg-text-match">Password harus sama</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fields khusus untuk Member (ditampilkan/hidden via JS) -->
                <div id="member-fields" class="space-y-6 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="dpc" class="block text-sm font-medium text-gray-700">DPC Wilayah</label>
                            <select id="dpc" name="dpc" class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih DPC</option>
                                <option value="samarinda">Samarinda</option>
                                <option value="bontang">Bontang</option>
                                <option value="balikpapan">Balikpapan</option>
                                <option value="sangatta">Sangatta</option>
                                <option value="berau">Berau</option>
                                <option value="kukar">Kutai Kartanegara</option>
                                <option value="paser">Paser</option>
                                <option value="penajam">Penajam Paser Utara</option>
                                <option value="kubar">Kutai Barat</option>
                                <option value="kutim">Kutai Timur</option>
                                <option value="mahulu">Mahakam Ulu</option>
                            </select>
                        </div>
                        <div>
                            <label for="occupation" class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                            <select id="occupation" name="occupation" class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Pekerjaan</option>
                                <option value="nelayan">Nelayan</option>
                                <option value="pembudidaya">Pembudidaya</option>
                                <option value="pengolah">Pengolah Ikan</option>
                                <option value="pedagang">Pedagang</option>
                                <option value="penyuluh">Penyuluh Perikanan</option>
                                <option value="peneliti">Peneliti</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea id="address" name="address" rows="3" required class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Alamat lengkap"></textarea>
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        Saya menyetujui <a href="#" class="text-blue-600 hover:text-blue-500">syarat dan ketentuan</a> serta <a href="#" class="text-blue-600 hover:text-blue-500">kebijakan privasi</a> FISHERIES
                    </label>
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="/login" class="font-medium text-blue-600 hover:text-blue-500">Login di sini</a>
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
    .toast-icon.warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
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
    .toast-progress-bar.warning {
        background: linear-gradient(90deg, #f59e0b, #d97706);
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
    // Toggle member fields based on registration type
    document.querySelectorAll('input[name="registration_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const memberFields = document.getElementById('member-fields');
            const dpcSelect = document.getElementById('dpc');
            const occupationSelect = document.getElementById('occupation');
            
            if (this.value === 'member') {
                memberFields.classList.remove('hidden');
                dpcSelect.setAttribute('required', 'required');
                occupationSelect.setAttribute('required', 'required');
            } else {
                memberFields.classList.add('hidden');
                dpcSelect.removeAttribute('required');
                occupationSelect.removeAttribute('required');
            }
        });
    });

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

    // Real-time password validation for register page
    document.addEventListener('DOMContentLoaded', function() {
        const registerForm = document.getElementById('register-form');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const checklist = document.getElementById('reg-password-checklist');
        const lockIcon = document.getElementById('reg-lock-icon');
        const lockIconConf = document.getElementById('reg-lock-icon-conf');
        const confirmMessage = document.getElementById('reg-confirm-message');

        const rules = [
            { id: 'length', test: val => val.length >= 8 },
            { id: 'uppercase', test: val => /[A-Z]/.test(val) },
            { id: 'lowercase', test: val => /[a-z]/.test(val) },
            { id: 'number', test: val => /[0-9]/.test(val) },
        ];

        // Show checklist on focus
        passwordInput.addEventListener('focus', function() {
            checklist.classList.remove('hidden');
        });

        // Validate password on input
        passwordInput.addEventListener('input', function() {
            const val = this.value;

            if (val.length > 0) {
                checklist.classList.remove('hidden');
            } else {
                checklist.classList.add('hidden');
                passwordInput.classList.remove('password-error', 'password-success');
                lockIcon.classList.remove('text-red-500', 'text-green-500');
                lockIcon.classList.add('text-gray-400');
                // Also re-validate confirmation
                validateConfirmation();
                return;
            }

            let allPassed = true;

            rules.forEach(rule => {
                const icon = document.getElementById('reg-icon-' + rule.id);
                const text = document.getElementById('reg-text-' + rule.id);
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

            // Also re-validate confirmation if it has value
            if (confirmInput.value.length > 0) {
                validateConfirmation();
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

        // Validate confirmation password
        function validateConfirmation() {
            const passVal = passwordInput.value;
            const confVal = confirmInput.value;
            const matchIcon = document.getElementById('reg-icon-match');
            const matchText = document.getElementById('reg-text-match');

            if (confVal.length === 0) {
                confirmMessage.classList.add('hidden');
                confirmInput.classList.remove('password-error', 'password-success');
                lockIconConf.classList.remove('text-red-500', 'text-green-500');
                lockIconConf.classList.add('text-gray-400');
                return;
            }

            confirmMessage.classList.remove('hidden');

            if (passVal === confVal && passVal.length > 0) {
                matchIcon.classList.remove('fa-circle', 'text-gray-300', 'text-red-400');
                matchIcon.classList.add('fa-check-circle', 'text-green-500');
                matchText.classList.remove('text-gray-500', 'text-red-500');
                matchText.classList.add('text-green-600');
                matchText.textContent = 'Password cocok';
                confirmInput.classList.remove('password-error');
                confirmInput.classList.add('password-success');
                lockIconConf.classList.remove('text-gray-400', 'text-red-500');
                lockIconConf.classList.add('text-green-500');
            } else {
                matchIcon.classList.remove('fa-check-circle', 'text-green-500');
                matchIcon.classList.add('fa-circle', 'text-red-400');
                matchText.classList.remove('text-green-600', 'text-gray-500');
                matchText.classList.add('text-red-500');
                matchText.textContent = 'Password tidak cocok';
                confirmInput.classList.remove('password-success');
                confirmInput.classList.add('password-error');
                lockIconConf.classList.remove('text-gray-400', 'text-green-500');
                lockIconConf.classList.add('text-red-500');
            }
        }

        confirmInput.addEventListener('input', validateConfirmation);

        confirmInput.addEventListener('focus', function() {
            if (this.value.length > 0) {
                confirmMessage.classList.remove('hidden');
            }
        });

        confirmInput.addEventListener('blur', function() {
            if (this.value.length === 0) {
                confirmMessage.classList.add('hidden');
                confirmInput.classList.remove('password-error', 'password-success');
                lockIconConf.classList.remove('text-red-500', 'text-green-500');
                lockIconConf.classList.add('text-gray-400');
            }
        });

        // Prevent form submission if password doesn't meet requirements
        registerForm.addEventListener('submit', function(e) {
            const val = passwordInput.value;
            const confVal = confirmInput.value;
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
                showToast('Password Belum Memenuhi Syarat', 'Password harus memiliki:' + listHtml, 'error');
                return false;
            }

            if (val !== confVal) {
                e.preventDefault();
                confirmInput.focus();
                showToast('Konfirmasi Tidak Cocok', 'Password dan konfirmasi password harus sama. Silakan periksa kembali.', 'warning');
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

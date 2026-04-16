@extends('layouts.app')

@section('title', 'Buat Password Baru - FISHERIES')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center w-full flex flex-col items-center">
            <div class="flex items-center justify-center mb-4">
                <img src="{{ asset('images/Logo_Fisheries1.png') }}" alt="Fisheries Logo" class="h-20 object-contain">
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Buat Password Baru</h2>
            <p class="mt-2 text-gray-600">Masukkan password baru untuk akun Anda.</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            @if (session('error'))
                <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-4 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form class="space-y-6" action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required autofocus class="appearance-none block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror" placeholder="Minimal 8 karakter">
                        <button type="button" onclick="togglePassword('password', 'toggle-icon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 transition">
                            <i id="toggle-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ketik ulang password baru">
                        <button type="button" onclick="togglePassword('password_confirmation', 'toggle-icon-conf')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 transition">
                            <i id="toggle-icon-conf" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>

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
</script>
@endsection

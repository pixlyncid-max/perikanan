@extends('layouts.app')

@section('title', 'Lupa Password - FISHERIES')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center w-full flex flex-col items-center">
            <div class="flex items-center justify-center mb-4">
                <img src="{{ asset('images/Logo_Fisheries1.png') }}" alt="Fisheries Logo" class="h-20 object-contain">
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Lupa Password?</h2>
            <p class="mt-2 text-gray-600">Masukkan email Anda untuk mereset password.</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            @if (session('status') || session('success'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg">
                    {{ session('status') ?? session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-4 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" placeholder="email@example.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">Kembali ke halaman Login</a>
                    </div>
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    Kirim Tautan Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

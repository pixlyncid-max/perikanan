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
            <form class="space-y-6" action="/login" method="POST">
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
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Lupa password?</a>
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
                    <button class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition">
                        <i class="fab fa-google text-red-500 mr-2"></i> Google
                    </button>
                    <button class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition">
                        <i class="fab fa-facebook text-blue-600 mr-2"></i> Facebook
                    </button>
                </div>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun? 
                    <a href="/register" class="font-medium text-blue-600 hover:text-blue-500">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

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
            <form class="space-y-6" action="/register" method="POST">
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
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••">
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••">
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
</script>
@endsection

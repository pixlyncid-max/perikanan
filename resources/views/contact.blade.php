@extends('layouts.app')

@section('title', 'Kontak - FISHERIES')

@section('content')


<!-- Contact Content -->
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contact Info -->
        <div class="lg:col-span-1 space-y-6 reveal-left">
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4 icon-circle">
                    <i class="fas fa-map-marker-alt text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Alamat Kantor Pusat</h3>
                <p class="text-gray-600">
                    {!! nl2br(e(get_setting('site_address', 'Jl. Delima Dalam Blok E, Sidodadi<br> 
                    Kec. Samarinda Ulu<br>
                    Kota Samarinda<br>
                    Kalimantan Timur 75243'))) !!}
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4 icon-circle">
                    <i class="fas fa-phone text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Telepon</h3>
                <p class="text-gray-600">
                    @if(get_setting('site_phone'))
                        Kantor: {{ get_setting('site_phone') }}<br>
                    @endif
                    @if(get_setting('whatsapp_number'))
                        WhatsApp: {{ get_setting('whatsapp_number') }}<br>
                    @endif
                    Hotline: 0800-1234-5678
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4 icon-circle">
                    <i class="fas fa-envelope text-orange-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Email</h3>
                <p class="text-gray-600">
                    @if(get_setting('site_email'))
                        Info: {{ get_setting('site_email') }}<br>
                        Support: {{ get_setting('site_email') }}<br>
                        Partnership: {{ get_setting('site_email') }}
                    @else
                        Info: fisheriesborneo@gmail.com<br>
                        Support: fisheriesborneo@gmail.com<br>
                        Partnership: fisheriesborneo@gmail.com
                    @endif
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4 icon-circle">
                    <i class="fas fa-clock text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Jam Operasional</h3>
                <p class="text-gray-600">
                    @php
                        $hours = explode(';', get_setting('contact_business_hours', 'Senin - Jumat: 08.00 - 17.00;Sabtu: 08.00 - 12.00;Minggu: Tutup'));
                        foreach($hours as $hour) {
                            if(trim($hour)) {
                                echo trim($hour) . '<br>';
                            }
                        }
                    @endphp
                </p>
            </div>

            <!-- Social Media -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Media Sosial</h3>
                <div class="flex space-x-3">
                    @if(get_setting('instagram_url'))
                        <a href="{{ get_setting('instagram_url') }}" target="_blank" class="w-10 h-10 bg-pink-600 text-white rounded-lg flex items-center justify-center hover:bg-pink-700 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    @if(get_setting('facebook_url'))
                        <a href="{{ get_setting('facebook_url') }}" target="_blank" class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if(get_setting('tiktok_url'))
                        <a href="{{ get_setting('tiktok_url') }}" target="_blank" class="w-10 h-10 bg-black text-white rounded-lg flex items-center justify-center hover:bg-gray-900 transition">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    @endif
                    @if(get_setting('youtube_url'))
                        <a href="{{ get_setting('youtube_url') }}" target="_blank" class="w-10 h-10 bg-red-600 text-white rounded-lg flex items-center justify-center hover:bg-red-700 transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                    @endif
                    @if(get_setting('whatsapp_number'))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', get_setting('whatsapp_number')) }}" target="_blank" class="w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center hover:bg-green-600 transition">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="lg:col-span-2 reveal-right">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Kirim Pesan</h2>
                <form action="/contact" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                class="w-full px-4 py-3 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan nama Anda">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                class="w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="email@example.com">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Telepon</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" 
                                class="w-full px-4 py-3 border @error('phone') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="08123456789">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Subjek</label>
                            <select name="subject" required 
                                class="w-full px-4 py-3 border @error('subject') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih subjek</option>
                                <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>Pertanyaan Umum</option>
                                <option value="membership" {{ old('subject') == 'membership' ? 'selected' : '' }}>Keanggotaan</option>
                                <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Kemitraan</option>
                                <option value="product" {{ old('subject') == 'product' ? 'selected' : '' }}>Produk</option>
                                <option value="complaint" {{ old('subject') == 'complaint' ? 'selected' : '' }}>Keluhan</option>
                                <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('subject')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Pesan</label>
                        <textarea name="message" required rows="5" 
                            class="w-full px-4 py-3 border @error('message') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Tulis pesan Anda di sini...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                        class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition flex items-center justify-center btn-animate ripple">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Pesan
                    </button>
                </form>
            </div>

            <!-- Map -->
            <div class="bg-white rounded-xl shadow-lg p-4 mt-8">
                <div class="h-80 rounded-lg overflow-hidden">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.678862248799!2d117.13760317426723!3d-0.4790536995162395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2b3c630a13bc0b97%3A0xefec007c66cf31a7!2sKONSULTAN%20BORNEO!5e0!3m2!1sid!2sid!4v1772674542404!5m2!1sid!2sid" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="mt-4 text-center">
                    <a href="https://maps.app.goo.gl/p44CxRbefcehiZZg9"
                       target="_blank" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-external-link-alt mr-2"></i> Buka di Google Maps
                    </a>
                    <p class="text-sm text-gray-500 mt-2">Jl. Delima Dalam Blok E, Sidodadi, Kec. Samarinda Ulu, Kota Samarinda, Kalimantan Timur 75243</p>
                </div>
            </div>

        </div>
    </div>

    <!-- DPC Contacts -->
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-8 reveal">Kontak DPC</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6 reveal stagger-1 card-hover">
                <h3 class="font-bold text-gray-800 mb-2">DPC Samarinda</h3>
                <p class="text-gray-600 text-sm mb-3">Jl. Pahlawan No. 45, Samarinda</p>
                <p class="text-gray-600 text-sm"><i class="fas fa-phone mr-2"></i>(0541) 765432</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 reveal stagger-2 card-hover">
                <h3 class="font-bold text-gray-800 mb-2">DPC Bontang</h3>
                <p class="text-gray-600 text-sm mb-3">Jl. MT. Haryono No. 12, Bontang</p>
                <p class="text-gray-600 text-sm"><i class="fas fa-phone mr-2"></i>(0548) 234567</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 reveal stagger-3 card-hover">
                <h3 class="font-bold text-gray-800 mb-2">DPC Balikpapan</h3>
                <p class="text-gray-600 text-sm mb-3">Jl. Sudirman No. 78, Balikpapan</p>
                <p class="text-gray-600 text-sm"><i class="fas fa-phone mr-2"></i>(0542) 876543</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 reveal stagger-1 card-hover">
                <h3 class="font-bold text-gray-800 mb-2">DPC Kutai Kartanegara</h3>
                <p class="text-gray-600 text-sm mb-3">Jl. Ahmad Yani No. 23, Tenggarong</p>
                <p class="text-gray-600 text-sm"><i class="fas fa-phone mr-2"></i>(0541) 345678</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 reveal stagger-2 card-hover">
                <h3 class="font-bold text-gray-800 mb-2">DPC Kutai Timur</h3>
                <p class="text-gray-600 text-sm mb-3">Jl. Basuki Rahmat No. 56, Sangatta</p>
                <p class="text-gray-600 text-sm"><i class="fas fa-phone mr-2"></i>(0553) 456789</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 reveal stagger-3 card-hover">
                <h3 class="font-bold text-gray-800 mb-2">DPC Berau</h3>
                <p class="text-gray-600 text-sm mb-3">Jl. Diponegoro No. 34, Tanjung Redeb</p>
                <p class="text-gray-600 text-sm"><i class="fas fa-phone mr-2"></i>(0554) 567890</p>
            </div>
        </div>
    </div>
</div>
@endsection

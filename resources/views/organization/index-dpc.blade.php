@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-[#002A49] mb-4">Dewan Pengurus Cabang (DPC)</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Pilih provinsi, kemudian pilih kota/kabupaten untuk melihat struktur kepengurusan DPC FISHERIES di daerah tersebut.</p>
        </div>

        <div class="mb-8 flex flex-col sm:flex-row gap-4 max-w-4xl mx-auto">
            <div class="flex-1">
                <select id="provinceSelect" class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#019ADA] focus:border-transparent shadow-sm">
                    <option value="">Semua Provinsi</option>
                    @foreach($regions as $province => $cities)
                        <option value="{{ strtolower($province) }}">{{ $province }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <select id="citySelect" class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#019ADA] focus:border-transparent shadow-sm" disabled>
                    <option value="">Semua Kota/Kabupaten</option>
                </select>
            </div>
        </div>

        <div class="space-y-6" id="dpcContainer">
            @foreach($regions as $province => $cities)
                @php
                    $provSlug = \Illuminate\Support\Str::slug($province);
                @endphp
                <div class="province-block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" data-province="{{ strtolower($province) }}">
                    <!-- Province Header -->
                    <div class="bg-[#002A49] px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3 text-white">
                            <i class="fas fa-map text-blue-400 text-xl"></i>
                            <h2 class="text-lg font-bold">{{ $province }}</h2>
                        </div>
                        <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full">{{ count($cities) }} Kota/Kab</span>
                    </div>
                    
                    <!-- Cities Grid -->
                    <div class="p-6">
                        @if(count($cities) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($cities as $city)
                                    @php
                                        $citySlug = \Illuminate\Support\Str::slug($city);
                                    @endphp
                                    <a href="{{ route('organization.dpc.show', ['province' => $provSlug, 'city' => $citySlug]) }}" class="city-card block px-4 py-3 bg-gray-50 hover:bg-blue-50 rounded-lg border border-gray-100 hover:border-blue-200 transition-colors group" data-city="{{ strtolower($city) }}">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-building text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                                            <div>
                                                <div class="font-medium text-gray-900 group-hover:text-blue-700">{{ $city }}</div>
                                                <div class="text-[10px] text-gray-500 uppercase tracking-wider mt-0.5">Struktur Inti Cabang</div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm italic">Belum ada data cabang untuk provinsi ini.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div id="noResults" class="hidden text-center py-12">
            <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Data cabang tidak ditemukan.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('provinceSelect');
        const citySelect = document.getElementById('citySelect');
        const provinceBlocks = document.querySelectorAll('.province-block');
        const noResults = document.getElementById('noResults');

        // Data struktur wilayah untuk mengisi opsi kota
        const regionData = {
            @foreach($regions as $province => $cities)
                "{{ strtolower($province) }}": [
                    @foreach($cities as $city)
                        { name: "{{ $city }}", value: "{{ strtolower($city) }}" },
                    @endforeach
                ],
            @endforeach
        };

        function filterDpc() {
            const selectedProvince = provinceSelect.value.toLowerCase();
            const selectedCity = citySelect.value.toLowerCase();
            let visibleProvinces = 0;

            provinceBlocks.forEach(block => {
                const blockProvince = block.getAttribute('data-province');
                const cityCards = block.querySelectorAll('.city-card');
                let visibleCitiesInBlock = 0;
                
                // Jika provinsi dipilih dan tidak cocok dengan blok ini, sembunyikan blok
                if (selectedProvince !== '' && blockProvince !== selectedProvince) {
                    block.style.display = 'none';
                    return;
                }

                // Filter kota di dalam blok provinsi ini
                cityCards.forEach(card => {
                    const cardCity = card.getAttribute('data-city');
                    if (selectedCity !== '' && cardCity !== selectedCity) {
                        card.style.display = 'none';
                    } else {
                        card.style.display = 'block';
                        visibleCitiesInBlock++;
                    }
                });

                // Tampilkan blok provinsi jika ada kota yang cocok (atau jika tidak memfilter berdasarkan kota)
                if (visibleCitiesInBlock > 0 || cityCards.length === 0) {
                    block.style.display = 'block';
                    visibleProvinces++;
                } else {
                    block.style.display = 'none';
                }
            });

            if (visibleProvinces === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }

        provinceSelect.addEventListener('change', function() {
            const selectedProvince = this.value;
            
            // Reset dan populate city dropdown
            citySelect.innerHTML = '<option value="">Semua Kota/Kabupaten</option>';
            
            if (selectedProvince !== '') {
                citySelect.disabled = false;
                const cities = regionData[selectedProvince] || [];
                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.value;
                    option.textContent = city.name;
                    citySelect.appendChild(option);
                });
            } else {
                citySelect.disabled = true;
            }
            
            filterDpc();
        });

        citySelect.addEventListener('change', filterDpc);
    });
</script>
@endpush
@endsection

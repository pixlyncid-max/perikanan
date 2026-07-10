@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-[#002A49] mb-4">Dewan Pengurus Wilayah (DPW)</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Pilih provinsi untuk melihat struktur kepengurusan DPW FISHERIES di wilayah tersebut.</p>
        </div>

        <div class="mb-8 flex justify-center max-w-3xl mx-auto">
            <div class="w-full sm:w-96">
                <select id="provinceSelect" class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#019ADA] focus:border-transparent shadow-sm">
                    <option value="">Pilih Provinsi...</option>
                    @foreach($provinces as $province)
                        <option value="{{ strtolower($province) }}">{{ $province }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="provinceGrid">
            @foreach($provinces as $province)
                @php
                    $slug = \Illuminate\Support\Str::slug($province);
                @endphp
                <a href="{{ route('organization.dpw.show', $slug) }}" class="province-card bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6 border border-gray-100 group flex items-center gap-4" data-name="{{ strtolower($province) }}">
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors shrink-0">
                        <i class="fas fa-map-marker-alt text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $province }}</h3>
                        <p class="text-xs text-gray-500 mt-1">Struktur Inti Wilayah</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div id="noResults" class="hidden text-center py-12">
            <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Provinsi tidak ditemukan.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('provinceSelect');
        const cards = document.querySelectorAll('.province-card');
        const noResults = document.getElementById('noResults');

        function filterProvinces() {
            const selectedProvince = provinceSelect.value.toLowerCase();
            let visibleCount = 0;

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const matchesSelect = selectedProvince === '' || name === selectedProvince;

                if (matchesSelect) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }

        provinceSelect.addEventListener('change', filterProvinces);
    });
</script>
@endpush
@endsection

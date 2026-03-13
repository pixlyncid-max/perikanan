# TODO: Ubah Statistik Perikanan menjadi Statistik Pembudidaya

## Progress

- [x] 1. Analisis file home.blade.php yang ada
- [x] 2. Buat rencana perubahan
- [x] 3. Dapatkan persetujuan dari user
- [x] 4. Ubah judul dari "Statistik Perikanan Kaltim" menjadi "Statistik Pembudidaya Kaltim"
- [x] 5. Buat layout grid untuk 4 grafik (2x2 pada desktop)
- [x] 6. Buat Grafik 1: Produksi Ikan (Doughnut Chart)
- [x] 7. Buat Grafik 2: Produksi Udang (Bar Chart)
- [x] 8. Buat Grafik 3: Statistik Nelayan (Horizontal Bar Chart)
- [x] 9. Buat Grafik 4: Tren Produksi Budidaya (Line Chart)
- [x] 10. Test halaman untuk memastikan semua grafik berfungsi dengan baik


## Detail Perubahan

### File yang Diedit:
- `resources/views/home.blade.php`

### Perubahan Utama:
1. **Judul Section**: "Statistik Perikanan Kaltim" → "Statistik Pembudidaya Kaltim"
2. **Layout**: Single chart → Grid 2x2 dengan 4 chart cards
3. **Chart 1 - Produksi Ikan**: Doughnut chart dengan data per wilayah
4. **Chart 2 - Produksi Udang**: Bar chart vertikal dengan data per wilayah
5. **Chart 3 - Statistik Nelayan**: Bar chart horizontal dengan data per wilayah
6. **Chart 4 - Tren Produksi**: Line chart dengan data 5 tahun terakhir

### Data Sample:
- Wilayah: Samarinda, Bontang, Balikpapan, Kukar, Kutim, Berau, Paser, Lainnya
- Data Ikan: 1250, 980, 1100, 850, 720, 650, 480, 920 (ton)
- Data Udang: 450, 380, 420, 290, 250, 180, 150, 320 (ton)
- Data Nelayan: 850, 720, 680, 540, 480, 390, 320, 610 (orang)
- Data Tren: 2020-2024 dengan kenaikan produksi

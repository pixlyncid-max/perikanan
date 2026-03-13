# FISHERIES - Platform E-Commerce Perikanan Profesional

FISHERIES adalah platform e-commerce dan komunitas perikanan profesional yang melayani nelayan, pembudidaya, dan pelaku usaha perikanan di Kalimantan Timur.

## Fitur Utama

### 🐟 E-Commerce Perikanan
- **Pelet Pakan Ikan**: Berbagai jenis pakan untuk lele, nila, gurame, patin, dan mas
- **Pakan Hidup**: Artemia, cacing sutra, dan cacing tanah
- **Umpan Ikan Laut**: Umpan berkualitas untuk tuna, cakalang, tenggiri, kakap, dan layang
- **Penyewaan Kapal**: Kapal nelayan tersedia di berbagai lokasi Kaltim
- **Vitamin Air**: Probiotik, disinfektan, dan suplemen perairan
- **Bibit Ikan**: Bibit berkualitas unggul untuk berbagai jenis ikan

### 👥 Keanggotaan
- Sistem login dan registrasi anggota
- Kartu anggota digital dengan QR code
- Diskon khusus anggota (10% untuk semua produk)
- Gratis ongkir untuk pembelian di atas Rp 500.000
- Akses pelatihan dan event eksklusif

### 📰 Konten & Informasi
- Artikel dan berita seputar perikanan
- Struktur organisasi DPP dan DPC
- Program kemitraan untuk supplier dan distributor

### 🏢 Struktur Organisasi
- **DPP Pusat**: Dewan Pimpinan Pusat FISHERIES Indonesia
- **DPC Kalimantan Timur**:
  - Samarinda
  - Bontang
  - Balikpapan
  - Sangatta (Kutai Timur)
  - Berau
  - Kutai Kartanegara (Tenggarong)
  - Paser (Tanah Grogot)
  - Penajam Paser Utara
  - Kutai Barat (Sendawar)
  - Mahakam Ulu (Ujoh Bilang)

## Teknologi

- **Backend**: PHP (Laravel-style structure)
- **Frontend**: HTML, Tailwind CSS, Font Awesome
- **Database**: MySQL (siap integrasi)
- **Template Engine**: Blade-style syntax

## Instalasi

1. Clone repository ini ke folder `htdocs` XAMPP:
```bash
cd c:/xampp/htdocs
git clone [repository-url] perikanan
```

2. Import database:
```bash
mysql -u root -p perikanan_db < database/fisheries_db.sql
```

3. Konfigurasi database di file `.env`:
```
DB_DATABASE=perikanan_db
DB_USERNAME=root
DB_PASSWORD=
```

4. Akses aplikasi:
```
http://localhost/perikanan
```

## Struktur Folder

```
perikanan/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── helpers.php
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── fisheries_db.sql
├── public/
│   ├── index.php
│   ├── .htaccess
│   ├── css/
│   └── js/
├── resources/
│   └── views/
│       ├── layouts/
│       ├── auth/
│       ├── organization/
│       ├── program/
│       ├── article/
│       └── errors/
├── routes/
├── vendor/
└── README.md
```

## Fitur yang Tersedia

### Halaman Utama
- ✅ Beranda dengan hero slider, berita, grafik, dan produk unggulan
- ✅ Tentang Kami (visi, misi, nilai organisasi)
- ✅ Kontak (formulir kontak, info kantor, peta lokasi)
- ✅ Kemitraan (formulir pendaftaran mitra)

### Organisasi
- ✅ Struktur DPP Pusat
- ✅ Daftar DPC di Kalimantan Timur
- ✅ Halaman detail untuk setiap DPC

### Program/E-Commerce
- ✅ Pelet Pakan Ikan (katalog produk dengan filter)
- ✅ Pakan Hidup (Artemia, Cacing Sutra, Cacing Tanah)
- ✅ Umpan Ikan Laut (berbagai jenis umpan)
- ✅ Penyewaan Kapal (filter by lokasi)
- ✅ Vitamin Air (probiotik, disinfektan, dll)
- ✅ Bibit Ikan (katalog bibit berkualitas)

### Artikel
- ✅ Daftar artikel dengan kategori
- ✅ Halaman detail artikel
- ✅ Tag dan filter

### Autentikasi
- ✅ Login
- ✅ Registrasi anggota
- ✅ Kartu anggota digital
- ✅ Logout

## Kontribusi

Kami terbuka untuk kontribusi dari komunitas perikanan. Silakan hubungi kami untuk:

- Menjadi mitra supplier
- Menjadi mitra distributor
- Kolaborasi pelatihan
- Sponsorship event

## Kontak

- **Email**: info@fisheries.id
- **Telepon**: (0541) 123456
- **Alamat**: Jl. Perikanan No. 123, Samarinda, Kalimantan Timur

## Lisensi

© 2024 FISHERIES Indonesia. All rights reserved.

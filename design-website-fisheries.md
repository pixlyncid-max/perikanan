# Design Document — Website Organisasi & Perdagangan Perikanan

## 1. Ringkasan Proyek

**Nama Proyek:** Website Organisasi Perikanan (Fisheries Association & Marketplace)
**Tipe:** Website informasi organisasi + katalog/penjualan produk perikanan
**Gaya UI:** Modern, clean, maritime/professional, dengan scroll animation (parallax, fade-in, reveal-on-scroll)
**Target Pengguna:** Anggota organisasi, cabang daerah, mitra bisnis, pembeli produk perikanan, pembaca artikel/berita

---

## 2. Sistem Warna (Color System)

| Nama Token | Hex | Kegunaan |
|---|---|---|
| `--color-primary-dark` | `#002A49` | Navbar, footer, heading besar, background section gelap (hero, CTA), teks pada background terang |
| `--color-primary-accent` | `#019ADA` | Tombol utama (CTA), link aktif, ikon, highlight, garis bawah, badge, hover state |
| `--color-base-white` | `#FFFFFF` | Background utama, teks pada background gelap, card background |

### Turunan warna (untuk kebutuhan UI, tetap dalam nuansa base)

| Nama Token | Hex | Kegunaan |
|---|---|---|
| `--color-primary-dark-80` | `#0E3A5C` | Hover state elemen dark |
| `--color-primary-dark-10` | `#E6ECF1` | Background section terang alternatif (selang-seling section) |
| `--color-accent-light` | `#5FC2EE` | Gradient, ilustrasi gelombang, background dekoratif lembut |
| `--color-accent-dark` | `#017CB3` | Hover pada tombol accent |
| `--color-neutral-gray` | `#6B7A88` | Teks sekunder/paragraf |
| `--color-neutral-line` | `#DCE4EA` | Border, divider |
| `--color-success` | `#2FB673` | Status/label (opsional, ekosistem/keberlanjutan) |

**Prinsip penggunaan:**
- 60% putih (ruang napas, keterbacaan)
- 30% biru tua `#002A49` (struktur, otoritas, navigasi)
- 10% biru cerah `#019ADA` (aksi, penekanan, energi/air)

---

## 3. Tipografi

| Elemen | Font | Ukuran (desktop) | Ukuran (mobile) | Weight |
|---|---|---|---|---|
| H1 (Hero) | Poppins / Plus Jakarta Sans | 56px | 32px | 700 |
| H2 (Section Title) | Poppins / Plus Jakarta Sans | 40px | 26px | 600 |
| H3 (Card/Sub Title) | Poppins / Plus Jakarta Sans | 24px | 20px | 600 |
| Body | Inter / Plus Jakarta Sans | 16px | 15px | 400 |
| Caption/Label | Inter | 13px | 12px | 500, uppercase, letter-spacing 0.05em |
| Button | Inter | 16px | 15px | 600 |

- Line-height body: 1.6
- Line-height heading: 1.2
- Max lebar paragraf: 65–75 karakter agar nyaman dibaca

---

## 4. Prinsip Layout & Grid

- **Grid:** 12-kolom, max-width container `1280px`, padding horizontal 24px (mobile) / 80px (desktop)
- **Spacing scale:** 4 / 8 / 16 / 24 / 32 / 48 / 64 / 96 / 128 px
- **Border radius:** 12px (card), 999px (button/pill), 24px (image besar/hero)
- **Shadow:** halus, `0 8px 24px rgba(0,42,73,0.08)` untuk card, gunakan jarang agar tetap clean
- **Section rhythm:** selang-seling background putih dan `#002A49` (atau tint terangnya) agar scroll terasa berirama

---

## 5. Struktur Halaman & Navigasi

### Navbar (Sticky)
- Logo kiri, menu tengah/kanan: **Tentang Kami · Organisasi · Dewan Perwakilan Cabang · Produk · Kemitraan · Artikel · Kontak**
- Background transparan di atas hero → berubah solid `#002A49` dengan blur saat discroll (scroll-triggered navbar)
- CTA button di navbar kanan: "Hubungi Kami" (accent `#019ADA`, pill shape)
- Mobile: hamburger menu → slide-in panel dari kanan, background `#002A49`, teks putih

### Footer
- Background `#002A49`, teks putih/abu terang
- 4 kolom: Logo & deskripsi singkat, Navigasi, Kontak & alamat cabang, Sosial media + newsletter form
- Garis dekoratif gelombang (wave SVG) di atas footer sebagai transisi dari section terakhir

---

## 6. Detail Section per Halaman

### 6.1 Hero Section
- Full-viewport (min-height 90vh), background `#002A49` dengan overlay foto/ilustrasi laut, jaring, atau kapal nelayan (opacity gelap agar teks tetap terbaca)
- Headline besar putih + subheadline abu terang
- Dua CTA: "Lihat Produk Kami" (solid accent `#019ADA`) & "Tentang Organisasi" (outline putih)
- Animasi: teks fade-up bertahap (headline → subheadline → CTA, delay 100–150ms), background dengan efek parallax lambat saat scroll, elemen ikan/gelombang mengambang (subtle float animation, loop)
- Scroll indicator kecil di bawah (mouse icon / chevron bounce)

### 6.2 Tentang Kami
- Layout 2 kolom: teks (visi misi singkat, sejarah organisasi) di kiri, gambar/ilustrasi di kanan (atau sebaliknya, selang-seling per section berikutnya)
- Statistik counter (contoh: jumlah anggota, cabang, tahun berdiri, tonase produksi) — **animasi angka menghitung naik (count-up) saat elemen masuk viewport**
- Animasi: reveal fade-in-up saat section masuk viewport (Intersection Observer), gambar dengan efek scale-in halus

### 6.3 Organisasi
- Struktur organisasi ditampilkan sebagai **org chart visual** modern (bukan tabel kaku): card berjenjang dengan foto, nama, jabatan
- Background alternatif `#E6ECF1` agar section ini menonjol dari putih
- Animasi: card muncul berurutan dari atas ke bawah mengikuti hierarki (stagger reveal)
- Bisa tambahkan tab/filter: "Pengurus Pusat" vs "Divisi"

### 6.4 Dewan Perwakilan Cabang
- Peta interaktif Indonesia (SVG map) menampilkan titik lokasi cabang per provinsi/kota
- Hover/klik titik pada peta → muncul card info cabang (nama ketua cabang, alamat, kontak)
- Di bawah peta: grid card daftar semua cabang (foto perwakilan, nama, wilayah, kontak singkat)
- Animasi: titik peta muncul dengan efek pulse/ping berurutan saat section masuk viewport, card grid fade-in stagger

### 6.5 Produk
- Grid katalog produk (3–4 kolom desktop, 2 kolom tablet, 1 kolom mobile)
- Card produk: foto, nama produk, kategori (tangkap/budidaya/olahan), harga atau "Hubungi untuk harga", tombol "Detail"
- Filter/kategori di atas grid (tab pill: Semua, Ikan Segar, Hasil Olahan, Ekspor, dll — mengacu ke 4 pilar layanan seperti proyek Poetradt sebelumnya jika relevan)
- Animasi: card hover → sedikit terangkat (translateY -6px) + shadow membesar + gambar zoom halus; saat scroll, card muncul dengan stagger fade-up
- Opsional: quick-view modal saat klik produk

### 6.6 Kemitraan
- Logo grid mitra/partner (grayscale default → berwarna saat hover)
- Section ajakan kemitraan dengan CTA "Jadi Mitra Kami"
- Animasi: logo fade-in stagger, marquee/infinite scroll horizontal untuk logo (opsional, jika partner banyak)

### 6.7 Artikel / Berita
- Grid card artikel (gambar thumbnail, kategori tag, judul, tanggal, ringkasan singkat)
- Artikel utama (featured) ditampilkan lebih besar di posisi pertama
- Animasi: card fade-up stagger, image zoom on hover
- Tombol "Lihat Semua Artikel" di akhir section

### 6.8 Kontak
- Layout 2 kolom: form kontak (kiri) + peta lokasi kantor pusat & info kontak (kanan)
- Background `#002A49`, form dalam card putih mengambang (elevated card)
- Form fields: Nama, Email, Subjek, Pesan, tombol submit accent `#019ADA`
- Animasi: form card slide-up + fade saat masuk viewport, input field dengan focus animation (border glow accent)

---

## 7. Animasi Scroll — Spesifikasi Teknis

| Jenis Animasi | Trigger | Elemen | Durasi/Easing |
|---|---|---|---|
| Fade-up on scroll | Elemen masuk 20% viewport | Heading, paragraf, card | 600ms, `ease-out` |
| Stagger reveal | Container masuk viewport | Grid card (produk, artikel, cabang) | delay 80–120ms antar item |
| Count-up number | Elemen masuk viewport | Statistik di "Tentang Kami" | 1.5–2s, `ease-out` |
| Parallax background | Scroll progress | Hero background image | translateY 0–15%, terikat scroll |
| Sticky navbar transform | Scroll Y > 60px | Navbar | 300ms background/blur transition |
| Hover lift | Mouse hover | Card produk/artikel | 250ms, translateY + shadow |
| Marquee infinite | Auto/loop | Logo mitra | linear, tanpa jeda |
| Progress bar scroll | Scroll progress halaman | Garis tipis di top navbar (opsional) | width 0–100% mengikuti scroll |

**Rekomendasi teknis implementasi:**
- Gunakan `IntersectionObserver` API untuk trigger reveal-on-scroll (ringan, tanpa library berat)
- Untuk animasi lebih kompleks: **GSAP + ScrollTrigger** atau **Framer Motion** (jika React) / **AOS.js** (jika HTML statis, paling ringan untuk implementasi cepat)
- Hormati preferensi pengguna: tambahkan `prefers-reduced-motion` media query agar animasi otomatis nonaktif bagi pengguna yang sensitif terhadap gerakan

---

## 8. Komponen UI Reusable

- **Button Primary:** background `#019ADA`, teks putih, radius pill, hover → `#017CB3` + scale 1.02
- **Button Secondary/Outline:** border `#002A49` atau putih (tergantung background), transparan, hover isi warna
- **Card:** radius 12px, shadow halus, padding 24px, hover elevate
- **Badge/Tag kategori:** pill kecil, background tint accent (`rgba(1,154,218,0.1)`), teks `#019ADA`
- **Section Label:** teks kecil uppercase accent di atas H2 (mis. "PRODUK KAMI") sebagai penanda visual tiap section
- **Divider gelombang (wave SVG):** dipakai di transisi antar section bertema laut, warna mengikuti section di atas/bawahnya

---

## 9. Responsive Breakpoints

| Breakpoint | Lebar | Catatan |
|---|---|---|
| Mobile | < 640px | 1 kolom, navbar hamburger, animasi disederhanakan |
| Tablet | 640–1024px | 2 kolom grid, navbar tetap horizontal ringkas |
| Desktop | 1024–1440px | Grid penuh sesuai spesifikasi di atas |
| Large Desktop | > 1440px | Container tetap max 1280px, ruang kosong kiri-kanan |

---

## 10. Catatan Aksesibilitas

- Kontras teks putih di atas `#002A49` sudah memenuhi WCAG AA
- Kontras teks `#019ADA` di atas putih **tidak** memenuhi AA untuk teks kecil — gunakan hanya untuk elemen besar/bold, ikon, atau tombol (bukan paragraf panjang)
- Semua animasi scroll harus punya fallback statis (`prefers-reduced-motion: reduce`)
- Pastikan semua gambar punya `alt text` deskriptif, terutama untuk foto produk dan perwakilan cabang

---

## 11. Referensi Struktur Section (Urutan Halaman Utama)

1. Navbar (sticky)
2. Hero
3. Tentang Kami (+ statistik)
4. Organisasi (struktur/org chart)
5. Dewan Perwakilan Cabang (peta interaktif)
6. Produk (katalog + filter)
7. Kemitraan (logo grid)
8. Artikel (grid berita)
9. Kontak (form + peta)
10. Footer


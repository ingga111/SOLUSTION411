# Design Document: Customer-Side Clean E-Commerce Refactor

**Date:** 2026-11-01  
**Status:** Approved by User  
**Topic:** Clean Modern E-Commerce Refactor for Customer/User Facing Views  

---

## 1. Executive Summary & Objective

Tujuan dari refactor ini adalah merombak seluruh antarmuka sisi pelanggan (*customer/user side*) pada aplikasi toko digital & top-up game **solustion411**. Desain yang diterapkan mengusung tema **Clean Modern E-Commerce** (terinspirasi dari platform marketplace modern seperti Tokopedia, Shopee, dan modern SaaS digital stores), dengan estetika netral, bersih, tipografi tegas (*Plus Jakarta Sans*), dukungan *Dual-Mode* (Light & Dark) yang mulus tanpa visual clutter, serta alur belanja yang sangat ramah pengguna smartphone (*mobile-first*).

---

## 2. Design System & Token Warna (Dual-Mode)

### 2.1 Palet Light Mode (Clean Marketplace Default)
- **Kanvas Halaman:** `bg-slate-50`
- **Permukaan Kartu / Kontainer:** `bg-white` dengan border `border-slate-200/80` dan bayangan lembut `shadow-sm` hingga `shadow-md`
- **Tipografi:** Heading `text-slate-900`, body text `text-slate-600`, teks pendukung `text-slate-400`
- **Input & Form Controls:** `bg-white border-slate-300 text-slate-900 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500`

### 2.2 Palet Dark Mode (Obsidian Neutral)
- **Kanvas Halaman:** `dark:bg-[#0B0F17]`
- **Permukaan Kartu / Kontainer:** `dark:bg-slate-900/90` dengan border `dark:border-slate-800` dan subtle backdrop-blur
- **Tipografi:** Heading `dark:text-white`, body text `dark:text-slate-300`, teks pendukung `dark:text-slate-400`
- **Input & Form Controls:** `dark:bg-slate-900 dark:border-slate-800 dark:text-white focus:dark:border-cyan-400`

### 2.3 Brand Accent Colors
- **Cyan (`cyan-500` / `cyan-600`):** Tombol aksi utama (CTA), status proses otomatis, navigasi aktif.
- **Amber (`amber-500` / `amber-400`):** Flash sale, badge promo spesial, akun sultan/bergaransi.
- **Emerald (`emerald-500` / `emerald-400`):** Status pesanan lunas/selesai, voucher diskon berhasil, ketersediaan stok aktif.
- **Fuchsia (`fuchsia-500` / `fuchsia-400`):** Badge preorder PDH dan seragam kustom.

---

## 3. Komponen Arsitektur & Lingkup Refactor

### 3.1 Layout Global, Navbar, Mobile Dock & Footer (`resources/views/layouts/app.blade.php`)
1. **Running Text Ticker:**
   - Ticker ramping di atas navbar dengan loop animasi tak hingga (`ticker-inner`), pause saat hover.
   - Menampilkan status layanan 24 jam nonstop, promo terhangat, dan nomor WhatsApp resmi CS.
2. **Sticky Header Navbar:**
   - Brand lockup: `SOLUSTION411 ID` dengan ikon petir modern.
   - Global Search Bar di tengah navbar dengan placeholder informatif, shortcut `Ctrl + K`, dan filter instan.
   - Navigasi link desktop: *Beranda*, *Cek Pesanan*, *Hitung WR*, dan shortcut *Admin* (jika role admin).
   - Theme Switcher: Tombol toggle Terang ☀️ / Gelap 🌙 dengan anti-FOUC script dan penyimpanan `localStorage`.
   - Hub Akun: Dropdown profil modern untuk user login atau tombol *Masuk* untuk tamu.
3. **Responsive Mobile Bottom Dock:**
   - Bar navigasi bawah layar HP dengan 4 tombol: *Beranda*, *Lacak Pesanan*, *Katalog*, dan *Akun/Login*.
4. **Interactive Modals:**
   - Modal *Cek Pesanan* instan tanpa reload halaman.
   - Modal *Hitung WR Mobile Legends* interaktif.
5. **Footer Marketplace:**
   - Bio toko, jaminan legalitas & keamanan 24 jam nonstop, badge metode pembayaran (BCA, Mandiri, BRI, BNI, QRIS, E-Wallet), dan tautan WhatsApp bantuan pelanggan.

### 3.2 Beranda & Katalog Produk (`resources/views/welcome.blade.php`)
1. **Hero Banner Carousel:**
   - Slider dinamis admin-configurable dengan rasio responsif (16:9 di mobile, 21:9 di desktop).
   - Tombol navigasi panah kiri/kanan, indikator slide berbentuk kapsul, dan tombol CTA.
2. **Flash Sale & Promo Hari Ini:**
   - Banner event dengan countdown timer real-time (jam, menit, detik).
   - Scroll instan ke produk terkait.
3. **Category Navigation Pills:**
   - Tab kategori scrollable horizontal touch-friendly dengan counter produk.
4. **Product Catalog Grid Berdasarkan Tipe Produk:**
   - `game`: Card poster vertikal, nama game, publisher, label harga mulai dari.
   - `game_account`: Card 16:9, preview screenshot akun, badge "🛡️ Full Garansi", chip ringkasan spesifikasi (Rank, Hero, Skin).
   - `preorder`: Card mockup seragam PDH, badge "👕 Preorder Open", pilihan ukuran S-3XL, opsi DP/Lunas.
   - `account`: Card digital streaming, pilihan durasi, badge kredensial otomatis.
   - `joki`: Card paket joki rank, garansi booster pro player.
5. **Client-side Instant Filter & Empty State:**
   - Pencarian real-time dan notifikasi ramah jika kata kunci tidak ditemukan.

### 3.3 Detail Produk & Alur Form Checkout (`resources/views/orders/checkout.blade.php`)
1. **Product Header & Interactive Showcase:**
   - Thumbnail/logo produk, badge tipe layanan, garansi resmi, dan harga awal.
   - Galeri foto interaktif dengan thumbnail strip dan modal zoom layar penuh (*fullscreen zoom*).
   - Tabel spesifikasi akun (*Account Specs*) atau panduan ukuran seragam (*Size Chart*).
2. **5-Step Form Checkout:**
   - **Langkah 1:** Masukkan Data Akun / Identitas Tujuan (User ID + Zone ID untuk game; Ukuran & Nama Bordir untuk Preorder PDH; Kontak email/WA untuk Akun Digital; Akun game untuk Joki).
   - **Langkah 2:** Pilih Varian / Nominal (Grid kartu touch-friendly, format Rupiah jelas, badge stok).
   - **Langkah 3:** Kode Voucher Diskon (AJAX check ke `/vouchers/check`, diskon instan).
   - **Langkah 4:** Pilih Metode Pembayaran (Transfer Bank Manual & QRIS/E-Wallet).
   - **Langkah 5:** Nomor WhatsApp & Ringkasan Checkout.
3. **Mobile Sticky Checkout Dock:**
   - Ringkasan total belanja dan tombol *"Beli Sekarang ➜"* yang melayang di bawah layar HP.

### 3.4 Halaman Invoice & Pelacakan Pembayaran (`resources/views/orders/show.blade.php`)
1. **Status Stepper 4 Tahap:**
   - *Menunggu Bayar* ➔ *Bukti Diunggah* ➔ *Diproses* ➔ *Selesai*.
2. **Countdown Waktu Pembayaran:**
   - Timer batas waktu transfer agar pesanan tidak kedaluwarsa.
3. **Card Instruksi Rekening & Tombol Salin 1-Klik:**
   - Tombol salin nomor rekening dan salin nominal transfer (kode unik) dengan feedback visual.
   - Tampilan barcode QRIS yang siap scan.
4. **Upload Bukti Pembayaran:**
   - File picker dengan instant image preview sebelum submit.
5. **Live Polling Status:**
   - Fetching berkala ke `/orders/{orderNumber}/status` tiap 10 detik untuk auto-refresh status invoice.
6. **Delivery Showcase:**
   - Kotak informasi kredensial/notifikasi penyerahan via WhatsApp resmi setelah status pesanan diselesaikan admin.

### 3.5 Customer Dashboard & Autentikasi
1. **Riwayat Pesanan Pelanggan (`resources/views/customer/orders/index.blade.php`):**
   - Filter tab status pesanan (*Semua, Menunggu Bayar, Diproses, Selesai, Batal*).
   - Kartu pesanan modern dengan format angka tabular dan link langsung ke invoice.
2. **Detail Pesanan Pelanggan (`resources/views/customer/orders/show.blade.php`):**
   - Membuat view baru yang sebelumnya missing untuk melengkapi `OrderHistoryController@show`.
3. **Kelola Profil (`resources/views/profile/edit.blade.php`):**
   - Avatar profil inisial, form update data diri, nomor WhatsApp, ganti kata sandi dengan toggle mata (*show/hide*).
4. **Autentikasi (`resources/views/auth/login.blade.php` & `register.blade.php`):**
   - Desain centered card minimalis, integrasi Cloudflare Turnstile, dan pesan validasi rapi.

---

## 4. Testing & Verification Plan

1. **Styling & Formatting:** Jalankan `vendor/bin/pint --format agent` pada seluruh file view Blade dan file PHP terkait.
2. **Unit & Feature Tests:** Jalankan `vendor/bin/phpunit` untuk memastikan seluruh alur pemesanan (`OrderControllerTest`, `CustomerOrderHistoryTest`, `VoucherTest`) lulus tanpa regresi.
3. **Visual & Responsive Verification:** Uji rendering seluruh halaman pada mode Terang (Light) dan mode Gelap (Dark), serta verifikasi responsiveness di resolusi mobile maupun desktop.


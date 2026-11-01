# Customer Side Clean E-Commerce Implementation Plan

> **For Antigravity:** REQUIRED WORKFLOW: Use `.agent/workflows/execute-plan.md` to execute this plan in single-flow mode.

**Goal:** Refactor seluruh antarmuka sisi customer/user (Layout/Navbar, Homepage, Checkout, Invoice, Customer Area & Auth) mengadopsi estetika Clean Modern E-Commerce dengan dukungan dual-mode (Light & Dark), mobile-first responsiveness, dan alur pemesanan yang mulus.

**Architecture:** Menerapkan Clean E-Commerce Design System berbasis Tailwind CSS terpadu, struktur kartu netral (`bg-white` / `dark:bg-slate-900/90`), border halus (`border-slate-200/80` / `dark:border-slate-800`), font *Plus Jakarta Sans*, interaksi form 5-langkah pada checkout, live polling invoice, dan melengkapi route customer order show yang hilang.

**Tech Stack:** Laravel 11, Blade Templates, Tailwind CSS (Dual-Mode), Alpine.js / Vanilla JavaScript, PHPUnit, Laravel Pint.

---

### Task 1: Refactor Global Layout, Navbar, Mobile Dock & Footer (`layouts/app.blade.php`)

**Files:**
- Modify: `resources/views/layouts/app.blade.php`
- Test: `tests/Feature/LayoutRenderTest.php` (atau verifikasi rute utama `/`)

**Step 1: Inspect and prepare layout design tokens**
- Buka dan amati CSS rules di `resources/views/layouts/app.blade.php`.
- Perbarui variabel CSS root & dark untuk transisi warna yang mulus antara light (`bg-slate-50`, text `text-slate-900`) dan dark (`#0B0F17`, text `white`).

**Step 2: Refactor Running Announcement Ticker Bar**
- Perbarui struktur pita pengumuman running text: layout ramping, flex alignment presisi, teks berputar tak terputus dengan pause saat hover (`hover:pause`), dan penanganan fallback jika tidak ada running text yang aktif.

**Step 3: Refactor Sticky Header Navbar & Navigation Hub**
- Logo & Brand: `SOLUSTION411 ID` dengan badge resmi.
- Desktop Global Search Bar: Input pencarian dengan ikon search, shortcut badge `Ctrl + K`, dan filter instan.
- Link Navigasi: Beranda, Kategori Cepat, Tombol *"🔍 Cek Pesanan"*, Tombol *"🧮 Hitung WR"*, dan shortcut Admin jika user adalah administrator.
- Theme Switcher: Tombol toggle Terang/Gelap yang responsif dan anti-kedip (anti-FOUC).
- Profile Menu: Dropdown profil modern jika auth atau tombol *"Masuk"* jika guest.

**Step 4: Refactor Mobile Bottom Navigation Dock & Quick Modals**
- Dock bawah untuk HP dengan tombol *Beranda*, *Cek Pesanan*, *Katalog*, dan *Akun*.
- Modal Cek Pesanan instan dengan form nomor invoice.
- Modal Hitung Win Rate MLBB interaktif.

**Step 5: Refactor Marketplace Footer**
- Bio toko, garansi transaksi aman 24 jam nonstop, grid tautan bantuan, badge metode pembayaran (BCA, Mandiri, BRI, BNI, QRIS, E-Wallet), dan tautan WhatsApp resmi.

**Step 6: Verify layout rendering**
Run: `vendor/bin/phpunit --filter=test_homepage_is_accessible`
Expected: PASS

---

### Task 2: Refactor Homepage & Product Catalog Grid (`welcome.blade.php`)

**Files:**
- Modify: `resources/views/welcome.blade.php`

**Step 1: Refactor Hero Banner Carousel**
- Modernisasi slider banner dengan rasio aspek dinamis (16:10 di mobile, 21:9 di desktop), navigasi panah kiri/kanan, indikator slide berbentuk kapsul (*pill dots*), dan tombol CTA langsung ke katalog.

**Step 2: Refactor Flash Sale & Promo Section**
- Banner event promo dengan gradient amber/rose elegan, badge waktu terbatas, dan countdown timer JavaScript real-time (jam, menit, detik).

**Step 3: Refactor Category Filter Pills**
- Tab kategori scrollable horizontal touch-friendly dengan badge counter produk dan penanda aktif yang jelas.

**Step 4: Refactor Product Catalog Grid by Product Type**
- Tipe `game`: Kartu vertikal bersih dengan poster game, nama produk, publisher, harga mulai dari, dan tombol order cepat.
- Tipe `game_account`: Kartu 16:9 dengan preview screenshot, badge *"🛡️ Garansi Anti-Hackback"*, chip spesifikasi akun (Rank, Hero, Skin), dan harga nett.
- Tipe `preorder`: Kartu mockup seragam PDH dengan badge *"👕 Preorder Open"*, keterangan ukuran S–3XL, opsi DP/Lunas, dan harga awal.
- Tipe `account`: Kartu platform digital streaming dengan badge kredensial otomatis.
- Tipe `joki`: Kartu paket joki rank pro player.

**Step 5: Refactor Live Search Filter & Empty State**
- Script JavaScript filter instan berbasis judul dan kategori, serta tampilan *empty state* yang ramah jika pencarian tidak ditemukan.

**Step 6: Verify homepage rendering**
Run: `vendor/bin/phpunit --filter=HomeControllerTest` (atau test homepage terkait)
Expected: PASS

---

### Task 3: Refactor Product Detail & 5-Step Checkout Flow (`orders/checkout.blade.php`)

**Files:**
- Modify: `resources/views/orders/checkout.blade.php`

**Step 1: Refactor Product Header & Interactive Showcase**
- Breadcrumb navigasi, card identitas produk, badge legalitas 100%, dan harga mulai dari.
- Galeri screenshot interaktif (untuk akun sultan & preorder): thumbnail carousel dan modal zoom fullscreen gambar.
- Box spesifikasi akun (*Account Specs*) atau panduan ukuran seragam (*Size Chart*).

**Step 2: Refactor Step 1 (Input Akun / Data Tujuan)**
- Input dinamis: User ID + Zone ID untuk game; Ukuran baju S-3XL + Nama Bordir untuk Preorder PDH; Data login untuk Joki; Email/Kontak untuk Akun Digital.

**Step 3: Refactor Step 2 (Pilih Varian / Nominal)**
- Grid kartu varian modern 2 kolom di mobile dan 3-4 kolom di desktop, badge stok, format harga Rupiah yang jelas, dan ring highlight saat varian dipilih.

**Step 4: Refactor Step 3 & 4 (Voucher Diskon & Metode Pembayaran)**
- Input kode promo voucher terintegrasi AJAX `/vouchers/check` dengan potongan harga instan.
- Pilihan metode bayar dikelompokkan: Transfer Bank Manual & QRIS/E-Wallet dengan logo resmi dan keterangan admin fee.

**Step 5: Refactor Step 5 & Mobile Sticky Checkout Dock**
- Input nomor WhatsApp aktif & rincian total tagihan.
- Sticky floating bottom bar di layar HP yang menampilkan rincian varian, total harga, dan tombol *"Beli Sekarang ➜"*.

**Step 6: Verify checkout feature tests**
Run: `vendor/bin/phpunit --filter=OrderCheckoutTest`
Expected: PASS

---

### Task 4: Refactor Invoice, Payment Instructions & Live Polling (`orders/show.blade.php`)

**Files:**
- Modify: `resources/views/orders/show.blade.php`

**Step 1: Refactor 4-Step Status Progress Stepper**
- Stepper visual responsif: *Menunggu Pembayaran* ➔ *Bukti Diunggah* ➔ *Diproses* ➔ *Selesai* dengan warna status dinamis.

**Step 2: Refactor Countdown Timer & Payment Detail Card**
- Timer hitung mundur batas waktu pembayaran.
- Card rekening toko dengan tombol salin 1-klik untuk Nomor Rekening dan Jumlah Transfer Tepat (dengan tooltip *"Tersalin! ✓"*).
- Barcode QRIS interaktif yang proporsional dan mudah di-scan.

**Step 3: Refactor Payment Proof Upload Form**
- Area unggah bukti transfer interaktif dengan instant image preview sebelum submit, status verifikasi bukti, dan alert jika bukti ditolak admin.

**Step 4: Refactor Live Status Auto-Polling Script**
- JavaScript fetch ke `/orders/{orderNumber}/status` tiap 10 detik untuk auto-refresh status saat admin memverifikasi pembayaran tanpa perlu reload manual.

**Step 5: Refactor Post-Completion Delivery Showcase**
- Kotak informasi kredensial/notifikasi penyerahan via WhatsApp resmi setelah status pesanan diselesaikan admin.

**Step 6: Verify order show and upload proof feature tests**
Run: `vendor/bin/phpunit --filter=OrderShowTest` (atau test order terkait)
Expected: PASS

---

### Task 5: Refactor Customer Dashboard, Orders History, Detail View & Profile

**Files:**
- Modify: `resources/views/customer/orders/index.blade.php`
- Create: `resources/views/customer/orders/show.blade.php`
- Modify: `resources/views/profile/edit.blade.php`
- Modify: `resources/views/auth/login.blade.php`
- Modify: `resources/views/auth/register.blade.php`

**Step 1: Refactor Customer Orders Index (`customer/orders/index.blade.php`)**
- Tab filter status pesanan (*Semua, Menunggu Bayar, Diproses, Selesai, Batal*).
- Kartu pesanan modern dengan tabular-nums dan tombol pintas ke invoice.

**Step 2: Create Customer Order Detail View (`customer/orders/show.blade.php`)**
- Buat view baru yang menangani route `customer.orders.show` untuk menampilkan rincian transaksi lengkap dari sisi akun pelanggan.

**Step 3: Refactor Profile Edit Page (`profile/edit.blade.php`)**
- Card profil modern dengan avatar inisial bergradasi, form data diri & nomor WA, form ganti password dengan toggle mata (*show/hide*), dan ringkasan statistik pesanan.

**Step 4: Refactor Authentication Views (`auth/login.blade.php` & `register.blade.php`)**
- Centered card minimalis clean e-commerce, toggle password visibility, Cloudflare Turnstile CAPTCHA, dan penanganan error validasi yang rapi.

**Step 5: Verify customer & auth feature tests**
Run: `vendor/bin/phpunit --filter=OrderHistoryTest` (dan `ProfileTest`)
Expected: PASS

---

### Task 6: Code Quality, Pint Formatting & Full Test Suite Verification

**Files:**
- Touch: All modified views & controllers

**Step 1: Run Laravel Pint Formatter**
Run: `vendor/bin/pint --format agent`
Expected: Pint exits with code 0 (clean formatting)

**Step 2: Run Full PHPUnit Test Suite**
Run: `vendor/bin/phpunit`
Expected: All tests pass (0 failures, 0 errors)

**Step 3: Update Task Tracker**
- Perbarui `<project-root>/docs/plans/task.md` menandai seluruh fase customer selesai.


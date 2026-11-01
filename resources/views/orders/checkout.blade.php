@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 pb-28">
    
    <!-- Breadcrumb (Linear Style) -->
    <div class="flex items-center gap-2 text-xs font-mono text-zinc-500 dark:text-zinc-400 mb-5">
        <a href="{{ route('home') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Beranda</a>
        <span>/</span>
        <a href="{{ route('home') }}#catalog-section" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Katalog Layanan</a>
        <span>/</span>
        <span class="text-zinc-900 dark:text-zinc-200 font-semibold">{{ $product->name }}</span>
    </div>

    @php
        $galleryUrls = $product->getGalleryUrls();
        $primaryImg = $product->getThumbnailUrl();
        $accountSpecs = $product->account_specs ?? [];
    @endphp

    <!-- 1. PRODUCT HEADER & INSTRUCTIONS CARD (Linear Showcase) -->
    <div class="bg-white dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 mb-6 shadow-sm">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-5 pb-5 border-b border-zinc-100 dark:border-zinc-800">
            <div class="flex items-start sm:items-center gap-3.5">
                <!-- Product Icon -->
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-0.5 flex-shrink-0">
                    <div class="w-full h-full bg-white dark:bg-zinc-950 rounded-[10px] overflow-hidden flex items-center justify-center">
                        @if($primaryImg)
                            <img src="{{ $primaryImg }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl sm:text-3xl">{{ $product->type->icon() }}</span>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="flex flex-wrap items-center gap-1.5 mb-1">
                        <span class="px-2 py-0.2 rounded text-[9px] font-mono font-bold uppercase tracking-wider bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700">
                            {{ $product->publisher ?? 'solustion411' }} • {{ $product->type->label() }}
                        </span>
                        <span class="px-2 py-0.2 rounded text-[9px] font-mono font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            ⚡ PROSES 1-3 DETIK
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-zinc-900 dark:text-zinc-100 tracking-tight">{{ $product->name }}</h1>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                        @if($product->isPreorder())
                            Preorder resmi PDH &amp; Seragam Organisasi / Komunitas. Dibuat dengan bahan berkualitas, jahitan rapi, dan sistem pembayaran DP atau Lunas!
                        @elseif($product->isAccount())
                            Akun resmi premium &amp; bergaransi. Kredensial akun dikirim otomatis setelah pembayaran!
                        @elseif($product->isGameAccount())
                            Jual beli akun bergaransi anti hackback dengan kredensial langsung dan aman!
                        @elseif($product->isJoki())
                            Layanan joki rank profesional, cepat, rapi dan terpercaya 24 jam nonstop!
                        @else
                            Layanan top up resmi 24 jam nonstop. Cukup masukkan User ID tanpa perlu login akun game!
                        @endif
                    </p>
                </div>
            </div>

            <div class="text-left md:text-right flex-shrink-0">
                <span class="text-[10px] font-mono text-zinc-500 uppercase tracking-wider block">Harga Mulai</span>
                <span class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-zinc-100 font-mono tabular-nums">
                    Rp {{ number_format($product->variants->min('price') ?? 0, 0, ',', '.') }}
                </span>
            </div>
        </div>

        @if($product->isGameAccount() || $product->isPreorder() || !empty($galleryUrls))
            <!-- Interactive Screenshot Gallery & Specs Showcase -->
            <div class="mt-5 pt-1">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xs font-mono font-bold text-zinc-900 dark:text-zinc-100 uppercase tracking-wider flex items-center gap-1.5">
                        <span>📸</span>
                        @if($product->isPreorder())
                            Foto Mockup Desain &amp; Galeri PDH / Seragam
                        @elseif($product->isGameAccount())
                            Bukti Screenshot &amp; Isi Akun Game
                        @else
                            Bukti Preview &amp; Informasi Layanan
                        @endif
                    </h2>
                    <span class="text-[11px] font-mono text-zinc-500 hidden sm:inline">
                        Klik gambar untuk perbesar layar penuh
                    </span>
                </div>

                <!-- Main Screenshot Preview -->
                <div class="relative w-full aspect-video sm:aspect-[16/9] max-h-[420px] rounded-xl overflow-hidden bg-zinc-100 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 group cursor-pointer shadow-sm" id="gallery-main-container">
                    <img id="gallery-main-image" src="{{ $galleryUrls[0] ?? $primaryImg }}" alt="{{ $product->name }}" class="w-full h-full object-contain sm:object-cover transition-transform duration-300 group-hover:scale-[1.01]">
                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/60 via-transparent to-transparent pointer-events-none"></div>
                    <button type="button" id="btn-zoom-overlay" class="absolute bottom-3 right-3 px-3 py-1.5 rounded-lg bg-zinc-950/80 hover:bg-zinc-900 text-white text-xs font-mono backdrop-blur-sm border border-zinc-700/80 flex items-center gap-1.5 transition-all">
                        <span>🔍</span>
                        <span>Perbesar Layar Penuh</span>
                    </button>
                    <div class="absolute top-3 left-3 pointer-events-none">
                        <span class="px-2.5 py-1 rounded-md bg-zinc-950/80 backdrop-blur-sm text-zinc-300 text-[10px] font-mono font-bold border border-zinc-700">
                            @if($product->isPreorder())
                                👕 Mockup &amp; Desain Resmi
                            @elseif($product->isGameAccount())
                                Screenshot Asli Terverifikasi
                            @else
                                Preview Akun Resmi Terverifikasi
                            @endif
                        </span>
                    </div>
                </div>

                @if(count($galleryUrls) > 1)
                    <div class="flex items-center gap-2 mt-3 overflow-x-auto pb-1 scrollbar-none">
                        @foreach($galleryUrls as $idx => $url)
                            <button type="button" class="gallery-thumb-btn relative flex-shrink-0 w-24 sm:w-28 aspect-video rounded-lg overflow-hidden border {{ $idx === 0 ? 'border-zinc-900 dark:border-zinc-100 ring-1 ring-zinc-900 dark:ring-zinc-100' : 'border-zinc-200 dark:border-zinc-800 opacity-60 hover:opacity-100' }} transition-all" data-img-url="{{ $url }}" data-img-index="{{ $idx }}">
                                <img src="{{ $url }}" alt="Thumbnail {{ $idx + 1 }}" class="w-full h-full object-cover">
                                <span class="absolute bottom-0.5 right-1 text-[8px] font-mono bg-zinc-950/80 px-1 rounded text-white">#{{ $idx + 1 }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif

                @if(!empty($accountSpecs))
                    <div class="mt-6 pt-5 border-t border-zinc-100 dark:border-zinc-800">
                        <h3 class="text-[11px] font-mono font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <span>📋</span>
                            @if($product->isPreorder())
                                Spesifikasi Bahan &amp; Detail PDH
                            @elseif($product->isGameAccount())
                                Spesifikasi &amp; Statistik Akun Lengkap
                            @else
                                Informasi &amp; Spesifikasi Produk
                            @endif
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            @foreach($accountSpecs as $key => $val)
                                <div class="p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-200 dark:border-zinc-800">
                                    <span class="text-[10px] font-mono text-zinc-500 uppercase tracking-wider block">{{ $key }}</span>
                                    <span class="text-xs font-bold text-zinc-900 dark:text-zinc-200 mt-0.5 block leading-tight">{{ $val }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- 3 Quick Steps Instructions (Minimalist Linear) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 mt-5 pt-4 border-t border-zinc-100 dark:border-zinc-800 text-xs font-mono">
                <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-200 dark:border-zinc-800">
                    <span class="w-5 h-5 rounded bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 flex items-center justify-center font-bold text-[10px] flex-shrink-0">1</span>
                    <span class="text-zinc-700 dark:text-zinc-300">Masukkan Data Akun</span>
                </div>
                <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-200 dark:border-zinc-800">
                    <span class="w-5 h-5 rounded bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 flex items-center justify-center font-bold text-[10px] flex-shrink-0">2</span>
                    <span class="text-zinc-700 dark:text-zinc-300">Pilih Paket Nominal</span>
                </div>
                <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-200 dark:border-zinc-800">
                    <span class="w-5 h-5 rounded bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 flex items-center justify-center font-bold text-[10px] flex-shrink-0">3</span>
                    <span class="text-zinc-700 dark:text-zinc-300">Bayar &amp; Selesai Instan</span>
                </div>
            </div>
        @endif
    </div>

    <!-- MAIN CHECKOUT FORM -->
    <form action="{{ route('orders.store') }}" method="POST" id="checkout-form" class="space-y-5 sm:space-y-6">
        @csrf

        @if($product->isPreorder())
            <!-- STEP 1: FORM DETAIL PREORDER PDH / UNIFORM -->
            <div class="bg-white dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-mono font-bold text-xs flex items-center justify-center flex-shrink-0">1</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white font-mono uppercase tracking-wider">Detail Pesanan Preorder PDH / Seragam</h3>
                            <p class="text-xs text-zinc-500">Pilih ukuran baju, jenis lengan, dan nama bordir dada.</p>
                        </div>
                    </div>
                    <button type="button" onclick="document.getElementById('modal-size-chart').classList.remove('hidden')" class="px-2.5 py-1 rounded-md bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 text-xs font-mono transition-all flex items-center gap-1.5 self-start sm:self-auto">
                        <span>📏</span>
                        <span>Panduan Ukuran (Size Chart)</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Ukuran Baju (Size) <span class="text-rose-500">*</span></label>
                        <select name="target_user_id" required class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono font-bold focus:outline-none focus:ring-1 focus:ring-zinc-500">
                            <option value="">-- Pilih Ukuran Baju --</option>
                            <option value="S" {{ old('target_user_id') == 'S' ? 'selected' : '' }}>S</option>
                            <option value="M" {{ old('target_user_id') == 'M' ? 'selected' : '' }}>M</option>
                            <option value="L" {{ old('target_user_id') == 'L' ? 'selected' : '' }}>L</option>
                            <option value="XL" {{ old('target_user_id') == 'XL' ? 'selected' : '' }}>XL</option>
                            <option value="XXL" {{ old('target_user_id') == 'XXL' ? 'selected' : '' }}>XXL</option>
                            <option value="3XL" {{ old('target_user_id') == '3XL' ? 'selected' : '' }}>3XL</option>
                        </select>
                        @error('target_user_id')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Pilihan Lengan <span class="text-rose-500">*</span></label>
                        <select name="target_zone_id" required class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono font-bold focus:outline-none focus:ring-1 focus:ring-zinc-500">
                            <option value="Lengan Panjang" {{ old('target_zone_id') == 'Lengan Panjang' ? 'selected' : '' }}>👔 Lengan Panjang</option>
                            <option value="Lengan Pendek" {{ old('target_zone_id') == 'Lengan Pendek' ? 'selected' : '' }}>👕 Lengan Pendek</option>
                        </select>
                        @error('target_zone_id')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Nama Dada / Bordir Nama (Opsional)</label>
                        <input type="text" name="target_nickname" value="{{ old('target_nickname') }}" placeholder="Contoh: Rian Pratama" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                        <span class="text-[10px] text-zinc-500 mt-0.5 block">Tulis '-' jika tanpa bordir nama.</span>
                    </div>

                    <div>
                        <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Divisi / Angkatan / Catatan</label>
                        <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Contoh: Divisi Acara" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                    </div>
                </div>
            </div>
        @elseif($product->requires_user_id || $product->requires_zone_id || $product->isJoki())
            <!-- STEP 1: MASUKKAN DATA AKUN GAME / JOKI -->
            <div class="bg-white dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-mono font-bold text-xs flex items-center justify-center flex-shrink-0">1</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white font-mono uppercase tracking-wider">
                                @if($product->isJoki())
                                    Data Akun Joki
                                @elseif($product->isGameAccount())
                                    Informasi Kontak Pembeli Akun
                                @else
                                    Data Akun Game
                                @endif
                            </h3>
                            <p class="text-xs text-zinc-500">Pastikan data akun yang Anda masukkan sudah benar.</p>
                        </div>
                    </div>

                    @if($product->requires_user_id)
                        <button type="button" onclick="document.getElementById('modal-id-guide').classList.remove('hidden')" class="px-2.5 py-1 rounded-md bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 text-xs font-mono transition-all flex items-center gap-1">
                            <span>ℹ️</span>
                            <span>Petunjuk ID</span>
                        </button>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    @if($product->isJoki())
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Login Via <span class="text-rose-500">*</span></label>
                            <select name="target_zone_id" required class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                                <option value="Moonton">Moonton (Email &amp; Password)</option>
                                <option value="VK">VK</option>
                                <option value="Facebook">Facebook</option>
                                <option value="TikTok">TikTok</option>
                                <option value="Google Play">Google Play</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Email / No. HP Akun Game <span class="text-rose-500">*</span></label>
                            <input type="text" name="target_user_id" value="{{ old('target_user_id') }}" required placeholder="Contoh: akun@gmail.com" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                        </div>

                        <div>
                            <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Password Akun Game <span class="text-rose-500">*</span></label>
                            <input type="password" name="target_nickname" value="{{ old('target_nickname') }}" required placeholder="Password akun game" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                            <span class="text-[10px] text-zinc-500 mt-0.5 block">Terenkripsi aman. Matikan verifikasi 2 langkah saat joki diproses.</span>
                        </div>

                        <div>
                            <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Catatan Hero / Request (Opsional)</label>
                            <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Contoh: Request hero Ling / Fanny" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                        </div>
                    @else
                        @if($product->requires_user_id)
                            <div>
                                <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">{{ $product->user_id_label }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="target_user_id" id="input_user_id" value="{{ old('target_user_id') }}" required placeholder="Masukkan {{ $product->user_id_label }}" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                                @error('target_user_id')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        @if($product->requires_zone_id)
                            <div>
                                <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">{{ $product->zone_id_label }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="target_zone_id" id="input_zone_id" value="{{ old('target_zone_id') }}" required placeholder="Contoh: 2024" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                                @error('target_zone_id')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @else
            <!-- STEP 1: INFORMASI AKUN DIGITAL & GARANSI -->
            <div class="bg-white dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="w-6 h-6 rounded bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-mono font-bold text-xs flex items-center justify-center flex-shrink-0">1</span>
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white font-mono uppercase tracking-wider">Detail Pengiriman &amp; Garansi Akun</h3>
                        <p class="text-xs text-zinc-500">Kredensial akun dikirim otomatis ke nomor WhatsApp &amp; Email Anda setelah pembayaran.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                    <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-200 dark:border-zinc-800">
                        <span class="text-xs font-bold text-zinc-900 dark:text-white font-mono block">⚡ Pengiriman Instan</span>
                        <span class="text-[11px] text-zinc-500 mt-0.5 block leading-relaxed">Kredensial dikirim via WhatsApp &amp; invoice web.</span>
                    </div>

                    <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-200 dark:border-zinc-800">
                        <span class="text-xs font-bold text-zinc-900 dark:text-white font-mono block">🛡️ Garansi Masa Aktif</span>
                        <span class="text-[11px] text-zinc-500 mt-0.5 block leading-relaxed">Garansi 100% aktif sesuai pilihan durasi paket Anda.</span>
                    </div>

                    <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-200 dark:border-zinc-800">
                        <span class="text-xs font-bold text-zinc-900 dark:text-white font-mono block">🍿 Siap Pakai</span>
                        <span class="text-[11px] text-zinc-500 mt-0.5 block leading-relaxed">Dapat digunakan langsung di berbagai perangkat.</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- STEP 2: PILIH NOMINAL / VARIASI DURASI -->
        <div class="bg-white dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-center gap-2.5 mb-4">
                <span class="w-6 h-6 rounded bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-mono font-bold text-xs flex items-center justify-center flex-shrink-0">2</span>
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white font-mono uppercase tracking-wider">
                        @if($product->isPreorder())
                            Pilihan Pembayaran (DP / Lunas) &amp; Paket
                        @elseif($product->isGame())
                            Pilih Nominal Top Up
                        @else
                            Pilih Paket / Variasi
                        @endif
                    </h3>
                    <p class="text-xs text-zinc-500">Pilih item atau paket yang Anda butuhkan.</p>
                </div>
            </div>

            @php
                $hasDurationKeywords = $product->variants->contains(function ($v) {
                    $n = strtolower($v->name);
                    return str_contains($n, 'hari') || str_contains($n, 'bulan') || str_contains($n, 'bln') || str_contains($n, 'minggu');
                });
                $hasDpLunasKeywords = $product->variants->contains(function ($v) {
                    $n = strtolower($v->name);
                    return str_contains($n, 'dp') || str_contains($n, 'uang muka') || str_contains($n, 'lunas') || str_contains($n, 'pelunasan');
                });
            @endphp

            @if($hasDpLunasKeywords)
                <div class="flex items-center gap-1.5 mb-4 overflow-x-auto pb-1 scrollbar-none" id="dp-filter-tabs">
                    <button type="button" onclick="filterVariantsByDuration('all', this)" class="duration-tab active-dur-tab px-3 py-1 rounded-md text-xs font-mono font-bold bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 whitespace-nowrap transition-all cursor-pointer">
                        Semua Pilihan
                    </button>
                    <button type="button" onclick="filterVariantsByDuration('dp', this)" class="duration-tab px-3 py-1 rounded-md text-xs font-mono font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white border border-zinc-200 dark:border-zinc-700 whitespace-nowrap cursor-pointer">
                        DP (Uang Muka)
                    </button>
                    <button type="button" onclick="filterVariantsByDuration('lunas', this)" class="duration-tab px-3 py-1 rounded-md text-xs font-mono font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white border border-zinc-200 dark:border-zinc-700 whitespace-nowrap cursor-pointer">
                        Lunas (Penuh)
                    </button>
                </div>
            @elseif($hasDurationKeywords)
                <div class="flex items-center gap-1.5 mb-4 overflow-x-auto pb-1 scrollbar-none" id="duration-filter-tabs">
                    <button type="button" onclick="filterVariantsByDuration('all', this)" class="duration-tab active-dur-tab px-3 py-1 rounded-md text-xs font-mono font-bold bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 whitespace-nowrap transition-all cursor-pointer">
                        Semua
                    </button>
                    <button type="button" onclick="filterVariantsByDuration('1hari', this)" class="duration-tab px-3 py-1 rounded-md text-xs font-mono font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white border border-zinc-200 dark:border-zinc-700 whitespace-nowrap cursor-pointer">
                        1 Hari
                    </button>
                    <button type="button" onclick="filterVariantsByDuration('3hari', this)" class="duration-tab px-3 py-1 rounded-md text-xs font-mono font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white border border-zinc-200 dark:border-zinc-700 whitespace-nowrap cursor-pointer">
                        3 Hari
                    </button>
                    <button type="button" onclick="filterVariantsByDuration('7hari', this)" class="duration-tab px-3 py-1 rounded-md text-xs font-mono font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white border border-zinc-200 dark:border-zinc-700 whitespace-nowrap cursor-pointer">
                        7 Hari
                    </button>
                    <button type="button" onclick="filterVariantsByDuration('bulan', this)" class="duration-tab px-3 py-1 rounded-md text-xs font-mono font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white border border-zinc-200 dark:border-zinc-700 whitespace-nowrap cursor-pointer">
                        1 Bulan
                    </button>
                </div>
            @endif

            <!-- Variant Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 sm:gap-3" id="variant-container">
                @foreach($product->variants as $variant)
                    @php
                        $strikethrough = round($variant->price * 1.15, -2);
                        $lowerName = strtolower($variant->name);

                        $badgeIcon = '⚡';
                        $durationChip = null;
                        $filterTag = 'other';

                        if (str_contains($lowerName, 'dp') || str_contains($lowerName, 'uang muka')) {
                            $badgeIcon = '💳';
                            $durationChip = 'DP';
                            $filterTag = 'dp';
                        } elseif (str_contains($lowerName, 'lunas') || str_contains($lowerName, 'pelunasan') || str_contains($lowerName, 'full')) {
                            $badgeIcon = '✓';
                            $durationChip = 'Lunas';
                            $filterTag = 'lunas';
                        } elseif (str_contains($lowerName, '1 hari') || str_contains($lowerName, '1d')) {
                            $badgeIcon = '⏱️';
                            $durationChip = '1 Hari';
                            $filterTag = '1hari';
                        } elseif (str_contains($lowerName, '3 hari') || str_contains($lowerName, '3d')) {
                            $badgeIcon = '⚡';
                            $durationChip = '3 Hari';
                            $filterTag = '3hari';
                        } elseif (str_contains($lowerName, '7 hari') || str_contains($lowerName, '7d') || str_contains($lowerName, 'minggu')) {
                            $badgeIcon = '🔥';
                            $durationChip = '7 Hari';
                            $filterTag = '7hari';
                        } elseif (str_contains($lowerName, 'bulan') || str_contains($lowerName, 'bln') || str_contains($lowerName, '1m')) {
                            $badgeIcon = '📅';
                            $durationChip = '1 Bulan';
                            $filterTag = 'bulan';
                        }
                    @endphp
                    <label class="cursor-pointer relative block p-3 rounded-lg border border-zinc-200 dark:border-zinc-800 hover:border-zinc-400 dark:hover:border-zinc-600 bg-white dark:bg-zinc-900/60 transition-all variant-card has-[:checked]:border-zinc-900 dark:has-[:checked]:border-zinc-100 has-[:checked]:ring-1 has-[:checked]:ring-zinc-900 dark:has-[:checked]:ring-zinc-100 has-[:checked]:bg-zinc-50 dark:has-[:checked]:bg-zinc-800/60 group" data-filter-tag="{{ $filterTag }}">
                        <input type="radio" name="variant_id" value="{{ $variant->id }}" data-price="{{ $variant->price }}" data-name="{{ $variant->name }}" class="hidden variant-input" {{ $loop->first ? 'checked' : '' }} required>
                        
                        <div class="flex flex-col h-full justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-1 mb-1.5">
                                    <div class="flex items-center gap-1">
                                        <span class="text-xs">{{ $badgeIcon }}</span>
                                        @if($durationChip)
                                            <span class="text-[9px] font-mono px-1 py-0.2 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 uppercase">
                                                {{ $durationChip }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($variant->stock_type !== 'unlimited')
                                        @if($variant->stock > 0)
                                            <span class="text-[9px] font-mono text-emerald-600 dark:text-emerald-400">
                                                Stok: {{ $variant->stock }}
                                            </span>
                                        @else
                                            <span class="text-[9px] font-mono text-rose-500">
                                                Habis
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                <span class="text-xs font-bold text-zinc-900 dark:text-zinc-100 block group-hover:text-zinc-700 dark:group-hover:text-white transition-colors line-clamp-2 leading-snug">
                                    {{ $variant->name }}
                                </span>
                            </div>

                            <div class="mt-2.5 pt-1.5 border-t border-zinc-100 dark:border-zinc-800/80">
                                <span class="text-[9px] font-mono text-zinc-400 line-through block">Rp {{ number_format($strikethrough, 0, ',', '.') }}</span>
                                <span class="text-xs font-bold text-zinc-900 dark:text-zinc-100 block font-mono tabular-nums">
                                    Rp {{ number_format($variant->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Dot Indicator -->
                        <div class="hidden group-has-[:checked]:block absolute top-2 right-2 w-2 h-2 rounded-full bg-zinc-900 dark:bg-zinc-100"></div>
                    </label>
                @endforeach
            </div>
            @error('variant_id')
                <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- STEP 3: PILIH METODE PEMBAYARAN -->
        <div class="bg-white dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-center gap-2.5 mb-4">
                <span class="w-6 h-6 rounded bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-mono font-bold text-xs flex items-center justify-center flex-shrink-0">3</span>
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white font-mono uppercase tracking-wider">Metode Pembayaran</h3>
                    <p class="text-xs text-zinc-500">Pilih saluran pembayaran otomatis QRIS atau Transfer Bank.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                @foreach($paymentChannels as $channel)
                    @php 
                        $fee = $channel['fee_customer']['flat'] ?? 0;
                    @endphp
                    <label class="cursor-pointer relative p-3 rounded-lg border border-zinc-200 dark:border-zinc-800 hover:border-zinc-400 dark:hover:border-zinc-600 bg-white dark:bg-zinc-900/60 transition-all payment-card has-[:checked]:border-zinc-900 dark:has-[:checked]:border-zinc-100 has-[:checked]:ring-1 has-[:checked]:ring-zinc-900 dark:has-[:checked]:ring-zinc-100 has-[:checked]:bg-zinc-50 dark:has-[:checked]:bg-zinc-800/60 group">
                        <input type="radio" name="payment_method" value="{{ $channel['code'] }}" data-name="{{ $channel['name'] }}" data-fee="{{ $fee }}" class="hidden payment-input" {{ $loop->first ? 'checked' : '' }} required>
                        
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-md bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center font-mono font-bold text-[10px] text-zinc-800 dark:text-zinc-200 flex-shrink-0">
                                    {{ substr($channel['code'], 0, 4) }}
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-zinc-900 dark:text-white block group-hover:text-zinc-700 dark:group-hover:text-white transition-colors">
                                        {{ $channel['name'] }}
                                    </span>
                                    <span class="text-[10px] font-mono text-zinc-500 block">
                                        ⚡ Otomatis 1-3 Detik
                                    </span>
                                </div>
                            </div>

                            <div class="text-right font-mono">
                                <span class="text-[11px] font-semibold text-zinc-600 dark:text-zinc-400 block channel-calculated-price">
                                    {{ $fee > 0 ? '+ Rp '.number_format($fee, 0, ',', '.') : 'Bebas Biaya' }}
                                </span>
                            </div>
                        </div>

                        <!-- Dot Indicator -->
                        <div class="hidden group-has-[:checked]:block absolute top-2 right-2 w-2 h-2 rounded-full bg-zinc-900 dark:bg-zinc-100"></div>
                    </label>
                @endforeach
            </div>
            @error('payment_method')
                <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- STEP 4: MASUKKAN KONTAK WHATSAPP & EMAIL -->
        <div class="bg-white dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-center gap-2.5 mb-4">
                <span class="w-6 h-6 rounded bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-mono font-bold text-xs flex items-center justify-center flex-shrink-0">4</span>
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white font-mono uppercase tracking-wider">Informasi Kontak</h3>
                    <p class="text-xs text-zinc-500">Bukti pembayaran dan status transaksi dikirim ke kontak ini.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">No. WhatsApp <span class="text-rose-500">*</span></label>
                    <input type="tel" name="customer_phone" id="input_phone" value="{{ old('customer_phone', auth()->user()?->phone) }}" required placeholder="08xxxxxxxxxx" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                </div>

                <div>
                    <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Alamat Email Aktif <span class="text-rose-500">*</span></label>
                    <input type="email" name="customer_email" id="input_email" value="{{ old('customer_email', auth()->user()?->email) }}" required placeholder="nama@email.com" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="customer_name" id="input_name" value="{{ old('customer_name', auth()->user()?->name) }}" required placeholder="Nama Anda" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-zinc-500">
                </div>
            </div>
        </div>

        <!-- STEP 5: KODE PROMO & RINGKASAN PEMBELIAN -->
        <div class="bg-white dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-center gap-2.5 mb-4">
                <span class="w-6 h-6 rounded bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-mono font-bold text-xs flex items-center justify-center flex-shrink-0">5</span>
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white font-mono uppercase tracking-wider">Voucher &amp; Ringkasan</h3>
                    <p class="text-xs text-zinc-500">Gunakan voucher promo untuk mendapatkan potongan harga.</p>
                </div>
            </div>

            <!-- Applied Voucher Hidden Input -->
            <input type="hidden" name="voucher_code" id="applied_voucher_code" value="{{ old('voucher_code') }}">

            <!-- Promo Code Command Bar Input -->
            <div class="flex gap-2 mb-3">
                <input type="text" id="promo-code-input" placeholder="Masukkan Kode Voucher (Contoh: HEMAT10)" class="flex-1 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-900 dark:text-white uppercase font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                <button type="button" id="btn-apply-promo" onclick="applyPromoCode()" class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 text-xs font-mono font-bold hover:bg-zinc-800 dark:hover:bg-white transition-all cursor-pointer">
                    Terapkan
                </button>
            </div>
            
            <!-- Promo Feedback Message -->
            <div id="promo-message" class="hidden text-xs font-mono mb-4 p-2.5 rounded-lg border"></div>

            <!-- Linear Summary Table -->
            <div class="space-y-2 text-xs font-mono text-zinc-600 dark:text-zinc-400 pb-3 border-b border-zinc-100 dark:border-zinc-800">
                <div class="flex justify-between">
                    <span>Layanan</span>
                    <span class="font-bold text-zinc-900 dark:text-white">{{ $product->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Item Nominal</span>
                    <span class="font-bold text-zinc-900 dark:text-white" id="summary-variant">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Metode Bayar</span>
                    <span class="text-zinc-900 dark:text-white" id="summary-payment">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Harga Item</span>
                    <span class="text-zinc-900 dark:text-white tabular-nums" id="summary-price">Rp 0</span>
                </div>
                <div class="hidden justify-between text-emerald-600 dark:text-emerald-400 font-bold" id="summary-discount-row">
                    <span class="flex items-center gap-1">
                        <span>🎟️ Diskon Voucher</span>
                        <span class="text-[10px] px-1 py-0.2 rounded bg-emerald-500/10 border border-emerald-500/20" id="summary-discount-code">-</span>
                    </span>
                    <span id="summary-discount" class="tabular-nums">- Rp 0</span>
                </div>
                <div class="flex justify-between">
                    <span>Biaya Layanan</span>
                    <span class="text-zinc-500 tabular-nums" id="summary-fee">Rp 0</span>
                </div>
            </div>

            <div class="pt-3 flex items-center justify-between mb-5">
                <span class="text-xs font-mono uppercase tracking-wider text-zinc-500">Total Pembayaran</span>
                <span class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-zinc-100 font-mono tabular-nums" id="summary-total">Rp 0</span>
            </div>

            <button type="button" onclick="openOrderConfirmModal()" class="w-full py-3.5 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-bold font-mono text-xs hover:bg-zinc-800 dark:hover:bg-white transition-all flex items-center justify-center gap-2 shadow-sm">
                <span>Beli Sekarang ⚡</span>
            </button>
        </div>

    </form>
</div>

<!-- STICKY FLOATING BOTTOM SUMMARY DOCK -->
<div class="fixed bottom-0 left-0 right-0 z-40 bg-white/90 dark:bg-zinc-950/90 backdrop-blur-md border-t border-zinc-200 dark:border-zinc-800 p-3 shadow-lg" style="padding-bottom: max(0.5rem, env(safe-area-inset-bottom));">
    <div class="max-w-4xl mx-auto flex items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5">
                <span class="text-[11px] text-zinc-500 font-mono hidden sm:inline">Item:</span>
                <span class="text-xs font-bold text-zinc-900 dark:text-white truncate max-w-[150px] sm:max-w-xs block font-mono" id="sticky-variant">-</span>
            </div>
            <div class="flex items-center gap-1.5 mt-0.5">
                <span class="text-[10px] font-mono text-zinc-500 uppercase">Total:</span>
                <span class="text-sm sm:text-base font-bold text-zinc-900 dark:text-white font-mono tabular-nums" id="sticky-total">Rp 0</span>
            </div>
        </div>

        <button type="button" onclick="openOrderConfirmModal()" class="px-5 py-2 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-mono font-bold text-xs hover:bg-zinc-800 dark:hover:bg-white transition-all flex items-center gap-1.5 flex-shrink-0 shadow-sm">
            <span>Beli Sekarang ⚡</span>
        </button>
    </div>
</div>

<!-- MODAL: ORDER CONFIRMATION -->
<div id="modal-order-confirm" class="hidden fixed inset-0 z-50 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 max-w-md w-full shadow-2xl">
        <div class="pb-3 border-b border-zinc-100 dark:border-zinc-800 mb-4">
            <h3 class="text-sm font-bold text-zinc-900 dark:text-white font-mono">Konfirmasi Data Pesanan</h3>
            <p class="text-xs text-zinc-500 mt-0.5">
                @if($product->requires_user_id)
                    Harap pastikan User ID dan Server ID sudah benar sebelum lanjut ke pembayaran.
                @else
                    Periksa kembali pilihan variasi dan kontak Anda.
                @endif
            </p>
        </div>

        <div class="space-y-2 text-xs font-mono bg-zinc-50 dark:bg-zinc-950 p-3 rounded-xl border border-zinc-200 dark:border-zinc-800 mb-4">
            <div class="flex justify-between">
                <span class="text-zinc-500">Layanan:</span>
                <span class="font-bold text-zinc-900 dark:text-white">{{ $product->name }}</span>
            </div>
            @if($product->isPreorder())
                <div class="flex justify-between">
                    <span class="text-zinc-500">Ukuran:</span>
                    <span class="font-bold text-zinc-900 dark:text-white" id="confirm-size">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-zinc-500">Lengan:</span>
                    <span class="font-bold text-zinc-900 dark:text-white" id="confirm-sleeve">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-zinc-500">Bordir:</span>
                    <span class="font-bold text-zinc-900 dark:text-white" id="confirm-embroidery">-</span>
                </div>
            @endif
            @if($product->requires_user_id)
                <div class="flex justify-between">
                    <span class="text-zinc-500">{{ $product->user_id_label }}:</span>
                    <span class="font-bold text-zinc-900 dark:text-white" id="confirm-user-id">-</span>
                </div>
            @endif
            @if($product->requires_zone_id)
                <div class="flex justify-between">
                    <span class="text-zinc-500">{{ $product->zone_id_label }}:</span>
                    <span class="font-bold text-zinc-900 dark:text-white" id="confirm-zone-id">-</span>
                </div>
            @endif
            <div class="flex justify-between">
                <span class="text-zinc-500">Varian:</span>
                <span class="font-bold text-zinc-900 dark:text-white" id="confirm-variant">-</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500">Metode Bayar:</span>
                <span class="text-zinc-900 dark:text-white" id="confirm-payment">-</span>
            </div>
            <div class="hidden justify-between text-emerald-600 dark:text-emerald-400 font-bold" id="confirm-discount-row">
                <span>Diskon:</span>
                <span id="confirm-discount" class="tabular-nums">- Rp 0</span>
            </div>
            <div class="flex justify-between pt-2 border-t border-zinc-200 dark:border-zinc-800">
                <span class="text-zinc-700 dark:text-zinc-300 font-bold">Total Tagihan:</span>
                <span class="font-bold text-zinc-900 dark:text-white tabular-nums" id="confirm-total">Rp 0</span>
            </div>
        </div>

        <div class="flex gap-2">
            <button type="button" onclick="document.getElementById('modal-order-confirm').classList.add('hidden')" class="flex-1 py-2.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-mono text-xs transition-all">
                Ubah Data
            </button>
            <button type="button" onclick="submitFinalOrder()" id="btn-final-submit" class="flex-1 py-2.5 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-bold font-mono text-xs hover:bg-zinc-800 dark:hover:bg-white transition-all flex items-center justify-center gap-1.5 shadow-sm">
                <span>Bayar Sekarang →</span>
            </button>
        </div>
    </div>
</div>

<!-- MODAL: PETUNJUK USER ID GAME -->
<div id="modal-id-guide" class="hidden fixed inset-0 z-50 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 max-w-lg w-full shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800 mb-4">
            <h3 class="text-sm font-bold text-zinc-900 dark:text-white font-mono">Petunjuk User ID Game</h3>
            <button type="button" onclick="document.getElementById('modal-id-guide').classList.add('hidden')" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white text-sm font-mono">✕</button>
        </div>

        <div class="space-y-3 text-xs text-zinc-600 dark:text-zinc-400">
            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 font-mono">
                <h4 class="font-bold text-zinc-900 dark:text-white mb-1">🎮 Mobile Legends</h4>
                <p>Buka profil pemain di pojok kiri atas. User ID dan Zone ID tertera di bawah avatar Anda. Contoh: <code class="text-zinc-900 dark:text-zinc-200">12345678 (2024)</code>.</p>
            </div>
            <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 font-mono">
                <h4 class="font-bold text-zinc-900 dark:text-white mb-1">🔥 Free Fire</h4>
                <p>Klik profil di pojok kiri atas. UID tertera di samping nickname Anda. Salin dan tempelkan pada kolom User ID.</p>
            </div>
        </div>

        <div class="mt-4 flex justify-end">
            <button type="button" onclick="document.getElementById('modal-id-guide').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 text-xs font-mono font-bold">
                Mengerti
            </button>
        </div>
    </div>
</div>

<!-- MODAL: SIZE CHART PREORDER PDH -->
<div id="modal-size-chart" class="hidden fixed inset-0 z-50 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 max-w-xl w-full shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800 mb-4">
            <div>
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white font-mono">Panduan Ukuran Baju (Size Chart) PDH</h3>
                <p class="text-[11px] text-zinc-500">Satuan centimeter (cm). Toleransi 1-2 cm.</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-size-chart').classList.add('hidden')" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white text-sm font-mono cursor-pointer">✕</button>
        </div>

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-800 mb-3">
            <table class="w-full text-left text-xs font-mono text-zinc-700 dark:text-zinc-300">
                <thead class="bg-zinc-50 dark:bg-zinc-950 uppercase text-[10px] border-b border-zinc-200 dark:border-zinc-800">
                    <tr>
                        <th class="py-2 px-3 font-bold">Size</th>
                        <th class="py-2 px-3 font-bold">Lebar Dada</th>
                        <th class="py-2 px-3 font-bold">Panjang</th>
                        <th class="py-2 px-3 font-bold">Lengan Pendek</th>
                        <th class="py-2 px-3 font-bold">Lengan Panjang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    <tr><td class="py-2 px-3 font-bold">S</td><td class="py-2 px-3">48 cm</td><td class="py-2 px-3">66 cm</td><td class="py-2 px-3">23 cm</td><td class="py-2 px-3">57 cm</td></tr>
                    <tr><td class="py-2 px-3 font-bold">M</td><td class="py-2 px-3">50 cm</td><td class="py-2 px-3">68 cm</td><td class="py-2 px-3">24 cm</td><td class="py-2 px-3">59 cm</td></tr>
                    <tr><td class="py-2 px-3 font-bold">L</td><td class="py-2 px-3">52 cm</td><td class="py-2 px-3">70 cm</td><td class="py-2 px-3">25 cm</td><td class="py-2 px-3">61 cm</td></tr>
                    <tr><td class="py-2 px-3 font-bold">XL</td><td class="py-2 px-3">54 cm</td><td class="py-2 px-3">72 cm</td><td class="py-2 px-3">26 cm</td><td class="py-2 px-3">63 cm</td></tr>
                    <tr><td class="py-2 px-3 font-bold">XXL</td><td class="py-2 px-3">56 cm</td><td class="py-2 px-3">74 cm</td><td class="py-2 px-3">27 cm</td><td class="py-2 px-3">65 cm</td></tr>
                    <tr><td class="py-2 px-3 font-bold">3XL</td><td class="py-2 px-3">58 cm</td><td class="py-2 px-3">76 cm</td><td class="py-2 px-3">28 cm</td><td class="py-2 px-3">67 cm</td></tr>
                </tbody>
            </table>
        </div>

        <div class="flex justify-end">
            <button type="button" onclick="document.getElementById('modal-size-chart').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 text-xs font-mono font-bold cursor-pointer">
                Tutup Panduan
            </button>
        </div>
    </div>
</div>

<!-- Fullscreen Lightbox Modal -->
<div id="screenshot-lightbox" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 backdrop-blur-md p-4 transition-all duration-200">
    <div class="relative max-w-4xl w-full max-h-[90vh] flex flex-col items-center">
        <div class="w-full flex items-center justify-between pb-2 text-white text-xs font-mono">
            <span id="lightbox-counter" class="px-2.5 py-1 rounded bg-zinc-800 border border-zinc-700">Foto 1 dari {{ count($galleryUrls) }}</span>
            <button type="button" id="lightbox-close" class="p-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white transition-all text-xs font-mono border border-zinc-700">
                ✕ Tutup
            </button>
        </div>
        <div class="relative w-full max-h-[80vh] flex items-center justify-center overflow-hidden rounded-xl bg-black border border-zinc-800">
            <img id="lightbox-img" src="" alt="Screenshot Akun Zoom" class="max-w-full max-h-[76vh] object-contain">
            @if(count($galleryUrls) > 1)
                <button type="button" id="lightbox-prev" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-lg bg-zinc-900/80 hover:bg-zinc-800 text-white flex items-center justify-center border border-zinc-700 text-lg transition-all">‹</button>
                <button type="button" id="lightbox-next" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-lg bg-zinc-900/80 hover:bg-zinc-800 text-white flex items-center justify-center border border-zinc-700 text-lg transition-all">›</button>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    let appliedVoucher = {
        code: '',
        name: '',
        discountAmount: 0
    };

    function formatRupiah(amount) {
        return 'Rp ' + Math.round(amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function updateCalculation() {
        let price = 0;
        let variantName = '-';
        let fee = 0;
        let paymentName = '-';

        const summaryVariant = document.getElementById('summary-variant');
        const summaryPayment = document.getElementById('summary-payment');
        const summaryPrice = document.getElementById('summary-price');
        const summaryFee = document.getElementById('summary-fee');
        const summaryTotal = document.getElementById('summary-total');

        const summaryDiscountRow = document.getElementById('summary-discount-row');
        const summaryDiscountCode = document.getElementById('summary-discount-code');
        const summaryDiscount = document.getElementById('summary-discount');
        const confirmDiscountRow = document.getElementById('confirm-discount-row');
        const confirmDiscount = document.getElementById('confirm-discount');

        const stickyVariant = document.getElementById('sticky-variant');
        const stickyTotal = document.getElementById('sticky-total');

        const selectedVariant = document.querySelector('.variant-input:checked');
        if (selectedVariant) {
            price = parseFloat(selectedVariant.dataset.price) || 0;
            variantName = selectedVariant.dataset.name || '-';
        }

        const selectedPayment = document.querySelector('.payment-input:checked');
        if (selectedPayment) {
            fee = parseFloat(selectedPayment.dataset.fee) || 0;
            paymentName = selectedPayment.dataset.name || selectedPayment.value;
        }

        const discount = appliedVoucher.discountAmount || 0;
        const payableSubtotal = Math.max(0, price - discount);
        const total = payableSubtotal + fee;

        if (summaryVariant) summaryVariant.textContent = variantName;
        if (summaryPayment) summaryPayment.textContent = paymentName;
        if (summaryPrice) summaryPrice.textContent = formatRupiah(price);
        if (summaryFee) summaryFee.textContent = formatRupiah(fee);

        if (discount > 0) {
            if (summaryDiscountRow) {
                summaryDiscountRow.classList.remove('hidden');
                summaryDiscountRow.classList.add('flex');
            }
            if (summaryDiscountCode) summaryDiscountCode.textContent = appliedVoucher.code;
            if (summaryDiscount) summaryDiscount.textContent = '- ' + formatRupiah(discount);

            if (confirmDiscountRow) {
                confirmDiscountRow.classList.remove('hidden');
                confirmDiscountRow.classList.add('flex');
            }
            if (confirmDiscount) confirmDiscount.textContent = '- ' + formatRupiah(discount);
        } else {
            if (summaryDiscountRow) {
                summaryDiscountRow.classList.add('hidden');
                summaryDiscountRow.classList.remove('flex');
            }
            if (confirmDiscountRow) {
                confirmDiscountRow.classList.add('hidden');
                confirmDiscountRow.classList.remove('flex');
            }
        }

        if (summaryTotal) summaryTotal.textContent = formatRupiah(total);
        if (stickyVariant) stickyVariant.textContent = variantName;
        if (stickyTotal) stickyTotal.textContent = formatRupiah(total);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const variantInputs = document.querySelectorAll('.variant-input');
        const paymentInputs = document.querySelectorAll('.payment-input');

        variantInputs.forEach(input => {
            input.addEventListener('change', () => {
                if (appliedVoucher.code) {
                    applyPromoCode();
                } else {
                    updateCalculation();
                }
            });
        });
        paymentInputs.forEach(input => input.addEventListener('change', updateCalculation));

        updateCalculation();

        // Gallery & Lightbox Logic
        const galleryUrls = @json($galleryUrls ?? []);
        let activeGalleryIndex = 0;
        const mainImage = document.getElementById('gallery-main-image');
        const mainContainer = document.getElementById('gallery-main-container');
        const btnZoomOverlay = document.getElementById('btn-zoom-overlay');
        const thumbButtons = document.querySelectorAll('.gallery-thumb-btn');

        const lightbox = document.getElementById('screenshot-lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        const lightboxClose = document.getElementById('lightbox-close');
        const lightboxCounter = document.getElementById('lightbox-counter');
        const lightboxPrev = document.getElementById('lightbox-prev');
        const lightboxNext = document.getElementById('lightbox-next');

        function setActiveImage(index) {
            if (!galleryUrls.length) return;
            activeGalleryIndex = (index + galleryUrls.length) % galleryUrls.length;
            const newUrl = galleryUrls[activeGalleryIndex];

            if (mainImage) mainImage.src = newUrl;

            thumbButtons.forEach((btn, idx) => {
                if (idx === activeGalleryIndex) {
                    btn.classList.add('border-zinc-900', 'dark:border-zinc-100', 'ring-1', 'ring-zinc-900', 'dark:ring-zinc-100');
                    btn.classList.remove('border-zinc-200', 'dark:border-zinc-800', 'opacity-60');
                } else {
                    btn.classList.remove('border-zinc-900', 'dark:border-zinc-100', 'ring-1', 'ring-zinc-900', 'dark:ring-zinc-100');
                    btn.classList.add('border-zinc-200', 'dark:border-zinc-800', 'opacity-60');
                }
            });

            if (lightbox && !lightbox.classList.contains('hidden')) {
                lightboxImg.src = newUrl;
                if (lightboxCounter) {
                    lightboxCounter.textContent = `Foto ${activeGalleryIndex + 1} dari ${galleryUrls.length}`;
                }
            }
        }

        thumbButtons.forEach((btn, idx) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                setActiveImage(idx);
            });
        });

        function openLightbox() {
            if (!galleryUrls.length || !lightbox) return;
            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');
            lightboxImg.src = galleryUrls[activeGalleryIndex];
            if (lightboxCounter) lightboxCounter.textContent = `Foto ${activeGalleryIndex + 1} dari ${galleryUrls.length}`;
            document.body.classList.add('overflow-hidden');
        }

        function closeLightbox() {
            if (!lightbox) return;
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        if (mainContainer) mainContainer.addEventListener('click', openLightbox);
        if (btnZoomOverlay) btnZoomOverlay.addEventListener('click', (e) => { e.stopPropagation(); openLightbox(); });
        if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
        if (lightboxPrev) lightboxPrev.addEventListener('click', (e) => { e.stopPropagation(); setActiveImage(activeGalleryIndex - 1); });
        if (lightboxNext) lightboxNext.addEventListener('click', (e) => { e.stopPropagation(); setActiveImage(activeGalleryIndex + 1); });
        if (lightbox) lightbox.addEventListener('click', (e) => { if (e.target === lightbox) closeLightbox(); });

        document.addEventListener('keydown', (e) => {
            if (lightbox && !lightbox.classList.contains('hidden')) {
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft') setActiveImage(activeGalleryIndex - 1);
                if (e.key === 'ArrowRight') setActiveImage(activeGalleryIndex + 1);
            }
        });
    });

    // Duration Filter Tabs
    function filterVariantsByDuration(tag, element) {
        const tabs = document.querySelectorAll('.duration-tab');
        tabs.forEach(tab => {
            tab.className = 'duration-tab px-3 py-1 rounded-md text-xs font-mono font-bold bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white border border-zinc-200 dark:border-zinc-700 whitespace-nowrap cursor-pointer';
        });
        if (element) {
            element.className = 'duration-tab active-dur-tab px-3 py-1 rounded-md text-xs font-mono font-bold bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 whitespace-nowrap transition-all cursor-pointer';
        }

        const cards = document.querySelectorAll('.variant-card');
        let firstVisibleInput = null;

        cards.forEach(card => {
            const cardTag = card.dataset.filterTag;
            if (tag === 'all' || cardTag === tag) {
                card.classList.remove('hidden');
                if (!firstVisibleInput) {
                    firstVisibleInput = card.querySelector('.variant-input');
                }
            } else {
                card.classList.add('hidden');
            }
        });

        const currentChecked = document.querySelector('.variant-input:checked');
        if (currentChecked && currentChecked.closest('.variant-card').classList.contains('hidden')) {
            if (firstVisibleInput) {
                firstVisibleInput.checked = true;
                firstVisibleInput.dispatchEvent(new Event('change'));
            }
        }
    }

    // Confirmation Modal Trigger
    function openOrderConfirmModal() {
        const form = document.getElementById('checkout-form');
        if (!form.reportValidity()) {
            return;
        }

        const inputUserId = document.getElementById('input_user_id');
        const inputZoneId = document.getElementById('input_zone_id');
        const confirmUserId = document.getElementById('confirm-user-id');
        const confirmZoneId = document.getElementById('confirm-zone-id');

        if (confirmUserId && inputUserId) confirmUserId.textContent = inputUserId.value;
        if (confirmZoneId && inputZoneId) confirmZoneId.textContent = inputZoneId.value;

        // Preorder specific fields
        const confirmSize = document.getElementById('confirm-size');
        const confirmSleeve = document.getElementById('confirm-sleeve');
        const confirmEmbroidery = document.getElementById('confirm-embroidery');

        if (confirmSize) {
            const sizeSelect = form.querySelector('select[name="target_user_id"]');
            confirmSize.textContent = sizeSelect ? (sizeSelect.value || '-') : '-';
        }
        if (confirmSleeve) {
            const sleeveSelect = form.querySelector('select[name="target_zone_id"]');
            confirmSleeve.textContent = sleeveSelect ? (sleeveSelect.value || '-') : '-';
        }
        if (confirmEmbroidery) {
            const embInput = form.querySelector('input[name="target_nickname"]');
            const embVal = embInput ? embInput.value.trim() : '';
            confirmEmbroidery.textContent = embVal || '(Tanpa Bordir Nama)';
        }

        const selVar = document.querySelector('.variant-input:checked');
        const selPay = document.querySelector('.payment-input:checked');
        
        if (selVar) document.getElementById('confirm-variant').textContent = selVar.dataset.name;
        if (selPay) document.getElementById('confirm-payment').textContent = selPay.dataset.name || selPay.value;
        document.getElementById('confirm-total').textContent = document.getElementById('summary-total').textContent;

        document.getElementById('modal-order-confirm').classList.remove('hidden');
    }

    function submitFinalOrder() {
        const btn = document.getElementById('btn-final-submit');
        btn.disabled = true;
        btn.innerHTML = '<span>Memproses...</span> ⏳';
        document.getElementById('checkout-form').submit();
    }

    async function applyPromoCode() {
        const input = document.getElementById('promo-code-input');
        const code = input ? input.value.trim().toUpperCase() : '';
        const msg = document.getElementById('promo-message');
        const btn = document.getElementById('btn-apply-promo');
        const appliedVoucherInput = document.getElementById('applied_voucher_code');

        if (!code) {
            alert('Silakan masukkan kode voucher terlebih dahulu!');
            return;
        }

        const selectedVariant = document.querySelector('.variant-input:checked');
        if (!selectedVariant) {
            alert('Silakan pilih nominal produk terlebih dahulu.');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Memeriksa...';

        try {
            const response = await fetch("{{ route('vouchers.check') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    code: code,
                    variant_id: selectedVariant.value,
                    quantity: 1
                })
            });

            const data = await response.json();

            if (response.status === 401 || data.requires_login) {
                removePromoCode(false);
                msg.className = 'text-xs mb-3 p-2.5 rounded-lg border bg-amber-500/10 border-amber-500/20 text-amber-500';
                msg.innerHTML = `⚠️ ${data.message} <a href="{{ route('login') }}" class="underline font-bold text-zinc-900 dark:text-white ml-1">Login ↗</a>`;
                msg.classList.remove('hidden');
                return;
            }

            if (response.ok && data.valid) {
                appliedVoucher = {
                    code: data.code,
                    name: data.name,
                    discountAmount: parseFloat(data.discount_amount) || 0
                };
                if (appliedVoucherInput) appliedVoucherInput.value = data.code;

                msg.className = 'text-xs mb-3 p-2.5 rounded-lg border bg-emerald-500/10 border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-between';
                msg.innerHTML = `
                    <span>✓ ${data.message}</span>
                    <button type="button" onclick="removePromoCode()" class="text-rose-500 hover:underline font-bold ml-2 text-[10px] cursor-pointer">Hapus</button>
                `;
                msg.classList.remove('hidden');

                updateCalculation();
            } else {
                removePromoCode(false);
                msg.className = 'text-xs mb-3 p-2.5 rounded-lg border bg-rose-500/10 border-rose-500/20 text-rose-500';
                msg.textContent = '⚠️ ' + (data.message || 'Kode voucher tidak valid.');
                msg.classList.remove('hidden');
            }
        } catch (err) {
            console.error('Voucher check error:', err);
            removePromoCode(false);
            msg.className = 'text-xs mb-3 p-2.5 rounded-lg border bg-rose-500/10 border-rose-500/20 text-rose-500';
            msg.textContent = '⚠️ Gagal memeriksa kode voucher.';
            msg.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Terapkan';
        }
    }

    function removePromoCode(clearInput = true) {
        appliedVoucher = { code: '', name: '', discountAmount: 0 };
        const appliedVoucherInput = document.getElementById('applied_voucher_code');
        if (appliedVoucherInput) appliedVoucherInput.value = '';
        if (clearInput) {
            const input = document.getElementById('promo-code-input');
            if (input) input.value = '';
            const msg = document.getElementById('promo-message');
            if (msg) msg.classList.add('hidden');
        }
        updateCalculation();
    }
</script>
@endpush
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-zinc-800/80">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-zinc-200 transition-colors">Dashboard</a>
                <span class="text-zinc-600">/</span>
                <a href="{{ route('admin.products.index') }}" class="hover:text-zinc-200 transition-colors">Produk</a>
                <span class="text-zinc-600">/</span>
                <span class="text-zinc-200">Tambah Produk Baru</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Tambah Produk Digital & Layanan</h1>
            <p class="text-xs text-zinc-400 mt-1">Daftarkan produk baru, paket variasi harga, serta konfigurasi formulir tujuan.</p>
        </div>

        <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5 self-start sm:self-auto">
            <span>← Kembali ke Daftar</span>
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 1. Informasi Dasar Produk -->
        <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800 space-y-4">
            <div class="flex items-center gap-2 pb-3 border-b border-zinc-800">
                <svg class="w-4 h-4 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m7.5 4.27 9 5.15"/>
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                    <path d="m3.3 7 8.7 5 8.7-5"/>
                    <path d="M12 22V12"/>
                </svg>
                <h3 class="text-sm font-semibold text-white tracking-tight">Informasi Umum Produk</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nama Layanan / Produk <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Mobile Legends, Netflix Premium" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                    @error('name')
                        <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-medium text-zinc-300">Kategori &amp; Layanan <span class="text-rose-400">*</span></label>
                        <a href="{{ route('admin.categories.index') }}" target="_blank" class="text-[11px] text-zinc-400 hover:text-white transition-colors">
                            + Kelola Kategori ↗
                        </a>
                    </div>
                    <select name="category_id" id="category_select" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" data-type="{{ $category->type ?? 'game' }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ ucfirst($category->type ?? 'Umum') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Tipe Layanan (Otomatis Sinkron)</label>
                    <select name="type" id="product_type" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                        @foreach($types as $type)
                            <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-[10px] text-zinc-500 mt-1 block">Otomatis mendeteksi tipe layanan sesuai kategori yang dipilih.</span>
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Publisher / Vendor</label>
                    <input type="text" name="publisher" value="{{ old('publisher') }}" placeholder="Contoh: Moonton, Netflix, CV Konveksi" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Foto Sampul / Desain Mockup Utama</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full text-xs text-zinc-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700 cursor-pointer bg-zinc-900 border border-zinc-800 rounded-lg py-1 px-2.5">
                    <span class="text-[10px] text-zinc-500 mt-1 block">Format: JPG, PNG, atau WebP (Maksimal 5MB)</span>
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Galeri Foto Produk (Bisa Banyak Foto)</label>
                    <input type="file" name="gallery[]" multiple accept="image/*" class="w-full text-xs text-zinc-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700 cursor-pointer bg-zinc-900 border border-zinc-800 rounded-lg py-1 px-2.5">
                    <span class="text-[10px] text-zinc-500 mt-1 block">Detail foto produk tambahan, panduan ukuran cm, atau varian.</span>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Spesifikasi &amp; Detail Produk</label>
                    <textarea name="account_specs_raw" id="account_specs_input" rows="3" placeholder="Bahan Kain: Nagata Drill High Quality&#10;Pilihan Lengan: Pendek &amp; Panjang&#10;Estimasi Pengerjaan: 14 - 21 Hari&#10;Ukuran Tersedia: S, M, L, XL, XXL, 3XL" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs font-mono text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">{{ old('account_specs_raw') }}</textarea>
                    <span class="text-[10px] text-zinc-500 mt-1 block">Format per baris: <code class="text-zinc-400">Nama Spesifikasi: Nilai Spesifikasi</code></span>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Deskripsi Produk</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi ringkas mengenai layanan atau produk ini..." class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">{{ old('description') }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Panduan / Instruksi Pemesanan</label>
                    <textarea name="instructions" rows="2" placeholder="Contoh: Masukkan User ID dan Zone ID dengan benar sesuai profil dalam game." class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">{{ old('instructions') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 2. Konfigurasi Input Tujuan (User ID & Zone ID) -->
        <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800 space-y-4" id="target-config-card">
            <div class="flex items-center gap-2 pb-3 border-b border-zinc-800">
                <svg class="w-4 h-4 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="22" x2="18" y1="12" y2="12"/>
                    <line x1="6" x2="2" y1="12" y2="12"/>
                    <line x1="12" x2="12" y1="6" y2="2"/>
                    <line x1="12" x2="12" y1="22" y2="18"/>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-white tracking-tight">Konfigurasi Form Input Pembeli</h3>
                    <p class="text-[11px] text-zinc-400">Tentukan formulir target akun game yang wajib diisi pembeli saat checkout.</p>
                </div>
            </div>

            <div id="pdh-notice-box" class="hidden p-3.5 rounded-lg bg-zinc-800/80 border border-zinc-700/60 text-xs text-zinc-300">
                <div class="flex items-center gap-2 font-medium text-white mb-1">
                    <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                    <span>Mode Preorder PDH / Uniform Aktif</span>
                </div>
                <p class="text-[11px] text-zinc-400">Checkout otomatis menyertakan opsi Ukuran Baju (S - 3XL), Pilihan Lengan (Pendek/Panjang), dan Nama Bordir Dada.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="game-id-fields">
                <div class="p-3.5 rounded-lg bg-zinc-900 border border-zinc-800">
                    <label class="flex items-center gap-2 cursor-pointer mb-2.5">
                        <input type="checkbox" name="requires_user_id" id="requires_user_id_check" value="1" checked class="rounded bg-zinc-800 border-zinc-700 text-zinc-100 focus:ring-0">
                        <span class="text-xs font-medium text-white">Memerlukan User ID / Player ID</span>
                    </label>
                    <label class="block text-[11px] text-zinc-400 mb-1">Label Kolom User ID</label>
                    <input type="text" name="user_id_label" value="User ID / ID Pemain" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 focus:outline-none focus:border-zinc-700">
                </div>

                <div class="p-3.5 rounded-lg bg-zinc-900 border border-zinc-800">
                    <label class="flex items-center gap-2 cursor-pointer mb-2.5">
                        <input type="checkbox" name="requires_zone_id" id="requires_zone_id_check" value="1" class="rounded bg-zinc-800 border-zinc-700 text-zinc-100 focus:ring-0">
                        <span class="text-xs font-medium text-white">Memerlukan Zone ID / Server (Contoh: MLBB)</span>
                    </label>
                    <label class="block text-[11px] text-zinc-400 mb-1">Label Kolom Zone ID</label>
                    <input type="text" name="zone_id_label" value="Zone ID (4-5 Digit)" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 focus:outline-none focus:border-zinc-700">
                </div>
            </div>
        </div>

        <!-- 3. Variasi Paket / Nominal Harga -->
        <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-800">
                <div>
                    <h3 class="text-sm font-semibold text-white tracking-tight">Variasi Paket &amp; Nominal Harga</h3>
                    <p class="text-[11px] text-zinc-400">Tambahkan nominal diamond, paket durasi akun, atau opsi pembayaran preorder.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" onclick="loadPdhVariantPresets()" class="px-2.5 py-1 rounded-md bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white border border-zinc-700/60 text-xs font-medium transition-colors">
                        Preset Preorder PDH
                    </button>
                    <button type="button" onclick="loadAccountDurationPresets()" class="px-2.5 py-1 rounded-md bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white border border-zinc-700/60 text-xs font-medium transition-colors">
                        Preset Akun Streaming
                    </button>
                    <button type="button" id="add-variant-btn" class="px-3 py-1 rounded-md bg-white text-zinc-950 hover:bg-zinc-200 font-semibold text-xs shadow-sm transition-colors inline-flex items-center gap-1">
                        <span>+ Tambah Baris</span>
                    </button>
                </div>
            </div>

            <div id="variants-container" class="space-y-3">
                <div class="variant-row p-4 rounded-lg bg-zinc-900/80 border border-zinc-800 relative">
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-medium text-zinc-300 mb-1">Nama Variasi / Nominal <span class="text-rose-400">*</span></label>
                            <input type="text" name="variants[0][name]" placeholder="Contoh: 86 Diamonds / 1 Bulan" required class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 focus:outline-none focus:border-zinc-700">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-zinc-300 mb-1">SKU / Kode <span class="text-rose-400">*</span></label>
                            <input type="text" name="variants[0][sku]" placeholder="ML-86" required class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 font-mono focus:outline-none focus:border-zinc-700">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-zinc-300 mb-1">Harga Jual (Rp) <span class="text-rose-400">*</span></label>
                            <input type="number" name="variants[0][price]" placeholder="20000" required class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 font-mono tabular-nums focus:outline-none focus:border-zinc-700">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-zinc-300 mb-1">Tipe Stok <span class="text-rose-400">*</span></label>
                            <select name="variants[0][stock_type]" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 focus:outline-none focus:border-zinc-700">
                                <option value="unlimited" selected>Unlimited (Game)</option>
                                <option value="manual">Terbatas (Akun/Manual)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-800">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-medium transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 font-semibold text-xs shadow-sm transition-colors">
                Simpan &amp; Terbitkan Produk
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let variantIndex = 1;

    function addVariantRow(name = '', sku = '', price = '', stockType = 'unlimited', stock = '') {
        const container = document.getElementById('variants-container');
        const html = `
            <div class="variant-row p-4 rounded-lg bg-zinc-900/80 border border-zinc-800 relative mt-3">
                <button type="button" onclick="this.closest('.variant-row').remove()" class="absolute -top-2.5 -right-2.5 w-6 h-6 rounded-full bg-zinc-800 hover:bg-rose-900/80 border border-zinc-700 text-zinc-400 hover:text-rose-300 flex items-center justify-center text-xs transition-colors" title="Hapus baris">
                    ✕
                </button>
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">Nama Variasi / Nominal <span class="text-rose-400">*</span></label>
                        <input type="text" name="variants[${variantIndex}][name]" value="${name}" placeholder="Contoh: 172 Diamonds" required class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 focus:outline-none focus:border-zinc-700">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">SKU / Kode <span class="text-rose-400">*</span></label>
                        <input type="text" name="variants[${variantIndex}][sku]" value="${sku}" placeholder="ML-172" required class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 font-mono focus:outline-none focus:border-zinc-700">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">Harga Jual (Rp) <span class="text-rose-400">*</span></label>
                        <input type="number" name="variants[${variantIndex}][price]" value="${price}" placeholder="40000" required class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 font-mono tabular-nums focus:outline-none focus:border-zinc-700">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">Tipe Stok <span class="text-rose-400">*</span></label>
                        <select name="variants[${variantIndex}][stock_type]" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 focus:outline-none focus:border-zinc-700">
                            <option value="unlimited" ${stockType === 'unlimited' ? 'selected' : ''}>Unlimited (Game)</option>
                            <option value="manual" ${stockType === 'manual' ? 'selected' : ''}>Terbatas (Akun/Manual)</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        variantIndex++;
    }

    function loadPdhVariantPresets() {
        const container = document.getElementById('variants-container');
        const now = Date.now().toString().slice(-4);
        
        container.innerHTML = `
            <div class="variant-row p-4 rounded-lg bg-zinc-900/80 border border-zinc-800 relative">
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">Nama Variasi / Opsi Pembayaran <span class="text-rose-400">*</span></label>
                        <input type="text" name="variants[0][name]" value="DP (Uang Muka 50%)" required class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">SKU / Kode <span class="text-rose-400">*</span></label>
                        <input type="text" name="variants[0][sku]" value="PDH-DP-${now}" required class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 font-mono">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">Nominal DP (Rp) <span class="text-rose-400">*</span></label>
                        <input type="number" name="variants[0][price]" value="75000" required class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 font-mono tabular-nums">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">Tipe Stok <span class="text-rose-400">*</span></label>
                        <select name="variants[0][stock_type]" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100">
                            <option value="manual" selected>Terbatas (Manual)</option>
                            <option value="unlimited">Unlimited</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">Slot Preorder</label>
                        <input type="number" name="variants[0][stock]" min="0" value="100" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 font-mono tabular-nums">
                    </div>
                </div>
            </div>
        `;
        variantIndex = 1;
        addVariantRow('Lunas (Pembayaran Penuh 100%)', `PDH-LUNAS-${now}`, 150000, 'manual', 100);
        addVariantRow('Pelunasan Akhir (Sisa 50%)', `PDH-PELUNASAN-${now}`, 75000, 'manual', 100);

        const typeSelect = document.getElementById('product_type');
        if (typeSelect) {
            typeSelect.value = 'preorder';
            handleProductTypeChange('preorder');
        }

        const specsInput = document.getElementById('account_specs_input');
        if (specsInput && !specsInput.value.trim()) {
            specsInput.value = "Bahan Kain: Nagata Drill High Quality\nBordir: Komputer Presisi Tinggi (Dada, Lengan & Punggung)\nPilihan Lengan: Pendek & Panjang\nEstimasi Pengerjaan: 14 - 21 Hari Kerja\nUkuran Tersedia: S, M, L, XL, XXL, 3XL\nMinimal DP: Rp 75.000 / 50%";
        }
    }

    function loadAccountDurationPresets() {
        const container = document.getElementById('variants-container');
        container.innerHTML = `
            <div class="variant-row p-4 rounded-lg bg-zinc-900/80 border border-zinc-800 relative">
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">Nama Variasi / Nominal <span class="text-rose-400">*</span></label>
                        <input type="text" name="variants[0][name]" value="1 Hari - 1 Profil (Sharing)" required class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">SKU / Kode <span class="text-rose-400">*</span></label>
                        <input type="text" name="variants[0][sku]" value="NF-1D-${Date.now().toString().slice(-4)}" required class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 font-mono">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">Harga Jual (Rp) <span class="text-rose-400">*</span></label>
                        <input type="number" name="variants[0][price]" value="5000" required class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 font-mono tabular-nums">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">Tipe Stok <span class="text-rose-400">*</span></label>
                        <select name="variants[0][stock_type]" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100">
                            <option value="manual" selected>Terbatas (Akun/Manual)</option>
                            <option value="unlimited">Unlimited (Game)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-zinc-300 mb-1">Jumlah Stok Awal</label>
                        <input type="number" name="variants[0][stock]" min="0" value="20" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 font-mono tabular-nums">
                    </div>
                </div>
            </div>
        `;
        variantIndex = 1;
        const now = Date.now().toString().slice(-4);
        addVariantRow('3 Hari - 1 Profil (Sharing)', `NF-3D-${now}`, 12000, 'manual', 15);
        addVariantRow('7 Hari - 1 Profil (Sharing)', `NF-7D-${now}`, 22000, 'manual', 15);
        addVariantRow('1 Bulan - 1 Profil (Sharing)', `NF-1M-${now}`, 35000, 'manual', 10);
        addVariantRow('1 Bulan - Akun Private (5 Profil)', `NF-1MPRV-${now}`, 135000, 'manual', 5);

        const typeSelect = document.getElementById('product_type');
        if (typeSelect) {
            typeSelect.value = 'account';
            handleProductTypeChange('account');
        }
    }

    function handleProductTypeChange(type) {
        const pdhBox = document.getElementById('pdh-notice-box');
        const gameIdFields = document.getElementById('game-id-fields');
        const reqUser = document.getElementById('requires_user_id_check');
        const reqZone = document.getElementById('requires_zone_id_check');

        if (type === 'preorder') {
            if (pdhBox) pdhBox.classList.remove('hidden');
            if (gameIdFields) gameIdFields.classList.add('opacity-40');
            if (reqUser) reqUser.checked = false;
            if (reqZone) reqZone.checked = false;
        } else {
            if (pdhBox) pdhBox.classList.add('hidden');
            if (gameIdFields) gameIdFields.classList.remove('opacity-40');
            if (type === 'game') {
                if (reqUser) reqUser.checked = true;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const addBtn = document.getElementById('add-variant-btn');
        if (addBtn) {
            addBtn.addEventListener('click', () => addVariantRow());
        }

        const catSelect = document.getElementById('category_select');
        const typeSelect = document.getElementById('product_type');

        if (catSelect && typeSelect) {
            catSelect.addEventListener('change', function() {
                const selectedOpt = this.options[this.selectedIndex];
                const detectedType = selectedOpt.getAttribute('data-type');
                if (detectedType) {
                    typeSelect.value = detectedType;
                    handleProductTypeChange(detectedType);
                }
            });
        }

        if (typeSelect) {
            typeSelect.addEventListener('change', (e) => handleProductTypeChange(e.target.value));
            handleProductTypeChange(typeSelect.value);
        }
    });
</script>
@endpush
@endsection

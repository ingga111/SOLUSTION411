@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-zinc-800/80">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-zinc-200 transition-colors">Dashboard</a>
                <span class="text-zinc-600">/</span>
                <a href="{{ route('admin.products.index') }}" class="hover:text-zinc-200 transition-colors">Produk</a>
                <span class="text-zinc-600">/</span>
                <span class="text-zinc-200">Edit Produk</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Edit: {{ $product->name }}</h1>
            <p class="text-xs text-zinc-400 mt-1">Perbarui informasi layanan, kelola paket nominal harga, dan tambah stok akun digital.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('products.checkout', $product->slug) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <span>Lihat Checkout</span>
                <span class="text-zinc-500">↗</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white text-xs font-medium transition-colors">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Form Update Product -->
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800 space-y-4">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-2 pb-3 border-b border-zinc-800">
                    <svg class="w-4 h-4 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <h3 class="text-sm font-semibold text-white tracking-tight">Detail &amp; Pengaturan Produk</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nama Produk <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-medium text-zinc-300">Kategori <span class="text-rose-400">*</span></label>
                            <a href="{{ route('admin.categories.index') }}" target="_blank" class="text-[11px] text-zinc-400 hover:text-white transition-colors">
                                Kelola Kategori ↗
                            </a>
                        </div>
                        <select name="category_id" id="edit_category_id" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-type="{{ $category->type ?? 'game' }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ ucfirst($category->type ?? 'Umum') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-300 mb-1.5">Tipe Layanan (Sinkron)</label>
                        <select name="type" id="edit_product_type" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                            @foreach($types as $type)
                                <option value="{{ $type->value }}" {{ $product->type->value == $type->value ? 'selected' : '' }}>
                                    {{ $type->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-zinc-300 mb-1.5">Publisher / Vendor</label>
                        <input type="text" name="publisher" value="{{ old('publisher', $product->publisher) }}" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                    </div>

                    <!-- Images & Gallery -->
                    <div>
                        <label class="block text-xs font-medium text-zinc-300 mb-1.5">Foto Sampul Utama</label>
                        @if($product->getThumbnailUrl())
                            <div class="mb-2 w-32 aspect-video rounded-lg overflow-hidden border border-zinc-800 bg-zinc-950">
                                <img src="{{ $product->getThumbnailUrl() }}" alt="Current thumbnail" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <input type="file" name="thumbnail" accept="image/*" class="w-full text-xs text-zinc-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700 cursor-pointer bg-zinc-900 border border-zinc-800 rounded-lg py-1 px-2">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-zinc-300 mb-1.5">Tambah Foto Galeri</label>
                        @if(!empty($product->getGalleryUrls()))
                            <div class="flex items-center gap-1.5 mb-2 overflow-x-auto pb-1">
                                @foreach($product->getGalleryUrls() as $gUrl)
                                    <div class="w-14 aspect-video rounded border border-zinc-800 overflow-hidden flex-shrink-0 bg-zinc-950">
                                        <img src="{{ $gUrl }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <input type="file" name="gallery[]" multiple accept="image/*" class="w-full text-xs text-zinc-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700 cursor-pointer bg-zinc-900 border border-zinc-800 rounded-lg py-1 px-2">
                    </div>

                    @php
                        $specsText = '';
                        if (is_array($product->account_specs)) {
                            foreach ($product->account_specs as $k => $v) {
                                $specsText .= "$k: $v\n";
                            }
                        }
                    @endphp
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-zinc-300 mb-1.5">Spesifikasi &amp; Detail Produk (Format: <code class="text-zinc-400">Key: Value</code>)</label>
                        <textarea name="account_specs_raw" rows="3" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs font-mono text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">{{ old('account_specs_raw', trim($specsText)) }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-zinc-300 mb-1.5">Deskripsi Produk</label>
                        <textarea name="description" rows="2" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-zinc-300 mb-1.5">Panduan / Instruksi Pengisian</label>
                        <textarea name="instructions" rows="2" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">{{ old('instructions', $product->instructions) }}</textarea>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer mt-2">
                            <input type="checkbox" name="requires_user_id" value="1" {{ $product->requires_user_id ? 'checked' : '' }} class="rounded bg-zinc-800 border-zinc-700 text-zinc-100 focus:ring-0">
                            <span class="text-xs font-medium text-white">Wajib User ID</span>
                        </label>
                        <input type="text" name="user_id_label" value="{{ $product->user_id_label }}" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 mt-1">
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer mt-2">
                            <input type="checkbox" name="requires_zone_id" value="1" {{ $product->requires_zone_id ? 'checked' : '' }} class="rounded bg-zinc-800 border-zinc-700 text-zinc-100 focus:ring-0">
                            <span class="text-xs font-medium text-white">Wajib Zone ID (Server)</span>
                        </label>
                        <input type="text" name="zone_id_label" value="{{ $product->zone_id_label }}" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 mt-1">
                    </div>
                </div>

                <div class="pt-4 flex justify-between items-center border-t border-zinc-800">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="rounded bg-zinc-800 border-zinc-700 text-zinc-100 focus:ring-0">
                        <span class="text-xs font-medium text-white">Status Produk Aktif</span>
                    </label>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 font-semibold text-xs shadow-sm transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            <!-- List of Existing Variants & Quick Stock Management -->
            <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-800">
                    <div>
                        <h3 class="text-sm font-semibold text-white tracking-tight">Daftar Variasi &amp; Stok</h3>
                        <p class="text-[11px] text-zinc-400">Total terdaftar: <span class="font-medium text-zinc-200 tabular-nums">{{ $product->variants->count() }}</span> variasi</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($product->variants as $variant)
                        <div class="p-3.5 rounded-lg bg-zinc-900/80 border border-zinc-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-white text-xs">{{ $variant->name }}</span>
                                    <span class="text-[10px] text-zinc-500 font-mono">SKU: {{ $variant->sku }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-semibold text-xs text-zinc-200 font-mono tabular-nums">
                                        Rp {{ number_format($variant->price, 0, ',', '.') }}
                                    </span>
                                    <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST" onsubmit="return confirm('Hapus variasi {{ $variant->name }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[11px] text-rose-400 hover:text-rose-300 font-medium px-2 py-0.5 rounded bg-rose-950/40 hover:bg-rose-900/60 border border-rose-800/40 transition-colors" title="Hapus variasi ini">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="mt-2.5 pt-2.5 border-t border-zinc-800/80 flex flex-wrap items-center justify-between gap-3 text-xs">
                                <div>
                                    @if($variant->stock_type === 'unlimited')
                                        <span class="text-zinc-500 text-[11px]">Stok: Unlimited</span>
                                    @else
                                        <span class="text-zinc-400 text-[11px]">
                                            Sisa Stok: <strong class="font-semibold font-mono tabular-nums {{ $variant->stock > 0 ? 'text-emerald-400' : 'text-rose-400' }}">{{ $variant->stock }}</strong> unit
                                        </span>
                                    @endif
                                </div>

                                @if($variant->stock_type !== 'unlimited')
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('admin.variants.add-stock', $variant->id) }}" method="POST" class="flex items-center gap-1.5">
                                            @csrf
                                            <input type="number" name="stock_to_add" value="5" min="1" class="w-14 bg-zinc-950 border border-zinc-800 rounded-md px-2 py-1 text-xs text-emerald-400 text-center font-mono tabular-nums" title="Jumlah stok yang ingin ditambahkan">
                                            <button type="submit" class="px-2.5 py-1 rounded-md bg-zinc-800 hover:bg-zinc-700 text-zinc-200 hover:text-white border border-zinc-700/60 text-xs font-medium transition-colors">
                                                + Tambah
                                            </button>
                                        </form>

                                        <details class="relative">
                                            <summary class="cursor-pointer text-[11px] text-zinc-400 hover:text-zinc-200 px-2 py-1 rounded bg-zinc-800 border border-zinc-700/50">
                                                Setel Manual
                                            </summary>
                                            <form action="{{ route('admin.variants.add-stock', $variant->id) }}" method="POST" class="absolute right-0 top-8 z-10 p-3 rounded-xl bg-zinc-950 border border-zinc-800 shadow-xl w-48 space-y-2">
                                                @csrf
                                                <label class="block text-[10px] text-zinc-400">Total Stok Langsung:</label>
                                                <input type="number" name="stock" value="{{ $variant->stock }}" min="0" class="w-full bg-zinc-900 border border-zinc-800 rounded-md p-1.5 text-xs text-white text-center font-mono tabular-nums">
                                                <button type="submit" class="w-full py-1 rounded-md bg-white text-zinc-950 hover:bg-zinc-200 font-semibold text-xs">
                                                    Simpan
                                                </button>
                                            </form>
                                        </details>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-zinc-500">Belum ada variasi pada produk ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Add New Variant Box -->
        <div>
            <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST" class="rounded-xl p-5 bg-zinc-900/60 border border-zinc-800 space-y-3 sticky top-24">
                @csrf
                <div class="pb-2 border-b border-zinc-800">
                    <h3 class="text-xs font-semibold text-white tracking-tight">Tambah Variasi Baru</h3>
                    <p class="text-[10px] text-zinc-400">Tambahkan paket nominal baru langsung ke produk ini.</p>
                </div>

                @if($product->isAccount())
                    <div>
                        <span class="text-[10px] text-zinc-400 font-medium uppercase block mb-1">Preset Durasi Cepat:</span>
                        <div class="flex flex-wrap gap-1">
                            <button type="button" onclick="fillVariantPreset('1 Hari - 1 Profil (Sharing)', 'NF-1D', 5000)" class="text-[10px] px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white transition-colors">+ 1 Hari</button>
                            <button type="button" onclick="fillVariantPreset('3 Hari - 1 Profil (Sharing)', 'NF-3D', 12000)" class="text-[10px] px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white transition-colors">+ 3 Hari</button>
                            <button type="button" onclick="fillVariantPreset('7 Hari - 1 Profil (Sharing)', 'NF-7D', 22000)" class="text-[10px] px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white transition-colors">+ 7 Hari</button>
                            <button type="button" onclick="fillVariantPreset('1 Bulan - 1 Profil (Sharing)', 'NF-1M', 35000)" class="text-[10px] px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white transition-colors">+ 1 Bulan Sharing</button>
                            <button type="button" onclick="fillVariantPreset('1 Bulan - Akun Private (5 Profil)', 'NF-1MPRV', 135000)" class="text-[10px] px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white transition-colors">+ 1 Bulan Private</button>
                        </div>
                    </div>
                @endif

                @if($product->isPreorder())
                    <div>
                        <span class="text-[10px] text-zinc-400 font-medium uppercase block mb-1">Preset Preorder:</span>
                        <div class="flex flex-wrap gap-1">
                            <button type="button" onclick="fillVariantPreset('DP (Uang Muka 50%)', 'PDH-DP', 75000)" class="text-[10px] px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white transition-colors">+ DP</button>
                            <button type="button" onclick="fillVariantPreset('Lunas (Pembayaran Penuh)', 'PDH-LNS', 150000)" class="text-[10px] px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white transition-colors">+ Lunas</button>
                            <button type="button" onclick="fillVariantPreset('Pelunasan Akhir (Sisa 50%)', 'PDH-PLNS', 75000)" class="text-[10px] px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white transition-colors">+ Pelunasan</button>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-[11px] font-medium text-zinc-300 mb-1">Nama Variasi / Nominal</label>
                    <input type="text" name="name" id="new_variant_name" required placeholder="Contoh: 1 Bulan 4K" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 focus:outline-none focus:border-zinc-700">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-zinc-300 mb-1">SKU / Kode Variasi</label>
                    <input type="text" name="sku" id="new_variant_sku" required placeholder="NF-1M-01" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 font-mono focus:outline-none focus:border-zinc-700">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-zinc-300 mb-1">Harga Jual (Rp)</label>
                    <input type="number" name="price" id="new_variant_price" required placeholder="35000" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 font-mono tabular-nums focus:outline-none focus:border-zinc-700">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-zinc-300 mb-1">Tipe Stok</label>
                    <select name="stock_type" id="new_variant_stock_type" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 focus:outline-none focus:border-zinc-700">
                        <option value="manual" {{ $product->isAccount() ? 'selected' : '' }}>Terbatas / Stok (Akun/Manual)</option>
                        <option value="unlimited" {{ $product->isGame() ? 'selected' : '' }}>Unlimited (Game Top-Up)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-zinc-300 mb-1">Jumlah Stok Awal</label>
                    <input type="number" name="stock" id="new_variant_stock" value="10" min="0" class="w-full bg-zinc-950 border border-zinc-800 rounded-md px-3 py-1.5 text-xs text-zinc-100 font-mono tabular-nums focus:outline-none focus:border-zinc-700">
                </div>

                <button type="submit" class="w-full py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 font-semibold text-xs shadow-sm transition-colors">
                    + Tambahkan Variasi
                </button>
            </form>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function fillVariantPreset(name, prefix, price) {
        const inputName = document.getElementById('new_variant_name');
        const inputSku = document.getElementById('new_variant_sku');
        const inputPrice = document.getElementById('new_variant_price');
        const inputStockType = document.getElementById('new_variant_stock_type');

        if (inputName) inputName.value = name;
        if (inputSku) inputSku.value = `${prefix}-${Date.now().toString().slice(-4)}`;
        if (inputPrice) inputPrice.value = price;
        if (inputStockType) inputStockType.value = 'manual';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const catSelect = document.getElementById('edit_category_id');
        const typeSelect = document.getElementById('edit_product_type');

        if (catSelect && typeSelect) {
            catSelect.addEventListener('change', function() {
                const selectedOpt = this.options[this.selectedIndex];
                const detectedType = selectedOpt.getAttribute('data-type');
                if (detectedType) {
                    typeSelect.value = detectedType;
                }
            });
        }
    });
</script>
@endpush
@endsection

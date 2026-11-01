@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-zinc-200">Kelola Voucher Promo</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-white flex items-center gap-2.5">
                Kelola Voucher &amp; Kode Promo
            </h1>
            <p class="text-xs text-zinc-400 mt-1">Buat kode diskon game, cashback, dan atur batasan kuota serta minimal order untuk pembeli.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-zinc-300 hover:text-white text-xs font-medium border border-zinc-800 transition-colors">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                <span>Dashboard</span>
            </a>
            <button type="button" onclick="document.getElementById('modal-create-voucher').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>Buat Voucher Baru</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 mb-6 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs space-y-1">
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $error }}</span>
                </p>
            @endforeach
        </div>
    @endif

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-zinc-900/60 rounded-xl p-5 border border-zinc-800">
            <span class="text-xs text-zinc-400 font-medium">Total Voucher</span>
            <div class="text-2xl font-bold tracking-tight text-white mt-1 tabular-nums">
                {{ $totalVouchers }}
            </div>
            <span class="text-[11px] text-zinc-500 mt-1 block">Semua kode promo tercatat</span>
        </div>

        <div class="bg-zinc-900/60 rounded-xl p-5 border border-zinc-800">
            <span class="text-xs text-zinc-400 font-medium">Voucher Aktif</span>
            <div class="text-2xl font-bold tracking-tight text-emerald-400 mt-1 tabular-nums">
                {{ $activeVouchers }}
            </div>
            <span class="text-[11px] text-zinc-500 mt-1 block">Dapat digunakan pembeli</span>
        </div>

        <div class="bg-zinc-900/60 rounded-xl p-5 border border-zinc-800">
            <span class="text-xs text-zinc-400 font-medium">Total Digunakan (Klaim)</span>
            <div class="text-2xl font-bold tracking-tight text-white mt-1 tabular-nums">
                {{ $totalClaims }} <span class="text-sm font-normal text-zinc-400">kali</span>
            </div>
            <span class="text-[11px] text-zinc-500 mt-1 block">Penggunaan oleh pelanggan</span>
        </div>
    </div>

    <!-- Voucher Table -->
    <div class="bg-zinc-900/60 rounded-xl border border-zinc-800 overflow-hidden">
        <div class="p-5 border-b border-zinc-800">
            <h3 class="text-sm font-semibold text-white">Daftar Kode Voucher</h3>
            <p class="text-xs text-zinc-400 mt-0.5">Voucher dapat dimasukkan pembeli pada step checkout order.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-950/60 text-zinc-400 uppercase tracking-wider text-[10px] border-b border-zinc-800">
                    <tr>
                        <th class="py-3 px-4 font-medium">Kode Voucher</th>
                        <th class="py-3 px-4 font-medium">Nama & Deskripsi</th>
                        <th class="py-3 px-4 font-medium">Potongan Diskon</th>
                        <th class="py-3 px-4 font-medium">Min. Belanja</th>
                        <th class="py-3 px-4 font-medium">Kuota Pemakaian</th>
                        <th class="py-3 px-4 font-medium">Masa Berlaku</th>
                        <th class="py-3 px-4 font-medium">Status</th>
                        <th class="py-3 px-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/80">
                    @forelse($vouchers as $voucher)
                        <tr class="hover:bg-zinc-800/20 transition-colors">
                            <td class="py-3.5 px-4 font-mono">
                                <div class="flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded bg-zinc-800 text-zinc-200 font-semibold border border-zinc-700 text-xs">
                                        {{ $voucher->code }}
                                    </span>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ $voucher->code }}'); alert('Kode disalin: {{ $voucher->code }}');" class="text-zinc-500 hover:text-white transition-colors p-1" title="Salin Kode">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                                    </button>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-white block">{{ $voucher->name }}</span>
                                @if($voucher->description)
                                    <span class="text-[11px] text-zinc-400 block line-clamp-1 mt-0.5">{{ $voucher->description }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($voucher->type === 'percentage')
                                    <span class="font-semibold text-amber-400 text-xs tabular-nums">{{ round($voucher->discount_amount) }}%</span>
                                    @if($voucher->max_discount)
                                        <span class="text-[10px] text-zinc-500 block tabular-nums">Maks. Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</span>
                                    @endif
                                @else
                                    <span class="font-semibold text-emerald-400 text-xs tabular-nums">Rp {{ number_format($voucher->discount_amount, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-zinc-500 block">Potongan Tetap</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($voucher->min_order_amount > 0)
                                    <span class="font-medium text-zinc-200 tabular-nums">Rp {{ number_format($voucher->min_order_amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-zinc-500">Tanpa Minimal</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-1 tabular-nums">
                                    <span class="font-medium text-white">{{ $voucher->used_count }}</span>
                                    <span class="text-zinc-600">/</span>
                                    <span class="text-zinc-400">{{ $voucher->usage_limit ?? '∞' }}</span>
                                </div>
                                @if($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit)
                                    <span class="text-[10px] text-rose-400 font-medium block">Kuota Habis</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-[11px] text-zinc-400">
                                @if($voucher->expires_at)
                                    <span class="{{ $voucher->expires_at->isPast() ? 'text-rose-400 font-medium' : 'text-zinc-300' }} tabular-nums">
                                        {{ $voucher->expires_at->format('d/m/Y H:i') }}
                                    </span>
                                    <span class="block text-[10px] text-zinc-500">
                                        {{ $voucher->expires_at->isPast() ? 'Kadaluarsa' : $voucher->expires_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="text-emerald-400 font-medium">Selamanya</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if(! $voucher->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-zinc-800 text-zinc-400 border border-zinc-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span>
                                        <span>Nonaktif</span>
                                    </span>
                                @elseif($voucher->expires_at && $voucher->expires_at->isPast())
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                        <span>Kadaluarsa</span>
                                    </span>
                                @elseif($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        <span>Habis</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        <span>Aktif</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <form action="{{ route('admin.vouchers.toggle', $voucher) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="{{ $voucher->is_active ? 'Matikan Voucher' : 'Aktifkan Voucher' }}" class="px-2.5 py-1 rounded-lg border text-xs font-medium transition-colors {{ $voucher->is_active ? 'bg-amber-500/10 border-amber-500/20 text-amber-300 hover:bg-amber-500/20' : 'bg-emerald-500/10 border-emerald-500/20 text-emerald-300 hover:bg-emerald-500/20' }}">
                                            {{ $voucher->is_active ? 'Matikan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <button type="button" onclick="openEditVoucherModal({{ json_encode($voucher) }})" class="p-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white border border-zinc-700 transition-colors" title="Edit Voucher">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </button>

                                    <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus voucher ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-colors" title="Hapus Voucher">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-zinc-500 text-xs">
                                Belum ada voucher diskon. Klik tombol <strong>+ Buat Voucher Baru</strong> untuk menambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL: BUAT VOUCHER BARU -->
<div id="modal-create-voucher" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 max-w-lg w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-zinc-800 mb-5">
            <h3 class="text-sm font-semibold text-white">Buat Voucher Diskon Baru</h3>
            <button type="button" onclick="document.getElementById('modal-create-voucher').classList.add('hidden')" class="w-7 h-7 rounded-lg bg-zinc-800 text-zinc-400 hover:text-white flex items-center justify-center text-xs transition-colors">✕</button>
        </div>

        <form action="{{ route('admin.vouchers.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Kode Voucher <span class="text-rose-400">*</span></label>
                    <input type="text" name="code" placeholder="DISKON10" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white uppercase font-mono focus:outline-none focus:border-zinc-600 transition-colors">
                    <span class="text-[10px] text-zinc-500 mt-1 block">Huruf/angka tanpa spasi</span>
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nama Voucher <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" placeholder="Promo Gajian Diamond" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Deskripsi Singkat</label>
                <textarea name="description" rows="2" placeholder="Diskon khusus top up dan beli akun" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Tipe Diskon <span class="text-rose-400">*</span></label>
                    <select name="type" id="create-type" onchange="toggleMaxDiscount('create-type', 'create-max-discount-box')" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                        <option value="fixed">Potongan Tetap (Rp)</option>
                        <option value="percentage">Persentase (%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nilai Diskon <span class="text-rose-400">*</span></label>
                    <input type="number" name="discount_amount" placeholder="Contoh: 10000 atau 10" step="any" min="0.01" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div id="create-max-discount-box" class="hidden">
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Maks. Potongan (Rp)</label>
                    <input type="number" name="max_discount" placeholder="Contoh: 50000" min="0" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
                    <span class="text-[10px] text-zinc-500 mt-1 block">Batas maksimal jika %</span>
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Min. Belanja (Rp)</label>
                    <input type="number" name="min_order_amount" value="0" min="0" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
                    <span class="text-[10px] text-zinc-500 mt-1 block">0 = tanpa syarat min</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Batas Kuota Pemakaian</label>
                <input type="number" name="usage_limit" placeholder="Kosongkan jika tanpa batas (Unlimited)" min="1" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Mulai Berlaku</label>
                    <input type="datetime-local" name="starts_at" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Kadaluarsa (Selesai)</label>
                    <input type="datetime-local" name="expires_at" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <div class="pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-zinc-900 bg-zinc-950 border-zinc-700 focus:ring-0">
                    <span class="text-xs text-zinc-300">Aktifkan voucher sekarang</span>
                </label>
            </div>

            <div class="pt-3 border-t border-zinc-800 flex justify-end gap-2.5">
                <button type="button" onclick="document.getElementById('modal-create-voucher').classList.add('hidden')" class="px-3.5 py-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-medium transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors">
                    Simpan Voucher
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDIT VOUCHER -->
<div id="modal-edit-voucher" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 max-w-lg w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-zinc-800 mb-5">
            <h3 class="text-sm font-semibold text-white">Edit Voucher</h3>
            <button type="button" onclick="document.getElementById('modal-edit-voucher').classList.add('hidden')" class="w-7 h-7 rounded-lg bg-zinc-800 text-zinc-400 hover:text-white flex items-center justify-center text-xs transition-colors">✕</button>
        </div>

        <form id="edit-voucher-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Kode Voucher <span class="text-rose-400">*</span></label>
                    <input type="text" name="code" id="edit-code" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white uppercase font-mono focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nama Voucher <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" id="edit-name" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Deskripsi Singkat</label>
                <textarea name="description" id="edit-description" rows="2" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Tipe Diskon <span class="text-rose-400">*</span></label>
                    <select name="type" id="edit-type" onchange="toggleMaxDiscount('edit-type', 'edit-max-discount-box')" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                        <option value="fixed">Potongan Tetap (Rp)</option>
                        <option value="percentage">Persentase (%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nilai Diskon <span class="text-rose-400">*</span></label>
                    <input type="number" name="discount_amount" id="edit-discount-amount" step="any" min="0.01" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div id="edit-max-discount-box" class="hidden">
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Maks. Potongan (Rp)</label>
                    <input type="number" name="max_discount" id="edit-max-discount" min="0" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Min. Belanja (Rp)</label>
                    <input type="number" name="min_order_amount" id="edit-min-order-amount" min="0" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Batas Kuota Pemakaian</label>
                <input type="number" name="usage_limit" id="edit-usage-limit" placeholder="Kosongkan jika unlimited" min="1" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Mulai Berlaku</label>
                    <input type="datetime-local" name="starts_at" id="edit-starts-at" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Kadaluarsa (Selesai)</label>
                    <input type="datetime-local" name="expires_at" id="edit-expires-at" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <div class="pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_active" id="edit-is-active" value="1" class="w-4 h-4 rounded text-zinc-900 bg-zinc-950 border-zinc-700 focus:ring-0">
                    <span class="text-xs text-zinc-300">Aktifkan voucher</span>
                </label>
            </div>

            <div class="pt-3 border-t border-zinc-800 flex justify-end gap-2.5">
                <button type="button" onclick="document.getElementById('modal-edit-voucher').classList.add('hidden')" class="px-3.5 py-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-medium transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleMaxDiscount(selectId, boxId) {
        const sel = document.getElementById(selectId);
        const box = document.getElementById(boxId);
        if (sel.value === 'percentage') {
            box.classList.remove('hidden');
        } else {
            box.classList.add('hidden');
        }
    }

    function openEditVoucherModal(v) {
        document.getElementById('edit-voucher-form').action = `/admin/vouchers/${v.id}`;
        document.getElementById('edit-code').value = v.code || '';
        document.getElementById('edit-name').value = v.name || '';
        document.getElementById('edit-description').value = v.description || '';
        document.getElementById('edit-type').value = v.type || 'fixed';
        document.getElementById('edit-discount-amount').value = parseFloat(v.discount_amount) || '';
        document.getElementById('edit-min-order-amount').value = parseFloat(v.min_order_amount) || '0';
        document.getElementById('edit-max-discount').value = v.max_discount ? parseFloat(v.max_discount) : '';
        document.getElementById('edit-usage-limit').value = v.usage_limit || '';
        document.getElementById('edit-is-active').checked = !!v.is_active;

        toggleMaxDiscount('edit-type', 'edit-max-discount-box');

        if (v.starts_at) {
            const date = new Date(v.starts_at);
            const offset = date.getTimezoneOffset() * 60000;
            document.getElementById('edit-starts-at').value = (new Date(date.getTime() - offset)).toISOString().slice(0, 16);
        } else {
            document.getElementById('edit-starts-at').value = '';
        }

        if (v.expires_at) {
            const date = new Date(v.expires_at);
            const offset = date.getTimezoneOffset() * 60000;
            document.getElementById('edit-expires-at').value = (new Date(date.getTime() - offset)).toISOString().slice(0, 16);
        } else {
            document.getElementById('edit-expires-at').value = '';
        }

        document.getElementById('modal-edit-voucher').classList.remove('hidden');
    }
</script>
@endpush
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-zinc-800/80">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-zinc-200 transition-colors">Dashboard</a>
                <span class="text-zinc-600">/</span>
                <a href="{{ route('admin.payment-methods.index') }}" class="hover:text-zinc-200 transition-colors">Metode Pembayaran</a>
                <span class="text-zinc-600">/</span>
                <span class="text-zinc-200">Tambah Rekening Baru</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Tambah Rekening Pembayaran</h1>
            <p class="text-xs text-zinc-400 mt-1">Masukkan data rekening bank, e-wallet, atau QRIS untuk menerima transfer pembeli.</p>
        </div>

        <a href="{{ route('admin.payment-methods.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white text-xs font-medium transition-colors self-start sm:self-auto">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data" class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800 space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Kode Channel <span class="text-rose-400">*</span></label>
                <input type="text" name="code" value="{{ old('code') }}" required placeholder="Contoh: BCA, MANDIRI, BRI, DANA" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 uppercase font-mono placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                @error('code')
                    <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nama Bank / Channel <span class="text-rose-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Bank Central Asia (BCA)" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                @error('name')
                    <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nomor Rekening / No. HP <span class="text-rose-400">*</span></label>
                <input type="text" name="account_number" value="{{ old('account_number') }}" required placeholder="Contoh: 1234567890" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 font-mono tabular-nums placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                @error('account_number')
                    <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Atas Nama (A/N) <span class="text-rose-400">*</span></label>
                <input type="text" name="account_holder" value="{{ old('account_holder') }}" required placeholder="Contoh: Budi Santoso" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                @error('account_holder')
                    <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Kategori Channel <span class="text-rose-400">*</span></label>
                <select name="group" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                    <option value="Transfer Bank Manual">Transfer Bank Manual</option>
                    <option value="E-Wallet">E-Wallet</option>
                    <option value="QRIS">QRIS Statis</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Urutan Tampilan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 font-mono tabular-nums focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Biaya Admin (Rp)</label>
                <input type="number" name="fee_flat" value="{{ old('fee_flat', 0) }}" placeholder="0" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 font-mono tabular-nums focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Gambar QRIS (Opsional)</label>
                <input type="file" name="qr_image" accept="image/*" class="w-full text-xs text-zinc-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700 cursor-pointer bg-zinc-900 border border-zinc-800 rounded-lg py-1 px-2">
                @error('qr_image')
                    <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Petunjuk Transfer Tambahan (Opsional)</label>
                <textarea name="instructions" rows="2" placeholder="Contoh: Mohon cantumkan nomor invoice pada berita transfer..." class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">{{ old('instructions') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-2.5 pt-4 border-t border-zinc-800">
            <a href="{{ route('admin.payment-methods.index') }}" class="px-3.5 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-xs font-medium text-zinc-300 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-4 py-1.5 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold shadow-sm transition-colors">
                Simpan Rekening
            </button>
        </div>
    </form>
</div>
@endsection

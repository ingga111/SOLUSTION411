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
                <span class="text-zinc-200">Edit {{ $paymentMethod->code }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Edit Rekening: {{ $paymentMethod->name }}</h1>
            <p class="text-xs text-zinc-400 mt-1">Perbarui nomor rekening, nama pemilik, atau petunjuk transfer.</p>
        </div>

        <a href="{{ route('admin.payment-methods.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white text-xs font-medium transition-colors self-start sm:self-auto">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.payment-methods.update', $paymentMethod->id) }}" method="POST" enctype="multipart/form-data" class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800 space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-zinc-400 mb-1.5">Kode Channel (Permanen)</label>
                <input type="text" value="{{ $paymentMethod->code }}" disabled class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-400 font-mono font-medium cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nama Bank / Channel <span class="text-rose-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $paymentMethod->name) }}" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                @error('name')
                    <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nomor Rekening / No. HP <span class="text-rose-400">*</span></label>
                <input type="text" name="account_number" value="{{ old('account_number', $paymentMethod->account_number) }}" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 font-mono tabular-nums focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                @error('account_number')
                    <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Atas Nama (A/N) <span class="text-rose-400">*</span></label>
                <input type="text" name="account_holder" value="{{ old('account_holder', $paymentMethod->account_holder) }}" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                @error('account_holder')
                    <p class="text-[11px] text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Kategori Channel <span class="text-rose-400">*</span></label>
                <select name="group" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                    <option value="Transfer Bank Manual" {{ $paymentMethod->group === 'Transfer Bank Manual' ? 'selected' : '' }}>Transfer Bank Manual</option>
                    <option value="E-Wallet" {{ $paymentMethod->group === 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                    <option value="QRIS" {{ $paymentMethod->group === 'QRIS' ? 'selected' : '' }}>QRIS Statis</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Urutan Tampilan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $paymentMethod->sort_order) }}" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 font-mono tabular-nums focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Biaya Admin (Rp)</label>
                <input type="number" name="fee_flat" value="{{ old('fee_flat', $paymentMethod->fee_flat) }}" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 font-mono tabular-nums focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Gambar QRIS (Opsional)</label>
                <input type="file" name="qr_image" accept="image/*" class="w-full text-xs text-zinc-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700 cursor-pointer bg-zinc-900 border border-zinc-800 rounded-lg py-1 px-2">
                @if($paymentMethod->qr_image)
                    <div class="mt-2 flex items-center gap-2">
                        <img src="{{ asset('storage/'.$paymentMethod->qr_image) }}" alt="QR" class="w-10 h-10 object-contain bg-white rounded p-1 border border-zinc-700">
                        <span class="text-[10px] text-zinc-400">QR code saat ini aktif</span>
                    </div>
                @endif
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Petunjuk Transfer Tambahan (Opsional)</label>
                <textarea name="instructions" rows="2" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">{{ old('instructions', $paymentMethod->instructions) }}</textarea>
            </div>

            <div class="sm:col-span-2 pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" value="1" {{ $paymentMethod->is_active ? 'checked' : '' }} class="w-3.5 h-3.5 rounded text-zinc-900 bg-zinc-950 border-zinc-800 focus:ring-0">
                    <span class="text-xs font-medium text-white">Status Rekening Aktif (Tampil dan dapat dipilih pelanggan)</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-2.5 pt-4 border-t border-zinc-800">
            <a href="{{ route('admin.payment-methods.index') }}" class="px-3.5 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-xs font-medium text-zinc-300 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-4 py-1.5 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold shadow-sm transition-colors">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-zinc-400 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
        <span>/</span>
        <a href="{{ route('admin.users.index') }}" class="hover:text-white transition-colors">Manajemen User</a>
        <span>/</span>
        <span class="text-zinc-200">Tambah User Baru</span>
    </div>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-white">Tambah Pengguna Baru</h1>
            <p class="text-xs text-zinc-400 mt-1">Buat akun untuk Administrator toko atau Pelanggan (Customer) baru.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-zinc-300 hover:text-white text-xs font-medium border border-zinc-800 transition-colors">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" class="bg-zinc-900/60 rounded-xl p-6 sm:p-8 border border-zinc-800 space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label for="name" class="block text-xs font-medium text-zinc-300 mb-1.5">Nama Lengkap <span class="text-rose-400">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Admin CS / Ahmad Dani" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                @error('name')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-medium text-zinc-300 mb-1.5">Alamat Email <span class="text-rose-400">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                <span class="text-[10px] text-zinc-500 mt-1 block">Digunakan untuk login ke sistem</span>
                @error('email')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-xs font-medium text-zinc-300 mb-1.5">Nomor WhatsApp / HP</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2.5 text-xs text-white font-mono focus:outline-none focus:border-zinc-600 transition-colors">
                <span class="text-[10px] text-zinc-500 mt-1 block">Kontak WhatsApp aktif (opsional)</span>
                @error('phone')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Role Selection -->
        <div>
            <label class="block text-xs font-medium text-zinc-300 mb-2">Pilih Peran Akun (Role) <span class="text-rose-400">*</span></label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="relative flex items-start gap-3 p-4 rounded-lg border cursor-pointer transition-colors {{ old('role', 'admin') === 'admin' ? 'bg-amber-500/5 border-amber-500/30 text-white' : 'bg-zinc-950 border-zinc-800 text-zinc-300 hover:border-zinc-700' }}">
                    <input type="radio" name="role" value="admin" {{ old('role', 'admin') === 'admin' ? 'checked' : '' }} class="mt-0.5 text-amber-500 focus:ring-0 bg-zinc-950 border-zinc-700">
                    <div>
                        <span class="text-sm font-medium {{ old('role', 'admin') === 'admin' ? 'text-amber-300' : 'text-zinc-200' }}">Administrator</span>
                        <p class="text-[11px] text-zinc-400 mt-1 leading-relaxed">
                            Memiliki akses penuh ke panel admin, verifikasi pembayaran pesanan, kelola produk, banner, flash sale, dan voucher.
                        </p>
                    </div>
                </label>

                <label class="relative flex items-start gap-3 p-4 rounded-lg border cursor-pointer transition-colors {{ old('role') === 'customer' ? 'bg-zinc-800/50 border-zinc-600 text-white' : 'bg-zinc-950 border-zinc-800 text-zinc-300 hover:border-zinc-700' }}">
                    <input type="radio" name="role" value="customer" {{ old('role') === 'customer' ? 'checked' : '' }} class="mt-0.5 text-zinc-400 focus:ring-0 bg-zinc-950 border-zinc-700">
                    <div>
                        <span class="text-sm font-medium text-zinc-200">Customer (Pelanggan)</span>
                        <p class="text-[11px] text-zinc-400 mt-1 leading-relaxed">
                            Pengguna biasa yang dapat melakukan checkout top-up / akun digital dan melihat riwayat transaksinya.
                        </p>
                    </div>
                </label>
            </div>
            @error('role')
                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Inputs with Toggle -->
        <div class="pt-4 border-t border-zinc-800 space-y-4">
            <h4 class="text-xs font-semibold text-zinc-300 flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                Pengaturan Password Akun
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="password" class="block text-xs font-medium text-zinc-300 mb-1.5">Password <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2.5 pr-10 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                        <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-500 hover:text-white focus:outline-none transition-colors" title="Lihat/Sembunyikan Password" aria-label="Lihat Password">
                            <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="w-4 h-4 eye-off-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-zinc-300 mb-1.5">Konfirmasi Password <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2.5 pr-10 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-500 hover:text-white focus:outline-none transition-colors" title="Lihat/Sembunyikan Konfirmasi Password" aria-label="Lihat Konfirmasi Password">
                            <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="w-4 h-4 eye-off-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-zinc-800">
            <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-medium transition-colors">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors">
                Buat Akun Pengguna
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        
        const eyeIcon = btn.querySelector('.eye-icon');
        const eyeOffIcon = btn.querySelector('.eye-off-icon');
        if (eyeIcon && eyeOffIcon) {
            if (isPassword) {
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    }
</script>
@endpush

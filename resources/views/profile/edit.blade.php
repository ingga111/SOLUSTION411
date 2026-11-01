@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6 pb-20">
    
    <!-- Breadcrumb (Linear Style) -->
    <div class="flex items-center gap-2 text-xs font-mono text-zinc-500 dark:text-zinc-400 mb-5">
        <a href="{{ route('home') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Beranda</a>
        <span>/</span>
        <span class="text-zinc-900 dark:text-zinc-200 font-bold">Profil Pengguna</span>
    </div>

    <!-- User Header Profile Card -->
    <div class="bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm mb-6">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
            <!-- Profile Avatar -->
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 flex items-center justify-center font-mono font-bold text-2xl flex-shrink-0 shadow-sm">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <!-- Profile Info -->
            <div class="flex-1 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-1">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-zinc-900 dark:text-zinc-100 tracking-tight">{{ $user->name }}</h1>
                    <span class="inline-flex self-center sm:self-auto px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase tracking-wider
                        {{ $user->isAdmin() ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700' }}">
                        {{ $user->isAdmin() ? '⚡ Administrator' : '👤 Customer' }}
                    </span>
                </div>
                <p class="text-xs font-mono text-zinc-500 flex items-center justify-center sm:justify-start gap-2">
                    <span>{{ $user->email }}</span>
                    <span>•</span>
                    <span>Bergabung {{ $user->created_at->format('M Y') }}</span>
                </p>

                <!-- Quick Stats -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 mt-4 font-mono">
                    <div class="bg-zinc-50 dark:bg-zinc-950 p-3 rounded-xl border border-zinc-200 dark:border-zinc-800 text-center sm:text-left">
                        <span class="text-[10px] text-zinc-500 uppercase tracking-wider block">Total Transaksi</span>
                        <span class="text-base font-bold text-zinc-900 dark:text-white tabular-nums">{{ $totalOrders }} Pesanan</span>
                    </div>
                    <div class="bg-zinc-50 dark:bg-zinc-950 p-3 rounded-xl border border-zinc-200 dark:border-zinc-800 text-center sm:text-left">
                        <span class="text-[10px] text-zinc-500 uppercase tracking-wider block">Selesai</span>
                        <span class="text-base font-bold text-emerald-600 dark:text-emerald-400 tabular-nums">{{ $completedOrders }} Berhasil</span>
                    </div>
                    <div class="col-span-2 sm:col-span-1 bg-zinc-50 dark:bg-zinc-950 p-3 rounded-xl border border-zinc-200 dark:border-zinc-800 text-center sm:text-left">
                        <span class="text-[10px] text-zinc-500 uppercase tracking-wider block">WhatsApp</span>
                        <span class="text-xs font-bold text-zinc-900 dark:text-white block mt-0.5 truncate">
                            {{ $user->phone ?? 'Belum Diatur' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Edit Forms -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: Edit Personal Information -->
        <div class="lg:col-span-7">
            <form action="{{ route('profile.update') }}" method="POST" class="bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-6 shadow-sm space-y-5">
                @csrf
                @method('PUT')

                <div class="border-b border-zinc-100 dark:border-zinc-800 pb-3">
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white font-mono uppercase tracking-wider">
                        Informasi Data Diri
                    </h3>
                    <p class="text-xs text-zinc-500 mt-0.5">Perbarui nama lengkap, kontak WhatsApp, dan alamat email Anda.</p>
                </div>

                <div class="space-y-3.5">
                    <div>
                        <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required placeholder="Nama Anda" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-zinc-500">
                        @error('name')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="email@gmail.com" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Nomor WhatsApp Aktif</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                        @error('phone')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800">
                    <h4 class="text-sm font-bold text-zinc-900 dark:text-white font-mono uppercase tracking-wider mb-0.5">
                        Ganti Password (Opsional)
                    </h4>
                    <p class="text-xs text-zinc-500 mb-3.5">Kosongkan kolom di bawah jika Anda tidak ingin mengubah password.</p>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Password Saat Ini</label>
                            <div class="relative">
                                <input type="password" name="current_password" id="current_password" placeholder="Masukkan password lama" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 pr-9 text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-zinc-500 font-mono">
                                <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 focus:outline-none" title="Lihat Password">
                                    <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg class="w-4 h-4 eye-off-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Password Baru</label>
                                <div class="relative">
                                    <input type="password" name="password" id="profile_password" placeholder="Min. 8 karakter" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 pr-9 text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-zinc-500 font-mono">
                                    <button type="button" onclick="togglePasswordVisibility('profile_password', this)" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 focus:outline-none" title="Lihat Password">
                                        <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg class="w-4 h-4 eye-off-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Ulangi Password</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="profile_password_confirmation" placeholder="Ulangi password" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 pr-9 text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-zinc-500 font-mono">
                                    <button type="button" onclick="togglePasswordVisibility('profile_password_confirmation', this)" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 focus:outline-none" title="Lihat Password">
                                        <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg class="w-4 h-4 eye-off-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-3 flex justify-end">
                    <button type="submit" class="px-5 py-2 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-bold font-mono text-xs hover:bg-zinc-800 dark:hover:bg-white transition-all shadow-sm">
                        Simpan Perubahan →
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Recent Orders & Quick Actions -->
        <div class="lg:col-span-5 space-y-5 font-mono">
            
            <!-- Quick Actions Card -->
            <div class="bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 sm:p-5 shadow-sm space-y-1.5 text-xs">
                <h3 class="text-[10px] text-zinc-500 uppercase tracking-wider mb-2 font-bold">Menu Pintas</h3>

                @if($user->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between p-2.5 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 text-amber-500 border border-amber-500/20 font-bold transition-all">
                        <span>⚡ Dashboard Admin</span>
                        <span>→</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between p-2.5 rounded-lg bg-zinc-50 hover:bg-zinc-100 dark:bg-zinc-950 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-800 transition-all">
                        <span>📋 Verifikasi Pesanan</span>
                        <span>→</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between p-2.5 rounded-lg bg-zinc-50 hover:bg-zinc-100 dark:bg-zinc-950 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-800 transition-all">
                        <span>👥 Kelola User &amp; Admin</span>
                        <span>→</span>
                    </a>
                @endif

                <a href="{{ route('customer.orders.index') }}" class="flex items-center justify-between p-2.5 rounded-lg bg-zinc-50 hover:bg-zinc-100 dark:bg-zinc-950 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-800 transition-all">
                    <span>📦 Riwayat Pesanan</span>
                    <span>→</span>
                </a>

                <a href="{{ route('home') }}" class="flex items-center justify-between p-2.5 rounded-lg bg-zinc-50 hover:bg-zinc-100 dark:bg-zinc-950 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-800 transition-all">
                    <span>🎮 Katalog Layanan</span>
                    <span>→</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="pt-1.5">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2 rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-rose-50 dark:hover:bg-rose-950/20 text-zinc-600 dark:text-zinc-400 hover:text-rose-600 dark:hover:text-rose-400 border border-zinc-200 dark:border-zinc-700 font-bold transition-all text-xs">
                        <span>🚪 Keluar Akun</span>
                    </button>
                </form>
            </div>

            <!-- Recent Orders Preview -->
            <div class="bg-white dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 sm:p-5 shadow-sm text-xs">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-[10px] text-zinc-500 uppercase tracking-wider font-bold">Pesanan Terakhir</h3>
                    <a href="{{ route('customer.orders.index') }}" class="text-[11px] text-zinc-600 dark:text-zinc-300 hover:underline">Lihat Semua</a>
                </div>

                <div class="space-y-2">
                    @forelse($recentOrders as $order)
                        <a href="{{ route('orders.show', $order->order_number) }}" class="block p-2.5 rounded-lg bg-zinc-50 hover:bg-zinc-100 dark:bg-zinc-950 dark:hover:bg-zinc-800 border border-zinc-200 dark:border-zinc-800 transition-all">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-zinc-900 dark:text-white">{{ $order->order_number }}</span>
                                <span class="text-[9px] font-bold px-1.5 py-0.2 rounded
                                    {{ $order->status->value === 'completed' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : ($order->status->value === 'processing' ? 'bg-cyan-500/10 text-cyan-600 dark:text-cyan-400' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400') }}">
                                    {{ $order->status->label() }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-zinc-500 font-sans">
                                <span class="truncate max-w-[140px]">{{ $order->items->first()?->product_name ?? 'Pesanan' }}</span>
                                <span class="font-bold text-zinc-900 dark:text-white font-mono tabular-nums">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </a>
                    @empty
                        <p class="text-[11px] text-zinc-400 text-center py-3">Belum ada transaksi.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

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

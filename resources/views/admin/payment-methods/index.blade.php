@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Header with Breadcrumbs & Action Hub -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-zinc-800/80">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-zinc-200 transition-colors">Dashboard</a>
                <span class="text-zinc-600">/</span>
                <span class="text-zinc-200">Rekening &amp; Metode Pembayaran</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Rekening &amp; Pembayaran Manual</h1>
            <p class="text-xs text-zinc-400 mt-1">Kelola nomor rekening bank, e-wallet, dan QRIS untuk transfer manual pembeli.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                    <path d="M9 14h6"/><path d="M9 18h6"/><path d="M9 10h6"/>
                </svg>
                <span>Verifikasi Pesanan</span>
            </a>
            <a href="{{ route('admin.payment-methods.create') }}" class="px-3.5 py-1.5 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 font-semibold text-xs transition-colors inline-flex items-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5 text-zinc-950" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/>
                    <path d="M12 5v14"/>
                </svg>
                <span>+ Tambah Rekening Baru</span>
            </a>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="rounded-xl p-4 bg-zinc-900/60 border border-zinc-800 flex items-start gap-3.5">
        <div class="p-2 rounded-lg bg-zinc-800 text-zinc-300 border border-zinc-700/60 shrink-0">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="14" x="2" y="5" rx="2"/>
                <line x1="2" x2="22" y1="10" y2="10"/>
            </svg>
        </div>
        <div class="text-xs">
            <h4 class="font-semibold text-white">Alur Pembayaran Transfer Manual</h4>
            <p class="text-zinc-400 mt-0.5 leading-relaxed">
                Nomor rekening dan penerima yang aktif di bawah ini ditampilkan saat checkout. Setelah pembeli mengunggah bukti pembayaran, verifikasi dan setujui melalui halaman 
                <a href="{{ route('admin.orders.index') }}" class="text-zinc-200 underline font-medium hover:text-white">Verifikasi Pesanan</a>.
            </p>
        </div>
    </div>

    <!-- Payment Methods Table -->
    <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-white tracking-tight">Daftar Rekening &amp; Channel</h3>
            <span class="text-xs text-zinc-400">Total: <strong class="text-zinc-200 tabular-nums">{{ $paymentMethods->count() }}</strong> metode</span>
        </div>

        <div class="overflow-x-auto rounded-lg border border-zinc-800">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-900/90 text-zinc-400 uppercase tracking-wider text-[10px] font-semibold border-b border-zinc-800">
                    <tr>
                        <th class="py-2.5 px-3.5">Bank / Channel</th>
                        <th class="py-2.5 px-3.5">Nomor Rekening</th>
                        <th class="py-2.5 px-3.5">Atas Nama</th>
                        <th class="py-2.5 px-3.5">Kategori</th>
                        <th class="py-2.5 px-3.5">Biaya Admin</th>
                        <th class="py-2.5 px-3.5">Status</th>
                        <th class="py-2.5 px-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60 bg-zinc-950/40">
                    @forelse($paymentMethods as $method)
                        <tr class="hover:bg-zinc-900/50 transition-colors">
                            <td class="py-2.5 px-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded bg-zinc-800 border border-zinc-700/60 flex items-center justify-center font-mono text-[10px] font-semibold text-zinc-300">
                                        {{ substr($method->code, 0, 3) }}
                                    </div>
                                    <div>
                                        <span class="font-medium text-white text-xs block">{{ $method->name }}</span>
                                        <span class="text-[10px] text-zinc-500 font-mono">{{ $method->code }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5 px-3.5 font-mono tabular-nums text-xs font-semibold text-zinc-200">
                                {{ $method->account_number ?? '-' }}
                            </td>
                            <td class="py-2.5 px-3.5 font-medium text-zinc-200">
                                {{ $method->account_holder ?? '-' }}
                            </td>
                            <td class="py-2.5 px-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-medium border bg-zinc-800/80 text-zinc-300 border-zinc-700/60">
                                    {{ $method->group }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3.5 font-mono tabular-nums text-zinc-300">
                                @if($method->fee_flat > 0)
                                    Rp {{ number_format($method->fee_flat, 0, ',', '.') }}
                                @else
                                    <span class="text-zinc-500">Gratis (Rp 0)</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3.5 whitespace-nowrap">
                                <form action="{{ route('admin.payment-methods.toggle', $method->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-medium transition-colors
                                        {{ $method->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20' : 'bg-zinc-800 text-zinc-400 border border-zinc-700 hover:bg-zinc-700' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $method->is_active ? 'bg-emerald-400' : 'bg-zinc-500' }}"></span>
                                        <span>{{ $method->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="py-2.5 px-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.payment-methods.edit', $method->id) }}" class="px-2.5 py-1 rounded-md bg-zinc-800 hover:bg-zinc-700 text-zinc-200 hover:text-white font-medium transition-colors text-xs border border-zinc-700/50">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.payment-methods.destroy', $method->id) }}" method="POST" onsubmit="return confirm('Hapus rekening ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-md bg-rose-950/40 hover:bg-rose-900/60 text-rose-300 border border-rose-800/40 font-medium transition-colors text-xs">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-zinc-500">Belum ada metode pembayaran/rekening. Silakan klik "+ Tambah Rekening Baru".</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

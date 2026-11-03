@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Header with Breadcrumbs & Action Hub -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-zinc-800/80">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-zinc-200 transition-colors">Dashboard</a>
                <span class="text-zinc-600">/</span>
                <span class="text-zinc-200">Manajemen Produk</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Daftar Produk Digital</h1>
            <p class="text-xs text-zinc-400 mt-1">Kelola game top-up, akun premium, variasi harga, dan inventori kredensial digital.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.categories.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 8 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/>
                </svg>
                <span>Kelola Kategori</span>
            </a>
            <a href="{{ route('admin.payment-methods.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="14" x="2" y="5" rx="2"/>
                    <line x1="2" x2="22" y1="10" y2="10"/>
                </svg>
                <span>Metode Pembayaran</span>
            </a>
            <a href="{{ route('admin.products.create') }}" class="px-3.5 py-1.5 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 font-semibold text-xs transition-colors inline-flex items-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5 text-zinc-950" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/>
                    <path d="M12 5v14"/>
                </svg>
                <span>+ Tambah Produk Baru</span>
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800">
        <div class="overflow-x-auto rounded-lg border border-zinc-800">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-900/90 text-zinc-400 uppercase tracking-wider text-[10px] font-semibold border-b border-zinc-800">
                    <tr>
                        <th class="py-2.5 px-3.5">Produk</th>
                        <th class="py-2.5 px-3.5">Kategori &amp; Layanan</th>
                        <th class="py-2.5 px-3.5">Publisher</th>
                        <th class="py-2.5 px-3.5">Input Form</th>
                        <th class="py-2.5 px-3.5">Variasi &amp; Stok</th>
                        <th class="py-2.5 px-3.5">Status</th>
                        <th class="py-2.5 px-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60 bg-zinc-950/40">
                    @forelse($products as $product)
                        <tr class="hover:bg-zinc-900/50 transition-colors">
                            <td class="py-2.5 px-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-zinc-800 border border-zinc-700/60 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                        @if($product->thumbnail)
                                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-4 h-4 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m7.5 4.27 9 5.15"/>
                                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                                <path d="m3.3 7 8.7 5 8.7-5"/>
                                                <path d="M12 22V12"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="font-medium text-xs text-white block">{{ $product->name }}</span>
                                        <span class="text-[11px] text-zinc-500 font-mono">/product/{{ $product->slug }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5 px-3.5">
                                <span class="font-medium text-xs text-zinc-200 block">{{ $product->category->name ?? '-' }}</span>
                                <span class="inline-flex items-center gap-1 mt-0.5 px-2 py-0.2 rounded text-[10px] font-medium border bg-zinc-800/80 text-zinc-300 border-zinc-700/60 uppercase tracking-wider">
                                    {{ $product->type->label() }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3.5 text-xs text-zinc-400">
                                {{ $product->publisher ?? '-' }}
                            </td>
                            <td class="py-2.5 px-3.5">
                                <div class="space-y-0.5 text-[11px]">
                                    @if($product->requires_user_id)
                                        <span class="text-zinc-300 block">• {{ $product->user_id_label }}</span>
                                    @endif
                                    @if($product->requires_zone_id)
                                        <span class="text-amber-400/90 block">• {{ $product->zone_id_label }}</span>
                                    @endif
                                    @if(!$product->requires_user_id && !$product->requires_zone_id)
                                        <span class="text-zinc-500">• Email Pelanggan</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-2.5 px-3.5 font-mono tabular-nums">
                                <span class="font-semibold text-xs text-zinc-200">{{ $product->variants->count() }} Paket</span>
                                @php 
                                    $limitedVariants = $product->variants->where('stock_type', '!=', 'unlimited');
                                    $hasLimited = $limitedVariants->isNotEmpty() || $product->isAccount() || $product->isGameAccount();
                                    $stockCount = $limitedVariants->sum('stock');
                                @endphp
                                @if($hasLimited)
                                    @if($stockCount > 0)
                                        <span class="text-[11px] block text-emerald-400 font-medium">Stok: {{ $stockCount }}</span>
                                    @else
                                        <span class="text-[11px] block text-rose-400 font-medium">Stok: 0 (Habis)</span>
                                    @endif
                                @endif
                            </td>
                            <td class="py-2.5 px-3.5 whitespace-nowrap">
                                @if($product->is_active)
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-zinc-800 text-zinc-400 border border-zinc-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span>
                                        <span>Nonaktif</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3.5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="px-2.5 py-1 rounded-md bg-zinc-800 hover:bg-zinc-700 text-zinc-200 hover:text-white font-medium transition-colors text-xs border border-zinc-700/50">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini beserta seluruh variasinya?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-md bg-rose-950/40 hover:bg-rose-900/60 text-rose-300 font-medium transition-colors text-xs border border-rose-800/50">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-zinc-500">
                                Belum ada data produk digital. Silakan klik tombol "Tambah Produk Baru".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="mt-4 pt-4 border-t border-zinc-800">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

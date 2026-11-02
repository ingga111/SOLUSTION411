@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Header with Quick Action & Export -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-zinc-800/80">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-zinc-200 transition-colors">Dashboard</a>
                <span class="text-zinc-600">/</span>
                <span class="text-zinc-200">Daftar Pesanan &amp; Verifikasi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Verifikasi Pembayaran &amp; Pesanan</h1>
            <p class="text-xs text-zinc-400 mt-1">Periksa mutasi transfer pembeli, verifikasi bukti pembayaran, dan unduh laporan transaksi terstruktur.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- EXPORT EXCEL BUTTON -->
            <a href="{{ route('admin.orders.export', array_merge(request()->query(), ['format' => 'excel'])) }}" 
               class="px-3 py-1.5 rounded-lg bg-emerald-950/60 border border-emerald-800/60 text-emerald-300 hover:text-emerald-100 hover:bg-emerald-900/60 text-xs font-semibold transition-all inline-flex items-center gap-1.5 shadow-sm" 
               title="Unduh laporan Excel rapi berformat warna, font, dan tabel (.xls)">
                <svg class="w-3.5 h-3.5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="8" y1="13" x2="16" y2="13"/>
                    <line x1="8" y1="17" x2="16" y2="17"/>
                    <line x1="10" y1="9" x2="10" y2="9"/>
                </svg>
                <span>Export Excel (.xls)</span>
            </a>

            <!-- EXPORT CSV BUTTON -->
            <a href="{{ route('admin.orders.export', array_merge(request()->query(), ['format' => 'csv'])) }}" 
               class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5" 
               title="Unduh data laporan pesanan standar CSV UTF-8">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" x2="12" y1="15" y2="3"/>
                </svg>
                <span>Export (.csv)</span>
            </a>

            <a href="{{ route('admin.payment-methods.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="14" x="2" y="5" rx="2"/>
                    <line x1="2" x2="22" y1="10" y2="10"/>
                </svg>
                <span>Rekening Bank</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m7.5 4.27 9 5.15"/>
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                    <path d="m3.3 7 8.7 5 8.7-5"/>
                    <path d="M12 22V12"/>
                </svg>
                <span>Katalog Produk</span>
            </a>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex flex-wrap items-center gap-1.5">
        <a href="{{ route('admin.orders.index', array_filter(array_merge(request()->query(), ['status' => null]))) }}" 
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ empty($status) ? 'bg-white text-zinc-950 font-semibold shadow-sm' : 'bg-zinc-900/60 border border-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
            Semua ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.orders.index', array_filter(array_merge(request()->query(), ['status' => 'waiting_verification']))) }}" 
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors flex items-center gap-1.5 {{ $status === 'waiting_verification' ? 'bg-zinc-800 text-white border border-zinc-700 font-semibold' : 'bg-zinc-900/60 border border-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
            <span>Perlu Verifikasi</span>
            @if($counts['waiting_verification'] > 0)
                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-semibold tabular-nums bg-amber-500/20 text-amber-400 border border-amber-500/30">
                    {{ $counts['waiting_verification'] }}
                </span>
            @endif
        </a>
        <a href="{{ route('admin.orders.index', array_filter(array_merge(request()->query(), ['status' => 'pending']))) }}" 
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ $status === 'pending' ? 'bg-zinc-800 text-white border border-zinc-700 font-semibold' : 'bg-zinc-900/60 border border-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
            Menunggu Bayar ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.orders.index', array_filter(array_merge(request()->query(), ['status' => 'completed']))) }}" 
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ $status === 'completed' ? 'bg-zinc-800 text-white border border-zinc-700 font-semibold' : 'bg-zinc-900/60 border border-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
            Selesai / Lunas ({{ $counts['completed'] }})
        </a>
        <a href="{{ route('admin.orders.index', array_filter(array_merge(request()->query(), ['status' => 'failed']))) }}" 
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ $status === 'failed' ? 'bg-zinc-800 text-white border border-zinc-700 font-semibold' : 'bg-zinc-900/60 border border-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
            Ditolak / Batal ({{ $counts['failed'] }})
        </a>
    </div>

    <!-- Advanced Precision Filter Bar -->
    <div class="rounded-xl p-4 bg-zinc-900/70 border border-zinc-800 space-y-3">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-2.5">
                <!-- 1. Search Keyword -->
                <div class="lg:col-span-2">
                    <label class="block text-[10px] font-mono text-zinc-400 uppercase tracking-wider mb-1">Cari Keyword</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-zinc-500 text-xs">🔍</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Invoice, nama, HP, ID game..." 
                               class="w-full pl-8 pr-3 py-1.5 rounded-lg bg-zinc-950 border border-zinc-800 text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-zinc-500 font-mono">
                    </div>
                </div>

                <!-- 2. Product Type Filter -->
                <div>
                    <label class="block text-[10px] font-mono text-zinc-400 uppercase tracking-wider mb-1">Tipe Produk</label>
                    <select name="product_type" class="w-full py-1.5 px-2.5 rounded-lg bg-zinc-950 border border-zinc-800 text-xs text-zinc-300 focus:outline-none focus:border-zinc-500">
                        <option value="">Semua Tipe</option>
                        @foreach($productTypes as $type)
                            <option value="{{ $type->value }}" {{ request('product_type') === $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Specific Product Filter -->
                <div>
                    <label class="block text-[10px] font-mono text-zinc-400 uppercase tracking-wider mb-1">Produk Spesifik</label>
                    <select name="product_id" class="w-full py-1.5 px-2.5 rounded-lg bg-zinc-950 border border-zinc-800 text-xs text-zinc-300 focus:outline-none focus:border-zinc-500">
                        <option value="">Semua Produk</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ (string) request('product_id') === (string) $prod->id ? 'selected' : '' }}>
                                {{ $prod->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 4. Status Pesanan Filter -->
                <div>
                    <label class="block text-[10px] font-mono text-zinc-400 uppercase tracking-wider mb-1">Status Pesanan</label>
                    <select name="status" class="w-full py-1.5 px-2.5 rounded-lg bg-zinc-950 border border-zinc-800 text-xs text-zinc-300 focus:outline-none focus:border-zinc-500">
                        <option value="">Semua Status</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai / Lunas</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Ditolak / Batal / Gagal</option>
                        <option value="waiting_verification" {{ request('status') === 'waiting_verification' ? 'selected' : '' }}>Perlu Verifikasi</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Bayar</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                    </select>
                </div>

                <!-- 5. Action Buttons -->
                <div class="flex items-end gap-1.5">
                    <button type="submit" class="flex-1 py-1.5 px-3 rounded-lg bg-zinc-100 hover:bg-white text-zinc-950 text-xs font-bold transition-all shadow-sm">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'product_type', 'product_id', 'clothing_size', 'date_from', 'date_to']))
                        <a href="{{ route('admin.orders.index') }}" 
                           class="py-1.5 px-2.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white text-xs font-mono transition-colors" title="Reset Semua Filter">
                            ✕
                        </a>
                    @endif
                </div>
            </div>

            <!-- Date Range & Active Filter Badges Row -->
            <div class="pt-2 border-t border-zinc-800/60 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[10px] font-mono text-zinc-500 uppercase tracking-wider">Periode Tanggal:</span>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="py-1 px-2 rounded-md bg-zinc-950 border border-zinc-800 text-[11px] text-zinc-300 font-mono focus:outline-none focus:border-zinc-500">
                    <span class="text-zinc-600 text-xs">s/d</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="py-1 px-2 rounded-md bg-zinc-950 border border-zinc-800 text-[11px] text-zinc-300 font-mono focus:outline-none focus:border-zinc-500">
                    <button type="submit" class="px-2 py-1 rounded bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-[10px] font-mono transition-colors">
                        Terapkan Tanggal
                    </button>
                </div>

                <!-- Quick info of current filtered orders -->
                <div class="text-[11px] font-mono text-zinc-400">
                    Total Ditemukan: <span class="text-zinc-100 font-bold tabular-nums">{{ $orders->total() }}</span> pesanan
                </div>
            </div>
        </form>
    </div>

    <!-- Orders Table with Modern Clean Styling -->
    <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800">
        <div class="overflow-x-auto rounded-lg border border-zinc-800">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-900/90 text-zinc-400 uppercase tracking-wider text-[10px] font-semibold border-b border-zinc-800">
                    <tr>
                        <th class="py-2.5 px-3.5 whitespace-nowrap">No. Pesanan &amp; Waktu</th>
                        <th class="py-2.5 px-3.5 whitespace-nowrap">Customer</th>
                        <th class="py-2.5 px-3.5 whitespace-nowrap">Produk Dibeli</th>
                        <th class="py-2.5 px-3.5 whitespace-nowrap">Metode</th>
                        <th class="py-2.5 px-3.5 whitespace-nowrap">Total Bayar</th>
                        <th class="py-2.5 px-3.5 whitespace-nowrap">Bukti Transfer</th>
                        <th class="py-2.5 px-3.5 whitespace-nowrap">Status</th>
                        <th class="py-2.5 px-3.5 font-medium text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60 bg-zinc-950/40">
                    @forelse($orders as $order)
                        <tr class="hover:bg-zinc-900/50 transition-colors">
                            <td class="py-2.5 px-3.5 whitespace-nowrap font-mono">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-medium text-xs text-white hover:text-cyan-400 transition-colors block">
                                    #{{ $order->order_number }}
                                </a>
                                <span class="text-[11px] text-zinc-500 font-sans block mt-0.5">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3.5">
                                <span class="font-medium text-xs text-zinc-200 block truncate max-w-[150px]">{{ $order->customer_name }}</span>
                                <span class="text-[11px] text-zinc-500 font-mono block truncate max-w-[150px]">{{ $order->customer_email }}</span>
                                <span class="text-[10px] text-zinc-500 font-mono">{{ $order->customer_phone ?? '-' }}</span>
                            </td>
                            <td class="py-2.5 px-3.5">
                                @foreach($order->items as $item)
                                    <div>
                                        <span class="font-medium text-xs text-zinc-200">{{ $item->product_name }}</span>
                                        <span class="text-[11px] text-zinc-400">({{ $item->variant_name }})</span>
                                    </div>
                                @endforeach

                                @if($order->isPreorder())
                                    <div class="mt-1 flex flex-wrap items-center gap-1">
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-mono font-bold bg-fuchsia-500/10 text-fuchsia-400 border border-fuchsia-500/20">
                                            Size: {{ $order->clothing_size ?: '-' }}
                                        </span>
                                        @if($order->sleeve_type)
                                            <span class="px-1.5 py-0.2 rounded text-[10px] font-mono bg-zinc-800 text-zinc-300 border border-zinc-700">
                                                {{ $order->sleeve_type }}
                                            </span>
                                        @endif
                                        @if($order->embroidery_name)
                                            <span class="px-1.5 py-0.2 rounded text-[10px] font-mono bg-zinc-800 text-zinc-300 border border-zinc-700" title="Bordir Nama">
                                                "{{ $order->embroidery_name }}"
                                            </span>
                                        @endif
                                    </div>
                                @elseif($order->isGame() || (!empty($order->target_user_id) && !$order->isPreorder()))
                                    <div class="mt-1 flex flex-wrap items-center gap-1">
                                        @if($order->target_user_id)
                                            <span class="px-1.5 py-0.2 rounded text-[10px] font-mono bg-cyan-500/10 text-cyan-400 border border-cyan-500/20" title="User ID & Server">
                                                ID: {{ $order->target_user_id }}{{ $order->target_zone_id ? ' ('.$order->target_zone_id.')' : '' }}
                                            </span>
                                        @endif
                                        @if($order->target_nickname)
                                            <span class="px-1.5 py-0.2 rounded text-[10px] font-mono bg-zinc-800 text-zinc-300 border border-zinc-700">
                                                {{ $order->target_nickname }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="py-2.5 px-3.5 whitespace-nowrap font-mono">
                                <span class="text-xs text-zinc-300">
                                    {{ $order->payment_method }}
                                </span>
                                @if($order->payment?->account_number)
                                    <span class="text-[10px] text-zinc-500 block mt-0.5">
                                        {{ $order->payment->account_number }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3.5 font-mono tabular-nums font-semibold text-xs text-zinc-200 whitespace-nowrap">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-3.5 whitespace-nowrap">
                                @if($order->payment?->payment_proof)
                                    <a href="{{ asset('storage/'.$order->payment->payment_proof) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-medium bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white border border-zinc-700/50 transition-colors">
                                        <span>Lihat Bukti</span>
                                        <span class="text-zinc-500">↗</span>
                                    </a>
                                @else
                                    <span class="text-[11px] text-zinc-500 italic">Belum ada</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3.5 whitespace-nowrap">
                                @if($order->status->value === 'processing' && $order->payment?->payment_proof && $order->payment?->status?->value !== 'paid')
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        <span>Perlu Verifikasi</span>
                                    </span>
                                @elseif($order->status->value === 'completed')
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        <span>Lunas &amp; Selesai</span>
                                    </span>
                                @elseif($order->status->value === 'processing')
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                                        <span>Sedang Diproses</span>
                                    </span>
                                @elseif($order->status->value === 'pending')
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        <span>Menunggu Bayar</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                        <span>Ditolak / Batal</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-zinc-800 hover:bg-zinc-700 text-zinc-200 hover:text-white font-medium transition-colors text-xs border border-zinc-700/50">
                                        <span>Detail</span>
                                        <span class="text-zinc-500">→</span>
                                    </a>
                                    @if(in_array($order->status->value, ['pending', 'processing']) && $order->status->value !== 'completed' && $order->payment?->status?->value !== 'paid')
                                        <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran pesanan #{{ $order->order_number }} dan proses pesanan ke pelanggan?');" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-md bg-emerald-950/40 hover:bg-emerald-900/60 text-emerald-300 border border-emerald-800/40 text-xs font-medium transition-colors">
                                                Konfirmasi
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-zinc-500">
                                Tidak ada data pesanan yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="mt-4 pt-4 border-t border-zinc-800">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

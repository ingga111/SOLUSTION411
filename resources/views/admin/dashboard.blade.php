@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- Dashboard Header & Navigation Hub -->
    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6 pb-6 border-b border-zinc-800/80">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-medium bg-zinc-900 border border-zinc-800 text-zinc-400 mb-2.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Pusat Kendali Toko Digital</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Admin Dashboard & Analitik</h1>
            <p class="text-xs text-zinc-400 mt-1 max-w-2xl">Pantau performa penjualan, ketersediaan inventori akun, dan alur transaksi pesanan secara real-time.</p>
        </div>

        <!-- Management Navigation Hub -->
        <div class="flex flex-wrap items-center gap-1.5">
            <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                    <path d="M9 14h6"/><path d="M9 18h6"/><path d="M9 10h6"/>
                </svg>
                <span>Pesanan</span>
                @if($pendingOrders > 0)
                    <span class="px-1.5 py-0.5 rounded-full bg-amber-500/15 text-amber-400 border border-amber-500/30 text-[10px] font-semibold tabular-nums">{{ $pendingOrders }}</span>
                @endif
            </a>
            <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m7.5 4.27 9 5.15"/>
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                    <path d="m3.3 7 8.7 5 8.7-5"/>
                    <path d="M12 22V12"/>
                </svg>
                <span>Produk</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 8 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/>
                </svg>
                <span>Kategori</span>
            </a>
            <a href="{{ route('admin.payment-methods.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="14" x="2" y="5" rx="2"/>
                    <line x1="2" x2="22" y1="10" y2="10"/>
                </svg>
                <span>Rekening</span>
            </a>
            <a href="{{ route('admin.banners.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                    <circle cx="9" cy="9" r="2"/>
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                </svg>
                <span>Banner</span>
            </a>
            <a href="{{ route('admin.running-texts.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m3 11 18-5v12L3 14v-3z"/>
                    <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>
                </svg>
                <span>Ticker</span>
            </a>
            <a href="{{ route('admin.flash-sales.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
                <span>Flash Sale</span>
            </a>
            <a href="{{ route('admin.vouchers.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/>
                    <path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/>
                </svg>
                <span>Voucher</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span>Users</span>
            </a>
            <a href="{{ route('admin.products.create') }}" class="px-3.5 py-1.5 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 font-semibold text-xs transition-colors inline-flex items-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5 text-zinc-950" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/>
                    <path d="M12 5v14"/>
                </svg>
                <span>+ Tambah Produk</span>
            </a>
        </div>
    </div>

    <!-- Low Stock Alert Banner (If Any Accounts/Variants <= 3 units) -->
    @if($lowStockVariants->isNotEmpty())
        <div class="p-4 sm:p-5 rounded-xl bg-amber-950/20 border border-amber-900/40 text-amber-200">
            <div class="flex items-start gap-3.5">
                <div class="p-2 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20 shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                        <line x1="12" x2="12" y1="9" y2="13"/>
                        <line x1="12" x2="12.01" y1="17" y2="17"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5">
                        <h4 class="font-semibold text-xs text-white">
                            Peringatan Stok Menipis <span class="text-zinc-400 font-normal">({{ $lowStockVariants->count() }} varian perlu restock)</span>
                        </h4>
                        <span class="text-[11px] text-zinc-400 font-normal">Harap segera perbarui kredensial akun digital</span>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($lowStockVariants as $variant)
                            <a href="{{ route('admin.products.edit', $variant->product_id) }}" class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 hover:border-amber-700/60 text-xs transition-colors">
                                <span class="font-medium text-white">{{ $variant->product->name ?? 'Produk' }}</span>
                                <span class="text-zinc-400 font-mono text-[11px]">({{ $variant->name }})</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold tabular-nums {{ $variant->stock === 0 ? 'bg-rose-950/60 text-rose-300 border border-rose-800/50' : 'bg-amber-950/60 text-amber-300 border border-amber-800/50' }}">
                                    Sisa: {{ $variant->stock }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Metric Cards Grid (Linear/Stripe Craft) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Today Revenue -->
        <div class="rounded-xl p-5 bg-zinc-900/60 border border-zinc-800 hover:border-zinc-700 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-400">Pendapatan Hari Ini</span>
                <div class="p-1.5 rounded-md bg-zinc-800/80 border border-zinc-700/50 text-zinc-300">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/>
                        <path d="M12 6v2"/><path d="M12 16v2"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-white tabular-nums tracking-tight mt-3">
                Rp {{ number_format($todayRevenue, 0, ',', '.') }}
            </div>
            <div class="flex items-center gap-1.5 text-[11px] text-zinc-400 mt-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Pesanan diproses & lunas hari ini</span>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="rounded-xl p-5 bg-zinc-900/60 border border-zinc-800 hover:border-zinc-700 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-400">Pendapatan Bulan Ini</span>
                <div class="p-1.5 rounded-md bg-zinc-800/80 border border-zinc-700/50 text-zinc-300">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/>
                        <polyline points="16 7 22 7 22 13"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-white tabular-nums tracking-tight mt-3">
                Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
            </div>
            <div class="flex items-center gap-1.5 text-[11px] text-zinc-400 mt-2">
                <span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span>
                <span>Periode {{ date('F Y') }}</span>
            </div>
        </div>

        <!-- Orders Overview (Pending & Processing) -->
        <div class="rounded-xl p-5 bg-zinc-900/60 border border-zinc-800 hover:border-zinc-700 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-400">Antrean Pesanan</span>
                <div class="p-1.5 rounded-md bg-zinc-800/80 border border-zinc-700/50 text-zinc-300">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2.5 mt-3">
                <span class="text-2xl font-bold text-white tabular-nums tracking-tight">{{ $processingOrders }}</span>
                <span class="text-xs text-zinc-400">Proses</span>
                <span class="text-zinc-600">/</span>
                <span class="text-xl font-semibold text-amber-400 tabular-nums tracking-tight">{{ $pendingOrders }}</span>
                <span class="text-xs text-zinc-400">Pending</span>
            </div>
            <div class="text-[11px] text-zinc-400 mt-2">
                Total keseluruhan: <span class="font-medium text-zinc-200 tabular-nums">{{ $totalOrders }}</span> transaksi
            </div>
        </div>

        <!-- Stock & Customer Summary -->
        <div class="rounded-xl p-5 bg-zinc-900/60 border border-zinc-800 hover:border-zinc-700 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-400">Stok Akun & Pelanggan</span>
                <div class="p-1.5 rounded-md bg-zinc-800/80 border border-zinc-700/50 text-zinc-300">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/>
                        <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/>
                        <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2.5 mt-3">
                <span class="text-2xl font-bold text-white tabular-nums tracking-tight">{{ $availableAccountStocks }}</span>
                <span class="text-xs text-zinc-400">Stok Siap</span>
                <span class="text-zinc-600">/</span>
                <span class="text-xl font-semibold text-zinc-300 tabular-nums tracking-tight">{{ $totalCustomers }}</span>
                <span class="text-xs text-zinc-400">Member</span>
            </div>
            <div class="text-[11px] text-zinc-400 mt-2">
                Inventori akun digital & voucher terdaftar
            </div>
        </div>
    </div>

    <!-- Visual Analytics: 7-Day Revenue Trend Chart -->
    <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h3 class="text-sm font-semibold text-white tracking-tight">Tren Penjualan 7 Hari Terakhir</h3>
                <p class="text-xs text-zinc-400 mt-0.5">Dinamika pendapatan harian dari transaksi produk game dan akun digital.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-zinc-800/80 text-zinc-300 border border-zinc-700/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                    <span>Omzet Harian</span>
                </span>
            </div>
        </div>

        <div class="relative w-full h-64 sm:h-72">
            <canvas id="salesTrendChart"></canvas>
        </div>
    </div>

    <!-- Recent Orders Table (Linear/Stripe Craft) -->
    <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <h3 class="text-sm font-semibold text-white tracking-tight">10 Transaksi Terbaru</h3>
                <p class="text-xs text-zinc-400 mt-0.5">Pesanan masuk terkini yang membutuhkan verifikasi atau sedang diproses.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1 text-xs font-medium text-zinc-400 hover:text-white transition-colors">
                <span>Lihat Semua Pesanan ({{ $totalOrders }})</span>
                <span class="text-zinc-500">→</span>
            </a>
        </div>

        <div class="overflow-x-auto rounded-lg border border-zinc-800">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-900/90 text-zinc-400 uppercase tracking-wider text-[10px] font-semibold border-b border-zinc-800">
                    <tr>
                        <th class="py-2.5 px-3.5">No. Invoice</th>
                        <th class="py-2.5 px-3.5">Pelanggan</th>
                        <th class="py-2.5 px-3.5">Kategori</th>
                        <th class="py-2.5 px-3.5">Total Pembayaran</th>
                        <th class="py-2.5 px-3.5">Metode</th>
                        <th class="py-2.5 px-3.5">Status</th>
                        <th class="py-2.5 px-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60 bg-zinc-950/40">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-zinc-900/50 transition-colors">
                            <td class="py-2.5 px-3.5 font-mono">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-medium text-xs text-white hover:text-cyan-400 transition-colors">
                                    {{ $order->order_number }}
                                </a>
                                <span class="block text-[11px] font-sans text-zinc-500 mt-0.5">
                                    {{ $order->created_at->diffForHumans() }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3.5">
                                <span class="font-medium text-xs text-zinc-200 block truncate max-w-[150px]">{{ $order->customer_name }}</span>
                                <span class="text-[11px] text-zinc-500 font-mono block truncate max-w-[150px]">{{ $order->customer_email }}</span>
                            </td>
                            <td class="py-2.5 px-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-medium border bg-zinc-800/80 text-zinc-300 border-zinc-700/60 uppercase tracking-wider">
                                    {{ $order->product_type->label() }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3.5 font-mono tabular-nums font-semibold text-xs text-zinc-200">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-3.5 text-xs font-mono text-zinc-400">
                                {{ $order->payment_method }}
                            </td>
                            <td class="py-2.5 px-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-1 rounded-full text-[11px] font-medium
                                    @if($order->status->value === 'completed') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                    @elseif($order->status->value === 'processing') bg-blue-500/10 text-blue-400 border border-blue-500/20
                                    @elseif($order->status->value === 'pending') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                    @else bg-rose-500/10 text-rose-400 border border-rose-500/20 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full @if($order->status->value === 'completed') bg-emerald-400 @elseif($order->status->value === 'processing') bg-blue-400 @elseif($order->status->value === 'pending') bg-amber-400 @else bg-rose-400 @endif"></span>
                                    <span>{{ $order->status->label() }}</span>
                                </span>
                            </td>
                            <td class="py-2.5 px-3.5 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white font-medium transition-colors text-xs border border-zinc-700/50">
                                    <span>Detail</span>
                                    <span class="text-zinc-500">→</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-zinc-500">
                                Belum ada riwayat transaksi pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Chart.js Script for Revenue Analytics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('salesTrendChart');
        if (!ctx) return;

        const labels = @json($salesChartLabels);
        const dataValues = @json($salesChartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: dataValues,
                    borderColor: '#38bdf8',
                    backgroundColor: 'rgba(56, 189, 248, 0.06)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#38bdf8',
                    pointBorderColor: '#18181b',
                    pointBorderWidth: 2,
                    pointRadius: 3.5,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#18181b',
                        titleColor: '#f4f4f5',
                        bodyColor: '#38bdf8',
                        borderColor: '#27272a',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + Number(context.raw).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.04)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#71717a',
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.04)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#71717a',
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(1) + ' jt';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(0) + ' rb';
                                }
                                return value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Dashboard Header with Quick Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 mb-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                <span>Pusat Kendali Toko Digital</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Admin Dashboard & Analitik</h1>
            <p class="text-xs text-gray-400 mt-1">Pantau performa penjualan, ketersediaan inventori, dan status pesanan real-time.</p>
        </div>

        <!-- Management Navigation Hub -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.orders.index') }}" class="px-3.5 py-2 rounded-xl bg-cyan-500/15 hover:bg-cyan-500/25 text-cyan-300 border border-cyan-500/30 text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                <span>📋 Pesanan</span>
                @if($pendingOrders > 0)
                    <span class="px-1.5 py-0.2 rounded-full bg-amber-500 text-slate-950 text-[10px] font-black">{{ $pendingOrders }}</span>
                @endif
            </a>
            <a href="{{ route('admin.products.index') }}" class="px-3.5 py-2 rounded-xl bg-indigo-500/15 hover:bg-indigo-500/25 text-indigo-300 border border-indigo-500/30 text-xs font-bold transition-all flex items-center gap-1.5">
                <span>📦 Produk</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="px-3.5 py-2 rounded-xl bg-pink-500/15 hover:bg-pink-500/25 text-pink-300 border border-pink-500/30 text-xs font-bold transition-all flex items-center gap-1.5">
                <span>📁 Kategori</span>
            </a>
            <a href="{{ route('admin.payment-methods.index') }}" class="px-3.5 py-2 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-300 border border-emerald-500/30 text-xs font-bold transition-all flex items-center gap-1.5">
                <span>💳 Rekening</span>
            </a>
            <a href="{{ route('admin.banners.index') }}" class="px-3.5 py-2 rounded-xl bg-purple-500/15 hover:bg-purple-500/25 text-purple-300 border border-purple-500/30 text-xs font-bold transition-all flex items-center gap-1.5">
                <span>🖼️ Banner</span>
            </a>
            <a href="{{ route('admin.running-texts.index') }}" class="px-3.5 py-2 rounded-xl bg-teal-500/15 hover:bg-teal-500/25 text-teal-300 border border-teal-500/30 text-xs font-bold transition-all flex items-center gap-1.5">
                <span>📢 Ticker</span>
            </a>
            <a href="{{ route('admin.flash-sales.index') }}" class="px-3.5 py-2 rounded-xl bg-amber-500/15 hover:bg-amber-500/25 text-amber-300 border border-amber-500/30 text-xs font-bold transition-all flex items-center gap-1.5">
                <span>🔥 Flash Sale</span>
            </a>
            <a href="{{ route('admin.vouchers.index') }}" class="px-3.5 py-2 rounded-xl bg-rose-500/15 hover:bg-rose-500/25 text-rose-300 border border-rose-500/30 text-xs font-bold transition-all flex items-center gap-1.5">
                <span>🎟️ Voucher</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-bold transition-all flex items-center gap-1.5">
                <span>👥 Users</span>
            </a>
            <a href="{{ route('admin.products.create') }}" class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-slate-950 font-black text-xs shadow-md shadow-cyan-500/20 transition-all flex items-center gap-1.5">
                <span>+ Tambah Produk</span>
            </a>
        </div>
    </div>

    <!-- Low Stock Alert Banner (If Any Accounts/Variants <= 3 units) -->
    @if($lowStockVariants->isNotEmpty())
        <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-200 backdrop-blur-sm shadow-lg">
            <div class="flex items-start gap-3.5">
                <div class="p-2 rounded-xl bg-amber-500/20 text-amber-400 shrink-0 text-xl">
                    ⚠️
                </div>
                <div class="flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <h4 class="font-bold text-sm text-white">
                            Peringatan Stok Menipis! ({{ $lowStockVariants->count() }} item hampir habis)
                        </h4>
                        <span class="text-[11px] text-amber-300 font-medium">Harap segera isi ulang stok kredensial akun</span>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($lowStockVariants as $variant)
                            <a href="{{ route('admin.products.edit', $variant->product_id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-500/20 hover:bg-amber-500/30 border border-amber-500/40 text-xs font-medium transition-colors">
                                <span class="font-bold text-white">{{ $variant->product->name ?? 'Produk' }}</span>
                                <span class="text-amber-300">({{ $variant->name }})</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-black {{ $variant->stock === 0 ? 'bg-rose-500 text-white' : 'bg-amber-400 text-slate-950' }}">
                                    Sisa: {{ $variant->stock }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Clean Sleek Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Today Revenue -->
        <div class="glass-card rounded-2xl p-5 border border-slate-800/80 hover:border-emerald-500/30 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-400">Pendapatan Hari Ini</span>
                <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 text-sm">💵</span>
            </div>
            <div class="text-2xl font-black text-emerald-400 mt-3 tracking-tight">
                Rp {{ number_format($todayRevenue, 0, ',', '.') }}
            </div>
            <div class="flex items-center gap-1.5 text-[11px] text-gray-400 mt-2">
                <span class="text-emerald-400 font-bold">●</span>
                <span>Pesanan diproses & lunas hari ini</span>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="glass-card rounded-2xl p-5 border border-slate-800/80 hover:border-cyan-500/30 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-400">Pendapatan Bulan Ini</span>
                <span class="p-2 rounded-xl bg-cyan-500/10 text-cyan-400 text-sm">📈</span>
            </div>
            <div class="text-2xl font-black text-cyan-400 mt-3 tracking-tight">
                Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
            </div>
            <div class="flex items-center gap-1.5 text-[11px] text-gray-400 mt-2">
                <span class="text-cyan-400 font-bold">●</span>
                <span>Periode {{ date('F Y') }}</span>
            </div>
        </div>

        <!-- Orders Overview (Pending & Processing) -->
        <div class="glass-card rounded-2xl p-5 border border-slate-800/80 hover:border-blue-500/30 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-400">Antrean Pesanan</span>
                <span class="p-2 rounded-xl bg-blue-500/10 text-blue-400 text-sm">⚡</span>
            </div>
            <div class="flex items-baseline gap-3 mt-3">
                <span class="text-2xl font-black text-blue-400">{{ $processingOrders }}</span>
                <span class="text-xs text-gray-400">Proses Top-Up</span>
                <span class="text-slate-600">|</span>
                <span class="text-xl font-black text-amber-400">{{ $pendingOrders }}</span>
                <span class="text-xs text-gray-400">Pending</span>
            </div>
            <div class="text-[11px] text-gray-400 mt-2">
                Total keseluruhan: <span class="font-bold text-white">{{ $totalOrders }}</span> transaksi
            </div>
        </div>

        <!-- Stock & Customer Summary -->
        <div class="glass-card rounded-2xl p-5 border border-slate-800/80 hover:border-purple-500/30 transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-400">Stok Akun & Pengguna</span>
                <span class="p-2 rounded-xl bg-purple-500/10 text-purple-400 text-sm">👥</span>
            </div>
            <div class="flex items-baseline gap-3 mt-3">
                <span class="text-2xl font-black text-purple-400">{{ $availableAccountStocks }}</span>
                <span class="text-xs text-gray-400">Stok Siap</span>
                <span class="text-slate-600">|</span>
                <span class="text-xl font-black text-white">{{ $totalCustomers }}</span>
                <span class="text-xs text-gray-400">Member</span>
            </div>
            <div class="text-[11px] text-gray-400 mt-2">
                Inventori akun digital & voucher terdaftar
            </div>
        </div>
    </div>

    <!-- Visual Analytics: 7-Day Revenue Trend Chart -->
    <div class="glass-card rounded-3xl p-6 border border-slate-800 mb-8 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h3 class="text-base sm:text-lg font-bold text-white tracking-tight">Tren Penjualan 7 Hari Terakhir</h3>
                <p class="text-xs text-gray-400 mt-0.5">Grafik dinamika pendapatan harian dari transaksi game top-up dan akun digital.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                    <span class="w-2 h-2 rounded-full bg-cyan-400"></span> Omzet Harian
                </span>
            </div>
        </div>

        <div class="relative w-full h-64 sm:h-72">
            <canvas id="salesTrendChart"></canvas>
        </div>
    </div>

    <!-- Recent Orders Table with Modern Clean Styling -->
    <div class="glass-card rounded-3xl p-6 border border-slate-800 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h3 class="text-base sm:text-lg font-bold text-white tracking-tight">10 Transaksi Terbaru</h3>
                <p class="text-xs text-gray-400 mt-0.5">Daftar pesanan masuk terbaru yang memerlukan verifikasi atau sedang diproses.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-cyan-400 hover:text-cyan-300 transition-colors">
                <span>Lihat Semua Pesanan ({{ $totalOrders }})</span>
                <span>→</span>
            </a>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-800/80">
            <table class="w-full text-left text-xs text-gray-300">
                <thead class="bg-slate-900/80 text-gray-400 uppercase tracking-wider text-[10px] border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4 font-bold">No. Invoice</th>
                        <th class="py-3.5 px-4 font-bold">Pelanggan</th>
                        <th class="py-3.5 px-4 font-bold">Kategori</th>
                        <th class="py-3.5 px-4 font-bold">Total Pembayaran</th>
                        <th class="py-3.5 px-4 font-bold">Metode</th>
                        <th class="py-3.5 px-4 font-bold">Status</th>
                        <th class="py-3.5 px-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 bg-slate-950/40">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-900/50 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-white">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-cyan-400 hover:underline">
                                    {{ $order->order_number }}
                                </a>
                                <span class="block text-[10px] font-sans font-normal text-gray-500 mt-0.5">
                                    {{ $order->created_at->diffForHumans() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-white block">{{ $order->customer_name }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">{{ $order->customer_email }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $order->isGame() ? 'bg-indigo-500/15 text-indigo-300 border border-indigo-500/30' : 'bg-cyan-500/15 text-cyan-300 border border-cyan-500/30' }}">
                                    {{ $order->product_type->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-black text-white font-mono">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-cyan-400 font-mono font-semibold">
                                {{ $order->payment_method }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @if($order->status->value === 'completed') bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                                    @elseif($order->status->value === 'processing') bg-blue-500/20 text-blue-300 border border-blue-500/30
                                    @elseif($order->status->value === 'pending') bg-amber-500/20 text-amber-300 border border-amber-500/30
                                    @else bg-rose-500/20 text-rose-300 border border-rose-500/30 @endif">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-cyan-400 hover:text-white font-bold transition-all text-xs">
                                    <span>Detail</span>
                                    <span>→</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-gray-500">
                                Belum ada riwayat pesanan.
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
                    borderColor: '#06b6d4',
                    backgroundColor: 'rgba(6, 182, 212, 0.12)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#06b6d4',
                    pointBorderColor: '#0B0E14',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
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
                        backgroundColor: '#121824',
                        titleColor: '#F8FAFC',
                        bodyColor: '#22d3ee',
                        borderColor: '#1E293B',
                        borderWidth: 1,
                        padding: 12,
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
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#94a3b8',
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

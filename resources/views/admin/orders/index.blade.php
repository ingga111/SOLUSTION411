@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header with Quick Action & Export -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-cyan-400 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-white">Daftar Pesanan &amp; Verifikasi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Verifikasi Pembayaran &amp; Pesanan</h1>
            <p class="text-xs text-gray-400 mt-1">Periksa bukti transfer manual pembeli dan konfirmasi pengiriman produk digital.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- EXPORT TO EXCEL BUTTON -->
            <a href="{{ route('admin.orders.export', request()->query()) }}" class="px-4 py-2.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-300 text-xs font-bold border border-emerald-500/40 transition-all flex items-center gap-2 shadow-sm" title="Unduh data laporan pesanan dalam format Excel CSV">
                <span class="text-base">📊</span>
                <span>Export ke Excel (.csv)</span>
            </a>

            <a href="{{ route('admin.payment-methods.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-gray-200 text-xs font-semibold border border-slate-800 transition-all flex items-center gap-1.5">
                <span>💳 Rekening Bank</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-gray-200 text-xs font-semibold border border-slate-800 transition-all flex items-center gap-1.5">
                <span>📦 Produk &amp; Stok Akun</span>
            </a>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 mb-6">
        <a href="{{ route('admin.orders.index') }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($status) ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-slate-950 shadow-md shadow-cyan-500/20 font-black' : 'bg-slate-900/90 border border-slate-800 text-gray-400 hover:text-white' }}">
            Semua Pesanan ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'waiting_verification']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all relative flex items-center gap-1.5 {{ $status === 'waiting_verification' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-600/30' : 'bg-slate-900/90 border border-slate-800 text-cyan-400 hover:text-cyan-300' }}">
            <span>🔔 Menunggu Verifikasi</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $counts['waiting_verification'] > 0 ? 'bg-cyan-400 text-slate-950 animate-pulse' : 'bg-slate-800 text-gray-300' }}">
                {{ $counts['waiting_verification'] }}
            </span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'pending' ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/30' : 'bg-slate-900/90 border border-slate-800 text-amber-400 hover:text-amber-300' }}">
            Menunggu Transfer ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'completed' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'bg-slate-900/90 border border-slate-800 text-emerald-400 hover:text-emerald-300' }}">
            Selesai / Lunas ({{ $counts['completed'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'failed']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'failed' ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'bg-slate-900/90 border border-slate-800 text-rose-400 hover:text-rose-300' }}">
            Ditolak / Batal ({{ $counts['failed'] }})
        </a>
    </div>

    <!-- Orders Table with Modern Clean Styling -->
    <div class="glass-card rounded-3xl p-6 border border-slate-800 shadow-xl">
        <div class="overflow-x-auto rounded-2xl border border-slate-800/80">
            <table class="w-full text-left text-xs text-gray-300">
                <thead class="bg-slate-900/90 text-gray-400 uppercase tracking-wider text-[10px] border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4 font-bold whitespace-nowrap">No. Pesanan &amp; Waktu</th>
                        <th class="py-3.5 px-4 font-bold whitespace-nowrap">Customer</th>
                        <th class="py-3.5 px-4 font-bold whitespace-nowrap">Produk Dibeli</th>
                        <th class="py-3.5 px-4 font-bold whitespace-nowrap">Rekening Tujuan</th>
                        <th class="py-3.5 px-4 font-bold whitespace-nowrap">Total Bayar</th>
                        <th class="py-3.5 px-4 font-bold whitespace-nowrap">Bukti Transfer</th>
                        <th class="py-3.5 px-4 font-bold whitespace-nowrap">Status Pesanan</th>
                        <th class="py-3.5 px-4 font-bold text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 bg-slate-950/40">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-900/50 transition-colors">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-mono font-bold text-cyan-400 hover:text-cyan-300 block">
                                    #{{ $order->order_number }}
                                </a>
                                <span class="text-[10px] text-gray-500 block mt-0.5">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-white block">{{ $order->customer_name }}</span>
                                <span class="text-[11px] text-gray-400 font-mono block">{{ $order->customer_email }}</span>
                                <span class="text-[10px] text-gray-500 font-mono">{{ $order->customer_phone ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                @foreach($order->items as $item)
                                    <div>
                                        <span class="font-semibold text-white">{{ $item->product_name }}</span>
                                        <span class="text-[10px] text-cyan-300">({{ $item->variant_name }})</span>
                                    </div>
                                @endforeach
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700 text-cyan-300 font-mono text-[11px] font-bold">
                                    {{ $order->payment_method }}
                                </span>
                                @if($order->payment?->account_number)
                                    <span class="text-[10px] text-gray-400 block mt-1 font-mono">
                                        {{ $order->payment->account_number }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-black text-white text-sm font-mono whitespace-nowrap">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($order->payment?->payment_proof)
                                    <a href="{{ asset('storage/'.$order->payment->payment_proof) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-cyan-500/15 border border-cyan-500/30 text-cyan-300 hover:text-white hover:bg-cyan-500/25 text-xs font-bold transition-all shadow-sm">
                                        <span>📎 Lihat Bukti</span>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-500 italic">Belum ada bukti</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($order->status->value === 'processing' && $order->payment?->payment_proof && $order->payment?->status?->value !== 'paid')
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 shadow-sm animate-pulse">
                                        <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                                        <span>Perlu Verifikasi</span>
                                    </span>
                                @elseif($order->status->value === 'completed')
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                        <span>✓ Lunas &amp; Selesai</span>
                                    </span>
                                @elseif($order->status->value === 'processing')
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-bold bg-blue-500/20 text-blue-300 border border-blue-500/40 shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                        <span>Sedang Diproses</span>
                                    </span>
                                @elseif($order->status->value === 'pending')
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                        <span>Menunggu Bayar</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40 shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                                        <span>Ditolak / Batal</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-400 hover:text-white font-bold transition-all text-xs">
                                        <span>Detail</span>
                                        <span>→</span>
                                    </a>
                                    @if(in_array($order->status->value, ['pending', 'processing']) && $order->status->value !== 'completed' && $order->payment?->status?->value !== 'paid')
                                        <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran pesanan #{{ $order->order_number }} dan proses pesanan ke pelanggan?');" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-slate-950 font-black text-xs transition-all shadow-md shadow-emerald-600/20">
                                                Konfirmasi ✓
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-500">
                                Tidak ada data pesanan yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection

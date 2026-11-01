@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 pb-20">

    <!-- Breadcrumb (Linear Style) -->
    <div class="flex items-center gap-2 text-xs font-mono text-zinc-500 dark:text-zinc-400 mb-5">
        <a href="{{ route('home') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Beranda</a>
        <span>/</span>
        <a href="{{ route('customer.orders.index') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Pesanan Saya</a>
        <span>/</span>
        <span class="text-zinc-900 dark:text-zinc-200 font-bold">{{ $order->order_number }}</span>
    </div>

    <!-- Header Card -->
    <div class="bg-white dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm mb-6 font-mono">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-zinc-100 dark:border-zinc-800">
            <div>
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                    <h1 class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white">{{ $order->order_number }}</h1>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                        @if($order->status->value === 'completed') bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20
                        @elseif($order->status->value === 'processing') bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20
                        @elseif($order->status->value === 'pending') bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20
                        @else bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 @endif">
                        ● {{ $order->status->label() }}
                    </span>
                </div>
                <p class="text-xs text-zinc-500 font-sans">
                    Dibuat pada {{ $order->created_at->format('d F Y, H:i') }} WIB • Tipe: {{ $order->product_type->label() }}
                </p>
            </div>
            <div>
                <a href="{{ route('orders.show', $order->order_number) }}" class="px-3.5 py-1.5 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-bold text-xs hover:bg-zinc-800 dark:hover:bg-white transition-all shadow-sm">
                    Lihat Invoice Pembayaran →
                </a>
            </div>
        </div>

        <!-- Target / Customization Specs -->
        <div class="mt-4">
            <h3 class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider mb-2.5">Informasi Akun / Tujuan Pengiriman</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 bg-zinc-50 dark:bg-zinc-950 p-3.5 rounded-xl border border-zinc-200 dark:border-zinc-800 text-xs">
                @if($order->isPreorder())
                    <div>
                        <span class="text-zinc-500 block text-[10px]">Ukuran Baju:</span>
                        <span class="font-bold text-zinc-900 dark:text-white">{{ $order->clothing_size ?? $order->target_user_id }}</span>
                    </div>
                    <div>
                        <span class="text-zinc-500 block text-[10px]">Model Lengan:</span>
                        <span class="font-bold text-zinc-900 dark:text-white">{{ $order->sleeve_type ?? $order->target_zone_id }}</span>
                    </div>
                    <div>
                        <span class="text-zinc-500 block text-[10px]">Bordir Nama:</span>
                        <span class="font-bold text-zinc-900 dark:text-white">{{ $order->embroidery_name ?: ($order->target_nickname ?: '-') }}</span>
                    </div>
                @elseif($order->isGame())
                    <div>
                        <span class="text-zinc-500 block text-[10px]">User ID Game:</span>
                        <span class="font-bold text-zinc-900 dark:text-white">{{ $order->target_user_id }}</span>
                    </div>
                    @if($order->target_zone_id)
                        <div>
                            <span class="text-zinc-500 block text-[10px]">Zone / Server ID:</span>
                            <span class="font-bold text-zinc-900 dark:text-white">{{ $order->target_zone_id }}</span>
                        </div>
                    @endif
                @endif
                <div>
                    <span class="text-zinc-500 block text-[10px]">Nama Kontak:</span>
                    <span class="font-semibold text-zinc-900 dark:text-white font-sans">{{ $order->customer_name }}</span>
                </div>
                <div>
                    <span class="text-zinc-500 block text-[10px]">Nomor WhatsApp:</span>
                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $order->customer_phone }}</span>
                </div>
                <div>
                    <span class="text-zinc-500 block text-[10px]">Email Notifikasi:</span>
                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $order->customer_email }}</span>
                </div>
            </div>
        </div>

        <!-- Purchased Items Breakdown -->
        <div class="mt-6">
            <h3 class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider mb-2.5">Item Layanan yang Dipesan</h3>
            <div class="space-y-2">
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-xs">
                        <div>
                            <span class="font-bold text-zinc-900 dark:text-white block font-sans">{{ $item->product_name }}</span>
                            <span class="text-zinc-500 text-[11px] mt-0.5 block">{{ $item->variant_name }} (x{{ $item->quantity }})</span>
                        </div>
                        <span class="font-bold text-zinc-900 dark:text-white tabular-nums">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Price Totals -->
        <div class="mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-800 space-y-1.5 text-xs text-zinc-500">
            <div class="flex justify-between">
                <span>Subtotal Item</span>
                <span class="text-zinc-900 dark:text-white tabular-nums">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($order->discount_amount > 0)
                <div class="flex justify-between text-emerald-600 dark:text-emerald-400 font-bold">
                    <span>Diskon Voucher</span>
                    <span class="tabular-nums">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between">
                <span>Biaya Layanan</span>
                <span class="text-zinc-900 dark:text-white tabular-nums">Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t border-zinc-100 dark:border-zinc-800 font-bold text-sm">
                <span class="text-zinc-900 dark:text-white uppercase tracking-wider">Total Pembayaran</span>
                <span class="text-zinc-900 dark:text-zinc-100 tabular-nums">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

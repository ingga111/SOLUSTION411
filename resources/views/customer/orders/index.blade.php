@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6 pb-20">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-zinc-900 dark:text-zinc-100 font-mono tracking-tight">Riwayat Pesanan</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Daftar transaksi top up game, akun digital, dan preorder Anda.</p>
        </div>
        <a href="{{ route('home') }}" class="self-start sm:self-auto px-3.5 py-1.5 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-mono font-bold text-xs hover:bg-zinc-800 dark:hover:bg-white transition-all shadow-sm">
            + Pesan Layanan Baru
        </a>
    </div>

    <div class="bg-white dark:bg-zinc-900/90 rounded-2xl p-5 border border-zinc-200 dark:border-zinc-800 shadow-sm">
        <div class="space-y-2.5">
            @forelse($orders as $order)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3.5 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 hover:border-zinc-400 dark:hover:border-zinc-700 transition-all font-mono">
                    <div>
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <span class="font-bold text-zinc-900 dark:text-white text-xs sm:text-sm">{{ $order->order_number }}</span>
                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase bg-zinc-200 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border border-zinc-300 dark:border-zinc-700">
                                {{ $order->product_type->label() }}
                            </span>
                        </div>
                        <p class="text-[11px] text-zinc-500 font-sans">
                            {{ $order->items->pluck('product_name')->implode(', ') }} • {{ $order->created_at->format('d M Y, H:i') }} WIB
                        </p>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-4">
                        <div class="text-left sm:text-right">
                            <span class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-zinc-100 block tabular-nums">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider
                                @if($order->status->value === 'completed') text-emerald-600 dark:text-emerald-400
                                @elseif($order->status->value === 'processing') text-cyan-600 dark:text-cyan-400
                                @elseif($order->status->value === 'pending') text-amber-600 dark:text-amber-400
                                @else text-rose-600 dark:text-rose-400 @endif">
                                ● {{ $order->status->label() }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('customer.orders.show', $order->order_number) }}" class="px-2.5 py-1 rounded-md bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-bold text-xs border border-zinc-200 dark:border-zinc-700 transition-all">
                                Detail
                            </a>
                            <a href="{{ route('orders.show', $order->order_number) }}" class="px-2.5 py-1 rounded-md bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-bold text-xs hover:bg-zinc-800 dark:hover:bg-white transition-all shadow-sm">
                                Invoice →
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-zinc-500 font-mono text-xs flex flex-col items-center">
                    <span class="text-3xl mb-2">📦</span>
                    <p class="font-bold text-zinc-700 dark:text-zinc-300">Belum Ada Riwayat Pesanan</p>
                    <p class="text-zinc-400 mt-0.5 max-w-sm">Anda belum memiliki transaksi pesanan top-up atau akun digital.</p>
                    <a href="{{ route('home') }}" class="mt-3 px-3.5 py-1.5 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-bold text-xs transition-all shadow-sm">
                        Mulai Belanja
                    </a>
                </div>
            @endforelse
        </div>

        @if($orders->hasPages())
            <div class="mt-5 pt-3 border-t border-zinc-100 dark:border-zinc-800 font-mono text-xs">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

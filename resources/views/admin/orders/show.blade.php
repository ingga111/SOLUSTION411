@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-zinc-800/80">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-zinc-200 transition-colors">Dashboard</a>
                <span class="text-zinc-600">/</span>
                <a href="{{ route('admin.orders.index') }}" class="hover:text-zinc-200 transition-colors">Pesanan</a>
                <span class="text-zinc-600">/</span>
                <span class="text-zinc-200">#{{ $order->order_number }}</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Pesanan #{{ $order->order_number }}</h1>
                @if($order->status->value === 'processing' && $order->payment?->payment_proof && $order->payment?->status?->value !== 'paid')
                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        <span>Perlu Verifikasi Bukti</span>
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
                        <span>Menunggu Pembayaran</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                        <span>Ditolak / Batal</span>
                    </span>
                @endif
            </div>
            <p class="text-xs text-zinc-400 mt-1">Dibuat pada {{ $order->created_at->format('d F Y, H:i:s') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white text-xs font-medium transition-colors">
                ← Kembali
            </a>
            <a href="{{ route('orders.show', $order->order_number) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <span>Halaman Customer</span>
                <span class="text-zinc-500">↗</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column: Payment Proof & Actions -->
        <div class="lg:col-span-6 space-y-6">
            
            <!-- Payment Proof Card -->
            <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-800">
                    <h3 class="text-xs font-semibold text-white tracking-tight uppercase">
                        Bukti Transfer Pembeli
                    </h3>
                    @if($order->payment?->payment_proof_uploaded_at)
                        <span class="text-[11px] text-zinc-500 font-mono tabular-nums">
                            Diunggah: {{ $order->payment->payment_proof_uploaded_at->format('d M, H:i') }}
                        </span>
                    @endif
                </div>

                @if($order->payment?->payment_proof)
                    <div class="rounded-lg overflow-hidden border border-zinc-800 bg-zinc-950 flex flex-col items-center">
                        @php
                            $extension = pathinfo($order->payment->payment_proof, PATHINFO_EXTENSION);
                            $isPdf = strtolower($extension) === 'pdf';
                        @endphp

                        @if($isPdf)
                            <div class="p-8 text-center">
                                <svg class="w-12 h-12 text-zinc-400 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" x2="8" y1="13" y2="13"/>
                                    <line x1="16" x2="8" y1="17" y2="17"/>
                                    <polyline points="10 9 9 9 8 9"/>
                                </svg>
                                <span class="text-xs font-medium text-white block">Dokumen Bukti PDF</span>
                                <a href="{{ asset('storage/'.$order->payment->payment_proof) }}" target="_blank" class="mt-3 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 font-semibold text-xs shadow-sm transition-colors">
                                    Buka File PDF ↗
                                </a>
                            </div>
                        @else
                            <a href="{{ asset('storage/'.$order->payment->payment_proof) }}" target="_blank" class="block group relative w-full text-center">
                                <img src="{{ asset('storage/'.$order->payment->payment_proof) }}" alt="Bukti Transfer" class="w-full max-h-96 object-contain rounded-t-lg group-hover:opacity-90 transition-opacity">
                                <div class="py-2 bg-zinc-900 border-t border-zinc-800 text-center text-xs text-zinc-400 group-hover:text-white transition-colors">
                                    Klik untuk Membuka Ukuran Penuh ↗
                                </div>
                            </a>
                        @endif
                    </div>
                @else
                    <div class="p-8 text-center rounded-lg border border-dashed border-zinc-800 bg-zinc-950/40">
                        <svg class="w-8 h-8 text-zinc-600 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <h4 class="text-xs font-medium text-zinc-400">Belum Ada Bukti Pembayaran</h4>
                        <p class="text-[11px] text-zinc-500 mt-1">Pembeli belum mengunggah foto atau file bukti transfer.</p>
                    </div>
                @endif
            </div>

            <!-- Verification Action Box -->
            @if(in_array($order->status->value, ['pending', 'processing']) && $order->status->value !== 'completed' && $order->payment?->status?->value !== 'paid')
                <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800 space-y-4">
                    <div class="pb-2 border-b border-zinc-800">
                        <h3 class="text-xs font-semibold text-white tracking-tight uppercase">Aksi Verifikasi Admin</h3>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Pastikan mutasi dana telah masuk ke rekening sebelum mengonfirmasi pesanan.</p>
                    </div>

                    <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin dana sebesar Rp {{ number_format($order->total_amount, 0, ',', '.') }} sudah masuk ke rekening? Pesanan akan ditandai selesai.');">
                        @csrf
                        <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 font-semibold text-xs shadow-sm transition-colors flex items-center justify-center gap-1.5">
                            <span>✓ Konfirmasi Pembayaran Valid &amp; Selesaikan Pesanan</span>
                        </button>
                    </form>

                    <!-- Reject Section -->
                    <div class="pt-3 border-t border-zinc-800">
                        <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST" onsubmit="return confirm('Tolak bukti pembayaran ini? Status pesanan akan menjadi Ditolak.');">
                            @csrf
                            <label class="block text-[11px] font-medium text-zinc-400 mb-1.5">Alasan Penolakan</label>
                            <div class="flex gap-2">
                                <input type="text" name="reason" placeholder="Contoh: Mutasi dana belum diterima di rekening" class="flex-1 bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-1.5 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-700">
                                <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-rose-950/40 hover:bg-rose-900/60 border border-rose-800/40 text-rose-300 font-medium text-xs transition-colors">
                                    Tolak ✕
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="rounded-xl p-5 bg-zinc-900/60 border border-zinc-800">
                    <span class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider block mb-1">Status Final</span>
                    <p class="text-xs font-medium text-zinc-200">
                        Pesanan ini berstatus <span class="text-emerald-400 font-semibold uppercase">{{ $order->status->value }}</span> (Status Pembayaran: <span class="text-zinc-200 font-semibold uppercase">{{ $order->payment?->status?->value ?? '-' }}</span>).
                    </p>
                    @if($order->payment?->admin_note)
                        <div class="mt-2.5 p-2.5 bg-zinc-950 rounded-lg border border-zinc-800 text-xs text-rose-300">
                            <strong>Catatan:</strong> {{ $order->payment->admin_note }}
                        </div>
                    @endif
                </div>
            @endif

            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                } elseif (!str_starts_with($cleanPhone, '62') && !empty($cleanPhone)) {
                    $cleanPhone = '62' . $cleanPhone;
                }

                $firstItem = $order->items->first();
                $productLabel = $firstItem ? ($firstItem->product_name . ' - ' . $firstItem->variant_name) : 'Pesanan';

                if ($order->isPreorder()) {
                    $waMessage = "Halo kak {$order->customer_name}! 👋\n"
                        . "Terima kasih telah melakukan Preorder di solustion411.\n\n"
                        . "Pesanan Anda telah kami konfirmasi:\n"
                        . "• Invoice: #{$order->order_number}\n"
                        . "• Produk: {$productLabel}\n"
                        . "• Ukuran Baju: " . ($order->clothing_size ?? $order->target_user_id) . "\n"
                        . "• Pilihan Lengan: " . ($order->sleeve_type ?? $order->target_zone_id) . "\n"
                        . "• Bordir Dada / NIM: " . ($order->embroidery_name ?: ($order->target_nickname ?: '-')) . "\n"
                        . ($order->notes ? "• Catatan: {$order->notes}\n" : "")
                        . "• Status Tagihan: " . ($order->isPaid() ? "LUNAS ✓" : "Menunggu Verifikasi Pembayaran") . "\n\n"
                        . "Spesifikasi baju sudah kami catat ke antrean produksi konveksi. Kami akan mengabari jadwal pengambilan melalui WhatsApp ini ya kak. Terima kasih! 🙏";
                } elseif ($order->isPulsa()) {
                    $waMessage = "Halo kak {$order->customer_name}! ⚡\n"
                        . "Terima kasih telah bertransaksi di solustion411.\n\n"
                        . "Pesanan Pulsa / Token Listrik Anda sedang diproses:\n"
                        . "• Invoice: #{$order->order_number}\n"
                        . "• Layanan: {$productLabel}\n"
                        . "• No. HP / ID Pelanggan PLN: {$order->target_user_id}\n\n"
                        . "Status: Sukses Terkirim / Kode Token: \n\n"
                        . "Terima kasih banyak telah berbelanja di tempat kami! 🙏";
                } else {
                    $waMessage = "Halo kak {$order->customer_name}! 👋\n"
                        . "Terima kasih telah berbelanja di solustion411.\n\n"
                        . "Pesanan Anda telah kami konfirmasi:\n"
                        . "• Invoice: #{$order->order_number}\n"
                        . "• Produk: {$productLabel}\n"
                        . "• Qty: " . ($firstItem?->quantity ?? 1) . "\n\n"
                        . "Berikut detail akun / pesanan Anda:\n"
                        . "📧 Email / Username: \n"
                        . "🔑 Password: \n"
                        . "📌 PIN Profil / Catatan: \n\n"
                        . "Silakan langsung dicoba login ya kak. Jika ada kendala segera kabari kami. Terima kasih banyak! 🙏";
                }

                $waLink = "https://wa.me/{$cleanPhone}?text=" . urlencode($waMessage);
            @endphp

            <!-- WHATSAPP DELIVERY & CONFIRMATION HELPER -->
            <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-zinc-800">
                    <h3 class="text-xs font-semibold text-white tracking-tight uppercase">
                        {{ $order->isPreorder() ? 'Konfirmasi Preorder via WhatsApp' : ($order->isPulsa() ? 'Kirim Pulsa/Token via WhatsApp' : 'Kirim Akun via WhatsApp') }}
                    </h3>
                    <span class="text-[10px] text-zinc-400 font-mono">
                        {{ $order->customer_phone }}
                    </span>
                </div>

                <p class="text-xs text-zinc-400">
                    Salin draft atau langsung buka percakapan WhatsApp dengan pembeli:
                </p>

                <div class="bg-zinc-950 border border-zinc-800 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] text-zinc-500 uppercase tracking-wider font-semibold">Draft Pesan:</span>
                        <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('wa-admin-template').innerText); alert('Draft pesan WA disalin ke clipboard!');" class="text-[11px] text-zinc-300 hover:text-white transition-colors">
                            Salin Teks
                        </button>
                    </div>
                    <pre id="wa-admin-template" class="text-xs font-mono text-zinc-300 whitespace-pre-wrap leading-relaxed select-all">{{ $waMessage }}</pre>
                </div>

                <div class="pt-1">
                    <a href="{{ $waLink }}" target="_blank" class="w-full py-2.5 px-4 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-200 hover:text-white border border-zinc-700/60 font-medium text-xs transition-colors flex items-center justify-center gap-1.5">
                        <span>Buka Chat WhatsApp</span>
                        <span class="text-zinc-500">↗</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Right Column: Order Details & Customer Info -->
        <div class="lg:col-span-6 space-y-6">
            
            <!-- Customer Information Card -->
            <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800 space-y-3">
                <h3 class="text-xs font-semibold text-white tracking-tight uppercase pb-2 border-b border-zinc-800">
                    Data Pembeli &amp; Tujuan
                </h3>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between py-1 border-b border-zinc-800/60">
                        <span class="text-zinc-400">Nama Lengkap:</span>
                        <span class="font-medium text-white">{{ $order->customer_name }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-800/60">
                        <span class="text-zinc-400">Email:</span>
                        <span class="font-medium text-zinc-200 font-mono">{{ $order->customer_email }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-800/60">
                        <span class="text-zinc-400">WhatsApp:</span>
                        <a href="{{ $waLink }}" target="_blank" class="font-mono text-zinc-300 hover:text-white transition-colors flex items-center gap-1">
                            {{ $order->customer_phone }} <span>↗</span>
                        </a>
                    </div>
                    @if($order->isPreorder())
                        <div class="mt-2.5 p-3 bg-zinc-950 border border-zinc-800 rounded-lg space-y-1.5 text-xs">
                            <span class="text-[11px] font-semibold text-zinc-300 uppercase tracking-wider block mb-1">Spesifikasi Baju PDH</span>
                            <div class="flex justify-between py-0.5 border-b border-zinc-800/60">
                                <span class="text-zinc-400">Ukuran:</span>
                                <span class="font-medium font-mono text-zinc-200">{{ $order->clothing_size ?? $order->target_user_id }}</span>
                            </div>
                            <div class="flex justify-between py-0.5 border-b border-zinc-800/60">
                                <span class="text-zinc-400">Pilihan Lengan:</span>
                                <span class="font-medium text-zinc-200">{{ $order->sleeve_type ?? $order->target_zone_id }}</span>
                            </div>
                            <div class="flex justify-between py-0.5 border-b border-zinc-800/60">
                                <span class="text-zinc-400">Bordir Dada / NIM:</span>
                                <span class="font-medium text-zinc-200">{{ $order->embroidery_name ?: ($order->target_nickname ?: '-') }}</span>
                            </div>
                            @if($order->notes)
                                <div class="pt-1">
                                    <span class="text-zinc-400 block text-[11px]">Catatan:</span>
                                    <span class="text-zinc-300 text-[11px] italic">{{ $order->notes }}</span>
                                </div>
                            @endif
                        </div>
                    @elseif($order->isPulsa())
                        <div class="flex justify-between py-1 border-b border-zinc-800/60">
                            <span class="text-zinc-400">Nomor HP / No. Meter:</span>
                            <span class="font-mono font-medium text-emerald-400 font-bold select-all">{{ $order->target_user_id }}</span>
                        </div>
                    @elseif($order->isJoki())
                        <div class="flex justify-between py-1 border-b border-zinc-800/60">
                            <span class="text-zinc-400">Login Via:</span>
                            <span class="font-medium text-zinc-200">{{ $order->target_zone_id }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-zinc-800/60">
                            <span class="text-zinc-400">Email/ID Akun:</span>
                            <span class="font-mono font-medium text-zinc-200">{{ $order->target_user_id }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-zinc-800/60">
                            <span class="text-zinc-400">Password:</span>
                            <span class="font-mono font-medium text-zinc-200 select-all">{{ $order->target_nickname }}</span>
                        </div>
                        @if($order->notes)
                            <div class="py-1 border-b border-zinc-800/60">
                                <span class="text-zinc-400 block text-[11px]">Catatan / Hero:</span>
                                <span class="text-zinc-300 text-[11px] italic">{{ $order->notes }}</span>
                            </div>
                        @endif
                    @else
                        @if($order->target_user_id)
                            <div class="flex justify-between py-1 border-b border-zinc-800/60">
                                <span class="text-zinc-400">Target User ID:</span>
                                <span class="font-mono font-medium text-zinc-200">{{ $order->target_user_id }}</span>
                            </div>
                        @endif
                        @if($order->target_zone_id)
                            <div class="flex justify-between py-1 border-b border-zinc-800/60">
                                <span class="text-zinc-400">Target Zone ID:</span>
                                <span class="font-mono font-medium text-zinc-200">{{ $order->target_zone_id }}</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Items Purchased Card -->
            <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800 space-y-3">
                <h3 class="text-xs font-semibold text-white tracking-tight uppercase pb-2 border-b border-zinc-800">
                    Item yang Dipesan
                </h3>

                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="p-3.5 rounded-lg bg-zinc-900/80 border border-zinc-800 space-y-2">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-medium text-xs text-white block">{{ $item->product_name }}</span>
                                    <span class="text-[11px] text-zinc-400">{{ $item->variant_name }}</span>
                                </div>
                                <span class="font-semibold text-xs text-zinc-200 font-mono tabular-nums">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>
                            </div>

                            @if(!empty($item->credentials_delivered))
                                <div class="mt-2.5 pt-2.5 border-t border-zinc-800">
                                    <span class="text-[10px] font-semibold text-emerald-400 uppercase tracking-wider block mb-1">
                                        Kredensial Akun / Lisensi Terkirim:
                                    </span>
                                    <div class="p-2.5 bg-zinc-950 rounded border border-emerald-500/20 font-mono text-xs text-emerald-300 break-all select-all whitespace-pre-wrap">{{ $item->credentials_delivered }}</div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Transfer & Payment Summary -->
            <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800 space-y-3">
                <h3 class="text-xs font-semibold text-white tracking-tight uppercase pb-2 border-b border-zinc-800">
                    Rincian Pembayaran
                </h3>

                <div class="space-y-2 text-xs text-zinc-300">
                    <div class="flex justify-between">
                        <span class="text-zinc-400">Metode:</span>
                        <span class="font-medium text-white">{{ $order->payment_method }}</span>
                    </div>
                    @if($order->payment?->account_number)
                        <div class="flex justify-between">
                            <span class="text-zinc-400">Rekening Tujuan:</span>
                            <span class="font-mono font-medium text-zinc-200">{{ $order->payment->account_number }}</span>
                        </div>
                    @endif
                    @if($order->payment?->account_holder)
                        <div class="flex justify-between">
                            <span class="text-zinc-400">Atas Nama:</span>
                            <span class="font-medium text-white">{{ $order->payment->account_holder }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between pt-2 border-t border-zinc-800">
                        <span class="text-zinc-400">Subtotal Item:</span>
                        <span class="text-zinc-200 font-mono tabular-nums">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-emerald-400 font-medium">
                            <span class="flex items-center gap-1.5">
                                <span>Diskon Voucher</span>
                                @if($order->voucher_code)
                                    <span class="text-[10px] bg-emerald-500/10 px-1.5 py-0.2 rounded border border-emerald-500/20 font-mono">{{ $order->voucher_code }}</span>
                                @endif
                            </span>
                            <span class="font-mono tabular-nums">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-zinc-400">Biaya Admin:</span>
                        <span class="text-zinc-200 font-mono tabular-nums">Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-zinc-800 font-semibold text-sm">
                        <span class="text-white">Total Tagihan:</span>
                        <span class="text-white font-mono tabular-nums text-base">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

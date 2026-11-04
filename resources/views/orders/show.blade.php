@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6 pb-20">

    <!-- Breadcrumb (Linear Style) -->
    <div class="flex items-center gap-2 text-xs font-mono text-zinc-500 dark:text-zinc-400 mb-5">
        <a href="{{ route('home') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Beranda</a>
        <span>/</span>
        <span class="text-zinc-500">Invoice</span>
        <span>/</span>
        <span class="text-zinc-900 dark:text-zinc-200 font-bold">{{ $order->order_number }}</span>
    </div>

    <!-- Status Alert Header & 4-Step Stepper -->
    <div class="bg-white dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 mb-6 text-center shadow-sm">
        @if($order->status->value === 'completed')
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 mb-2.5 text-2xl border border-emerald-500/20 font-mono">
                ✓
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-zinc-900 dark:text-zinc-100 tracking-tight">Pesanan Berhasil Diselesaikan!</h1>
            @if($order->isPreorder())
                <p class="text-xs text-zinc-500 mt-0.5">Pembayaran telah dikonfirmasi. Pesanan Preorder PDH / Seragam Anda telah dicatat dalam daftar produksi konveksi.</p>
            @elseif($order->isAccount() || $order->isGameAccount())
                <p class="text-xs text-zinc-500 mt-0.5">Pembayaran telah dikonfirmasi. Detail akun Anda akan dikirimkan oleh Admin langsung ke WhatsApp Anda.</p>
            @else
                <p class="text-xs text-zinc-500 mt-0.5">Item pesanan Anda telah sukses diproses dan diselesaikan.</p>
            @endif
        @elseif($order->status->value === 'processing')
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 mb-2.5 text-2xl border border-cyan-500/20 font-mono">
                ⚡
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-zinc-900 dark:text-zinc-100 tracking-tight">Pembayaran Diterima &amp; Sedang Diproses</h1>
            @if($order->isPreorder())
                <p class="text-xs text-zinc-500 mt-0.5">Pembayaran telah kami terima. Pesanan Preorder PDH Anda sedang diverifikasi admin dan masuk ke antrean konveksi.</p>
            @elseif($order->isAccount() || $order->isGameAccount())
                <p class="text-xs text-zinc-500 mt-0.5">Pembayaran telah kami terima. Admin sedang menyiapkan akun dan akan segera mengirimkannya ke WhatsApp Anda.</p>
            @else
                <p class="text-xs text-zinc-500 mt-0.5">Sistem sedang memproses pesanan ke ID Akun game Anda. Mohon ditunggu beberapa saat.</p>
            @endif
        @elseif($order->status->value === 'pending')
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 mb-2.5 text-2xl border border-amber-500/20 font-mono">
                💳
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-zinc-900 dark:text-zinc-100 tracking-tight">Menunggu Pembayaran</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Selesaikan transfer sesuai nominal di bawah untuk proses verifikasi pesanan.</p>
        @else
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 mb-2.5 text-2xl border border-rose-500/20 font-mono">
                ✕
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-zinc-900 dark:text-zinc-100 tracking-tight">Pesanan {{ $order->status->label() }}</h1>
        @endif

        <!-- 4-Step Visual Progress Stepper -->
        <div class="mt-6 pt-5 border-t border-zinc-100 dark:border-zinc-800">
            <div class="grid grid-cols-4 gap-2 text-center font-mono">
                <!-- Step 1 -->
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-md flex items-center justify-center text-xs font-bold mb-1 {{ in_array($order->status->value, ['pending', 'processing', 'completed']) ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-400' }}">
                        1
                    </div>
                    <span class="text-[10px] sm:text-[11px] {{ in_array($order->status->value, ['pending', 'processing', 'completed']) ? 'text-zinc-900 dark:text-white font-semibold' : 'text-zinc-400' }}">
                        Menunggu Bayar
                    </span>
                </div>

                <!-- Step 2 -->
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-md flex items-center justify-center text-xs font-bold mb-1 {{ in_array($order->status->value, ['processing', 'completed']) || $order->payment?->payment_proof ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-400' }}">
                        2
                    </div>
                    <span class="text-[10px] sm:text-[11px] {{ in_array($order->status->value, ['processing', 'completed']) || $order->payment?->payment_proof ? 'text-zinc-900 dark:text-white font-semibold' : 'text-zinc-400' }}">
                        Bukti Diunggah
                    </span>
                </div>

                <!-- Step 3 -->
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-md flex items-center justify-center text-xs font-bold mb-1 {{ in_array($order->status->value, ['processing', 'completed']) ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-400' }}">
                        3
                    </div>
                    <span class="text-[10px] sm:text-[11px] {{ in_array($order->status->value, ['processing', 'completed']) ? 'text-zinc-900 dark:text-white font-semibold' : 'text-zinc-400' }}">
                        Diproses
                    </span>
                </div>

                <!-- Step 4 -->
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-md flex items-center justify-center text-xs font-bold mb-1 {{ $order->status->value === 'completed' ? 'bg-emerald-500 text-white' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-400' }}">
                        4
                    </div>
                    <span class="text-[10px] sm:text-[11px] {{ $order->status->value === 'completed' ? 'text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-zinc-400' }}">
                        Selesai
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- WHATSAPP DELIVERY NOTICE CARD (For Account / Game Account products) -->
    @if($order->isAccount() || $order->isGameAccount())
        <div class="bg-zinc-50 dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 mb-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 mb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center text-base flex-shrink-0">
                        📱
                    </div>
                    <div>
                        <h3 class="font-bold text-xs sm:text-sm text-zinc-900 dark:text-zinc-100 font-mono">Pengiriman Akun Dilakukan via WhatsApp</h3>
                        <p class="text-[11px] text-zinc-500">Akun tidak diberikan di web demi keamanan dan privasi kredensial Anda.</p>
                    </div>
                </div>
                <span class="text-[9px] font-mono uppercase tracking-wider px-2 py-0.5 rounded bg-zinc-200 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">
                    Direct WA Delivery
                </span>
            </div>

            <div class="bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3.5 space-y-2">
                <p class="text-xs text-zinc-700 dark:text-zinc-300 font-mono leading-relaxed">
                    Admin kami akan menghubungi nomor WhatsApp Anda: <strong class="text-zinc-900 dark:text-white">{{ $order->customer_phone }}</strong> dan memberikan detail akun lengkap.
                </p>
                <p class="text-[11px] text-zinc-500">
                    Pastikan nomor WhatsApp aktif untuk menerima kredensial akun.
                </p>
            </div>

            <div class="mt-3 pt-3 border-t border-zinc-200 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <span class="text-[11px] text-zinc-500">Ada pertanyaan terkait pesanan?</span>
                <a href="https://wa.me/6285150718231?text={{ urlencode('Halo Admin solustion411, saya sudah membayar pesanan #' . $order->order_number . ' untuk ' . ($order->items->first()?->product_name ?? 'Produk Digital') . '. Mohon konfirmasi pengiriman akun saya. Terima kasih!') }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-mono font-bold text-xs hover:bg-zinc-800 dark:hover:bg-white transition-all shadow-sm">
                    <span>Chat WhatsApp Admin ↗</span>
                </a>
            </div>
        </div>
    @endif

    <!-- PREORDER PDH & UNIFORM NOTICE CARD -->
    @if($order->isPreorder())
        <div class="bg-zinc-50 dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 mb-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 mb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center text-base flex-shrink-0">
                        👕
                    </div>
                    <div>
                        <h3 class="font-bold text-xs sm:text-sm text-zinc-900 dark:text-zinc-100 font-mono">Preorder PDH &amp; Uniform Tercatat</h3>
                        <p class="text-[11px] text-zinc-500">Data ukuran baju, model lengan, dan bordir nama Anda sudah masuk ke sistem konveksi.</p>
                    </div>
                </div>
                <span class="text-[9px] font-mono uppercase tracking-wider px-2 py-0.5 rounded bg-zinc-200 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">
                    Preorder Konveksi
                </span>
            </div>

            <div class="bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3.5 space-y-2">
                <p class="text-xs text-zinc-700 dark:text-zinc-300 font-mono leading-relaxed">
                    Pesanan atas nama <strong class="text-zinc-900 dark:text-white">{{ $order->customer_name }}</strong> (No. WA: <strong class="text-zinc-900 dark:text-white">{{ $order->customer_phone }}</strong>) akan diproduksi sesuai spesifikasi.
                </p>
                <p class="text-[11px] text-zinc-500">
                    Update progres produksi dikabarkan via WhatsApp. Simpan nomor invoice ini untuk pengambilan baju.
                </p>
            </div>

            <div class="mt-3 pt-3 border-t border-zinc-200 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <span class="text-[11px] text-zinc-500">Konfirmasi ukuran atau progres?</span>
                <a href="https://wa.me/6285150718231?text={{ urlencode('Halo Admin solustion411, saya ingin konfirmasi Preorder PDH #' . $order->order_number . ' atas nama ' . $order->customer_name . ' (Ukuran: ' . ($order->clothing_size ?? $order->target_user_id) . ', Lengan: ' . ($order->sleeve_type ?? $order->target_zone_id) . '). Terima kasih!') }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-mono font-bold text-xs hover:bg-zinc-800 dark:hover:bg-white transition-all shadow-sm">
                    <span>Chat Admin Konveksi ↗</span>
                </a>
            </div>
        </div>
    @endif

    <!-- PAYMENT INSTRUCTIONS & PROOF UPLOAD CARD (For Pending) -->
    @if(!$order->isPaid() && $order->payment)
        <div class="bg-white dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 mb-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3.5 mb-4 gap-2">
                <div>
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white font-mono uppercase tracking-wider">Instruksi Transfer Pembayaran</h3>
                    <p class="text-xs text-zinc-500 mt-0.5">Saluran: <strong class="text-zinc-900 dark:text-white">{{ $order->payment->payment_name }}</strong></p>
                </div>
                <div class="sm:text-right font-mono">
                    <span class="text-[10px] text-zinc-500 uppercase tracking-wider block">Total Tagihan Transfer</span>
                    <span class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-zinc-100 tabular-nums">Rp {{ number_format($order->payment->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Manual Account Details Box -->
            <div class="bg-zinc-50 dark:bg-zinc-950 rounded-xl p-4 border border-zinc-200 dark:border-zinc-800 mb-4 space-y-3 font-mono">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <span class="text-[10px] text-zinc-500 uppercase block mb-1">Nomor Rekening / No. HP:</span>
                        <div class="flex items-center justify-between bg-white dark:bg-zinc-900 px-3 py-2 rounded-lg border border-zinc-200 dark:border-zinc-800">
                            <span id="account-number" class="text-base font-bold text-zinc-900 dark:text-white tracking-wider">
                                {{ $order->payment->account_number ?? $order->payment->pay_code ?? 'Rekening Toko' }}
                            </span>
                            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('account-number').innerText.trim()); alert('Nomor Rekening berhasil disalin!');" class="ml-2 px-2.5 py-1 rounded bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-xs font-bold text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 transition-all flex items-center gap-1 cursor-pointer">
                                <span>Salin</span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <span class="text-[10px] text-zinc-500 uppercase block mb-1">Atas Nama Pemilik:</span>
                        <div class="bg-white dark:bg-zinc-900 px-3 py-2 rounded-lg border border-zinc-200 dark:border-zinc-800">
                            <span class="text-xs font-bold text-zinc-900 dark:text-white block">
                                {{ $order->payment->account_holder ?? 'solustion411 Official' }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($order->payment->qr_url)
                    <div class="flex flex-col items-center justify-center p-3 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800">
                        <span class="text-xs text-zinc-700 dark:text-zinc-300 mb-2">Scan QRIS (Semua Bank &amp; E-Wallet):</span>
                        <div class="p-2 bg-white rounded-lg border border-zinc-200">
                            <img src="{{ $order->payment->qr_url }}" alt="QRIS Toko" class="w-40 h-40 object-contain">
                        </div>
                    </div>
                @endif

                <p class="text-[11px] text-zinc-600 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-900/60 p-2.5 rounded-lg border border-zinc-200 dark:border-zinc-800 font-sans">
                    💡 <strong>Penting:</strong> Transfer tepat sejumlah <strong>Rp {{ number_format($order->payment->total_amount, 0, ',', '.') }}</strong> agar pesanan otomatis terdeteksi. Lampirkan foto bukti transfer di bawah ini.
                </p>
            </div>

            <!-- PAYMENT PROOF SECTION -->
            <div class="border-t border-zinc-100 dark:border-zinc-800 pt-4">
                @if($order->payment->payment_proof)
                    <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 font-mono">
                        <div class="flex items-center justify-between mb-2.5">
                            <h4 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">Bukti Transfer Berhasil Diunggah</h4>
                            <span class="text-[10px] text-zinc-500">
                                {{ $order->payment->payment_proof_uploaded_at?->diffForHumans() ?? 'Baru saja' }}
                            </span>
                        </div>

                        <div class="flex items-center gap-3 mb-3">
                            <a href="{{ asset('storage/' . $order->payment->payment_proof) }}" target="_blank" class="block w-16 h-16 rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-800 flex-shrink-0 bg-white dark:bg-zinc-900">
                                <img src="{{ asset('storage/' . $order->payment->payment_proof) }}" alt="Bukti Transfer" class="w-full h-full object-cover">
                            </a>
                            <div>
                                <p class="text-xs text-zinc-700 dark:text-zinc-300 font-semibold font-sans">Bukti Pembayaran Terkirim</p>
                                <a href="{{ asset('storage/' . $order->payment->payment_proof) }}" target="_blank" class="text-[11px] text-zinc-500 hover:underline font-medium mt-0.5 inline-block">
                                    🔍 Buka Gambar Ukuran Penuh
                                </a>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 text-xs text-zinc-500 font-sans">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>Sedang diverifikasi admin. Layar ini otomatis diperbarui jika pembayaran telah disetujui.</span>
                        </div>
                    </div>
                @else
                    <div class="bg-zinc-50 dark:bg-zinc-950 rounded-xl p-4 border border-zinc-200 dark:border-zinc-800">
                        <h4 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider font-mono mb-1">Unggah Bukti Pembayaran</h4>
                        <p class="text-[11px] text-zinc-500 mb-3 leading-relaxed">
                            Kirimkan screenshot m-Banking, resi ATM, atau notifikasi transfer e-wallet untuk verifikasi.
                        </p>

                        <form action="{{ route('orders.upload-proof', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Foto Bukti Transfer <span class="text-rose-500">*</span></label>
                                <input type="file" name="payment_proof" accept="image/*,.pdf" required class="w-full text-xs text-zinc-700 dark:text-zinc-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-mono file:font-bold file:bg-zinc-900 file:text-white dark:file:bg-zinc-100 dark:file:text-zinc-950 hover:file:opacity-90 cursor-pointer bg-white dark:bg-zinc-900 p-2 rounded-lg border border-zinc-200 dark:border-zinc-800 font-mono">
                                @error('payment_proof')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Catatan / Nama Pengirim (Opsional)</label>
                                <input type="text" name="notes" placeholder="Contoh: Transfer atas nama Budi" class="w-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                            </div>

                            <button type="submit" class="w-full py-2.5 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-bold font-mono text-xs hover:bg-zinc-800 dark:hover:bg-white transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-sm">
                                <span>Kirim Bukti Pembayaran Sekarang →</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Invoice Details Receipt Card (Linear / Stripe Style) -->
    <div class="bg-white dark:bg-zinc-900/90 rounded-2xl p-5 sm:p-6 border border-zinc-200 dark:border-zinc-800 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-zinc-100 dark:border-zinc-800 pb-4 mb-4 font-mono">
            <div>
                <span class="text-[10px] text-zinc-500 uppercase block">Nomor Invoice</span>
                <span class="font-bold text-sm sm:text-base text-zinc-900 dark:text-white">{{ $order->order_number }}</span>
            </div>
            <div>
                <span class="text-[10px] text-zinc-500 uppercase block">Waktu Transaksi</span>
                <span class="text-xs text-zinc-700 dark:text-zinc-300">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
            </div>
            <div>
                <span class="text-[10px] text-zinc-500 uppercase block">Status</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                    @if($order->status->value === 'completed') bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20
                    @elseif($order->status->value === 'processing') bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20
                    @elseif($order->status->value === 'pending') bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20
                    @else bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 @endif">
                    {{ $order->status->label() }}
                </span>
            </div>
        </div>

        <!-- Target Destination Information -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-mono bg-zinc-50 dark:bg-zinc-950 p-3.5 rounded-xl border border-zinc-200 dark:border-zinc-800 mb-4">
            @if($order->isPreorder())
                <div class="sm:col-span-2 p-3 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg space-y-2">
                    <div class="flex items-center justify-between text-zinc-900 dark:text-white font-bold text-xs">
                        <span class="flex items-center gap-1.5 uppercase tracking-wider"><span>👕</span> Detail Preorder PDH &amp; Seragam</span>
                        <span class="text-[9px] bg-zinc-100 dark:bg-zinc-800 text-zinc-500 px-1.5 py-0.2 rounded border border-zinc-200 dark:border-zinc-700">Preorder Spec</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-1 text-[11px]">
                        <div>
                            <span class="text-zinc-500 block">Ukuran Baju:</span>
                            <span class="font-bold text-zinc-900 dark:text-white inline-block mt-0.5">
                                {{ $order->clothing_size ?? $order->target_user_id }}
                            </span>
                        </div>
                        <div>
                            <span class="text-zinc-500 block">Pilihan Lengan:</span>
                            <span class="font-semibold text-zinc-900 dark:text-white inline-block mt-0.5">
                                {{ $order->sleeve_type ?? $order->target_zone_id }}
                            </span>
                        </div>
                        <div>
                            <span class="text-zinc-500 block">Bordir Dada / NIM:</span>
                            <span class="font-semibold text-zinc-900 dark:text-white inline-block mt-0.5">
                                {{ $order->embroidery_name ?: ($order->target_nickname ?: '-') }}
                            </span>
                        </div>
                    </div>
                    @if($order->notes)
                        <div class="pt-1.5 border-t border-zinc-100 dark:border-zinc-800 text-[10px]">
                            <span class="text-zinc-500">Catatan Khusus:</span>
                            <span class="text-zinc-700 dark:text-zinc-300 italic ml-1">"{{ $order->notes }}"</span>
                        </div>
                    @endif
                </div>
            @elseif($order->isPulsa())
                <div>
                    <span class="text-zinc-500 block">Nomor Tujuan / No. Meter PLN:</span>
                    <span class="font-bold text-zinc-900 dark:text-white">{{ $order->target_user_id }}</span>
                </div>
            @elseif($order->isGame())
                <div>
                    <span class="text-zinc-500 block">Tujuan Akun Game:</span>
                    <span class="font-bold text-zinc-900 dark:text-white">{{ $order->target_user_id }} {{ $order->target_zone_id ? "({$order->target_zone_id})" : '' }}</span>
                </div>
            @elseif($order->isJoki())
                <div>
                    <span class="text-zinc-500 block">Akun Joki:</span>
                    <span class="font-bold text-zinc-900 dark:text-white">{{ $order->target_user_id }} (Via: {{ $order->target_zone_id }})</span>
                </div>
            @endif
            <div>
                <span class="text-zinc-500 block">Nama Pemesan:</span>
                <span class="font-semibold text-zinc-900 dark:text-white">{{ $order->customer_name }}</span>
            </div>
            <div>
                <span class="text-zinc-500 block">No. WhatsApp:</span>
                <span class="font-semibold text-zinc-900 dark:text-white">{{ $order->customer_phone }}</span>
            </div>
            <div>
                <span class="text-zinc-500 block">Email Pengiriman:</span>
                <span class="font-semibold text-zinc-900 dark:text-white">{{ $order->customer_email }}</span>
            </div>
        </div>

        <!-- Order Items Breakdown -->
        <div class="space-y-2 mb-4 font-mono">
            <span class="text-[10px] text-zinc-500 uppercase tracking-wider block">Rincian Pembelian</span>
            @foreach($order->items as $item)
                <div class="flex items-center justify-between text-xs p-3 bg-zinc-50 dark:bg-zinc-950 rounded-lg border border-zinc-200 dark:border-zinc-800">
                    <div>
                        <span class="font-bold text-zinc-900 dark:text-white block font-sans">{{ $item->product_name }}</span>
                        <span class="text-[11px] text-zinc-500">{{ $item->variant_name }}</span>
                    </div>
                    <span class="font-bold text-zinc-900 dark:text-white tabular-nums">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <!-- Price Totals -->
        <div class="space-y-1.5 text-xs font-mono border-t border-zinc-100 dark:border-zinc-800 pt-3 text-zinc-500">
            <div class="flex justify-between">
                <span>Subtotal Item</span>
                <span class="text-zinc-900 dark:text-white tabular-nums">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($order->discount_amount > 0)
                <div class="flex justify-between text-emerald-600 dark:text-emerald-400 font-bold">
                    <span class="flex items-center gap-1">
                        <span>🎟️ Diskon Voucher</span>
                        @if($order->voucher_code)
                            <span class="text-[10px] bg-emerald-500/10 px-1 py-0.2 rounded border border-emerald-500/20 font-mono">{{ $order->voucher_code }}</span>
                        @endif
                    </span>
                    <span class="tabular-nums">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between">
                <span>Biaya Layanan</span>
                <span class="text-zinc-900 dark:text-white tabular-nums">Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t border-zinc-100 dark:border-zinc-800 font-bold text-sm">
                <span class="text-zinc-900 dark:text-white uppercase tracking-wider">Total Pembayaran</span>
                <span class="text-zinc-900 dark:text-zinc-100 font-bold text-base tabular-nums">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- CS Help Button & Back -->
        <div class="mt-6 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex flex-col sm:flex-row items-center justify-between gap-3 font-mono text-xs">
            <a href="{{ route('home') }}" class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors">
                ← Kembali ke Beranda
            </a>
            <a href="https://wa.me/6285150718231?text={{ urlencode('Halo Admin solustion411, saya ingin konfirmasi invoice ' . $order->order_number) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 font-bold transition-all flex items-center gap-1">
                <span>📱 Chat WhatsApp CS</span>
            </a>
        </div>
    </div>

</div>

@push('scripts')
@if(in_array($order->status->value, ['pending', 'processing']))
<script>
    const orderNumber = "{{ $order->order_number }}";
    const checkStatusUrl = "{{ route('orders.check-status', $order->order_number) }}";

    // Auto poll status every 6 seconds
    const interval = setInterval(async () => {
        try {
            const res = await fetch(checkStatusUrl);
            const data = await res.json();
            if (data.is_paid || data.status === 'completed') {
                clearInterval(interval);
                window.location.reload();
            }
        } catch (e) {
            console.error('Status check error:', e);
        }
    }, 6000);
</script>
@endif
@endpush
@endsection

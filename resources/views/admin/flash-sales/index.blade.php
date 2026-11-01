@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-zinc-200">Kelola Flash Sale Event</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-white flex items-center gap-2.5">
                Kelola Flash Sale Event Hari Ini
            </h1>
            <p class="text-xs text-zinc-400 mt-1">Atur banner ticker countdown promo Flash Sale yang tampil di halaman beranda toko secara dinamis.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-zinc-300 hover:text-white text-xs font-medium border border-zinc-800 transition-colors">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Lihat Beranda</span>
            </a>
            <button type="button" onclick="document.getElementById('modal-create-flashsale').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>Buat Event Baru</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 mb-6 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs space-y-1">
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $error }}</span>
                </p>
            @endforeach
        </div>
    @endif

    <!-- 1. LIVE PREVIEW BANNER -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-400 flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Tampilan Langsung di Beranda (Live Preview)</span>
            </h3>
            <span class="inline-flex items-center gap-1.5 text-xs {{ $currentFlashSale ? 'text-emerald-400' : 'text-zinc-500' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $currentFlashSale ? 'bg-emerald-400' : 'bg-zinc-600' }}"></span>
                <span>{{ $currentFlashSale ? 'Sedang Tayang di Beranda' : 'Tidak Ada Event Aktif' }}</span>
            </span>
        </div>

        @if($currentFlashSale)
            <div class="rounded-xl p-5 border border-zinc-800 bg-zinc-900/70 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 flex-shrink-0">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1.001A3.75 3.75 0 0012 18z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-semibold text-white">{{ $currentFlashSale->title }}</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                {{ $currentFlashSale->badge_text }}
                            </span>
                        </div>
                        <p class="text-xs text-zinc-400 mt-0.5">{{ $currentFlashSale->subtitle }}</p>
                    </div>
                </div>

                @if($currentFlashSale->end_time)
                    <div class="flex items-center gap-2 text-xs font-mono font-medium text-amber-400 bg-zinc-950 px-3.5 py-2 rounded-lg border border-zinc-800 flex-shrink-0" id="preview-timer" data-endtime="{{ $currentFlashSale->end_time->toISOString() }}">
                        <span class="text-[11px] text-zinc-400 font-sans">Berakhir dalam:</span>
                        <span class="px-1.5 py-0.5 bg-zinc-900 border border-zinc-800 rounded text-amber-300 tabular-nums" id="p-hours">00</span>:
                        <span class="px-1.5 py-0.5 bg-zinc-900 border border-zinc-800 rounded text-amber-300 tabular-nums" id="p-minutes">00</span>:
                        <span class="px-1.5 py-0.5 bg-zinc-900 border border-zinc-800 rounded text-amber-300 tabular-nums" id="p-seconds">00</span>
                    </div>
                @endif
            </div>
        @else
            <div class="p-8 rounded-xl border border-dashed border-zinc-800 bg-zinc-900/30 text-center">
                <p class="text-xs text-zinc-400">Saat ini tidak ada Flash Sale yang aktif. Aktifkan event di bawah atau buat event baru agar tampil di beranda.</p>
            </div>
        @endif
    </div>

    <!-- 2. DAFTAR EVENT & PENGATURAN -->
    <div class="bg-zinc-900/60 rounded-xl border border-zinc-800 overflow-hidden">
        <div class="p-5 border-b border-zinc-800">
            <h3 class="text-sm font-semibold text-white">Daftar Event Flash Sale</h3>
            <p class="text-xs text-zinc-400 mt-0.5">Kelola judul, badge diskon, dan waktu hitung mundur countdown.</p>
        </div>

        <div class="divide-y divide-zinc-800/80">
            @forelse($flashSales as $fs)
                <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-zinc-800/20 transition-colors">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-lg {{ $fs->is_active ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-zinc-800 text-zinc-500 border border-zinc-700' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1.001A3.75 3.75 0 0012 18z"/></svg>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h4 class="font-semibold text-sm text-white">{{ $fs->title }}</h4>
                                <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                    {{ $fs->badge_text }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium {{ $fs->isRunning() ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($fs->is_active ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-zinc-800 text-zinc-400 border border-zinc-700') }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $fs->isRunning() ? 'bg-emerald-400' : ($fs->is_active ? 'bg-amber-400' : 'bg-zinc-500') }}"></span>
                                    <span>{{ $fs->isRunning() ? 'Sedang Berjalan' : ($fs->is_active ? 'Waktu Habis' : 'Nonaktif') }}</span>
                                </span>
                            </div>
                            <p class="text-xs text-zinc-400">{{ $fs->subtitle ?? 'Tidak ada deskripsi.' }}</p>
                            <div class="flex flex-wrap items-center gap-4 mt-2 text-[11px] text-zinc-400">
                                <span>Target: <code class="text-zinc-300 font-mono">{{ $fs->target_url ?? '#group-game' }}</code></span>
                                @if($fs->end_time)
                                    <span>Berakhir: <strong class="text-zinc-200 tabular-nums">{{ $fs->end_time->format('d M Y, H:i') }} WIB</strong> ({{ $fs->end_time->diffForHumans() }})</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 self-end md:self-center">
                        <form action="{{ route('admin.flash-sales.toggle', $fs) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-medium transition-colors border {{ $fs->is_active ? 'bg-amber-500/10 text-amber-300 hover:bg-amber-500/20 border-amber-500/20' : 'bg-emerald-500/10 text-emerald-300 hover:bg-emerald-500/20 border-emerald-500/20' }}">
                                {{ $fs->is_active ? 'Matikan' : 'Aktifkan' }}
                            </button>
                        </form>

                        <button type="button" onclick="openEditFlashSaleModal({{ json_encode($fs) }})" class="px-2.5 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-xs font-medium border border-zinc-700 transition-colors">
                            Edit
                        </button>

                        <form action="{{ route('admin.flash-sales.destroy', $fs) }}" method="POST" onsubmit="return confirm('Hapus event flash sale ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 text-xs font-medium border border-rose-500/20 transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-xs text-zinc-500">
                    Belum ada event flash sale.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- MODAL: BUAT EVENT FLASH SALE BARU -->
<div id="modal-create-flashsale" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 max-w-lg w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-zinc-800 mb-5">
            <h3 class="text-sm font-semibold text-white">Buat Event Flash Sale Baru</h3>
            <button type="button" onclick="document.getElementById('modal-create-flashsale').classList.add('hidden')" class="w-7 h-7 rounded-lg bg-zinc-800 text-zinc-400 hover:text-white flex items-center justify-center text-xs transition-colors">✕</button>
        </div>

        <form action="{{ route('admin.flash-sales.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Judul Event <span class="text-rose-400">*</span></label>
                <input type="text" name="title" value="FLASH SALE EVENT HARI INI" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Badge Teks <span class="text-rose-400">*</span></label>
                    <input type="text" name="badge_text" value="HEMAT 30%" required placeholder="Contoh: HEMAT 30%" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Diskon Persen (%)</label>
                    <input type="number" name="discount_percentage" value="30" min="1" max="100" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Deskripsi / Subtitle</label>
                <textarea name="subtitle" rows="2" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">Dapatkan harga diamond & pass termurah sebelum periode berakhir.</textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Waktu Berakhir (Countdown Timer) <span class="text-rose-400">*</span></label>
                <input type="datetime-local" name="end_time" id="create-end-time" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-[11px] text-zinc-500">Preset Cepat:</span>
                    <button type="button" onclick="setQuickTime('create-end-time', 6)" class="px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-[10px] text-zinc-300 font-medium">+6 Jam</button>
                    <button type="button" onclick="setQuickTime('create-end-time', 12)" class="px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-[10px] text-zinc-300 font-medium">+12 Jam</button>
                    <button type="button" onclick="setQuickTime('create-end-time', 24)" class="px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-[10px] text-zinc-300 font-medium">+24 Jam</button>
                    <button type="button" onclick="setQuickTime('create-end-time', 72)" class="px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-[10px] text-zinc-300 font-medium">+3 Hari</button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Link Tujuan Saat Diklik</label>
                <input type="text" name="target_url" value="#group-game" placeholder="#group-game atau /product/slug" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
            </div>

            <div class="pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-zinc-900 bg-zinc-950 border-zinc-700 focus:ring-0">
                    <span class="text-xs text-zinc-300">Aktifkan langsung di beranda</span>
                </label>
            </div>

            <div class="pt-3 border-t border-zinc-800 flex justify-end gap-2.5">
                <button type="button" onclick="document.getElementById('modal-create-flashsale').classList.add('hidden')" class="px-3.5 py-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-medium transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors">
                    Simpan Event
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDIT EVENT FLASH SALE -->
<div id="modal-edit-flashsale" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 max-w-lg w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-zinc-800 mb-5">
            <h3 class="text-sm font-semibold text-white">Edit Event Flash Sale</h3>
            <button type="button" onclick="document.getElementById('modal-edit-flashsale').classList.add('hidden')" class="w-7 h-7 rounded-lg bg-zinc-800 text-zinc-400 hover:text-white flex items-center justify-center text-xs transition-colors">✕</button>
        </div>

        <form id="edit-flashsale-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Judul Event <span class="text-rose-400">*</span></label>
                <input type="text" name="title" id="edit-title" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Badge Teks <span class="text-rose-400">*</span></label>
                    <input type="text" name="badge_text" id="edit-badge-text" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Diskon Persen (%)</label>
                    <input type="number" name="discount_percentage" id="edit-discount-percentage" min="1" max="100" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Deskripsi / Subtitle</label>
                <textarea name="subtitle" id="edit-subtitle" rows="2" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors"></textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Waktu Berakhir (Countdown Timer)</label>
                <input type="datetime-local" name="end_time" id="edit-end-time" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-[11px] text-zinc-500">Preset Cepat:</span>
                    <button type="button" onclick="setQuickTime('edit-end-time', 6)" class="px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-[10px] text-zinc-300 font-medium">+6 Jam</button>
                    <button type="button" onclick="setQuickTime('edit-end-time', 12)" class="px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-[10px] text-zinc-300 font-medium">+12 Jam</button>
                    <button type="button" onclick="setQuickTime('edit-end-time', 24)" class="px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-[10px] text-zinc-300 font-medium">+24 Jam</button>
                    <button type="button" onclick="setQuickTime('edit-end-time', 72)" class="px-2 py-0.5 rounded bg-zinc-800 hover:bg-zinc-700 text-[10px] text-zinc-300 font-medium">+3 Hari</button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Link Tujuan Saat Diklik</label>
                <input type="text" name="target_url" id="edit-target-url" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
            </div>

            <div class="pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_active" id="edit-is-active" value="1" class="w-4 h-4 rounded text-zinc-900 bg-zinc-950 border-zinc-700 focus:ring-0">
                    <span class="text-xs text-zinc-300">Aktifkan di beranda</span>
                </label>
            </div>

            <div class="pt-3 border-t border-zinc-800 flex justify-end gap-2.5">
                <button type="button" onclick="document.getElementById('modal-edit-flashsale').classList.add('hidden')" class="px-3.5 py-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-medium transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function setQuickTime(inputId, hoursToAdd) {
        const d = new Date();
        d.setHours(d.getHours() + hoursToAdd);
        const offset = d.getTimezoneOffset() * 60000;
        const localISOTime = (new Date(d.getTime() - offset)).toISOString().slice(0, 16);
        document.getElementById(inputId).value = localISOTime;
    }

    const createEndTimeEl = document.getElementById('create-end-time');
    if (createEndTimeEl && !createEndTimeEl.value) {
        setQuickTime('create-end-time', 12);
    }

    function openEditFlashSaleModal(data) {
        document.getElementById('edit-flashsale-form').action = `/admin/flash-sales/${data.id}`;
        document.getElementById('edit-title').value = data.title || '';
        document.getElementById('edit-badge-text').value = data.badge_text || '';
        document.getElementById('edit-discount-percentage').value = data.discount_percentage || '';
        document.getElementById('edit-subtitle').value = data.subtitle || '';
        document.getElementById('edit-target-url').value = data.target_url || '#group-game';
        document.getElementById('edit-is-active').checked = !!data.is_active;

        if (data.end_time) {
            const date = new Date(data.end_time);
            const offset = date.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(date.getTime() - offset)).toISOString().slice(0, 16);
            document.getElementById('edit-end-time').value = localISOTime;
        } else {
            document.getElementById('edit-end-time').value = '';
        }

        document.getElementById('modal-edit-flashsale').classList.remove('hidden');
    }

    const prevTimerEl = document.getElementById('preview-timer');
    if (prevTimerEl && prevTimerEl.dataset.endtime) {
        const targetTime = new Date(prevTimerEl.dataset.endtime).getTime();
        function updatePrevTimer() {
            const now = new Date().getTime();
            const diff = targetTime - now;
            if (diff <= 0) {
                document.getElementById('p-hours').textContent = '00';
                document.getElementById('p-minutes').textContent = '00';
                document.getElementById('p-seconds').textContent = '00';
                return;
            }
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('p-hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('p-minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('p-seconds').textContent = String(seconds).padStart(2, '0');
        }
        updatePrevTimer();
        setInterval(updatePrevTimer, 1000);
    }
</script>
@endpush
@endsection

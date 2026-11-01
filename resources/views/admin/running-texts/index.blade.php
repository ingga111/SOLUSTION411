@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-zinc-200">Kelola Running Text</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-white flex items-center gap-2.5">
                Kelola Running Text (Teks Pengumuman Berjalan)
            </h1>
            <p class="text-xs text-zinc-400 mt-1">Atur pesan pengumuman, info promo, dan status layanan yang berjalan di bilah atas (*top bar ticker*) website.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-zinc-300 hover:text-white text-xs font-medium border border-zinc-800 transition-colors">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.banners.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-zinc-300 hover:text-white text-xs font-medium border border-zinc-800 transition-colors">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                <span>Kelola Banner</span>
            </a>
            <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>Tambah Teks Berjalan</span>
            </button>
        </div>
    </div>

    <!-- Alert Notifications -->
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

    <!-- LIVE TICKER PREVIEW CARD -->
    <div class="bg-zinc-900/60 rounded-xl p-5 border border-zinc-800 mb-8">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-300">Pratinjau Langsung (Live Ticker Preview)</span>
            </div>
            <span class="text-[11px] text-zinc-500">Tampilan persis seperti di bilah atas website</span>
        </div>

        <!-- Simulated Top Bar -->
        <div class="rounded-lg bg-zinc-950 border border-zinc-800 py-2.5 px-4 overflow-hidden relative">
            <div class="flex items-center gap-3">
                <span class="flex-shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-zinc-800 text-zinc-200 border border-zinc-700">
                    INFO
                </span>
                <div class="overflow-hidden whitespace-nowrap flex-1">
                    <div class="ticker-inner text-xs text-zinc-300">
                        @forelse($runningTexts->where('is_active', true) as $ticker)
                            <span class="mx-4 inline-flex items-center gap-1.5">
                                <span>{{ $ticker->icon ?? '⚡' }}</span>
                                <span>{{ $ticker->content }}</span>
                            </span>
                        @empty
                            <span class="mx-4 text-zinc-500 italic">Tidak ada teks berjalan yang aktif.</span>
                        @endforelse

                        <!-- Duplicate loop for smooth animation -->
                        @foreach($runningTexts->where('is_active', true) as $ticker)
                            <span class="mx-4 inline-flex items-center gap-1.5" aria-hidden="true">
                                <span>{{ $ticker->icon ?? '⚡' }}</span>
                                <span>{{ $ticker->content }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-zinc-900/60 rounded-xl p-5 border border-zinc-800">
            <span class="text-xs text-zinc-400 font-medium">Total Teks Pengumuman</span>
            <div class="text-2xl font-bold tracking-tight text-white mt-1 tabular-nums">
                {{ $totalTexts }}
            </div>
            <span class="text-[11px] text-zinc-500 mt-1 block">Seluruh item tersimpan di database</span>
        </div>

        <div class="bg-zinc-900/60 rounded-xl p-5 border border-zinc-800">
            <span class="text-xs text-zinc-400 font-medium">Teks Sedang Aktif</span>
            <div class="text-2xl font-bold tracking-tight text-emerald-400 mt-1 tabular-nums">
                {{ $activeTexts }}
            </div>
            <span class="text-[11px] text-zinc-500 mt-1 block">Tampil bergulir di bilah atas</span>
        </div>

        <div class="bg-zinc-900/60 rounded-xl p-5 border border-zinc-800">
            <span class="text-xs text-zinc-400 font-medium">Status Ticker</span>
            <div class="text-2xl font-bold tracking-tight text-white mt-1 flex items-center gap-2">
                <span>{{ $activeTexts > 0 ? 'Aktif' : 'Kosong' }}</span>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2 py-0.5 rounded-full {{ $activeTexts > 0 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-zinc-800 text-zinc-400 border border-zinc-700' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $activeTexts > 0 ? 'bg-emerald-400' : 'bg-zinc-500' }}"></span>
                    <span>{{ $activeTexts > 0 ? 'Running' : 'Standby' }}</span>
                </span>
            </div>
            <span class="text-[11px] text-zinc-500 mt-1 block">Auto-pause saat hover kursor</span>
        </div>
    </div>

    <!-- Running Text Table Card -->
    <div class="bg-zinc-900/60 rounded-xl border border-zinc-800 overflow-hidden">
        <div class="p-5 border-b border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-semibold text-white">Daftar Pesan Running Text</h3>
                <p class="text-xs text-zinc-400 mt-0.5">Kelola isi teks, urutan giliran tampil, serta link target jika teks bisa diklik.</p>
            </div>
            <div class="text-xs text-zinc-400">
                Menampilkan <strong class="text-white tabular-nums">{{ $runningTexts->count() }}</strong> baris pengumuman
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-950/60 text-zinc-400 uppercase tracking-wider text-[10px] border-b border-zinc-800">
                    <tr>
                        <th class="py-3 px-4 font-medium text-center">Urutan</th>
                        <th class="py-3 px-4 font-medium">Ikon</th>
                        <th class="py-3 px-4 font-medium">Isi Pesan Pengumuman</th>
                        <th class="py-3 px-4 font-medium">Link URL (Opsional)</th>
                        <th class="py-3 px-4 font-medium">Status</th>
                        <th class="py-3 px-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/80">
                    @forelse($runningTexts as $item)
                        <tr class="hover:bg-zinc-800/20 transition-colors">
                            <td class="py-3.5 px-4 text-center font-mono">
                                <span class="text-xs font-semibold text-zinc-300 bg-zinc-800 px-2 py-0.5 rounded border border-zinc-700 tabular-nums">
                                    #{{ $item->sort_order }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="w-8 h-8 rounded-lg bg-zinc-800 border border-zinc-700 flex items-center justify-center text-sm flex-shrink-0">
                                    {{ $item->display_icon }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-medium text-white text-xs max-w-xl leading-relaxed">
                                    {{ $item->content }}
                                </p>
                            </td>
                            <td class="py-3.5 px-4 font-mono">
                                @if($item->url)
                                    <a href="{{ $item->url }}" target="_blank" class="px-2 py-0.5 rounded bg-zinc-800 border border-zinc-700 text-[11px] text-zinc-300 hover:text-white max-w-[180px] truncate inline-flex items-center gap-1 transition-colors">
                                        <span class="truncate">{{ $item->url }}</span>
                                        <svg class="w-2.5 h-2.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                    </a>
                                @else
                                    <span class="text-zinc-600 text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($item->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-zinc-800 text-zinc-400 border border-zinc-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span>
                                        <span>Nonaktif</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <form action="{{ route('admin.running-texts.toggle', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="{{ $item->is_active ? 'Matikan Teks Ini' : 'Aktifkan Teks Ini' }}" class="px-2.5 py-1 rounded-lg border text-xs font-medium transition-colors {{ $item->is_active ? 'bg-amber-500/10 border-amber-500/20 text-amber-300 hover:bg-amber-500/20' : 'bg-emerald-500/10 border-emerald-500/20 text-emerald-300 hover:bg-emerald-500/20' }}">
                                            {{ $item->is_active ? 'Matikan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <button type="button" onclick="openEditModal({{ json_encode($item) }})" class="p-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white border border-zinc-700 transition-colors" title="Edit">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </button>

                                    <form action="{{ route('admin.running-texts.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus teks berjalan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-colors" title="Hapus">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-zinc-500">
                                <p class="text-zinc-300 font-medium text-xs">Belum ada baris teks berjalan.</p>
                                <p class="text-[11px] text-zinc-500 mt-1">Klik tombol "+ Tambah Teks Berjalan" di atas untuk menambahkan pengumuman.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL: CREATE RUNNING TEXT -->
<div id="modal-create-running-text" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 max-w-xl w-full shadow-2xl relative my-8">
        <div class="flex items-center justify-between pb-4 border-b border-zinc-800 mb-5">
            <div>
                <h3 class="text-sm font-semibold text-white">Tambah Teks Berjalan Baru</h3>
                <p class="text-xs text-zinc-400">Tambahkan pesan pengumuman atau info promo pada bilah atas.</p>
            </div>
            <button type="button" onclick="closeCreateModal()" class="w-7 h-7 rounded-lg bg-zinc-800 text-zinc-400 hover:text-white flex items-center justify-center text-xs transition-colors">
                ✕
            </button>
        </div>

        <form action="{{ route('admin.running-texts.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Content -->
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Isi Teks Pengumuman <span class="text-rose-400">*</span></label>
                <textarea name="content" required rows="3" placeholder="Contoh: Diskon Kilat Spesial Weekend! Top Up Diamond MLBB & FF Mulai Rp 1.000!" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors"></textarea>
            </div>

            <!-- Icon / Emoji -->
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Ikon / Emoji</label>
                <input type="text" name="icon" id="create-icon" value="⚡" maxlength="50" placeholder="Pilih preset atau ketik emoji..." class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors mb-2">
                <!-- Preset Clickables -->
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="text-[11px] text-zinc-500 mr-1">Preset cepat:</span>
                    @foreach(['⚡', '🎮', '👑', '🍿', '💬', '🔥', '💎', '🎁', '🛡️', '🎟️', '📢', '🚨', '✨', '🚀', '⭐', '🏆'] as $emoji)
                        <button type="button" onclick="selectEmoji('create', '{{ $emoji }}')" class="w-7 h-7 rounded bg-zinc-800 hover:bg-zinc-700 text-xs flex items-center justify-center transition-colors border border-zinc-700">
                            {{ $emoji }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- URL & Sort Order -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Link URL Tujuan (Opsional)</label>
                    <input type="text" name="url" placeholder="Contoh: #games, https://wa.me/..." class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white font-mono focus:outline-none focus:border-zinc-600 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Urutan (Sort)</label>
                    <input type="number" name="sort_order" value="0" min="0" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <!-- Active Checkbox -->
            <div class="pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-zinc-900 bg-zinc-950 border-zinc-700 focus:ring-0">
                    <span class="text-xs text-zinc-300">Langsung Aktifkan di Bilah Atas</span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-zinc-800">
                <button type="button" onclick="closeCreateModal()" class="px-3.5 py-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-xs font-medium text-zinc-300 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors">
                    Simpan Teks Berjalan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDIT RUNNING TEXT -->
<div id="modal-edit-running-text" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 max-w-xl w-full shadow-2xl relative my-8">
        <div class="flex items-center justify-between pb-4 border-b border-zinc-800 mb-5">
            <div>
                <h3 class="text-sm font-semibold text-white">Edit Teks Berjalan</h3>
                <p class="text-xs text-zinc-400">Sesuaikan isi pesan atau link target running text.</p>
            </div>
            <button type="button" onclick="closeEditModal()" class="w-7 h-7 rounded-lg bg-zinc-800 text-zinc-400 hover:text-white flex items-center justify-center text-xs transition-colors">
                ✕
            </button>
        </div>

        <form id="edit-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Content -->
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Isi Teks Pengumuman <span class="text-rose-400">*</span></label>
                <textarea name="content" id="edit-content" required rows="3" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors"></textarea>
            </div>

            <!-- Icon / Emoji -->
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Ikon / Emoji</label>
                <input type="text" name="icon" id="edit-icon" maxlength="50" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors mb-2">
                <!-- Preset Clickables -->
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="text-[11px] text-zinc-500 mr-1">Preset cepat:</span>
                    @foreach(['⚡', '🎮', '👑', '🍿', '💬', '🔥', '💎', '🎁', '🛡️', '🎟️', '📢', '🚨', '✨', '🚀', '⭐', '🏆'] as $emoji)
                        <button type="button" onclick="selectEmoji('edit', '{{ $emoji }}')" class="w-7 h-7 rounded bg-zinc-800 hover:bg-zinc-700 text-xs flex items-center justify-center transition-colors border border-zinc-700">
                            {{ $emoji }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- URL & Sort Order -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Link URL Tujuan (Opsional)</label>
                    <input type="text" name="url" id="edit-url" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white font-mono focus:outline-none focus:border-zinc-600 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Urutan (Sort)</label>
                    <input type="number" name="sort_order" id="edit-sort_order" min="0" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <!-- Active Checkbox -->
            <div class="pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" id="edit-is_active" value="1" class="w-4 h-4 rounded text-zinc-900 bg-zinc-950 border-zinc-700 focus:ring-0">
                    <span class="text-xs text-zinc-300">Teks Berjalan Aktif</span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-zinc-800">
                <button type="button" onclick="closeEditModal()" class="px-3.5 py-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-xs font-medium text-zinc-300 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('modal-create-running-text').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('modal-create-running-text').classList.add('hidden');
    }

    function openEditModal(item) {
        const form = document.getElementById('edit-form');
        form.action = `/admin/running-texts/${item.id}`;

        document.getElementById('edit-content').value = item.content || '';
        document.getElementById('edit-icon').value = item.icon || '⚡';
        document.getElementById('edit-url').value = item.url || '';
        document.getElementById('edit-sort_order').value = item.sort_order ?? 0;
        document.getElementById('edit-is_active').checked = Boolean(item.is_active);

        document.getElementById('modal-edit-running-text').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modal-edit-running-text').classList.add('hidden');
    }

    function selectEmoji(target, emoji) {
        const el = document.getElementById(`${target}-icon`);
        if (el) {
            el.value = emoji;
        }
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
        }
    });
</script>
@endsection

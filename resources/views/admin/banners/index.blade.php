@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-zinc-200">Kelola Banner Promo</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-white">Kelola Banner Slide &amp; Promo</h1>
            <p class="text-xs text-zinc-400 mt-1">Upload banner promo game, event diskon, dan atur tampilan carousel beranda toko.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-zinc-300 hover:text-white text-xs font-medium border border-zinc-800 transition-colors">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.running-texts.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-zinc-300 hover:text-white text-xs font-medium border border-zinc-800 transition-colors">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                <span>Running Text</span>
            </a>
            <button type="button" onclick="document.getElementById('modal-create-banner').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>Tambah Banner</span>
            </button>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-zinc-900/60 rounded-xl p-4 sm:p-5 border border-zinc-800 mb-8 flex items-start gap-3.5">
        <div class="w-9 h-9 rounded-lg bg-zinc-800 border border-zinc-700 flex items-center justify-center text-zinc-300 flex-shrink-0">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        </div>
        <div class="text-xs">
            <h4 class="font-medium text-white">Banner Carousel di Halaman Beranda</h4>
            <p class="text-zinc-400 mt-1 leading-relaxed">
                Admin memiliki kontrol penuh atas banner promo di beranda. Anda dapat mengunggah gambar banner custom (rekomendasi rasio 16:9), atau menggunakan latar belakang gradien netral. Banner berstatus <strong>Aktif</strong> akan otomatis tampil bergantian di slide beranda pembeli.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Banners Grid / List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($banners as $banner)
            <div class="bg-zinc-900/60 rounded-xl overflow-hidden border border-zinc-800 flex flex-col justify-between hover:border-zinc-700 transition-colors">
                <!-- Banner Preview -->
                <div class="relative w-full aspect-[16/9] bg-gradient-to-br {{ $banner->gradient ?? 'from-zinc-900 to-zinc-950' }} overflow-hidden flex items-center justify-center p-5 text-center">
                    @if($banner->getImageUrl())
                        <img src="{{ $banner->getImageUrl() }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/90 via-zinc-950/40 to-transparent"></div>
                    @endif
                    <div class="relative z-10 max-w-full">
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-zinc-800/90 text-zinc-200 border border-zinc-700/80 mb-2">
                            {{ $banner->badge_text ?? 'PROMO' }}
                        </span>
                        <h4 class="text-sm font-semibold text-white line-clamp-2 leading-snug">{{ $banner->title }}</h4>
                        @if($banner->subtitle)
                            <p class="text-[11px] text-zinc-400 mt-1 line-clamp-1">{{ $banner->subtitle }}</p>
                        @endif
                    </div>

                    <div class="absolute top-3 right-3 z-20 flex items-center gap-1.5">
                        @if($banner->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-medium uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 backdrop-blur-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                <span>Aktif</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-medium uppercase bg-zinc-900/90 text-zinc-400 border border-zinc-700 backdrop-blur-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span>
                                <span>Nonaktif</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Info & Actions -->
                <div class="p-4 flex-1 flex flex-col justify-between bg-zinc-900/40">
                    <div class="flex items-center justify-between text-[11px] text-zinc-400 mb-3">
                        <span>Urutan: <strong class="text-zinc-200 tabular-nums">#{{ $banner->sort_order }}</strong></span>
                        <span>Target: <span class="font-mono text-zinc-300 truncate max-w-[140px] inline-block">{{ $banner->target_url ?? '-' }}</span></span>
                    </div>

                    <div class="pt-3 border-t border-zinc-800/80 flex items-center justify-between gap-2">
                        <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-2.5 py-1 rounded-lg border text-xs font-medium transition-colors {{ $banner->is_active ? 'bg-amber-500/10 border-amber-500/20 text-amber-300 hover:bg-amber-500/20' : 'bg-emerald-500/10 border-emerald-500/20 text-emerald-300 hover:bg-emerald-500/20' }}">
                                {{ $banner->is_active ? 'Matikan' : 'Aktifkan' }}
                            </button>
                        </form>

                        <div class="flex items-center gap-1.5">
                            <button type="button" onclick="openEditModal({{ json_encode($banner) }})" class="p-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white border border-zinc-700 transition-colors" title="Edit">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-colors" title="Hapus">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 rounded-xl text-center border border-dashed border-zinc-800 bg-zinc-900/30">
                <svg class="w-10 h-10 mx-auto mb-3 text-zinc-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                <h3 class="text-sm font-semibold text-white">Belum Ada Banner Promo</h3>
                <p class="text-xs text-zinc-400 mt-1 max-w-sm mx-auto">Klik tombol Tambah Banner di atas untuk menambah slide banner promo baru untuk beranda.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Create Banner -->
<div id="modal-create-banner" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 max-w-xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between pb-4 border-b border-zinc-800 mb-5">
            <h3 class="text-sm font-semibold text-white">Tambah Banner Promo Baru</h3>
            <button type="button" onclick="document.getElementById('modal-create-banner').classList.add('hidden')" class="w-7 h-7 rounded-lg bg-zinc-800 text-zinc-400 hover:text-white flex items-center justify-center text-xs transition-colors">✕</button>
        </div>

        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Judul Banner <span class="text-rose-400">*</span></label>
                <input type="text" name="title" required placeholder="Contoh: PROMO SPESIAL TOP UP 24 JAM" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Subjudul / Deskripsi Singkat</label>
                <input type="text" name="subtitle" placeholder="Contoh: Diskon hingga 30% Diamond MLBB & Free Fire!" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Badge Teks</label>
                    <input type="text" name="badge_text" value="PROMO SPESIAL" placeholder="Contoh: FLASH SALE" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Link Tujuan (Klik Banner)</label>
                    <input type="text" name="target_url" value="#games" placeholder="Contoh: /product/mobile-legends atau #games" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Upload Gambar Banner (Opsional)</label>
                <input type="file" name="image" accept="image/*" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-400 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700">
                <span class="text-[10px] text-zinc-500 mt-1 block">Format: JPG, PNG, WEBP (Maksimal 4MB). Jika kosong, gradien latar belakang akan digunakan.</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Gradien Warna Background</label>
                    <select name="gradient" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                        <option value="from-blue-900 via-indigo-950 to-slate-950">Biru - Indigo (MLBB Style)</option>
                        <option value="from-amber-900 via-orange-950 to-slate-950">Oranye - Api (Free Fire Style)</option>
                        <option value="from-purple-900 via-slate-950 to-emerald-950">Ungu - Hijau (Sultan Style)</option>
                        <option value="from-rose-900 via-pink-950 to-slate-950">Merah Crimson - Hitam</option>
                        <option value="from-cyan-900 via-teal-950 to-slate-950">Cyan Neon - Gelap</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nomor Urutan Tampil</label>
                    <input type="number" name="sort_order" value="1" min="0" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <div class="pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_active" id="create-is-active" value="1" checked class="w-4 h-4 rounded text-zinc-900 bg-zinc-950 border-zinc-700 focus:ring-0">
                    <span class="text-xs text-zinc-300">Aktifkan langsung di slide beranda</span>
                </label>
            </div>

            <div class="pt-3 border-t border-zinc-800 flex justify-end gap-2.5">
                <button type="button" onclick="document.getElementById('modal-create-banner').classList.add('hidden')" class="px-3.5 py-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-medium transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors">Simpan Banner</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Banner -->
<div id="modal-edit-banner" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 max-w-xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between pb-4 border-b border-zinc-800 mb-5">
            <h3 class="text-sm font-semibold text-white">Edit Banner Promo</h3>
            <button type="button" onclick="document.getElementById('modal-edit-banner').classList.add('hidden')" class="w-7 h-7 rounded-lg bg-zinc-800 text-zinc-400 hover:text-white flex items-center justify-center text-xs transition-colors">✕</button>
        </div>

        <form id="edit-banner-form" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Judul Banner <span class="text-rose-400">*</span></label>
                <input type="text" name="title" id="edit-title" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Subjudul / Deskripsi Singkat</label>
                <input type="text" name="subtitle" id="edit-subtitle" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Badge Teks</label>
                    <input type="text" name="badge_text" id="edit-badge" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Link Tujuan</label>
                    <input type="text" name="target_url" id="edit-target-url" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Ganti Gambar Banner (Biarkan kosong jika tidak ingin ganti)</label>
                <input type="file" name="image" accept="image/*" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-400 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Gradien Warna</label>
                    <select name="gradient" id="edit-gradient" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                        <option value="from-blue-900 via-indigo-950 to-slate-950">Biru - Indigo (MLBB Style)</option>
                        <option value="from-amber-900 via-orange-950 to-slate-950">Oranye - Api (Free Fire Style)</option>
                        <option value="from-purple-900 via-slate-950 to-emerald-950">Ungu - Hijau (Sultan Style)</option>
                        <option value="from-rose-900 via-pink-950 to-slate-950">Merah Crimson - Hitam</option>
                        <option value="from-cyan-900 via-teal-950 to-slate-950">Cyan Neon - Gelap</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nomor Urutan</label>
                    <input type="number" name="sort_order" id="edit-sort-order" min="0" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-white tabular-nums focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
            </div>

            <div class="pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_active" id="edit-is-active" value="1" class="w-4 h-4 rounded text-zinc-900 bg-zinc-950 border-zinc-700 focus:ring-0">
                    <span class="text-xs text-zinc-300">Aktifkan di slide beranda</span>
                </label>
            </div>

            <div class="pt-3 border-t border-zinc-800 flex justify-end gap-2.5">
                <button type="button" onclick="document.getElementById('modal-edit-banner').classList.add('hidden')" class="px-3.5 py-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-medium transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(banner) {
        const form = document.getElementById('edit-banner-form');
        form.action = '/admin/banners/' + banner.id;
        document.getElementById('edit-title').value = banner.title || '';
        document.getElementById('edit-subtitle').value = banner.subtitle || '';
        document.getElementById('edit-badge').value = banner.badge_text || 'PROMO SPESIAL';
        document.getElementById('edit-target-url').value = banner.target_url || '#games';
        document.getElementById('edit-gradient').value = banner.gradient || 'from-blue-900 via-indigo-950 to-slate-950';
        document.getElementById('edit-sort-order').value = banner.sort_order || 0;
        document.getElementById('edit-is-active').checked = banner.is_active;

        document.getElementById('modal-edit-banner').classList.remove('hidden');
    }
</script>
@endsection

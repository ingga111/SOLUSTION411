@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Header with Breadcrumbs & Action Hub -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-zinc-800/80">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-zinc-200 transition-colors">Dashboard</a>
                <span class="text-zinc-600">/</span>
                <span class="text-zinc-200">Kelola Kategori</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Kelola Kategori Produk &amp; Layanan</h1>
            <p class="text-xs text-zinc-400 mt-1">Atur kategori katalog untuk mempermudah pemilihan layanan dan penentuan alur transaksi produk.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-white hover:bg-zinc-800 text-xs font-medium transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m7.5 4.27 9 5.15"/>
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                    <path d="m3.3 7 8.7 5 8.7-5"/>
                    <path d="M12 22V12"/>
                </svg>
                <span>Kelola Produk</span>
            </a>
            <button type="button" onclick="openCreateModal()" class="px-3.5 py-1.5 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 font-semibold text-xs transition-colors inline-flex items-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5 text-zinc-950" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/>
                    <path d="M12 5v14"/>
                </svg>
                <span>+ Tambah Kategori Baru</span>
            </button>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-3.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-3.5 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-3.5 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs space-y-1">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl p-5 bg-zinc-900/60 border border-zinc-800">
            <span class="text-xs font-medium text-zinc-400">Total Kategori</span>
            <div class="text-2xl font-bold text-white tabular-nums tracking-tight mt-2">
                {{ $totalCategories }}
            </div>
            <span class="text-[11px] text-zinc-500 mt-1 block">Seluruh kategori terdaftar di sistem</span>
        </div>

        <div class="rounded-xl p-5 bg-zinc-900/60 border border-zinc-800">
            <span class="text-xs font-medium text-zinc-400">Kategori Aktif</span>
            <div class="text-2xl font-bold text-white tabular-nums tracking-tight mt-2">
                {{ $activeCategories }}
            </div>
            <span class="text-[11px] text-zinc-500 mt-1 block">Tampil di katalog pilihan produk admin</span>
        </div>

        <div class="rounded-xl p-5 bg-zinc-900/60 border border-zinc-800">
            <span class="text-xs font-medium text-zinc-400">Total Produk Terhubung</span>
            <div class="text-2xl font-bold text-white tabular-nums tracking-tight mt-2">
                {{ $totalProducts }}
            </div>
            <span class="text-[11px] text-zinc-500 mt-1 block">Produk game &amp; akun dalam kategori</span>
        </div>
    </div>

    <!-- Category Table Card -->
    <div class="rounded-xl p-5 sm:p-6 bg-zinc-900/60 border border-zinc-800">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <h3 class="text-sm font-semibold text-white tracking-tight">Daftar Kategori Layanan</h3>
                <p class="text-xs text-zinc-400 mt-0.5">Urutkan dan kelola kategori untuk mempermudah pemilihan saat menambah produk.</p>
            </div>
            <div class="text-xs text-zinc-400">
                Menampilkan <strong class="text-zinc-200 tabular-nums">{{ $categories->count() }}</strong> kategori
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-zinc-800">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-900/90 text-zinc-400 uppercase tracking-wider text-[10px] font-semibold border-b border-zinc-800">
                    <tr>
                        <th class="py-2.5 px-3.5">Kategori &amp; Icon</th>
                        <th class="py-2.5 px-3.5">Slug Identifier</th>
                        <th class="py-2.5 px-3.5">Tipe Layanan</th>
                        <th class="py-2.5 px-3.5 text-center">Urutan</th>
                        <th class="py-2.5 px-3.5 text-center">Produk Terkait</th>
                        <th class="py-2.5 px-3.5">Status</th>
                        <th class="py-2.5 px-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60 bg-zinc-950/40">
                    @forelse($categories as $category)
                        <tr class="hover:bg-zinc-900/50 transition-colors">
                            <td class="py-3 px-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-zinc-800 border border-zinc-700/60 flex items-center justify-center text-sm flex-shrink-0">
                                        {{ $category->icon_display }}
                                    </div>
                                    <div>
                                        <span class="font-medium text-white text-xs block">{{ $category->name }}</span>
                                        @if($category->parent)
                                            <span class="text-[10px] text-zinc-500 flex items-center gap-1 mt-0.5">
                                                <span>↳ Sub dari:</span>
                                                <strong class="text-zinc-300 font-normal">{{ $category->parent->name }}</strong>
                                            </span>
                                        @else
                                            <span class="text-[10px] text-zinc-500">Kategori Utama</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3.5 font-mono">
                                <span class="px-2 py-0.5 rounded bg-zinc-900 border border-zinc-800 text-[11px] text-zinc-300">
                                    {{ $category->slug }}
                                </span>
                            </td>
                            <td class="py-3 px-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-medium border bg-zinc-800/80 text-zinc-300 border-zinc-700/60 uppercase tracking-wider">
                                    {{ $category->type_label }}
                                </span>
                            </td>
                            <td class="py-3 px-3.5 text-center font-mono tabular-nums text-zinc-400">
                                #{{ $category->sort_order }}
                            </td>
                            <td class="py-3 px-3.5 text-center font-mono tabular-nums">
                                @if($category->products_count > 0)
                                    <span class="text-xs text-zinc-200 font-medium">
                                        {{ $category->products_count }} Produk
                                    </span>
                                @else
                                    <span class="text-[11px] text-zinc-500">
                                        0
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3.5 whitespace-nowrap">
                                @if($category->is_active)
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-zinc-800 text-zinc-400 border border-zinc-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span>
                                        <span>Nonaktif</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Toggle Active -->
                                    <form action="{{ route('admin.categories.toggle', $category) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="{{ $category->is_active ? 'Nonaktifkan Kategori' : 'Aktifkan Kategori' }}" class="px-2.5 py-1 rounded-md border text-xs font-medium transition-colors {{ $category->is_active ? 'bg-zinc-800/80 border-zinc-700/60 text-zinc-300 hover:text-white hover:bg-zinc-700' : 'bg-emerald-950/40 border-emerald-800/50 text-emerald-300 hover:bg-emerald-900/60' }}">
                                            {{ $category->is_active ? 'Matikan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <!-- Edit Button -->
                                    <button type="button" onclick="openEditModal({{ json_encode($category) }})" class="px-2.5 py-1 rounded-md bg-zinc-800 hover:bg-zinc-700 text-zinc-200 hover:text-white border border-zinc-700/50 text-xs font-medium transition-colors">
                                        Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ $category->name }}\'?{{ $category->products_count > 0 ? ' (Perhatian: ' . $category->products_count . ' produk di dalamnya juga akan ikut terhapus)' : '' }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-md bg-rose-950/40 hover:bg-rose-900/60 text-rose-300 border border-rose-800/40 text-xs font-medium transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-zinc-500">
                                Belum ada kategori yang ditambahkan. Silakan klik "+ Tambah Kategori Baru".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL: CREATE CATEGORY -->
<div id="modal-create-category" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="rounded-xl p-6 max-w-lg w-full bg-zinc-900 border border-zinc-800 shadow-2xl relative my-8">
        <!-- Close Button -->
        <button type="button" onclick="closeCreateModal()" class="absolute top-4 right-4 w-7 h-7 rounded-md bg-zinc-800 hover:bg-zinc-700 text-zinc-400 hover:text-white flex items-center justify-center text-xs transition-colors">
            ✕
        </button>

        <div class="mb-5 pb-3 border-b border-zinc-800">
            <h3 class="text-sm font-semibold text-white tracking-tight">Tambah Kategori Baru</h3>
            <p class="text-xs text-zinc-400 mt-0.5">Kelompokkan layanan game, akun, atau preorder seragam.</p>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nama Kategori <span class="text-rose-400">*</span></label>
                <input type="text" name="name" id="create-name" required placeholder="Contoh: Top-Up Game, Akun Netflix" oninput="autoGenerateSlug('create')" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
            </div>

            <!-- Slug -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-medium text-zinc-300">Slug Identifier (URL)</label>
                    <span class="text-[10px] text-zinc-500">Otomatis dari nama</span>
                </div>
                <input type="text" name="slug" id="create-slug" placeholder="top-up-game" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-200 font-mono focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
            </div>

            <!-- Type & Sort Order -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Tipe Layanan</label>
                    <select name="type" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                        <option value="">-- Kategori Umum --</option>
                        @foreach($defaultTypes as $typeItem)
                            <option value="{{ $typeItem['value'] }}">{{ $typeItem['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Urutan Tampil (Sort)</label>
                    <input type="number" name="sort_order" value="0" min="0" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 font-mono focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                </div>
            </div>

            <!-- Icon / Emoji -->
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Icon / Emoji</label>
                <div class="flex items-center gap-2 mb-2">
                    <input type="text" name="icon" id="create-icon" value="📁" maxlength="50" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
                <div class="flex flex-wrap items-center gap-1">
                    <span class="text-[10px] text-zinc-500 mr-1">Preset:</span>
                    @foreach(['🎮', '🍿', '👑', '⚔️', '👕', '💎', '⚡', '🎁', '🛡️', '💳', '🛒', '📦', '🏷️', '🔥', '🎯'] as $emoji)
                        <button type="button" onclick="selectEmoji('create', '{{ $emoji }}')" class="w-6 h-6 rounded bg-zinc-800 hover:bg-zinc-700 text-xs flex items-center justify-center transition-colors border border-zinc-700/50">
                            {{ $emoji }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Parent Category (Sub-category nesting) -->
            @if($parentCategories->count() > 0)
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Induk Kategori (Opsional)</label>
                    <select name="parent_id" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 focus:outline-none focus:border-zinc-600 transition-colors">
                        <option value="">-- Tidak ada (Sebagai Kategori Utama) --</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->icon_display }} {{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Active Checkbox -->
            <div class="pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" value="1" checked class="w-3.5 h-3.5 rounded text-zinc-900 bg-zinc-950 border-zinc-800 focus:ring-0">
                    <span class="text-xs font-medium text-white">Aktifkan Kategori Langsung</span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-zinc-800">
                <button type="button" onclick="closeCreateModal()" class="px-3.5 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-xs font-medium text-zinc-300 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-1.5 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold shadow-sm transition-colors">
                    Simpan Kategori Baru
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDIT CATEGORY -->
<div id="modal-edit-category" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="rounded-xl p-6 max-w-lg w-full bg-zinc-900 border border-zinc-800 shadow-2xl relative my-8">
        <!-- Close Button -->
        <button type="button" onclick="closeEditModal()" class="absolute top-4 right-4 w-7 h-7 rounded-md bg-zinc-800 hover:bg-zinc-700 text-zinc-400 hover:text-white flex items-center justify-center text-xs transition-colors">
            ✕
        </button>

        <div class="mb-5 pb-3 border-b border-zinc-800">
            <h3 class="text-sm font-semibold text-white tracking-tight">Edit Kategori</h3>
            <p class="text-xs text-zinc-400 mt-0.5">Sesuaikan nama, tipe layanan, atau urutan tampil.</p>
        </div>

        <form id="edit-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nama Kategori <span class="text-rose-400">*</span></label>
                <input type="text" name="name" id="edit-name" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 placeholder-zinc-500 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
            </div>

            <!-- Slug -->
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Slug Identifier (URL) <span class="text-rose-400">*</span></label>
                <input type="text" name="slug" id="edit-slug" required class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-200 font-mono focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
            </div>

            <!-- Type & Sort Order -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Tipe Layanan</label>
                    <select name="type" id="edit-type" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                        <option value="">-- Kategori Umum --</option>
                        @foreach($defaultTypes as $typeItem)
                            <option value="{{ $typeItem['value'] }}">{{ $typeItem['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Urutan Tampil (Sort)</label>
                    <input type="number" name="sort_order" id="edit-sort_order" min="0" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 font-mono focus:outline-none focus:border-zinc-600 focus:ring-1 focus:ring-zinc-600 transition-colors">
                </div>
            </div>

            <!-- Icon / Emoji -->
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Icon / Emoji</label>
                <div class="flex items-center gap-2 mb-2">
                    <input type="text" name="icon" id="edit-icon" maxlength="50" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 focus:outline-none focus:border-zinc-600 transition-colors">
                </div>
                <div class="flex flex-wrap items-center gap-1">
                    <span class="text-[10px] text-zinc-500 mr-1">Preset:</span>
                    @foreach(['🎮', '🍿', '👑', '⚔️', '👕', '💎', '⚡', '🎁', '🛡️', '💳', '🛒', '📦', '🏷️', '🔥', '🎯'] as $emoji)
                        <button type="button" onclick="selectEmoji('edit', '{{ $emoji }}')" class="w-6 h-6 rounded bg-zinc-800 hover:bg-zinc-700 text-xs flex items-center justify-center transition-colors border border-zinc-700/50">
                            {{ $emoji }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Parent Category -->
            @if($parentCategories->count() > 0)
                <div>
                    <label class="block text-xs font-medium text-zinc-300 mb-1.5">Induk Kategori (Opsional)</label>
                    <select name="parent_id" id="edit-parent_id" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-100 focus:outline-none focus:border-zinc-600 transition-colors">
                        <option value="">-- Tidak ada (Sebagai Kategori Utama) --</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->icon_display }} {{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Active Checkbox -->
            <div class="pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" id="edit-is_active" value="1" class="w-3.5 h-3.5 rounded text-zinc-900 bg-zinc-950 border-zinc-800 focus:ring-0">
                    <span class="text-xs font-medium text-white">Kategori Aktif</span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-zinc-800">
                <button type="button" onclick="closeEditModal()" class="px-3.5 py-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-xs font-medium text-zinc-300 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-1.5 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold shadow-sm transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('modal-create-category').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('modal-create-category').classList.add('hidden');
    }

    function openEditModal(category) {
        const form = document.getElementById('edit-form');
        form.action = `/admin/categories/${category.id}`;

        document.getElementById('edit-name').value = category.name || '';
        document.getElementById('edit-slug').value = category.slug || '';
        document.getElementById('edit-type').value = category.type || '';
        document.getElementById('edit-sort_order').value = category.sort_order ?? 0;
        document.getElementById('edit-icon').value = category.icon || '📁';
        
        const parentSelect = document.getElementById('edit-parent_id');
        if (parentSelect) {
            parentSelect.value = category.parent_id || '';
            Array.from(parentSelect.options).forEach(opt => {
                opt.disabled = (opt.value == category.id);
            });
        }

        document.getElementById('edit-is_active').checked = Boolean(category.is_active);
        document.getElementById('modal-edit-category').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modal-edit-category').classList.add('hidden');
    }

    function selectEmoji(target, emoji) {
        const el = document.getElementById(`${target}-icon`);
        if (el) {
            el.value = emoji;
        }
    }

    function autoGenerateSlug(target) {
        const nameVal = document.getElementById(`${target}-name`).value;
        const slugEl = document.getElementById(`${target}-slug`);
        if (slugEl) {
            slugEl.value = nameVal
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
        }
    });
</script>
@endsection

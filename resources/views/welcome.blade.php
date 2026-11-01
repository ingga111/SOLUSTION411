@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5 pb-16">
    
    <!-- 1. HERO BANNER (Linear Precision Slider) -->
    <div class="relative rounded-2xl overflow-hidden bg-zinc-950 border border-zinc-200 dark:border-zinc-800/80 mb-6 group" id="hero-slider-container">
        <div class="relative w-full aspect-[16/9] sm:aspect-[21/9] min-h-[260px] sm:min-h-[320px] md:min-h-[380px] overflow-hidden" id="slider-track">
            @forelse($banners as $index => $banner)
                <div class="banner-slide absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' }}" data-index="{{ $index }}">
                    <!-- Background: Uploaded Image or Dynamic Gradient -->
                    @if($banner->getImageUrl())
                        <img src="{{ $banner->getImageUrl() }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-zinc-950 via-zinc-950/70 to-transparent"></div>
                    @else
                        <div class="absolute inset-0 bg-gradient-to-r from-zinc-950 via-zinc-900 to-zinc-950"></div>
                        <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-zinc-800/30 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute right-12 top-1/2 -translate-y-1/2 hidden md:flex items-center justify-center opacity-10 pointer-events-none text-9xl">
                            🎮
                        </div>
                    @endif

                    <!-- Slide Content Overlay -->
                    <div class="relative z-20 h-full flex flex-col justify-center px-6 sm:px-12 md:px-16 py-6 max-w-2xl">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-[10px] font-mono font-semibold uppercase tracking-wider bg-zinc-900/80 text-zinc-300 border border-zinc-700/80 mb-3 w-fit backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ $banner->badge_text ?? 'PROMO RESMI' }}
                        </div>
                        <h2 class="text-2xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight leading-tight">
                            {{ $banner->title }}
                        </h2>
                        @if($banner->subtitle)
                            <p class="mt-2 text-xs sm:text-sm text-zinc-300 line-clamp-2 max-w-xl leading-relaxed">
                                {{ $banner->subtitle }}
                            </p>
                        @endif

                        <div class="mt-5 flex flex-wrap items-center gap-3">
                            <a href="{{ $banner->target_url ?? '#catalog-section' }}" class="px-5 py-2.5 rounded-lg bg-zinc-100 hover:bg-white text-zinc-950 font-bold text-xs font-mono transition-all flex items-center gap-2 shadow-sm">
                                <span>Beli Sekarang</span>
                                <span>→</span>
                            </a>
                            <span class="inline-flex items-center gap-1.5 text-xs font-mono text-zinc-400 bg-zinc-900/80 px-3 py-2 rounded-lg border border-zinc-800 backdrop-blur-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>Proses 1-3 Detik</span>
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="banner-slide absolute inset-0 w-full h-full bg-zinc-950 flex flex-col justify-center px-6 sm:px-16 py-6">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-mono font-bold bg-zinc-800 text-zinc-300 border border-zinc-700 w-fit mb-2">⚡ SOLUSTION411 ID</span>
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">Top Up Game Cepat &amp; Bergaransi</h2>
                    <p class="text-xs sm:text-sm text-zinc-400 mt-1">Mobile Legends, Free Fire, Valorant &amp; Akun Digital Terpercaya.</p>
                    <div class="mt-5">
                        <a href="#catalog-section" class="px-5 py-2.5 rounded-lg bg-zinc-100 hover:bg-white text-zinc-950 font-bold text-xs font-mono transition-all inline-flex items-center gap-2 shadow-sm">
                            <span>Mulai Belanja</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Slider Navigation Controls -->
        <button type="button" onclick="prevSlide()" class="absolute left-3 top-1/2 -translate-y-1/2 z-30 w-8 h-8 rounded-lg bg-zinc-900/80 hover:bg-zinc-800 text-zinc-300 border border-zinc-700/80 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 backdrop-blur-sm" aria-label="Slide Sebelumnya">
            ❮
        </button>
        <button type="button" onclick="nextSlide()" class="absolute right-3 top-1/2 -translate-y-1/2 z-30 w-8 h-8 rounded-lg bg-zinc-900/80 hover:bg-zinc-800 text-zinc-300 border border-zinc-700/80 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 backdrop-blur-sm" aria-label="Slide Selanjutnya">
            ❯
        </button>

        <!-- Slider Indicators Bars -->
        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 z-30 flex items-center gap-1.5">
            @foreach($banners as $index => $banner)
                <button type="button" onclick="goToSlide({{ $index }})" class="slider-dot h-1.5 rounded-full transition-all {{ $index === 0 ? 'bg-zinc-100 w-6' : 'bg-zinc-600/70 w-1.5 hover:bg-zinc-400' }}" aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
    </div>

    <!-- 2. FLASH SALE / PROMO HARI INI (Linear Precision Card) -->
    @if($flashSale)
        <a href="{{ $flashSale->target_url ?? '#catalog-section' }}" class="block group mb-6">
            <div class="rounded-xl p-3.5 sm:p-4 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/80 flex flex-col sm:flex-row items-center justify-between gap-3 hover:border-zinc-400 dark:hover:border-zinc-700 transition-all shadow-sm">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center text-zinc-900 dark:text-zinc-100 text-sm font-mono flex-shrink-0">
                        🔥
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-zinc-100 font-mono">{{ $flashSale->title }}</h3>
                            <span class="px-2 py-0.2 rounded bg-rose-500/10 text-rose-600 dark:text-rose-400 text-[10px] font-mono font-bold border border-rose-500/20 uppercase">{{ $flashSale->badge_text }}</span>
                        </div>
                        @if($flashSale->subtitle)
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">{{ $flashSale->subtitle }}</p>
                        @endif
                    </div>
                </div>

                @if($flashSale->end_time)
                    <div class="flex items-center gap-2 text-xs font-mono text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-950 px-3 py-1.5 rounded-lg border border-zinc-200 dark:border-zinc-800 flex-shrink-0 w-full sm:w-auto justify-center" id="flash-sale-timer" data-endtime="{{ $flashSale->end_time->toISOString() }}">
                        <span class="text-[10px] text-zinc-500 uppercase tracking-wider">Berakhir:</span>
                        <span class="px-1.5 py-0.5 bg-zinc-200 dark:bg-zinc-800 rounded font-bold" id="fs-hours">00</span>:
                        <span class="px-1.5 py-0.5 bg-zinc-200 dark:bg-zinc-800 rounded font-bold" id="fs-minutes">00</span>:
                        <span class="px-1.5 py-0.5 bg-zinc-200 dark:bg-zinc-800 rounded font-bold" id="fs-seconds">00</span>
                    </div>
                @endif
            </div>
        </a>
    @endif

    <!-- 3. LINEAR SEGMENTED CATEGORY TABS -->
    <div class="-mx-4 px-4 sm:mx-0 sm:px-0 mb-6 overflow-x-auto pb-1 scrollbar-none">
        <div class="inline-flex p-1 bg-zinc-100 dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-800 rounded-xl min-w-max gap-1">
            <button type="button" onclick="filterCategory('all', this)" class="category-pill active-pill px-3 py-1.5 rounded-lg text-xs font-medium transition-all flex items-center gap-1.5 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white shadow-sm font-semibold">
                <span class="text-xs">⚡</span>
                <span>Semua Layanan</span>
            </button>
            @foreach($categories as $cat)
                <button type="button" onclick="filterCategory('{{ $cat->slug }}', this)" class="category-pill px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-all flex items-center gap-1.5">
                    <span class="text-xs">{{ $cat->icon_display }}</span>
                    <span>{{ $cat->name }}</span>
                    @if($cat->products_count ?? $cat->products->count())
                        <span class="px-1.5 py-0.2 rounded text-[10px] font-mono bg-zinc-200 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400">{{ $cat->products_count ?? $cat->products->count() }}</span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <!-- 4. PRODUCT CATALOG GRID (Linear Precision Cards) -->
    <div id="catalog-section" class="scroll-mt-20 mb-12">
        @forelse($categories as $category)
            @php
                $catType = $category->type ?? 'general';
            @endphp
            <div class="product-group-section mb-10" id="group-{{ $category->slug }}" data-category="{{ $category->slug }}">
                <!-- Category Section Header -->
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-zinc-100 dark:border-zinc-800/80">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-2 tracking-tight">
                            <span>{{ $category->icon_display }}</span>
                            <span>{{ $category->name }}{{ $catType === 'game_account' && !str_contains(strtolower($category->name), 'bergaransi') ? ' Bergaransi' : '' }}{{ $catType === 'game' && !str_contains(strtolower($category->name), 'favorit') ? ' Favorit' : '' }}</span>
                        </h3>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">
                            @if($catType === 'game')
                                Isi diamond game instan cukup masukkan User ID &amp; Server Zone.
                            @elseif($catType === 'game_account')
                                Akun game sultan &amp; smurf bergaransi resmi anti-hackback.
                            @elseif($catType === 'preorder')
                                Preorder PDH &amp; Seragam Organisasi custom ukuran S–3XL.
                            @elseif($catType === 'joki')
                                Joki rank dikerjakan booster pro player berpengalaman.
                            @elseif($catType === 'account')
                                Akun streaming &amp; langganan digital siap pakai.
                            @else
                                Produk {{ $category->name }} berkualitas siap dipesan.
                            @endif
                        </p>
                    </div>
                    <span class="text-[10px] font-mono uppercase tracking-wider text-zinc-500 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-900 px-2.5 py-1 rounded border border-zinc-200 dark:border-zinc-800 hidden sm:inline-block">
                        {{ $category->type_label }}
                    </span>
                </div>

                <!-- Products Grid -->
                @if($category->products->isNotEmpty())
                    @if($catType === 'game_account')
                        <!-- Game Account Grid (16:9 Aspect Cards) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($category->products as $product)
                                @php
                                    $thumb = $product->getThumbnailUrl();
                                    $specs = $product->account_specs ?? [];
                                @endphp
                                <a href="{{ route('products.checkout', $product->slug) }}" class="product-game-card group rounded-xl p-4 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/90 hover:border-zinc-400 dark:hover:border-zinc-700 transition-all flex flex-col justify-between shadow-sm" data-category="{{ $category->slug }}" data-title="{{ $product->name }}" data-publisher="{{ $product->publisher }}">
                                    <div>
                                        <div class="w-full aspect-[16/9] rounded-lg bg-zinc-100 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 overflow-hidden relative mb-3">
                                            @if($thumb)
                                                <img src="{{ $thumb }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-4xl">👑</div>
                                            @endif
                                            <span class="absolute top-2 left-2 text-[9px] font-mono font-bold px-2 py-0.5 rounded bg-zinc-950/80 text-zinc-300 border border-zinc-700 backdrop-blur-sm">
                                                🛡️ Full Garansi
                                            </span>
                                        </div>

                                        <h4 class="font-bold text-xs sm:text-sm text-zinc-900 dark:text-zinc-100 group-hover:text-zinc-600 dark:group-hover:text-white transition-colors line-clamp-1">
                                            {{ $product->name }}
                                        </h4>

                                        @if(!empty($specs))
                                            <div class="mt-2.5 flex flex-wrap gap-1">
                                                @foreach(array_slice($specs, 0, 3) as $k => $v)
                                                    <span class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700/80 text-zinc-600 dark:text-zinc-300">
                                                        <strong>{{ $k }}:</strong> {{ $v }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-4 pt-2.5 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                                        <div>
                                            <span class="text-[9px] text-zinc-500 uppercase tracking-wider block font-mono">Harga Akun</span>
                                            <span class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-zinc-100 font-mono tabular-nums">
                                                Rp {{ number_format($product->variants->min('price') ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="px-2.5 py-1 rounded bg-zinc-100 dark:bg-zinc-800 group-hover:bg-zinc-900 group-hover:text-white dark:group-hover:bg-zinc-100 dark:group-hover:text-zinc-950 text-zinc-700 dark:text-zinc-300 font-mono text-[11px] font-semibold transition-colors flex items-center gap-1">
                                            Beli →
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                    @elseif($catType === 'preorder')
                        <!-- Preorder Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($category->products as $product)
                                @php
                                    $thumb = $product->getThumbnailUrl();
                                    $specs = $product->account_specs ?? [];
                                    $hasDp = $product->variants->contains(fn($v) => str_contains(strtolower($v->name), 'dp') || str_contains(strtolower($v->name), 'uang muka'));
                                    $hasLunas = $product->variants->contains(fn($v) => str_contains(strtolower($v->name), 'lunas'));
                                    $minPrice = $product->variants->min('price') ?? 0;
                                @endphp
                                <a href="{{ route('products.checkout', $product->slug) }}" class="product-game-card group rounded-xl p-4 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/90 hover:border-zinc-400 dark:hover:border-zinc-700 transition-all flex flex-col justify-between shadow-sm" data-category="{{ $category->slug }}" data-title="{{ $product->name }}" data-publisher="{{ $product->publisher }}">
                                    <div>
                                        <div class="w-full aspect-[16/9] rounded-lg bg-zinc-100 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 overflow-hidden relative mb-3">
                                            @if($thumb)
                                                <img src="{{ $thumb }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-4xl">👕</div>
                                            @endif
                                            <span class="absolute top-2 left-2 text-[9px] font-mono font-bold px-2 py-0.5 rounded bg-zinc-950/80 text-zinc-300 border border-zinc-700 backdrop-blur-sm">
                                                PREORDER
                                            </span>
                                            @if($hasDp && $hasLunas)
                                                <span class="absolute top-2 right-2 text-[8px] font-mono font-bold px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                                    DP / LUNAS
                                                </span>
                                            @endif
                                        </div>

                                        <h4 class="font-bold text-xs sm:text-sm text-zinc-900 dark:text-zinc-100 group-hover:text-zinc-600 dark:group-hover:text-white transition-colors line-clamp-1">
                                            {{ $product->name }}
                                        </h4>
                                        <p class="text-[11px] text-zinc-500 mt-0.5 line-clamp-1">{{ $product->publisher ?? 'Konveksi solustion411' }}</p>

                                        @if(!empty($specs))
                                            <div class="mt-2.5 flex flex-wrap gap-1">
                                                @foreach(array_slice($specs, 0, 3) as $k => $v)
                                                    <span class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700/80 text-zinc-600 dark:text-zinc-300">
                                                        <strong>{{ $k }}:</strong> {{ $v }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-4 pt-2.5 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                                        <div>
                                            <span class="text-[9px] text-zinc-500 uppercase tracking-wider block font-mono">Mulai Dari</span>
                                            <span class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-zinc-100 font-mono tabular-nums">
                                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="px-2.5 py-1 rounded bg-zinc-100 dark:bg-zinc-800 group-hover:bg-zinc-900 group-hover:text-white dark:group-hover:bg-zinc-100 dark:group-hover:text-zinc-950 text-zinc-700 dark:text-zinc-300 font-mono text-[11px] font-semibold transition-colors flex items-center gap-1">
                                            Preorder →
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                    @elseif($catType === 'joki')
                        <!-- Jasa Joki Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($category->products as $product)
                                <a href="{{ route('products.checkout', $product->slug) }}" class="product-game-card group rounded-xl p-4 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/90 hover:border-zinc-400 dark:hover:border-zinc-700 transition-all flex flex-col justify-between shadow-sm" data-category="{{ $category->slug }}" data-title="{{ $product->name }}" data-publisher="{{ $product->publisher }}">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center text-xl flex-shrink-0">
                                            ⚔️
                                        </div>
                                        <div>
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-mono font-bold uppercase bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700">
                                                PRO BOOSTER
                                            </span>
                                            <h4 class="font-bold text-xs sm:text-sm text-zinc-900 dark:text-zinc-100 group-hover:text-zinc-600 dark:group-hover:text-white transition-colors mt-1">
                                                {{ $product->name }}
                                            </h4>
                                            <p class="text-[11px] text-zinc-500 mt-0.5 line-clamp-2">{{ $product->description }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-2.5 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                                        <div>
                                            <span class="text-[9px] text-zinc-500 uppercase tracking-wider block font-mono">Mulai Dari</span>
                                            <span class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-zinc-100 font-mono tabular-nums">
                                                Rp {{ number_format($product->variants->min('price') ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="px-2.5 py-1 rounded bg-zinc-100 dark:bg-zinc-800 group-hover:bg-zinc-900 group-hover:text-white dark:group-hover:bg-zinc-100 dark:group-hover:text-zinc-950 text-zinc-700 dark:text-zinc-300 font-mono text-[11px] font-semibold transition-colors flex items-center gap-1">
                                            Pesan Joki →
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                    @elseif($catType === 'account')
                        <!-- Akun Premium & Streaming Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($category->products as $product)
                                <a href="{{ route('products.checkout', $product->slug) }}" class="product-game-card group rounded-xl p-4 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/90 hover:border-zinc-400 dark:hover:border-zinc-700 transition-all flex flex-col justify-between shadow-sm" data-category="{{ $category->slug }}" data-title="{{ $product->name }}" data-publisher="{{ $product->publisher }}">
                                    <div>
                                        <div class="flex items-center gap-2.5 mb-2.5">
                                            <div class="w-9 h-9 rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center text-lg flex-shrink-0">
                                                🍿
                                            </div>
                                            <div>
                                                <span class="text-[9px] font-mono uppercase text-zinc-500">
                                                    {{ $product->publisher ?? 'Digital Service' }}
                                                </span>
                                                <h4 class="font-bold text-xs sm:text-sm text-zinc-900 dark:text-zinc-100 group-hover:text-zinc-600 dark:group-hover:text-white transition-colors line-clamp-1">
                                                    {{ $product->name }}
                                                </h4>
                                            </div>
                                        </div>

                                        @if($product->variants->isNotEmpty())
                                            <div class="mt-2 pt-2 border-t border-zinc-100 dark:border-zinc-800/80">
                                                <span class="text-[9px] font-mono text-zinc-500 uppercase tracking-wider block mb-1">Pilihan Durasi:</span>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($product->variants as $v)
                                                        <span class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700/80 text-zinc-700 dark:text-zinc-300">
                                                            ⏱️ {{ Str::limit(str_replace(['- 1 Profil (Sharing)', '- Akun Private (5 Profil)', ' - Sharing', ' - Private'], '', $v->name), 14) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-3.5 pt-2.5 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                                        <div>
                                            <span class="text-[9px] text-zinc-500 uppercase tracking-wider block font-mono">Mulai Dari</span>
                                            <span class="text-xs sm:text-sm font-bold text-zinc-900 dark:text-zinc-100 font-mono tabular-nums">
                                                Rp {{ number_format($product->variants->min('price') ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="px-2.5 py-1 rounded bg-zinc-100 dark:bg-zinc-800 group-hover:bg-zinc-900 group-hover:text-white dark:group-hover:bg-zinc-100 dark:group-hover:text-zinc-950 text-zinc-700 dark:text-zinc-300 font-mono text-[11px] font-semibold transition-colors flex items-center gap-1">
                                            Pilih Paket →
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                    @else
                        <!-- Top Up Games & General Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
                            @foreach($category->products as $product)
                                <a href="{{ route('products.checkout', $product->slug) }}" class="product-game-card group rounded-xl p-3 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/90 hover:border-zinc-400 dark:hover:border-zinc-700 transition-all flex flex-col justify-between shadow-sm" data-category="{{ $category->slug }}" data-title="{{ $product->name }}" data-publisher="{{ $product->publisher }}">
                                    <div>
                                        <div class="w-full aspect-[4/3] rounded-lg bg-zinc-100 dark:bg-zinc-950 flex flex-col items-center justify-center border border-zinc-200 dark:border-zinc-800 mb-2.5 overflow-hidden relative">
                                            @if($product->getThumbnailUrl())
                                                <img src="{{ $product->getThumbnailUrl() }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="text-3xl">{{ $category->icon_display }}</div>
                                                <span class="text-[9px] font-mono text-zinc-500 uppercase tracking-wider mt-1">{{ $product->publisher ?? $category->name }}</span>
                                            @endif
                                            
                                            <span class="absolute top-1.5 right-1.5 text-[8px] font-mono font-bold px-1.5 py-0.2 rounded bg-zinc-950/80 text-zinc-300 border border-zinc-700 backdrop-blur-sm">
                                                1-3 DETIK
                                            </span>
                                        </div>

                                        <h4 class="font-bold text-xs text-zinc-900 dark:text-zinc-100 group-hover:text-zinc-600 dark:group-hover:text-white transition-colors line-clamp-1">
                                            {{ $product->name }}
                                        </h4>
                                        <p class="text-[10px] text-zinc-500 mt-0.5">{{ $product->publisher ?? $category->name }}</p>
                                    </div>

                                    <div class="mt-3 pt-2 border-t border-zinc-100 dark:border-zinc-800/80 flex items-center justify-between">
                                        <div>
                                            <span class="text-[8px] font-mono text-zinc-500 uppercase tracking-wider block">Mulai</span>
                                            <span class="text-xs font-bold text-zinc-900 dark:text-zinc-100 font-mono tabular-nums">
                                                Rp {{ number_format($product->variants->min('price') ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="w-6 h-6 rounded bg-zinc-100 dark:bg-zinc-800 group-hover:bg-zinc-900 group-hover:text-white dark:group-hover:bg-zinc-100 dark:group-hover:text-zinc-950 text-zinc-600 dark:text-zinc-300 flex items-center justify-center font-mono text-xs transition-colors">
                                            →
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="p-6 rounded-xl bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800 text-center font-mono text-xs text-zinc-500">
                        Belum ada produk aktif di kategori {{ $category->name }}.
                    </div>
                @endif
            </div>
        @empty
            <div class="p-10 rounded-2xl bg-white dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800 text-center flex flex-col items-center justify-center">
                <span class="text-3xl mb-2">📁</span>
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white font-mono">Belum Ada Kategori Layanan</h3>
                <p class="text-xs text-zinc-500 mt-0.5">Admin dapat mengaktifkan kategori melalui panel dashboard.</p>
            </div>
        @endforelse

        <!-- Empty Search Notice -->
        <div id="search-empty-state" class="hidden p-8 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-center flex-col items-center justify-center my-6">
            <span class="text-3xl mb-2">🔍</span>
            <h4 class="text-sm font-bold text-zinc-900 dark:text-white font-mono">Produk Tidak Ditemukan</h4>
            <p class="text-xs text-zinc-500 mt-0.5 max-w-sm">Tidak ada game atau produk yang cocok dengan kata kunci pencarian Anda.</p>
            <button type="button" onclick="document.getElementById('global-game-search').value = ''; filterGamesSearch('');" class="mt-3 px-3.5 py-1.5 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-bold text-xs font-mono transition-all">
                Reset Pencarian
            </button>
        </div>
    </div>

    <!-- 5. VALUE PROPOSITION HIGHLIGHTS (Linear Grid) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mt-12 pt-10 border-t border-zinc-200 dark:border-zinc-800/80">
        <div class="bg-white dark:bg-zinc-900/60 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 hover:border-zinc-400 dark:hover:border-zinc-700 transition-all flex flex-col items-start shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center text-sm mb-2.5">
                ⚡
            </div>
            <h4 class="text-xs font-bold text-zinc-900 dark:text-zinc-100 font-mono">Proses 1-3 Detik</h4>
            <p class="text-[11px] text-zinc-500 mt-0.5">Pengiriman saldo &amp; diamond game instan otomatis 24 jam.</p>
        </div>

        <div class="bg-white dark:bg-zinc-900/60 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 hover:border-zinc-400 dark:hover:border-zinc-700 transition-all flex flex-col items-start shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center text-sm mb-2.5">
                🛡️
            </div>
            <h4 class="text-xs font-bold text-zinc-900 dark:text-zinc-100 font-mono">100% Legal &amp; Garansi</h4>
            <p class="text-[11px] text-zinc-500 mt-0.5">Semua item game resmi aman dari ban dengan garansi toko.</p>
        </div>

        <div class="bg-white dark:bg-zinc-900/60 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 hover:border-zinc-400 dark:hover:border-zinc-700 transition-all flex flex-col items-start shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center text-sm mb-2.5">
                💳
            </div>
            <h4 class="text-xs font-bold text-zinc-900 dark:text-zinc-100 font-mono">Pembayaran Lengkap</h4>
            <p class="text-[11px] text-zinc-500 mt-0.5">Dukungan QRIS instan dan transfer Bank BCA, Mandiri, BRI, BNI.</p>
        </div>

        <a href="https://wa.me/6285150718231?text=Halo%20Admin%20solustion411,%20saya%20butuh%20bantuan" target="_blank" class="bg-white dark:bg-zinc-900/60 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 hover:border-zinc-400 dark:hover:border-zinc-700 transition-all flex flex-col items-start group shadow-sm block">
            <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center text-sm mb-2.5 group-hover:scale-105 transition-transform">
                💬
            </div>
            <h4 class="text-xs font-bold text-zinc-900 dark:text-zinc-100 font-mono">Bantuan CS 24 Jam</h4>
            <p class="text-[11px] text-zinc-500 mt-0.5">Siap membantu via WhatsApp: <strong class="text-zinc-800 dark:text-zinc-200 font-mono">0851-5071-8231</strong>.</p>
        </a>
    </div>

</div>

<!-- Auto Slider & Category Filter Logic -->
<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.banner-slide');
    const dots = document.querySelectorAll('.slider-dot');
    let sliderTimer = null;

    function showSlide(index) {
        if (!slides.length) return;
        if (index >= slides.length) currentSlide = 0;
        else if (index < 0) currentSlide = slides.length - 1;
        else currentSlide = index;

        slides.forEach((slide, i) => {
            if (i === currentSlide) {
                slide.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
                slide.classList.add('opacity-100', 'z-10');
            } else {
                slide.classList.add('opacity-0', 'pointer-events-none', 'z-0');
                slide.classList.remove('opacity-100', 'z-10');
            }
        });

        dots.forEach((dot, i) => {
            if (i === currentSlide) {
                dot.classList.add('bg-zinc-100', 'w-6');
                dot.classList.remove('bg-zinc-600/70', 'w-1.5');
            } else {
                dot.classList.remove('bg-zinc-100', 'w-6');
                dot.classList.add('bg-zinc-600/70', 'w-1.5');
            }
        });
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
    }

    function goToSlide(index) {
        showSlide(index);
        resetSliderTimer();
    }

    function resetSliderTimer() {
        if (sliderTimer) clearInterval(sliderTimer);
        sliderTimer = setInterval(nextSlide, 5000);
    }

    // Initialize Auto Slider
    if (slides.length > 1) {
        resetSliderTimer();
        const sliderContainer = document.getElementById('hero-slider-container');
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', () => clearInterval(sliderTimer));
            sliderContainer.addEventListener('mouseleave', resetSliderTimer);
        }
    }

    // Category Filter Logic (Segmented Control)
    function filterCategory(category, buttonEl) {
        document.querySelectorAll('.category-pill').forEach(pill => {
            pill.classList.remove('active-pill', 'bg-white', 'dark:bg-zinc-800', 'text-zinc-900', 'dark:text-white', 'shadow-sm', 'font-semibold');
            pill.classList.add('text-zinc-600', 'dark:text-zinc-400');
        });

        buttonEl.classList.add('active-pill', 'bg-white', 'dark:bg-zinc-800', 'text-zinc-900', 'dark:text-white', 'shadow-sm', 'font-semibold');
        buttonEl.classList.remove('text-zinc-600', 'dark:text-zinc-400');

        // Show/hide sections
        const groups = document.querySelectorAll('.product-group-section');
        groups.forEach(group => {
            if (category === 'all') {
                group.style.display = '';
            } else if (group.id === 'group-' + category) {
                group.style.display = '';
            } else {
                group.style.display = 'none';
            }
        });
    }

    // Flash Sale Live Countdown Timer
    const fsTimerEl = document.getElementById('flash-sale-timer');
    if (fsTimerEl && fsTimerEl.dataset.endtime) {
        const targetTime = new Date(fsTimerEl.dataset.endtime).getTime();
        function updateFSTimer() {
            const now = new Date().getTime();
            const diff = targetTime - now;
            if (diff <= 0) {
                const elH = document.getElementById('fs-hours');
                const elM = document.getElementById('fs-minutes');
                const elS = document.getElementById('fs-seconds');
                if (elH) elH.textContent = '00';
                if (elM) elM.textContent = '00';
                if (elS) elS.textContent = '00';
                return;
            }
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            const elH = document.getElementById('fs-hours');
            const elM = document.getElementById('fs-minutes');
            const elS = document.getElementById('fs-seconds');
            if (elH) elH.textContent = String(hours).padStart(2, '0');
            if (elM) elM.textContent = String(minutes).padStart(2, '0');
            if (elS) elS.textContent = String(seconds).padStart(2, '0');
        }
        updateFSTimer();
        setInterval(updateFSTimer, 1000);
    }

    // Live Search Filter for Games
    function filterGamesSearch(query) {
        const term = query.toLowerCase().trim();
        const cards = document.querySelectorAll('.product-game-card');
        let matchedCount = 0;
        cards.forEach(card => {
            const title = card.getAttribute('data-title') || '';
            const pub = card.getAttribute('data-publisher') || '';
            if (title.toLowerCase().includes(term) || pub.toLowerCase().includes(term)) {
                card.style.display = '';
                matchedCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const emptyNotice = document.getElementById('search-empty-state');
        if (emptyNotice) {
            if (term.length > 0 && matchedCount === 0) {
                emptyNotice.classList.remove('hidden');
                emptyNotice.classList.add('flex');
            } else {
                emptyNotice.classList.add('hidden');
                emptyNotice.classList.remove('flex');
            }
        }
    }
</script>
@endsection

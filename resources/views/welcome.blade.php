@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-20">
    
    <!-- 1. HERO BANNER CAROUSEL (solustion411 Modern Slider - Admin Configurable) -->
    <div class="relative rounded-3xl overflow-hidden glass-card border border-cyan-500/25 mb-8 sm:mb-10 shadow-2xl group" id="hero-slider-container">
        <div class="relative w-full aspect-[16/10] sm:aspect-[21/9] min-h-[300px] sm:min-h-[360px] md:min-h-[420px] overflow-hidden" id="slider-track">
            @forelse($banners as $index => $banner)
                <div class="banner-slide absolute inset-0 w-full h-full transition-opacity duration-700 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' }}" data-index="{{ $index }}">
                    <!-- Background: Uploaded Image or Dynamic Gradient -->
                    @if($banner->getImageUrl())
                        <img src="{{ $banner->getImageUrl() }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/60 to-transparent"></div>
                    @else
                        <div class="absolute inset-0 bg-gradient-to-r {{ $banner->gradient ?? 'from-cyan-950 via-slate-900 to-indigo-950' }}"></div>
                        <!-- Ambient Gamer Glow & Decorative Graphics -->
                        <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute right-12 top-1/2 -translate-y-1/2 hidden md:flex items-center justify-center opacity-25 pointer-events-none text-9xl">
                            🎮
                        </div>
                    @endif

                    <!-- Slide Content Overlay -->
                    <div class="relative z-20 h-full flex flex-col justify-center px-6 sm:px-12 md:px-16 py-6 sm:py-8 max-w-2xl">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] sm:text-[11px] font-black uppercase bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 mb-3 w-fit backdrop-blur-md">
                            <span class="animate-pulse">⚡</span> {{ $banner->badge_text ?? 'PROMO SPESIAL' }}
                        </div>
                        <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight sm:leading-tight drop-shadow-md">
                            {{ $banner->title }}
                        </h2>
                        @if($banner->subtitle)
                            <p class="mt-2 sm:mt-3 text-xs sm:text-sm md:text-base text-gray-300 line-clamp-2 max-w-xl leading-relaxed drop-shadow">
                                {{ $banner->subtitle }}
                            </p>
                        @endif

                        <div class="mt-5 sm:mt-7 flex flex-wrap items-center gap-3">
                            <a href="{{ $banner->target_url ?? '#catalog-section' }}" class="px-6 sm:px-8 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 via-blue-600 to-indigo-600 hover:from-cyan-400 hover:to-blue-500 text-slate-950 font-black text-xs sm:text-sm shadow-lg shadow-cyan-500/25 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                                <span>Top Up Sekarang</span>
                                <span>→</span>
                            </a>
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-300 backdrop-blur-md bg-slate-950/60 px-3.5 py-2 rounded-xl border border-slate-700/60">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>Proses 1-3 Detik</span>
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback slide if no banner seeded -->
                <div class="banner-slide absolute inset-0 w-full h-full bg-gradient-to-r from-cyan-950 via-slate-900 to-indigo-950 flex flex-col justify-center px-6 sm:px-16 py-6 sm:py-8">
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 w-fit mb-3">⚡ SOLUSTION411 ID</span>
                    <h2 class="text-2xl sm:text-5xl font-black text-white tracking-tight">Top Up Game Termurah &amp; Tercepat</h2>
                    <p class="text-xs sm:text-sm text-gray-300 mt-2">Mobile Legends, Free Fire, Valorant, &amp; Akun Sultan Bergaransi Anti-Hackback.</p>
                    <div class="mt-6">
                        <a href="#catalog-section" class="px-6 py-3 rounded-2xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black text-xs transition-all inline-flex items-center gap-2 shadow-lg shadow-cyan-500/20">
                            <span>Mulai Belanja</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Slider Navigation Controls -->
        <button type="button" onclick="prevSlide()" class="absolute left-3.5 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-2xl bg-slate-950/70 hover:bg-slate-900 text-white border border-slate-700/80 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 backdrop-blur-sm" aria-label="Slide Sebelumnya">
            ❮
        </button>
        <button type="button" onclick="nextSlide()" class="absolute right-3.5 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-2xl bg-slate-950/70 hover:bg-slate-900 text-white border border-slate-700/80 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 backdrop-blur-sm" aria-label="Slide Selanjutnya">
            ❯
        </button>

        <!-- Slider Indicators Dots -->
        <div class="absolute bottom-3 sm:bottom-4 left-1/2 -translate-x-1/2 z-30 flex items-center gap-2">
            @foreach($banners as $index => $banner)
                <button type="button" onclick="goToSlide({{ $index }})" class="slider-dot h-2 sm:h-2.5 rounded-full transition-all {{ $index === 0 ? 'bg-cyan-400 w-7 sm:w-8' : 'bg-slate-700 w-2 sm:w-2.5 hover:bg-slate-500' }}" aria-label="Go to slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
    </div>

    <!-- 2. FLASH SALE / PROMO HARI INI TICKER (Admin Managed) -->
    @if($flashSale)
        <a href="{{ $flashSale->target_url ?? '#catalog-section' }}" class="block group mb-8 sm:mb-10">
            <div class="glass-card rounded-2xl p-4 sm:p-5 border border-amber-500/30 bg-gradient-to-r from-amber-950/30 via-slate-900/80 to-orange-950/30 flex flex-col sm:flex-row items-center justify-between gap-4 hover:border-amber-500/60 transition-all shadow-xl">
                <div class="flex items-center gap-3.5 w-full sm:w-auto">
                    <div class="w-11 h-11 rounded-2xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400 text-2xl font-bold flex-shrink-0 group-hover:scale-110 transition-transform">
                        🔥
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-sm sm:text-base font-black text-white group-hover:text-amber-300 transition-colors">{{ $flashSale->title }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full bg-rose-500/20 text-rose-300 text-[10px] font-black border border-rose-500/30 animate-pulse">{{ $flashSale->badge_text }}</span>
                        </div>
                        @if($flashSale->subtitle)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $flashSale->subtitle }}</p>
                        @endif
                    </div>
                </div>

                @if($flashSale->end_time)
                    <div class="flex items-center gap-2 text-xs font-mono font-bold text-amber-300 bg-slate-950/80 px-4 py-2 rounded-xl border border-amber-500/30 flex-shrink-0 w-full sm:w-auto justify-center" id="flash-sale-timer" data-endtime="{{ $flashSale->end_time->toISOString() }}">
                        <span class="text-[11px] font-sans text-gray-400">Berakhir dalam:</span>
                        <span class="px-2 py-1 bg-amber-500/20 rounded-lg text-amber-300 font-black" id="fs-hours">00</span>:
                        <span class="px-2 py-1 bg-amber-500/20 rounded-lg text-amber-300 font-black" id="fs-minutes">00</span>:
                        <span class="px-2 py-1 bg-amber-500/20 rounded-lg text-amber-300 font-black" id="fs-seconds">00</span>
                    </div>
                @endif
            </div>
        </a>
    @endif

    <!-- 3. CATEGORY TABS FILTER (Clean Modern Pills) -->
    <div class="-mx-4 px-4 sm:mx-0 sm:px-0 mb-8 overflow-x-auto pb-2 scrollbar-none">
        <div class="flex items-center gap-2 sm:gap-2.5 min-w-max">
            <button type="button" onclick="filterCategory('all', this)" class="category-pill active-pill px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl sm:rounded-2xl text-xs font-black transition-all flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-blue-600 text-slate-950 shadow-md shadow-cyan-500/20 border border-cyan-400/40">
                <span>🔥</span>
                <span>Semua Layanan</span>
            </button>
            @foreach($categories as $cat)
                <button type="button" onclick="filterCategory('{{ $cat->slug }}', this)" class="category-pill px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl sm:rounded-2xl text-xs font-bold text-gray-300 hover:text-white bg-slate-900/80 hover:bg-slate-800 border border-slate-800 transition-all flex items-center gap-2">
                    <span>{{ $cat->icon_display }}</span>
                    <span>{{ $cat->name }}</span>
                    @if($cat->products_count ?? $cat->products->count())
                        <span class="px-1.5 py-0.2 rounded-full bg-slate-800 text-[10px] text-gray-400">{{ $cat->products_count ?? $cat->products->count() }}</span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <!-- 4. PRODUCT CATALOG GRID (Clean & Sleek Modern Cards) -->
    <div id="catalog-section" class="scroll-mt-24 mb-16">
        @forelse($categories as $category)
            @php
                $catType = $category->type ?? 'general';
                $accentColor = match($catType) {
                    'game' => 'cyan',
                    'game_account' => 'amber',
                    'preorder' => 'fuchsia',
                    'joki' => 'emerald',
                    'account' => 'indigo',
                    default => 'pink',
                };
            @endphp
            <div class="product-group-section mb-12" id="group-{{ $category->slug }}" data-category="{{ $category->slug }}">
                <!-- Category Section Header -->
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-white flex items-center gap-2.5 tracking-tight">
                            <span class="w-8 h-8 rounded-xl bg-{{ $accentColor }}-500/15 text-{{ $accentColor }}-400 flex items-center justify-center text-sm border border-{{ $accentColor }}-500/30">
                                {{ $category->icon_display }}
                            </span>
                            <span>{{ $category->name }}{{ $catType === 'game_account' && !str_contains(strtolower($category->name), 'bergaransi') ? ' Bergaransi' : '' }}{{ $catType === 'game' && !str_contains(strtolower($category->name), 'favorit') ? ' Favorit' : '' }}</span>
                        </h3>
                        <p class="text-xs text-gray-400 mt-1">
                            @if($catType === 'game')
                                Isi diamond &amp; koin game instan cukup masukkan User ID tanpa antre!
                            @elseif($catType === 'game_account')
                                Akun game sultan &amp; smurf bergaransi anti hackback dengan kredensial langsung.
                            @elseif($catType === 'preorder')
                                Preorder PDH, Kemeja &amp; Seragam Organisasi custom ukuran S–3XL dengan opsi DP &amp; Lunas.
                            @elseif($catType === 'joki')
                                Dikerjakan booster pro player berpengalaman, aman anti banned.
                            @elseif($catType === 'account')
                                Layanan akun digital &amp; streaming dengan durasi fleksibel dan kredensial otomatis.
                            @else
                                Pilihan produk dan layanan {{ $category->name }} terbaik siap dipesan.
                            @endif
                        </p>
                    </div>
                    <span class="text-xs font-bold text-{{ $accentColor }}-300 bg-{{ $accentColor }}-950/40 px-3 py-1 rounded-xl border border-{{ $accentColor }}-500/30 hidden sm:inline-block">
                        {{ $category->type_label }}
                    </span>
                </div>

                <!-- Products Grid -->
                @if($category->products->isNotEmpty())
                    @if($catType === 'game_account')
                        <!-- Jual Beli Akun Grid (16:9 Aspect Cards with Specs) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($category->products as $product)
                                @php
                                    $thumb = $product->getThumbnailUrl();
                                    $specs = $product->account_specs ?? [];
                                @endphp
                                <a href="{{ route('products.checkout', $product->slug) }}" class="product-game-card group glass-card rounded-3xl p-5 border border-slate-800 hover:border-amber-500/50 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between shadow-lg" data-category="{{ $category->slug }}" data-title="{{ $product->name }}" data-publisher="{{ $product->publisher }}">
                                    <div>
                                        <div class="w-full aspect-[16/9] rounded-2xl bg-slate-950 border border-slate-800 overflow-hidden relative mb-4">
                                            @if($thumb)
                                                <img src="{{ $thumb }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-5xl">👑</div>
                                            @endif
                                            <span class="absolute top-2.5 left-2.5 text-[10px] font-black px-2.5 py-0.5 rounded-full bg-slate-950/90 text-amber-300 border border-amber-500/40 backdrop-blur-md">
                                                🛡️ Full Garansi
                                            </span>
                                        </div>

                                        <h4 class="font-black text-sm sm:text-base text-white group-hover:text-amber-300 transition-colors line-clamp-1">
                                            {{ $product->name }}
                                        </h4>

                                        @if(!empty($specs))
                                            <div class="mt-3 flex flex-wrap gap-1.5">
                                                @foreach(array_slice($specs, 0, 3) as $k => $v)
                                                    <span class="text-[10px] px-2 py-0.5 rounded-lg bg-slate-900 border border-slate-800 text-gray-300">
                                                        <strong>{{ $k }}:</strong> {{ $v }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-5 pt-3 border-t border-slate-800 flex items-center justify-between">
                                        <div>
                                            <span class="text-[10px] text-gray-400 block font-semibold uppercase">Harga Akun</span>
                                            <span class="text-base font-black text-amber-300 font-mono">
                                                Rp {{ number_format($product->variants->min('price') ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="px-3.5 py-1.5 rounded-xl bg-amber-500/15 group-hover:bg-amber-400 text-amber-300 group-hover:text-slate-950 font-black text-xs transition-colors flex items-center gap-1">
                                            Beli Akun →
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                    @elseif($catType === 'preorder')
                        <!-- Preorder PDH & Uniform Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($category->products as $product)
                                @php
                                    $thumb = $product->getThumbnailUrl();
                                    $specs = $product->account_specs ?? [];
                                    $hasDp = $product->variants->contains(fn($v) => str_contains(strtolower($v->name), 'dp') || str_contains(strtolower($v->name), 'uang muka'));
                                    $hasLunas = $product->variants->contains(fn($v) => str_contains(strtolower($v->name), 'lunas'));
                                    $minPrice = $product->variants->min('price') ?? 0;
                                @endphp
                                <a href="{{ route('products.checkout', $product->slug) }}" class="product-game-card group glass-card rounded-3xl p-5 border border-slate-800 hover:border-fuchsia-500/50 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between shadow-lg" data-category="{{ $category->slug }}" data-title="{{ $product->name }}" data-publisher="{{ $product->publisher }}">
                                    <div>
                                        <div class="w-full aspect-[16/9] rounded-2xl bg-slate-950 border border-slate-800 overflow-hidden relative mb-4">
                                            @if($thumb)
                                                <img src="{{ $thumb }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-5xl">👕</div>
                                            @endif
                                            <span class="absolute top-2.5 left-2.5 text-[10px] font-black px-2.5 py-0.5 rounded-full bg-slate-950/90 text-fuchsia-300 border border-fuchsia-500/40 backdrop-blur-md">
                                                👕 Preorder Open
                                            </span>
                                            @if($hasDp && $hasLunas)
                                                <span class="absolute top-2.5 right-2.5 text-[9px] font-black px-2 py-0.5 rounded-full bg-emerald-400 text-slate-950 backdrop-blur-md">
                                                    DP &amp; Lunas
                                                </span>
                                            @endif
                                        </div>

                                        <h4 class="font-black text-sm sm:text-base text-white group-hover:text-fuchsia-300 transition-colors line-clamp-1">
                                            {{ $product->name }}
                                        </h4>
                                        <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $product->publisher ?? 'Konveksi solustion411' }}</p>

                                        @if(!empty($specs))
                                            <div class="mt-3 flex flex-wrap gap-1.5">
                                                @foreach(array_slice($specs, 0, 3) as $k => $v)
                                                    <span class="text-[10px] px-2 py-0.5 rounded-lg bg-slate-900 border border-slate-800 text-gray-300">
                                                        <strong class="text-fuchsia-400">{{ $k }}:</strong> {{ $v }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-5 pt-3 border-t border-slate-800 flex items-center justify-between">
                                        <div>
                                            <span class="text-[10px] text-gray-400 block font-semibold uppercase">Mulai Dari (DP/Full)</span>
                                            <span class="text-base font-black text-fuchsia-400 font-mono">
                                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="px-3.5 py-1.5 rounded-xl bg-fuchsia-500/15 group-hover:bg-fuchsia-500 text-fuchsia-300 group-hover:text-white font-black text-xs transition-colors flex items-center gap-1 shadow-sm">
                                            Preorder Sekarang →
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                    @elseif($catType === 'joki')
                        <!-- Jasa Joki Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            @foreach($category->products as $product)
                                <a href="{{ route('products.checkout', $product->slug) }}" class="product-game-card group glass-card rounded-3xl p-5 border border-slate-800 hover:border-emerald-500/50 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between shadow-lg" data-category="{{ $category->slug }}" data-title="{{ $product->name }}" data-publisher="{{ $product->publisher }}">
                                    <div class="flex items-start gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-3xl flex-shrink-0">
                                            ⚔️
                                        </div>
                                        <div>
                                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                                PRO BOOSTER
                                            </span>
                                            <h4 class="font-black text-base text-white group-hover:text-emerald-300 transition-colors mt-1">
                                                {{ $product->name }}
                                            </h4>
                                            <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $product->description }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-5 pt-3 border-t border-slate-800 flex items-center justify-between">
                                        <div>
                                            <span class="text-[10px] text-gray-400 block uppercase font-semibold">Mulai Dari</span>
                                            <span class="text-sm font-black text-emerald-400 font-mono">
                                                Rp {{ number_format($product->variants->min('price') ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="px-3.5 py-1.5 rounded-xl bg-emerald-500/15 group-hover:bg-emerald-500 text-emerald-300 group-hover:text-slate-950 font-black text-xs transition-colors flex items-center gap-1">
                                            Pesan Joki →
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                    @elseif($catType === 'account')
                        <!-- Akun Premium / Streaming Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-5">
                            @foreach($category->products as $product)
                                <a href="{{ route('products.checkout', $product->slug) }}" class="product-game-card group glass-card rounded-3xl p-5 border border-slate-800 hover:border-indigo-500/50 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between shadow-lg" data-category="{{ $category->slug }}" data-title="{{ $product->name }}" data-publisher="{{ $product->publisher }}">
                                    <div>
                                        <div class="flex items-center justify-between gap-3 mb-3.5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-2xl bg-indigo-500/15 border border-indigo-500/30 flex items-center justify-center text-2xl flex-shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                                                    🍿
                                                </div>
                                                <div>
                                                    <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-md bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                                        {{ $product->publisher ?? 'Akun Digital' }}
                                                    </span>
                                                    <h4 class="font-black text-sm sm:text-base text-white group-hover:text-indigo-300 transition-colors line-clamp-1 mt-1">
                                                        {{ $product->name }}
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>

                                        @if($product->variants->isNotEmpty())
                                            <div class="mt-3 pt-3 border-t border-slate-800/80">
                                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1.5">Pilihan Durasi / Variasi:</span>
                                                <div class="flex flex-wrap gap-1.5">
                                                    @foreach($product->variants as $v)
                                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-lg bg-slate-900 border border-slate-800 text-indigo-300 group-hover:border-indigo-500/30 transition-colors">
                                                            ⏱️ {{ Str::limit(str_replace(['- 1 Profil (Sharing)', '- Akun Private (5 Profil)', ' - Sharing', ' - Private'], '', $v->name), 14) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-4 pt-3 border-t border-slate-800 flex items-center justify-between">
                                        <div>
                                            <span class="text-[9px] text-gray-400 block uppercase font-semibold">Mulai Dari</span>
                                            <span class="text-sm font-black text-indigo-300 font-mono">
                                                Rp {{ number_format($product->variants->min('price') ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="px-3.5 py-1.5 rounded-xl bg-indigo-500/15 group-hover:bg-indigo-500 text-indigo-300 group-hover:text-white font-black text-xs transition-colors flex items-center gap-1">
                                            Pilih Paket →
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                    @else
                        <!-- Top-Up Game / Universal Products Grid (Default) -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-5">
                            @foreach($category->products as $product)
                                <a href="{{ route('products.checkout', $product->slug) }}" class="product-game-card group glass-card rounded-2xl p-3.5 border border-slate-800 hover:border-{{ $accentColor }}-500/50 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between shadow-lg" data-category="{{ $category->slug }}" data-title="{{ $product->name }}" data-publisher="{{ $product->publisher }}">
                                    <div>
                                        <div class="w-full aspect-[4/3] rounded-xl bg-gradient-to-tr from-slate-900 to-indigo-950 flex flex-col items-center justify-center border border-slate-800/80 mb-3 group-hover:border-{{ $accentColor }}-500/40 transition-all overflow-hidden relative shadow-md">
                                            @if($product->getThumbnailUrl())
                                                <img src="{{ $product->getThumbnailUrl() }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="text-4xl group-hover:scale-110 transition-transform">{{ $category->icon_display }}</div>
                                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">{{ $product->publisher ?? $category->name }}</span>
                                            @endif
                                            
                                            <span class="absolute top-2 right-2 text-[8px] font-black px-2 py-0.5 rounded-full bg-{{ $accentColor }}-500/90 text-slate-950 backdrop-blur-md shadow-sm">
                                                ⚡ 1-3 DETIK
                                            </span>
                                        </div>

                                        <h4 class="font-black text-xs sm:text-sm text-white group-hover:text-{{ $accentColor }}-300 transition-colors line-clamp-1">
                                            {{ $product->name }}
                                        </h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $product->publisher ?? $category->name }}</p>
                                    </div>

                                    <div class="mt-4 pt-2.5 border-t border-slate-800/80 flex items-center justify-between">
                                        <div>
                                            <span class="text-[9px] text-gray-400 block uppercase font-semibold">Mulai</span>
                                            <span class="text-xs font-black text-{{ $accentColor }}-400 font-mono">
                                                Rp {{ number_format($product->variants->min('price') ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="w-7 h-7 rounded-lg bg-slate-800 group-hover:bg-{{ $accentColor }}-500 text-gray-300 group-hover:text-slate-950 flex items-center justify-center text-xs font-bold transition-colors">
                                            →
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                @else
                    <!-- Empty State for this Category -->
                    <div class="p-8 rounded-3xl glass-card border border-slate-800/80 text-center flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-2xl bg-{{ $accentColor }}-500/10 border border-{{ $accentColor }}-500/20 flex items-center justify-center text-2xl mb-2 text-{{ $accentColor }}-400">
                            {{ $category->icon_display }}
                        </div>
                        <p class="text-xs text-gray-300 font-bold">Belum ada produk aktif di kategori {{ $category->name }}.</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">Admin dapat menambahkan produk baru melalui panel dashboard.</p>
                    </div>
                @endif
            </div>
        @empty
            <!-- State when No Active Categories Exist -->
            <div class="p-12 rounded-3xl glass-card border border-slate-800 text-center flex flex-col items-center justify-center">
                <div class="w-14 h-14 rounded-2xl bg-pink-500/10 border border-pink-500/20 flex items-center justify-center text-3xl mb-3 text-pink-400">📁</div>
                <h3 class="text-base font-bold text-white">Belum Ada Kategori Layanan Aktif</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-md">Admin dapat mengaktifkan atau menambahkan kategori baru melalui panel dashboard untuk menampilkan produk.</p>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.categories.index') }}" class="mt-4 px-4 py-2 rounded-xl bg-pink-600 hover:bg-pink-500 text-white text-xs font-bold transition-all">
                            📁 Kelola Kategori Admin
                        </a>
                    @endif
                @endauth
            </div>
        @endforelse
    </div>

    <!-- 5. KEUNGGULAN LAYANAN (solustion411 Feature Highlights) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mt-16 pt-12 border-t border-slate-800">
        <div class="glass-card p-5 rounded-3xl border border-slate-800 hover:border-cyan-500/30 transition-all flex flex-col items-center sm:items-start text-center sm:text-left">
            <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-2xl mb-3 shadow-sm">
                ⚡
            </div>
            <h4 class="text-sm font-black text-white">Proses Kilat 1-3 Detik</h4>
            <p class="text-[11px] text-gray-400 mt-1 leading-relaxed">Pengiriman diamond game instan &amp; otomatis 24 jam nonstop.</p>
        </div>

        <div class="glass-card p-5 rounded-3xl border border-slate-800 hover:border-amber-500/30 transition-all flex flex-col items-center sm:items-start text-center sm:text-left">
            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-2xl mb-3 shadow-sm">
                🛡️
            </div>
            <h4 class="text-sm font-black text-white">100% Legal &amp; Bergaransi</h4>
            <p class="text-[11px] text-gray-400 mt-1 leading-relaxed">Diamond resmi aman dari ban dan akun game monsep anti hackback.</p>
        </div>

        <div class="glass-card p-5 rounded-3xl border border-slate-800 hover:border-emerald-500/30 transition-all flex flex-col items-center sm:items-start text-center sm:text-left">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl mb-3 shadow-sm">
                💳
            </div>
            <h4 class="text-sm font-black text-white">Metode Bayar Lengkap</h4>
            <p class="text-[11px] text-gray-400 mt-1 leading-relaxed">QRIS, BCA, Mandiri, BRI, DANA, OVO, ShopeePay hingga Indomaret.</p>
        </div>

        <a href="https://wa.me/6285150718231?text=Halo%20Admin%20solustion411,%20saya%20butuh%20bantuan%20terkait%20layanan" target="_blank" class="glass-card p-5 rounded-3xl border border-slate-800 hover:border-purple-500/40 hover:-translate-y-1 transition-all flex flex-col items-center sm:items-start text-center sm:text-left group cursor-pointer block">
            <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-2xl mb-3 shadow-sm group-hover:scale-110 transition-transform">
                💬
            </div>
            <h4 class="text-sm font-black text-white group-hover:text-purple-300 transition-colors">Customer Support 24/7</h4>
            <p class="text-[11px] text-gray-400 mt-1 leading-relaxed">Bantuan ramah &amp; responsif via WhatsApp: <strong class="text-purple-300 font-mono">0851-5071-8231</strong>.</p>
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
                dot.classList.add('bg-cyan-400', 'w-8');
                dot.classList.remove('bg-slate-700', 'w-2.5');
            } else {
                dot.classList.remove('bg-cyan-400', 'w-8');
                dot.classList.add('bg-slate-700', 'w-2.5');
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

    // Category Filter Logic
    function filterCategory(category, buttonEl) {
        // Highlight active pill
        document.querySelectorAll('.category-pill').forEach(pill => {
            pill.classList.remove('active-pill', 'bg-gradient-to-r', 'from-cyan-500', 'to-blue-600', 'text-slate-950', 'shadow-md', 'shadow-cyan-500/20', 'border-cyan-400/40');
            pill.classList.add('text-gray-300', 'bg-slate-900/80', 'border-slate-800');
        });

        buttonEl.classList.add('active-pill', 'bg-gradient-to-r', 'from-cyan-500', 'to-blue-600', 'text-slate-950', 'shadow-md', 'shadow-cyan-500/20', 'border-cyan-400/40');
        buttonEl.classList.remove('text-gray-300', 'bg-slate-900/80', 'border-slate-800');

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
</script>
@endsection

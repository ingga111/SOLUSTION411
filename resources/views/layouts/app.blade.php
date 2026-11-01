<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'solustion411 - Top Up Game & Akun Digital Cepat & Presisi' }}</title>
    <meta name="description" content="Platform top up game instan, akun digital, dan joki dengan sistem presisi otomatis 24 jam nonstop proses 1-3 detik.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Instant Theme Detection Script (Anti-FOUC) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark) || savedTheme === null) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'ui-monospace', 'SFMono-Regular', 'Menlo', 'monospace'],
                    },
                    colors: {
                        zinc: {
                            850: '#1f1f23',
                            900: '#18181b',
                            950: '#09090b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --bg-canvas: #fafafa;
            --surface-card: #ffffff;
            --surface-border: #e4e4e7;
            --text-heading: #09090b;
            --text-muted: #71717a;
        }

        html.dark {
            --bg-canvas: #09090b;
            --surface-card: #121215;
            --surface-border: #27272a;
            --text-heading: #f4f4f5;
            --text-muted: #a1a1aa;
        }

        body {
            background-color: var(--bg-canvas);
            color: var(--text-heading);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .linear-card {
            background-color: var(--surface-card);
            border: 1px solid var(--surface-border);
        }

        /* Seamless Linear Ticker */
        @keyframes linearTicker {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .linear-ticker-track {
            display: inline-flex;
            white-space: nowrap;
            animation: linearTicker 36s linear infinite;
        }
        .linear-ticker-track:hover {
            animation-play-state: paused;
        }

        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .mobile-bottom-nav {
                padding-bottom: max(0.5rem, env(safe-area-inset-bottom));
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-zinc-800 selection:text-white dark:selection:bg-zinc-200 dark:selection:text-zinc-950 {{ request()->routeIs('products.checkout') || request()->routeIs('orders.show') ? 'pb-24 sm:pb-28' : 'pb-16 md:pb-0' }}">

    <!-- 1. SLIM TICKER BROADCAST (Linear / Raycast Style) -->
    @php
        $activeTickers = isset($runningTexts) ? $runningTexts : collect([]);
    @endphp

    @if($activeTickers->isNotEmpty())
        <div id="ticker-broadcast-bar" class="h-8 bg-zinc-100 dark:bg-zinc-950/90 border-b border-zinc-200 dark:border-zinc-800/80 px-3 sm:px-4 text-[11px] overflow-hidden relative z-50 flex items-center backdrop-blur-sm">
            <div class="max-w-7xl mx-auto w-full flex items-center gap-3">
                <span class="flex-shrink-0 flex items-center gap-1.5 px-2 py-0.5 rounded text-[9px] font-mono font-bold uppercase tracking-wider bg-zinc-200 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border border-zinc-300 dark:border-zinc-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    LIVE
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.running-texts.index') }}" title="Kelola Broadcast" class="ml-1 hover:text-zinc-900 dark:hover:text-white">✏️</a>
                        @endif
                    @endauth
                </span>
                <div class="overflow-hidden whitespace-nowrap flex-1">
                    <div class="linear-ticker-track text-[11px] font-mono text-zinc-600 dark:text-zinc-400">
                        @foreach($activeTickers as $ticker)
                            @if(!empty($ticker->url))
                                <a href="{{ $ticker->url }}" class="mx-5 hover:text-zinc-900 dark:hover:text-zinc-200 transition-colors inline-flex items-center gap-1.5">
                                    <span>{{ $ticker->icon ?? '⚡' }}</span>
                                    <span>{{ $ticker->content }}</span>
                                </a>
                            @else
                                <span class="mx-5 inline-flex items-center gap-1.5">
                                    <span>{{ $ticker->icon ?? '⚡' }}</span>
                                    <span>{{ $ticker->content }}</span>
                                </span>
                            @endif
                        @endforeach
                        <!-- Duplicate for seamless loop -->
                        @foreach($activeTickers as $ticker)
                            @if(!empty($ticker->url))
                                <a href="{{ $ticker->url }}" class="mx-5 hover:text-zinc-900 dark:hover:text-zinc-200 transition-colors inline-flex items-center gap-1.5" aria-hidden="true">
                                    <span>{{ $ticker->icon ?? '⚡' }}</span>
                                    <span>{{ $ticker->content }}</span>
                                </a>
                            @else
                                <span class="mx-5 inline-flex items-center gap-1.5" aria-hidden="true">
                                    <span>{{ $ticker->icon ?? '⚡' }}</span>
                                    <span>{{ $ticker->content }}</span>
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. SLIM STICKY HEADER (Raycast Command-Bar Driven) -->
    <header class="sticky top-0 z-40 backdrop-blur-md bg-white/80 dark:bg-zinc-950/80 border-b border-zinc-200 dark:border-zinc-800/80 transition-colors h-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between gap-4">
            
            <!-- Brand Mark -->
            <div class="flex items-center gap-4 flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-7 h-7 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 flex items-center justify-center font-bold text-xs font-mono shadow-sm group-hover:scale-105 transition-transform flex-shrink-0">
                        ⚡
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-sm tracking-tight text-zinc-900 dark:text-zinc-100 font-mono">solustion<span class="text-zinc-400">411</span></span>
                        <span class="text-[9px] font-mono px-1.5 py-0.2 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700/60 uppercase">id</span>
                    </div>
                </a>
            </div>

            <!-- Raycast-Style Command Search Bar (Desktop) -->
            <div class="hidden md:flex flex-1 max-w-sm mx-4">
                <div class="relative w-full">
                    <input type="text" id="global-game-search" oninput="filterGamesSearch(this.value)" placeholder="Cari game, produk, atau invoice..." class="w-full bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg pl-8 pr-12 py-1.5 text-xs text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-400 dark:focus:ring-zinc-600 transition-all font-mono">
                    <div class="absolute left-2.5 top-2 text-zinc-400 dark:text-zinc-500 pointer-events-none">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <div class="absolute right-2 top-1.5 px-1.5 py-0.5 rounded bg-zinc-200/80 dark:bg-zinc-800 text-[10px] font-mono text-zinc-600 dark:text-zinc-400 pointer-events-none border border-zinc-300 dark:border-zinc-700/80">
                        Ctrl K
                    </div>
                </div>
            </div>

            <!-- Navigation & Quick Tools -->
            <div class="flex items-center gap-1.5 sm:gap-2">
                <!-- Direct Track Order Tool Button -->
                <button type="button" onclick="document.getElementById('modal-track-order').classList.remove('hidden')" class="hidden sm:flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Lacak Pesanan</span>
                </button>

                <!-- MLBB Win Rate Calculator Trigger -->
                <button type="button" onclick="document.getElementById('modal-calculator-mlbb').classList.remove('hidden')" class="hidden sm:flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Hitung WR</span>
                </button>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-2.5 py-1 rounded-md text-xs font-mono font-bold text-amber-500 dark:text-amber-400 bg-amber-500/10 border border-amber-500/20 hover:bg-amber-500/20 transition-all flex items-center gap-1">
                            <span>⚡</span>
                            <span>Admin</span>
                        </a>
                    @endif
                @endauth

                <!-- Mobile Quick Search Trigger -->
                <button type="button" onclick="document.getElementById('modal-track-order').classList.remove('hidden')" class="sm:hidden w-8 h-8 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-100 dark:bg-zinc-900 flex items-center justify-center text-zinc-600 dark:text-zinc-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                <!-- THEME TOGGLE (Monochrome) -->
                <button id="theme-toggle" type="button" aria-label="Ganti Tema" class="w-8 h-8 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-100 dark:bg-zinc-900 flex items-center justify-center text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-all">
                    <span id="theme-toggle-dark-icon" class="text-xs select-none">🌙</span>
                    <span id="theme-toggle-light-icon" class="hidden text-xs select-none">☀️</span>
                </button>

                <!-- Profile / Auth Hub -->
                @auth
                    <div class="relative" id="profile-dropdown-container">
                        <button id="profile-btn" type="button" aria-expanded="false" aria-label="Menu Profil" class="w-8 h-8 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-mono font-bold text-xs flex items-center justify-center hover:opacity-90 transition-opacity focus:outline-none">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </button>

                        <!-- Linear Dropdown Menu -->
                        <div id="profile-menu" class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-zinc-900 rounded-xl p-1.5 border border-zinc-200 dark:border-zinc-800 shadow-xl z-50 text-xs">
                            <div class="px-2.5 py-2 border-b border-zinc-100 dark:border-zinc-800/80 mb-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-zinc-900 dark:text-zinc-100 truncate block">{{ auth()->user()->name }}</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-mono uppercase {{ auth()->user()->isAdmin() ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-500' }}">
                                        {{ auth()->user()->isAdmin() ? 'Admin' : 'Member' }}
                                    </span>
                                </div>
                                <span class="text-[10px] font-mono text-zinc-500 dark:text-zinc-400 truncate block mt-0.5">{{ auth()->user()->email }}</span>
                            </div>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                <span>👤</span>
                                <span>Profil Pengguna</span>
                            </a>

                            <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                <span>📦</span>
                                <span>Riwayat Pesanan</span>
                            </a>

                            @if(auth()->user()->isAdmin())
                                <div class="my-1 border-t border-zinc-100 dark:border-zinc-800/80"></div>
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-amber-600 dark:text-amber-400 font-semibold hover:bg-amber-50 dark:hover:bg-amber-950/20 transition-colors">
                                    <span>⚡</span>
                                    <span>Dashboard Admin</span>
                                </a>
                                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                    <span>📋</span>
                                    <span>Verifikasi Pesanan</span>
                                </a>
                                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                    <span>📦</span>
                                    <span>Kelola Produk</span>
                                </a>
                                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                                    <span>📁</span>
                                    <span>Kelola Kategori</span>
                                </a>
                            @endif

                            <div class="my-1 border-t border-zinc-100 dark:border-zinc-800/80"></div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-colors">
                                    <span>🚪</span>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- High Contrast Minimal Login Button -->
                    <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-bold text-xs hover:bg-zinc-800 dark:hover:bg-white transition-all shadow-sm">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Notifications (Linear Banner Style) -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-3 w-full">
            <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-500/30 text-emerald-800 dark:text-emerald-300 px-3.5 py-2.5 rounded-xl flex items-center gap-2.5 text-xs font-mono">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-3 w-full">
            <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-500/30 text-rose-800 dark:text-rose-300 px-3.5 py-2.5 rounded-xl flex items-center gap-2.5 text-xs font-mono">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- 3. LINEAR-STYLE FOOTER -->
    <footer class="border-t border-zinc-200 dark:border-zinc-800/80 bg-white dark:bg-zinc-950 mt-20 pt-12 pb-16 md:pb-10 text-xs transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16 pb-10 border-b border-zinc-100 dark:border-zinc-800/80">
                <!-- Col 1: Bio -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-md bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 flex items-center justify-center font-bold text-[11px] font-mono">
                            ⚡
                        </div>
                        <span class="font-bold text-sm tracking-tight text-zinc-900 dark:text-zinc-100 font-mono">solustion<span class="text-zinc-400">411</span></span>
                    </div>
                    <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed text-[11px] max-w-md">
                        Platform e-commerce digital store dan top up game instan dengan kecepatan proses 1-3 detik, sistem keamanan terverifikasi, dan layanan pelanggan 24/7.
                    </p>
                    <div class="flex items-center gap-2 text-[10px] font-mono text-zinc-500 dark:text-zinc-400 pt-1">
                        <span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">⚡ 1-3 DETIK PROSES</span>
                        <span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">🛡️ 100% GARANSI</span>
                        <span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">🔒 SISTEM AMAN</span>
                    </div>
                </div>

                <!-- Col 2: Support & Contact -->
                <div class="space-y-3">
                    <h4 class="font-mono font-bold text-zinc-900 dark:text-zinc-100 text-xs uppercase tracking-wider">
                        Bantuan & Kontak Resmi
                    </h4>
                    <p class="text-zinc-500 dark:text-zinc-400 text-[11px]">
                        Butuh konfirmasi transaksi atau pertanyaan produk? Hubungi Customer Service kami melalui saluran WhatsApp resmi.
                    </p>
                    <div class="flex flex-wrap gap-2.5 pt-1">
                        <a href="https://wa.me/6285150718231?text=Halo%20Admin%20solustion411,%20saya%20butuh%20bantuan" target="_blank" class="px-3.5 py-2 rounded-lg bg-zinc-100 dark:bg-zinc-900 hover:bg-zinc-200 dark:hover:bg-zinc-800 border border-zinc-200 dark:border-zinc-800 text-zinc-800 dark:text-zinc-200 font-mono text-xs font-semibold transition-all inline-flex items-center gap-1.5">
                            <span>📱 WhatsApp CS: 0851-5071-8231</span>
                        </a>
                        <button type="button" onclick="document.getElementById('modal-track-order').classList.remove('hidden')" class="px-3.5 py-2 rounded-lg bg-zinc-100 dark:bg-zinc-900 hover:bg-zinc-200 dark:hover:bg-zinc-800 border border-zinc-200 dark:border-zinc-800 text-zinc-800 dark:text-zinc-200 font-mono text-xs font-semibold transition-all inline-flex items-center gap-1.5">
                            <span>🔍 Cek Pesanan</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-zinc-500 text-[11px] font-mono">
                <p>&copy; {{ date('Y') }} solustion411. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center gap-3">
                    <span class="hover:text-zinc-700 dark:hover:text-zinc-300 cursor-pointer">Syarat & Ketentuan</span>
                    <span>•</span>
                    <span class="hover:text-zinc-700 dark:hover:text-zinc-300 cursor-pointer">Kebijakan Privasi</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- 4. MOBILE BOTTOM DOCK (Raycast Floating Bar Style) -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/90 dark:bg-zinc-950/90 backdrop-blur-md border-t border-zinc-200 dark:border-zinc-800 px-4 py-2 mobile-bottom-nav">
        <div class="flex items-center justify-around text-center">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('home') ? 'text-zinc-900 dark:text-white font-bold' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}">
                <span class="text-base">🏠</span>
                <span class="text-[10px]">Beranda</span>
            </a>
            <button type="button" onclick="document.getElementById('modal-track-order').classList.remove('hidden')" class="flex flex-col items-center gap-0.5 text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                <span class="text-base">🔍</span>
                <span class="text-[10px]">Lacak</span>
            </button>
            <button type="button" onclick="document.getElementById('modal-calculator-mlbb').classList.remove('hidden')" class="flex flex-col items-center gap-0.5 text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                <span class="text-base">🧮</span>
                <span class="text-[10px]">Hitung WR</span>
            </button>
            @auth
                <a href="{{ route('customer.orders.index') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('customer.*') ? 'text-zinc-900 dark:text-white font-bold' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}">
                    <span class="text-base">📦</span>
                    <span class="text-[10px]">Pesanan</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('login') ? 'text-zinc-900 dark:text-white font-bold' : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-white' }}">
                    <span class="text-base">👤</span>
                    <span class="text-[10px]">Masuk</span>
                </a>
            @endauth
        </div>
    </div>

    <!-- MODAL 1: CEK PESANAN CEPAT -->
    <div id="modal-track-order" class="hidden fixed inset-0 z-50 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 max-w-md w-full shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800 mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-mono text-zinc-400">#</span>
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white font-mono">Lacak Status Pesanan</h3>
                </div>
                <button type="button" onclick="document.getElementById('modal-track-order').classList.add('hidden')" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white text-sm font-mono">✕</button>
            </div>

            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-4 leading-relaxed">
                Masukkan Nomor Invoice yang tertera pada transaksi Anda (Contoh: <code class="text-zinc-900 dark:text-zinc-200 font-mono">INV-20261003-XXXX</code>).
            </p>

            <form onsubmit="event.preventDefault(); const inv = document.getElementById('input-track-inv').value.trim(); if(inv) window.location.href='/invoice/' + inv;" class="space-y-3.5">
                <div>
                    <label class="block text-[11px] font-mono font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Nomor Invoice</label>
                    <input type="text" id="input-track-inv" required placeholder="INV-XXXXXXXX-XXXX" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3.5 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500 uppercase">
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-track-order').classList.add('hidden')" class="px-3 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-xs font-semibold hover:bg-zinc-200 dark:hover:bg-zinc-700">Tutup</button>
                    <button type="submit" class="px-4 py-1.5 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 text-xs font-bold hover:bg-zinc-800 dark:hover:bg-white transition-all shadow-sm">Cek Sekarang</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: KALKULATOR WIN RATE MLBB -->
    <div id="modal-calculator-mlbb" class="hidden fixed inset-0 z-50 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 max-w-md w-full shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800 mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-mono text-zinc-400">%</span>
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white font-mono">Kalkulator WR Mobile Legends</h3>
                </div>
                <button type="button" onclick="document.getElementById('modal-calculator-mlbb').classList.add('hidden')" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white text-sm font-mono">✕</button>
            </div>

            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-4">
                Kalkulasi jumlah kemenangan beruntun (win streak) yang diperlukan untuk mencapai target win rate.
            </p>

            <div class="space-y-3">
                <div>
                    <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Total Pertandingan Saat Ini</label>
                    <input type="number" id="calc-total-match" placeholder="Contoh: 350" min="1" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">WR Saat Ini (%)</label>
                        <input type="number" step="0.1" id="calc-current-wr" placeholder="51.5" min="0" max="100" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Target WR (%)</label>
                        <input type="number" step="0.1" id="calc-target-wr" placeholder="65.0" min="1" max="100" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-zinc-500">
                    </div>
                </div>

                <button type="button" onclick="calculateWinRate()" class="w-full py-2.5 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-bold text-xs hover:bg-zinc-800 dark:hover:bg-white transition-all shadow-sm">
                    Kalkulasi Kemenangan
                </button>

                <!-- Result Box -->
                <div id="calc-result" class="hidden p-3.5 rounded-xl bg-zinc-100 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-center font-mono">
                    <span class="text-[11px] text-zinc-500 block">Dibutuhkan sekitar:</span>
                    <span id="calc-wins-needed" class="text-xl font-bold text-zinc-900 dark:text-white my-1 block">0 Win Streak</span>
                    <span class="text-[10px] text-zinc-400 block">kemenangan tanpa kalah berturut-turut.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Theme Toggle Logic
            const themeToggleBtn = document.getElementById('theme-toggle');
            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');

            function syncThemeIcon() {
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    darkIcon.classList.add('hidden');
                    lightIcon.classList.remove('hidden');
                } else {
                    lightIcon.classList.add('hidden');
                    darkIcon.classList.remove('hidden');
                }
            }

            syncThemeIcon();

            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.contains('dark');
                    if (isDark) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                    syncThemeIcon();
                });
            }

            // Keyboard shortcut for search (Ctrl + K)
            window.addEventListener('keydown', function (e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const searchInput = document.getElementById('global-game-search');
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                }
            });

            // Profile Dropdown
            const profileBtn = document.getElementById('profile-btn');
            const profileMenu = document.getElementById('profile-menu');

            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isHidden = profileMenu.classList.contains('hidden');
                    if (isHidden) {
                        profileMenu.classList.remove('hidden');
                        profileBtn.setAttribute('aria-expanded', 'true');
                    } else {
                        profileMenu.classList.add('hidden');
                        profileBtn.setAttribute('aria-expanded', 'false');
                    }
                });

                document.addEventListener('click', function (e) {
                    if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
                        profileMenu.classList.add('hidden');
                        profileBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });

        // MLBB Calculator Logic
        function calculateWinRate() {
            const totalMatch = parseFloat(document.getElementById('calc-total-match').value);
            const currentWr = parseFloat(document.getElementById('calc-current-wr').value);
            const targetWr = parseFloat(document.getElementById('calc-target-wr').value);
            const resBox = document.getElementById('calc-result');
            const resText = document.getElementById('calc-wins-needed');

            if (isNaN(totalMatch) || isNaN(currentWr) || isNaN(targetWr)) {
                alert('Silakan lengkapi semua kolom pertandingan & win rate.');
                return;
            }

            if (targetWr <= currentWr) {
                alert('Target win rate harus lebih tinggi daripada win rate saat ini!');
                return;
            }

            if (targetWr >= 100) {
                alert('Target win rate tidak bisa mencapai 100% jika sudah pernah kalah.');
                return;
            }

            const currentWins = (currentWr / 100) * totalMatch;
            const winsNeeded = Math.ceil(((targetWr * totalMatch) - (100 * currentWins)) / (100 - targetWr));

            resText.innerText = winsNeeded + ' Win Streak';
            resBox.classList.remove('hidden');
        }

        // Live Search Filter for Games
        function filterGamesSearch(query) {
            const term = query.toLowerCase().trim();
            const cards = document.querySelectorAll('.product-game-card');
            cards.forEach(card => {
                const title = card.getAttribute('data-title') || '';
                const pub = card.getAttribute('data-publisher') || '';
                if (title.toLowerCase().includes(term) || pub.toLowerCase().includes(term)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>

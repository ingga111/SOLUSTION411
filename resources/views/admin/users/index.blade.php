@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-zinc-200">Manajemen User</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-white">Kelola User &amp; Administrator</h1>
            <p class="text-xs text-zinc-400 mt-1">Daftar semua pengguna terdaftar, buat akun admin baru, dan atur hak akses.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-zinc-300 hover:text-white text-xs font-medium border border-zinc-800 transition-colors">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white text-zinc-950 hover:bg-zinc-200 text-xs font-semibold transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>Tambah User Baru</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-zinc-900/60 rounded-xl p-5 border border-zinc-800">
            <span class="text-xs text-zinc-400 font-medium">Total Pengguna</span>
            <div class="text-2xl font-bold tracking-tight text-white mt-1 tabular-nums">{{ number_format($totalUsers) }}</div>
        </div>

        <div class="bg-zinc-900/60 rounded-xl p-5 border border-zinc-800">
            <span class="text-xs text-zinc-400 font-medium">Administrator</span>
            <div class="text-2xl font-bold tracking-tight text-amber-400 mt-1 tabular-nums">{{ number_format($totalAdmins) }}</div>
        </div>

        <div class="bg-zinc-900/60 rounded-xl p-5 border border-zinc-800">
            <span class="text-xs text-zinc-400 font-medium">Pelanggan (Customer)</span>
            <div class="text-2xl font-bold tracking-tight text-white mt-1 tabular-nums">{{ number_format($totalCustomers) }}</div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-zinc-900/60 rounded-xl p-4 border border-zinc-800 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no. WhatsApp..." class="w-full bg-zinc-950 border border-zinc-800 rounded-lg pl-9 pr-4 py-2.5 text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-zinc-600 transition-colors">
                <svg class="w-3.5 h-3.5 absolute left-3 top-3 text-zinc-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>

            <div class="w-full md:w-52">
                <select name="role" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:border-zinc-600 transition-colors">
                    <option value="">Semua Peran (Role)</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer (Pelanggan)</option>
                </select>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none px-4 py-2.5 rounded-lg bg-white text-zinc-950 text-xs font-semibold hover:bg-zinc-200 transition-colors">
                    Filter
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-xs font-medium border border-zinc-700 transition-colors" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-zinc-900/60 rounded-xl border border-zinc-800 overflow-hidden">
        <div class="p-5 border-b border-zinc-800 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-white">Daftar Pengguna</h3>
            <span class="text-xs text-zinc-400 tabular-nums">Menampilkan {{ $users->total() }} total akun</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-950/60 text-zinc-400 uppercase tracking-wider text-[10px] border-b border-zinc-800">
                    <tr>
                        <th class="py-3 px-4 font-medium">Pengguna</th>
                        <th class="py-3 px-4 font-medium">Email</th>
                        <th class="py-3 px-4 font-medium">WhatsApp</th>
                        <th class="py-3 px-4 font-medium">Peran (Role)</th>
                        <th class="py-3 px-4 font-medium">Total Pesanan</th>
                        <th class="py-3 px-4 font-medium">Tgl Bergabung</th>
                        <th class="py-3 px-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/80">
                    @forelse($users as $user)
                        <tr class="hover:bg-zinc-800/20 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-medium text-xs flex-shrink-0 {{ $user->isAdmin() ? 'bg-amber-500/10 border border-amber-500/20 text-amber-300' : 'bg-zinc-800 border border-zinc-700 text-zinc-300' }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-medium text-white text-xs">{{ $user->name }}</span>
                                            @if($user->id === auth()->id())
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Anda</span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-zinc-500 tabular-nums">ID: #{{ $user->id }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4 font-mono text-zinc-300 text-[11px]">
                                {{ $user->email }}
                            </td>

                            <td class="py-3.5 px-4 font-mono text-zinc-400 text-[11px]">
                                {{ $user->phone ?? '-' }}
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium {{ $user->isAdmin() ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-zinc-800 text-zinc-300 border border-zinc-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->isAdmin() ? 'bg-amber-400' : 'bg-zinc-500' }}"></span>
                                    {{ $user->isAdmin() ? 'Admin' : 'Customer' }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="text-xs font-medium tabular-nums {{ $user->orders_count > 0 ? 'text-white' : 'text-zinc-500' }}">
                                    {{ $user->orders_count }} <span class="text-zinc-500 font-normal">transaksi</span>
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-zinc-400 tabular-nums text-[11px]">
                                {{ $user->created_at->format('d M Y, H:i') }}
                            </td>

                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white border border-zinc-700 transition-colors" title="Edit Pengguna">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-colors" title="Hapus Pengguna">
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-zinc-500 text-xs">
                                Tidak ada data pengguna yang sesuai dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-5 border-t border-zinc-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-sm mx-auto px-4 py-12">
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 sm:p-7 shadow-sm">
        
        <div class="text-center mb-6">
            <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 flex items-center justify-center font-bold font-mono text-base mx-auto mb-2.5 shadow-sm">
                ✨
            </div>
            <h1 class="text-xl font-extrabold text-zinc-900 dark:text-zinc-100 tracking-tight font-mono">Daftar Akun</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Buat akun untuk mempermudah pengecekan riwayat pesanan</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST" class="space-y-3.5">
            @csrf

            <div>
                <label for="name" class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Nama Anda" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-500 font-mono">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-500 font-mono">
                @error('email')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">No. WhatsApp</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-500 font-mono">
                @error('phone')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 pr-9 text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-500 font-mono">
                    <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 focus:outline-none" title="Lihat Password">
                        <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg class="w-4 h-4 eye-off-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-[11px] font-mono text-zinc-700 dark:text-zinc-300 mb-1">Konfirmasi Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 pr-9 text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-500 font-mono">
                    <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 focus:outline-none" title="Lihat Password">
                        <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg class="w-4 h-4 eye-off-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                    </button>
                </div>
            </div>

            <!-- Cloudflare Turnstile Captcha -->
            <div class="my-2 flex flex-col items-center justify-center">
                <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.key') }}" data-theme="auto"></div>
                @error('cf-turnstile-response')
                    <p class="text-xs text-rose-500 mt-1 text-center font-mono">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full mt-1 py-2.5 rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-950 font-bold font-mono text-xs hover:bg-zinc-800 dark:hover:bg-white transition-all flex items-center justify-center gap-1.5 shadow-sm">
                <span>Daftar Sekarang →</span>
            </button>
        </form>

        <div class="mt-5 pt-4 border-t border-zinc-100 dark:border-zinc-800 text-center text-xs font-mono text-zinc-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-bold text-zinc-900 dark:text-white hover:underline ml-1">
                Masuk di sini
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        
        const eyeIcon = btn.querySelector('.eye-icon');
        const eyeOffIcon = btn.querySelector('.eye-off-icon');
        if (eyeIcon && eyeOffIcon) {
            if (isPassword) {
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    }
</script>
@endpush

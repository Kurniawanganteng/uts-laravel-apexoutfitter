<x-guest-layout>
    <!-- Flash Status Messages -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if(session('error'))
    <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 p-3.5 text-xs font-semibold text-red-800 flex items-center space-x-2">
        <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="mb-6">
        <h1 class="text-xl font-black text-slate-900 tracking-tight">Masuk ke Akun Anda</h1>
        <p class="text-xs text-slate-500 mt-1">
            Gunakan alamat email dan kata sandi yang terdaftar untuk mengakses layanan Apex Outfitter.
        </p>
    </div>

    <!-- Formulir Login Standar & Bersih -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       placeholder="nama@email.com"
                       class="w-full pl-9 pr-3.5 py-2.5 text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none transition" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-xs font-bold text-slate-700">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-[11px] text-emerald-700 hover:text-emerald-900 font-medium" href="{{ route('password.request') }}">
                        Lupa sandi?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full pl-9 pr-3.5 py-2.5 text-sm rounded-xl border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none transition" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-xs text-slate-600 font-medium">Ingat saya</span>
            </label>
        </div>

        <!-- Tombol Masuk -->
        <div class="pt-2">
            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm py-2.5 px-4 rounded-xl shadow-md transition">
                Masuk
            </button>
        </div>
    </form>

    <!-- Registrasi Publik Pelanggan Baru -->
    <div class="mt-6 pt-5 border-t border-slate-200 text-center">
        <p class="text-xs text-slate-600">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:text-emerald-900 hover:underline">
                Daftar Pelanggan Baru
            </a>
        </p>
    </div>

    <!-- Bantuan Pengisian Cepat (Discreet Helper untuk Pengujian Dosen/UTS) -->
    <details class="mt-6 pt-4 border-t border-slate-100 text-xs text-slate-400 group">
        <summary class="cursor-pointer hover:text-slate-600 font-medium list-none flex items-center justify-center space-x-1">
            <span>Bantuan Akun Pengujian Demo</span>
            <svg class="w-3 h-3 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
        </summary>
        <div class="mt-3 p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-2 text-left">
            <div class="text-[11px] font-semibold text-slate-500">Klik untuk mengisi form secara otomatis:</div>
            <div class="grid grid-cols-3 gap-1.5">
                <button type="button" onclick="fillForm('admin@mail.com', 'password123')" class="p-2 rounded-xl bg-white border border-slate-200 text-[11px] font-bold text-slate-700 hover:bg-amber-50 hover:text-amber-800 hover:border-amber-300 transition text-center">
                    Owner
                </button>
                <button type="button" onclick="fillForm('staff@mail.com', 'password123')" class="p-2 rounded-xl bg-white border border-slate-200 text-[11px] font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-800 hover:border-blue-300 transition text-center">
                    Staff
                </button>
                <button type="button" onclick="fillForm('customer@mail.com', 'password123')" class="p-2 rounded-xl bg-white border border-slate-200 text-[11px] font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 transition text-center">
                    Jack (Customer)
                </button>
            </div>
            <div class="text-[10px] text-slate-400 font-mono text-center pt-1">
                Sandi bawaan seluruh akun: <strong>password123</strong>
            </div>
        </div>
    </details>

    <script>
        function fillForm(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</x-guest-layout>

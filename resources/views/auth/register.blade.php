<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pendaftaran Pelanggan Baru</h1>
        <p class="text-xs text-slate-500 mt-1">
            Buat akun publik untuk mulai berbelanja perlengkapan ekspedisi outdoor di Apex Outfitter.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4 max-w-lg mx-auto">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   placeholder="contoh: Jack Kurniawan"
                   class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:border-emerald-600 focus:ring-emerald-600" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   placeholder="contoh: pelanggan@gmail.com"
                   class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:border-emerald-600 focus:ring-emerald-600" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi (Minimal 8 Karakter)</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   placeholder="••••••••"
                   class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:border-emerald-600 focus:ring-emerald-600" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   placeholder="••••••••"
                   class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:border-emerald-600 focus:ring-emerald-600" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-md shadow-emerald-800/20 transition">
                Daftar Akun Sekarang
            </button>
        </div>

        <div class="text-center pt-4 border-t border-slate-200">
            <a class="text-xs text-slate-600 hover:text-emerald-700 font-medium underline" href="{{ route('login') }}">
                Sudah memiliki akun? Silakan Masuk di sini &rarr;
            </a>
        </div>
    </form>
</x-guest-layout>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Apex Outfitter') }} — Autentikasi Pengguna</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-100 min-h-screen py-10 px-4 flex flex-col justify-center items-center">
        <div class="w-full max-w-md flex flex-col items-center">
            <!-- Brand Logo -->
            <div class="mb-6 text-center">
                <a href="/" class="flex flex-col items-center space-y-2 group">
                    <x-application-logo class="w-14 h-14 shadow-lg shadow-emerald-950/20 rounded-2xl group-hover:scale-105 transition-transform" />
                    <div>
                        <span class="text-2xl font-black text-slate-900 tracking-tight">Apex<span class="text-emerald-700">Outfitter</span></span>
                        <span class="block text-[11px] font-semibold text-slate-500 uppercase tracking-widest mt-0.5">Outdoor Gear & Expedition</span>
                    </div>
                </a>
            </div>

            <!-- Content Card -->
            <div class="w-full bg-white shadow-xl shadow-slate-200/80 border border-slate-200/90 rounded-3xl p-6 sm:p-8">
                {{ $slot }}
            </div>

            <!-- Footer note -->
            <div class="mt-6 text-center text-xs text-slate-500">
                <p>Apex Outfitter &copy; {{ date('Y') }}</p>
                <div class="mt-1 flex items-center justify-center space-x-3">
                    <a href="{{ route('shop.index') }}" class="text-emerald-700 hover:text-emerald-800 font-semibold underline">Portal Toko Online</a>
                    <span class="text-slate-300">&bull;</span>
                    <a href="{{ route('panduan') }}" target="_blank" class="text-slate-500 hover:text-slate-800 font-medium">Panduan Pengujian</a>
                </div>
            </div>
        </div>
    </body>
</html>

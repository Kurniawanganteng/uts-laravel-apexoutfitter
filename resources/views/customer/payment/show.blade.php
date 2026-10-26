@extends('layouts.customer')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    
    <!-- Payment Gateway Header Bar -->
    <div class="bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 text-white rounded-3xl p-6 shadow-xl border border-slate-800 flex items-center justify-between">
        <div class="flex items-center space-x-3.5">
            <x-application-logo class="w-11 h-11 rounded-2xl shadow-md shadow-emerald-900/40" />
            <div>
                <h1 class="text-lg font-black tracking-tight">Apex<span class="text-emerald-400">Pay</span> Gateway</h1>
                <p class="text-xs text-emerald-400 font-semibold flex items-center space-x-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    <span>Secure 256-Bit SSL Digital Payment</span>
                </p>
            </div>
        </div>
        <div class="text-right">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                Menunggu Pembayaran
            </span>
        </div>
    </div>

    <!-- Main Payment Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        
        <!-- Order & Total Information -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-4">
            <div>
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Nomor Referensi Pesanan</span>
                <div class="text-base font-extrabold font-mono text-slate-900">{{ $order->order_number }}</div>
                <div class="text-xs text-slate-500 mt-0.5">Penerima: {{ $order->customer_name }} &bull; {{ $order->customer_phone }}</div>
            </div>
            <div class="sm:text-right">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Tagihan</span>
                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-800 font-mono">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </div>
            </div>
        </div>

        @php
            $payment = $order->payment;
        @endphp

        <!-- Countdown Notice -->
        <div class="bg-amber-50 rounded-2xl p-4 border border-amber-200 flex items-center justify-between text-xs text-amber-950">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-amber-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Batas Waktu Penyelesaian Pembayaran:</span>
            </div>
            <span class="font-mono font-bold text-amber-800 bg-white px-2.5 py-0.5 rounded border border-amber-300" id="timer">14:59</span>
        </div>

        <!-- PAYMENT METHOD 1: QRIS -->
        @if($payment->payment_method === 'qris')
        <div class="space-y-4 text-center">
            
            <!-- Real QRIS Card Frame -->
            <div class="max-w-xs mx-auto bg-white border-2 border-slate-900 rounded-3xl shadow-xl overflow-hidden">
                <!-- QRIS Official Header Banner -->
                <div class="bg-red-700 text-white p-3 flex items-center justify-between border-b-2 border-slate-900">
                    <div class="flex items-center space-x-1 font-black text-sm tracking-wider">
                        <span>QRIS</span>
                    </div>
                    <div class="text-[10px] font-bold tracking-widest uppercase bg-white/20 px-2 py-0.5 rounded text-white">
                        GPN NASIONAL
                    </div>
                </div>

                <!-- Merchant Details -->
                <div class="py-2.5 px-4 bg-slate-50 border-b border-slate-200 text-center">
                    <div class="font-black text-xs text-slate-900 tracking-wide">APEX OUTFITTER INDONESIA</div>
                    <div class="text-[10px] font-mono text-slate-500">NMID: ID1020268891024 &bull; Terminal: A01</div>
                </div>

                <!-- High Fidelity Vector QR Matrix -->
                <div class="p-5 flex flex-col items-center justify-center bg-white">
                    <div class="p-3 bg-white border border-slate-200 rounded-2xl shadow-inner relative">
                        <svg class="w-52 h-52 text-slate-900" viewBox="0 0 100 100" fill="currentColor">
                            <!-- Outer Finder Top-Left -->
                            <rect x="4" y="4" width="28" height="28" rx="2" fill="currentColor"/>
                            <rect x="8" y="8" width="20" height="20" fill="white"/>
                            <rect x="12" y="12" width="12" height="12" rx="1" fill="currentColor"/>
                            
                            <!-- Outer Finder Top-Right -->
                            <rect x="68" y="4" width="28" height="28" rx="2" fill="currentColor"/>
                            <rect x="72" y="8" width="20" height="20" fill="white"/>
                            <rect x="76" y="12" width="12" height="12" rx="1" fill="currentColor"/>
                            
                            <!-- Outer Finder Bottom-Left -->
                            <rect x="4" y="68" width="28" height="28" rx="2" fill="currentColor"/>
                            <rect x="8" y="72" width="20" height="20" fill="white"/>
                            <rect x="12" y="76" width="12" height="12" rx="1" fill="currentColor"/>

                            <!-- Timing Patterns & Alignment Squares -->
                            <rect x="36" y="6" width="4" height="4"/>
                            <rect x="44" y="6" width="4" height="4"/>
                            <rect x="52" y="6" width="4" height="4"/>
                            <rect x="60" y="6" width="4" height="4"/>
                            <rect x="6" y="36" width="4" height="4"/>
                            <rect x="6" y="44" width="4" height="4"/>
                            <rect x="6" y="52" width="4" height="4"/>
                            <rect x="6" y="60" width="4" height="4"/>

                            <!-- Dense Real QR Data Modules -->
                            <rect x="36" y="14" width="6" height="4"/>
                            <rect x="46" y="14" width="4" height="6"/>
                            <rect x="54" y="14" width="8" height="4"/>
                            <rect x="36" y="22" width="4" height="8"/>
                            <rect x="44" y="22" width="8" height="4"/>
                            <rect x="56" y="22" width="4" height="8"/>
                            
                            <rect x="16" y="36" width="6" height="6"/>
                            <rect x="26" y="36" width="4" height="6"/>
                            <rect x="16" y="46" width="4" height="6"/>
                            <rect x="24" y="46" width="6" height="4"/>
                            <rect x="16" y="56" width="6" height="4"/>
                            <rect x="26" y="56" width="4" height="6"/>

                            <!-- Center Apex Outfitter Badge -->
                            <rect x="36" y="36" width="28" height="28" rx="4" fill="#065f46"/>
                            <path d="M50 42L40 58H60L50 42Z" fill="white"/>
                            <path d="M50 42L50 58H60L50 42Z" fill="#a7f3d0"/>

                            <!-- Lower Data Matrices -->
                            <rect x="68" y="36" width="8" height="4"/>
                            <rect x="80" y="36" width="4" height="6"/>
                            <rect x="88" y="36" width="6" height="4"/>
                            <rect x="68" y="44" width="6" height="6"/>
                            <rect x="78" y="44" width="8" height="4"/>
                            <rect x="90" y="44" width="4" height="8"/>
                            <rect x="68" y="54" width="4" height="6"/>
                            <rect x="76" y="54" width="6" height="6"/>
                            <rect x="86" y="54" width="8" height="4"/>

                            <rect x="36" y="68" width="8" height="4"/>
                            <rect x="48" y="68" width="6" height="6"/>
                            <rect x="58" y="68" width="4" height="6"/>
                            <rect x="66" y="68" width="8" height="4"/>
                            <rect x="78" y="68" width="4" height="8"/>
                            <rect x="86" y="68" width="8" height="4"/>

                            <rect x="36" y="78" width="6" height="6"/>
                            <rect x="46" y="78" width="4" height="6"/>
                            <rect x="54" y="78" width="8" height="4"/>
                            <rect x="66" y="78" width="6" height="4"/>
                            <rect x="76" y="78" width="6" height="6"/>
                            <rect x="86" y="78" width="4" height="6"/>

                            <rect x="36" y="88" width="4" height="6"/>
                            <rect x="44" y="88" width="8" height="4"/>
                            <rect x="56" y="88" width="6" height="6"/>
                            <rect x="66" y="88" width="8" height="4"/>
                            <rect x="78" y="88" width="6" height="4"/>
                            <rect x="88" y="88" width="6" height="6"/>
                        </svg>
                    </div>
                </div>

                <!-- Footer Amount Display -->
                <div class="p-3 bg-slate-50 border-t border-slate-200">
                    <div class="text-[10px] text-slate-500 font-medium">Nominal Pembayaran Resmi:</div>
                    <div class="text-base font-black text-slate-900 font-mono">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                Buka aplikasi BCA Mobile, Livin' Mandiri, BRImo, BNI Mobile, GoPay, OVO, atau DANA, pilih menu <strong>Scan QRIS</strong>, lalu arahkan kamera ke kode di atas.
            </p>
        </div>

        <!-- PAYMENT METHOD 2: VIRTUAL ACCOUNT -->
        @elseif($payment->payment_method === 'bank_transfer')
        <div class="space-y-4">
            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-950 space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-800">Virtual Account: Bank {{ strtoupper($payment->payment_channel) }}</span>
                <p class="text-xs text-blue-700">Lakukan pembayaran via ATM, Mobile Banking, atau Internet Banking ke nomor rekening virtual berikut:</p>
            </div>

            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Nomor Virtual Account</span>
                        <div class="text-xl sm:text-2xl font-mono font-black text-slate-900 tracking-wider" id="vaText">
                            {{ $payment->va_number }}
                        </div>
                    </div>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $payment->va_number }}'); alert('Nomor Virtual Account berhasil disalin!')" class="px-3.5 py-2 rounded-xl bg-white border border-slate-300 hover:border-slate-400 text-xs font-bold text-slate-800 shadow-sm transition">
                        Salin Nomor VA
                    </button>
                </div>
                <div class="text-xs text-slate-500 pt-2 border-t border-slate-200">
                    Atas Nama: <strong>APEX OUTFITTER - {{ $order->customer_name }}</strong>
                </div>
            </div>

            <!-- Petunjuk Pembayaran VA Accordion -->
            <div class="border border-slate-200 rounded-2xl divide-y divide-slate-100 text-xs">
                <div class="p-3.5 bg-slate-50 font-bold text-slate-800">
                    Panduan Pembayaran m-Banking:
                </div>
                <div class="p-3.5 text-slate-600 space-y-1">
                    <p>1. Buka aplikasi m-Banking Anda dan login.</p>
                    <p>2. Pilih menu <strong>Transfer</strong> &gt; <strong>Virtual Account</strong>.</p>
                    <p>3. Masukkan nomor VA: <span class="font-mono font-bold">{{ $payment->va_number }}</span>.</p>
                    <p>4. Pastikan nama merchant adalah <strong>Apex Outfitter</strong> dan nominal sesuai.</p>
                    <p>5. Masukkan PIN transaksi Anda untuk menyelesaikan.</p>
                </div>
            </div>
        </div>

        <!-- PAYMENT METHOD 3: E-WALLET -->
        @else
        <div class="space-y-4">
            <div class="p-4 rounded-2xl bg-purple-50 border border-purple-200 text-purple-950">
                <span class="text-xs font-bold uppercase tracking-wider text-purple-800">E-Wallet Channel: {{ strtoupper($payment->payment_channel) }}</span>
                <p class="text-xs text-purple-700 mt-1">Konfirmasi pembayaran melalui akun dompet digital yang tertaut:</p>
            </div>

            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Kode Referensi Pembayaran</span>
                    <div class="text-xl font-mono font-bold text-slate-900">{{ $payment->va_number }}</div>
                </div>
                <button type="button" onclick="navigator.clipboard.writeText('{{ $payment->va_number }}'); alert('Kode referensi berhasil disalin!')" class="px-3.5 py-2 rounded-xl bg-white border border-slate-300 text-xs font-bold text-slate-800 shadow-sm">
                    Salin Kode
                </button>
            </div>
        </div>
        @endif

        <!-- SIMULATOR CONTROLS (TESTING / UTS DEMO) -->
        <div class="pt-6 border-t border-slate-200 space-y-4">
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-800 text-white flex items-center justify-center flex-shrink-0 font-bold text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Verifikasi & Konfirmasi Pelunasan (Simulator Webhook)</h4>
                        <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">
                            Simulasi pembayaran instan untuk pengujian sistem UTS. Server akan mengunci stok (<em>lockForUpdate</em>), memotong kuantitas di gudang inventaris secara atomik, dan menerbitkan struk lunas resmi.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <!-- Simulate Pay Button -->
                <form action="{{ route('customer.payment.simulate', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-lg shadow-emerald-900/30 transition text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <span>Konfirmasi Pembayaran Sukses (Simulasi)</span>
                    </button>
                </form>

                <!-- Restriction Notice: Cancellation only by Owner -->
                @can('admin-only')
                <!-- Cancel Order Button (Owner / Administrator Only) -->
                <form action="{{ route('customer.payment.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Konfirmasi Owner: Batalkan pesanan ini?')" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-700 font-bold py-2.5 px-4 rounded-xl border border-red-200 transition text-xs">
                        Batalkan Transaksi Pesanan (Hak Akses Owner)
                    </button>
                </form>
                @else
                <div class="text-center text-[11px] text-slate-400 pt-1">
                    Catatan: Pembatalan transaksi hanya dapat diproses melalui konfirmasi Owner / Administrator Toko.
                </div>
                @endcan
            </div>

            <div class="text-center pt-2">
                <a href="{{ route('customer.orders.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
                    &larr; Bayar nanti, kembali ke Riwayat Pesanan
                </a>
            </div>
        </div>

    </div>
</div>

<script>
    // Live Countdown Timer Demo
    let duration = 14 * 60 + 59;
    const timerDisplay = document.getElementById('timer');
    if (timerDisplay) {
        setInterval(() => {
            if (duration <= 0) return;
            duration--;
            const mins = Math.floor(duration / 60);
            const secs = duration % 60;
            timerDisplay.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }, 1000);
    }
</script>
@endsection

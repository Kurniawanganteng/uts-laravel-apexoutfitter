@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Pengguna</h1>
        <p class="text-xs text-slate-500 mt-0.5">Kelola akun hak akses sistem: Owner / Admin, Staff Toko, dan Customer.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm h-fit">
        <h2 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Tambah Pengguna Baru</h2>
        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="contoh: Budi Setiawan" class="w-full border border-slate-300 p-2.5 rounded-xl text-xs outline-none focus:border-emerald-600" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="budi@apexoutfitter.com" class="w-full border border-slate-300 p-2.5 rounded-xl text-xs outline-none focus:border-emerald-600" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi (Minimal 6 Karakter)</label>
                <input type="password" name="password" placeholder="••••••••" class="w-full border border-slate-300 p-2.5 rounded-xl text-xs outline-none focus:border-emerald-600" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Role Hak Akses</label>
                <select name="role" class="w-full border border-slate-300 p-2.5 rounded-xl text-xs outline-none bg-white focus:border-emerald-600" required>
                    <option value="staff">Staff (Kasir & Gudang)</option>
                    <option value="admin">Owner / Administrator (Akses Penuh)</option>
                    <option value="customer">Customer (Pelanggan Belanja)</option>
                </select>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 px-4 rounded-xl font-bold text-xs w-full shadow-md shadow-emerald-700/20 transition">
                Simpan Akun Pengguna
            </button>
        </form>
    </div>

    <div class="md:col-span-2 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <h2 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Daftar Akun Pengguna Terdaftar</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-slate-600 font-bold">
                        <th class="p-3">Nama Lengkap</th>
                        <th class="p-3">Alamat Email</th>
                        <th class="p-3">Peran (Role)</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $u)
                    <tr class="hover:bg-slate-50/50">
                        <td class="p-3 font-bold text-slate-800">
                            {{ $u->name }}
                            @if($u->id === auth()->id())
                            <span class="ml-1 text-[10px] text-amber-700 bg-amber-100 font-semibold px-2 py-0.5 rounded-full">Akun Anda</span>
                            @endif
                        </td>
                        <td class="p-3 font-mono text-slate-600">{{ $u->email }}</td>
                        <td class="p-3">
                            @if($u->role === 'admin')
                            <span class="px-2.5 py-1 text-[10px] rounded-full font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                OWNER / ADMIN
                            </span>
                            @elseif($u->role === 'staff')
                            <span class="px-2.5 py-1 text-[10px] rounded-full font-bold bg-cyan-100 text-cyan-800 border border-cyan-200">
                                STAFF OPERASIONAL
                            </span>
                            @else
                            <span class="px-2.5 py-1 text-[10px] rounded-full font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                CUSTOMER
                            </span>
                            @endif
                        </td>
                        <td class="p-3 text-right">
                            @if($u->id !== auth()->id())
                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun {{ $u->name }}? Pastikan tidak ada transaksi yang terkait.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-bold hover:underline">
                                    Hapus
                                </button>
                            </form>
                            @else
                            <span class="text-slate-400 italic">Terkunci</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</div>
@endsection

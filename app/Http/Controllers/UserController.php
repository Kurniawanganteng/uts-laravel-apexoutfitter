<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,staff,customer',
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.unique'      => 'Email ini sudah terdaftar di sistem.',
            'password.required' => 'Password wajib diisi (minimal 6 karakter).',
            'role.required'     => 'Pilih role akses akun.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', "Pengguna {$request->name} ({$request->role}) berhasil ditambahkan!");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login!']);
        }

        if ($user->sales()->count() > 0 || $user->purchases()->count() > 0 || $user->orders()->count() > 0) {
            return back()->withErrors(['error' => 'Pengguna tidak dapat dihapus karena memiliki riwayat transaksi (penjualan, pembelian, atau pesanan) yang terikat.']);
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', "Akun pengguna '{$user->name}' berhasil dihapus.");
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request (Form Login Manual).
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        if ($user && $user->isCustomer()) {
            return redirect()->intended(route('shop.index'))->with('success', 'Selamat datang kembali, ' . $user->name . '! Silakan jelajahi perlengkapan outdoor kami.');
        }

        if ($user && $user->isStaff()) {
            return redirect()->intended(route('sales.create'))->with('success', 'Selamat bertugas, ' . $user->name . '! Terminal Kasir POS siap digunakan.');
        }

        return redirect()->intended(route('dashboard', absolute: false))->with('success', 'Selamat datang Owner ' . ($user->name ?? '') . '! Dashboard analitik & kontrol penuh aktif.');
    }

    /**
     * 1-Click Quick Demo Login (POST)
     */
    public function quickLogin(Request $request): RedirectResponse
    {
        $role = $request->input('role', 'customer');
        return $this->performQuickLogin($role);
    }

    /**
     * 1-Click Quick Demo Login (GET shortcut)
     */
    public function quickLoginGet(string $role): RedirectResponse
    {
        return $this->performQuickLogin($role);
    }

    /**
     * Perform quick login by target role
     */
    protected function performQuickLogin(string $role): RedirectResponse
    {
        $targetRole = match ($role) {
            'admin', 'owner' => 'admin',
            'staff'          => 'staff',
            default          => 'customer',
        };

        // Cari user sesuai role (utamakan Jack Kurniawan untuk customer)
        if ($targetRole === 'customer') {
            $user = User::where('role', 'customer')->where('name', 'like', '%Jack%')->first()
                 ?? User::where('email', 'customer@mail.com')->first()
                 ?? User::where('role', 'customer')->first();
        } elseif ($targetRole === 'admin') {
            $user = User::where('role', 'admin')->where('email', 'admin@mail.com')->first()
                 ?? User::where('role', 'admin')->first();
        } else {
            $user = User::where('role', 'staff')->where('email', 'staff@mail.com')->first()
                 ?? User::where('role', 'staff')->first();
        }

        if (!$user) {
            return back()->with('error', 'Akun demo untuk peran ' . strtoupper($role) . ' belum tersedia di database.');
        }

        Auth::login($user);
        request()->session()->regenerate();

        if ($user->isCustomer()) {
            return redirect()->route('shop.index')->with('success', 'Masuk sebagai Customer (' . $user->name . '). Selamat datang di portal belanja perlengkapan outdoor!');
        }

        if ($user->isStaff()) {
            return redirect()->route('sales.create')->with('success', 'Masuk sebagai Staff Toko (' . $user->name . '). Terminal Kasir POS siap digunakan.');
        }

        return redirect()->route('dashboard')->with('success', 'Masuk sebagai Owner / Administrator (' . $user->name . '). Seluruh kontrol manajerial aktif.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar dari akun.');
    }
}

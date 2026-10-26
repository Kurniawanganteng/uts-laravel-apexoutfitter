<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStaffOrAdmin
{
    /**
     * Handle an incoming request.
     * Ensure only Admin/Owner and Staff can access backoffice / internal routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        if ($user->isCustomer()) {
            return redirect()->route('shop.index')->with('error', 'Akses terbatas: Halaman inventaris & kasir hanya untuk Staff dan Owner toko.');
        }

        return $next($request);
    }
}

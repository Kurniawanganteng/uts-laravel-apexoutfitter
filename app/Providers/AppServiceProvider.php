<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::define('admin-only', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('staff-or-admin', function (User $user) {
            return in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('customer-only', function (User $user) {
            return $user->role === 'customer';
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Drošības filtrs skolotāju lapām
        Gate::define('access-teacher', function (User $user) {
            return $user->usertype === 'teacher';
        });

        // 2. Drošības filtrs admina lapām
        Gate::define('access-admin', function (User $user) {
            return $user->usertype === 'admin';
        });

        // 3. Drošības filtrs parastajiem lietotājiem
        Gate::define('access-user', function (User $user) {
            return $user->usertype === 'user';
        });
    }
}
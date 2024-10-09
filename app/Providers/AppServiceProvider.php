<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('admin', function(User $user) {
        return $user->id_role == '1';
        });
        Gate::define('walas', function(User $user) {
        return $user->id_role == '2';
        });
        Gate::define('guru_mapel', function(User $user) {
        return $user->id_role == '3';
        });
        Gate::define('km_kelas', function(User $user) {
        return $user->id_role == '4';
        });
        Gate::define('siswa', function(User $user) {
        return $user->id_role == '5';
        });
    }
}

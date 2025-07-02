<?php
// ARQUIVO: app/Providers/AuthServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\Gate; // Certifique-se que esta linha existe
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // ADICIONE ESTE CÓDIGO
        Gate::define('access-admin-area', function ($user) {
            return $user->role === 'administrador';
        });
    }
}

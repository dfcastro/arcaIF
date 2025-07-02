<?php
// ARQUIVO: app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon; // 1. IMPORTE A CLASSE CARBON

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
        // 2. ADICIONE ESTAS LINHAS
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        
        // Define o idioma para a biblioteca de datas
        Carbon::setLocale(config('app.locale'));
    }
}
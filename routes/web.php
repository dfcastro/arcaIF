<?php

use Illuminate\Support\Facades\Route;
 use App\Livewire\GerenciarAnimais;
 use App\Livewire\GerenciarEspecies;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

   

Route::get('/animais', GerenciarAnimais::class)->middleware('auth'); // Protegido por autenticação

Route::get('/especies', GerenciarEspecies::class)->middleware('auth');

require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\GerenciarAnimais;
use App\Livewire\GerenciarEspecies;
use App\Livewire\GerenciarRacas; // Adicione no topo do arquivo
use App\Livewire\Dashboard;
use App\Livewire\GerenciarLotes;
use App\Livewire\ShowLote; 
use App\Livewire\ShowAnimal;
use App\Livewire\Relatorios; 

Route::view('/', 'welcome');

Route::get('/dashboard', Dashboard::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');



Route::get('/animais', GerenciarAnimais::class)->middleware('auth'); // Protegido por autenticação

Route::get('/especies', GerenciarEspecies::class)->middleware('auth');

Route::get('/racas', GerenciarRacas::class)->middleware('auth');

Route::get('/lotes', GerenciarLotes::class)->middleware('auth');

Route::get('/lotes/{lote}', ShowLote::class)->middleware('auth')->name('lotes.show');

Route::get('/animais/{animal}', ShowAnimal::class)->middleware('auth')->name('animais.show');

Route::get('/relatorios', Relatorios::class)->middleware('auth')->name('relatorios');






require __DIR__ . '/auth.php';

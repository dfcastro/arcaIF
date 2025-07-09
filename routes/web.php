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
use App\Livewire\GerenciarUsuarios;
use App\Livewire\GerenciarLocalizacoes;

use App\Livewire\GerenciarIngredientes;
use App\Livewire\FormularRacao;
use App\Livewire\ListarFormulas;
use App\Livewire\GerenciarCategorias;
use App\Livewire\PrevisaoConsumo;
use App\Livewire\GerenciarProtocolos;
use App\Livewire\CalendarioSanitario;

Route::get('/', function () {
    return redirect()->route('login');
});

// LINHA NOVA


Route::get('/dashboard', Dashboard::class)->middleware(['auth'])->name('dashboard');

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

Route::get('/utilizadores', GerenciarUsuarios::class)->middleware(['auth', 'can:access-admin-area'])->name('utilizadores.index');

Route::get('/localizacoes', GerenciarLocalizacoes::class)->middleware('auth');

Route::get('/ingredientes', GerenciarIngredientes::class)->middleware('auth');

Route::get('/formulador', FormularRacao::class)->middleware('auth');

Route::get('/formulas', ListarFormulas::class)->middleware('auth');

Route::get('/categorias', GerenciarCategorias::class)->middleware('auth');

Route::get('/previsao-consumo', PrevisaoConsumo::class)->middleware('auth');

Route::get('/protocolos', GerenciarProtocolos::class)->middleware('auth');

Route::get('/agenda-sanitaria', CalendarioSanitario::class)->middleware('auth')->name('agenda.sanitaria');

require __DIR__ . '/auth.php';

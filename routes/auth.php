<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    // Rota de Registo - ADICIONE ESTA LINHA
    

    Volt::route('login', 'pages.auth.login')->name('login');
    Volt::route('forgot-password', 'pages.auth.forgot-password')->name('password.request');
    Volt::route('reset-password/{token}', 'pages.auth.reset-password')->name('password.reset');
});

Route::middleware('auth')->group(function () {
    //Volt::route('verify-email', 'pages.auth.verify-email')->name('verification.notice');
    Volt::route('confirm-password', 'pages.auth.confirm-password')->name('password.confirm');

    Route::get('logout', Logout::class)->name('logout');
});
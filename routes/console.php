<?php
// ARQUIVO: routes/console.php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


// Nossos comandos agendados:
Schedule::command('app:verificar-partos-proximos')->dailyAt('08:00');
Schedule::command('app:verificar-agenda-sanitaria')->dailyAt('08:05'); // ADICIONE ESTA LINHA
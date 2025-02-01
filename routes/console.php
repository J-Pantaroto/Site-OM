<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\Sincronizar;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('schedule:sincronizar', function () {
    dispatch(new Sincronizar());
})->purpose('Sincronizar produtos e status das vendas');

// Configuração do agendamento
app(Schedule::class)->command('schedule:sincronizar')->everyMinute();
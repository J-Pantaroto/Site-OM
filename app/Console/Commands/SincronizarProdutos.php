<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Sincronizar;

class SincronizarProdutos extends Command
{
    protected $signature = 'sincronizar:produtos';
    protected $description = 'Disparar o job de sincronização dos produtos, grupos e subgrupos.';

    public function handle(): void
    {
        $this->info('Disparando job de sincronização...');
        Sincronizar::dispatch();
        $this->info('Job de sincronização enviado para a fila.');
    }
}

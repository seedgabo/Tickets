<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class alertas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:Alertas';

    /**
     * The console command description.
     *
     * @var string
     * */
    protected $description = 'Verifica las alertas programadas y envia los correos y notificaciones';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $alertas = \App\Models\Alerta::poremitir()->get();
       
       foreach ($alertas as $alerta) {
            $alerta->emitir();
            $this->info($alerta->titulo . " emitida");
         
       }

    }
}

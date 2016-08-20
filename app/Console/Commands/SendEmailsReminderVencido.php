<?php

namespace App\Console\Commands;

use App\Funciones;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendEmailsReminderVencido extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendMailVencidos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia los correos a los colaboradores cuyo ticket se haya vencido';

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
       $vencidos = \App\Models\Tickets::
       where("vencimiento","<", Carbon::now())
       ->where(function($q){
         $q->where("mail_alert_vencido", "=","");
         $q->orWhereNull("mail_alert_vencido");
       })
       ->get();
       
       foreach ($vencidos as $ticket) {
            Funciones::sendMailTicketVencido($ticket);
            $ticket->mail_alert_vencido = true;
            $ticket->save();
            $this->info($ticket->titulo . " enviado correo de ticket vencido");
       }
    }
}

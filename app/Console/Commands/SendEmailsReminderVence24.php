<?php

namespace App\Console\Commands;

use App\Funciones;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendEmailsReminderVence24 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendMailVence24';

    /**
     * The console command description.
     *
     * @var string
     * */
    protected $description = 'Envia un correo al usuario y al guardian de que el ticket vence en 24 horas o menos';

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
       where("vencimiento","<", Carbon::now()->addHours(24))
       ->where(function($q){
         $q->where("mail_alert_24", "=","");
         $q->orWhereNull("mail_alert_24");
       })
       ->get();
       
       foreach ($vencidos as $ticket) {
            Funciones::sendMailTicketVence24($ticket);
            $ticket->mail_alert_24 = true;
            $ticket->save();
            $this->info($ticket->titulo . " enviado correo de ticket por vencer en 24 horas");
       }
    }
}

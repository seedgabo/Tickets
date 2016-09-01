<?php

namespace App\Providers;

use App\Models\Auditorias;
use App\Models\ComentariosTickets;
use App\Models\Tickets;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Tickets::created(function ($ticket) {
            Auditorias::create(['tipo' => 'Creación', 'user_id' =>$ticket->user_id, 'ticket_id' => $ticket->id ]);
        });
      
        Tickets::deleting(function ($ticket) {
            Auditorias::create(['tipo' => 'Eliminación', 'user_id' =>$ticket->user_id, 'ticket_id' => $ticket->id ]);
        });

        ComentariosTickets::created(function($comentario){
            Auditorias::create(['tipo' => 'Seguimiento', 'user_id' =>$comentario->user_id, 'ticket_id' => $comentario->ticket->id ]);
        });

        ComentariosTickets::deleting(function($comentario){
            Auditorias::create(['tipo' => 'Seguimiento Eliminado', 'user_id' =>$comentario->user_id, 'ticket_id' => $comentario->ticket->id ]);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

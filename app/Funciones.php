<?php
namespace App;

use App\Dbf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Tests\HttpCache\request;

class Funciones
{


    public static function  transdate( $date, $formato ='l j \d\e F \d\e Y h:i:s A' , $diferencia = false)
    {
        if(gettype($date) == "NULL" )
            return "";
        if (gettype($date) == "string" )
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $date );
        $cadena = $date->format($formato);
        $recibido = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Mon','Tue','Wed','Thu','Fri','Sat','Sun','January','February','March','April','May','June','July','August','September','October','November','December','second','seconds','minute','minutes','day','days','hour','hours','month','months','year','years','week','weeks','before','after',"of");
        $traducido = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Lun','Mar','Mie','Jue','Vie','Sab','Dom','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre','Segundo','Segundos','Minuto','Minutos','Dia','Dias','Hora','Horas','Mes','Meses','Año','Años','Semana','Semanas','Antes','Despues',"de");
        $texto = str_replace($recibido,$traducido,$cadena);
        if (ends_with($texto,"Antes"))
        {
         $texto = "Dentro de " .str_replace("Antes","",$texto);
     }
     if (ends_with($texto,"Despues"))
     {
         $texto = "Hace " .str_replace("Despues","",$texto);
     }

     if($diferencia == true)
     {
         $texto = str_replace(["Dentro de ", "Hace "],"",$texto);
     }

     return $texto;
     }


     public static function  getEmpresa()
     {
        $empresa = Empresas::find(Session::get('empresa'));
        return $empresa;
    }


 
    public static function sendMailUser($user)
    {
        Mail::send('emails.NewUser', ['user' => $user], function ($m) use ($user)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to($user->email);
            $m->subject('¡Usuario Creado con Exito!');
        });
    }

    public static function sendMailNewTicket($ticket, $user, $guardian)
    {
        $usuarios = \App\Models\CategoriasTickets::find($ticket->categoria_id)->Users();

        Mail::send('emails.NewTicket', ['user' => $user,'guardian' => $guardian ,'ticket' => $ticket], function ($m)
            use ($user, $guardian)
            {
                $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
                $m->to($guardian->email)->subject('¡Nuevo Ticket Asignado!');
            });

        foreach ($usuarios as $usuario) {
            Mail::send('emails.NewTicketGeneral', ['user' => $usuario,'guardian' => $guardian ,'ticket' => $ticket, "creador" => $user], function ($m)   use ($usuario, $guardian)
            {
                $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
                $m->to($usuario->email, $usuario->nombre)->subject('¡Nuevo Ticket!');
            });
        }
    }

    public static function sendMailNewComentario($users, $comentario)
    {
        $usuarios = \App\User::whereIn('id',$users)->lists("nombre","email")->toArray();

        Mail::send('emails.NewComentario', ["comentario" =>  $comentario], function ($m)   use ($usuarios)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to($usuarios)->subject('¡Nuevo Comentario!');
        });
    }

    public static function sendMailNewGuardian($guardian, $user, $ticket)
    {

        Mail::send('emails.NewGuardian', ["guardian" =>  $guardian,"ticket"=>$ticket,"user" => $user], function ($m)   use ($guardian)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to($guardian->email, $guardian->nombre)->subject('Asignación de guardian');
        });
    }

    public static function sendMailPasswordChanged ($user)
    {
        Mail::send('emails.changedPassword', ["user" => $user], function ($m)   use ($user)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to($user->email, $user->nombre)->subject('Cambio de Contraseña detectado');
        });
    }

    public static function sendMailCambioEstado($guardian, $user,$ticket)
    {
        Mail::send('emails.nuevoEstado', ["guardian" =>  $guardian,"ticket"=>$ticket,"user" => $user], function ($m)   use ($user,$guardian)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to([$user->email, $guardian->email])->subject('Caso ha Cambiado de estado');
        });
    }

    // Programados
    public static function sendMailTicketVencido($ticket)
    {
        $user = $ticket->user;
        $guardian = $ticket->guardian;
        Mail::send('emails.ticket-vencido', ["user" => $user, "ticket" => $ticket, 'guardian' => $guardian], function ($m)   use ($user,$guardian)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to([$user->email, $guardian->email])->subject('Caso Vencido');
        });
    }

    public static function sendMailTicketVence3($ticket)
    {
        $user = $ticket->user;
        $guardian = $ticket->guardian;
        Mail::send('emails.ticket-vence3', ["user" => $user, "ticket" => $ticket, 'guardian' => $guardian], function ($m)   use ($user,$guardian)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to([$user->email, $guardian->email])->subject('Caso Por Vencer');
        });
    }

    public static function sendMailTicketVence24($ticket)
    {
        $user = $ticket->user;
        $guardian = $ticket->guardian;
        Mail::send('emails.ticket-vence24', ["user" => $user, "ticket" => $ticket, 'guardian' => $guardian], function ($m)   use ($user,$guardian)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to([$user->email, $guardian->email])->subject('Caso por Vencer en 24 horas');
        });
    }


    public static function sendMailUpdateVencimiento($user,$guardian, $ticket)
    {
        Mail::send('emails.changeVencimiento', ["guardian" =>  $guardian,"ticket"=>$ticket,"user" => $user], function ($m)   use ($guardian)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to($guardian->email, $guardian->nombre)->subject('Nueva Fecha de Vencimiento');
        });   
    }
    public static function UpdateVencimiento($user,$guardian, $ticket)
    {
        return;
        return;
    }
}

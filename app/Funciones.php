<?php
namespace App;

use App\Dbf;
use App\Models\Dispositivo;
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
        Dispositivo::sendPush("Nuevo Caso Creado","Se ha creado un nuevo caso en la categoría " .$ticket->categoria->nombre, [$user->id,$guardian->id]);
    }

    public static function sendMailNewComentario($users, $comentario)
    {
        $usuarios = \App\User::whereIn('id',$users)->lists("nombre","email")->toArray();

        Mail::send('emails.NewComentario', ["comentario" =>  $comentario], function ($m)   use ($usuarios)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to($usuarios)->subject('¡Nuevo Comentario!');
        });
        $usuarios = \App\User::whereIn('id',$users)->pluck('id');
        $ticket = $comentario->ticket;
        Dispositivo::sendPush("Nuevo Seguimiento","Se ha creado un nuevo comentario en el caso " .$ticket->titulo, $usuarios);
    }

    public static function sendMailNewGuardian($guardian, $user, $ticket)
    {

        Mail::send('emails.NewGuardian', ["guardian" =>  $guardian,"ticket"=>$ticket,"user" => $user], function ($m)   use ($guardian)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to($guardian->email, $guardian->nombre)->subject('Asignación de guardian');
        });
        Dispositivo::sendPush("Nuevo Guardian","Se le ha asignado el caso " .$ticket->titulo, [$user->id,$guardian->id]);
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

        Dispositivo::sendPush("Cambio de estado","El caso " .$ticket->titulo. " ha cambiado de estado a " .$ticket->estado, [$user->id,$guardian->id]);
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

        Dispositivo::sendPush("Caso Vencido","Ha vencido el plazo para el caso de  " .$ticket->titulo , [$user->id,$guardian->id]);
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

        Dispositivo::sendPush("Caso Por Vencer","Atención! El caso esta por vencer: " .$ticket->titulo, [$user->id,$guardian->id]);
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

        Dispositivo::sendPush("Recordatorio de Caso","El caso " .$ticket->titulo . " vencerá en menos de 24 horas", [$user->id,$guardian->id]);
    }


    public static function sendMailUpdateVencimiento($user,$guardian, $ticket)
    {
        Mail::send('emails.changeVencimiento', ["guardian" =>  $guardian,"ticket"=>$ticket,"user" => $user], function ($m)   use ($guardian)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to($guardian->email, $guardian->nombre)->subject('Nueva Fecha de Vencimiento');
        });   

        Dispositivo::sendPush("Actualización de caso","Ha cambiado la fecha de plazo para el caso  " .$ticket->titulo, [$user->id,$guardian->id]);
    }


    public static function sendMailContenidoActualizado($ticket,$user,$guardian)
    {
        Mail::send('emails.updateContenido', ["guardian" =>  $guardian,"ticket"=>$ticket,"user" => $user], function ($m)   use ($guardian)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to($guardian->email, $guardian->nombre)->subject('Contenido actualizado');
        });   
        Dispositivo::sendPush("Actualización de caso","El contenido del caso se actualizó: " .$ticket->titulo, [$user->id,$guardian->id]);
    }


    public static function UpdateVencimiento($user,$guardian, $ticket)
    {
        return;
    }
}

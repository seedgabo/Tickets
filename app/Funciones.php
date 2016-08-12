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

    public static function  utf8_encode_deep(&$input)
    {
        if (is_string($input)) {
            $input = utf8_encode($input);
        } else if (is_array($input)) {
            foreach ($input as &$value) {
                Funciones::utf8_encode_deep($value);
            }

            unset($value);
        } else if (is_object($input)) {
            $vars = array_keys(get_object_vars($input));

            foreach ($vars as $var) {
                Funciones::utf8_encode_deep($input->$var);
            }
        }
    }

    /**
     * Traduce una fecha a español según el formato dado
     * @param  Carbon  $date       Fecha a traducir
     * @param  string  $formato    Formato dado, por default fecha larga
     * @param  boolean $diferencia Si es True se aplican traducciones a Before y After como Hace o  Dentro de
     * @return String              Fecha traducida
     */
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

    public static function  printable($array)
    {
     if (count($array) > 0)
     {
         $tabla =   "<table>
         <thead>
            <tr>
              <th>" . implode('</th><th>', array_keys(current($array))) . "</th></tr></thead> <tbody>";
              foreach ($array as $row):
                array_map('htmlentities', $row);
            $tabla.="  <tr><td>" . implode('</td><td>', $row) ."</td> </tr>";
            endforeach;
            $tabla.="</tbody></table>";
        }
        return $tabla;
    }


    public static function  getEmpresa()
    {
        $empresa = Empresas::find(Session::get('empresa'));
        return $empresa;
    }

    public static function  getCliente()
    {
       $cliente =  \App\Cliente::where("COD_TER", Session::get('cliente'))->first();
       return $cliente;
    }

    public static function  borrarCarrito()
    {
        Carritos::where('user_id',Auth::user()->id)
        ->where('COD_CLI',Session::get('cliente'))
        ->delete();
    }

    public static function procesarCarrito($productos)
    {
        $empresa =  Funciones::getEmpresa();
        $num_ped = $empresa->num_ped;

        foreach ($productos as $producto) {
            $producto->num_ped = $num_ped;
            $producto->estado  =  1;
            $producto->save();
        }
        $empresa->num_ped++;
        $empresa->save();
        return true;
    }

    public static function sendMailProccessCarrito($productos, $emails,$user ,$empresa,$cliente)
    {
        Mail::send('emails.CarritoProcesado', ['user' => $user,'cliente' => $cliente ,'empresa' => $empresa, 'email' => $emails,'productos' => $productos], function ($m) use ($user, $empresa , $emails)
        {
            $m->from('SistemaSeguimiento@duflosa.com', "Sistema de Seguimiento");
            $m->to($emails)->subject('¡Tu compra ha sido procesada!');
        });
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

    /**
     * Verifica si un producto contiene la imagen correspodiente a su COD_REF de no tenerlo devuelve una imagen general de no disponible
     * @param  Collection $producto Producto a buscar Imagen
     * @return String   Url con la dirección de la imagen
     */
    public static function getUrlProducto($producto)
    {
        if(File::exists(public_path().'/img/'. $producto->empresa_id .'/productos/' . trim($producto->COD_REF) .".jpg"))
            return $url = asset('img/'. $producto->empresa_id .'/productos/' . trim($producto->COD_REF) .".jpg");

        if(File::exists(public_path().'/img/'. $producto->empresa_id .'/productos/' . trim($producto->COD_REF) .".png"))
            return $url = asset('img/'. $producto->empresa_id .'/productos/' . trim($producto->COD_REF) .".png");

        else
            return $url = asset("img/nodisponible.jpg");
    }


    /**
     * Obtiene la url hacia la imagen de perfil del usuario seleccionado
     * @param  USer $user Usuario a buscar la imagen
     * @return String       Url con ladirección de la imagen, de no tener se devolvera un valor default
     */
    public static function getUrlProfile( $user = null)
    {
        if ($user == null)
        {
            $user = Auth::user();
        }
        $files =glob(public_path().'/img/users/'. Auth::user()->id . "*");
        if($files)
            return $url = asset('img/users/'. Auth::user()->id. "." . pathinfo($files[0], PATHINFO_EXTENSION));
        else
            return $url = asset('/img/user.jpg');
    }


    /**
     * Obtener el path para las referencias de productos a traves de la variable de sessión Empresas
     * @return String Path con la dirección a la base de datos de la empresa
     */
    public static function getPathRef()
    {
        $empresa =Empresas::find(Session::get('empresa'));
        return $empresa->direccion_base_de_datos . "/IN.SIA/REFEREN.DBF";
    }

    /**
     * Obtiene el path hace la base de datos de pedidos
     * @return String Path haca la base de datos de pedidos
     */
    public static function getPathPed()
    {
        $empresa =Empresas::find(Session::get('empresa'));
        return $empresa->direccion_base_de_datos . "/IN.SIA/PEDIDOS.DBF";
    }

    /**
     * Obtiene el path hace la base de datos de Clientes
     * @return String Path haca la base de datos de Clientes
     */
    public static function getPathCli()
    {
        $empresa =Empresas::find(Session::get('empresa'));
        return $empresa->direccion_tabla_clientes . "/MAE_TER.DBF";
    }

    /**
     * Obtiene el path hacia la direccion de base de datos de Cartera
     * @return String Path hacia la direccion a base de datos Cartera
     */
    public static function getPathCar()
    {
        $empresa =Empresas::find(Session::get('empresa'));
        return $empresa->direccion_base_de_datos . "/IN.SIA/CARTERA.DBF";
    }
}

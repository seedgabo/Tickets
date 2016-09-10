<?php

namespace App\Http\Controllers;

use App\Carritos;
use App\Dbf;
use App\Funciones;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\CategoriasTickets;
use App\Models\Tickets;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;

class AjaxController extends Controller
{

    public function setEstadoTicket(Request $request, $id)
    {
        $ticket = Tickets::find($id);
        $ticket->estado = $request->input('estado');
        $ticket->save();

        \App\Models\Auditorias::create(['tipo' => 'cambio de estado', 'user_id' => Auth::user()->id, 'ticket_id' => $ticket->id]);

        \App\Funciones::sendMailCambioEstado($ticket->guardian, $ticket->user, $ticket);
        
        return $ticket->estado;
    }

    public function setGuardianTicket(Request $request, $id)
    {
        $ticket = Tickets::find($id);
        $ticket->guardian_id = $request->input('guardian_id');
        $ticket->save();

        \App\Models\Auditorias::create(['tipo' => 'cambio de responsable', 'user_id' => Auth::user()->id, 'ticket_id' => $ticket->id]);

        \App\Funciones::sendMailNewGuardian(User::find($request->input('guardian_id')),User::find(Auth::user()->id),$ticket);
        return $ticket;
    }

    public function setVencimientoTicket(Request $request, $id)
    {
        $ticket = Tickets::find($id);
        $ticket->vencimiento = $request->input('vencimiento');
        $ticket->save();

        \App\Models\Auditorias::create(['tipo' => 'cambio de fecha de vencimiento', 'user_id' => Auth::user()->id, 'ticket_id' => $ticket->id]);

        \App\Funciones::sendMailUpdateVencimiento($ticket->guardian, $ticket->user,$ticket);
        
        \App\Funciones::UpdateVencimiento($ticket->guardian, $ticket->user,$ticket);        
        return $ticket;
    }

    public function setInvitadosTickets(Request $request, $id)
    {
        $ticket = \App\Models\Tickets::find($id);
        $ticket->invitados_id = $request->input('invitados_id');
        $ticket->save();

        \App\Models\Auditorias::create(['tipo' => 'cambio de invitados', 'user_id' => Auth::user()->id, 'ticket_id' => $ticket->id]);

        \App\Funciones::sendMailInvitados($ticket);
        Flash::success('Enviado correo de colaboración a invitados');
        return back();
    }

    public function addComentarioTicket(Request $request)
    {
        if($request->hasFile('archivo') && $request->get("encriptado") == "true" && !$request->has("clave"))
        {
            Flash::error("Debe Ingresar Una contraseña para encriptar el archivo");
            return back();
        }

        $comentario = \App\Models\ComentariosTickets::create($request->input('comentario'));
        Flash::success("Comentario Agregado exitosamente");
        if($request->hasFile('archivo'))
        {
            $nombre =  $request->file("archivo")->getClientOriginalName();

            // Si Se pidio Encriptar El Archivo
            if($request->get("encriptado") == "true")
            {                   
                $comentario->encriptado = true;
                $comentario->archivo = $nombre;
                $comentario->clave = $request->get("clave");              

                $encriptado = Crypt::encrypt(file_get_contents($request->file("archivo")));
                Storage::put("ComentariosTickets/". $comentario->id . "/" . $nombre , $encriptado);

                Flash::success('Archivo Encriptado');
            }
            else
            {
                $request->file('archivo')->move(public_path("archivos/ComentariosTickets/"), $nombre );
                $comentario->archivo =  $nombre;
            }
            $comentario->save();
        }
        if($request->exists('notificacion'))
        {
            \App\Funciones::sendMailNewComentario($request->input('emails'), $comentario);
        }

        if($request->ajax())
            return $comentario;
        else
            return  back();
    }

    public function deleteComentarioTicket(Request $request,$id)
    {
        $comentario = \App\Models\ComentariosTickets::find($id);
        if($comentario->user_id != Auth::user()->id)
        {
            abort(503);
        }
        $comentario->delete();
        return back();
    }

    public function getUsersbyCategoria(Request $request)
    {
        $categoria =  Input::get("depdrop_all_params")["categoria"];
        $users = User::where("categorias_id", "LIKE", '%"'. $categoria. '"%')->select("nombre as name","id")->get();
        return json_encode(["output" => $users, "selected" => ""]);
    }

    public function email()
    {
        if (Input::has('to'))
        {
            $validator = Validator::make(Input::all(),
            array('contenido'=> 'required|min:8',
            'title'=> 'required|min:3|max:50',
            'to'=> 'required',
            'file' => 'max:10240|mimes:jpeg,bmp,png,doc,docx,xls,xlsx,pdf,jpg,gif,sql,txt,ppt,pptx'
        ));
        if ($validator->fails())
        {
            $salida ['message'] =  $validator->messages()->first();
            return Redirect::back()->withErrors($validator);
        }
        foreach (Input::get('to') as $to)
        {
            $persona = User::where("email","=",$to)->first();
            $residencia = $persona->residencia;
            Mail::send('emails.basic',
            array('title' => Input::get('title'), 'contenido' => Input::get('contenido'), 'persona'=> $persona , 'residencia' => $residencia),
            function($message) use ($to)
            {
                $message->to($to)->subject(Input::get('title'));
                if(Input::hasFile('file'))
                {
                    $message->attach(Input::file('file')->getRealPath(), array('as' =>Input::file('file')->getClientOriginalName()));
                }
            });

        };
        $salida['status']  = "ok";
        $salida['message'] = "Mensaje Enviado entregado a los Destinatarios:  ";
        foreach (Input::get('to') as $key => $correo) {
            $salida['message'] .= $correo  .",  ";
        }
            \Alert::success("Mensaje Enviado")->flash();
            return  redirect('admin');
        }
        return "error No Ha Seleccionado ningun Destinatario";
    }

}

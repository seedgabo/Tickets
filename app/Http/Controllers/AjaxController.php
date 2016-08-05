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
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;

class AjaxController extends Controller
{

    public function setEstadoTicket(Request $request, $id)
    {
        $ticket = Tickets::find($id);
        $ticket->estado = $request->input('estado');
        $ticket->save();
        return $ticket->estado;
    }

    public function setGuardianTicket(Request $request, $id)
    {
        $ticket = Tickets::find($id);
        $ticket->guardian_id = $request->input('guardian_id');
        $ticket->save();
        \App\Funciones::sendMailNewGuardian(User::find($request->input('guardian_id')),User::find(Auth::user()->id),$ticket);
        return $ticket;
    }

    public function addComentarioTicket(Request $request)
    {
        $comentario = \App\Models\ComentariosTickets::create($request->input('comentario'));
        Flash::success("Comentario Agregado exitosamente");
        if($request->hasFile('archivo'))
        {
            $nombre = $comentario->id  . "." . $request->file("archivo")->getClientOriginalExtension();
            $request->file('archivo')->move(public_path("archivos/ComentariosTickets/"), $nombre );
            $comentario->archivo =  $request->file("archivo")->getClientOriginalName();
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

}

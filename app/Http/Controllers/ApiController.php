<?php

namespace App\Http\Controllers;

use App\Funciones;
use App\Http\Requests;
use App\Models\Tickets;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Laracasts\Flash\Flash;

class ApiController extends Controller
{

    public function doLogin (Request $request)
    {

        $response = Auth::user();
        $response["img"] = Auth::user()->imagen();
        return $response;
    }

    public function getCategorias (Request $request)
    {

        $categorias = Auth::user()->categorias()->whereInLoose("parent_id",["",null])->toArray();

        return \Response::json(array_values($categorias), 200);
    }

    public function getTickets (Request $request, $categoria)
    {

        $tickets = \App\Models\Tickets::where("categoria_id",$categoria)
        ->with("user")->with("guardian")->withCount('comentarios')
        ->get();

        $subcategorias = Auth::user()->categorias()->whereLoose("parent_id", $categoria)->toArray();
        return \Response::json(['tickets' => $tickets, 'categorias' => array_values($subcategorias)], 200);
    }

 	public function getCategoriasDocumentos (Request $request)
 	{
        $categorias = \App\Models\CategoriaDocumentos::
        where(function($q){
            $q->where("parent_id","");
            $q->orWhereNull("parent_id");
        })
        ->get();

        return \Response::json($categorias, 200);
    }

    public function getDocumentos(Request $request, $categoria)
    {
        $documentos = \App\Models\Documentos::where("activo","=","1")->where("categoria_id","=",$categoria)->get();
        foreach ($documentos as $documento) {
            $documento->mime = substr(strrchr($documento->archivo,'.'),1);
        }

        $subcategorias = \App\Models\CategoriaDocumentos::where("parent_id",$categoria)->get();
        return \Response::json(['documentos'=>$documentos, 'categorias' => $subcategorias], 200);
    }

    public function getTicket (Request $request, $ticket_id)
    {

        $ticket = \App\Models\Tickets::where("id","=",$ticket_id)
        ->with("user")->with("guardian")
        ->first();
        if($ticket->archivo != null)
            $ticket->path  = $ticket->archivo();
        $ticket->mime  = substr(strrchr($ticket->archivo,'.'),1);

        $comentarios = $ticket->comentarios()->with("user")->orderBy('created_at','desc')->get();

        $comentarios->each(function($c) {
            if($c->archivo != null)
                $c->path = $c->file();
            $c->mime = substr(strrchr($c->archivo,'.'),1);
        });

        return \Response::json(['comentarios' => $comentarios, 'ticket' => $ticket], 200);
    }

    public function getMisTickets(Request $request)
    {
       $ticketsCreados = Auth::user()->tickets()->with('user','guardian')->get();
       $ticketsResponsables = Auth::user()->tickets_guardian()->with('user','guardian')->get();
       return \Response::json(['ticketsCreados'=>$ticketsCreados, 'ticketsResponsables' => $ticketsResponsables], 200);      
    }

    public function getAllTickets(Request $request)
    {
        $tickets = Tickets::
        orwhereIn("categoria_id",Auth::user()->categorias_id)
        ->orwhere("user_id",Auth::user()->id)
        ->orWhere("guardian_id",Auth::user()->id)
        ->with('user','guardian')->get();
       return \Response::json(['tickets'=>$tickets], 200);      
    }

    public function getTicketsAbiertos(Request $request)
    {
        $tickets = Auth::user()->tickets()->where("estado","<>", "completado")->with('user','guardian')->get();
       return \Response::json(['tickets'=>$tickets], 200);      
    }

    public function getTicketsVencidos(Request $request)
    {
        $tickets = Auth::user()->tickets()->where("vencimiento", new Carbon())->with('user','guardian')->get();
       return \Response::json(['tickets'=>$tickets], 200);      
    }


    public function getUsuariosCategoria(Request $request ,$categoria_id)
    {
        $categoria =  \App\Models\CategoriasTickets::find($categoria_id);
        $users = $categoria->users();
        return json_encode($users);
    }

    public function addTicket(Request $request)
    {

        if($request->hasFile('archivo') && $request->get("encriptado") == "true" && !$request->has("clave"))
        {
            return "Debe Ingresar Una contraseña para encriptar el archivo";
        }
        
        $input = $request->except("archivo","enriptado","clave","vencimiento");
        $input =  array_add($input,'user_id',Auth::user()->id);
        $input =  array_add($input,'estado','abierto');
        $tickets = \App\Models\Tickets::create($input);
        $tickets->vencimiento = new Carbon($request->input('vencimiento'));
        $tickets->save();
        if($request->hasFile('archivo'))
        {   
            $nombre = $request->file("archivo")->getClientOriginalName();
            // Si Se pidio Encriptar El Archivo
            if($request->get("encriptado") == "true")
            {
                $tickets->encriptado = true;
                $tickets->archivo = $nombre;       
                $tickets->clave = $request->get("clave");              

                $encriptado = Crypt::encrypt(file_get_contents($request->file("archivo")));
                Storage::put("tickets/". $tickets->id . "/" . $nombre , $encriptado);
            }
            // Si no
            else
            {
                $request->file('archivo')->move(public_path("archivos/tickets/" . $tickets->id . "/"), $nombre );
                $tickets->archivo = $nombre;                
            }

            $tickets->save();
        }

        Funciones::sendMailNewTicket($tickets, $tickets->user, $tickets->guardian);
        return $tickets;
    }

    public function addComentarioTicket (Request $request, $ticket_id)
    {
        $ticket = \App\Models\Tickets::find($ticket_id);
        if($request->hasFile('archivo') && $request->get("encriptado") == "true" && !$request->has("clave"))
        {
            return "Error: Sin Clave de encriptacion";
        }

        $comentario = \App\Models\ComentariosTickets::create([ 
            'user_id'    => Auth::user()->id, 
            'texto' => $request->input('texto'),
            'ticket_id'  => $ticket_id
            ]);

        if($request->hasFile('archivo'))
        {
            $nombre = $request->file("archivo")->getClientOriginalName();
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

        \App\Funciones::sendMailNewComentario([$ticket->user->id,$ticket->guardian->id], $comentario);  
        return $comentario;
    }
    
    public function deleteComentarioTicket(Request $request,$id)
    {
        $comentario = \App\Models\ComentariosTickets::find($id);
        if($comentario->user_id != Auth::user()->id)
        {
            abort(503);
        }
        $comentario->delete();
        return "true";
    }

    public function getFileTicketEncrypted(Request $request, $id, $clave)
    {
        $ticket = Tickets::find($id);
        if($clave != $ticket->clave)
            return response()->make($decryptedContents, 200, array(
                'Content-Type' => (new \finfo(FILEINFO_MIME))->buffer("Clave Incorrecta"),
                'Content-Disposition' => 'attachment; filename="error.txt"'
            ));

        $encryptedContents = Storage::get("tickets/". $id . "/" . $ticket->archivo);
        $decryptedContents = Crypt::decrypt($encryptedContents);

        return response()->make($decryptedContents, 200, array(
            'Content-Type' => (new \finfo(FILEINFO_MIME))->buffer($decryptedContents),
            'Content-Disposition' => 'attachment; filename="' . $ticket->archivo . '"'
        ));
    }

    public function getFileComentarioTicketEncrypted(Request $request, $id, $clave)
    {
        $comentario = ComentariosTickets::find($id);
        if($clave != $comentario->clave)
            return response()->make($decryptedContents, 200, array(
                'Content-Type' => (new \finfo(FILEINFO_MIME))->buffer("Clave Incorrecta"),
                'Content-Disposition' => 'attachment; filename="error.txt"'
            ));

        $encryptedContents = Storage::get("ComentariosTickets/". $id . "/" . $comentario->archivo);
        $decryptedContents = Crypt::decrypt($encryptedContents);

        return response()->make($decryptedContents, 200, array(
            'Content-Type' => (new \finfo(FILEINFO_MIME))->buffer($decryptedContents),
            'Content-Disposition' => 'attachment; filename="' . $comentario->archivo . '"'
        ));
    }

    public function busqueda(Request $request)
    {
        $query = $request->input('query');
        $documentos = \App\Models\Documentos::where("activo","=","1")->where("titulo","like","%".$query."%")
        ->orwhere("descripcion","like","%".$query."%")->with('categoria')->get();

        $tickets = \App\Models\Tickets::where("titulo","like","%".$query."%")
        ->orwhere("contenido","like","%".$query."%")
        ->whereIn("categoria_id",Auth::user()->categorias()->pluck("id"))
        ->with('user','guardian')->get();

        $categorias = \App\Models\CategoriasTickets::where("nombre","like", "%". $query ."%")
        ->whereIn("id",Auth::user()->categorias()->pluck("id"))
        ->get();
        
        return \Response::json(['tickets' => $tickets, 'documentos' => $documentos, 'categorias' => $categorias]);
    }
    
}

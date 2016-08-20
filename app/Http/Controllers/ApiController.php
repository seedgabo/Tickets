<?php

namespace App\Http\Controllers;

use App\Funciones;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

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
        $tickets = \App\Models\Tickets::create($input);
        $tickets->vencimiento = new Carbon($request->input('vencimiento'));
        $tickets->user_id = Auth::user()->id;
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
            $nombre = $comentario->id  . "." . $request->file("archivo")->getClientOriginalExtension();
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

        \App\Funciones::sendMailNewComentario([$ticket->user->email,$ticket->guardian->email], $comentario);  
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
}

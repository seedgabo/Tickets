<?php

namespace App\Http\Controllers;
use App\Carritos;
use App\Dbf;
use App\Empresas;
use App\Funciones;
use App\Http\Requests;
use App\Models\CategoriasTickets;
use App\Models\ComentariosTickets;
use App\Models\Documentos;
use App\Models\Tickets;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Laracasts\Flash\Flash;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (Auth::check()) {
            $categorias = Auth::user()->categorias()->wherein("parent_id",["",null]);
            $tickets = Tickets::where("guardian_id",Auth::user()->id)->take(6)->get();
            $documentos = Documentos::where("activo","=","1")->orderby("updated_at","desc")->with('categoria')->take(6)->get();
            return view('menu')->withCategorias($categorias)->withTickets($tickets)->withDocumentos($documentos);
        }
        else{
            return redirect("login");
        }
    }

    public function menu(Request $request)
    {
        $empresa =  Funciones::getEmpresa();
        return view('menu')->withEmpresa($empresa);
    }

    public function profile (Request $request)
    {
        $qr = array("email" => Auth::user()->email, "url" => url(""), "token" => Crypt::encrypt(Auth::user()->id));
        $qr = json_encode($qr);
        return view('profile')->withUser(Auth::user())->withQr($qr);
    }

    public function profileUpdate (Request $request)
    {
        $user = Auth::user();
        $user->nombre =  $request->input('nombre');
        $user->email =  $request->input('email');
        $user->save();
        if($request->hasFile('photo'))
        {
            array_map('unlink', glob(public_path("img/users/". $user->id .".*")));
            $request->file('photo')->move(public_path('img/users/'), $user->id ."." . $request->file('photo')->getClientOriginalExtension());
        }
        if($request->has('password'))
        {
            if (!Hash::check($request->input('oldpassword'), Auth::user()->password)) {
                Flash::Error('La contraseña no coincide con la actual');
                return back();
            }
            if ($request->input('password') != $request->input('password_confirm')) {
                Flash::Error('Las contraseñas no coinciden');
                return back();
            }
            $user->password = Hash::make($request->input('password'));
            $user->save();
            Flash::Success("Contraseña Actualizada");
            Funciones::sendMailPasswordChanged($user);
        }
        Flash::Success("Usuario actualizado correctamente");
        return  back();
    }

    public function tickets(Request $request)
    {
        $desde = Input::get('desde', Carbon::now()->startOfYear());
        $hasta = Input::get('hasta', Carbon::now()->tomorrow());

        $categorias =  Auth::user()->categorias();
        $tickets = Tickets::where('estado',"<>","completado")
        ->whereIn("categoria_id",$categorias->pluck("id"))
        ->orderBy("categoria_id","asc")
        ->orderBy("created_at")
        ->whereBetween("created_at",[$desde,$hasta])
        ->get();

        return  view('tickets')->withTickets($tickets)->withDesde($desde)->withHasta($hasta);
    }

    public function misTickets(Request $request)
    {
        $desde = Input::get('desde', Carbon::now()->startOfYear());
        $hasta = Input::get('hasta', Carbon::now()->tomorrow());

        $tickets= Tickets::where(function($q){
            $q->orWhere("user_id",Auth::user()->id);
            $q->orWhere("guardian_id",Auth::user()->id);
        })
        ->whereBetween("created_at",[$desde,$hasta])
        ->orderBy("categoria_id","asc")
        ->orderBy("created_at")
        ->get();
        return  view('tickets')->withTickets($tickets)->withDesde($desde)->withHasta($hasta);
    }

    public function todostickets(Request $request)
    {
        $desde = Input::get('desde', Carbon::now()->startOfYear());
        $hasta = Input::get('hasta', Carbon::now()->tomorrow());
        $tickets = Tickets::where(function($q){
            $q->orwhereIn("categoria_id",Auth::user()->categorias_id)       
            ->orwhere("user_id",Auth::user()->id)
            ->orWhere("guardian_id",Auth::user()->id)
            ->orwhere("invitados_id", "LIKE", '%"'. Auth::user()->id . '%');
        })
        ->whereBetween("created_at",[$desde,$hasta])
        ->get();

        return  view('tickets')->withTickets($tickets)->withDesde($desde)->withHasta($hasta);
    }

    public function porCategoria(Request $request, $categoria)
    {
        $desde = Input::get('desde', Carbon::now()->startOfYear());
        $hasta = Input::get('hasta', Carbon::now()->tomorrow());
        $tickets = CategoriasTickets::find($categoria)->tickets()->whereBetween("created_at",[$desde,$hasta])->get();
        $subCategorias = Auth::user()->categorias()->whereLoose("parent_id",$categoria);
        return  view('tickets')
        ->withTickets($tickets)->withSubcategorias($subCategorias)
        ->withDesde($desde)->withHasta($hasta);
    }

    public function ticketAgregar(Request $request)
    {
        return view("agregar-caso");
    }

    public function ticketVer(Request $request, $id)
    {
        $ticket= Tickets::find($id);
        if(!in_array(Auth::user()->id, $ticket->participantes()->pluck('id')->toArray()))
        {
            return view("errors/401");
        }
        $comentarios = ComentariosTickets::where("ticket_id",$ticket->id)
        ->orderBy("created_at", "desc")
        ->get();
        return view("verTicket")->withTicket($ticket)->withComentarios($comentarios);
    }

    public function ticketEliminar(Request $request, $id)
    {
        $ticket =Tickets::find($id);
        if($ticket->user_id == Auth::user()->id  || Auth::user()->admin ==1 )
        {
            $ticket->delete();
        }
        else
        {
            \Flash::error("No tiene los permisos necesarios");
        }
        return back();
    }

    public function ticketEditar(Request $request, $id)
    {
        $ticket =Tickets::find($id);
        if($ticket->user_id == Auth::user()->id || Auth::user()->id == $ticket->guardian_id )
        {
            $ticket->contenido = $request->input('contenido');
            \App\Models\ComentariosTickets::Create(['ticket_id' => $ticket->id, 'user_id' => Auth::user()->id,
                'texto' => "<b style='color:green'><em> ". Auth::user()->nombre . " actualizó el contenido del ticket </em></b>"
                ]);
            $ticket->save();
            \Flash::success("Contenido Actualizado");
            Funciones::sendMailContenidoActualizado($ticket,$ticket->user,$ticket->guardian);
        }
        else
        {
            \Flash::error("No tiene los permisos necesarios");
        }
        return back();
    }

    public function listarDocumentos (Request $request, $categoria)
    {
        $categorias = \App\Models\CategoriaDocumentos::where("parent_id","=",$categoria)->get();
        $documentos = \App\Models\Documentos::where("categoria_id", "=", $categoria)->where("activo","=","1")->get();
        return view('ver-documentos')->withDocumentos($documentos)->withCategoria($categoria)->withCategorias($categorias);
    }

    public function listarCategorias (Request $request)
    {
        $categorias = \App\Models\CategoriaDocumentos::where("parent_id","=","0")
        ->orwhereNull("parent_id")
        ->distinct()->get();
        return view('ver-categoriasDocumentos')->withCategorias($categorias);
    }


    public function getFileTicketEncrypted(Request $request, $id, $clave)
    {
        $ticket = Tickets::find($id);
        if($clave != $ticket->clave)
            return "Clave Incorrecta. No Autorizado";

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
            return "Clave Incorrecta. No Autorizado";

        $encryptedContents = Storage::get("ComentariosTickets/". $id . "/" . $comentario->archivo);
        $decryptedContents = Crypt::decrypt($encryptedContents);

        return response()->make($decryptedContents, 200, array(
            'Content-Type' => (new \finfo(FILEINFO_MIME))->buffer($decryptedContents),
            'Content-Disposition' => 'attachment; filename="' . $comentario->archivo . '"'
        ));
    }

    public function getDocumento(Request $request, $id)
    {
        $documento = \App\Models\Documentos::find($id);
        \App\Models\Auditorias::Create(['user_id' => Auth::user()->id, 'documento_id'=> $id , 'tipo' => 'descarga']);
        return response()->download(storage_path("documentos/" . $documento->id  ."/" . $documento->archivo), $documento->archivo);
    }

    public function descargarArchivo(Request $request, $id)
    {
        $archivo = \App\Models\Archivos::findorFail($id);

        $file = Storage::get("archivos/". $id);

        return response()->make($file, 200, array(
            'Content-Type' => (new \finfo(FILEINFO_MIME))->buffer($file),
            'Content-Disposition' => 'attachment; filename="' . $archivo->nombre . '"'
        ));
    }

    public function busqueda(Request $request)
    {
        $query = $request->input('query');
        $documentos = \App\Models\Documentos::where("activo","=","1")->where("titulo","like","%".$query."%")
        ->orwhere("descripcion","like","%".$query."%")->get();

        $tickets = Tickets::orwhere("titulo","like","%".$query."%")
        ->orwhere("contenido","like","%".$query."%")
        ->where(function($q){
           $q->orwhereIn("categoria_id",Auth::user()->categorias_id)
            ->orwhere("user_id",Auth::user()->id)
            ->orWhere("guardian_id",Auth::user()->id)
            ->orwhere("invitados_id", "LIKE", '%"'. Auth::user()->id . '%');
        })
        ->get();

        $categorias = CategoriasTickets::where("nombre","like", "%". $query ."%")
        ->whereIn("id",Auth::user()->categorias()->pluck("id"))
        ->get();
        
        return view('busqueda')->withTickets($tickets)->withDocumentos($documentos)->withCategorias($categorias);
    }
}

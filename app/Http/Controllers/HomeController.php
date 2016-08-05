<?php

namespace App\Http\Controllers;
use App\Carritos;
use App\Dbf;
use App\Empresas;
use App\Funciones;
use App\Http\Requests;
use App\Models\CategoriasTickets;
use App\Models\ComentariosTickets;
use App\Models\Tickets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
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
            $categorias = Auth::user()->categorias();
            $tickets = Tickets::where("guardian_id",Auth::user()->id)->take(6)->get();
            return view('menu')->withCategorias($categorias)->withTickets($tickets);
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
        $categorias =  Auth::user()->categorias();
        $tickets = Tickets::where('estado',"<>","completado")
        ->whereIn("categoria_id",$categorias->pluck("id"))
        ->orderBy("categoria_id","asc")
        ->orderBy("created_at")
        ->get();
        return  view('tickets')->withTickets($tickets);
    }

    public function misTickets(Request $request)
    {
        $tickets= Tickets::where("user_id",Auth::user()->id)
        ->orderBy("categoria_id","asc")
        ->orderBy("created_at")
        ->get();
        return  view('tickets')->withTickets($tickets);
    }

    public function todostickets(Request $request)
    {
        if(Auth::user()->admin != 1)
        {
            $categorias = Auth::user()->categorias();
            $tickets = Tickets::whereIn("categoria_id",$categorias->pluck("id"))
            ->get();
        }
        else
        {
            $tickets = Tickets::all();
        }
        return  view('tickets')->withTickets($tickets);
    }

    public function porCategoria(Request $request, $categoria)
    {
        $tickets = CategoriasTickets::find($categoria)->tickets;
        return  view('tickets')->withTickets($tickets);
    }

    public function ticketVer(Request $request, $id)
    {
        $ticket= Tickets::find($id);
        if(!in_array($ticket->categoria_id,Auth::user()->categorias_id))
        {
            return view("errors/401");
        }
        $comentarios = ComentariosTickets::where("ticket_id",$ticket->id)
        ->orderBy("created_at")
        ->get();
        return view("verTicket")->withTicket($ticket)->withComentarios($comentarios);
    }

    public function ticketEliminar(Request $request, $id)
    {
        $ticket =Tickets::find($id);
        if($ticket->user_id == Auth::user()->id || Auth::user()->admin ==1 )
        {
            $ticket->delete();
        }
        else
        {
            \Flash::error("No tiene los permisos necesarios");
        }
        return back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\CategoriasTickets;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller
{
    public function categoriasUsuarios(Request $request, $categoria)
    {
    	$categoria = CategoriasTickets::find($categoria);
    	$users = \App\User::all();
    	return view('Admin.agregarmasivamente')->withUsers($users)->withCategoria($categoria);
    }

    public function agregarmasivamente(Request $request, $categoria)
    {
    	$usuarios = $request->input("usuarios");
    	foreach ($usuarios as $usuario_id) {
    		$usuario = \App\User::find($usuario_id);
    		$usuario->categorias_id = $usuario->categorias_id == null ? [] : $usuario->categorias_id;
    		if(!in_array($categoria,$usuario->categorias_id))
    		{
    			$array = array_values($usuario->categorias_id);
    			if (($key = array_search($categoria, $array)) == false) {
    				$array[] = $categoria;
    			}
                $usuario->categorias_id = array_values($array);
                echo $usuario->nombre;
                $usuario->save();
            }
        }

        $nousuarios = \App\User::whereNotIn("id",$usuarios)->get();
        foreach ($nousuarios as $usuario) {
          if(in_array($categoria,$usuario->categorias_id))
          {
             $array = array_values($usuario->categorias_id);
             if (($key = array_search($categoria, $array)) !== false) {
                unset($array[$key]);}
                $usuario->categorias_id = $array;
                $usuario->save();
            }
        }


        \Alert::success("Agregado Masivamente Correcto")->flash();
        return redirect('admin/categorias');   
    }


    public function auditarUsuario(Request $request,$user_id = null)
    {
        $desde = Input::get('desde', Carbon::now()->startOfMonth());
        $hasta = Input::get('hasta', Carbon::now()->tomorrow());
        $limit = Input::get('limit', 200);

        if($user_id != null)
            $registros =\App\Models\Auditorias::whereBetween("created_at",[$desde,$hasta])->where("user_id","=",$user_id)->get();
        else
            $registros =\App\Models\Auditorias::whereBetween("created_at",[$desde,$hasta])->get();
        
        return view("auditorias.usuario")
        ->withDesde($desde)
        ->withHasta($hasta)
        ->withLimit($desde)
        ->withRegistros($registros);
    }
}

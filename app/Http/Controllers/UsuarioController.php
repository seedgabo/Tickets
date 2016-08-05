<?php

namespace App\Http\Controllers;

use App\Empresas;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laracasts\Flash\Flash;

class UsuarioController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        return view("usuarios.index")->withUsuarios(User::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("usuarios.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except("_method","_token");
        $usuario= new User;
        $usuario->fill($data);
        $usuario->admin = $request->input('admin', '0');
        $usuario->password = Hash::make($request->input('password', 'Tickets6325'));
        $usuario->save();
        \App\Funciones::sendMailUser($usuario);
        Flash::success('Usuario Creado');
        return redirect('Usuarios');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = User::find($id);
        return view('usuarios.editar')->withUsuario($usuario);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario = User::find($id);
        return view('usuarios.editar')->withUsuario($usuario);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except("_method","_token");
        $usuario= User::find($id);
        $usuario->fill($data);
        $usuario->admin = $request->input('admin', '0');
        if ($request->has('password') && $request->input('password') != "")
            $usuario->password = Hash::make($request->input('password'));
        $usuario->save();
        Flash::success('Usuario Actualizado');
        return redirect('Usuarios');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        User::destroy($id);
        Flash::success('Usuario Eliminado');
        return redirect('Usuarios');
    }
}

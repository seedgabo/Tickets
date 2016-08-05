<?php

namespace App\Http\Controllers;

use App\Empresas;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class EmpresaController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        return view("empresas.index")->withEmpresas(Empresas::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empresa = Empresas::find($id);
        return view('empresas.editar')->withEmpresa($empresa);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $data = $request->except('emails',"_method","_token");
        $data['emails'] = explode(",", $request->get('emails'));
        $empresa=Empresas::find($id);
        $empresa->fill($data);
        $empresa->cartera_global = $request->get('cartera_global', false);
        $empresa->clientes_global = $request->get('clientes_global', false);
        $empresa->cantidad_global = $request->get('cantidad_global', false);
        $empresa->precio_global = $request->get('precio_global', false);
        $empresa->save();
        if($request->hasFile("image"))
        {
            array_map('unlink', glob(public_path("img/empresas/". $empresa->id .".*")));
            $request->file("image")->move(public_path("img/empresas"), $empresa->id . "." . $request->file("image")->getClientOriginalExtension());
        }
        Flash::success('Empresa Actualizada');
        return redirect('Empresa');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

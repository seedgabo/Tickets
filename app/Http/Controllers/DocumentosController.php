<?php

namespace App\Http\Controllers;

use App\Models\Documentos;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class DocumentosController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        return view("documentos.index")->withDocumentos(Documentos::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $documentos = Documentos::where("titulo","LIKE",$request->input('titulo'))->get();
        return view('documentos.create')
            ->with('documentos', $documentos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except("archivo");
        $documento = Documentos::Create($data);
        $documento->editable = $request->input("editable",false);
        $documento->activo = $request->input("activo",false);
        if($request->hasFile("archivo"))
        {
            $nombre = $request->file("archivo")->getClientOriginalName();
            $documento->archivo = $nombre;       
            $request->file("archivo")->move(storage_path("documentos/"),$nombre);
        }
        $documento->save();
        Flash::success("Documento Generado");
        return redirect("documentos");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $documento = Documentos::find($id);
        return view('documentos.editar')->withDocumento($documento);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $documentos = Documentos::find($id);
        return view('documentos.edit')
            ->with('documentos', $documentos);
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
        $data = $request->except("archivo");
        $documento = Documentos::find($id);
        $documento->fill($data);
        $documento->editable = $request->input("editable",false);
        $documento->activo = $request->input("activo",false);        
        if($request->hasFile("archivo"))
        {
            $nombre = $request->file("archivo")->getClientOriginalName();
            $documento->archivo = $nombre;       
            $request->file("archivo")->move(storage_path("documentos/"),$nombre);
        }
        $documento->save();
        Flash::success("Documento Actualizado");
        return redirect("documentos");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Documentos::destroy($id);
        Flash::success("Documento Eliminado Correctamente");
        return redirect('documentos');
        
        
    }
}

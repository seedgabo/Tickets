<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DocumentoRequest as StoreRequest;
use App\Http\Requests\DocumentoRequest as UpdateRequest;

class DocumentoCrudController extends CrudController {

	public function __construct() {
        parent::__construct();

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\Documentos");
        $this->crud->setRoute("admin/documentos");
        $this->crud->setEntityNameStrings('documento', 'documentos');

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->addColumn([
            'label' => '#',
            'name'  => 'id',
            'type'  => 'text'
        ]);

		$this->crud->setFromDb();

        $this->crud->addColumn([
            'name'=> 'Activo',
            'label' => "Activo", // Table column heading
            'type' => "model_function",
            'function_name' => 'getActivoText',
        ]);
        $this->crud->addColumn([
            'name'=> 'Editable',
            'label' => "Protegido", // Table column heading
            'type' => "model_function",
            'function_name' => 'getEditableText',
        ]);

        $this->crud->addColumn([
            'label' => 'Archivo',
            'name'  => 'Archivo',
            'type'  => 'model_function',
            'function_name' => 'getLinkArchivo'
        ]);

        $this->crud->removeColumns(['activo','archivo', 'editable']);

        $this->crud->addField([
            'name' => 'editable',
            'type' => 'checkbox',
            'label'=>  'Editable'
        ],'both'); 
        $this->crud->addField([
            'name' => 'activo',
            'type' => 'checkbox',
            'label'=>  'Activo'
        ],'both'); 
        $this->crud->addField([
            'name' => 'descripcion',
            'type' => 'textarea',
            'label'=>  'Descripcion'
        ],'both'); 
        $this->crud->addField([
            'name' => 'categoria',
            'type' => 'categorias_documentos',
            'label'=>  'Categoría',
        ],'both'); 
        $this->crud->addField([
            'name' => 'archivo',
            'type' => 'archivo_documentos',
            'label' => 'Archivo',
        ]);

    }


	public function store(StoreRequest $request)
	{
        $data = $request->except("archivo");
        $documento = \App\Models\Documentos::Create($data);
        $documento->editable = $request->input("editable",false);
        $documento->activo = $request->input("activo",false);
        if($request->hasFile("archivo"))
        {
            $nombre = $request->file("archivo")->getClientOriginalName();
            $documento->archivo = $nombre;       
            $request->file("archivo")->move(storage_path("documentos/". $documento->id), $nombre);
        }
        $documento->save();
        return redirect("admin/documentos");
	}

	public function update(UpdateRequest $request, $id)
	{
        $response = parent::updateCrud();
        $documento = \App\Models\Documentos::find($id);
        if($request->hasFile("archivo"))
        {
            $nombre = $request->file("archivo")->getClientOriginalName();
            $documento->archivo = $nombre;      
            $request->file("archivo")->move(storage_path("documentos/". $documento->id), $nombre);
        }
        $documento->save();
         return $response;
	}
}

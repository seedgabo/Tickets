<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CategoriasRequest as StoreRequest;
use App\Http\Requests\CategoriasRequest as UpdateRequest;
use Illuminate\Support\Facades\Auth;

class CategoriasCrudController extends CrudController {

	public function __construct() {
        parent::__construct();

        $this->crud->setModel("App\Models\CategoriasTickets");
        $this->crud->setRoute("admin/categorias");
        $this->crud->setEntityNameStrings('Categoria', 'categorias de Casos');


        $this->crud->setFromDb();
        
        $this->crud->addColumn(
        [
            'label' => "Categoría Padre", 
            'type' => "select",
            'name' => 'padre', 
            'entity' => 'parent', 
            'attribute' => "nombre",
            'model' => "App\Models\CategoriaTickets", 
        ]);

        $this->crud->addField(
        [
            'name' => 'padre',
            'label' => 'Categoria',
            'type' => 'categoria_tickets_radio'
        ], 'both');

        $this->crud->addButtonFromModelFunction("line", "boton", "getButtonAddMasive", "beginning");
        $this->crud->removeField('parent_id','both');
        $this->crud->removeColumn('parent_id');
    }



	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}

}

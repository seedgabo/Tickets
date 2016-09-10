<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AlertaRequest as StoreRequest;
use App\Http\Requests\AlertaRequest as UpdateRequest;

class AlertaCrudController extends CrudController {

	public function __construct() {
        parent::__construct();

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\Alerta");
        $this->crud->setRoute("admin/alertas");
        $this->crud->setEntityNameStrings('alerta', 'alertas');

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/

		$this->crud->setFromDb();
        $this->crud->removeFields(['usuarios','emitido']);
        $this->crud->removeColumns(['usuarios']); // remove an array of columns from the stack


        $this->crud->addField([
            'type' => 'textarea',
            'name' => 'mensaje',
            'label' => "Mensaje de la notificación"
        ]);         
        $this->crud->addField([
            'type' => 'ckeditor',
            'name' => 'correo',
            'label' => "Cuerpo del Correo"
        ]);
        $this->crud->addField([
                'type' => 'select_from_array',
                'name' => 'emitido',
                'options' => ["0" => "No", "1" => "Emitida"],
                'label' => "Emitida?"
            ],'update');

        $this->crud->addField([
            'type' => 'text',
            'name' => 'programado',
            'attributes' => ['class'=>'form-control datetimepicker'],
            'label' => "Programada para:"
        ]);
        $this->crud->addField([
            'type' => 'usuarios_alertas',
            'name' => 'usuarios[]',
            'label' => "Usuarios:"
        ]);


        $this->crud->addColumn([
            'type' => 'model_function',
            'function_name' => 'getUsuariosText',
            'label' => "Usuarios:"
        ]);
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']);
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);

        // ------ CRUD ACCESS
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);

        // ------ CRUD REORDER
        // $this->crud->enableReorder('label_name', MAX_TREE_LEVEL);
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('reorder');

        // ------ CRUD DETAILS ROW
        // $this->crud->enableDetailsRow();
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('details_row');
        // NOTE: you also need to do overwrite the showDetailsRow($id) method in your EntityCrudController to show whatever you'd like in the details row OR overwrite the views/backpack/crud/details_row.blade.php

        // ------ AJAX TABLE VIEW
        // Please note the drawbacks of this though: 
        // - 1-n and n-n columns are not searchable
        // - date and datetime columns won't be sortable anymore
        // $this->crud->enableAjaxTable(); 

        // ------ ADVANCED QUERIES
        // $this->crud->addClause('active');
        // $this->crud->addClause('type', 'car');
        $this->crud->addClause('where', 'emitido', '=', '0');
        // $this->crud->addClause('whereName', 'car');
        // $this->crud->addClause('whereHas', 'posts', function($query) {
        //     $query->activePosts();
        // });
        // $this->crud->orderBy();
        // $this->crud->groupBy();
        // $this->crud->limit();
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

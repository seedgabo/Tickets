<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\RecomendacionRequest as StoreRequest;
use App\Http\Requests\RecomendacionRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class RecomendacionCrudController extends CrudController {

	public function __construct() {
        parent::__construct();
        $this->verificarPermisos();

        $this->crud->setModel("App\Models\Recomendacion");
        $this->crud->setRoute("admin/recomendaciones");
        $this->crud->setEntityNameStrings('recomendacion', 'recomendaciones');


		$this->crud->setFromDb();
        $this->crud->removeFields(['user_id']);
        $this->crud->removeColumns(['caso_id', 'user_id']);
        
        if (Input::has('caso_id')) {
           $this->crud->addField([
               'label' => "Caso Médico",
               'type' => 'select2',
               'name' => 'caso_id', 
               'entity' => 'caso_id', 
               'attribute' => 'titulo_caso_fecha',
               'model' => "\App\Models\Casos_medicos",
               'attributes' => ['disabled' => 'disabled']
          ]);
          $this->crud->addField([
               'type' => 'custom_html',
               'name' => 'caso', 
               'value' => '<input type="hidden" name="caso_id" value="'. Input::get('caso_id') .'">', 
          ]);
        }
        else
        {
              $this->crud->addField([
                   'label' => "Caso Médico",
                   'type' => 'select2',
                   'name' => 'caso_id', 
                   'entity' => 'caso', 
                   'attribute' => 'titulo_caso_fecha',
                   'model' => "\App\Models\Casos_medicos"
              ]); 
        }

        $this->crud->addField([
                'label' => "Recomendación",
                'type' => 'textarea',
                'name' => 'descripcion'
            ]);


        $this->crud->addColumn([
             'label' => "Caso Médico",
             'type' => 'select',
             'name' => 'caso_id', 
             'entity' => 'caso', 
             'attribute' => 'titulo_caso',
             'model' => "\App\Models\Casos_medicos",
        ]);

        $this->crud->addColumn([
             'label' => "Médico",
             'type' => 'select',
             'name' => 'user_id', 
             'entity' => 'user', 
             'attribute' => 'nombre',
             'model' => "\App\User",
        ]); 


		// ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        //  // remove an array of columns from the stack
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
        // $this->crud->addClause('where', 'name', '==', 'car');
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
		$data  = $request->except("_token");
        $data['user_id'] = Auth::user()->id;
        \App\Models\Recomendacion::create($data);
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect('admin/ver-caso/' . $data['caso_id']);
	}

	public function update(UpdateRequest $request)
	{
        $data  = $request->except("_token");
        \App\Models\Recomendacion::create($data);
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect('admin/ver-caso/' . $data['caso_id']);
	}

  public function verificarPermisos()
  {
      if(!Auth::user()->can('Agregar Recomendaciones') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['create']);
      }
      if(!Auth::user()->can('Editar Recomendaciones') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['update']);
      }
      if(!Auth::user()->can('Eliminar Recomendaciones') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['delete']);
      }
  }
}

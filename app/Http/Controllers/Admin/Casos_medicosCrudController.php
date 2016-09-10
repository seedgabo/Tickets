<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\Casos_medicosRequest as StoreRequest;
use App\Http\Requests\Casos_medicosRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class Casos_medicosCrudController extends CrudController {

	public function __construct() {
    parent::__construct();
    $this->verificarPermisos();

    $this->crud->setModel("App\Models\Casos_medicos");
    $this->crud->setRoute("admin/casos-medicos");
    $this->crud->setEntityNameStrings('Caso Medico', 'Casos Medicos');

    /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/

		$this->crud->setFromDb();

        $this->crud->addField([
               'label' => "Paciente",
               'type' => 'select2',
               'name' => 'paciente_id', 
               'entity' => 'paciente', 
               'attribute' => 'full_name_cedula',
               'model' => "\App\Models\Paciente" 
          ]);      
        $this->crud->addField([
               'label' => "Origen Del Caso",
               'type' => 'tinymce',
               'name' => 'origen_del_caso',
          ]);           
        $this->crud->addField([
               'label' => "Descripción del Caso",
               'type' => 'tinymce',
               'name' => 'descripcion',
          ]);   

        $this->crud->addField([
            'label' => "Fecha de Apertura", 
            'type' => "text",
            'name' => 'apertura',
            'attributes' => ['class'=>'form-control datepicker','required' => 'required']
          ]); 

        $this->crud->addField([
            'label' => "Fecha de Cierre", 
            'type' => "text",
            'name' => 'cierre',
            'attributes' => ['class'=>'form-control datepicker']
          ]);   

        $this->crud->addField([
               'label' => "Estado",
               'type' => 'select_from_array',
               'name' => 'estado', 
                'options' => ['Abierto' => 'Abierto', 'Cerrado' => 'Cerrado', 'Prorroga Constitucional' => 'Prorroga Constitucional'],
                'allows_null' => false
          ]);     

        $this->crud->addField([
               'label' => "Medico",
               'type' => 'select_medico',
               'name' => 'medico_id', 
          ]);
        

        $this->crud->removeFields(['ticket_id']);

        $this->crud->removeColumns(['origen_del_caso','paciente_id','apertura','cierre', 'medico_id','ticket_id']);

        $this->crud->addColumn([
               'label' => "Origen del Caso",
               'type' => 'text',
               'name' => 'origen_del_caso', 
            ]);
        $this->crud->addColumn([
               'label' => "Paciente",
               'type' => 'select',
               'name' => 'paciente_id', 
               'entity' => 'paciente', 
               'attribute' => 'full_name',
               'model' => "\App\Models\Paciente" 
            ]);
        
        $this->crud->addColumn([
               'label' => "Medico",
               'type' => 'select',
               'name' => 'medico_id', 
               'entity' => 'medico', 
               'attribute' => 'nombre',
               'model' => "\App\User" 
            ]);

        $this->crud->addColumn([
             'label' => "Fecha de Apertura", // Table column heading
             'type' => "model_function",
             'function_name' => 'transApertura', 
          ]);

        $this->crud->addColumn([
             'label' => "Seguimiento", // Table column heading
             'type' => "model_function",
             'function_name' => 'verBotonTicket', 
          ]);

        $this->crud->addcolumn([
             'label' => "Incapacidades Acumuladas", 
             'type' => "model_function",
             'function_name' => 'getDiasIncapacidadAcumuladosAttribute', 
          ]);

        $this->crud->addButtonFromModelFunction("line", "VerPaciente", "getButtonVerPaciente", "end");
        $this->crud->addButtonFromModelFunction("line", "VerCaso", "getButtonVerCaso", "end");
        $this->crud->addButtonFromModelFunction("line", "agregarRecomendacion", "getButtonnAgregarRecomendacion", "end");
        if (Input::has('paciente_id')) 
        {
          $paciente = \App\Models\Paciente::find(Input::get('paciente_id'));
          $this->crud->setEntityNameStrings('caso medico', 'Casos de: ' . $paciente->full_name);
          $this->crud->addClause('where', 'paciente_id', '=', $paciente->id);
        }
    }

	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}

  public function verificarPermisos()
  {
      if(!Auth::user()->can('Agregar Casos Medicos') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['create']);
      }
      if(!Auth::user()->can('Editar Casos Medicos') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['update']);
      }
      if(!Auth::user()->can('Eliminar Casos Medicos') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['delete']);
      }
  }
}

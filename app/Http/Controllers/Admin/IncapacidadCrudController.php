<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\IncapacidadRequest as StoreRequest;
use App\Http\Requests\IncapacidadRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class IncapacidadCrudController extends CrudController {

	public function __construct() {
        parent::__construct();
        $this->verificarPermisos();

        $this->crud->setModel("App\Models\Incapacidad");
        $this->crud->setRoute("admin/incapacidades");
        $this->crud->setEntityNameStrings('incapacidad', 'incapacidades');

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/

		// $this->crud->setFromDb();


        $this->crud->addField([
            'name' => 'paciente_id',
            'label' => 'Paciente',
            'type' => 'select2',
            'entity' => 'paciente', 
            'attribute' => 'full_name',
            'model' => "\App\Models\Paciente"
            ]);        
        $this->crud->addField([
            'name' => 'caso_id',
            'label' => 'Caso Asignado',
            'type' => 'select2',
            'entity' => 'Casos_medicos', 
            'attribute' => 'titulo_caso',
            'model' => "\App\Models\Casos_medicos"
            ]);             
        $this->crud->addField([
         'label' => "Medico",
         'type' => 'select_medico',
         'name' => 'medico_id', 
         'entity' => 'medico'
         ]);
        $this->crud->addField([
         'label' => "Fecha Ingreso",
         'type' => 'date',
         'name' => 'fecha_ingreso',
         ]);
        $this->crud->addField([
         'label' => "Fecha de Incapacidad",
         'type' => 'date',
         'name' => 'fecha_incapacidad',
         ]);
        $this->crud->addField([
         'label' => "Fecha de Liquidación",
         'type' => 'date',
         'name' => 'fecha_liquidacion',
         ]);
        $this->crud->addField([
         'label' => "Entidad",
         'type' => 'select_from_array',
         'name' => 'entidad',
         'options' => ['eps' => 'eps', 'arl' => 'arl', 'particular' => 'particular']
         ]);    
        $this->crud->addField([
         'label' => "Eps",
         'type' => 'select2',
         'name' => 'eps_id', 
         'entity' => 'medico', 
         'attribute' => 'nombre',
         'model' => "\App\Models\Eps" 
         ]); 
        $this->crud->addField([
         'label' => "Cie10",
         'type' => 'select2',
         'name' => 'cie10_id', 
         'entity' => 'Cie10', 
         'attribute' => 'titulo',
         'model' => "\App\Models\Cie10" 
         ]);
        $this->crud->addField([
         'label' => "Origen",
         'type' => 'select_from_array',
         'name' => 'origen',
         'options' => ['Común' => 'Común', 'Laboral' => 'Laboral', 'Otro' => 'Otro']
         ]); 
        $this->crud->addField([
         'label' => "¿Prorroga?",
         'type' => 'select_from_array',
         'name' => 'prorroga',
         'options' => ['si' => 'Si', 'no' => 'No']
         ]);
        $this->crud->addField([
         'label' => "Dias de Incapacidad",
         'type' => 'number',
         'name' => 'dias_incapacidad',
         ]);
        $this->crud->addField([
         'label' => "Sistema Afectado",
         'type' => 'textarea',
         'name' => 'sistema_afectado',
         ]);
        $this->crud->addField([
         'label' => "Caso Especial",
         'type' => 'textarea',
         'name' => 'caso_especial',
         ]); 
        $this->crud->addField([
         'label' => "Estado",
         'type' => 'select_from_array',
         'name' => 'estado',
         'options' => ['Abierto' => 'Abierto','Cerrado' => 'Cerrado','Por Revision' => 'Por Revision']
         ]);  


        $this->crud->addColumn([
            'name' => 'paciente_id',
            'label' => 'Paciente',
            'type' => 'select',
            'entity' => 'paciente', 
            'attribute' => 'full_name',
            'model' => "\App\Models\Paciente"
            ]);

        $this->crud->addColumn([
             'label' => "Medico",
             'type' => 'select_medico',
             'name' => 'medico_id', 
             'entity' => 'medico', 
             'attribute' => 'nombre',
             'model' => "\App\User" 
             ]);
        $this->crud->addColumn([
             'label' => "Entidad",
             'name' => 'entidad', 
             'type' => 'text'
             ]);
        $this->crud->addColumn([
             'label' => "Eps",
             'name' => 'eps_id', 
             'type' => 'select',
             'entity' => 'eps',
             'attribute' => 'nombre',
             'model' => '\App\Models\Eps'
             ]);
        $this->crud->addColumn([
             'label' => "Cie10",
             'name' => 'cie10_id', 
             'type' => 'select',
             'entity' => 'cie10',
             'attribute' => 'titulo',
             'model' => '\App\Models\Cie10'
             ]);
        $this->crud->addColumn([
             'label' => "Fecha de Ingreso",
             'name' => 'fecha_ingreso', 
             'type' => 'date'
             ]);
        $this->crud->addColumn([
             'label' => "Fecha de Incapacidad",
             'name' => 'fecha_incapacidad', 
             'type' => 'date'
             ]);
        $this->crud->addColumn([
             'label' => "Dias de Incapacidad",
             'name' => 'dias_incapacidad', 
             'type' => 'number'
             ]);
        $this->crud->addColumn([
             'label' => "Estado",
             'name' => 'estado', 
             'type' => 'text'
             ]);


        $this->crud->addButtonFromModelFunction("line", "VerPaciente", "getButtonVerPaciente", "end");
        $this->crud->addButtonFromModelFunction("line", "verCaso", "getButtonVerCaso", "end");
        $this->crud->addButtonFromModelFunction("line", "verDetalles", "getButtonverIncapacidad", "end");
        
        if (Input::has('paciente_id')) 
        {
          $paciente = \App\Models\Paciente::find(Input::get('paciente_id'));
          $this->crud->setEntityNameStrings('Incapacidad de: ' . $paciente->full_name, 'Incapacidades de: ' . $paciente->full_name);
          $this->crud->addClause('where', 'paciente_id', '=', $paciente->id);
          $this->crud->addField([
            'name'  => 'paciente_id',
            'label' => 'Paciente',
            'type'  => 'hidden',
            'value'  =>  Input::get('paciente_id')
          ]);
        }

        if (Input::has('caso_id')) 
        {
          $caso = \App\Models\Casos_medicos::find(Input::get('caso_id'));
          $this->crud->addClause('where', 'caso_id', '=', $caso->id);
          $this->crud->addField([
            'name'  => 'caso_id',
            'label' => 'Caso',
            'type'  => 'hidden',
            'value'  =>  Input::get('caso_id')
            ]);
        }
    }

    public function store(StoreRequest $request)
    {
        $data = $request->except("_method","_token");
        $incapacidad = \App\Models\Incapacidad::create($data);
        $incapacidad->medico_id = Auth::user()->id;
        $incapacidad->save();
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect('admin/incapacidades');
    }

    public function update(UpdateRequest $request)
    {
          return parent::updateCrud();
    }

    public function verificarPermisos()
    {
            if(!Auth::user()->can('Agregar Incapacidades') &&  !Auth::user()->hasRole('SuperAdmin'))
            {
              $this->crud->denyAccess(['create']);
            }
            if(!Auth::user()->can('Editar Incapacidades') &&  !Auth::user()->hasRole('SuperAdmin'))
            {
              $this->crud->denyAccess(['update']);
            }
            if(!Auth::user()->can('Eliminar Incapacidades') &&  !Auth::user()->hasRole('SuperAdmin'))
            {
              $this->crud->denyAccess(['delete']);
            }
    }
}

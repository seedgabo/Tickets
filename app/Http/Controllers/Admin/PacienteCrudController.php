<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\PacienteRequest as StoreRequest;
use App\Http\Requests\PacienteRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class PacienteCrudController extends CrudController {

	public function __construct() 
    {
        parent::__construct();
        $this->verificarPermisos();
        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\Paciente");
        $this->crud->setRoute("admin/pacientes");
        $this->crud->setEntityNameStrings('paciente', 'pacientes');

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/

		// $this->crud->setFromDb();


        $this->crud->addField(['name' => 'nombres','label'=>'Nombres','type'=>'text','attributes' => ['required' => 'required']]);
        $this->crud->addField(['name' => 'apellidos','label'=>'Apellidos','type'=>'text','attributes' => ['required' => 'required']]);
        $this->crud->addField(['name' => 'cedula','label'=>'Cedula','type'=>'number','attributes' => ['required' => 'required']]);
        $this->crud->addField(['name' => 'nacimiento','label'=>'Fecha de Nacimiento','attributes' => ['required' => 'required', 'class' => 'datepicker form-control']]);
        $this->crud->addField(['name' => 'sexo','label'=>'Sexo','type'=>'select_from_array','options' => ['M' => 'Masculino', 'F' => 'Femenino'],'allows_null' => false,'attributes' => ['required' => 'required']]);
        $this->crud->addField(['name' => 'ingreso','label'=>'Fecha de Ingreso A la Empresa','attributes' => ['required' => 'required', 'class' => 'datepicker form-control']]);
        $this->crud->addField(['name' => 'egreso','label'=>'Fecha de Egreso A la Empresa','attributes' => ['class' => 'datepicker form-control']]);
        $this->crud->addField(['name' => 'telefono','label'=>'Telefono','type'=>'number']);
        $this->crud->addField(['name' => 'email','label'=>'Email','type'=>'email','attributes' => ['required' => 'required']]);
        $this->crud->addField(['name' => 'telefono','label'=>'Telefono','type'=>'number']);
        $this->crud->addField(['name' => 'direccion','label'=>'Dirección','type'=>'textarea']);
        $this->crud->addField(['name' => 'ibc','label'=>'IBC','type'=>'text']);

        $this->crud->addField(['name' => 'nota','label'=>'Nota','type'=>'summernote']);

        $this->crud->addField(['name' => 'cargo','label'=>'Cargo','type'=>'text']);
        $this->crud->addField(['name' => 'departamento','label'=>'Departamento','type'=>'text']);

        $this->crud->addField([
               'label' => "Eps",
               'type' => 'select2',
               'name' => 'eps_id', 
               'entity' => 'eps', 
               'attribute' => 'nombre',
               'model' => "\App\Models\Eps" 
            ]);
        $this->crud->addField([
               'label' => "Arl",
               'type' => 'select2',
               'name' => 'arl_id', 
               'entity' => 'arl', 
               'attribute' => 'nombre',
               'model' => "\App\Models\Arl" 
            ]);
        $this->crud->addField([
               'label' => "Fondo de Pensiones",
               'type' => 'select2',
               'name' => 'fondosDePensiones_id', 
               'entity' => 'fondo', 
               'attribute' => 'nombre',
               'model' => "\App\Models\FondosDePensiones" 
            ]);
        $this->crud->addField([
               'label' => "Puesto de Trabajo",
               'type' => 'select2',
               'name' => 'puesto_id', 
               'entity' => 'puesto', 
               'attribute' => 'nombre',
               'model' => "\App\Models\Puesto" 
            ]);
        $this->crud->addField([
           'label' => "Medico",
           'type' => 'select_medico',
           'name' => 'user_id',
           'attribute' => 'nombre',
           'model' => "\App\User" 
          ]);

        $this->crud->addField([
            'name' => 'foto',
            'label' => 'Foto',
            'type' => 'foto_file_paciente'
            ]);


        $this->crud->addColumns(['nombres','apellidos','cedula','telefono','cargo','departamento','nota','direccion','ibc']); 

        $this->crud->addColumn([
               'label' => "Eps",
               'type' => 'select',
               'name' => 'eps_id', 
               'entity' => 'eps', 
               'attribute' => 'nombre',
               'model' => "\App\Models\Eps" 
            ]);

        $this->crud->addColumn([
               'label' => "Arl",
               'type' => 'select',
               'name' => 'arl_id', 
               'entity' => 'arl', 
               'attribute' => 'nombre',
               'model' => "\App\Models\Arl" 
            ]);

        $this->crud->addColumn([
           'label' => "Medico",
           'type' => 'select',
           'name' => 'user_id', 
           'entity' => 'medico', 
           'attribute' => 'nombre',
           'model' => "\App\User" 
        ]);

        $this->crud->addColumn([
             'label' => "Puesto de Trabajo",
             'type' => 'select',
             'name' => 'puesto_id', 
             'entity' => 'puesto', 
             'attribute' => 'nombre',
             'model' => "\App\Models\Puesto" 
          ]);
        


        // $this->crud->enableAjaxTable(); 

        // ------ ADVANCED QUERIES
        // $this->crud->addClause('active');
        // $this->crud->addClause('type', 'car');
        // $this->crud->addClause('whereName', 'car');
        // $this->crud->addClause('whereHas', 'posts', function($query) {
        //     $query->activePosts();
        // });
        // $this->crud->orderBy();
        // $this->crud->groupBy();
        // $this->crud->limit();
        // 
        
        $this->crud->addButtonFromModelFunction("line", "verPaciente", "getButtonverPaciente", "end");
        $this->crud->addButtonFromModelFunction("line", "verCasos", "getButtonVerCasos", "end");
        
        if (Input::has('paciente_id')) 
        {
           $paciente = \App\Models\Paciente::find(Input::get('paciente_id'));
           $this->crud->setEntityNameStrings('paciente', 'Paciente: ' . $paciente->full_name);
           $this->crud->denyAccess(['create','delete']);
           $this->crud->addClause('where', 'id', '=', $paciente->id);
        }
    }

	public function store(StoreRequest $request)
	{
        $data = $request->except("_method","_token");
        $paciente = \App\Models\Paciente::create($data);
        Static::saveFoto($request , $paciente);
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect('admin/pacientes');
	}

	public function update(UpdateRequest $request)
	{
      $paciente = \App\Models\Paciente::find($request->id);
      $data = $request->except("_method","_token");

      $paciente->fill($data);
      $paciente->save();
      Static::saveFoto($request , $paciente);
		  \Alert::success(trans('backpack::crud.insert_success'))->flash();
      return redirect('admin/pacientes');
	}

  public  static function saveFoto($request , $paciente)
  {
        if($request->hasFile('foto'))
        {
          $archivo = $request->file('foto');
          $nombre  =  $paciente->id . "." . $archivo->getClientOriginalExtension();
          $archivo->move(public_path("/img/pacientes/"), $nombre);
          $paciente->foto = $nombre;
          $paciente->save();
        }
  }


  public function verificarPermisos()
  {
        if(!Auth::user()->can('Agregar Pacientes') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
          $this->crud->denyAccess(['create']);
        }
        if(!Auth::user()->can('Editar Pacientes') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
          $this->crud->denyAccess(['update']);
        }
        if(!Auth::user()->can('Eliminar Pacientes') &&  !Auth::user()->hasRole('SuperAdmin'))
        {
          $this->crud->denyAccess(['delete']);
        }
  }
}

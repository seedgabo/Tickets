<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\UsuariosRequest as StoreRequest;
use App\Http\Requests\UsuariosRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuariosCrudController extends CrudController {

	public function __construct() {
        parent::__construct();

        
        $this->verificarPermisos();

        $this->crud->setModel("App\Models\Usuarios");
        $this->crud->setRoute("admin/usuarios");
        $this->crud->setEntityNameStrings('usuario', 'usuarios');

		$this->crud->setFromDb();
        $this->crud->removeField('categorias_id','both');
        $this->crud->removeColumns(['categorias_id','admin','medico']);

        $this->crud->addField([
            'name' => 'categorias',
            'label' => 'Categorias',
            'type' => 'categorias_user_checkbox'
            ], 'both');

        $this->crud->addField([
            'name'=> 'admin',
            'label' => 'Administrador',
            'type' => 'checkbox'
        ], 'both');

        $this->crud->addField([
            'name'=> 'medico',
            'label' => 'Es Médico?',
            'type' => 'checkbox'
        ], 'both');

        $this->crud->addColumn([
            'label' => "Administrador", // Table column heading
            'type' => "model_function",
            'function_name' => 'getAdmin', // the method in your Model
        ]);

        $this->crud->addColumn([
            'label' => "Categorias", // Table column heading
            'type' => "model_function",
            'function_name' => 'getCategoriasText', // the method in your Model
        ]);

        $this->crud->addColumn([
            'label' => "Médico", // Table column heading
            'type' => "model_function",
            'function_name' => 'getMedicoText', // the method in your Model
        ]);

        $this->crud->addButtonFromModelFunction("line", "boton", "getButtonAuditar", "end");
    }

    

	public function store(StoreRequest $request)
	{

        $data = $request->except("_method","_token");
        $usuario= new \App\User;
        $usuario->fill($data);
        $usuario->admin = $request->input('admin', '0');
        $usuario->password = Hash::make($request->input('email', 'Casos6325'));
        $usuario->save();
        \App\Funciones::sendMailUser($usuario);
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect('admin/usuarios');
	}

	public function update(UpdateRequest $request, $id)
	{
		$data = $request->except("_method","_token","deleted_at");
        $usuario= \App\User::find($id);
        $usuario->fill($data);
        $usuario->admin = $request->input('admin', false);
        $usuario->medico = $request->input('medico', false);
        $usuario->save();
        \Alert::success(trans('backpack::crud.update_success'))->flash();
        return redirect('admin/usuarios');
	}



    public function verificarPermisos()
    {
            if(!Auth::user()->can('Agregar Usuarios') &&  !Auth::user()->hasRole('SuperAdmin'))
            {
              $this->crud->denyAccess(['create']);
            }
            if(!Auth::user()->can('Editar Usuarios') &&  !Auth::user()->hasRole('SuperAdmin'))
            {
              $this->crud->denyAccess(['update']);
            }
            if(!Auth::user()->can('Eliminar Usuarios') &&  !Auth::user()->hasRole('SuperAdmin'))
            {
              $this->crud->denyAccess(['delete']);
            }
    }
}

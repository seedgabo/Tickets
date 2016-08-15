<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UsuariosRequest as StoreRequest;
use App\Http\Requests\UsuariosRequest as UpdateRequest;

class UsuariosCrudController extends CrudController {

	public function __construct() {
        parent::__construct();

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\Usuarios");
        $this->crud->setRoute("admin/usuarios");
        $this->crud->setEntityNameStrings('usuario', 'usuarios');

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->crud->setFromDb();
        $this->crud->removeColumn('categorias_id');
        $this->crud->removeColumn('admin');

        $this->crud->addField([
            'name' => 'categorias_id',
            'label' => 'Categorias',
            'type' => 'categorias'
            ], 'both');

        $this->crud->addField([
            'name'=> 'admin',
            'label' => 'Administrador',
            'type' => 'checkbox'
        ], 'both');

        $this->crud->addColumn([
            'label' => "Administrador", // Table column heading
            'type' => "model_function",
            'function_name' => 'getAdmin', // the method in your Model
        ]);


    }

    

	public function store(StoreRequest $request)
	{

		return parent::storeCrud();
	}

	public function update(UpdateRequest $request, $id)
	{
		$data = $request->except("_method","_token");
        $usuario= \App\Models\Usuarios::find($id);
        $usuario->fill($data);
        $usuario->admin = $request->input('admin', '0');
        if ($request->has('password') && $request->input('password') != "")
            $usuario->password = Hash::make($request->input('password'));
        $usuario->save();
        return redirect('admin/usuarios');
	}
}
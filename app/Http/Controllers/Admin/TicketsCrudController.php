<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TicketsRequest as StoreRequest;
use App\Http\Requests\TicketsRequest as UpdateRequest;
use Illuminate\Support\Facades\Auth;

class TicketsCrudController extends CrudController {

	public function __construct() {
        parent::__construct();

        $this->verificarPermisos()
        $this->crud->setModel("App\Models\Tickets");
        $this->crud->setRoute("admin/tickets");
        $this->crud->setEntityNameStrings('Caso', 'Casos');


        $this->crud->setFromDb();


        $this->crud->addField([
            'name' => 'user_id',
            'type' => 'select',
            'entity' => 'user',
            'label' => 'Creador',
            'attribute' => 'nombre',
            'model' =>'App\Models\usuarios'
        ], 'both');

        $this->crud->addField([
            'name' => 'guardian_id',
            'type' => 'select',
            'entity' => 'guardian',
            'label' => 'Responsable',
            'attribute' => 'nombre',
            'model' =>'App\Models\usuarios'
        ], 'both');

        $this->crud->addField([
            'name' => 'categoria_id',
            'type' => 'categorias_ticket_radio',
            'label' => 'Categoria',
            'entity' => 'categoria',
            'attribute' => 'nombre',
            'model' =>'App\Models\categorias'
        ], 'both');

        $this->crud->addField([
            'name' => 'estado',
            'label' => "Estado",
            'type' => 'select_from_array',
            'options' => ['abierto' => 'abierto', 'completado' =>'completado','en curso'=>'en curso','rechazado' => 'rechazado'],
            'allows_null' => false
        ],'both');

        $this->crud->addField([
            'name' => 'transferible',
            'label' => "Transferible",
            'type' => 'select_from_array',
            'options' => ['0' =>'No', '1' => 'Si'],
            'allows_null' => false
        ],'both');

        $this->crud->addField([
            'name' => 'encriptado',
            'label' => "Encriptado",
            'type' => 'select_from_array',
            'options' => ['0' => 'No' ,'1' => 'Si'],
            'allows_null' => false
        ],'both');

        $this->crud->addField([
                'name' => 'invitados_id',
                'label' => "Inivtados",
                'type' => 'select_invitados',
                'allows_null' => true
            ],'update');

        $this->crud->addField([
            'name' => 'canSetVencimiento',
            'label' => "El responsable puede cambiar la fecha de vencimiento",
            'type' => 'select_from_array',
            'options' => ['0' => 'No' ,'1' => 'Si'],
            'allows_null' => false
        ],'both');

        $this->crud->addField([
            'name' => 'canSetGuardian',
            'label' => "El responsable puede cambiar Transferir",
            'type' => 'select_from_array',
            'options' => ['0' => 'No' ,'1' => 'Si'],
            'allows_null' => false
        ],'both');

        $this->crud->addField([
            'name' => 'canSetEstado',
            'label' => "El responsable puede cambiar el estado",
            'type' => 'select_from_array',
            'options' => ['0' => 'No' ,'1' => 'Si'],
            'allows_null' => false
        ],'both');

        $this->crud->addField([
            'name' => 'contenido',
            'type' => 'ckeditor'
        ], 'both');

        $this->crud->addField([
            'label' => "vencimiento", 
            'type' => "text",
            'name' => 'vencimiento',
            'attributes' => ['class'=>'form-control datetimepicker']
        ]); 



        $this->crud->addColumn([
            'label' => "Usuario", 
            'type' => "select",
            'name' => 'Usuario', 
            'entity' => 'user', 
            'attribute' => "nombre", 
            'model' => "App\User",
            ]);

        $this->crud->addColumn([
            'label' => "Guardian", 
            'type' => "select",
            'name' => 'Guardian', 
            'entity' => 'guardian', 
            'attribute' => "nombre", 
            'model' => "App\User",
            ]);

            $this->crud->addColumn([
            'label' => "Categoria", 
            'type' => "select",
            'name' => 'Categorias', 
            'entity' => 'categoria', 
            'attribute' => "nombre", 
            'model' => "App\Models\Categorias",
            ]); 


        $this->crud->removeColumns(['transferible', 'encriptado', 'clave', 'archivo', 'user_id', 'guardian_id', 'categoria_id', 'canSetGuardian', 'canSetVencimiento', 'invitados_id']);

        
        $this->crud->removeFields(['clave','archivo']);

    }

	public function store(StoreRequest $request)
	{
        return parent::storeCrud();

	}

	public function update(UpdateRequest $request)
	{
        $ticket = \App\Models\Tickets::find($request->input("id"));
        $ticket->invitados_id = $request->input("invitados_id");
        $ticket->save();
        return parent::updateCrud();
	}


    public function verificarPermisos()
    {
      if(!Auth::user()->can('Agregar Casos') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['create']);
      }
      if(!Auth::user()->can('Editar Casos') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['update']);
      }
      if(!Auth::user()->can('Eliminar Casos') &&  !Auth::user()->hasRole('SuperAdmin'))
      {
        $this->crud->denyAccess(['delete']);
      }
  }
}

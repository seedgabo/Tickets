<?php namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TicketsRequest as StoreRequest;
use App\Http\Requests\TicketsRequest as UpdateRequest;

class TicketsCrudController extends CrudController {

	public function __construct() {
        parent::__construct();

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\Tickets");
        $this->crud->setRoute("admin/tickets");
        $this->crud->setEntityNameStrings('Caso', 'Casos');

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/

		$this->crud->setFromDb();

		// ------ CRUD FIELDS
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
            'label' => 'Responsable',
            'entity' => 'guardian',
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
            'options' => ['1' => 'Si', '0' =>'No'],
            'allows_null' => false
        ],'both');
        $this->crud->addField([
            'name' => 'encriptado',
            'label' => "Encriptado",
            'type' => 'select_from_array',
            'options' => ['1' => 'Si', '0' =>'No'],
            'allows_null' => false
        ],'both');

        $this->crud->addField([
            'name' => 'contenido',
            'type' => 'ckeditor'
        ], 'both');

        // ------ CRUD COLUMNS
        $this->crud->addColumn([
            'label' => "Usuario", // Table column heading
            'type' => "select",
            'name' => 'Usuario', // the method that defines the relationship in your Model
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => "nombre", // foreign key attribute that is shown to user
            'model' => "App\User", // foreign key model
            ]);
        $this->crud->addColumn([
            'label' => "Guardian", // Table column heading
            'type' => "select",
            'name' => 'Guardian', // the method that defines the relationship in your Model
            'entity' => 'guardian', // the method that defines the relationship in your Model
            'attribute' => "nombre", // foreign key attribute that is shown to user
            'model' => "App\User", // foreign key model
            ]);

            $this->crud->addColumn([
            'label' => "Categoria", // Table column heading
            'type' => "select",
            'name' => 'Categorias', // the method that defines the relationship in your Model
            'entity' => 'categoria', // the method that defines the relationship in your Model
            'attribute' => "nombre", // foreign key attribute that is shown to user
            'model' => "App\Models\Categorias", // foreign key model
            ]); 
            $this->crud->addField([
            'label' => "vencimiento", // Table column heading
            'type' => "text",
            'name' => 'vencimiento',
            'attributes' => ['class'=>'form-control datetimepicker']
            ]); 
            $this->crud->removeColumns(['transferible', 'encriptado', 'clave', 'archivo', 'user_id', 'guardian_id', 'categoria_id']); // remove an array of columns from the stack
            $this->crud->removeFields(['clave','archivo']);

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

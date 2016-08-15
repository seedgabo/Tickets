<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Usuarios extends Model
{
	use CrudTrait;

     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'users';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	// protected $guarded = ['id'];
	protected $fillable = ["nombre","email","categorias_id","admin"];
	// protected $hidden = [];
    // protected $dates = [];
	   protected $casts = [
        'categorias_id' => 'array',
		'admin' => 'boolean'
    ];

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	public function getAdmin(){
		if($this->admin == 1)
		 	return "Administrador";
		else
			return "Usuario";
	}
	public function getCategoriasText()
    {
        $cat = CategoriasTickets::wherein('id', $this->categorias_id)->pluck("nombre");
		return $cat->implode(", ", $cat);
    }
    public function imagen()
    {
        $files =glob(public_path().'/img/users/'. $this->id . "*");
        if($files)
            return $url = asset('img/users/'. $this->id. "." . pathinfo($files[0], PATHINFO_EXTENSION));
        else
            return $url = asset('/img/user.jpg');
    }
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function Categorias()
    {
        return CategoriasTickets::wherein('id', $this->categorias_id)->get();
    }
	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| ACCESORS
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
}

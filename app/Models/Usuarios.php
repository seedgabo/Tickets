<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Usuarios extends Model
{
	use CrudTrait;

	protected $table = 'users';
	protected $primaryKey = 'id';
	protected $fillable = ["nombre","email","categorias_id","admin"];

    protected $casts = [
        'categorias_id' => 'array',
		'admin' => 'boolean'
    ];



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


	public function getCategorias()
    {
        return CategoriasTickets::wherein('id', $this->categorias_id)->get();
    }

}

<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Alerta extends Model
{
	use CrudTrait;

    /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'alertas';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	// protected $guarded = ['id'];
	protected $fillable = ['titulo','mensaje','correo','programado','emitido','usuarios', 'user_id'];
	// protected $hidden = [];
    protected $dates = ['programado'];
	protected $casts = [
	      'usuarios' => 'array',
	];

    public function emitir(){
    	$usuarios = $this->usuarios();
    	$mails = $usuarios->pluck("email")->toArray();
    	$titulo = $this->titulo;
    	$usuarios_ids = $usuarios->pluck("id")->toArray();
		$this->emitido = true;
    	$this->save();

    	Mail::raw($this->correo, function ($message) use($mails, $titulo){
			$message->to($mails);
			$message->subject($titulo);
		});
		\App\Models\Dispositivo::SendPush($titulo,$this->mensaje,$usuarios_ids);
    }

	public function getUsuariosText()
    {
        $usuarios = \App\User::wherein('id', $this->usuarios)->pluck("nombre");
		return $usuarios->implode(", ", $usuarios);
    }

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function user()
	{
		return $this->belongsTo("\App\User","user_id","id");
	}

	public function usuarios()
    {
        return \App\User::wherein('id', $this->usuarios)->get();
    }
	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
    public function scopePoremitir($query){
		return $query->where('programado', '<', Carbon::now())->where("emitido","=",false);
	}

    public function scopeEmitidos($query){
		return $query->where('emitido', '=', true);
	}

    public function scopeProximos($query, $cant){
		return $query->where('programado', '>', Carbon::now())->where("emitido","=", false)->orderBy("programado","asc")->limit($cant);
	}
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

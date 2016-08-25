<?php

namespace App\Models;

use App\Models\Tickets;
use App\Models\Documentos;
use App\User;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Auditorias extends Model
{
	use CrudTrait;

     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'auditorias';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	// protected $guarded = ['id'];
	protected $fillable = ['tipo','user_id','ticket_id','documento_id'];
	// protected $hidden = [];
    // protected $dates = [];

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function user()
	{
		return $this->hasOne('\App\Models\Usuarios','id','user_id');
	}
	public function ticket()
	{
		return $this->hasOne('\App\Models\Tickets','id','ticket_id');
	}
	public function documento()
	{
		return $this->hasOne('\App\Models\Documentos','id','documento_id');
	}
	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeDescargas($query, $documento_id)
    {
        return $query->where('tipo', '=', 'descarga')->where('documento_id',"=",$documento_id)->sum();
    }


	public function auditado()
	{	
		//Si es algo sobre un usuario
		if(isset($this->user_id))
		{
			// Si es algo sobre un ticket
			if(isset($this->ticket_id))
			{
				if($this->tipo == "Creación")
					return "El Usuario " . User::find($this->user_id)->nombre . " creó un Caso:" .Tickets::withTrashed()->find($this->ticket_id)->titulo;

				if($this->tipo == "Actualización")
					return "El Usuario " . User::find($this->user_id)->nombre . " Actualizó algun dato del Caso ". Tickets::withTrashed()->find($this->ticket_id)->titulo;

				if($this->tipo == "Eliminación")
					return "El Usuario " . User::find($this->user_id)->nombre . " Eliminó el Caso ".Tickets::withTrashed()->find($this->ticket_id)->titulo;
			}

			//Si es algo sobre un documento
			if(isset($this->documento_id))
			{
				return "El Usuario " . User::find($this->user_id)->nombre . " Descargó el documento ". Documentos::withTrashed()->find($this->documento_id)->titulo;
			}
		}
	}
}

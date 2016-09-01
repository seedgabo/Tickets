<?php

namespace App\Models;

use App\Models\Tickets;
use App\Models\Documentos;
use App\User;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auditorias extends Model
{
	use CrudTrait;


	protected $table = 'auditorias';
	protected $primaryKey = 'id';
	protected $fillable = ['tipo','user_id','ticket_id','documento_id'];

	// protected $hidden = [];
    // protected $dates = [];

	public function user()
	{
		return $this->hasOne('\App\Models\Usuarios','id','user_id');
	}
	public function ticket()
	{
		return $this->hasOne('\App\Models\Tickets','id','ticket_id')->withTrashed();
	}
	public function documento()
	{
		return $this->hasOne('\App\Models\Documentos','id','documento_id')->withTrashed();
	}

	public function objeto()
	{
		if (isset($this->ticket_id))
			return $this->ticket;
		else if (isset($this->documento_id))
			return $this->documento;

		$foo =  new \stdClass();
		$foo->titulo = "ninguno";
	  	return  $foo;
	}


	public function scopeDescargas($query, $documento_id)
    {
        return $query->where('tipo', '=', 'descarga')->where('documento_id',"=",$documento_id)->sum();
    }


	public function auditado()
	{	
		//Si es algo sobre un usuario
		if(isset($this->user_id))
		{
			if($this->tipo == "login")
				return "El Usuario " . $this->user->nombre . " inició sesión";
			// Si es algo sobre un ticket
			if(isset($this->ticket_id))
			{
				if($this->tipo == "Creación")
					return "El Usuario " . $this->user->nombre . " creó un Caso:" . $this->ticket->titulo;

				if($this->tipo == "Actualización")
					return "El Usuario " . $this->user->nombre . " Actualizó algun dato del Caso ". $this->ticket->titulo;

				if($this->tipo == "Eliminación")
					return "El Usuario " . $this->user->nombre . " Eliminó el Caso ". $this->ticket->titulo;

				if($this->tipo == "cambio de estado")
					return "El Usuario " . $this->user->nombre . " cambio el estado del Caso ". $this->ticket->titulo;

				if($this->tipo == "cambio de responsable")
					return "El Usuario " . $this->user->nombre . " cambio el responsable del Caso ". $this->ticket->titulo;

				if($this->tipo == "cambio de fecha de vencimiento")
					return "El Usuario " . $this->user->nombre . " cambio el vencimiento del Caso ". $this->ticket->titulo;
			}

			//Si es algo sobre un documento
			if(isset($this->documento_id))
			{
				return "El Usuario " . $this->user->nombre . " Descargó el documento ". $this->documento->titulo;
			}
		}
	}
}

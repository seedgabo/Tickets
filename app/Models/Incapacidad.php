<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
class Incapacidad extends Model
{
	use CrudTrait;
	use softDeletes;

    /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'incapacidades';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	// protected $guarded = ['id'];
	protected $fillable = ['paciente_id', 'caso_id', 'fecha_ingreso', 'fecha_incapacidad', 'fecha_liquidacion', 'entidad', 'eps_id', 'dias_incapacidad', 'prorroga', 'origen', 'cie10_id', 'sistema_afectado', 'caso_especial', 'medico_id', 'estado']; // 
	// protected $hidden = [];
    protected $dates = ['fecha_ingreso','fecha_incapacidad','fecha_liquidacion','deleted_at'];

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
	public function paciente(){
		return $this->belongsTo('App\Models\Paciente','paciente_id','id');
	}
	
	public function eps(){
		return $this->belongsTo('\App\Models\Eps','eps_id');
	}

	public function caso(){
		return $this->belongsTo('\App\Models\Casos_medicos','caso_id');
	}

	public function cie10(){
		return $this->belongsTo('\App\Models\Cie10','cie10_id');
	}

	public function medico(){
		return $this->belongsTo('\App\User','medico_id','id');
	}

	public function archivos(){
		return $this->belongsToMany('App\Models\Archivos','archivos_incapacidades', 'incapacidad_id','archivo_id');
	}


    public function getButtonVerPaciente(){
		if($this->paciente_id != null && $this->paciente_id != "")
      		return '<a href="'. url('admin/ver-paciente/'. $this->paciente_id) .'" class="btn btn-xs btn-info"><i class="fa fa-user-md"></i> Ver paciente </a>';
    }

    public function getButtonVerCaso(){
      if($this->caso_id != null && $this->caso_id != "")
      	return '<a href="'. url('admin\ver-caso\\' . $this->caso_id) .'" class="btn btn-xs btn-info"><i class="fa fa-file-code-o"></i> Ver Caso </a>';
    }

    public function getButtonverIncapacidad(){
      return '<a href="'. url('admin\ver-incapacidad\\' . $this->id) .'" class="btn btn-xs btn-warning"><i class="fa fa-file-code-o"></i> Ver Detalles </a>';
    }

}

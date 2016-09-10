<?php

namespace App\Models;

use App\Funciones;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Casos_medicos extends Model
{
	use CrudTrait;
  use SoftDeletes;
     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/
  const  categoria_id_casos = 99;
  protected $table = 'casos_medicos';
  protected $primaryKey = 'id';
  protected $guarded = ['id'];
  protected $fillable = ['paciente_id','origen_del_caso','medico_id','descripcion','apertura','cierre','estado', 'ticket_id'];
  protected $dates = ['deleted_at','apertura','cierre'];
  protected $appends = ['dias_incapacidad_acumulados','titulo_caso','titulo_caso_fecha'];
  public function paciente(){
    return $this->belongsTo('\App\Models\Paciente','paciente_id','id');
  }

  public function medico(){
    return $this->belongsTo('\App\User','medico_id','id');
  }

  public function puesto(){
    return $this->belongsTo('\App\Models\Puesto','puesto_id','id');
  }

  public function recomendaciones(){
    return $this->hasMany('\App\Models\Recomendacion','caso_id');
  }

  public function incapacidades(){
    return $this->hasMany('\App\Models\Incapacidad','caso_id');
  }

  public function verBotonTicket(){
   if($this->ticket_id != null && $this->ticket_id != "")
    {
      return '<a href="'. url('ticket/ver/' . $this->ticket_id) . '" class="btn btn-sm btn-info"> Ver Seguimiento </a>';
    }
    else
    {
      return "No iniciado";
    }
  }
  
  public function getTituloCasoAttribute(){
    return  "Caso Médico de ". $this->paciente->full_name . " por " .  $this->medico->nombre;
  }  

  public function getTituloCasoFechaAttribute(){
    return  "Caso Médico de ". $this->paciente->full_name . " por " .  $this->medico->nombre  . " del " . $this->apertura->format('d/m/Y');
  }  
  public function getDiasIncapacidadAcumuladosAttribute(){
    $dias = 0;
     foreach ($this->incapacidades as $inc) {
       $dias +=  $inc->dias_incapacidad;
     }
    return $dias;
  }

  public function archivos(){
   return $this->belongsToMany('App\Models\Archivos','archivo_casos', 'caso_id','archivo_id');
  }



    public function getButtonVerPaciente(){
     return '<a href="'. url('admin\pacientes?paciente_id='. $this->paciente_id) .'" class="btn btn-xs btn-info"><i class="fa fa-user-md"></i> Ver paciente </a>';
    }

    public function getButtonVerCaso(){
     return '<a href="'. url('admin\ver-caso\\' . $this->id) .'" class="btn btn-xs btn-warning"><i class="fa fa-file-code-o"></i> Ver Detalles </a>';
    }

    public function getButtonnAgregarRecomendacion()
    {
     return '<a href="'. url('admin/recomendaciones/create?caso_id='. $this->id) . '" class="btn btn-xs btn-warning">Agregar Recomendación</a>';
    }

    public function transApertura(){
     return Funciones::transdate($this->apertura);
    }
}

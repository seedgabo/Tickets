<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Paciente extends Model
{
	use CrudTrait;
  use softDeletes;
  
  protected $table = 'pacientes';
  protected $primaryKey = 'id';
  protected $fillable = ['nombres','apellidos','cedula','nacimiento','sexo','email','telefono','direccion','nota','foto','cargo','departamento','eps_id','arl_id','fondosDePensiones_id','ibc', 'user_id','puesto_id','ingreso','egreso', 'estadoCivil'];

  protected $appends = ['foto_url','full_name','full_name_cedula'];

  protected $dates = ['deleted_at','ingreso','', 'nacimiento'];

   public function setIngresoAttribute($value)
   {
        if (strlen($value)) {
                $this->attributes['ingreso'] = Carbon::Parse($value);
        } else {
            $this->attributes['ingreso'] = null;
        }
    }

   public function setEgresoAttribute($value)
   {
        if (strlen($value)) {
                $this->attributes['egreso'] = Carbon::parse($value);
        } else {
            $this->attributes['egreso'] = null;
        }
    }

  public function getFullNameAttribute($value)
  {
    return $this->nombres  . " " . $this->apellidos;
  }

  public function getFullNameCedulaAttribute($value)
  {
    return $this->nombres  . " " . $this->apellidos . " - " . $this->cedula;
  }

  public function getFotoUrlAttribute($value)
  {
   if ($this->foto != null && $this->foto != "")
   {
     return asset("/img/pacientes/" . $this->foto);
   }
   else
   {
    return $url = asset('/img/user.jpg');
  }
  }

  public function eps()
  {
   return $this->belongsTo('\App\Models\Eps','eps_id');
  }

  public function arl()
  {
   return $this->belongsTo('\App\Models\Arl','arl_id');
  }

  public function fondo()
  {
   return $this->belongsTo('\App\Models\FondosDePensiones','fondosDePensiones_id');
  }

  public function casos()
  {
   return $this->hasMany('\App\Models\Casos_medicos');
  }

  public function medico()
  {
    return $this->belongsTo('\App\User','user_id','id');
  }

  public function puesto()
  {
    return $this->belongsTo('\App\Models\Puesto','puesto_id','id');
  }

  public function archivos()
  {
   return $this->belongsToMany('App\Models\Archivos','archivo_paciente', 'paciente_id','archivo_id');
  }

  public function incapacidades()
  {
    return $this->hasMany('\App\Models\Incapacidad','paciente_id');
  }


  public function getButtonVerCasos()
  {
    return '<a href="'. url('admin\casos-medicos?paciente_id='. $this->id) .'" class="btn btn-xs btn-info"><i class="fa fa-file-text"></i> Ver Casos </a>';
  }

  public function getButtonVerPaciente()
  {
    return '<a href="'. url('admin\ver-paciente\\' . $this->id) .'" class="btn btn-xs btn-warning"><i class="fa fa-user-md"></i> Ver Detalles </a>';
  }
}

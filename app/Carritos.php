<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carritos extends Model
{
    protected $table = 'carritos';
    public $timestamps = false;
    
    protected $fillable = [
        'NOM_REF','user_id','empresa_id','transaccion','estado','COD_REF','cantidad','VAL_REF', 'nombre_cliente','COD_CLI','COD_VEN', 'fecha'
    ];

    public static function CarritoActual()
    {
    	return Carritos::where('user_id',Auth::user()->id)
    	->where("empresa_id",Session::get('empresa'))
    	->where("COD_CLI",Session::get('cliente'))
        ->where("estado", "0")
    	->get();
    }
}

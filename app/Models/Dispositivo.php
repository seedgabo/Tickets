<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Davibennun\LaravelPushNotification\Facades\PushNotification;
use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
	use CrudTrait;

     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'dispositivos';
	protected $primaryKey = 'id';
	// public $timestamps = false;
	// protected $guarded = ['id'];
	protected $fillable = ['token','dispositivo','plataforma','activo','user_id'];
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
		return $this->belongsTo("\App\User","user_id","id");
	}

	public function scopeActivo($query){
		return $query->where('activo', '=', '1');
	}
	
	public function scopeAndroid($query){
		return $query->where('plataforma', '=', 'android');
	}

	public function scopeIos($query){
		return $query->where('plataforma', '=', 'ios');
	}

	public static function SendPush($titulo,$mensaje,$usuarios_ids)
	{
		$dispositivos = \App\Models\Dispositivo::whereIn('user_id',$usuarios_ids)->android()->activo()->get();
	    $disp = [];

	    foreach ($dispositivos as $dispositivo) {
	        $disp[]= PushNotification::Device($dispositivo->token);
	    }
	    $devices = PushNotification::DeviceCollection($disp);
	    $message = PushNotification::Message($mensaje,[
	        'badge' => 1,
	        'image' => 'www/img/logo.png',
	        'title' => $titulo,
	    ]);

	    $collection = PushNotification::app('android')->to($devices)->send($message);
	}
}

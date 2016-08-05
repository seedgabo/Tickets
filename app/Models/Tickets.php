<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tickets extends Model
{

    public $table = "tickets";

	protected $dates = ['deleted_at'];


    public $fillable = [
        "titulo",
		"contenido",
		"user_id",
        "guardian_id",
		"estado",
		"categoria_id",
		"archivo",
        "vencimiento",
        "transferible",
        "created_at"
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "titulo" => "string",
		"contenido" => "string",
        "user_id" => "string",
        "guardian_id" => "string",
		"estado" => "string",
		"categoria_id" => "string",
		"archivo" => "string",
        "transferible" => "string",
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        "titulo" => "required|min:3|max:50",
		"contenido" => "required|min:3",
        "user_id" => "required",
        "guardian_id" => "required",
		"estado" => "required",
		"categoria_id" => "required",
		"archivo" => "unique:tickets"
    ];


    public static function byCategorias($categorias)
    {
        $tickets = Tickets::all();
        $permitidas = [];
        foreach ($tickets as $ticket)
        {
             if (in_array($ticket->categoria_id  ,$categorias->id))
                $permitidas[] =  $ticket;
         }
        return $permitidas;
    }

    public function archivo()
    {
        return asset("archivos/tickets/". $this->archivo);
    }


    public function categoria()
    {
        return $this->belongsTo('App\Models\CategoriasTickets', 'categoria_id', 'id');
    }

    public function guardian(){
        return $this->belongsTo("\App\User","guardian_id");
    }

    public function user(){
        return $this->belongsTo("\App\User","user_id");
    }
}

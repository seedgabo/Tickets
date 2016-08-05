<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ComentariosTickets extends Model
{

    public $table = "comentarios_tickets";

	protected $dates = ['deleted_at'];


    public $fillable = [
        "texto",
		"user_id",
        "ticket_id",
        "archivo"
    ];
    protected $casts = [
        "texto" => "string",
		"user_id" => "integer",
        "ticket_id" => "integer",
        "archivo" => "string"
    ];

    public static $rules = [
        "texto" => "required|min:8",
		"user_id" => "required",
        "ticket_id" => "required",
        "archivo"  => "image|max:20000"
    ];

    public function archivo()
    {
        return  asset("archivos/ComentariosTickets/" . $this->id . "." . explode(".",$this->archivo)[1]);
    }

}

<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ComentariosTickets extends Model
{
    use SoftDeletes;

    public $table = "comentarios_tickets";

    protected $dates = ['deleted_at'];


    public $fillable = [
        "texto",
        "user_id",
        "ticket_id",
        "archivo",
        "encriptado",
    ];

    protected $casts = [
        "texto" => "string",
        "user_id" => "integer",
        "ticket_id" => "integer",
        "archivo" => "string",
        "encriptado" => "boolean",
        "clave" => "string"
    ];

    public static $rules = [
        "texto" => "min:8",
        "user_id" => "",
        "ticket_id" => "",
        "archivo"  => "max:80000"
    ];

    public function file()
    {
        if($this->encriptado == true)
        {
            return url("getEncryptedFile/comentario/" . $this->id);
        }
        else
        {
            return  asset("archivos/ComentariosTickets/" . $this->id . "." . explode(".", $this->archivo)[1]);
        }
    }

}

<?php

namespace App\Models;

use App\User;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class CategoriasTickets extends Model
{

    public $table = "categorias_tickets";

	protected $dates = ['deleted_at'];


    public $fillable = [
        "id",
        "nombre",
		"descripción",
    ];

   
    protected $casts = [
        "nombre" => "string",
    		"descripción" => "string",
    		"user_id" => "array"
        ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
            "nombre" => "required|min:3|max:50",
    		"descripción" => "min:3",
        ];

    public function tickets()
    {
        return $this->hasMany('App\Models\Tickets',"categoria_id");
    }

    public function guardian()
    {
        Return $this->belongsTo('App\User',"guardian_id");
    }

    public function users()
    {
        $users = User::where("categorias_id", "LIKE", '%"'. $this->id. '"%')->get();
        return $users;
    }

}

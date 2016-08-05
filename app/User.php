<?php

namespace App;

use App\Empresas;
use App\Models\CategoriasTickets;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'email','categorias_id' ,'admin'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

      protected $casts = [
        'categorias_id' => 'array',
    ];


    public function Categorias()
    {
        return CategoriasTickets::wherein('id', $this->categorias_id)->get();
    }

    public function imagen()
    {
        $files =glob(public_path().'/img/users/'. $this->id . "*");
        if($files)
            return $url = asset('img/users/'. $this->id. "." . pathinfo($files[0], PATHINFO_EXTENSION));
        else
            return $url = asset('/img/user.jpg');
    }
}

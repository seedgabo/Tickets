<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Empresas extends Model
{
    protected $table = 'empresas';


       protected $fillable =
       [
        'nombre','emails','num_ped', 'cartera_global', 'precio_global','clientes_global'
        ];


      protected $casts =
      [
        'emails' => 'array',
        'cartera_global' => 'boolean',
        'clientes_global' => 'boolean',
        'precio_global' => 'boolean'
      ];

      public  function imagen()
      {
          if(File::exists(public_path().'/img/empresas/' .$this->id .".jpg"))
              return $url = asset('img/empresas/'. $this->id .".jpg");

          if(File::exists(public_path().'/img/empresas/' .$this->id .".png"))
              return $url = asset('img/empresas/'. $this->id .".png");

          else
              return $url = asset("img/nodisponible.jpg");
      }

}

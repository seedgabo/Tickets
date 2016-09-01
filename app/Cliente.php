<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    public $timestamps = true;

       protected $fillable =
       [
        'id',
        'COD_TER',
        'NOM_TER',
        'DIR1',
        'TEL1',
        'EMAIL',
        'TIP_PRV',
        'NIT',
        'COD_VEN',
        'empresa_id'
      ];

      protected $hidden =
      [
          'created_at',
          'updated_at'
      ];


}

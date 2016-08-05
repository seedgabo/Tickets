<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cartera extends Model
{
    protected $table = 'cartera';
    public $timestamps = false;

       protected $fillable =
       [
        'COD_TRN',
        'NUM_TRN',
        'COD_TER',
        'NOM_TER',
        'FEC_DOC',
        'FEC_VEN',
        'VALOR',
        'SIN_VEN',
        'A130',
        'A3160',
        'A6190',
        'A91120',
        'MAS120',
        'SALDO',
        'dias',
        'COD_VEN',
        'empresa_id'
        ];

        protected $hidden =
        [
            'created_at',
            'updated_at'
        ];


}

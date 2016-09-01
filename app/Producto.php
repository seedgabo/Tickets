<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    public $timestamps = false;

       protected $fillable =
       [
        'id',
        'COD_REF',
        'NOM_REF',
        'COD_TIP',
        'VAL_REF',
        'EXISTENCIA',
        'SALD_PED',
        'empresa_id'
       ];

       protected $hidden =
       [
           'created_at',
           'updated_at'
       ];


}

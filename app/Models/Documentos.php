<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\CrudTrait;
class documentos extends Model
{
    use SoftDeletes;
    use CrudTrait;

    public $table = 'documentos';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'titulo',
        'descripcion',
        'version',
        'editable',
        'categoria',
        "activo",
        "archivo"
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'titulo' => 'string',
        'descripcion' => 'string',
        'descripcion' => 'string',
        'categorias' => 'string',
        'version' => 'integer',
        'editable' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'titulo' => 'required|min:3|max:50|text',
        'descripcion' => 'min:3|max:50|text'
    ];


    public function getActivoText(){
        return $this->activo == 1 ? 'Activo' : '<span style="color:red">Inactivo</span>';
    }
    public function getEditableText(){
        return $this->editable == 1 ? 'Editable' : '<span style="color:red">Protegido</span>';
    }

    public function getLinkArchivo(){
        return "<a href='" . url('getDocumento/'. $this->id). "'> Ver Archivo </a>";
    }
}

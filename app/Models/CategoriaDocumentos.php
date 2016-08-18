<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class CategoriaDocumentos extends Model
{
	use CrudTrait;

	protected $table = 'categorias_documentos';
	protected $primaryKey = 'id';
	protected $fillable = ['id','nombre','parent_id'];


	public function parent()
    {
        return $this->belongsTo(get_class(), 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(get_class(), 'parent_id');
    }

	public function documentos()
	{
		return $this->hasMany("\App\Models\Documenots","categoria_id","id");
	}


	public static function ordered_menu($array,$parent_id = 0)
	{
		$temp_array = array();
		foreach($array as $element)
		{
			if($element['parent_id']==$parent_id)
			{
				$element['subs'] = static::ordered_menu($array,$element['id']);
				$temp_array[] = $element;
			}
		}
		return $temp_array;
	}

	public static function radio_menu($array,$parent_id = 0 ,$value= [], $disabled = [], $required = false)
	{
		$menu_html = '<ul class="fa-ul">';
		foreach($array as $element)
		{
			if($element['parent_id']==$parent_id)
			{
				$menu_html .= '<li><input type="radio" value="'.$element['id'] .'" name="parent_id" id="'.$element['nombre'] .'" ';
				if(in_array($element['id'],$disabled))  $menu_html .= " disabled ";
				if($required)  $menu_html .= " required ";
				if(in_array($element['id'],$value))  $menu_html .= " checked ";
				$menu_html .= '><label for="'.$element['nombre'].'"> <i class="fa fa-folder"></i> '. $element['nombre'].'</label>';
				$menu_html .= static::radio_menu($array,$element['id'], $value, $disabled ,$required);
				$menu_html .= '</li>';
			}
		}
		$menu_html .= '</ul>';
		return $menu_html;
	}

	public static function radio_menu_documentos($array ,$parent_id = 0 ,$value= [], $disabled = [], $required = false)
	{
		$menu_html = '<ul class="fa-ul">';
		foreach($array as $element)
		{
			if($element['parent_id']==$parent_id)
			{
				$menu_html .= '<li><input type="radio" value="'.$element['id'] .'" name="categoria_id" id="'.$element['nombre'] .'" ';
				if(in_array($element['id'],$disabled))  $menu_html .= " disabled ";
				if($required)  $menu_html .= " required ";
				if(in_array($element['id'],$value))  $menu_html .= " checked ";
				$menu_html .= '><label for="'.$element['nombre'].'> <i class="fa fa-folder"></i> '. $element['nombre'].'</label>';
				$menu_html .= static::radio_menu_documentos($array,$element['id'], $value, $disabled ,$required);
				$menu_html .= '</li>';
			}
		}
		$menu_html .= '</ul>';
		return $menu_html;
	}

	public static function menu($array,$parent_id = 0)
	{
		$menu_html = '<ol class="fa-ul"';
		if($parent_id == 0) $menu_html .= 'id="accordion"';
		$menu_html .= '>';
		foreach($array as $element)
		{
			if($element['parent_id']==$parent_id)
			{
				$menu_html .= '<li> <i class="fa fa-folder fa-li" style="color:blue;"></i> '. $element['nombre'];
				$menu_html .= static::menu($array,$element['id']);
				$menu_html .= static::menu_documentos($element['id']);
				$menu_html .= '</li>';
			}
		}
		$menu_html .= '</ol>';
		return $menu_html;
	}

	public static function menu_documentos($categoria_id = null)
	{
		$documentos = \App\Models\Documentos::where("categoria_id","=",$categoria_id)->pluck("titulo","id");
		$menu = "<ol class='fa-ul'>";
		foreach ($documentos as $key => $value)
		{
			$menu .= "<li><i class='fa fa-file fa-li' style='color:red;'></i> ". $value ."</li>";
		}
		return $menu ."</ol>";
	}

}

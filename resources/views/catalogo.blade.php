@extends('layouts.app')

@section('header')
 <link rel="stylesheet" href="{{asset('css/lightbox.min.css')}}">
@stop
@section('content')
	<div class="container">
		<div class="well">
			{{ Form::open(['method' => 'GET', 'class' => 'form-inline' , 'id' => "busquedaForm"]) }}
             <label for="clave"> Filtrar:</label>
			   <div class="form-group">
				   <div class="col-xs-9">
					    <select class="form-control" name="clave">
		   		    		<option value="NOM_REF">Nombre</option>
		   		    		<option value="COD_REF">Codigo</option>
		   		    		<option value="VAL_REF">Precio</option>
		   		    	</select>
				   </div>
			   </div>

			    <div class="form-group @if($errors->first('NOM_REF')) has-error @endif">
			        	{{ Form::text('valor',null, ['class' => 'form-control']) }}
			        	<small class="text-danger">{{ $errors->first('NOM_REF') }}</small>
			    </div>
               <div class="form-group{{ $errors->has('orden') ? ' has-error' : '' }}">
                   {!! Form::label('orden', 'Ordenar Por:', ['class' => 'control-label']) !!}
                       {!! Form::select('orden', ["NOM_REF" => 'Nombre', "COD_REF" => "Codigo", "VAL_REF" => "Precio"], Input::get('orden', null), ['class' => 'form-control']) !!}
                       <small class="text-danger">{{ $errors->first('orden') }}</small>
               </div>
               <div class="form-group{{ $errors->has('orden_dir') ? ' has-error' : '' }}">
                   {!! Form::label('orden_dir', 'Dirección:', ['class' => 'control-label']) !!}
                       {!! Form::select('orden_dir', ["asc" => 'Ascendente', "desc" => "Descendente"], Input::get('orden_dir', null), ['class' => 'form-control']) !!}
                       <small class="text-danger">{{ $errors->first('orden_dir') }}</small>
               </div>
			<div class="checkbox checkbox-primary pull-right">
			    <input type="checkbox" id="checkbox1"  @if(isset($_COOKIE['imagenes']) && $_COOKIE['imagenes'] == "true" ) checked  @endif>
			    <label for="checkbox1" style="font-size:16px;">
			        Mostrar Imagenes
			    </label>
			  </div>
               <br> <span>|</span>
	 		<div class="pull-right">
               <div class="form-group{{ $errors->has('cod_tip') ? ' has-error' : '' }} ">
                   {!! Form::label('cod_tip', 'Líneas:', ['class' => 'control-label']) !!}
                       {!! Form::select('cod_tip', ['' => 'Seleccionar:'] +  $lineas, Input::get('cod_tip', null), ['class' => 'form-control' ,  'onchange' =>"this.form.submit()"]) !!}
                       <small class="text-danger">{{ $errors->first('cod_tip') }}</small>
               </div>
               <div class="form-group{{ $errors->has('cod_gru') ? ' has-error' : '' }} ">
                   {!! Form::label('cod_gru', 'Grupos:', ['class' => 'control-label']) !!}
                       {!! Form::select('cod_gru', ['' => 'Seleccionar:'] + $grupos, Input::get('cod_gru', null), ['class' => 'form-control' , 'onchange' =>"this.form.submit()"]) !!}
                       <small class="text-danger">{{ $errors->first('cod_gru') }}</small>
               </div>
			        {{ Form::submit("Buscar", ['class' => 'btn btn-primary ']) }}
			</div>

			{{ Form::close() }}
		</div>


		<div class="text-center">
			{!! $productos->
				appends(['orden' => Input::get('orden'), 'orden_dir' => Input::get("orden_dir"), 'cod_gru' => Input::get("cod_gru"), 'cod_tip' => Input::get("cod_tip")])
				->render() !!}
		</div>

		<?php $i=0; ?>
		@forelse ($productos as $producto)
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="well text-center hover" width="100%">
					<a href="{{ App\Funciones::getUrlProducto($producto) }}" data-lightbox='roadtrip' data-title='{{$producto->NOM_REF}}'>
					<img class="lazy" data-original="{{ App\Funciones::getUrlProducto($producto) }}" height="250px"  width="100%" alt="Imagen no Disponible" 
					@if(!isset($_COOKIE['imagenes']) || $_COOKIE['imagenes'] == "false" ) 
						style="display:none"  
					@endif >
					</a>
					<h5>{{str_limit($producto->NOM_REF,30)}}</h5>
					<p>{{number_format($producto->VAL_REF,2,",",".") }} $</p>
					{!! Form::open(['method' => 'POST', 'url' => url('ajax/addCarrito'), 'class' => 'row form-inline form-product']) !!}
						<input id="{{$i}}" type="hidden" class="form-control input-sm" name="VAL_REF"
						value="{{$producto->VAL_REF}}" step="1">
						<input type="hidden" name="COD_REF" value="{{$producto->COD_REF}}">
						<input type="hidden" name="NOM_REF" value="{{$producto->NOM_REF}}">
						<input type="hidden" name="COD_CLI" value="{{Session::get('cliente')}}">
						<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
						<input type="hidden" name="COD_VEN" value="{{Auth::user()->cod_vendedor}}">
						<input type="hidden" name="empresa_id" value="{{$empresa->id}}">
						<input class="form-control input-sm" type="number" name="cantidad" value="0" min="1">
						<br><br>
						<input class="btn btn-sm btn-info" type="submit" value="Agregar al Carrito">
					</form>
					<small>{{$producto->COD_REF}}</small>
					<p class="text-right">
						<small>
                        @if($empresa->cantidad_global)

							Existencia: 
							{{-- @if(intval($producto->EXISTENCIA) > 50)  --}}
							{{-- Disponible  --}}
							{{-- @else  --}}
							{{$producto->EXISTENCIA}} 
							{{-- @endif  --}}
							<br>
							Pedidos: {{$producto->SALD_PED}} <br>
                        @endif
                        @if($empresa->precio_global)
							<button onclick="$('#{{$i++}}').attr('type','number'); $(this).hide()" class="btn btn-xs"> Editar precio </button>
                        @endif
						</small>
					</p>
				</div>
			</div>
		@empty
			No Hay Productos
		@endforelse

		<div class="text-center">
			{!! $productos->
				appends(['orden' => Input::get('orden'), 'orden_dir' => Input::get("orden_dir"), 'cod_gru' => Input::get("cod_gru"), 'cod_tip' => Input::get("cod_tip")])
				->render() !!}
		</div>
	</div>
    <script src="{{asset('js/jquery.lazyload.min.js')}}"></script>
    <script src="{{asset('js/js-cookie.js')}}"></script>
	<script>
		$(document).ready(function() {
            $('.form-product').ajaxForm(function(data) {
            	$.toast({
            		heading: 'Producto '+ data +' Agregado al Carrito ',
            		text: "<br><br><a href='{!!url('/carrito')!!}'><h3> ir al  <i class='fa fa-cart-plus'> Carrito </h3></a>",
            		showHideTransition: 'slide',
            		icon: 'success',
            		position: 'top-center',
    				stack: false
            	})
            });

            $("img.lazy").lazyload({
                effect : "fadeIn"
            });

            $("#checkbox1").change(function(){
                $("img.lazy").toggle('fast');
                if(Cookies.get('imagenes') == 'true'){
                        Cookies.set('imagenes', 'false');
                }
                else{
                    Cookies.set('imagenes', 'true');
                }
            });
        });
	</script>
@stop

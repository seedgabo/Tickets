@extends('layouts.app')

@section('content')
<div class="">
<ul class="list-group">
	<li class="list-group-item  row">
	    <div class="col-md-3">Nombre</div>
		<div class="col-md-3">Email</div>
		<div class="col-md-3">Categorias</div>
		<div class="col-md-3">Acciones</div>
	</li>
	@forelse ($usuarios as $usuario)
	<li class="list-group-item row">
		<div class="col-md-3">
			{{$usuario->nombre}}
		</div>
		<div class="col-md-3">{{$usuario->email}}</div>
		<div class="col-md-3">
			{{ implode($usuario->categorias()->pluck("nombre")->toArray(), ", ")}}
		</div>
		<div class="col-md-3">
			<a href="{{url('Usuarios/'. $usuario->id)}}" class="btn btn-info btn-xs"> Editar</a>
			@if ($usuario->admin != 1 or $usuario->id != Auth::user()->id)
			<a href="{{url('Usuarios/delete/'. $usuario->id)}}" class="btn btn-danger btn-xs"> Eliminar</a>
			@endif
		</div>
	</li>
	@empty
		{{-- empty expr --}}
	@endforelse
</ul>
   <a class="btn btn-success pull-right" href="{{url('Usuarios/create')}}"><i class="fa fa-plus"></i> Crear Nuevo</a>
</div>
@stop

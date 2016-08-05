@extends('layouts.app')

@section('content')
<div class="">
	 {!! Form::open(['route' => ['Usuarios.store'], 'method' => 'POST', 'class' => 'form-horizontal col-md-6 col-md-offset-3 well']) !!}

	 	<div class="form-group @if($errors->first('nombre')) has-error @endif">
	 	    {!! Form::label('nombre', 'Nombre') !!}
	 	    {!! Form::text('nombre', null, ['class' => 'form-control', 'required' => 'required']) !!}
	 	    <small class="text-danger">{{ $errors->first('nombre') }}</small>
	 	</div>

	 	<div class="form-group @if($errors->first('email')) has-error @endif">
	 	    {!! Form::label('email', 'Email') !!}
	 	    {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'eg: foo@bar.com']) !!}
	 	    <small class="text-danger">{{ $errors->first('email') }}</small>
	 	</div>

	 	<div class="form-group @if($errors->first('categorias_id')) has-error @endif">
	 	    {!! Form::label('email', 'Categorias') !!}
	 	    {!! Form::select('categorias_id[]', App\Models\CategoriasTickets::lists("nombre","id") , null ,['class' => 'form-control chosen', 'multiple' => 'multiple']) !!}
	 	    <small class="text-danger">{{ $errors->first('categorias_id') }}</small>
	 	</div>

		<div class="form-group @if($errors->first('password')) has-error @endif">
	 	    {!! Form::label('password', 'Contraseña') !!}
	 	    {!! Form::password('password', ['class' => 'form-control']) !!}
	 	    <small class="text-danger">{{ $errors->first('password') }}</small>
	 	</div>


	   <div class="form-group">
	       <div class="checkbox @if($errors->first('admin')) has-error @endif">
	           <label for="admin">
	               {!! Form::checkbox('admin', '1', null, ['id' => 'admin']) !!} Administrador
	           </label>
	       </div>
	       <small class="text-danger">{{ $errors->first('admin') }}</small>
	   </div>
	   
	 	<div class="btn-group pull-right">
	 		{!! Form::submit("Guardar", ['class' => 'btn btn-success']) !!}
	 	</div>

	 {!! Form::close() !!}
</div>
@stop

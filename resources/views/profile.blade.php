@extends('layouts.app')
@section('content')
	<div class="">
		{!! Form::model($user, ['method' => 'PUT', 'class' => 'form-horizontal', 'files' => true]) !!}
			<fieldset>
				<legend>Datos</legend>
			 <div class="text-left">
			 	<img src="{{$user->imagen() }}" height="80px" alt="usuario Imagen" class="img-circle">
			 </div>
			<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
			    {!! Form::label('nombre', 'Nombre:', ['class' => 'col-sm-3 control-label']) !!}
				<div class="col-sm-9">
			    	{!! Form::text('nombre', null, ['class' => 'form-control', 'required' => 'required']) !!}
			    	<small class="text-danger">{{ $errors->first('nombre') }}</small>
				</div>
			</div>
			<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
			    {!! Form::label('email', 'Email', ['class' =>'col-sm-3 control-label']) !!}
			    <div class="col-sm-9">
			    	{!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
			    	<small class="text-danger">{{ $errors->first('email') }}</small>
				</div>
			</div>

			<div class="form-group{{ $errors->has('photo') ? ' has-error' : '' }}">
			    {!! Form::label('photo', 'Imagen de Perfil', ['class' => 'col-sm-3 control-label']) !!}
			    <div class="col-sm-9">
				    {!! Form::file('photo', ['accept' => '.jpg,.png']) !!}
				    <p class="help-block">Solo imagenes menores a 1mb</p>
				    <small class="text-danger">{{ $errors->first('photo') }}</small>
			    </div>
			</div>

			</fieldset>

			<fieldset>
				<legend>Cambiar Clave:</legend>

				<div class="form-group{{ $errors->has('oldpassword') ? ' has-error' : '' }}">
				    {!! Form::label('oldpassword', 'Contraseña Antigua', ['class' => 'col-sm-3 control-label']) !!}
				    <div class="col-sm-9">
				        {!! Form::password('oldpassword', ['class' => 'form-control']) !!}
				        <small class="text-danger">{{ $errors->first('oldpassword') }}</small>
					</div>
				</div>

				<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
				    {!! Form::label('password', 'Contraseña nueva:', ['class' => 'col-sm-3 control-label']) !!}
				    <div class="col-sm-9">
				        {!! Form::password('password', ['class' => 'form-control']) !!}
				        <small class="text-danger">{{ $errors->first('password') }}</small>
					</div>
				</div>
				<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
				    {!! Form::label('password_confirm', 'Confirmar contraseña:', ['class' => 'col-sm-3 control-label']) !!}
				    <div class="col-sm-9">
				        {!! Form::password('password_confirm', ['class' => 'form-control']) !!}
				        <small class="text-danger">{{ $errors->first('password_confirm') }}</small>
					</div>
				</div>
			</fieldset>


		{!! Form::submit("Guardar", ['class' => 'btn btn-block btn-primary']) !!}
		{!! Form::close() !!}

		<br>
		<a href="" class="btn btn-block btn-success"> <i class="fa fa-android fa-lg"></i> Obtener para android</a>
	</div>
@stop

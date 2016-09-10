@extends('layouts.app')

@section('content')
	{!! Form::open(['method' => 'POST', 'route' => 'tickets.store', 'class' => 'form-horizontal col-md-10 col-md-offset-1' ,'id' => 'nuevoTicket', 'files'=>true]) !!}

	<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
	<input type="hidden" name="estado" value="abierto">

	{{-- Titulo --}}
	<div class="form-group @if($errors->first('titulo')) has-error @endif">
		{!! Form::label('titulo', 'Titulo') !!}
		{!! Form::text('titulo', null, ['class' => 'form-control', 'required' => 'required']) !!}
		<small class="text-danger">{{ $errors->first('titulo') }}</small>
	</div>
	
	{{-- Contenido --}}
	<div class="form-group @if($errors->first('contenido')) has-error @endif">
		{!! Form::label('contenido', 'Contenido') !!}
		{!! Form::textarea('contenido', null, ['class' => 'form-control ckeditor', 'required' => 'required', 'id' =>'textarea']) !!}
		<small class="text-danger">{{ $errors->first('contenido') }}</small>
	</div>
		
	{{-- Categoria --}}
	<div class="form-group @if($errors->first('categoria_id')) has-error @endif">
		{!! Form::label('categoria_id', 'Categoria') !!}
		{!! Form::select('categoria_id',\App\Models\Categorias::all()->lists("nombre","id"), null, ['id' => 'categoria', 'class' => 'form-control chosen', 'required' => 'required']) !!}
		<small class="text-danger">{{ $errors->first('categoria_id') }}</small>
	</div>

	{{-- Guardian --}}
	<div class="form-group @if($errors->first('guardian_id')) has-error @endif">
		{!! Form::label('guardian_id', 'Asignar a: (Responsable)') !!}
		{!! Form::select('guardian_id',\App\User::lists("nombre","id"), null, ['id' => 'guardian_id', 'class' => 'form-control chosen depdrop', 'required' => 'required']) !!}
		<small class="text-danger">{{ $errors->first('guardian_id') }}</small>
	</div>	
	<script>
		$("#guardian_id").depdrop({
			depends: ['categoria'],
			url: '{{url('ajax/getUsersbyCategoria')}}',
			placeholder: false
		});
		$('#guardian_id').on('depdrop.change', function(event, id, value, count) {
			$('.chosen').chosen('destroy').chosen();
		});
	</script>

	{{-- Vencimiento --}}
	<div class="form-group @if($errors->first('vencimiento')) has-error @endif">
		{!! Form::label('vencimiento', 'Fecha de Expiración') !!}
		{!! Form::text('vencimiento', null, ['class' => 'form-control datetimepicker', 'required' => 'required']) !!}
		<small class="text-danger">{{ $errors->first('vencimiento') }}</small>
		<a href="#!" onclick="$('.datetimepicker').val('')">No vence</a>
	</div>
	
	 <a href="#!" class="toggleOptions btn btn-primary btn-sm"> Mas Opciones</a>
	<div id="masOpciones" style="display: none">

		{{-- Transerible --}}
		<div class="form-group @if($errors->first('transferible')) has-error @endif">
			{!! Form::label('transferible', '¿Este caso es transferible?', ['class' => 'col-sm-3 control-label']) !!}
			<div class="col-sm-9">
				{!! Form::select('transferible',[1=>"Si",0=> "No"], 1, ['id' => 'transferible', 'class' => 'form-control']) !!}
				<small class="text-danger">{{ $errors->first('transferible') }}</small>
			</div>
		</div>


				
		{{-- canSetVencimiento --}}
		<div class="form-group{{ $errors->has('canSetVencimiento') ? ' has-error' : '' }}">
		    {!! Form::label('canSetVencimiento', 'El Responsable puede cambiar la  fecha de vencimiento?') !!}
		    {!! Form::select('canSetVencimiento',['0' => 'No', '1' => 'Si'], null, ['id' => 'canSetVencimiento', 'class' => 'form-control', 'required' => 'required']) !!}
		    <small class="text-danger">{{ $errors->first('canSetVencimiento') }}</small>
		</div>

			{{-- canSetGuardian --}}
		<div class="form-group{{ $errors->has('canSetGuardian') ? ' has-error' : '' }}">
		    {!! Form::label('canSetGuardian', '¿El Responsable puede asignar este caso a otra persona?') !!}
		    {!! Form::select('canSetGuardian',['0' => 'No', '1' => 'Si'], null, ['id' => 'canSetGuardian', 'class' => 'form-control', 'required' => 'required']) !!}
		    <small class="text-danger">{{ $errors->first('canSetGuardian') }}</small>
		</div>

		{{-- canSetEstado --}}
		<div class="form-group{{ $errors->has('canSetEstado') ? ' has-error' : '' }}">
		    {!! Form::label('canSetEstado', '¿EL Responsable puede cambiar el estado del caso?') !!}
		    {!! Form::select('canSetEstado',['0' => 'No', '1' => 'Si'], null, ['id' => 'canSetEstado', 'class' => 'form-control', 'required' => 'required']) !!}
		    <small class="text-danger">{{ $errors->first('canSetEstado') }}</small>
		</div>

		{{-- Archivo --}}
		<div class="form-group @if($errors->first('archivo')) has-error @endif">
			{!! Form::label('archivo', 'Archivo') !!}
			{!! Form::file('archivo', ["class" => "file-bootstrap"]) !!}
			<p class="help-block">El archivo debe pesar menos de 10Mb, solo documentos, imagenes y archivos comprimidos estan permitidos</p>
			<small class="text-danger">{{ $errors->first('archivo') }}</small>
		</div>

		{{-- Encriptado --}}
		<div class="form-group">
			<div class="checkbox{{ $errors->has('encriptado') ? ' has-error' : '' }}">
				<label for="encriptado">
					{!! Form::checkbox('encriptado','true', false, ['id' => 'encriptado']) !!} Encriptar Archivo
				</label>
			</div>
			<small class="text-danger">{{ $errors->first('encriptado') }}</small>
		</div>
		
		{{-- Clave de Encriptación --}}
		<div class="form-group{{ $errors->has('clave') ? ' has-error' : '' }}">
			{!! Form::label('clave', 'Clave de Encriptación', ['class' => 'col-sm-3 control-label']) !!}
			<div class="col-sm-9">
				{!! Form::password('clave', null, ['class' => 'form-control']) !!}
				<small class="text-danger">{{ $errors->first('clave') }}</small>
			</div>
		</div>

	</div>

    {!! Form::submit("Agregar Caso", ['class' => 'btn btn-success pull-right' , 'form' => 'nuevoTicket']) !!}

	{!! Form::close() !!}

	<script>
		$(document).ready(function() {
		  $('.chosen').chosen();
			$(".file-bootstrap").fileinput({
		        maxFileSize: 10000,
				showUpload: false,
		        browseClass: "btn btn-default",
		        browseLabel: "Subir",
		        browseIcon: "<i class=\"glyphicon glyphicon-upload\"></i> ",
		        removeClass: "btn btn-danger",
		        removeLabel: "",
		        removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
		        uploadClass: "btn btn-info",
			});
			$('.toggleOptions').click(function(){
				$('#masOpciones').toggle('fast');
			});
		});
	</script>
@stop
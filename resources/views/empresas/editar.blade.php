@extends('layouts.app')

@section('content')

<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/css/fileinput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput_locale_es.min.js"></script>
<div class="container">

	 {!! Form::model($empresa, ['route' => ['Empresa.update', $empresa->id], 'method' => 'PUT', 'class' => 'form-horizontal col-md-6 col-md-offset-3 well', 'files' => true]) !!}

	 	<div class="form-group @if($errors->first('nombre')) has-error @endif">
	 	    {!! Form::label('nombre', 'Nombre de la Empresa') !!}
	 	    {!! Form::text('nombre', null, ['class' => 'form-control', 'required' => 'required']) !!}
	 	    <p class="help-block">Nombre de la Empresa</p>
	 	    <small class="text-danger">{{ $errors->first('nombre') }}</small>
	 	</div>


	 	<div class="form-group @if($errors->first('emails')) has-error @endif">
	 	    {!! Form::label('emails', 'Emails para el envio de información') !!}
	 	    {!! Form::text('emails', implode(",",$empresa->emails), ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'eg: foo@bar.com']) !!}
	 	    <p class="help-block">Dirección de correo  a los que se desea enviar un email cada vez que se realize una transacción <br>
	 	     <span class="text-danger">Correos deben estar separados por comas y sin espacios</span></p>
	 	    <small class="text-danger">{{ $errors->first('emails') }}</small>
	 	</div>

		<div class="form-group">
		    <div class="col-sm-9">
		        <div class="checkbox">
		            <label for="cartera_global">
		                {!! Form::checkbox('cartera_global', true , null, ['id' => 'cartera_global']) !!} Mostrar cartera completa a usuarios
		            </label>
		        </div>
		        <small class="text-danger">{{ $errors->first('cartera_global') }}</small>
		    </div>
		</div>

		<div class="form-group">
		    <div class="col-sm-9">
		        <div class="checkbox">
		            <label for="clientes_global">
		                {!! Form::checkbox('clientes_global',true, null, ['id' => 'clientes_global']) !!} Mostrar todos los clientes a usuarios
		            </label>
		        </div>
		        <small class="text-danger">{{ $errors->first('clientes_global') }}</small>
		    </div>
		</div>

		<div class="form-group">
		    <div class="col-sm-9">
		        <div class="checkbox">
		            <label for="precio_global">
		                {!! Form::checkbox('precio_global',true, null, ['id' => 'precio_global']) !!} Permitir editar el precio en el catalogo
		            </label>
		        </div>
		        <small class="text-danger">{{ $errors->first('precio_global') }}</small>
		    </div>
		</div>

		<div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
		    {!! Form::label('image', 'Imagen de Empresa', ['class' => 'col-sm-3 control-label']) !!}
		        <div class="col-sm-9">
		            {!! Form::file('image', ['accept' => 'image/*']) !!}
		            <small class="text-danger">{{ $errors->first('image') }}</small>
		        </div>
		</div>

		<div class="form-group">
		    <div class="col-sm-9">
		        <div class="checkbox">
		            <label for="cantidad_global">
		                {!! Form::checkbox('cantidad_global',true, null, ['id' => 'cantidad_global']) !!} Mostrar la cantidad en el catalogo
		            </label>
		        </div>
		        <small class="text-danger">{{ $errors->first('cantidad_global') }}</small>
		    </div>
		</div>


	 	<div class="btn-group pull-right">

	 		{!! Form::submit("Guardar", ['class' => 'btn btn-success']) !!}
	 	</div>

	 {!! Form::close() !!}


	 {!! Form::open(['method' => 'POST', 'url' => 'upload/cargarImagenes/' . $empresa->id, 'class' => 'form-horizontal col-md-6 col-md-offset-3 well' , 'files' => true]) !!}

		<div class="form-group{{ $errors->has('imagenes[]') ? ' has-error' : '' }}">
		    {!! Form::label('imagenes', 'Imagenes de productos', ['class' => 'col-sm-3 control-label']) !!}
		    <div class="col-sm-9">
			    {!! Form::file('imagenes', ['required' => 'required', "multiple" , "class" => "file-bootstrap", 'accept' => '.jpg,.zip']) !!}
			    <p class="help-block">Solo subir imagenes jpg, o archivos .zip que contengan las imagenes
				<span class="text-danger"> el nombre del archivo debe coincidir con el codigo del producto</span>
			    </p>
			    <small class="text-danger">{{ $errors->first('imagenes') }}</small>
			  {!! Form::submit('enviar') !!}
		    </div>
		</div>
	 {!! Form::close() !!}

	<script>
	 	$(".file-bootstrap").fileinput({
		        maxFileSize: 2000000,
		        languageS: 'es',
				showUpload: true,
		        dropZoneTitle : "Suelte aqui: .jpg o .zip",
		        browseClass: "btn btn-info",
		        browseLabel: "Agregar",
		        browseIcon: "<i class=\"glyphicon glyphicon-upload\"></i> ",
		        removeClass: "btn btn-danger",
		        removeLabel: "",
		        removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
		        uploadClass: "btn btn-info",
		        uploadLabel: "Subir",
		        uploadIcon: "<i class=\"glyphicon glyphicon-upload\"></i> ",
		        uploadUrl: "{{url('upload/cargarImagenes/'. $empresa->id)}}",
			    uploadAsync: true,
			    maxFileCount: 200
			}).on("filebatchselected", function(event, files) {
			    $(".file-bootstrap").fileinput("upload");
			});
	</script>
</div>
@stop

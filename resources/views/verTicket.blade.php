@extends('layouts.app')
@section('content')
<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/css/fileinput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput_locale_es.min.js"></script>

<div class="">
<div class="text-center">
	<ol class="breadcrumb">
		<li>
			<a href="{{url('ticket')}}">Tickets Abiertos</a>
		</li>

		<li>
			<a href="{{url('mis-tickets')}}">mis Tickets</a>
		</li>

		<li> <a href="{{url('tickets/todos')}}">Todos los Tickets </a></li>

		<li class="active">{{$ticket->titulo}}</li>
	</ol>
</div>
	<div class="col-md-12">
		<div class="panel panel-primary hover">
		   <div style="text-transform: uppercase;" class="panel-heading text-center">
	   		<p class="">{{$ticket->titulo}}
	    	<span class="label label-warning pull-right">{!! $ticket->categoria->nombre !!}</span>
			 @if(Auth::user()->id == $ticket->guardian_id || Auth::user()->id == $ticket->user_id)
				<a data-toggle="modal" href='#modal-editar' class="label label-primary pull-right">
					<i class="fa fa-edit"></i> Editar
				</a>
			@endif
	   		</p>
		   </div>
			<div class="panel-body">
				{!! $ticket->contenido !!}
				@if ($ticket->archivo!= '' || $ticket->archivo != null)
					<h4 class="text-right">					
					<a  @if($ticket->encriptado == "true") onclick="verArchivo('{{$ticket->archivo()}}')" @else href="{{$ticket->archivo()}}" @endif>
						@if($ticket->encriptado == "true")<i class="fa fa-lock"></i> @endif {{$ticket->archivo}}
					</a></h4>
				@endif
			</div>
			<div class="panel-footer">
			<div class="container-fluid">
				@if (Auth::user()->id == $ticket->guardian_id || Auth::user()->id == $ticket->user_id)
				<div class="col-md-3 form-inline">
					{!! Form::label('estado', 'Estado:') !!}
	    			{!! Form::select('estado', ['abierto' => 'abierto', 'completado' => 'completado', 'en curso' => 'en curso', ' rechazado' => ' rechazado'], $ticket->estado, ['id'=> 'estado','class' => 'form-control chosen', 'onChange' => "cambiarEstado($ticket->id , this.value)"]) !!}
				</div>
				@if ($ticket->transferible == 1 || $ticket->guardian_id == Auth::user()->id)
				<div class="col-md-4 form-inline">
					{!! Form::label('guardian', 'Responsable:') !!}
	    			{!! Form::select('guardian',$ticket->categoria->users()->lists("nombre","id"), $ticket->guardian_id, ['id'=> 'estado','class' => 'form-control chosen', 'onChange' => "cambiarGuardian($ticket->id , this.value)"]) !!}
				</div>
				@endif
				@endif
				@if(Auth::user()->id == $ticket->user_id)
				<div class="col-md-3 form-inline row">
					{!! Form::label('vencimiento', 'Vencimiento:') !!}
					{!! Form::text('vencimiento', $ticket->vencimiento,['id'=> 'vencimiento','class' => 'form-control pre datetimepicker', 'onblur' => "cambiarVencimiento($ticket->id , this.value)"]) !!}
					<button type="button" class="btn">Cambiar</button>
				</div>
				@endif
				<p class="text-right"><span class="text-info">Creado por:</span> {{ $ticket->user->nombre }}</p>
				<p class="text-right"><span class="text-info">Asignado a:</span> {{ $ticket->guardian->nombre }}</p>
				<small style="color:red">Vence el: {{$ticket->vencimiento ? \App\Funciones::transdate($ticket->vencimiento) : "No Vence"}}</small> <br>
			</div>
			</div>
		</div>
	</div>
	
	<h2 class="text-center"> Seguimiento</h2>
	<div class="col-md-12 well hover" >
		<div class="list-group" style="overflow-y: scroll; max-height: 400px;">
			@forelse ($comentarios as $comentario)
			<div class="list-group-item">
				{!!$comentario->texto!!}
				<p class="text-right">
				{{$comentario->user->nombre}}
				<img src="{{$comentario->user->imagen()}}" alt="" class="img-circle" height="35px">
				@if (Auth::user()->id == $comentario->user_id)
				 <a class="btn btn-danger btn-xs" href="{{url('ajax/deleteComentarioTicket/'.$comentario->id)}}" title="Borrar Comentario" onclick="return confirm('¿Esta seguro de que quiere eliminar este comentario?')"><i class="fa fa-trash"></i></a>
				@endif
				<br> {{\App\Funciones::transdate($comentario->created_at)}}
				@if (isset($comentario->archivo) && $comentario->archivo != "")
					<br>
					<a  @if($comentario->encriptado == "true") onclick="verArchivo('{{$comentario->file()}}')" @else href="{{$comentario->file()}}" @endif>
						@if($comentario->encriptado == "true")<i class="fa fa-lock"></i> @endif {{$comentario->archivo}}
					</a>
				@endif
				</p>
			</div>
			<hr>
			@empty
				Agregar su Seguimiento<br>
			@endforelse
		</div>

		{!! Form::open(['method' => 'POST', 'url' => 'ajax/addComentarioTicket', 'class' => 'form-horizontal form-comentario', 'id' => 'form-comentario', "files" => "true" , 'novalidate']) !!}
			<input type="hidden" name="comentario[ticket_id]" value="{{$ticket->id}}">
			<input type="hidden" name="comentario[user_id]" value="{{Auth::user()->id}}">
			<textarea rows="3" required="required" minlength="8" class="form-control" name="comentario[texto]" placeholder="agrega aqui el seguimiento"></textarea>

				<button type="button" onclick="masOpciones();" class="btn btn-xs btn-primary">Mas Opciones</button>
			<div id="input-avanced" style="display:none">

				<div class="form-group">
				    <div class="col-sm-offset-2 col-sm-9">
				        <div class="checkbox @if($errors->first('notificacion')) has-error @endif">
				            <label for="notificacion">
				                {!! Form::checkbox('notificacion', 'true', true, ['id' => 'notificacion']) !!} Enviar Correo
				            </label>
				        </div>
				        <small class="text-danger">{{ $errors->first('notificacion') }}</small>
				    </div>
				</div>

				<div class="form-group @if($errors->first('emails[]')) has-error @endif">
				    {!! Form::label('emails[]', 'Enviar a', ['class' => 'col-sm-4 control-label']) !!}
				    <div class="col-sm-8">
				    	{!! Form::select('emails[]',$ticket->categoria->users()->lists("nombre","id"),[$ticket->user_id,$ticket->guardian_id], ['id' => 'emails[]', 'class' => 'form-control chosen', 'required' => 'required', 'multiple']) !!}
				    	<small class="text-danger">{{ $errors->first('emails[]') }}</small>
					</div>
				</div>

				<div class="form-group @if($errors->first('archivo')) has-error @endif">
				    <div class="col-sm-12">
					    {!! Form::file('archivo',["class"=>"file-bootstrap", "accept" =>".xlsx,.xls,image/*,.doc, .docx.,.ppt, .pptx,.txt,.pdf,.zip,.rar"]) !!}
					    <p class="help-block">Solo imagenes, menores a 10Mb</p>
					    <small class="text-danger">{{ $errors->first('archivo') }}</small>
				    </div>
				</div>

				<div class="form-group ">
				    <div class="checkbox{{ $errors->has('encriptado') ? ' has-error' : '' }} col-sm-offset-2">
				        <label for="encriptado">
				            {!! Form::checkbox('encriptado','true',false, ['id' => 'encriptado']) !!} <b> Encriptar Archivo </b>
				        </label>
				    </div>
				    <small class="text-danger">{{ $errors->first('encriptado') }}</small>
				</div>
				<div class="form-group{{ $errors->has('clave') ? ' has-error' : '' }}">
				    {!! Form::label('clave', 'Clave de Encriptacion', ['class' => 'col-sm-3 control-label']) !!}
					<div class="col-sm-9">
				    	{!! Form::password('clave', null, ['class' => 'form-control']) !!}
				    	<small class="text-danger">{{ $errors->first('clave') }}</small>
					</div>
				</div>

			</div>


			<br>
			<div class="text-right">
	        	{!! Form::submit("Enviar", ['class' => 'btn btn-success']) !!}
			</div>
		{!! Form::close() !!}
	</div>
 

	 <div class="modal fade" id="modal-editar">
		 <div class="modal-dialog modal-lg">
			 <div class="modal-content">
				 <div class="modal-header">
					 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					 <h4 class="modal-title">Editar Contenido del Ticket</h4>
				 </div>
				 <div class="modal-body">
				 <div class="row">
					{!! Form::model($ticket, ['url' => url('editar-ticket/' . $ticket->id), 'method' => 'PUT', 'class' => 'form-horizontal col-md-10 col-md-offset-1', 'id' => 'editar-ticket']) !!}
					
					    <div class="form-group{{ $errors->has('contenido') ? ' has-error' : '' }}">
					        {!! Form::label('contenido', 'Contenido') !!}
					        {!! Form::textarea('contenido', null, ['class' => 'form-control', 'required' => 'required', 'id' => 'textarea']) !!}
					        <small class="text-danger">{{ $errors->first('contenido') }}</small>
					    </div>
					
					
					{!! Form::close() !!}
				 </div>
				 </div>
				 <div class="modal-footer">
					 <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					 {!! Form::submit("Agregar", ['class' => 'btn btn-success' , 'form' => 'editar-ticket']) !!}
				 </div>
			 </div>
		 </div>
	 </div>
 

	<script>
		function cambiarEstado(id, estado)
		{
			$.post("{{url('ajax/setEstadoTicket/')}}" +"/" + id, {estado: estado},
			function(data)
			{
				$.toast({
            		heading: 'Hecho',
            		text: "Estado Actualizado",
            		showHideTransition: 'slide',
            		icon: 'success',
            		position: 'mid-center',
            	})
				$("#estado").val(data);
				location.reload(true); 
			})
		}

		function cambiarVencimiento (id, vencimiento)
		{
			if(vencimiento != "{{$ticket->vencimiento}}")
				$.post("{{url('ajax/setVencimiento/')}}" +"/" + id, {vencimiento: vencimiento},
					function(data)
					{
						$.toast({
							heading: 'Hecho',
							text: "Vencimiento Actualizado",
							showHideTransition: 'slide',
							icon: 'success',
							position: 'mid-center',
						})
						location.reload(true); 
					})	
		}

		function cambiarGuardian(id, guardian_id)
		{
			$.post("{{url('ajax/setGuardianTicket/')}}" +"/" + id, {guardian_id: guardian_id},
				function(data)
				{
					$.toast({
	            		heading: 'Hecho',
	            		text: "Guardian Transferido",
	            		showHideTransition: 'slide',
	            		icon: 'success',
	            		position: 'mid-center',
    			});
					location.reload(true); 
    		})
		}

		function masOpciones()
		{
			$('#input-avanced').fadeToggle('slow');
			$('.chosen').chosen('destroy').chosen();
		}

		function verArchivo(url)
		{
			var clave =  prompt("Ingrese la Contraseña");
			if (clave)
				window.location = url + "/" +clave;
			else
				alert("debe ingresar una clave valida");
		}

		$(document).ready(function() {
			// $('#form-comentario').submit(function(event) {
			// 	$.toast({
	  //           		heading: '<h3 class="text-center">Enviando Comentario <br> <i class="fa fa-spinner fa-pulse"></i></h3>',
	  //           		text: '',
	  //           		showHideTransition: 'slide',
	  //           		icon: 'success',
	  //           		position: 'mid-center',
   //          	})
			// })

		    $(".file-bootstrap").fileinput({
		        maxFileSize: 10000,
				showUpload: false,
		        browseClass: "btn btn-success",
		        browseLabel: "Cargar Archivo",
		        browseIcon: "<i class=\"glyphicon glyphicon-upload\"></i> ",
				previewFileType: "image",
		        browseClass: "btn btn-success",
		        browseLabel: "Cargar Archivo",
		        browseIcon: "<i class=\"glyphicon glyphicon-files\"></i> ",
		        removeClass: "btn btn-danger",
		        removeLabel: "",
		        removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
		        uploadClass: "btn btn-info",
			});
		});

	</script>
</div>
@stop

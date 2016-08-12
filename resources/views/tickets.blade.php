@extends('layouts.app')
@section('content')
	<div class="text-right">
		<a class="btn btn-primary" data-toggle="modal" href='#modal-ticket'><i class="fa fa-plus"></i> Crear un ticket</a>
		<hr>
	</div>
	<div class="table-responsive">
		<table class="table table-hover datatable">
			<thead>
				<tr>
					<th>Ticket</th>
					<th>Categoría</th>
					<th>Estado</th>
					{{-- <th>contenido</th> --}}
					<th>Usuario</th>
					<th>Asignado a</th>
					<th>Creado el</th>
					<th>Vence el</th>
				</tr>
			</thead>
			<tbody>
			 @forelse ($tickets as $ticket)
				<tr class="@if($ticket->estado == "completado") success @endif @if($ticket->estado == "rechazado") danger @endif @if($ticket->estado == "en curso") info @endif @if($ticket->estado == "abierto") warning @endif">
					<td>
						<a class="btn btn-link" style="text-transform: uppercase;" href="{{url("ticket/ver/".$ticket->id)}}">
						{{$ticket->titulo}}
						<span class="badge label-success">{{App\Models\ComentariosTickets::where("ticket_id",$ticket->id)->count()}}</span>
						</a>
						@if ($ticket->user_id == Auth::user()->id)
							<a class="btn text-right btn-xs btn-danger" onclick="return confirm('esta seguro de que desea eliminar este ticket?')" href="{{url("ticket/eliminar/".$ticket->id)}}"> <i class="fa fa-trash"></i></a>
						@endif
					</td>
					<td>{{\App\Models\categoriasTickets::find($ticket->categoria_id)->nombre}}</td>
					<td>{{$ticket->estado}}</td>
					{{-- <td>{!! str_limit($ticket->contenido,50) !!}</td> --}}
					<td>{{ \App\User::find($ticket->user_id)->nombre}}</td>
					<td>{{ \App\User::find($ticket->guardian_id)->nombre}}</td>
					<td>{{ App\Funciones::transdate($ticket->created_at)}}</td>
					<td>{{ $ticket->vencimiento  ?  \App\Funciones::transdate($ticket->vencimiento) : "No Vence"}}</td>
				</tr>
			 @empty
			 	Ningún ticket existente
			 @endforelse
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					{{-- <th></th> --}} 
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
	</div>
	</div>

	<div class="modal fade" id="modal-ticket">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="fa fa-ticket"></i> Nuevo Ticket</h4>
				</div>
				<div class="modal-body">
				<div class="row">

				{!! Form::open(['method' => 'POST', 'route' => 'tickets.store', 'class' => 'form-horizontal col-md-10 col-md-offset-1' ,'id' => 'nuevoTicket', 'files'=>true]) !!}
					<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
					<input type="hidden" name="estado" value="abierto">
				    <div class="form-group @if($errors->first('titulo')) has-error @endif">
				        {!! Form::label('titulo', 'Titulo') !!}
				        {!! Form::text('titulo', null, ['class' => 'form-control', 'required' => 'required']) !!}
				        <small class="text-danger">{{ $errors->first('titulo') }}</small>
				    </div>

				    <div class="form-group @if($errors->first('contenido')) has-error @endif">
				        {!! Form::label('contenido', 'Contenido') !!}
				        {!! Form::textarea('contenido', null, ['class' => 'form-control', 'required' => 'required', 'id' =>'textarea']) !!}
				        <small class="text-danger">{{ $errors->first('contenido') }}</small>
				    </div>

				    <div class="form-group @if($errors->first('categoria_id')) has-error @endif">
				        {!! Form::label('categoria_id', 'Categoria') !!}
				        {!! Form::select('categoria_id',Auth::user()->categorias()->lists("nombre","id"), null, ['id' => 'categoria', 'class' => 'form-control chosen', 'required' => 'required']) !!}
				        <small class="text-danger">{{ $errors->first('categoria_id') }}</small>
				    </div>

				    <div class="form-group @if($errors->first('guardian_id')) has-error @endif">
				        {!! Form::label('guardian_id', 'Asignar a:') !!}
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

				    <div class="form-group @if($errors->first('transferible')) has-error @endif">
				        {!! Form::label('transferible', 'Transerible?', ['class' => 'col-sm-3 control-label']) !!}
				        <div class="col-sm-9">
				        	{!! Form::select('transferible',[1=>"Si",0=> "No"], 1, ['id' => 'transferible', 'class' => 'form-control chosen']) !!}
				        	<small class="text-danger">{{ $errors->first('transferible') }}</small>
				    	</div>
				    </div>

				    <div class="form-group @if($errors->first('vencimiento')) has-error @endif">
				        {!! Form::label('vencimiento', 'Fecha de Expiración') !!}
				        {!! Form::text('vencimiento', null, ['class' => 'form-control datetimepicker', 'required' => 'required']) !!}
				        <small class="text-danger">{{ $errors->first('vencimiento') }}</small>
				        <a href="#!" onclick="$('.datetimepicker').val('')">No vence</a>
				    </div>

				    <div class="form-group @if($errors->first('archivo')) has-error @endif">
				        {!! Form::label('archivo', 'Archivo') !!}
				        {!! Form::file('archivo', ["class" => "file-bootstrap"]) !!}
				        <p class="help-block">El archivo debe pesar menos de 10Mb, solo documentos, imagenes y archivos comprimidos estan permitidos</p>
				        <small class="text-danger">{{ $errors->first('archivo') }}</small>
				    </div>
				    <div class="form-group">
				        <div class="checkbox{{ $errors->has('encriptado') ? ' has-error' : '' }}">
				            <label for="encriptado">
				                {!! Form::checkbox('encriptado','true', false, ['id' => 'encriptado']) !!} Encriptar Archivo
				            </label>
				        </div>
				        <small class="text-danger">{{ $errors->first('encriptado') }}</small>
				    </div>

				    <div class="form-group{{ $errors->has('clave') ? ' has-error' : '' }}">
				        {!! Form::label('clave', 'Clave de Encriptación', ['class' => 'col-sm-3 control-label']) !!}
				    	<div class="col-sm-9">
				        	{!! Form::text('clave', null, ['class' => 'form-control']) !!}
				        	<small class="text-danger">{{ $errors->first('clave') }}</small>
				    	</div>
				    </div>


				{!! Form::close() !!}

				</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			        {!! Form::submit("Agregar", ['class' => 'btn btn-success' , 'form' => 'nuevoTicket']) !!}
				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			$('#modal-ticket').on('shown.bs.modal', function () {
			  $('.chosen').chosen('destroy').chosen();
			});
			$(".file-bootstrap").fileinput({
		        maxFileSize: 10000,
				showUpload: false,
		        browseClass: "btn btn-success",
		        browseLabel: "Agregar",
		        browseIcon: "<i class=\"glyphicon glyphicon-upload\"></i> ",
		        removeClass: "btn btn-danger",
		        removeLabel: "",
		        removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
		        uploadClass: "btn btn-info",
			});

				$('#modal-ticket').on('shown.bs.modal', function () {
				  $('.chosen').chosen('destroy').chosen();
				});
		});
	</script>
@stop

@extends('layouts.app')
@section('content')
	<div class="text-right">
		<a class="btn btn-primary" data-toggle="modal" href='{{url('agregar-ticket')}}'><i class="fa fa-plus"></i> Crear un Caso</a>
		<hr>
	</div>
	@if(isset($subcategorias) && sizeof($subcategorias) != 0)
		<h3 class="text-center text-primary">SubCategorias</h3>
		<div class="list-group">
			@forelse ($subcategorias as $categoria)
			<a class="list-group-item" href="{{url('tickets/categoria/'. $categoria->id)}}">
				<span class="badge">{{$categoria->tickets->count()}}</span>
				{{ $categoria->nombre }}
			</a>
			@empty
			@endforelse
		</div>
		<hr>
		<h3 class="text-center text-primary">Casos</h3>
	@endif
	<div class="well">
		{!! Form::open(['method' => 'GET',  'class' => 'form-inline']) !!}
		
		    <div class="form-group{{ $errors->has('desde') ? ' has-error' : '' }}">
		        {!! Form::label('desde', 'Desde:', ['class' => 'col-sm-3 control-label']) !!}
		        <div class="col-sm-9">
		        	{!! Form::date('desde',$desde, ['class' => 'form-control', 'required' => 'required']) !!}
		        	<small class="text-danger">{{ $errors->first('desde') }}</small>
		        </div>
		    </div>

		    <div class="form-group{{ $errors->has('hasta') ? ' has-error' : '' }}">
		        {!! Form::label('hasta', 'Hasta:') !!}
		        {!! Form::date('hasta',$hasta, ['class' => 'form-control', 'required' => 'required']) !!}
		        <small class="text-danger">{{ $errors->first('hasta') }}</small>
		    </div>
		
		    <div class="btn-group pull-right">
		        {!! Form::submit("Buscar", ['class' => 'btn btn-success']) !!}
		    </div>
		
		{!! Form::close() !!}
	</div>
	<div class="table-responsive">
		<table class="table table-hover datatable">
			<thead>
				<tr>
					<th>#</th>
					<th>Caso</th>
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
				<tr class="@if($ticket->estado == "completado") success @endif @if($ticket->estado == "rechazado") danger @endif @if($ticket->estado == "en curso") info @endif @if($ticket->estado == "abierto") warning @endif @if($ticket->estado == "vencido") vencido @endif">
					<td>{{$ticket->id}}</td>
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
			 	Ningún caso existente
			 @endforelse
			</tbody>
			<tfoot>
				<tr>
					<th></th>
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

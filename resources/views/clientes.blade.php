@extends('layouts.app')

@section('content')
	<div class="container">
	<a class="btn btn-primary pull-right" data-toggle="modal" href='#modal-cliente'><i class="fa fa-user-plus"></i> Agregar Cliente</a>
	<h3>Seleccione el Cliente:</h3>
	<div class="well">
			{{ Form::open(['method' => 'GET', 'class' => 'form-inline' , 'id' => "busquedaForm"]) }}
			   <div class="form-group">
				   <div class="col-xs-9">
					    <select class="form-control" name="clave">
		   		    		<option value="NOM_TER">Nombre</option>
		   		    		<option value="COD_TER">Codigo</option>
		   		    		<option value="EMAIL">Email</option>
		   		    		<option value="DIR1">Dirección</option>
		   		    		<option value="TEL1">Telefono</option>
		   		    	</select>
				   </div>
			   </div>

			    <div class="form-group @if($errors->first('valor')) has-error @endif">
			        <div class="col-xs-9">
			        	{{ Form::text('valor',null, ['class' => 'form-control']) }}
			        	<small class="text-danger">{{ $errors->first('valor') }}</small>
			    	</div>
			    </div>

			    <div class="form-group">
			        {{ Form::submit("Buscar", ['class' => 'btn btn-primary']) }}
			    </div>
			{{ Form::close() }}
		</div>
		<div class="list-group">
		<a class="list-group-item row">
				<span class="col-md-4 col-xs-12">Nombre</span>
				<span class="col-md-2 hidden-xs hidden-md">Codigo</span>
				<span class="col-md-2 hidden-xs hidden-md">Email</span>
				<span class="col-md-2 hidden-xs hidden-md">Direccion</span>
				<span class="col-md-2 hidden-xs hidden-md">Telefóno</span>
			</a>
		@forelse ($clientes as $cliente)
			<a href="{{url('ajax/setCliente/'.$cliente->COD_TER)}}" class="list-group-item row">
				<span class="col-md-4 col-xs-12">{{$cliente->NOM_TER}} </span>

				<span class="col-md-2 hidden-xs hidden-md">{{$cliente->COD_TER}} </span>
				<span class="col-md-2 hidden-xs hidden-md">{{$cliente->EMAIL}} </span>
				<span class="col-md-2 hidden-xs hidden-md">{{$cliente->DIR1}} </span>
				<span class="col-md-2 hidden-xs hidden-md">{{$cliente->TEL1}} </span>
			</a>
		@empty
			No hay Clientes
		@endforelse
		</div>
	</div>

		<div class="modal fade" id="modal-cliente">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Agregar nuevo cliente</h4>
					</div>
					<div class="modal-body">
					<div class="row">

						{!! Form::open(['method' => 'POST', 'url' => url("ajax/addCliente"), 'class' => 'form-horizontal col-md-10 col-md-offset-1', 'id' => "form-cliente"]) !!}
								<input type="hidden" name="TIP_PRV" value="1">
								<input type="hidden" name="ESTADO" value="1">
								<input type="hidden" name="LISTA_PREC" value="1">
								<input type="hidden" name="CLASIFIC" value="2">
							<div class="form-group @if($errors->first('NOM_TER')) has-error @endif">
							    {!! Form::label('NOM_TER', 'Nombre:') !!}
							    {!! Form::text('NOM_TER', null, ['class' => 'form-control', 'required' => 'required']) !!}
							    <small class="text-danger">{{ $errors->first('NOM_TER') }}</small>
							</div>

							<div class="form-group @if($errors->first('EMAIL')) has-error @endif">
							    {!! Form::label('EMAIL', 'Email:') !!}
							    {!! Form::email('EMAIL', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'eg: foo@bar.com']) !!}
							    <small class="text-danger">{{ $errors->first('EMAIL') }}</small>
							</div>

							<div class="form-group @if($errors->first('COD_TER')) has-error @endif">
							    {!! Form::label('COD_TER', 'Codigo:') !!}
							    {!! Form::text('COD_TER', null, ['class' => 'form-control', 'required' => 'required']) !!}
							    <small class="text-danger">{{ $errors->first('COD_TER') }}</small>
							</div>

							<div class="form-group @if($errors->first('DIR1')) has-error @endif">
							    {!! Form::label('DIR1', 'Dirección:') !!}
							    {!! Form::text('DIR1', null, ['class' => 'form-control', 'required' => 'required']) !!}
							    <small class="text-danger">{{ $errors->first('DIR1') }}</small>
							</div>

							<div class="form-group @if($errors->first('TEL1')) has-error @endif">
							    {!! Form::label('TEL1', 'Telefono:') !!}
							    {!! Form::text('TEL1', null, ['class' => 'form-control']) !!}
							    <small class="text-danger">{{ $errors->first('TEL1') }}</small>
							</div>

							<div class="form-group @if($errors->first('CIUDAD')) has-error @endif">
							    {!! Form::label('CIUDAD', 'Ciudad:') !!}
							    {!! Form::text('CIUDAD', null, ['class' => 'form-control', 'required' => 'required']) !!}
							    <small class="text-danger">{{ $errors->first('CIUDAD') }}</small>
							</div>


						{!! Form::close() !!}
					</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
						<input form="form-cliente" type="submit" value="Guardar" class="btn btn-primary"/>
					</div>
				</div>
			</div>
		</div>
@stop

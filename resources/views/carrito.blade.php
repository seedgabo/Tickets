@extends('layouts.app')
<?php $total=0; $cantidad_total=0; ?>
@section('content')
	<div class="container">
		<h3 class="col-md-6">{{$empresa->nombre}}</h3>
		<h3 class="col-md-6 text-right visible-md-block visible-lg-block">{{Auth::user()->nombre}}</h3>
		<div class="col-md-offset-10 col-md-2">
			<a class="btn btn-info"href="{{url('catalogo')}}"> Ir al catalogo </a>
		</div>
		<h2 class="text-center">CARRITO ACTUAL</h2>
		<h3 class="col-md-12">
			Cliente: {{$cliente  ? $cliente['NOM_TER']: 'No se ha seleccionado ningún cliente'}}
			<a class="btn btn-link" href="{{url('clientes')}}"> Seleccionar Cliente</a>
		</h3>
	


		<div class="table-responsive container">
			<table class="table datatable table-bordered table-condensed table-hover text-center">
				<thead>
					<tr>
						<th>Codigo</th>
						<th>Nombre</th>
						<th>Precio Unitario</th>
						<th>Cantidad</th>
						<th>Total</th>
						<th style="color:#2A4AA5; text-decoration: underline; ">Acciones</th>
					</tr>
				</thead>
				<tbody>
				@forelse ($carrito as $producto)
					<tr>
						<td>{{$producto->COD_REF}}</td>
						<td>{{$producto->NOM_REF}}</td>
						<td>{{number_format($producto->VAL_REF,2,",",".")}}</td>
						<td>{{$producto->cantidad}}</td>
						<?php $subtotal = $producto->VAL_REF * $producto->cantidad; $cantidad_total+= $producto->cantidad ?>
						<td>{{number_format($subtotal,2,",",".")}}</td>
						<?php   $total += $subtotal; ?>
						<td><a href="{{url('ajax/eliminarCarrito/' . $producto->id)}}" class="btn btn-sm btn-warning" type="button"><i class="fa fa-trash"></i> Eliminar</a></td>
					</tr>
				@empty
				</tbody>
				</table>
				<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>No hay nada en el Carrito</strong>  Agrega algunos Productos
				</div>
				@endforelse
				</tbody>
					@if (sizeof($carrito)>0)
					<thead>
					<tr>					
						<th></th>
						<th></th>
						<th></th>
						<th class="text-center">Cantidad: {{$cantidad_total}}</th>
						<th class="text-center">TOTAL: {{number_format($total,2,",",".")}} </th>
						<th class="text-center"><a href="{{url('ajax/eliminarCarrito/')}}" class="btn btn-danger btn-sm btn-block" type="button"><i class="fa fa-trash-o"></i> Limpiar Carrito </a></th>
					</tr>
					</thead>
					@endif
					<tfoot>
						<tr>
							<th></th>
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
	
		@if (sizeof($carrito)>0)
			<a href="{{url('ajax/procesarCarrito')}}" class="btn btn-success btn-lg"><i class="fa fa-cloud-upload"></i> Procesar Carrito</a>
		@endif
	</div>
@stop
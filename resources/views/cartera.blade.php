@extends('layouts.app')
@section('content')
	<div class="text-center">
		<ol class="breadcrumb">
			<li class="active">
				CARTERA
			</li>
		</ol>
	</div>
	<div class="table-responsive">
	<table class="table datatable table-hover" data-page-length="25">
		<thead>
			<tr>
				<!-- <th>Codigo</th> -->
				<th>Cliente</th>
				<th>De 1 a 30</th>
				<th>De 31 a 60</th>
				<th>De 61 a 90</th>
				<th>De 91 a 120</th>
				<th>Mas de 120</th>
				<th class="text-danger">Valor</th>
			</tr>
		</thead>
		<tbody>
		@forelse ($cartera as $elemento)
			<tr>
				{{-- <td>{{$elemento["COD_TER"]}}</td> --}}
				<td><a href="{{url('cartera/'. $elemento['COD_TER'])}}">{{$elemento["NOM_TER"]}} </a></td>
				<td @if($elemento["A130"]>0) class="text-danger"  @endif >{{number_format($elemento["A130"],0,",",".")}}</td>
				<td @if($elemento["A3160"]>0) class="text-danger"  @endif >{{number_format($elemento["A3160"],0,",",".")}}</td>
				<td @if($elemento["A6190"]>0) class="text-danger"  @endif >{{number_format($elemento["A6190"],0,",",".")}}</td>
				<td @if($elemento["A91120"]>0) class="text-danger"  @endif >{{number_format($elemento["A91120"],0,",",".")}}</td>
				<td @if($elemento["MAS120"]>0) class="text-danger"  @endif >{{number_format($elemento["MAS120"],0,",",".")}}</td>
				<td class="text-danger">{{number_format($elemento["TOTAL"],0,",",".")}}</td>
			</tr>
		@empty
			No hay datos en la cartera
		@endforelse
		</tbody>
		<tfoot>
			<tr>
			<th></th>
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
		<div class="text-right" >Total: {{number_format($total,"0",",",".")}} $</div>
@stop
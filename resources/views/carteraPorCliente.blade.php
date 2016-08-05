@extends('layouts.app')
@section('content')
<div class="text-center">
	<ol class="breadcrumb">
		<li>
			<a href="{{url('/cartera')}}">CARTERA</a>
		</li>
		<li class="active">{{$cartera[0]["NOM_TER"]}}</li>
	</ol>
</div>
<div class="table-responsive">
	<table class="table datatable table-hover table-bordered" data-page-length="25" width="100%">
		<thead>
			<tr>
				<th>Codigo</th>
				<th>Cliente</th>
				<th># Factura</th>
				<th>Fecha Documento</th>
				<th>Fecha Vencimiento</th>
				<th>Valor de  <br> Factura</th>
				<th class="text-success">De 1 a 30</th>
				<th class="text-success">De 31 a 60</th>
				<th class="text-success">De 61 a 90</th>
				<th class="text-success">De 91 a 120</th>
				<th class="text-success">mas de 120</th>
				<th class="text-danger">Total</th>
			</tr>
		</thead>
		<tbody>
		@forelse ($cartera as $elemento)
			<tr>
				<td>{{$elemento["COD_TER"]}}</td>
				<td>{{$elemento["NOM_TER"]}}</td>
				<td>{{$elemento["NUM_TRN"]}}</td>
				<td data-type="date">{{$elemento["FEC_DOC"]}}</td>
				<td data-type="date">{{ $elemento["FEC_VEN"]}}</td>
				<td >{{ number_format($elemento["VALOR"],0,",",".")}}</td>
				<td class="text-success">{{number_format($elemento["A130"],0,",",".")}}</td>
				<td class="text-success">{{number_format($elemento["A3160"],0,",",".")}}</td>
				<td class="text-success">{{number_format($elemento["A6190"],0,",",".")}}</td>
				<td class="text-success">{{number_format($elemento["A91120"],0,",",".")}}</td>
				<td class="text-success">{{number_format($elemento["MAS120"],0,",",".")}}</td>
				<td clasS="text-danger">{{number_format($elemento["SALDO"],0,",",".")}}</td>
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
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</tfoot>
			<div class="text-right" colspan="11">TOTAL: {{number_format($cartera->sum("SALDO"),0,",",".")}} $</div>
	</table>
</div>

@stop

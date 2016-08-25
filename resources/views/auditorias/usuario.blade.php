@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
         Auditar Usuario
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Auditar Usuario</li>
      </ol>
    </section>
@endsection


@section('content')
<div class="box-body">
{!! Form::open(['method' => 'GET', 'class' => 'form-inline']) !!}

    <div class="form-group{{ $errors->has('desde') ? ' has-error' : '' }}">
        {!! Form::label('desde', 'Desde:') !!}
        {!! Form::date('desde', $desde, ['class' => 'form-control', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('desde') }}</small>
    </div>

    <div class="form-group{{ $errors->has('hasta') ? ' has-error' : '' }}">
        {!! Form::label('hasta', 'Hasta:') !!}
        {!! Form::date('hasta',$hasta, ['class' => 'form-control', 'required' => 'required']) !!}
        <small class="text-danger">{{ $errors->first('hasta') }}</small>
    </div>

    <div class="form-group">
        {!! Form::submit("Auditar", ['class' => 'btn btn-success']) !!}
    </div>

{!! Form::close() !!}
    <div class="row">
        <div class="table table-bordered table-striped display">
            <table id="crudTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Actividad</th>
                        <th>Objeto</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($registros as $registro)
                        <tr>
                            <td>{{ $registro->user->nombre }}</td>
                            <td>{{ $registro->tipo }}</td>
                            <td>@if (isset($registro->ticket_id))
                                    Caso
                                @else
                                    Documento
                                @endif
                            </td>
                            <td>{{ \App\Funciones::transdate($registro->created_at) }}</td>
                            <td>{{ $registro->auditado() }}</td>
                        </tr> 
                    @empty
                        <h3>No Hay Registros</h3>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
@endsection



@section('after_scripts'){{--  --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/bs/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.11,b-1.1.2,b-colvis-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1/datatables.min.css"/>

    <script type="text/javascript" src="https://cdn.datatables.net/t/bs/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.11,b-1.1.2,b-colvis-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1/datatables.min.js"></script>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
            table =  $('#crudTable').DataTable({
                "language": {
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    },
                    "decimal": ",",
                    "thousands": ".",
                    buttons: {
                    'copy' : 'Copiar', 'excel' :'Exportar a Excel', 'pdf': 'Exportar a Pdf' ,'print' :'Imprimir', 'colvis':'Ver'
                    }
                },
                responsive: true,
                ordering: false,
                colReorder: true,
                dom: 'rlBtip',
                buttons: [
                    {
                        extend:    'copyHtml5',
                        text:      '<i class="fa fa-files-o text-info"></i>',
                        titleAttr: 'Copiar',
                        className: "btn btn-default",
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend:    'excelHtml5',
                        text:      '<i class="fa fa-file-excel-o text-success"></i>',
                        titleAttr: 'Excel',
                        className: "btn btn-default",
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend:    'print',
                        text:      '<i class="fa fa-print text-warning"></i>',
                        titleAttr: 'Imprimir',
                        className: "btn btn-default",                        
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend:    'pdfHtml5',
                        text:      '<i class="fa fa-file-pdf-o text-danger"></i>',
                        titleAttr: 'PDF',
                        className: "btn btn-default",
                        exportOptions: {
                            columns: ':visible'
                        },
                        orientation: 'landscape'
                    },
                    {
                        extend:    'colvis',
                        text:      '<i class="fa fa-eye text-primary"></i>',
                        className: "btn btn-default",
                        titleAttr: 'Mostrar/Ocultar Columnas'
                    }
                ]
            });
    });
    </script>
@endsection

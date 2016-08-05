@extends('layouts.app')

@section('content')

    <div class="">
        {!! Form::open(['method'=> 'GET', 'class' => 'form-horizontal']) !!}
        
            <div class="form-group @if($errors->first('busqueda')) has-error @endif">
                {!! Form::label('busqueda', 'Buscar', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-9">
                    {!! Form::text('busqueda', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">{{ $errors->first('busqueda') }}</small>
                </div>
            </div>

        {!! Form::close() !!}

        <h1 class="pull-left">Tickets</h1>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('tickets.create') !!}"> <i class="fa fa-plus"></i>  Agregar</a>

        <div class="clearfix"></div>

        <div class="clearfix"></div>

        @if($tickets->isEmpty())
            <div class="well text-center">Ningun Tickets Encontrado.</div>
        @else
            @include('tickets.table')
        @endif
        
    </div>
@endsection
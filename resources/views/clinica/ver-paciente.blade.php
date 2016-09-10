@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
         Paciente : {{$paciente->full_name}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
        <li><a href="{{ url('admin/pacientes') }}">Listado de pacientes</a></li>
        <li class="active">{{$paciente->full_name}}</li>
      </ol>
    </section>
@endsection


@section('content')
<div class="col-md-9">
    
    @include('clinica.partials.ficha-paciente', ['paciente' => $paciente])
    
    @include('clinica.partials.tabla-casos',['casos' => $paciente->casos])
    
</div>

<div class="col-md-3">
 @include('clinica.partials.tabla-documentos', ['documentos' => $paciente->archivos])
</div>

<div class="col-md-12">
  {!! Form::open(['method' => 'POST', 'url' => 'admin/cargar-archivo/paciente/'. $paciente->id, 'class' => 'form-inline', 'files' => true]) !!}
        <div class="form-group{{ $errors->has('archivo') ? ' has-error' : '' }} col-sm-4">
            {!! Form::label('archivo', 'agregar un Archivo:') !!}
            {!! Form::file('archivo', ['required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('archivo') }}</small>
        </div>
    
        <div class="form-group col-sm-3">
            {!! Form::submit("Subir", ['class' => 'btn btn-success']) !!}
        </div>    
    {!! Form::close() !!}
</div>

<style type="text/css" media="screen">
    .hover
    {
        -webkit-transition: .1s linear;
           -moz-transition: .1s linear;
            -ms-transition: .1s linear;
             -o-transition: .1s linear;
                transition: .1s linear;
    }
    .hover:hover
    {
        box-shadow: 5px 5px 20px #D4D4D4;
    }
</style>    
@endsection



@extends('backpack::layout')

@section('header')
<section class="content-header">
  <h1>
   Caso de Paciente: {{$paciente->full_name}}
 </h1>
 <ol class="breadcrumb">
  <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
  <li><a href="{{ url('admin/incapacidades') }}">Listado de incapacidades</a></li>
</ol>
</section>
@endsection


@section('content')
<div class="row">
{{--   <div class="col-md-offset-1 col-md-10">
    @include('clinica.partials.ficha-paciente', ['paciente' => $paciente])
  </div> --}}
{{--   <div class="col-md-offset-1 col-md-10">
    @include('clinica.partials.ficha-caso',['caso' => $caso])
  </div> --}}
  <div class="col-md-offset-1 col-md-10">
      @include('clinica.partials.ficha-incapacidad',['incapacidad' => $incapacidad])
  </div>
</div>
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
  .box-body div{
        line-height: 2;
  }
</style>    
@endsection



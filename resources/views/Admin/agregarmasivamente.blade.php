@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Usuarios Masivos a Categoria
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Categoria Masivas</li>
      </ol>
    </section>
@endsection


@section('content')
<div class="row">
{!! Form::open(['method' => 'PUT', 'url' => 'admin/categorias-masivas/'. $categoria->id , 'class' => 'form']) !!}
     <div class="form-group{{ $errors->has('categoria') ? ' has-error' : '' }} col-md-12"> 
         {!! Form::label('categoria', 'Categoria:', ['class' => 'col-sm-3 control-label']) !!}
         <div class="col-sm-9">
             {{$categoria->nombre }} 
         </div>
     </div>

     <div class="form-group{{ $errors->has('usuarios') ? ' has-error' : '' }}  col-md-12">
         {!! Form::label('usuarios[]', 'Usuarios', ['class' => 'col-sm-3 control-label']) !!}
         <div class="col-sm-9">
             {!! Form::select('usuarios[]', $users->pluck('nombre','id'), $categoria->users()->pluck('id')->toArray(), ['id' => 'usuarios', 'class' => 'form-control chosen', 'required' => 'required', 'multiple', "data-placeholder" => "Selecciona los usuarios"]) !!}
             <small class="text-danger">{{ $errors->first('usuarios') }}</small>
         </div>
     </div>

    <center>
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>  Guardar</button>
    </center>

{!! Form::close() !!}


</div>
@endsection

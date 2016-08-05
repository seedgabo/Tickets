@extends('layouts.app')

@section('content')

    <div class="">

        <h1 class="pull-left">Categorias</h1>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('categoriasTickets.create') !!}"> <i class="fa fa-plus"></i>Agregar</a>

        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        @if($categoriasTickets->isEmpty())
            <div class="well text-center">Ningun Categoria Encontrada.</div>
        @else
            @include('categoriasTickets.table')
        @endif
        
    </div>
@endsection
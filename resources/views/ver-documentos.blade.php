@extends('layouts.app')
@section('content')
    <div class="col-md-12">
        
        <ol class="breadcrumb">
            <li>
                <a href="{{url('ver-documentos')}}">Categorias</a>
            </li>
            <li class="active">{{$categoria}}</li>
        </ol>
        
        <h3 class="text-uppercase text-center">{{$categoria}}</h3>
    <div class="list-group col-md-8 col-md-offset-2">
        @forelse ($documentos as $doc)
            <a href="{{url('getDocumento/'.$doc->id)}}" class="list-group-item">
            <i class="fa fa-file pull-left fa-2x"></i>
                <h4 class="list-group-item-heading">{{ $doc->titulo}}</h4>
                <p class="list-group-item-text">{!! $doc->descripcion !!}</p>
            </a>            
        @empty
            <b>No hay Documentos Disponibles</b>
        @endforelse
    </div>

    </div>
    
@endsection
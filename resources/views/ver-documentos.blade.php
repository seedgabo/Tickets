@extends('layouts.app')
@section('content')
    <div class="col-md-12">
        <h3 class="text-uppercase text-center">{{$categoria}}</h3>
    <div class="list-group col-md-8 col-md-offset-2">
        @forelse ($documentos as $doc)
            <a href="{{'getDocumento/'.$doc->id}}" class="list-group-item active">
                <h4 class="list-group-item-heading">{{ $doc->titulo}}</h4>
                <p class="list-group-item-text">{!! $doc->descripcion !!}</p>
            </a>            
        @empty
            <b>No hay Documentos Disponibles</b>
        @endforelse
    </div>

    </div>
    
@endsection
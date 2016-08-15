@extends('layouts.app')

@section('content')

<div class="col-md-8 col-md-offset-2">

    <h4 class="text-uppercase text-center text-primary">Casos</h4>
    <div class="list-group">
        @forelse ($tickets as $ticket)
            <a href="{{url('ticket/ver/'.$ticket->id)}}" class="list-group-item"><i class="fa fa-ticket"></i> {{$ticket->titulo}}</a>
        @empty
            No hay ningun ticket que coincida con la busqueda
        @endforelse
    </div>

    <h4 class="text-uppercase text-center text-primary">Documentos</h4>
    <div class="list-group">
        @forelse ($documentos as $doc)
            <a href="{{url('getDocumento/'.$doc->id)}}" class="list-group-item"><i class="fa fa-file"></i> {{$doc->titulo}}</a>
        @empty
            No hay ningun documento que coincida con la busqueda
        @endforelse
    </div>
    
</div>
    
@endsection
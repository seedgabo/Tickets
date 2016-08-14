@extends('layouts.app')

@section('content')
<div class="text-center">
             <div class="col-md-6">
                <div class="list-group">
                    <li class="list-group-item active">
                       <h4>Mis Tickets</h4>
                    </li>
                  @forelse ($tickets as $ticket)
                    <a class="list-group-item" href="{{url('/ticket/ver/'. $ticket->id)}}">
                        <span class="badge">{{$ticket->categoria->nombre}}</span>
                        <h4 class="list-group-item-heading">{{ $ticket->titulo }}</h4>
                        <p class="list-group-item-text">
                            <small class="pull-left"><b>Vence el:</b>{{ $ticket->vencimiento  ?  \App\Funciones::transdate($ticket->vencimiento) : "No Vence"}}</small><br>
                        </p>
                      </a>
                  @empty
                  @endforelse
                </div>
            </div>
             <div class="col-md-6">
                <div class="list-group">
                    <li class="list-group-item active">
                       <h4>Por Categorías</h4>
                    </li>
                  @forelse ($categorias as $categoria)
                    <a class="list-group-item" href="{{url('tickets/categoria/'. $categoria->id)}}">
                      <span class="badge">{{$categoria->tickets->count()}}</span>
                       {{ $categoria->nombre }}
                    </a>
                  @empty
                  @endforelse
                </div>
            </div>
</div>

@stop
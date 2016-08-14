@extends('layouts.app')

@section('content')
    <div class="">

        <center> <h2>Categorias</h2></center>
        <div class="list-group">
            @forelse ($categorias as  $cat)
                <a href="{{url('ver-documentos/'. $cat)}}" class="list-group-item">{{$cat}}</a>
            @empty            
            @endforelse
        </div>

    </div>
@endsection
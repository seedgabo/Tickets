@extends('layouts.app')

@section('content')
        
        <ol class="breadcrumb">
            <li>
                <a href="{{url('ver-documentos')}}">Categorias</a>
            </li>
        </ol>

        <center> <h2>Categorias</h2></center>
        <div class="list-group col-md-8 col-md-offset-2">
            @forelse ($categorias as  $cat)
                <a href="{{url('ver-documentos/'. $cat->id)}}" class="list-group-item">
                    <i class="fa fa-folder"></i>
                    {{$cat->nombre}}
                </a>
            @empty            
            @endforelse
        </div>

    </div>
@endsection
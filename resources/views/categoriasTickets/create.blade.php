@extends('layouts.app')

@section('content')
<div class="">

    <div class="row">
        <div class="col-sm-12">
            <h1 class="pull-left">Crear nuevo CategoriasTickets</h1>
        </div>
    </div>

    @include('core-templates::common.errors')

    <div class="row">
        {!! Form::open(['route' => 'categoriasTickets.store']) !!}

            @include('categoriasTickets.fields')

        {!! Form::close() !!}
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="">

    <div class="row">
        <div class="col-sm-12">
            <h1 class="pull-left">Crear nuevo documento</h1>
        </div>
    </div>

    @include('core-templates::common.errors')

    <div class="row">
        {!! Form::open(['route' => 'documentos.store', 'files' => true]) !!}

            @include('documentos.fields')

        {!! Form::close() !!}
    </div>
</div>
@endsection
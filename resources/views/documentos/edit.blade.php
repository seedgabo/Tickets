@extends('layouts.app')

@section('content')
    <div class="">

        <div class="row">
            <div class="col-sm-12">
                <h1 class="pull-left">Guardar Documento</h1>
            </div>
        </div>

        @include('core-templates::common.errors')

        <div class="row">
            {!! Form::model($documentos, ['route' => ['documentos.update', $documentos->id], 'method' => 'patch', 'files' => true]) !!}

            @include('documentos.fields')

            {!! Form::close() !!}
        </div>
    </div>
@endsection
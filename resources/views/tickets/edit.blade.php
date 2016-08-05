@extends('layouts.app')

@section('content')
    <div class="">

        <div class="row">
            <div class="col-sm-12">
                <h1 class="pull-left">Guardar Tickets</h1>
            </div>
        </div>

        @include('core-templates::common.errors')

        <div class="row">
            {!! Form::model($tickets, ['route' => ['tickets.update', $tickets->id], 'method' => 'patch', 'files' => true]) !!}

            @include('tickets.fields')

            {!! Form::close() !!}
        </div>
    </div>
@endsection
   <?php $i = 0 ?>
@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Correos Masivos
      </h1>
    </section>
    <script src="{{asset('ckeditor/ckeditor.js')}}"></script>
@endsection


@section('content')

    <div class="well">
        {!! Form::open(['method' => 'GET', 'class' => 'form-inline']) !!}
        
            <div class="form-group{{ $errors->has('filtro') ? ' has-error' : '' }}">
                {!! Form::label('filtro[]', 'Filtro') !!}
                {!! Form::select('filtro[]',$filtro, Input::get('filtro'), ['id' => 'filtro', 'class' => 'form-control chosen', 'required' => 'required', 'multiple']) !!}
            </div>
        
            <div class="btn-group pull-right">
                {!! Form::submit("Buscar", ['class' => 'btn btn-success']) !!}
            </div>
        
        {!! Form::close() !!}
    </div>
    {{-- expr --}}
    <div class="container-fluid box" role="tabpanel">

        <!-- Nav tabs -->

        <ul class="nav nav-tabs" role="tablist">

            <li role="presentation" class="active">

                <a href="#personas" aria-controls="personas" role="tab" data-toggle="tab">Personas</a>

            </li>

            <li role="presentation">

                <a id="emailtab" href="#email" aria-controls="email" role="tab" data-toggle="tab">Email</a>

            </li>

        </ul>



        <!-- Tab panes -->

        <div class="tab-content">

            <div role="tabpanel" class="tab-pane  active" id="personas">

                <div class="col-md-12">

                    <a onclick="selectall()" class="btn">Seleccionar Todos</a>

                    <a onclick="unselectall()" class="btn">Quitar Todos</a>

                    {{-- <a class="btn btn-flat btn-block" onclick="$('#emailtab').trigger('click')" >Siguiente</a> --}}

                </div>

                {{ Form::open(['method' => 'POST','url' => 'ajax/email','files' => true , 'class' => 'form']) }}

                @foreach ($correos as $nombre => $correo)



                    <div class="form-group col-md-3">

                        <div class="checkbox">

                            <label for="{{'correos['.$i.']'}}">

                                {{ Form::checkbox("to[".$i."]", $correo,true) }} <strong>{{$nombre}}</strong> <br> <small>{{$correo}} </small>

                            </label>

                        </div>

                    </div>

                @endforeach

                <div class="col-md-12">

                    <a onclick="selectall()" class="btn">Seleccionar Todos</a>

                    <a onclick="unselectall()" class="btn">Quitar Todos</a>

                    {{-- <a class="btn btn-primary btn-lg btn-block" onclick="$('#emailtab').trigger('click')" >Siguiente</a> --}}

                </div>

            </div>

            <div role="tabpanel" class="tab-pane fade" id="email">


                    <div class="form-group">

                        {{ Form::label('title', 'Titulo Del Mensaje:') }}

                        {{ Form::text('title', "", ['class' => 'form-control', 'required' => 'required']) }}


                    </div>


                    <div class="col-md-12">
                    <div class="form-group">

                        {{ Form::label('contenido', 'Contenido del Mensaje:') }}

                        {{ Form::textarea('contenido', "", ['id' => 'textarea', 'class' => 'form-control ckeditor', 'required' => 'required']) }}

                    </div>
                    </div>

                    <div class="form-group col-md-12">

                        {{ Form::label('file', 'Adjuntar Archivo:') }}

                        {{Form::file('file',[])}}


                        <span class="label label-info">Suba un archivo Valido menor a 10Mb</span>

                    </div>



                    <div class="btn-group pull-right">

                        {{ Form::submit("Enviar", ['id' => 'enviar','class' => 'btn btn-success']) }}

                    </div>

                    {{ Form::close() }}

            </div>

        </div>
    </div>


<script type="text/javascript">
    function selectall()
    {

        var checkboxes = $(':checkbox');

        checkboxes.prop('checked', true);
    }
    function unselectall()
    {

        var checkboxes = $(':checkbox');

        checkboxes.prop('checked', false);
    }

</script>
@stop

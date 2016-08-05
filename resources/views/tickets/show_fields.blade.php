<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $tickets->id !!}</p>
</div>

<!-- Titulo Field -->
<div class="form-group">
    {!! Form::label('titulo', 'Titulo:') !!}
    <p>{!! $tickets->titulo !!}</p>
</div>

<!-- Contenido Field -->
<div class="form-group">
    {!! Form::label('contenido', 'Contenido:') !!}
    <p>{!! $tickets->contenido !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{!! $tickets->user_id !!}</p>
</div>

<!-- Estado Field -->
<div class="form-group">
    {!! Form::label('estado', 'Estado:') !!}
    <p>{!! $tickets->estado !!}</p>
</div>

<!-- Categoria Id Field -->
<div class="form-group">
    {!! Form::label('categoria_id', 'Categoria Id:') !!}
    <p>{!! $tickets->categoria_id !!}</p>
</div>

<!-- Archivo Field -->
<div class="form-group">
    {!! Form::label('archivo', 'Archivo:') !!}
    <p>{!! $tickets->archivo !!}</p>
</div>


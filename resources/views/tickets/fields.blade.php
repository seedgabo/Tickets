<!--- Titulo Field --->
<div class="form-group col-sm-6">
    {!! Form::label('titulo', 'Titulo:') !!}
    {!! Form::text('titulo', null, ['class' => 'form-control ']) !!}
</div>

<!--- Contenido Field --->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('contenido', 'Contenido:') !!}
    {!! Form::textarea('contenido', null, ['class' => 'form-control', 'id' => 'textarea']) !!}
</div>

<!--- Categoria Id Field --->
<div class="form-group col-sm-6">
    {!! Form::label('categoria_id', 'Categoria:') !!}
    {!! Form::select('categoria_id', \App\Models\CategoriasTickets::lists("nombre","id"), null, ['class' => 'form-control chosen' , "id" => "categoria"]) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('guardian_id', 'Asignar a:') !!}
    {!! Form::select('guardian_id',\App\User::lists("nombre","id"), null, ['id' => 'guardian_id', 'class' => 'form-control chosen depdrop', 'required' => 'required']) !!}
    <small class="text-danger">{{ $errors->first('guardian_id') }}</small>
</div>
<script>
    $("#guardian_id").depdrop({
        depends: ['categoria'],
        url: '{{url('ajax/getUsersbyCategoria')}}',
        placeholder: false
    });
    $('#guardian_id').on('depdrop.change', function(event, id, value, count) {
            $('.chosen').chosen('destroy').chosen();
    });
</script>


<!--- User Id Field --->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'Usuario:') !!}
    {!! Form::select('user_id', App\User::lists("nombre","id") , null, ['class' => 'form-control chosen' , "id" => "user_id"]) !!}
</div>
<script>
    $("#user_id").depdrop({
        depends: ['categoria'],
        url: '{{url('ajax/getUsersbyCategoria')}}',
        placeholder: false
    });
    $('#guardian_id').on('depdrop.change', function(event, id, value, count) {
            $('.chosen').chosen('destroy').chosen();
    });
</script>


<!--- Estado Field --->
<div class="form-group col-sm-6">
    {!! Form::label('estado', 'Estado:') !!}
    {!! Form::select('estado', ['abierto' => 'abierto', 'completado' => 'completado', 'en curso' => 'en curso', ' rechazado' => ' rechazado'], null, ['class' => 'form-control chosen']) !!}
</div>


<!--- Archivo Field --->
<div class="form-group col-sm-6">
    {!! Form::label('archivo', 'Archivo:') !!}
    {!! Form::file('archivo') !!}
</div>

<!-- Transferible Field --->
<div class="form-group col-sm-6">
    {!! Form::label('encriptado', 'Encriptado:') !!}
    {!! Form::select('encriptado',["false" => "no" , "truephp arti" => "si"], null ,['class' => 'form-control chosen']) !!}
</div>

<!--- vencimiento Field --->
<div class="form-group col-sm-6">
    {!! Form::label('vencimiento', 'Vence el:') !!}
    {!! Form::text('vencimiento',null, ['class' => 'form-control datetimepicker']) !!}
    <a href="#!" onclick="$('.datetimepicker').val('')">No vence</a>
</div>

<!-- Transferible Field --->
<div class="form-group col-sm-6">
    {!! Form::label('transferible', 'Transferible:') !!}
    {!! Form::select('transferible',["0" => "no" , "1" => "si"], null ,['class' => 'form-control chosen']) !!}
</div>
    


<!--- Submit Field --->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('tickets.index') !!}" class="btn btn-default">Cancel</a>
</div>

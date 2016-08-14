<!--- Titulo Field --->
<div class="form-group col-sm-6">
    {!! Form::label('titulo', 'Titulo:') !!}
    {!! Form::text('titulo', null, ['class' => 'form-control ']) !!}
</div>

<!--- Contenido Field --->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('descripcion', 'Descripcion:') !!}
    {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'id' => '']) !!}
</div>

<!--- Categoria Id Field --->
<div class="form-group col-sm-6">
    {!! Form::label('categoria', 'Categoria:') !!}
    {!! Form::text('categoria', null, ['class' => 'form-control input-sm', 'list' => 'categorias' ,'autocomplete' => 'off']) !!}
    <datalist id="categorias">
     @forelse (App\Models\Documentos::distinct()->pluck("categoria") as $cat)
        <option value="{{$cat}}"> 
     @empty         
     @endforelse
    </datalist>
</div>

<!--- Estado Field --->
<div class="form-group col-sm-6">
    {!! Form::label('activo', 'Activo:') !!}
    {!! Form::select('activo', ['1' => 'Activo', '0' => 'No Activo'], null, ['class' => 'form-control chosen']) !!}
</div>

<!-- Transferible Field --->
<div class="form-group col-sm-6">
    {!! Form::label('editable', 'Protegido:') !!}
    {!! Form::select('editable',["0" => "no" , "1" => "si"], null ,['class' => 'form-control chosen']) !!}
</div>

<!--- Archivo Field --->
<div class="form-group col-sm-6">
    {!! Form::label('archivo', 'Archivo:') !!}
    {!! Form::file('archivo', ['class' => '']) !!}
</div>

    


<!--- Submit Field --->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('documentos.index') !!}" class="btn btn-default">Cancelar</a>
</div>

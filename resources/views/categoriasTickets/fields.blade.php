<!--- Nombre Field --->
<div class="form-group col-sm-6">
    {!! Form::label('nombre', 'Nombre:') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control', 'required' => 'required', 'minlength' => '3']) !!}
</div>

<!--- Descripción Field --->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('descripción', 'Descripción:') !!}
    {!! Form::textarea('descripción', null, ['class' => 'form-control', 'required' => 'required', 'minlength' => '3']) !!}
</div>

<!--- Submit Field --->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('categoriasTickets.index') !!}" class="btn btn-default">Cancel</a>
</div>

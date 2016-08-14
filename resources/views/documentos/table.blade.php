<table class="table table-bordered table-responsive">
    <thead>
            <th>Id</th>
            <th>Titulo</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Activo</th>
            <th>Documento</th>
            <th>Protegido</th>
    <th width="50px">Acción</th>
    </thead>
    <tbody>
    @foreach($documentos as $documento)
        <tr>
            <td>{!! $documento->id !!}</td>
            <td>{!! $documento->titulo !!}</td>
            <td>{!! $documento->descripcion !!}</td>
            <td>{!! $documento->categoria !!}</td>
            <td>{!! $documento->activo  == 1 ?  "Si" : "No" !!}</td>
            <td><a href="{!! "getDocumento/" . $documento->id !!}">Ver </a></td>
            <td>{!! $documento->editable  == 1 ?  "Si" : "No" !!}</td>
            <td>
                <a href="{!! route('documentos.edit', [$documento->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
                <a href="{!! route('documentos.delete', [$documento->id]) !!}" onclick="return confirm('Estas seguro que deseas eliminar este documento?')">
                    <i class="glyphicon glyphicon-trash"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
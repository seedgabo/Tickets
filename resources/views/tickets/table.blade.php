<table class="table table-bordered table-responsive">
    <thead>
            <th>Id</th>
			<th>Titulo</th>
			<th>Contenido</th>
            <th>Usuario</th>
            <th>Guardian</th>
			<th>Estado</th>
			<th>Categoria</th>
            <th>Tranferible?</th>
			<th>Archivo</th>
    <th width="50px">Acción</th>
    </thead>
    <tbody>
    @foreach($tickets as $tickets)
        <tr>
            <td>{!! $tickets->id !!}</td>
			<td>{!! $tickets->titulo !!}</td>
			<td>{!! $tickets->contenido !!}</td>
            <td>{!! \App\User::find($tickets->user_id)->nombre !!}</td>
            <td>{!! \App\User::find($tickets->guardian_id)->nombre !!}</td>
			<td>{!! $tickets->estado !!}</td>
			<td>{!! \App\Models\CategoriasTickets::find($tickets->categoria_id)->nombre !!}</td>
            <td>{!! $tickets->transferible  == 1 ?  "Si" : "No" !!}</td>
			<td>{!! $tickets->archivo!!}</td>
            <td>
                <a href="{!! route('tickets.edit', [$tickets->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
                <a href="{!! route('tickets.delete', [$tickets->id]) !!}" onclick="return confirm('Estas seguro que deseas eliminar este Tickets?')">
                    <i class="glyphicon glyphicon-trash"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
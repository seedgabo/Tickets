    <div class="box box-default hover">
        <div class="box-header">
            <h5 class="text-primary">Incapacidades del Caso Médico # {{$caso->id}}</h5>
            <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha de la Incapacidad</th>
                            <th>Estado</th>
                            <th>Días Incapacidad</th>
                            <th>Médico</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1 ?>
                    @forelse ($incapacidades as $incapacidad)
                        <tr>
                            <td>{{$incapacidad->id}}</td>
                            <td>{{\App\Funciones::transdate($incapacidad->fecha_incapacidad, 'l d M Y')}}</td>
                            <td>{{$incapacidad->estado}}</td>
                            <td>{{$incapacidad->dias_incapacidad}}</td>
                            <td>{{$incapacidad->medico->nombre}}</td>
                            <td><a class="btn btn-sm" href="{{url('admin/ver-incapacidad/' . $incapacidad->id)}}">Ver</a></td>
                        </tr>
                    @empty
                    <tr>
                        Este paciente no tiene ningún caso
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            <a href="{{url('admin/incapacidades/create?caso_id='. $caso->id  . "&paciente_id=" . $caso->paciente_id)}}" class="btn btn-primary">
                <i class="fa fa-wheelchair"></i> Agregar Incapacidad
            </a>
            </div>
        </div>
    </div>
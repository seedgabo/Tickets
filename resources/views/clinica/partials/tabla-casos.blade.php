    <div class="box box-default hover">
        <div class="box-header">
            <h5 class="text-primary">Casos Médicos</h5>
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
                            <th>Fecha de Apertura</th>
                            <th>Estado</th>
                            <th>Médico</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1 ?>
                    @forelse ($paciente->casos as $caso)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{\App\Funciones::transdate($caso->apertura)}}</td>
                            <td>{{$caso->estado}}</td>
                            <td>{{$caso->medico->nombre}}</td>
                            <td><a class="btn btn-sm" href="{{url('admin/ver-caso/' . $caso->id)}}">Ver</a></td>
                        </tr>
                    @empty
                    <tr>
                        Este paciente no tiene ningún caso
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
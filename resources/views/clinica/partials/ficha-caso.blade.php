    <div class="box box-default hover">
        <div class="box-header">
            <h5 class="text-primary">Caso</h5>
            <div class="box-tools pull-right">
              <b>Fecha de Apertura: </b> {{\App\Funciones::transdate($caso->apertura)}}
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>            
        </div>
        <div class="box-body">
            <div class="col-md-6">
                <p><b>Origen del Caso:</b>      {!! $caso->origen_del_caso !!}</p>
            </div>
            <div class="col-md-6">
                <p><b>Descripción del Caso:</b> {!! $caso->descripcion !!}</p>
            </div>
            <div class="col-md-6">
                <b>Cerrado el:</b> {{\App\Funciones::transdate($caso->cierre)}}
            </div>
            <div class="col-md-6">
                <b>Médico:</b> @if(isset($caso->medico)){{ $caso->medico->nombre }}@endif
            </div>
            <div class="col-md-6">
                <b>Dias Total de Incapacidad:</b> {{$caso->dias_incapacidad_acumulados}} Días
            </div>
        </div>
            <div class="box-footer text-center">
                    @if ($caso->ticket_id != null && $caso->ticket_id != 0)
                        <a href="{{url('/ticket/ver/'. $caso->ticket_id)}}">  <i class="fa fa-ticket"></i> Ver Seguimiento</a> 
                    @else
                        <a href="{{url('admin/iniciar-seguimiento/' .$caso->id)}}"> <i class="fa fa-ticket"></i> Iniciar el seguimiento</a>
                    @endif
            </div>
    </div>
    <div class="box box-solid box-primary hover">
        <div class="box-header">
            <h5>Incapacidad de <a href="{{url('admin/ver-paciente/'. $incapacidad->paciente_id)}}">{{$incapacidad->paciente->full_name}}</a></h5>
            <div class="box-tools pull-right">
              <b><i class="fa fa-clock-o"></i> </b> {{\App\Funciones::transdate($incapacidad->created_at)}}
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>            
        </div>
        <div class="box-body">
            <div class="col-md-6">
                <p><b>Paciente:</b> <a href="{{url('admin/ver-paciente/'. $incapacidad->paciente_id)}}">{{$incapacidad->paciente->full_name}}</a></</p>
            </div>
            <div class="col-md-6">
                <p><b>Caso:</b> @if (isset($incapacidad->caso_id))<a href="{{url('admin/ver-caso/'. $incapacidad->caso_id)}}"> Ver Caso </a> @else No Asignado @endif</p>
            </div>
            <div class="col-md-6">
                <b>Médico:</b> @if(isset($incapacidad->medico)){{ $incapacidad->medico->nombre }}@endif
            </div>
            <div class="col-md-6">
                <b>Fecha de Ingreso: </b> {{\App\Funciones::transdate($incapacidad->fecha_ingreso, 'l d M Y')}}
            </div>
            <div class="col-md-6">
                <b>Fecha de Incapacidad: </b> {{\App\Funciones::transdate($incapacidad->fecha_incapacidad, 'l d M Y')}}
            </div>
            <div class="col-md-6">
                <b>Fecha de Liquidación:</b> {{\App\Funciones::transdate($incapacidad->fecha_liquidacion, 'l d M Y')}}
            </div>
            <div class="col-md-6">
                <b>Entidad:</b> {{$incapacidad->entidad}}
            </div>
            <div class="col-md-6">
                <b>Eps:</b> @if (isset($incapacidad->eps)) {{$incapacidad->eps->nombre}} </a> @else No Asignado @endif
            </div>
            <div class="col-md-6">
                <b>Dias de Incapacidad:</b> {{$incapacidad->dias_incapacidad}} Días
            </div>
            <div class="col-md-6">
                <b>Prorroga:</b> {{$incapacidad->prorroga}}
            </div>

            <div class="col-md-6">
                <b>Origen:</b> {{$incapacidad->origen}}
            </div>
            <div class="col-md-6">
                <b>Cie10:</b> @if (isset($incapacidad->cie10)) {{$incapacidad->cie10->codigo}} @endif
            </div>
            <div class="col-md-6">
                <b>Sistema Afectado:</b> {{$incapacidad->sistema_afectado}}
            </div>
            <div class="col-md-6">
                <b>Caso Especial:</b> {{$incapacidad->caso_especial}}
            </div>
            <div class="col-md-6">
                
            </div>

        </div>
        <div class="box-footer ">
            <p class="pull-right">
                <b>Estado:</b>  {{$incapacidad->estado}}
            </p>
        </div>
    </div>
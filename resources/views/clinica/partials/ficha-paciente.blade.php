    <div class="box box-default hover">
        <div class="box-header">
            <h5 class="text-primary">
                <a href="{{url('admin/ver-paciente/'. $paciente->id)}}">{{$paciente->fullname}} </a></h5>
            <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">

            <div class="col-md-3 text-center">
                <img src="{{$paciente->foto_url}}" class="img-thumbnail"  style="height:120px;">
            </div>
            <div class="col-md-9">                
                <div class="col-md-4">
                    <b>Nombres:</b>  {{$paciente->nombres}}
                </div>
                <div class="col-md-4">
                    <b>Apellidos:</b>  {{$paciente->apellidos}}
                </div>
                <div class="col-md-4">
                    <b>Documento:</b>  {{$paciente->cedula}}
                </div>
                <div class="col-md-4">
                    <b>Fecha de Nacimiento:</b>  {{$paciente->nacimiento->format("d/m/y")}}
                </div>
                <div class="col-md-4">
                    <b>Sexo:</b>  {{$paciente->sexo == 'M' ? 'Masculino' : 'Femenino' }}
                </div>
                <div class="col-md-4">
                    <b>Fecha de Ingreso:</b>  {{ $paciente->fecha_ingreso }}
                </div>
                  <div class="col-md-4">
                    <b>Fecha de Egreso:</b>  {{ $paciente->fecha_egreso }}
                </div>
                <div class="col-md-4">
                    <b>Estado civil:</b>  {{$paciente->estadoCivil}}
                </div>
                <div class="col-md-4">
                    <b>Telefono:</b> {{$paciente->telefono}}
                </div>
                <div class="row"></div>
                <div class="col-md-4">
                    <b>Cargo:</b> {{$paciente->cargo}}
                </div>
                <div class="col-md-4">
                    <b>Departamento:</b> {{$paciente->departamento}}
                </div>
                @if (isset($paciente->puesto))
                <div class="col-md-4">
                    <b>Punto de Operación:</b>@if(isset($paciente->puesto)) {{$paciente->puesto->nombre}} @endif
                </div>
                @endif
                <div class="col-md-8">
                     <b>Dirección</b> {{$paciente->direccion}}
                </div>
                <div class="col-md-6 text-center">
                    <h5><b>Eps:</b> @if(isset($paciente->eps)) {{$paciente->eps->nombre}} @endif</h5>
                </div>                
                <div class="col-md-6 text-center">
                    <h5><b>ARL:</b> @if(isset($paciente->arl)) {{$paciente->arl->nombre}} @endif</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="well">
       <ul class="timeline">
          <!-- timeline time label -->
          <li class="time-label">
            <span class="bg-red">
              Recomendaciones
            </span>
          </li>
          @foreach ($recomendaciones as $recomendacion)
          <li>
            <i class="fa fa-comments bg-yellow"></i>

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i>{{\App\Funciones::transdate($recomendacion->created_at, 'd/m/y h:m:i A')}}</span>

              <h3 class="timeline-header">{{$recomendacion->titulo}}</h3>

              <div class="timeline-body">
                {{$recomendacion->descripcion}}
              </div>
              <div class="timeline-footer">
                <div style="height: 40px; width: 40px;" class="pull-left">
                  <img src="{{$recomendacion->user->imagen()}}" class="img-responsive img-circle" alt="">
                </div>
                <span class="text-primary">
                  {{$recomendacion->user->nombre}}
                </span>
              </div>
            </div>
          </li>
          @endforeach
          <li>
             <div class="timeline-item">
                <div class="timeline-body">
                   <a class="btn btn-primary" href="{{url('admin/recomendaciones/create?caso_id='. $caso->id)}}"> <i class="fa fa-calendar-plus-o"></i> Agregar Recomendación</a>
                </div>
             </div>
          </li>
      </ul>
    </div>
@if (Auth::user()->canAny(['Agregar Casos Medicos', 'Editar Casos Medicos','Eliminar Casos Medicos',
  'Agregar Pacientes', 'Editar Pacientes','Eliminar Pacientes',
  'Agregar Incapacidades', 'Editar Incapacidades','Eliminar Incapacidades',
  'Agregar Recomendaciones', 'Editar Recomendaciones','Eliminar Recomendaciones',
  ]) ||  Auth::user()->hasRole('SuperAdmin'))
  <li class="treeview">

    <a href="#"><i class="fa fa-hospital-o"></i><span>Gestión de historias Clínicas</span> <i class="fa fa-angle-left pull-right"></i></a>

    <ul class="treeview-menu">

      <form action="{{url('admin/paciente/buscar')}}" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="search" name="query" class="form-control" placeholder="Buscar Paciente">
          <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
        </div>
      </form>
      
      @if (Auth::user()->canAny(['Agregar Pacientes', 'Editar Pacientes','Eliminar Pacientes']) ||  Auth::user()->hasRole('SuperAdmin'))
      <li><a href="{{ url('admin/pacientes') }}"><i class="fa fa-user-md"></i> <span>Pacientes</span></a></li>
      @endif


      @if (Auth::user()->canAny(['Agregar Casos Medicos', 'Editar Casos Medicos','Eliminar Casos Medicos']) ||  Auth::user()->hasRole('SuperAdmin'))
      <li><a href="{{ url('admin/casos-medicos') }}"><i class="fa fa-file-text"></i> <span>Casos Médicos</span></a></li>               
      @endif

      @if (Auth::user()->canAny(['Agregar Recomendaciones', 'Editar Recomendaciones','Eliminar Recomendaciones']) ||  Auth::user()->hasRole('SuperAdmin'))
      <li><a href="{{ url('admin/recomendaciones') }}"><i class="fa fa-file-text"></i> <span>Recomendaciones</span></a></li>                
      @endif

      @if (Auth::user()->canAny(['Agregar Incapacidades', 'Editar Incapacidades','Eliminar Incapacidades']) ||  Auth::user()->hasRole('SuperAdmin'))
      <li><a href="{{ url('admin/incapacidades') }}"><i class="fa fa-wheelchair"></i> <span>Incapacidades</span></a></li>
      @endif


      @if(Auth::user()->can('Administar Tablas Medicos') ||  Auth::user()->hasRole('SuperAdmin'))
      <li class="treeview">
        <a href="#"><i class="fa fa-table"></i><span>Tablas</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
          <li><a href="{{ url('admin/puestos') }}"><i class="fa fa-map-pin"></i> <span>Puestos de Trabajo</span></a></li>
          <li><a href="{{ url('admin/fondos-de-pensiones') }}"><i class="fa fa-table"></i> <span>Fondos de Pensiones</span></a></li>
          <li><a href="{{ url('admin/eps') }}"><i class="fa fa-building"></i> <span>Eps</span></a></li>
          <li><a href="{{ url('admin/arl') }}"><i class="fa fa-building"></i> <span>Arl</span></a></li>
          <li><a href="{{ url('admin/cie10') }}"><i class="fa fa-table"></i> <span>Cie10</span></a></li>
        </ul>
      </li>
      @endif


    </ul>
  </li>

  @endif    
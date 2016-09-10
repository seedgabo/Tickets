@if(Auth::user()->hasAnyRole(['SuperAdmin','Administrar Respaldos']))
      <li><a href="{{ url('admin/backup') }}"><i class="fa fa-hdd-o"></i> <span>Respaldos</span></a></li>
      <li><a href="{{ url('admin/archivos') }}"><i class="fa fa-archive"></i> <span>Archivos</span></a></li>
@endif  
@hasanyrole(['Correos Masivos','SuperAdmin'])


    <li class="treeview">
        <a href="#"><i class="fa fa-envelope-square"></i><span>Correos Masivos</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
          <li><a href="{{ url('admin/email/departamento') }}"><i class="fa fa-envelope"></i> <span>Pacientes por Departamento</span></a></li>
          <li><a href="{{ url('admin/email/puesto') }}"><i class="fa fa-envelope"></i> <span>Pacientes por Puesto</span></a></li>

          <li><a href="{{ url('admin/email/usuarios') }}"><i class="fa fa-envelope"></i> <span>Usuarios</span></a></li>
          <li><a href="{{ url('admin/email/usuarios/departamento') }}"><i class="fa fa-envelope"></i> <span>Usuarios por Departamento</span></a></li>
        </ul>
    </li>


@endhasanyrole
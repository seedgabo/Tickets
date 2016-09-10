@if (Auth::user()->canAny(['Agregar Usuarios', 'Editar Usuarios','Eliminar Usuarios']) 
  || Auth::user()->hasAnyRole(['SuperAdmin','Administrar Permisos']))

      <li class="treeview">
      <a href="#"><i class="fa fa-group"></i> <span>Gestion de Usuarios</span> <i class="fa fa-angle-left pull-right"></i></a>
      
      <ul class="treeview-menu">

            @if (Auth::user()->canAny(['Agregar Usuarios', 'Editar Usuarios','Eliminar Usuarios'])   || Auth::user()->hasRole('SuperAdmin'))
              <li><a href="{{ url('admin/usuarios') }}"><i class="fa fa-users"></i> <span> Usuarios </span></a></li>
            @endif


             @if(Auth::user()->hasAnyRole(['SuperAdmin','Administrar Permisos']))

                <li class="treeview">
                  <a href="#"><i class="fa fa-group"></i> <span>Permisos Y Roles</span> <i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
                    <li><a href="{{ url('admin/user') }}"><i class="fa fa-user"></i> <span>Usuarios</span></a></li>
                    <li><a href="{{ url('admin/role') }}"><i class="fa fa-group"></i> <span>Roles</span></a></li>
                    <li><a href="{{ url('admin/permission') }}"><i class="fa fa-key"></i> <span>Permisos</span></a></li>
                  </ul>
                </li>

             @endif
       </ul>
     </li>
@endif
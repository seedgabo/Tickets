@if (Auth::user()->canAny(['Agregar Casos', 'Editar Casos','Eliminar Casos',
  'Agregar Categorias', 'Editar Categorias','Eliminar Categorias',
  ]) ||  Auth::user()->hasRole('SuperAdmin'))

  <li class="treeview">

    <a href="#"><i class="fa fa-sitemap"></i><span>Gestión Casos</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
  
      <li><a href="{{ url('admin/tickets') }}"><i class="fa fa-ticket"></i> <span> Casos </span></a></li>
  
      <li><a href="{{ url('admin/categorias') }}"><i class="fa fa-bars"></i> <span> Categorias </span></a></li>  

      <li><a href="{{url('getListaCategoriasTickets')}}" target="tree"><i class="fa-list fa"></i> Ver Arbol</a></li>
    </ul>
  </li>

@endif
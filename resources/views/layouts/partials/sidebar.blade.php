<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{App\Funciones::getUrlProfile()}}" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                   <p> <a href="{{url('profile')}}">{{ Auth::user()->nombre }} <br> (Ver Perfil)</a></p>
                </div>
            </div>
        @endif

    
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
             <li><a href="{{ url('menu') }}">
                <i class='fa fa-home'></i> 
                <span>Menu</span>
            </a></li>
            <!-- Optionally, you can add icons to the links -->
            <li><a href="{{ url('home') }}">
                <i class='fa fa-building'></i> 
                <span>Empresas (@if (Session::has('empresa')){{ \App\Funciones::getEmpresa()->nombre }}@endif) </span>
            </a></li>
            <li><a href="{{ url('clientes') }}">
                <i class='fa fa-users'></i> 
                <span>Clientes 
                (@if (Session::has('cliente')){{ \App\Funciones::getCliente()['NOM_TER'] }}@endif)
                </span>
            </a></li>
            <li><a href="{{ url('cartera') }}">
                <i class='fa fa-briefcase'></i> 
                <span>Cartera
                </span>
            </a></li>
            @if (Session::has('cliente'))
            <li><a href="{{ url('catalogo') }}">
                <i class='fa fa-credit-card'></i> 
                <span>Catalogo</span>
            </a></li>
            @endif
             @if (Session::has('cliente'))
            <li><a href="{{ url('carrito') }}">
                <i class='fa fa-shopping-cart'></i> 
                <span>Carrito </span>
            </a></li>
            @endif
            <li class="treeview">
                <a href="#"><i class='fa fa-ticket'></i> <span>Matriz de Seguimiento</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{asset('mis-tickets')}}">Mis Tickets</a></li>
                    <li><a href="{{asset('ticket')}}">Tickets Abiertos</a></li>
                    <li><a href="{{asset('tickets/todos')}}">Todos los  Tickets</a></li>
                </ul>
            </li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>

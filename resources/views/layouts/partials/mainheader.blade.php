<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="http://siasoftsas.com" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="{{asset('img/favicon.png')}}" alt="logo" width="70%"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="{{asset('img/logo.png')}}" alt="logo" width="70%"></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}"><i class="fa fa-sign-in"></i>Login</a></li>
                @else
                    @if (Auth::user()->admin == 1)
                        <li class="dropdown">
                            <a aria-expanded="true" href="#" class="dropdown-toggle" data-toggle="dropdown">
                              <i class="fa fa-lock"></i><span class="hidden-xs"> Administración</span>
                            </a>
                            <ul class="dropdown-menu">
                              <li><a href="{{url('/Empresa')}}"><i class="fa fa-building-o"></i> Empresas</a></li>
                              <li><a href="{{url('/Usuarios')}}"><i class="fa fa-user-secret"></i> Usuarios</a></li> 
                              
                              <li class="dropdown-header">Tickets</li>

                              <li><a href="{{url('/tickets')}}"><i class="fa fa-ticket"></i> Tickets</a></li>
                              <li><a href="{{url('/categoriasTickets')}}"><i class="fa fa-ticket"></i> Categorias de Tickets</a></li> 
                            </ul>
                        </li>
                    @endif
                    
                    <li><a href="{{ url('/logout') }}"><i class="fa fa-sign-out"></i> Salir</a></li>
                @endif


                <!-- Control Sidebar Toggle Button -->
                <!--
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
                -->
        </div>
    </nav>
</header>
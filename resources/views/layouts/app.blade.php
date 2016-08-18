
<!DOCTYPE html>
<html lang="es">

<head>
    @include('layouts.partials.htmlheader')
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="{{url('')}}">
                    <img src="{{asset('img/logo.png')}}" alt="logo" class="img-responsive" style="height: 45px; display:inline">
                    <b>DUFLO</b>SA
                </a>
            </div>
            <!-- /.navbar-header -->
            <div class="navbar-collapse">
                
                <ul class="nav navbar-top-links navbar-right">
                @if (Auth::check())
                <li class="dropdown text-center">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <img src="{{Auth::user()->imagen()}}" alt="perfil" class="img-circle" style="height: 40px; width: 40px;">
                        {{Auth::user()->nombre}}
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                      <li><a href="{{url('profile')}}"><i class="fa fa-user"></i> Ver Perfil</a></li>
                    @if (Auth::user()->admin == 1)
                     <li><a href="{{url('admin')}}"><i class="fa fa-lock fa-fw"></i> Administrar</i></a></li>
                    @endif
                      <li><a href="{{url('logout')}}"><i class="fa fa-sign-out"></i> Cerrar Sesión</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>Leer todos</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                @endif
                </ul>
                <!-- /.navbar-top-links -->

                <div class="navbar sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li class="sidebar-search">
                                <div class="input-group custom-search-form">
                                    <form action="{{url('busqueda')}}">                                            
                                        <div class="input-group">                                                                                
                                        <input type="text" name="query" class="form-control" placeholder="Buscar..." required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </span>                                            
                                        </div>
                                    </form>
                                </div>
                            </li>
                        @if (Auth::check())
                            <li>
                                <a href="{{url('mis-tickets')}}"><i class="fa fa-ticket fa-fw"></i> Mis Casos</a>
                            </li>                       
                            <li>
                                <a href="{{url('ticket')}}"><i class="fa fa-ticket fa-fw"></i> Casos Abiertos</a>
                            </li>                        
                            <li>
                                <a href="{{url('/tickets/todos')}}"><i class="fa fa-ticket fa-fw"></i> Todos los Casos</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-list-alt fa-fw"></i> Por Categorias<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                        @foreach (Auth::user()->categorias()->wherein("parent_id",["",null]) as $categoria)
                                            <li>
                                                <a href="{{url('tickets/categoria/' . $categoria->id)}}"><i class="fa fa-check fa-fw"></i> {{$categoria->nombre}}</a>
                                            </li>
                                        @endforeach
                                </ul>
                            </li>
                            <li>
                                <a href="{{url('ver-documentos')}}"><i class="fa fa-files-o"></i> Gestión de Documentos</a>
                            </li>
                        @endif
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="">
                <div class="row">
                @include('flash::message')
                    <div class="">
                        @yield('content')
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    @include('layouts.partials.scripts')

</body>
</html>

<html>
<head>
    <link rel="stylesheet" href= "{{asset('css/bootstrap-email.css')}}" />
    <style type="text/css" media="screen">
        body
        {
            font-family: 'Helvetica Neue';
            font-size: 14px;
            font-weight: 12px;
        }
    </style>
</head>

<body>
    <h3 class="container">Hola, {{$user->nombre}}</h3>
    <div class="well">
        Su usuario ha sido creado con exito

        Usuario:  {{$user->email}} <br>
        Contraseña: {{$user->cod_vendedor}} <br>
    </div>
    <small> Se Recomienda cambia la clave lo antes posible</small>


    <a href="http://siasoftsas.com">
        {{Html::image('http://siasoftsas.com/public/img/logo.png')}}
    </a>
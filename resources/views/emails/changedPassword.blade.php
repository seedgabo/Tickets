<html>
<head>
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/zurb-ink/1.0.5/ink.min.css" />
</head>

<body>
    <table class="container">
        <div>
            <h3> Hola, {{$user->nombre}}</h3>
            <p>Hemos detectado que recientemente se ha cambiado la contraseña de su usuario en el sistema</p>
            <p style="color:red">Si piensa que es un error por favor comuniquese con el equipo técnico</p>
        </div>
    </table>
    
    <a href="{{url('/')}}">
        {{Html::image(asset('img/logo.png'))}}
    </a>
</body>
</html> 
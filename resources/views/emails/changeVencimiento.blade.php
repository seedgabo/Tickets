<html>
<head>
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/zurb-ink/1.0.5/ink.min.css" />
</head>

<body>
    <table class="container">
        <div>
            <h3> Hola, {{$guardian->nombre}}</h3>
            <p>Se ha actualizado la fecha de vecimiento del caso: <b>{{$ticket->titulo}}</b></p><br>
            <b>Nueva Fecha:</b>{{$ticket->vencimiento ? \App\Funciones::transdate($ticket->vencimiento) : "No Vence"}}
            <p style="color:red">Si piensa que es un error por favor comuniquese con el equipo técnico</p>
        </div>
    </table>
    
    <a href="{{url('/')}}">
        {{Html::image(asset('img/logo.png'))}}
    </a>
</body>
</html> 
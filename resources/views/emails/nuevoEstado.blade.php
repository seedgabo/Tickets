<html>
<head>
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/zurb-ink/1.0.5/ink.min.css" />
</head>

<body>
    <table class="container">
        <div>
            <h3> Ticket: {{$ticket->titulo}} </h3>
            <p>Se ha actualizado el estado del  caso: <b>{{$ticket->estado}}</b></p><br>
            <b>Fecha Límite del Caso:</b>{{$ticket->vencimiento ? \App\Funciones::transdate($ticket->vencimiento) : "No Vence" }}
        </div>
    </table>
    
    <a href="{{url('/')}}" style="width:300px;">
        {{Html::image(asset('img/logo.png'))}}
    </a>
</body>
</html> 
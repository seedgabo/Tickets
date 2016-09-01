<html>
<head>
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/zurb-ink/1.0.5/ink.min.css" />
</head>

<body>
    <table class="container">
        <div>
            <p>Se te  ha inivtado a colaborar y participar en el caso: <b>{{$ticket->titulo}}</b></p><br>
            <p style="color:red">Si piensa que es un error por favor comuniquese con el equipo técnico</p>
        </div>
      <table class="button">
      <tr>
        <td>
            <a href="{{url('/ticket/ver/'.$ticket->id)}}"> Ver Caso</a>
        </td>
      </tr>
    </table>
    </table>
    
    <a href="{{url('/')}}" style="width:300px;">
        {{Html::image(asset('img/logo.png'))}}
    </a>
</body>
</html> 
<html>
<head>
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/zurb-ink/1.0.5/ink.min.css" />
</head>

<body>
    <table class="container">
        <div>
            <h3>Un comentario ha sido agregado</h3>
            <div style="background-color: #E8E8E8; border: 1px black solid; border-radius: 50%; text-align:center;">
              
            Ticket:  {{ App\Models\Tickets::find($comentario->ticket_id)->titulo}} <br>
            Usuario:  {{App\User::find($comentario->user_id)->nombre}}<br>
             @if (isset($comentario->archivo))
                <span>Archivo: {{$comentario->archivo()}}</span>
             @endif
            </div>
        </div>

    </table>

      <table class="button">
      <tr>
        <td>
            <a href="{{url('/ticket/ver/'.$comentario->ticket_id)}}"> Ver Ticket</a>
        </td>
      </tr>
    </table>
    
    <a href="{{url('/')}}">
        {{Html::image(asset('img/logo.png'))}}
    </a>
</body>
</html>
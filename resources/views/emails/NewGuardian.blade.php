<html>
<head>
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/zurb-ink/1.0.5/ink.min.css" />
</head>

<body>
    <table class="container">
        <div>
            <h3>Hola, {{ $guardian->nombre}}</h3>
            <p>A usted se le ha transferido un ticket</p>
            <div style="background-color: #DCDCDC; border: dashed 1px black; ">
              
              Ticket:  {{$ticket->titulo}} <br>
              Usuario:  {{ $user->nombre}}<br>

            </div>
        </div>

    </table>

      <table class="button">
      <tr>
        <td>
            <a href="{{url('/ticket/ver/'.$ticket->id)}}"> Ver Ticket</a>
        </td>
      </tr>
    </table>
    
    <a href="{{url('/')}}">
        {{Html::image(asset('img/logo.png'))}}
    </a>
</body>
</html>
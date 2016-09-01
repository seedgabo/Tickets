<html>
<head>
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/zurb-ink/1.0.5/ink.min.css" />
</head>

<body>
    <table class="container">
        <div>
            <h3 style="color:red">Caso Por vencer: {{$ticket->titulo}} de {{$ticket->categoria->nombre}} </h3>
            <p>este ticket esta a punto de llegar a su fecha limite, por favor responda y completelo</p>
            <div style="background-color: #E2E2E2; ">
              
              Caso:  {{$ticket->titulo}} <br>
              Usuario:  {{ $user->nombre}}<br>
              Guardian: {{$ticket->guardian->nombre}} <br>

            </div>
        </div>

    </table>

      <table class="button">
      <tr>
        <td>
            <a href="{{url('/ticket/ver/'.$ticket->id)}}"> Ver Caso</a>
        </td>
      </tr>
    </table>
    
    <a href="{{url('/')}}" style="width:300px;">
        {{Html::image(asset('img/logo.png'))}}
    </a>
</body>
</html>
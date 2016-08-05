<html>
<head>
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/zurb-ink/1.0.5/ink.min.css" />
</head>

<body>
    <table class="container">
        <div>
            <h3>Hola, {{$user->nombre}}</h3>
             El Usuario {{$creador->nombre}} ha asignado un nuevo Ticket a: <span style="color:red">{{$guardian->nombre}}</span> en la categoria:
            <span style="color:blue;">Categoria: {{\App\Models\CategoriasTickets::find($ticket->categoria_id)->nombre}}<span>
        </div
        <br><br><br>
        <div style="background-color: #A4EDFF; border: 1px dashed black; border-radius: 50px; padding: 30px;" >
            <h1>{{$ticket->titulo}}</h1>
            <p> {!! str_limit($ticket->contenido, 200)  !!}</p>
        </div>
    </table>
            Con fecha limite el 
            <b><span style="color:red"> {{\App\Funciones::transdate($ticket->vencimiento)}} </span></b>  

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
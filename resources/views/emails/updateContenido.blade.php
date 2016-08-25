<html>
<head>
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/zurb-ink/1.0.5/ink.min.css" />
</head>

<body>
    <table class="container">
        <div>
            <p>Se ha actualizado el contenido del caso: <b>{{$ticket->titulo}}</b></p><br>
    </table>
    
    <a href="{{url('/')}}" style="width:300px; height: 300px;">
        {{Html::image(asset('img/logo.png'))}}
    </a>
</body>
</html> 
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Vista de Categorias</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
</head>
<style>
  li{
      margin-top: 10px;
  }
</style>

<body>
    <h2>Vista de Categorias de Tickets</h2> 
    {!! \App\Models\CategoriasTickets::menu(\App\Models\CategoriasTickets::all(),0) !!}
</body>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>


</html>
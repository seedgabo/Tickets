<?php 
    $cantidad_total= 0;
    $subtotal = 0;
    $total = 0;
 ?>
<html>
<head>
    <link rel="stylesheet" href= "{{asset('css/bootstrap-email.css')}}" />
    <style type="text/css" media="screen">
        body
        {
            font-family: 'Helvetica Neue';
            font-size: 14px;
            font-weight: 12px;
        }
    </style>
</head>

<body>
    <h3 class="container">Su Compra ha sido Procesada</h3>
    <table class="table table-bordered table-condensed table-hover text-center">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($productos as $producto)
                <tr>
                    <td>{{$producto->COD_REF}}</td>
                    <td>{{$producto->NOM_REF}}</td>
                    <td>{{number_format($producto->VAL_REF,2,",",".")}}</td>
                    <td>{{$producto->cantidad}}</td>
                    <?php $subtotal = $producto->VAL_REF * $producto->cantidad; $cantidad_total+= $producto->cantidad ?>
                    <td>{{number_format($subtotal,2,",",".")}}</td>
                    <?php   $total += $subtotal; ?>
                </tr>
            @empty
            @endforelse
            </tbody>
    </table>
    <div class="well">
        <div class="container">
            <p>TOTAL: {{ number_format($total,2,",",".")}}</p>
            <p>Cliente: {{ $cliente['NOM_TER'] ."-" .  $cliente['COD_TER']}}</p>
            <p>Usuario: {{$user->nombre . "-". $user->email}}</p>
        </div>
    </div>
    <a href="http://siasoftsas.com">
        {{Html::image('http://siasoftsas.com/public/img/logo.png')}}
    </a>
</body>
</head>
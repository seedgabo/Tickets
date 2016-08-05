@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h3>{{$empresa->nombre}}</h3>
    <h4>{{$cliente['NOM_TER']}}</h4>
        <div class="col-md-6">
                <div class="panel panel-info hover">
                    <div class="panel-body text-center">
                      <a href="#" onclick="alert('No Disponible')" disabled  class="disabled">
                        <img src="{{asset('img/cartera.jpg')}}" height="200" alt="">
                        <h5>Cartera</h5>
                      </a>
                    </div>
                </div>
            </div>
             <div class="col-md-6">
                <div class="panel panel-info hover">
                    <div class="panel-body text-center">
                      <a href="{{url('catalogo')}}">
                        <img src="{{asset('img/ventas.png')}}" height="200" alt="">
                        <h5>Ventas</h5>
                      </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-info hover">
                    <div class="panel-body text-center">
                      <a href="{{url('carrito')}}">
                        <img src="{{asset('img/carrito.png')}}" height="200" alt="">
                        <h5>Carrito</h5>
                      </a>
                    </div>
                </div>
            </div>
</div>

@stop
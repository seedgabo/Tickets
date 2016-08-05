<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use  App\Dbf;
use  App\Funciones;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class ApiController extends Controller
{
    /**
     * [doLogin description]
     * @method doLogin
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function doLogin (Request $request){

        $response = Auth::user();
        $response["img"] = Funciones::getUrlProfile();
        return $response;
    }

    /**
     * [getEmpresas description]
     * @method getEmpresas
     * @param  Request     $request [description]
     * @return [type]               [description]
     */
    public function getEmpresas (Request $request){

        $empresas = Auth::user()->empresas();

        return \Response::json($empresas, 200);
    }

    /**
     * [getClientes description]
     * @method getClientes
     * @param  Request     $request [description]
     * @param  [type]      $empresa [description]
     * @return [type]               [description]
     */
    public function getClientes (Request $request, $empresa){

        $request->session()->put('empresa',$empresa);
        $clientes = \App\Cliente::where("empresa_id",$empresa)->orWhereNull("empresa_id")->get();
        return \Response::json($clientes, 200);
    }

    /**
     * [getProductos description]
     * @method getProductos
     * @param  Request      $request [description]
     * @param  [type]       $empresa [description]
     * @return [type]                [description]
     */
    public function getProductos (Request $request, $empresa){
        $request->session()->put('empresa',$empresa);
        $query = \App\Producto::where("empresa_id",$empresa)->orWhereNull("empresa_id");
        if ($request->input('page', -1) == -1)
        {
            $productos = $query->get();
        }
        else
        {
            $productos = $query->paginate(50);
        }

        foreach ($productos as $producto) {
          $producto->imagen = Funciones::getUrlProducto($producto);
          $array[] = $producto;
        }
        return $productos;
    }


    public function searchProducto (Request $request, $empresa){
        $request->session()->put('empresa',$empresa);
        $query = \App\Producto::where("empresa_id",$empresa);
        $query  = $query->where(function($q) use($request){
            $q->orWhere("COD_REF", "LIKE", "%". $request->input("query","") ."%");
            $q->orWhere("NOM_REF", "LIKE", "%". $request->input("query","") ."%");
            $q->orWhere("COD_TIP", "LIKE", "%". $request->input("query","") ."%");
        });

        $productos = $query->paginate(50);


        foreach ($productos as $producto) {
          $producto->imagen = Funciones::getUrlProducto($producto);
          $array[] = $producto;
        }
        return $productos;
    }

    public function producto(Request $request, $cod){
        $producto = \App\Producto::where("COD_REF","=",$cod)->first();
        $producto->imagen = Funciones::getUrlProducto($producto);
        return $producto;
    }



    public function getCartera (Request $request, $empresa){

        $request->session()->put('empresa',$empresa);
        if (Auth::user()->COD_CLI != "") {
            $cartera =  \App\Cartera::where("COD_TER", Auth::user()->COD_CLI)->where("empresa_id",$empresa)->orWhereNull("empresa_id")->get();
        }
        else {
            $cartera =  \App\Cartera::where("empresa_id",$empresa)->orWhereNull("empresa_id")
            ->orderby("NOM_TER","asc")->get();
        }

         $porcliente= $cartera->groupBy('COD_TER');
         $total = $cartera->sum("SALDO");
        if(sizeof($porcliente) > 1)
        {
            foreach ($porcliente as  $COD_TER => $clientes)
            {
                $cliente[$COD_TER] = $clientes[0];
                $cliente[$COD_TER]["TOTAL"] = $clientes->sum("SALDO");
                $cliente[$COD_TER]["SIN_VEN"] = $clientes->sum("SIN_VEN");
                $cliente[$COD_TER]["A130"] = $clientes->sum("A130");
                $cliente[$COD_TER]["A3160"] = $clientes->sum("A3160");
                $cliente[$COD_TER]["A6190"] = $clientes->sum("A6190");
                $cliente[$COD_TER]["A91120"] = $clientes->sum("A91120");
                $cliente[$COD_TER]["MAS120"] = $clientes->sum("MAS120");
            }
        }
        return json_encode(["cartera" => array_values($cliente), "total" => $total]);
    }


    public function porCliente(Request $request, $empresa,$codigo){
        $cliente =  \App\Cartera::where("COD_TER","LIKE",$codigo."%")->get();
        return json_encode(["cliente" => $cliente]);
    }


    public function procesarCarrito(Request $request, $empresa){
        $request->session()->put('empresa',$empresa);
        $data = $request->all();
        $fecha = new \Carbon\Carbon();
        $fecha = $fecha->format('d/m/Y');

        //Limpiar Carrito
        if(isset($data[0]))
            Carritos::where('user_id',Auth::user()->id)
            ->where('COD_CLI', $data[0]["COD_CLI"])
            ->delete();

        $productos =[];
        foreach ($data as $producto) {

            $carrito = new  \App\Carritos();
            $carrito->user_id  = Auth::user()->id;
            $carrito->NOM_REF  =  $producto["NOM_REF"];
            $carrito->empresa_id  = $producto["empresa_id"];
            $carrito->COD_REF  = $producto["COD_REF"];
            $carrito->cantidad = $producto["cantidad"];
            $carrito->VAL_REF  = $producto["VAL_REF"];
            $carrito->COD_CLI  = $producto["COD_CLI"];
            $carrito->COD_VEN  = Auth::user()->cod_vendedor;
            $carrito->fecha    =  $fecha;
            $carrito->save();
            $producos[]= $carrito;
        }
         $response = Funciones::procesarCarrito($productos);
         return json_encode(["result" => $response,"items" => sizeof($productos)]);
    }

}

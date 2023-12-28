<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;


class IndexProductoController extends Controller
{
    public function index()
    {
        //
        try {
            // Obtener todos los productos con sus relaciones
            $productos = Producto::with(['producto_sabores', 'relleno', 'catalogo', 'producto_coberturas'])->get();
            
            // Iterar sobre los productos y cargar los sabores en un array
            $response = $productos->map(function ($producto) {
                $productoArray = $producto->toArray();
                
                // Obtener los sabores y agregarlos al array del producto
                $sabores = $producto->producto_sabores->map(function ($productoSabor) {
                    return $productoSabor->sabor->toArray();
                });
                
                $productoArray["sabores"] = $sabores->toArray();
                
                return $productoArray;
            });
            
            return $response;
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'data' => null], 500);
        }
    }

}

<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Sabor;
use App\Models\ProductoCobertura;
use App\Models\ProductoPromocion;
use App\Models\ProductoSabor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use App\Http\Controllers\CoberturaController;
use App\Models\Cobertura;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        // Consulta para obtener productos activos
        $productosActivos = Producto::where('estado', 'activo')
            ->where('nombre', 'like', "%$search%")
            ->with(['sabores','relleno', 'catalogo'])
            ->get();

        // Consulta para obtener productos inactivos
        $productosInactivos = Producto::where('estado', 'inactivo')
            ->where('nombre', 'like', "%$search%")
            ->with(['sabores','relleno', 'catalogo',])
            ->get();

        // Combina los resultados de ambas consultas
        $response = [
            'productos_activos' => $productosActivos,
            'productos_inactivos' => $productosInactivos,
        ];

        return $response;
    }

    //ale
    public function productInvent()
{
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




    public function asociarSaboresConProducto($idProducto, $idSabor)
    {
        // Busca el producto y el sabor por sus respectivos IDs
        $producto = Producto::find($idProducto);
        $sabor = Sabor::find($idSabor);

        // Verifica si ambos existen
        if (!$producto || !$sabor) {
            return response()->json(['mensaje' => 'Producto o sabor no encontrado'], 404);
        }

        // Asocia el sabor con el producto
        $producto->sabores()->attach($sabor->id);

        return response()->json(['mensaje' => 'Sabor asociado con éxito'], 200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.productos');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            $errores = 0;
            DB::beginTransaction();
            $producto = new Producto();
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precio = $request->precio;
            $producto->imagen = $request->imagen;
            $producto->relleno_id = $request->relleno_id;
            $producto->catalogo_id = $request->catalogo_id;
            //comprovando si viene una imagen
            if($request->hasFile('imagen')){
                //obteniendo el archivo de una imagen
                $imagen = $request->file('imagen');
                //generando un nombre unico para la imagen
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                //subiendo la imagen a una carpeta del servidor
                $imagen->move(public_path('images/productos/'),$nombreImagen);
                $producto->imagen = $nombreImagen;
            }else{
                $producto->imagen = "none.jpg";
            }

            if($producto->save() <= 0)
            {
                $errores++;
            }
            // Guardar las relaciones con sabores
         // Obtener sabores seleccionados desde la cadena JSON
         $saboresSeleccionados = json_decode($request->input('saboresSeleccionados'));

         // Asociar sabores usando la relación belongsToMany
         $producto->sabores()->sync($saboresSeleccionados);
         // Guardar las relaciones con sabores
         $sabores = $request->productoSabor;
         if (!is_null($sabores) && is_array($sabores)) {
             $producto->sabores()->sync($sabores); // Utiliza sync para sincronizar las relaciones
         }
            //guardando la relcion de promociones 
           /* $promocio = $request->productoPromocion;
           if (!is_null($promocio) && is_array($promocio)) 
            {
                foreach ($promocio as $key => $promo) {
                    $productoPromocion = new ProductoPromocion();
                    $productoPromocion->promocion_id = $promo['id'];
                    $productoPromocion->producto_id = $producto->id;
    
                    if ($productoPromocion->save() <= 0) {
                        $errores++;
                    }
                }
            }*/
            //GUARDANDO LA COBERTURA
            
            $cobertu = $request->productoCobertura;
            if (!is_null($cobertu) && is_array($cobertu))
            {
                foreach ($cobertu as $key => $cob) {
                    $productoCobertura = new ProductoCobertura();
                    $productoCobertura->cobertura_id = $cob['id'];
                    $productoCobertura->producto_id = $producto->id;
    
                    if ($productoCobertura->save() <= 0) {
                        $errores++;
                    }
                }
            }       

            if($errores == 0)
            {
                DB::commit();
                return response()->json(['status'=>'ok','data'=>$producto],201);
            }else{
                DB::rollBack();
                return response()->json(['status'=>'fail','data'=>null],409);
            }
        }catch(\Exception $e)
            {
                //DB::rollBack();
            return $e->getMessage();
            }        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try{
            $producto = Producto::findOrFail($id);
            $producto = Producto::with('sabores', 'relleno', 'catalogo', 'producto_coberturas')
            ->findOrFail($id);
       
            //convirtienod en array
            $response = $producto->toArray();
            $response["relleno"]= $producto->relleno->toArray();
            $response["sabores"]= $producto->sabores->toArray();
            $response["catalogo"]= $producto->catalogo->toArray();
            
          
           // $response["promocion"] = $producto->promocion->toArray();  
          // Calcula el costo total, incluyendo las coberturas adicionales
        $costoTotal = $producto->precio;


        // Agrega el costo total al array de respuesta
        $response["costo_total"] = $costoTotal;

        return response()->json(['status' => 'ok', 'data' => $response], 200);
    } catch (\Exception $e) {
        return $e->getMessage();
    }
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        try {
            $errores = 0;
            DB::beginTransaction();
            $producto = Producto::findOrFail($id);
            $imagenAnterior = $producto->imagen;
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->imagen = $request->imagen;
            $producto->relleno_id = $request->relleno_id;
            $producto->catalogo_id = $request->catalogo_id;
    
            // COMPROBANDO SI VIENE UNA IMAGEN
            if ($request->hasFile('imagen')) {
                // eliminando el archivo anterior
                $imagePath = public_path() . '/images/productos/' . $imagenAnterior;
                if ($imagenAnterior != 'none.jpg') {
                    unlink($imagePath);
                }
                // obteniendo el archivo de imagen
                $imagen = $request->file('imagen');
                // generando un nombre único para la imagen
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                // subiendo la imagen a una carpeta del servidor
                $imagen->move(public_path('images/productos/'),$nombreImagen);
                $producto->imagen = $nombreImagen;
            }
            
            if($producto->update() <= 0)
                {
                    $errores++;
                }
    
            // Guardar las relaciones con sabores
    // Obtener sabores seleccionados desde la cadena JSON
    $saboresSeleccionados = json_decode($request->input('saboresSeleccionados'));

    // Asociar sabores usando la relación belongsToMany
    $producto->sabores()->sync($saboresSeleccionados);
    // Guardar las relaciones con sabores
    $sabores = $request->productoSabor;
    if (!is_null($sabores) && is_array($sabores)) {
        $producto->sabores()->sync($sabores); // Utiliza sync para sincronizar las relaciones
    }
    
            // Guardando la relación de promociones
            $promociones = $request->productoPromocion;
            if (!is_null($promociones) && is_array($promociones)) {
                foreach ($promociones as $key => $promocion) {
                    $productoPromocion = ProductoPromocion::findOrFail($promocion['id']);
                    $productoPromocion->promocion_id = $promocion['id'];
                    $productoPromocion->producto_id = $producto->id;
    
                    if ($productoPromocion->update() <= 0) {
                        $errores++;
                    }
                }
            }
    
            // GUARDANDO LA COBERTURA
            $coberturas = $request->productoCobertura;
            if (!is_null($coberturas) && is_array($coberturas)) {
                foreach ($coberturas as $key => $cobertura) {
                    $productoCobertura = ProductoCobertura::findOrFail($cobertura['id']);
                    $productoCobertura->cobertura_id = $cobertura['id'];
                    $productoCobertura->producto_id = $producto->id;
    
                    if ($productoCobertura->update() <= 0) {
                        $errores++;
                    }
                }
            }
    
            if($errores == 0)
            {
                DB::commit();
                return response()->json(['status'=>'ok','data'=>$producto],202);
            }else{
                DB::rollBack();
                return response()->json(['status'=>'fail','data'=>null],409);
            }
        }catch(\Exception $e)
            {
                //DB::rollBack();
            return $e->getMessage();
            }        
    }
    

    /**
     * Update an active product.
     */
    

public function desactivarProducto($id)
{
    try {
        $producto = Producto::findOrFail($id);

        // Verifica si el producto ya está inactivo antes de intentar desactivarlo nuevamente
        if ($producto->estado === 'inactivo') {
            return response()->json(['message' => 'El producto ya está inactivo'], 200);
        }

        $producto->estado = 'inactivo';
        $producto->save();

        return response()->json(['message' => 'Producto desactivado con éxito']);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['message' => 'Producto no encontrado'], 404);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al desactivar el producto', 'error' => $e->getMessage()], 500);
    }
}

public function activarProducto($id)
{
    try {
        $producto = Producto::findOrFail($id);

        // Verifica si el producto ya está inactivo antes de intentar desactivarlo nuevamente
        if ($producto->estado === 'activo') {
            return response()->json(['message' => 'El producto ya está activo'], 200);
        }

        $producto->estado = 'activo';
        $producto->save();

        return response()->json(['message' => 'Producto activo con éxito']);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['message' => 'Producto no encontrado'], 404);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al activar el producto', 'error' => $e->getMessage()], 500);
    }
}

}
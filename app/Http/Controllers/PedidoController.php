<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use App\Models\DetallePedido;
use Cart;
use App\Models\Inventario;
use App\Models\Producto;



class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try{
            $pedidos = Pedido::all();
            $response = $pedidos->toArray();
            $i = 0;
            foreach($pedidos as $pedido){
                $response[$i]['user'] = $pedido->user->toArray();
                $detalle = $pedido->detalle_pedidos->toArray();
                $f=0;
                foreach($pedido->detalle_pedidos as $d){
                    $detalle[$f]['producto'] = $d->producto->toArray();
                    $detalle[$f]['producto']['sabor'] = $d->producto->sabor->toArray();
                    $detalle[$f]['producto']['catalogo'] = $d->producto->catalogo->toArray();
                    $f++;
                }
                return $response[$i]['detallePedidos'] = $detalle;
                $i++;
            }
           return $response;
        }catch(\Exception $e)
        {
            return $e->getMessage();
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.pedidos');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Crea una instancia de Pedido y guarda los detalles del pedido
            $pedido = new Pedido();
            $pedido->direccion_envio = $request->direccionEnvio;
            $pedido->hora = $request->hora;
            $pedido->total = $request->total;
            $pedido->telefono = $request->telefono;
            $pedido->costo_envio = $request->costoEnvio;
            $pedido->fecha = $request->fecha;
            $pedido->estado = 'R'; // Cambia esto según tu lógica de estados
            $pedido->user_id = $request->user['id'];

            if (!$pedido->save()) {
                DB::rollBack();
                return response()->json(['status' => 'fail', 'message' => 'Error al crear el pedido'], 500);
            }

            // Guarda los detalles del pedido
            foreach ($request->detallePedido as $detalle) {
                $detallePedido = new DetallePedido();
                $detallePedido->producto_id = $detalle['producto']['id'];
                $detallePedido->pedido_id = $pedido->id;
                // Agrega otros campos del detalle de pedido si los tienes
                if (!$detallePedido->save()) {
                    DB::rollBack();
                    return response()->json(['status' => 'fail', 'message' => 'Error al guardar los detalles del pedido'], 500);
                }
            }

            DB::commit();

            return response()->json(['status' => 'ok', 'message' => 'Pedido guardado con éxito'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($estado)
    {
        try{
            $pedidos = Pedido::where('estado','=',$estado)->get();
            $response = $pedidos->toArray();
            $i=0;
            foreach($pedidos as $pedido){
            $response[$i]['user'] = $pedido->user->toArray();
            $detalle = $pedido->detalle_pedidos->toArray();

            $f=0;
            foreach($pedido-> detalle_pedidos as $d){
                    $detalle[$f]['producto'] = $d->producto->toArray();
                    $detalle[$f]['producto']['relleno'] = $d->producto->relleno->toArray();
                    $detalle[$f]['producto']['sabores'] = $d->producto->sabores->toArray();
                    $detalle[$f]['producto']['catalogo'] = $d->producto->catalogo->toArray();
                    $detalle[$f]['cantidad'] = $d->cantidad;
                    $f++;
                }
                $response[$i]['detallePedidos'] = $detalle;
                $i++;
            }
            return $response;
        }catch(\Exception $e){
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try{
            $errores = 0;
            DB::beginTransaction();
        
        $pedido = Pedido::findOrFail($id);
        if($request->estado == 'E'){
            //cuando se entrega el o los vehiculos al cliente
           $pedido->estado = $request->estado;
           $pedido->total = $request->total;
           //$alquiler->observaciones = $request->observaciones;
           if($pedido->update()<=0){
            $errores++;
           }
           $detalle = $request->detallePedido;
           foreach($detalle as $key => $det){
            $detallePedido = DetallePedido::findOrFail($det['id']);
            $detallePedido->cantidad = $det['cantidad'];
            if (!$detallePedido->update()<=0) {
                $errores++;
            }
            
        }
         }elseif($request->estado == 'A'){ 
            //cuando el clientedevuelve los vehiculos 
            $pedido->estado = $request->estado;
            $pedido->fecha_entrega = $request->fecha_entrega;
           // $pedido->observaciones = $request->observaciones;
            if(!$pedido->update()<=0){
                $errores++;
               }
               $detalle = $request->detallePedido;
               foreach($detalle as $key => $det){
                $detallePedido = DetallePedido::findOrFail($det['id']);
                $detallePedido->precio_unitario = $det['precioUnitario'];
                $detallePedido->cantidad = $det['cantidad'];
                if($detallePedido->update()<=0){
                    $errores++;
               }
             }
        }else{
            //cuando la reserva sea cancelada (el cliente ya no hara el alquiler)
            //cambiar el estado a C de cancelado el alquiler
            $pedido->estado = $request->estado;
            //$pedido->observaciones = $request->observaciones;
            if($pedido->update()<=0){
             $errores++;
            }
        }
        if ($errores == 0) {
            DB::commit();
            return response()->json(['status'=>'ok','data'=>$pedido],202);
        }else{
            DB::rollBack();
            return response()->json(['status'=>'fail','data'=>null],409);
        }
    }catch(\Exception $e){
       return $e->getMessage(); 
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function finalizar(Request $request)
    {
        // Validar los datos del formulario
        $this->validate($request, [
            'direccion_envio' => 'required',
            'telefono' => 'required',
            'fecha_entrega' => 'required|date|after_or_equal:today',
        ], [
            'direccion_envio.required' => 'El campo Dirección de Envío es obligatorio.',
            'telefono.required' => 'El campo Teléfono es obligatorio.',
            'fecha_entrega.required' => 'El campo Fecha de Entrega es obligatorio.',
            'fecha_entrega.date' => 'El campo Fecha de Entrega debe ser una fecha válida.',
            'fecha_entrega.after_or_equal' => 'El campo Fecha de Entrega debe ser igual o posterior a hoy.',
        ]);
    
        // Crear un nuevo pedido
        $pedido = new Pedido();
        $pedido->correlativo = $this->getCorrelativo();
        $pedido->direccion_envio = $request->direccion_envio;
        $pedido->hora = $request->hora;
        $pedido->telefono = $request->telefono;
        // Obtener el tipo de pedido
        $tipoPedido = $request->input('tipo_pedido');

        // Configurar el estado del pedido según el tipo seleccionado
        $estadoPedido = ($tipoPedido == 'express') ? 'E' : 'A';
         $pedido->estado = $estadoPedido;
        $pedido->fecha_entrega = $request->fecha_entrega;
        $pedido->total = Cart::getTotal();
        // Comparar la fecha de entrega con la fecha actual
        $fechaEntrega = new \DateTime($request->fecha_entrega);
        $fechaActual = new \DateTime();

        // Asignar el estado correspondiente
        $pedido->estado = ($fechaEntrega <= $fechaActual) ? 'E' : 'A';
        
        $pedido->user_id = auth()->user()->id; // que el usuario esté autenticado
    
        if (!$pedido->save()) {
            return back()->with('error', 'Hubo un problema al guardar el pedido.');
        }
        
    
        // Guardar los detalles del pedido
        foreach (Cart::getContent() as $item) {
            $detallePedido = new DetallePedido();
            $detallePedido->pedido_id = $pedido->id;
            $detallePedido->producto_id = $item->id;
            $detallePedido->cantidad = $item->quantity;

    
            // Obtener el producto y su inventario
            $producto = Producto::find($item->id);
            $inventario = Inventario::where('producto_id', $producto->id)
                ->orderBy('fecha_vencimiento', 'asc')
                ->get();
    
            // Verificar si hay suficientes productos en inventario
           // Verificar si hay suficientes productos en inventario
if ($tipoPedido == 'express' && !$this->verificarInventario($inventario, $detallePedido->cantidad)) {
    DB::rollBack();
    return redirect()->route('cart.index')->with('success_msg', 'No hay suficientes existencias en el inventario para cubrir la cantidad solicitada de ' . $producto->nombre);
}

    
            // Guardar el detalle del pedido
            if (!$detallePedido->save()) {
                DB::rollBack();
                return back()->with('error', 'Hubo un problema al guardar los detalles del pedido.');
            }
        }

        // Restar la cantidad en inventario solo después de confirmar que hay suficientes existencias (solo para pedidos express 'E')
if ($tipoPedido == 'express') {
    foreach (Cart::getContent() as $item) {
        $producto = Producto::find($item->id);
        $quantityToSubtract = $item->quantity;

        // Verificar el estado del pedido antes de restar el inventario
        $detallePedido = DetallePedido::where('pedido_id', $pedido->id)
            ->where('producto_id', $producto->id)
            ->first();

        // Solo restar el inventario si el estado del pedido es 'E' (entregado)
        if ($detallePedido && $pedido->estado == 'E') {
            $this->restarInventario($producto, $quantityToSubtract);
        }
    }
}
    
        // Vaciar el carrito
        Cart::clear();
    
        // Si llegamos aquí, la transacción fue exitosa
        return redirect()->route('cart.index')->with('success_msg', 'Pedido realizado con éxito pronto se contactaran con usted para confirmar.');

// Restar la cantidad en inventario solo después de confirmar que hay suficientes existencias
foreach (Cart::getContent() as $item) {
    $producto = Producto::find($item->id);
    $quantityToSubtract = $item->quantity;

    // Verificar el estado del pedido antes de restar el inventario
    $detallePedido = DetallePedido::where('pedido_id', $pedido->id)
        ->where('producto_id', $producto->id)
        ->first();

    // Solo restar el inventario si el detalle del pedido tiene un estado 'E' (entregado)
    if ($detallePedido && $pedido->estado == 'E') {
        $this->restarInventario($producto, $quantityToSubtract);
    }
}

// Vaciar el carrito
Cart::clear();

// Si llegamos aquí, la transacción fue exitosa
return redirect()->route('cart.index')->with('success_msg', 'Pedido realizado con éxito.');
}
   
    
    // Verificar si hay suficientes productos en inventario
    private function verificarInventario($inventario, $cantidad)
    {
        $cantidadDisponible = 0;
    
        foreach ($inventario as $inv) {
            $cantidadDisponible += $inv->cantidad_inventario;
        }
    
        return $cantidadDisponible >= $cantidad;
    }
    
    // Restar la cantidad en inventario
    private function restarInventario($producto, $cantidad)
    {
        $inventarios = Inventario::where('producto_id', $producto->id)
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();
    
        foreach ($inventarios as $inventario) {
            if ($inventario->cantidad_inventario >= $cantidad) {
                // Restar la cantidad en el inventario y salir del bucle
                $inventario->cantidad_inventario -= $cantidad;
                $inventario->save();
                break;
            } else {
                // Restar la cantidad disponible en este inventario
                $cantidad -= $inventario->cantidad_inventario;
                $inventario->cantidad_inventario = 0;
                $inventario->save();
            }
        }
    }
    

private function getCorrelativo()
{
    $result = DB::select("SELECT
    CONCAT(TRIM(YEAR(CURDATE())),LPAD(TRIM(MONTH(CURDATE())),2,0),LPAD(IFNULL(MAX(TRIM(SUBSTRING(correlativo,7,4))),0)+1,4,0)) as correlativo FROM pedidos WHERE SUBSTRING(correlativo,1,6) = CONCAT(TRIM(YEAR(CURDATE())),LPAD(TRIM(MONTH(CURDATE())),2,0))");
    return $result[0]->correlativo;
}

 /**
     * Cambiar el estado de un pedido.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeState(Request $request, $id)
    {
        try {
            // Buscar el pedido por ID
            $pedido = Pedido::findOrFail($id);

            // Validar el nuevo estado (puedes personalizar esto según tus estados)
            $nuevoEstado = $request->input('nuevoEstado');
            if (!in_array($nuevoEstado, ['R', 'E', 'A', 'P'])) {
                return response()->json(['status' => 'fail', 'message' => 'Estado no válido'], 422);
            }

            // Cambiar el estado del pedido
            $pedido->estado = $nuevoEstado;

            // Guardar el pedido actualizado
            if ($pedido->save()) {
                return response()->json(['status' => 'ok', 'message' => 'Estado del pedido cambiado con éxito'], 200);
            } else {
                return response()->json(['status' => 'fail', 'message' => 'Error al cambiar el estado del pedido'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error interno del servidor'], 500);
        }
    }

    public function cancelarPedido($id){
    try {
        $pedido = Pedido::findOrFail($id);
        $pedido->estado = 'C';  // Cambia 'C' según tus estados
        $pedido->save();

        return response()->json(['status' => 'ok', 'message' => 'Pedido cancelado con éxito'], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Error al cancelar el pedido'], 500);
    }
}


public function historialPedidos()
{
    // Obtener historial de pedidos del usuario actual con los estados deseados
    $historialPedidos = auth()->user()->pedidos()
        ->whereIn('estado', ['E', 'A'])
        ->orderBy('created_at', 'desc')
        ->get();

    // Obtener información adicional
    $historialPedidosConInfoAdicional = [];
    foreach ($historialPedidos as $pedido) {
        $infoAdicional = DB::table('detalle_pedidos')
            ->join('productos', 'detalle_pedidos.producto_id', '=', 'productos.id')
            ->select('productos.nombre as producto_nombre', 'detalle_pedidos.cantidad')
            ->where('pedido_id', $pedido->id)
            ->get();

        $historialPedidosConInfoAdicional[] = [
            'pedido' => $pedido,
            'infoAdicional' => $infoAdicional,
        ];
    }

    return view('historial_pedidos', compact('historialPedidosConInfoAdicional'));
}
public function anularPedido($id)
{
    try {
        // Obtener el pedido a anular
        $pedido = Pedido::findOrFail($id);
        
        // Asegurarse de que el pedido pertenece al usuario actual
        if ($pedido->user_id != auth()->id()) {
            abort(403, 'No tienes permisos para anular este pedido.');
        }

        // Verificar si es un pedido express para sumar las existencias al inventario
        if ($pedido->estado == 'E') {
            $this->sumarExistenciasAlInventario($pedido);
        }

        // Cambiar el estado del pedido a 'C' (cancelado)
        $pedido->estado = 'C';
        $pedido->save();

        return redirect()->route('historial_pedidos')->with('success_msg', 'Pedido anulado con éxito.');
    } catch (\Exception $e) {
        return redirect()->route('historial_pedidos')->with('error_msg', 'Error al anular el pedido.');
    }
}

private function sumarExistenciasAlInventario($pedido)
{
    try {
        DB::beginTransaction();

        // Obtener los detalles del pedido
        $detallesPedido = DetallePedido::where('pedido_id', $pedido->id)->get();

        // Sumar las existencias al inventario
        foreach ($detallesPedido as $detalle) {
            $producto = Producto::find($detalle->producto_id);
            $cantidad = $detalle->cantidad;

            // Sumar la cantidad al inventario
            $this->sumarInventario($producto, $cantidad);
        }

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}



private function sumarInventario($producto, $cantidad)
{
    try {
        // Obtener el inventario actual del producto
        $inventario = Inventario::where('producto_id', $producto->id)
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();

        // Sumar la cantidad al inventario existente
        foreach ($inventario as $inventarioItem) {
            $inventarioDisponible = $inventarioItem->cantidad_inventario;
            if ($inventarioDisponible > 0) {
                $cantidadRestante = min($cantidad, $inventarioDisponible);
                $inventarioItem->cantidad_inventario += $cantidadRestante;
                $cantidad -= $cantidadRestante;
                $inventarioItem->save();
            }

            if ($cantidad <= 0) {
                break;
            }
        }
    } catch (\Exception $e) {
        throw $e;
    }
}


}
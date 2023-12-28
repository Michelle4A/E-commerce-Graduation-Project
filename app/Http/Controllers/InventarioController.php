<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Pedido;


class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try
        {
            // Cargar la relación 'producto' usando eager loading
            $inventarios = Inventario::with('producto')->get();
    
            return $inventarios;
        }
        catch(\Exception $e)
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
        return view('admin.inventarios');
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            $inventario = new Inventario();
            $inventario->cantidad_inventario = $request->cantidad_inventario;
            $inventario->fecha_agregado = $request->fecha_agregado;
            $inventario->fecha_vencimiento = $request->fecha_vencimiento;
            $inventario->producto_id = $request->input('producto_id');
            if($inventario->save() >= 1){
               
                return response()->json(['status'=>'ok','data'=>$inventario],201);
            }else{
                return response()->json(['status'=>'fail','data'=>null],409);
            }
        }catch(\Exception $e)
        {
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
            $inventario = Inventario::findOrFail($id);
            return $inventario;
        }catch(\Exception $e)
        {
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
            $inventario = Inventario::findOrFail($id);
            $inventario->cantidad_inventario = $request->cantidad_inventario;
            $inventario->fecha_agregado = $request->fecha_agregado;
            $inventario->fecha_vencimiento = $request->fecha_vencimiento;
            $inventario->producto_id = $request->input('producto_id');
            if($inventario->update() >= 1){
                return response()->json(['status'=>'ok','data'=>$inventario],201);
            }else{
                return response()->json(['status'=>'fail','data'=>null],409);
            }
        }catch(\Exception $e)
        {
            return $e->getMessage();
        }      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try{
            $inventario = Inventario::findOrFail($id);
            if($inventario->delete() >= 1){
                return response()->json(['status'=>'ok','data'=>$inventario],200);
            }else{
                return response()->json(['status'=>'fail','data'=>null],409);
            }
        }catch(\Exception $e)
        {
            return $e->getMessage();
        }      
    }

      // Nuevo método para evitar restar inventario en productos asociados a pedidos agendados
      private function debeRestarInventario($producto_id)
      {
          // Obtener el último pedido asociado al producto
          $ultimoPedido = Pedido::whereHas('detalle_pedidos', function ($query) use ($producto_id) {
              $query->where('producto_id', $producto_id);
          })->latest()->first();
  
          // Verificar si el último pedido es express ('E') o agendado ('A')
          return $ultimoPedido && $ultimoPedido->estado == 'E';
      }
}

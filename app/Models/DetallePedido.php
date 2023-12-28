<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    protected $table = "detalle_pedidos";

         //relaciones inversas
         public function pedido()
         {
             return $this->belongsTo(Pedido::class);
         }
 
     public function producto()
     {
         return $this->belongsTo(Producto::class);
     }    
}

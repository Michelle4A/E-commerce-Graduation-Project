<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoSabor extends Model
{
    use HasFactory;

    protected $table = 'producto_sabores';

    public function producto()
{
    return $this->belongsTo(Producto::class, 'producto_id');
}

public function sabor()
{
    return $this->belongsTo(Sabor::class, 'sabor_id');
}
            
}

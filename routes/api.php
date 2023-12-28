<?php

use App\Http\Controllers\BancoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaborController;
use App\Http\Controllers\RellenoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\CoberturaController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PedidoController;
//use App\Models\FormaPago;
use App\Models\Pedido;
use App\Models\Promocion;
use App\Http\Controllers\FormaPago;
use App\Http\Controllers\InventarioController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('inventarios', InventarioController::class);
Route::resource('sabores', SaborController::class);
Route::resource('rellenos', RellenoController::class);
Route::resource('catalogos', CatalogoController::class);
Route::resource('productos', ProductoController::class);
Route::resource('promociones', PromocionController::class);
Route::resource('coberturas', CoberturaController::class);
Route::resource('bancos', BancoController::class);
//Route::resource('forma_Pago', FormaPago::class);
//Route::get('/productos-get', [ProductoController::class, 'index']);
Route::resource('pedidos', PedidoController::class);
Route::get('promociones/state', [PromocionController::class, 'showByState']);
//Route::put('pedido/change', [PedidoController::class, 'changeState']);
//Route::get('pedidos/estado', [PedidoController::class, 'showByState']);
Route::get('/reportes/productos/pdf',[PDFController::class,'pdfProductos'])->name('pdfProductos');
Route::get('/reportes/reservas/rango',[PDFController::class,'pdfReservas'])->name('pdfReservas');

Route::get('/reportes/reservas',[PDFController::class,'viewReservas'])->name('report.reservas');

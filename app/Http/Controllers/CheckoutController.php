<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Muestra la vista del checkout.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Puedes agregar lógica adicional aquí según tus necesidades
        return view('checkout');
    }

    /**
     * Procesa el formulario de checkout y la forma de pago.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processCheckout(Request $request)
    {
        // Puedes validar los datos del formulario antes de procesarlos
        $request->validate([
            'direccion_envio' => 'required|string',
            'telefono' => 'required|string',
            'fecha_entrega' => 'required|date',
            'hora' => 'required|time',
            'metodo_pago' => 'required|in:tarjeta,efectivo', // Ajusta las opciones según tus métodos de pago
        ]);

        // Obtén los datos del formulario
        $direccionEnvio = $request->input('direccion_envio');
        $telefono = $request->input('telefono');
        $fechaEntrega = $request->input('fecha_entrega');
        $horaEntrega = $request->input('hora');
        $metodoPago = $request->input('metodo_pago');

        // Puedes realizar acciones adicionales aquí, como almacenar la información en la base de datos, etc.

        // Redirecciona o muestra un mensaje de éxito, según tus necesidades
        return redirect()->route('checkout.index')->with('success_msg', '¡Pedido realizado con éxito!');
    }
}

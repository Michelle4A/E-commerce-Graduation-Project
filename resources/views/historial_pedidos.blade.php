@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 80px">
        <h2>Historial de Pedidos</h2>
        @if(session()->has('success_msg'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session()->get('success_msg') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @endif

        @if(count($historialPedidosConInfoAdicional) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha de Entrega</th>
                        <th>Hora de Entrega</th>
                        <th>Estado</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Total Pedido</th>
                        <th>Costo Envio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historialPedidosConInfoAdicional as $historial)
                        @foreach($historial['infoAdicional'] as $detalle)
                            <tr>
                                <td>{{ $historial['pedido']->fecha_entrega }}</td>
                                <td>{{ $historial['pedido']->hora }}</td>
                                <td>
                                    @if ($historial['pedido']->estado == 'E')
                                        Express
                                    @elseif ($historial['pedido']->estado == 'A')
                                        Agendado
                                    @else
                                        {{ $historial['pedido']->estado }}
                                    @endif
                                </td>
                                <td>{{ $detalle->producto_nombre }}</td>
                                <td>{{ $detalle->cantidad }}</td> <!-- Muestra la cantidad por separado -->
                                <td>${{ $historial['pedido']->total + $historial['pedido']->costo_envio }}</td>
                                <td>{{ $historial['pedido']->costo_envio }}</td>
                                <td>
                                    <form id="anularForm_{{ $historial['pedido']->id }}" action="{{ route('anular_pedido', ['id' => $historial['pedido']->id]) }}" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmarAnulacion({{ $historial['pedido']->id }})">Anular</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay pedidos en tu historial.</p>
        @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

     <!-- Script para mostrar SweetAlert de confirmación -->
     <script>
        function confirmarAnulacion(pedidoId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¿Realmente deseas anular este pedido?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, anularlo'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviar el formulario
                    document.getElementById('anularForm_' + pedidoId).submit();
                }
            });
        }
    </script>
@endsection
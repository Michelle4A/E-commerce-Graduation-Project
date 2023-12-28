<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Exportar a PDF</title>
    <link rel="stylesheet" href="CSS/estilosPDF.css">
</head>
<body>
    <header>
       
        <h3>Cocoa Sweet</h3>
        <div class="header_logo">
            <img src="images/icono.png" alt="logo" id="logo">
        </div>
    </header>
    
    <main>
        
        <section class="report-section">
            
            <div class="container">
                <br>
                <h4 id="titulo_reporte">Reservas de Productos</h4>
                <h5 id="subtitulo_reporte">Periodo del: &nbsp;{{$fecha1}}&nbsp; Al {{$fecha2}}</h5>
        
                <?php $count = 0; ?>
                @foreach($datos as $item)
                <div style="align-content: space-around; font-size: 18px;">
                    <b >Correlativo:</b> &nbsp; {{$item->correlativo}}&nbsp;&nbsp; Fecha Reserva: &nbsp;{{$item->fecha_entrega}}
                </div>
                <!--Fecha Alquiler: &nbsp; {{$item->name}}-->
                <br>
                <div class="text-center" style="text-align: center; font:size 10px;">
                    <label for=""><strong>Detalle de la Reserva</strong></label>
                </div>
                <table id="inv">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                          
                        </tr>              
                    </thead>
                    <tbody>
                        @foreach($item->detalle as $det)
                        <tr>
                            <td>{{$det->nombre}}</td>
                            <td>{{$det->precio }}</td>
                            <td>{{$det->cantidad }}</td>
                          
                            
                        </tr>
                        @endforeach
                    </tbody>
                    <tr>
                        <td colspan="2">
                            Total a Pagar:
                            <td>
                                {{$item->total}}
                            </td>
                        </td>
                    </tr>
                </table>
                <br>
                <?php $count++; ?>
                @endforeach
                <hr>
                <label for="">Total De Pedido:</label>&nbsp; <strong><b>{{$count}}</b></strong>
            </div>
        </section>
    </main>
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $pdf->text(270, 800, "Pagina $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ');
        }

    </script>
</body>
</html>
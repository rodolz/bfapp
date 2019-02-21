<!DOCTYPE html>
<html>
    <header>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="{{ public_path() }}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <style>
            @media all {
                .page-break	{ display: none; }
            }

            @media print {
                .page-break	{ display: block; page-break-before: always; }
            }
            @page { margin-top: 5px; }
            .top-buffer { margin-top:20px; }
            .footer {
                position: fixed;
                height: 10px;
                bottom: 0;
                width: 100%;
                page-break-after:auto;
            }
            /* borderless table */
            .table-borderless td, .table-borderless th {
                border: 0 !important;
            }
        </style>
    </header>
    <body>
        <div class="row">
            <img src="{{ public_path() }}/images/cintillo_control_old.jpg" alt="bf_cintillo" width="750px"/> 
        </div>
        <div class="row">
            <div class="pull-left">
                <p><strong>Cliente: {{ $cotizacion->cliente->empresa }}</strong></p>
                <p><strong>Dirección:</strong> {{ $cotizacion->cliente->direccion }}</p>
                <p><strong>Contacto:</strong> {{ $cotizacion->cliente->contacto }}</p>
                <p><strong>Teléfono:</strong> {{ $cotizacion->cliente->tel_local }}</p>
                <p><strong>Email:</strong> {{ $cotizacion->cliente->email }}</p>
            </div>
            <div class="pull-right">
                <p><strong>Fecha: {{ $cotizacion->created_at->format('d-m-Y') }}</strong></p>   
                <p><strong>Cotizacion #{{ $cotizacion->num_cotizacion }}</strong></p>              
            </div>
        </div>
        <div class="row text-center">
            <p>Estimado/a Sr./Sra.:</p>
            <p>Por medio de la presente tenemos el agrado de cotizarle el siguiente material:</p>
        </div>
        <div class="row top-buffer">
            <table class="table table-bordered table-condensed text-center">
                <thead>
                    <tr>
                        <th class="text-center">Código</th>
                        <th class="text-center">Descripción</th>
                        <th class="text-center">Medidas</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Precio</th>
                        <th class="text-center">Totales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cotizacion->cotizacion_producto as $producto)
                        <tr>
                            <td> {{ $producto->codigo }} </td>
                            <td> {{ $producto->descripcion }} </td>
                            <td> {{ $producto->medidas }} </td>
                            <td> {{ $producto->pivot->cantidad_producto }} </td>
                            <td> B/.{{ number_format($producto->pivot->precio_final,2) }} </td>
                            <td> B/.{{ number_format(($producto->pivot->precio_final*$producto->pivot->cantidad_producto),2) }} </td>
                        </tr>
                    @endforeach
                    <tr class="table-borderless">
                        <td colspan="5" class='text-right'><strong>Sub Total:</strong></td>
                        <td>B/.{{number_format($cotizacion->subtotal,2) }}</td>
                    </tr>
                    <tr class="table-borderless">
                        <td colspan="5" class='text-right'><strong>ITBMS ({{$cotizacion->itbms}}%):</strong></td>
                        <td>B/.{{number_format($cotizacion->subtotal*$cotizacion->itbms/100,2) }}</td>
                    </tr>
                    <tr class="table-borderless">
                        <td colspan="5" class='text-right'><strong>Total:</strong></td>
                        <td>B/.{{number_format($cotizacion->monto_cotizacion,2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Footer -->
        <div class="row footer">
            <div class="pull-left">
                <p>Favor emitir cheques a nombre de: <strong>BF Services, s.a</strong></p>
            </div>
            <div class="pull-right">
                <p>Copia-Documento no fiscal</p>
            </div>
        </div>
        <!-- Footer -->
    </body>
</html>
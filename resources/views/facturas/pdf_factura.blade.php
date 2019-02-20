<!DOCTYPE html>
<html>
    <header>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="{{ public_path() }}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{ public_path() }}/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <style>
            @media all {
                .page-break	{ display: none; }
            }

            @media print {
                .page-break	{ display: block; page-break-before: always; }
            }
            @page { margin-top: 5px; }
            .page-break {
                page-break-after: always;
            }

            .top-buffer { margin-top:20px; }
            .footer {
                position: fixed;
                height: 10px;
                bottom: 0;
                width: 100%;
            }
            /* borderless table */
            .table-borderless td, .table-borderless th {
                border: 0 !important;
            }
        </style>
    </header>
    <body>
        <div class="row">
            <img src="{{ public_path() }}/images/cintillo_control_old.jpg" alt="bf_cintillo" width="100%"/> 
        </div>
        <div class="row">
            <div class="pull-left">
                <h3><strong>Cliente: <u>{{ $factura->cliente->empresa }}</u></strong></h3>
                <p><strong>RUC/CI:</strong> {{ $factura->cliente->ruc }}</p>
                <p><strong>Dirección:</strong> {{ $factura->cliente->direccion }}</p>
                <p><strong>Telf:</strong> {{ $factura->cliente->tel_local }}</p>
            </div>
            <div class="pull-right">
                <h3><strong>Control N° {{ $factura->num_factura }}</strong></h3>
                <p><strong>Fecha: {{ $factura->created_at->format('d-m-Y') }}</strong></p>               
            </div>
        </div>
        <div class="row top-buffer">
            <table class="table table-condensed">
                <caption>Condición de pago: {{ $factura->condicion }}</caption>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($factura->orden->ordenes_productos as $producto)
                        <tr>
                            <td> {{ $producto->codigo }} </td>
                            <td> {{ $producto->descripcion }} </td>
                            <td> {{ $producto->pivot->cantidad_producto }} </td>
                            <td> B/.{{ number_format($producto->pivot->precio_final,2) }} </td>
                            <td> B/.{{ number_format(($producto->pivot->precio_final*$producto->pivot->cantidad_producto),2) }} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class='text-right'><strong>Sub Total :</strong></td>
                        <td>B/.{{number_format($factura->subtotal,2) }}</td>
                    </tr>
                    <tr class="table-borderless">
                        <td colspan="4" class='text-right'><strong>ITBMS ({{$factura->itbms}}%) :</strong></td>
                        <td>B/.{{number_format($factura->subtotal*$factura->itbms/100,2) }}</td>
                    </tr>
                    <tr class="table-borderless">
                        <td colspan="4" class='text-right'><strong>Total :</strong></td>
                        <td>B/.{{number_format($factura->monto_factura,2) }}</td>
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

        <script src="{{ public_path() }}/js/jquery-1.11.2.min.js" type="text/javascript"></script> 
        <script src="{{ public_path() }}/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
    </body>
</html>
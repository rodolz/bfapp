<!DOCTYPE html>
<html>
    <header>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            @page { margin-top: 5px; }
            .page-break {
                page-break-after: always;
            }

            .top-buffer { margin-top:20px; }
            footer {
                position: fixed;
                height: 10px;
                bottom: 0;
                width: 100%;
            }
        </style>
        <link href="{{ public_path() }}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{ public_path() }}/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
    </header>
    <body>
        <div class="row">
            <img src="{{ public_path() }}/images/cintillo_control_old.jpg" alt="bf_cintillo" width="750px"/> 
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="pull-left">
                    <p><strong>Cliente: {{ $cotizacion->cliente->empresa }}</strong></p>
                    <p><strong>Dirección:</strong> {{ $cotizacion->cliente->direccion }}</p>
                    <p><strong>Contacto:</strong> {{ $cotizacion->cliente->contacto }}</p>
                    <p><strong>Teléfono:</strong> {{ $cotizacion->cliente->tel_local }}</p>
                    <p><strong>Email:</strong> {{ $cotizacion->cliente->email }}</p>
                </div>
                <div class="pull-right">
                    <p><strong>Fecha: {{ $cotizacion->created_at->format('d-m-Y') }}</strong></p>               
                </div>
            </div>
        </div>
        <div class="row">
            <p class='text-center'>Estimado/a Sr./Sra.:</p>
            <p class='text-center'>Por medio de la presente tenemos el agrado de cotizarle el siguiente material:</p>
        </div>
        <div class="row top-buffer">
            <table class="table table-sm table-bordered table-condensed text-center">
                <thead>
                    <tr>
                        <th class="text-center">Código</th>
                        <th class="text-center">Descripción</th>
                        <th class="text-center">Medidas</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Precio</th>
                        <th class="text-center" width="75px">Totales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cotizacion->cotizacion_producto as $producto)
                        <tr>
                            <td> {{ $producto->codigo }} </td>
                            <td> {{ $producto->descripcion }} </td>
                            <td> {{ $producto->medidas }} </td>
                            <td> {{ $producto->pivot->cantidad_producto }} </td>
                            <td> ${{ number_format($producto->pivot->precio_final,2) }} </td>
                            <td> ${{ number_format(($producto->pivot->precio_final*$producto->pivot->cantidad_producto),2) }} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" class='text-right'>Sub Total :</td>
                        <td>${{number_format($cotizacion->subtotal,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class='text-right'>ITBMS ({{$cotizacion->itbms}}%) :</td>
                        <td>${{number_format($cotizacion->subtotal*$cotizacion->itbms/100,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class='text-right'>Total :</td>
                        <td>${{number_format($cotizacion->monto_cotizacion,2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Footer -->
        <footer>
            <div class="pull-left">
                <p>Favor emitir cheques a nombre de: <strong>BF Services, s.a</strong></p>
            </div>
            <div class="pull-right">
                <p>Copia-Documento no fiscal</p>
            </div>
        </footer>
        <!-- Footer -->

        <script src="{{ public_path() }}/js/jquery-1.11.2.min.js" type="text/javascript"></script> 
        <script src="{{ public_path() }}/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
    </body>
</html>
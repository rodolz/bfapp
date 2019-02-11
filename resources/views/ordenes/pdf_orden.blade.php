<!DOCTYPE html>
<html>
    <header>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            @page { margin-top: 10px; }
            .page-break {
                page-break-after: always;
            }

            .top-buffer { margin-top:20px; }
            footer {
                position: fixed;
                height: 25px;
                bottom: 0;
                width: 100%;
            }
        </style>
        <link href="{{ public_path() }}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{ public_path() }}/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
    </header>
    <body>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <img src="{{ public_path() }}/images/banner.jpg" alt="bf_cintillo" width="725px" height="200px" />
            </div> 
        </div>

        <div class="row top-buffer">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="pull-left">
                    <label>Fecha: {{ $orden->created_at->format('d-m-Y') }}</label>
                </div>
                <div class="pull-right">
                    <label>Nota de entrega #{{ $orden->num_orden }}</label>
                </div>
            </div>
        </div>
        <div class="row top-buffer">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
           
                <table class="table">
                    <tbody>
                        <tr>
                            <td style="white-space: nowrap;"><img src="{{ public_path() }}/images/iconos/empresa.png" width="30px" height="30px"/> <strong>{{ $cliente->empresa }}</strong></td>
                            <td colspan="2"><img src="{{ public_path() }}/images/iconos/direccion.png" width="30px" height="30px"/> <strong>{{ $cliente->direccion }}</strong></td>
                        </tr>
                        <tr>
                            <td><img src="{{ public_path() }}/images/iconos/correo.png" width="30px" height="30px"/> <strong>{{ $cliente->email }}</strong></td>
                            <td><img src="{{ public_path() }}/images/iconos/tlf.png" width="30px" height="30px"/> <strong>{{ $cliente->tel_local }}</strong></td>
                            <td><img src="{{ public_path() }}/images/iconos/contacto2.png" width="30px" height="30px"/> <strong>{{ $cliente->contacto }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2"><img src="{{ public_path() }}/images/iconos/camion.png" width="30px" height="30px"/>
                            @foreach ($orden->ordenes_repartidores as $repartidor)
                                @if ($loop->last) 
                                    <strong>{{ $repartidor->nombre }}</strong>
                                @else
                                    <strong>{{ $repartidor->nombre }},</strong>
                                @endif
                            @endforeach
                            </td>
                            <td style="white-space: nowrap;"><img src="{{ public_path() }}/images/iconos/maletin.png" width="30px" height="30px"/> <strong>{{ $vendedor->nombre }}</strong></td>
                        </tr>
                    </tbody>
                </table>
       
            </div>
        </div>
        <div class="row">
                <table class="table table-bordered table-condensed text-center">
                    <thead>
                        <tr>
                            <th class="bg-primary text-center">Código</th>
                            <th class="bg-primary text-center">Descripción</th>
                            <th class="bg-primary text-center">Medidas</th>
                            <th class="bg-primary text-center">Cantidad</th>
                            <th class="bg-primary text-center">Precio</th>
                            <th class="bg-primary text-center">Totales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orden->ordenes_productos as $producto)
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
                            <td colspan="5"></td>
                            <td class="bg-primary"><strong>${{number_format($orden->monto_orden,2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p class="text-left"><i><small>Nota: los precios no incluyen ITBMS.</small></i></p>
            </div>
        </div>

        <!-- Footer -->
        <footer class='text-right'>
            <p>Recibido por:___________________________________</p>
        </footer>
        <!-- Footer -->

        <script src="{{ public_path() }}/js/jquery-1.11.2.min.js" type="text/javascript"></script> 
        <script src="{{ public_path() }}/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
    </body>
</html>
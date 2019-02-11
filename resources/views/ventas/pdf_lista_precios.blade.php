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
            <div class="pull-left">
                <h3><strong>Lista de Precios</strong></h3>
            </div>
        </div>
        <div class="row top-buffer">
            <table class="table table-bordered table-condensed text-center">
                <caption>Para la siguiente fecha: {{ date('d/m/Y') }}</caption>
                <thead>
                    <tr>
                        <th class="text-center">Código</th>
                        <th class="text-center">Descripción</th>
                        <th class="text-center">Medidas</th>
                        <th class="text-center">Precio Venta</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                     $categoria = '';
                    @endphp
                    @foreach ($productos_disponibles as $producto)
                        @if($producto->categoria->nombre_categoria != $categoria)
                                <tr class="bg-success"><td colspan="4"><strong>{{ $producto->categoria->nombre_categoria }}</strong></td></tr>
                                <tr>
                                    <td> {{ $producto->codigo }} </td>
                                    <td> {{ $producto->descripcion }} </td>
                                    <td> {{ $producto->medidas }} </td>
                                    <td> ${{ number_format($producto->precio,2) }} </td>
                                </tr>
                            @php
                            $categoria = $producto->categoria->nombre_categoria;
                            @endphp
                        @else
                                <tr>
                                    <td> {{ $producto->codigo }} </td>
                                    <td> {{ $producto->descripcion }} </td>
                                    <td> {{ $producto->medidas }} </td>
                                    <td> ${{ number_format($producto->precio,2) }} </td>
                                </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <script src="{{ public_path() }}/js/jquery-1.11.2.min.js" type="text/javascript"></script> 
        <script src="{{ public_path() }}/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
    </body>
</html>
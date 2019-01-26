@extends('layout.master')
    @section('page-title')
        <h2 class="title bold">Resumen</h2>
    @endsection
    @section('content')
    <section class="box nobox">
        <div class="content-body">  
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="r4_counter db_box">
                                <i class='pull-left fa fa-dollar icon-md icon-rounded icon-green'></i>
                                <div class="stats">
                                    <h4><strong>${{ number_format($monto_por_cobrar , 2) }}</strong></h4>
                                    <span>Monto por cobrar</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="r4_counter db_box">
                                <i class='pull-left fa fa-file-pdf-o icon-md icon-rounded icon-orange'></i>
                                <div class="stats">
                                    <h4><strong>{{ isset($ordenes_totales) ? $ordenes_totales : "No hay notas de entregas"  }}</strong></h4>
                                    <span>Notas de entregas</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="r4_counter db_box">
                                <i class='pull-left fa fa-truck icon-md icon-rounded icon-primary'></i>
                                <div class="stats">
                                    <h4><strong>{{ isset($repartidor_top1) ? $repartidor_top1->nombre : "No se pudo encontrar"  }}</strong></h4>
                                    <span>Repartidor mas activo</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="r4_counter db_box">
                                <i class='pull-left fa fa-user icon-md icon-rounded icon-warning'></i>
                                <div class="stats">
                                    <h4><strong>{{ isset($cliente_top1) ? $cliente_top1->empresa : "No se pudo encontrar"  }}</strong></h4>
                                    <span>Cliente con mas ordenes</span>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End .row --> 

                </div>
            </div>
        </div>
    </section>

    <div class="col-lg-12">
            <section class="box ">
                <header class="panel_header">
                    <h2 class="title pull-left">Ventas - Octobre 2018</h2>
                    <div class="actions panel_actions pull-right">
                        <i class="box_toggle fa fa-chevron-down"></i>
                    </div>
                </header>
                <div class="content-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Control #</th>
                                <th>Fiscal #</th>
                                <th>Fecha</th>
                                <th>SubTotal</th>
                                <th>ITBMS</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($facturas) > 0)
                                @foreach($facturas as $factura)
                                    <tr>
                                        <td>{{ $factura->num_factura }}</td>
                                        <td>{{ $factura->num_fiscal }}</td>
                                        <td>{{ $factura->created_at->format('d-m-Y') }}
                                        <td>${{ number_format($factura->subtotal, 2, '.', ',') }}</td>
                                        <td>${{ number_format(($factura->monto_factura*$factura->itbms)/100, 2, '.', ',') }}</td>
                                        <td>${{ $factura->monto_factura }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                
                                    <th class="thick-line" scope="row">Totales</th>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td>${{ number_format($sum_subtotal, 2, '.', ',') }}</td>
                                    <td>${{ number_format($sum_itbms, 2, '.', ',') }}</td>
                                    <td>${{ number_format($sum_total, 2, '.', ',') }}</td>
                                </tr>
                            @else
                                <tr><th colspan="7" scope="row" class="text-center">No se encontraron registros para este periodo</th></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <section class="box ">
                <header class="panel_header">
                    <h2 class="title pull-left">Top Clientes</h2>
                    <div class="actions panel_actions pull-right">
                        <i class="box_toggle fa fa-chevron-down"></i>
                    </div>
                </header>
                <div class="content-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style=>Cliente</th>
                                <th style=>Cantidad de Ordenes</th>
                                <th style=>Deuda</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($clientes_top))
                            @foreach($clientes_top as $cliente)
                                <tr>
                                    <td>{{ $cliente->empresa }}</td>
                                    <td>{{ $cliente->ordenes->count() }}</td>
                                    <td>
                                        @if($cliente->deuda == 0)
                                            <a class="text text-success" href="/pagos/estado_cuenta_pdf/{{$cliente->id}}">${{ number_format($cliente->deuda, 2, '.', ',')}}</a>
                                        @else
                                            <a class="text text-danger" href="/pagos/estado_cuenta_pdf/{{$cliente->id}}">${{ number_format($cliente->deuda, 2, '.', ',')}}</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr><th scope="row">No hay registros en la DB</th></tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

    <div class="col-lg-6">
            <section class="box ">
                <header class="panel_header">
                    <h2 class="title pull-left">Top Productos</h2>
                    <div class="actions panel_actions pull-right">
                        <i class="box_toggle fa fa-chevron-down"></i>
                    </div>
                </header>
                <div class="content-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width:20%">Codigo</th>
                                <th style="width:60%">Descripcion</th>
                                <th style="width:20%">Cantidad Vendida</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($productos_top))
                                @foreach($productos_top as $producto)
                                    <tr>
                                        <td>{{ $producto->codigo }}</td>
                                        <td>{{ $producto->descripcion }}</td>
                                        <td>{{ $producto->sp }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><th scope="row">No se encuentran registros en la DB</th></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
    @endsection
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
                                    <h4><strong>{{ isset($repartidor) ? $repartidor->nombre : "No se pudo encontrar"  }}</strong></h4>
                                    <span>Repartidor mas activo</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="r4_counter db_box">
                                <i class='pull-left fa fa-user icon-md icon-rounded icon-warning'></i>
                                <div class="stats">
                                    <h4><strong>{{ isset($cliente) ? $cliente->empresa : "No se pudo encontrar"  }}</strong></h4>
                                    <span>Cliente con mas ordenes</span>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End .row --> 

                </div>
            </div>
        </div>
    </section>
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
                                <th style="width:60%">Cliente</th>
                                <th style="width:30%">Cantidad de Ordenes</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($clientes_top5))
                            @foreach($clientes_top5 as $key => $value)
                                <tr>
                                    <td> {{ $value->empresa }} </td>
                                    <td>{{ $key}}</td>
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
                                <th style="width:60%">Producto</th>
                                <th style="width:30%">Cantidad Vendida</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($productos_top5))
                                @foreach($productos_top5 as $key => $value)
                                    <tr>
                                        <td> {{ $value->codigo }} </td>
                                        <td>{{ $key }}</td>
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
@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Productos</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left"><strong>{{ $producto->descripcion }} ({{ $producto->codigo }})</strong></h2>
    @endsection

@section('content')
    <section class="box primary">
        <!--  PANEL HEADER    -->
        <header class="panel_header">
            @yield('panel-title')
        </header>
        <div class="content-body">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="pull-left">
                        <h2 class="title bold">Resumen</h2>
                    </div>
                </div>
                <div class="well row top15">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Categoria</th>
                                    <th>Descripcion</th>
                                    <th>Medidas</th>
                                    <th>Precio Venta</th>
                                    <th>Precio Costo</th>
                                    <th width="125px">Cantidad Disp.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>{{ $producto->codigo }}</th>
                                    <th>{{ $producto->categoria->nombre_categoria }}</th>
                                    <th>{{ $producto->descripcion }}</th>
                                    <th>{{ $producto->medidas }}</th>
                                    <th>${{ number_format($producto->precio, 2, '.', ',') }}</th>
                                    <th>${{ number_format($producto->precio_costo, 2, '.', ',') }}</th>
                                    <th width="125px">{{ $producto->cantidad }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="text-center">
                            <h2 class="bold">Ventas</h2>
                        </div>
                    </div>
                    <div class="well row top15 right15">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nota de Entrega</th>
                                        <th>Cliente</th>
                                        <th>Monto</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(count($ordenes) > 0)
                                    @foreach ($ordenes as $orden)
                                        <tr>
                                            @if($orden->id == 0)
                                                <th scope="row"><a href="#">{{ $orden->num_orden }}</a></th>
                                            @else
                                                <th scope="row"><a href="{{ URL::to('ordenes/orden_pdf/'.$orden->id) }}"># {{ $orden->num_orden }}</a></th>
                                            @endif
                                            <td>{{ $orden->cliente->empresa }}</td>
                                            <td>${{ number_format($orden->pivot->cantidad_producto*$orden->pivot->precio_final, 2, '.', ',') }}</td>
                                            <td>{{ $orden->pivot->cantidad_producto }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th scope="row" colspan="2">Total</th>
                                        <td class="bold">${{ number_format($monto_total, 2, '.', ',') }}</td>
                                        <td class="bold">{{ $cantidad_total }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="4">
                                            <h2 class="bold text-warning text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:30px"></i> No se han creado notas de entregas con este producto.</h2>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row text-center">
                 <a type="button" class="btn" href="{{ URL::route('productos.index') }}">Atras</a>
            </div>
        </div>
    </section>
@endsection
@extends('layout.master')
    
    @section('page-title')
        <h2 class="title bold">Productos</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Inventario</h2>
    @endsection

@section('content')
    <section class="box primary">
        <!--  PANEL HEADER    -->      
        <header class="panel_header">
            @yield('panel-title')
        <!--<div class="actions panel_actions pull-right">
        <i class="box_toggle fa fa-chevron-down"></i>
        <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
        <i class="box_close fa fa-times"></i>
        </div> -->
        </header>
        <div class="content-body">    
{{--             <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pull-left">
                        <h2 class="bold">Productos Disponibles</h2>
                    </div>
                </div>
            </div> --}}
            <div class="row top15">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <!-- <th>Categoria</th> -->
                                <th>Descripción</th>
                                <th>Medidas</th>
                                <th scope="row">Cantidad Disp.</th>
                                <th>Precio Unitario</th>
                            </tr>
                        </thead>
                            <tbody>
                            @if(count($productos_disponibles) > 0)
                                @php ($cond = '')
                                @foreach ($productos_disponibles as $producto)
                                @if($producto->categoria->nombre_categoria != $cond)
                                <tr class='bg-primary'>
                                    <th colspan="5">{{ $producto->categoria->nombre_categoria }}</th>
                                </tr>

                                @php ($cond = $producto->categoria->nombre_categoria)
                                @endif
                                    <tr>
                                        <td>{{ $producto->codigo }}</td>
                                        <!-- <td>{{ $producto->categoria->nombre_categoria }}</td> -->
                                        <td>{{ $producto->descripcion }}</td>
                                        <td>{{ $producto->medidas }}</td>
                                        <th scope="row">{{ $producto->cantidad }}</th>
                                        <td>${{ number_format($producto->precio, 2, '.', ',') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="3">Totales</th>
                                    <th scope="row" >{{ $cantidad_total }}</th>
                                    <th scope="row" >${{ number_format($monto_total, 2, '.', ',') }}</th>
                                </tr>
                            @else
                            <tr>cantidad_total
                                <td colspan="5">
                                    <h2 class="bold text-danger text-center"><i class="fa fa-exclamation-circle" aria-hidden="true" style="font-size:30px"></i> Inventario Vacio</h2>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row text-center">
                {{-- <a class="btn btn-info {{ count($productos_disponibles) > 0 ? '': 'disabled'}} top15" href="{{ URL::to('lista_precios_csv') }}"> Exportar en Excel</a> --}}
                <a class="btn btn-purple {{ count($productos_disponibles) > 0 ? '': 'disabled'}} top15" href="{{ URL::to('productos/inventario_pdf') }}"> Exportar en PDF</a>
            </div>
            <!-- PAGINACION -->
        </div>
    </section>

@endsection
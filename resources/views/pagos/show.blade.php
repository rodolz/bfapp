@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Pagos</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Pago | <strong>{{ $pago->numero_referencia }}</strong></h2>
    @endsection

@section('content')
    <section class="box primary">
        <!--  PANEL HEADER    -->      
        <header class="panel_header">
            @yield('panel-title')
        </header>
        <div class="content-body">    
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pull-left">
                        <h2 class="title bold">Cliente: <u>{{ $pago->facturas->first()->cliente->empresa }}</u></h2>
                    </div>
                </div>
            </div>

            <div class="well row top15">
                {{-- Detalle del pago --}}
            	<div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th># de Referencia</th>
                                <th>Banco</th>
                                <th>Metodo</th>
                                <th>Monto de Pago</th>
                                <th>Descripcion</th>
                                <th>Fecha de Pago</th>
                            </tr>
                    	</thead>
                        <tbody>
                    	    <tr>
                                <th scope="row">{{ $pago->numero_referencia }}</th>
                    	        <td>{{ $pago->banco }}</td>
                                <td>{{ $pago->tipo_pago->nombre_pago }}</td>
                    	        <td class="bold">${{ number_format($pago->monto_pago,2,'.',',') }}</td>
                                <td>{{ $pago->descripcion }}</td>
                                <td>{{ $pago->created_at->format('d-m-Y') }}</td>
                    	    </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pull-left">
                        <h2 class="bold">Controles en este pago</h2>
                    </div>
                </div>
            </div>
            <div class="well row top15">
                {{-- Detalle de cada factura/control --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th># Fiscal</th>
                                <th># Control</th>
                                <th># Nota de Entrega</th>
                                <th>Condición</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pago->facturas as $factura)
                                <tr>
                                    <th scope="row">{{ $factura->num_fiscal }}</th>
                                    <th>{{ $factura->num_factura }}</th>
                                    <td>{{ $factura->orden->num_orden }}</td>
                                    <td>{{ $factura->condicion }}</td>
                                    <td>${{ number_format($factura->monto_factura,2,'.',',') }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th scope="row" colspan="4">Total</th>
                                <td class="bold"> ${{ $monto_total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row text-center">
                 <a type="button" class="btn" href="{{ URL::route('pagos.index') }}">Atras</a>
            </div>
        </div>
    </section>
@endsection
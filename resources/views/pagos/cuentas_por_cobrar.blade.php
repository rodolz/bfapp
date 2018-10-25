@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Pagos</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Cuentas por Cobrar</h2>
    @endsection

@section('content')
    <section class="box primary">
        <header class="panel_header">
            @yield('panel-title')
        </header>
        <div class="content-body">    
        	<div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <!-- start -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="invoice-head">
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 invoice-title">
                                    <h3 class="text-center bg-primary bold">{{ $cliente->empresa }}</h3>    
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 invoice-head-info">
                                    <span class="text-muted">
                                        <strong>Dirección:</strong><br/>
                                        {{ $cliente->direccion }}
                                    </span>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 invoice-head-info">
                                    <span class="text-muted"> 
                                        <strong>Fecha:</strong><br/>
                                        {{ date('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-lg-12 col-xs-6 invoice-infoblock pull-left">
                            <h4>Contacto:</h4>
                            <address>
                                <h3>{{ $cliente->contacto }}</h3>
                                <span class="text-muted">
                                Correo: {{  $cliente->email }}<br>
                                Telefono Local: {{  $cliente->tel_local }}<br>
                                Telefono Celular: {{  $cliente->tel_celular }}
                                </span>
                            </address>
                        </div>
                        <div class="col-xs-12 invoice-infoblock text-right">
                            <div class="invoice-due">
                                <h3 class="text-muted">Deuda Total:</h3> &nbsp; <h3 class="text-danger">$ {{ number_format($monto_total,2,'.',',') }}</h3>                
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3>Resumen</h3><br>
                            <div class="table-responsive">
                                <table class="table table-hover invoice-table">
                                    <thead>
                                        <tr>
                                            <td><h4>Fiscal #</h4></td>
                                            <td><h4>Control #</h4></td>
                                            <td class="text-center"><h4>Condición</h4></td>
                                            <td class="text-center"><h4>Fecha</h4></td>
                                            <td width="200px" class="text-center bg-info"><h4>30 días</h4></td>
                                            <td width="200px" class="text-center bg-warning"><h4>60 días</h4></td>
                                            <td width="200px" class="text-right bg-danger"><h4>90 días o mas</h4></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($facturas) > 0)
                                        @php
                                            $total_30dias = 0;
                                            $total_60dias = 0;
                                            $total_90dias = 0;
                                        @endphp
                                        @foreach ($facturas as $factura)
    	                                        <tr>
    	                                            <th scope="row">{{ $factura->num_fiscal }}</th>
                                                    <th scope="row">{{ $factura->num_factura }}</th>
    	                                            <td class="text-center">{{ $factura->condicion }}</td>
    	                                            <td class="text-center">{{ $factura->created_at->format('d-m-Y') }}</td>
                                                    @php
                                                    $date1 = new DateTime($factura->created_at->format('Y-m-d'));
                                                    $date2 = new DateTime(date('Y-m-d', strtotime('TODAY')));
                                                    $interval = $date1->diff($date2);
                                                    @endphp
                                                    
                                                    @if ($interval->m <= 1)
                                                            <!-- Plazo hasta 30 dias -->
                                                            @if($interval->m == 0 && $interval->d >= 0)
                                                                @php ($total_30dias = $total_30dias + $factura->monto_factura)
                                                                <td class="text-center text-info">${{ $factura->getAmountFormatted() }}</td>
                                                                <td class="text-center">&nbsp;</td>
                                                                <td class="text-center">&nbsp;</td> 
                                                            @elseif ($interval->m == 1 && $interval->d == 0)
                                                                @php ($total_30dias = $total_30dias + $factura->monto_factura)
                                                                <td class="text-center text-info">${{ $factura->getAmountFormatted()  }}</td>
                                                                <td class="text-center">&nbsp;</td>
                                                                <td class="text-center">&nbsp;</td> 
                                                            <!-- Plazo hasta 60 dias  -->
                                                            @elseif($interval->m == 1 && $interval->d > 0)
                                                                @php ($total_60dias = $total_60dias + $factura->monto_factura)
                                                                <td class="text-center">&nbsp;</td>
                                                                <td class="text-center text-warning">${{ $factura->getAmountFormatted()  }}</td>
                                                                <td class="text-center">&nbsp;</td>
                                                            @endif
                                                    @elseif ($interval->m == 2)
                                                            @if ($interval->d == 0)
                                                                @php ($total_60dias = $total_60dias + $factura->monto_factura)
                                                                <td class="text-center">&nbsp;</td>
                                                                <td class="text-center text-warning">${{ $factura->getAmountFormatted()  }}</td>
                                                                <td class="text-center">&nbsp;</td> 
                                                            @else
                                                                @php ($total_60dias = $total_60dias + $factura->monto_factura)
                                                                <td class="text-center">&nbsp;</td>
                                                                <td class="text-center text-warning">${{ $factura->getAmountFormatted()  }}</td>
                                                                <td class="text-center">&nbsp;</td>  
                                                            @endif
                                                    @else
                                                            @if ($interval->d == 0)
                                                                @php ($total_90dias = $total_90dias + $factura->monto_factura)
                                                                <td class="text-center">&nbsp;</td>
                                                                <td class="text-center">&nbsp;</td>
                                                                <td class="text-right text-danger">${{ $factura->getAmountFormatted() }}</td> 
                                                            @else
                                                                @php ($total_90dias = $total_90dias + $factura->monto_factura)
                                                                <td class="text-center">&nbsp;</td>
                                                                <td class="text-center">&nbsp;</td>
                                                                <td class="text-right text-danger">${{ $factura->getAmountFormatted()  }}</td>
                                                            @endif
                                                    @endif            
    	                                        </tr>
                                    	@endforeach
                                        <tr>
                                            <td class="thick-line">Totales</td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line text-center"><h4 style='margin:0px;' class="text-info">${{number_format($total_30dias,2,'.',',')}}</h4></td>
                                            <td class="thick-line text-center"><h4 style='margin:0px;' class="text-warning">${{number_format($total_60dias,2,'.',',') }}</h4></td>
                                            <td class="thick-line text-right"><h4 style='margin:0px;' class="text-danger">${{number_format($total_90dias,2,'.',',') }}</h4></td>
                                        </tr>
                                        @else
                                            <tr>
                                                <td colspan="7">
                                                    <h2 class="bold text-success text-center"><i class="fa fa-check-circle" aria-hidden="true" style="font-size:30px"></i> No tiene facturas pendientes</h2>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div><br>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <a href="/pagos/estado_cuenta_pdf/{{$cliente->id}}" target="_blank" class="btn btn-purple btn-md"><i class="fa fa-print"></i> &nbsp; Imprimir </a>     
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>
        </div>
    </section>

@endsection
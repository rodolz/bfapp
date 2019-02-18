<!DOCTYPE html>
<html>
    <header>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="{{ public_path() }}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <style>
            @media all {
                .page-break	{ display: none; }
            }

            @media print {
                .page-break	{ display: block; page-break-before: always; }
            }
            @page { margin-top: 5px; }
            .top-buffer { margin-top:20px; }
            .footer {
                position: fixed;
                height: 10px;
                bottom: 0;
                width: 100%;
                page-break-after:auto;
            }
            /* borderless table */
            .table-borderless td, .table-borderless th {
                border: 0 !important;
            }
        </style>
    </header>
    <body>
            <div class="row">
                <img src="{{ public_path() }}/images/cintillo_control_old.jpg" alt="bf_cintillo" width="100%" />
            </div>
            <div class="row text-center">
                    <h1>Estado de Cuenta</h1>
            </div>
            <div class="row">
                <div class="pull-left">
                    <h3>Monto: <strong>${{number_format($monto_total,2,'.',',')}}</strong></h3>
                </div>
            </div>
            <div class="row">
                <table class="table table-condensed table-bordered">
                    <caption>Para la siguiente fecha: {{ date('d/m/Y') }}</caption>
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Fiscal #</th>
                            <th>Control #</th>
                            <th>Condición</th>
                            <th>Fecha</th>
                            <th class="bg-primary">30 días</th>
                            <th class="bg-warning">60 días</th>
                            <th class="bg-danger">90 días en adelante</th>
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
                                    <td class="text-center">{{ $factura->cliente->empresa }}</td>
                                    <td class="text-center">{{ $factura->num_fiscal }}</td>
                                    <td class="text-center">{{ $factura->num_factura }}</td>
                                    <td class="text-center">{{ $factura->condicion }}</td>
                                    <td class="text-center">{{ $factura->created_at->format('d/m/Y') }}</td>
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
                                                <td class="text-center text-danger">${{ $factura->getAmountFormatted()  }}</td>
                                                <td class="text-center">&nbsp;</td>
                                            @endif
                                    @elseif ($interval->m == 2)
                                            @if ($interval->d == 0)
                                                @php ($total_60dias = $total_60dias + $factura->monto_factura)
                                                <td class="text-center">&nbsp;</td>
                                                <td class="text-center text-danger">${{ $factura->getAmountFormatted()  }}</td>
                                                <td class="text-center">&nbsp;</td> 
                                            @else
                                                @php ($total_60dias = $total_60dias + $factura->monto_factura)
                                                <td class="text-center">&nbsp;</td>
                                                <td class="text-center text-danger">${{ $factura->getAmountFormatted()  }}</td>
                                                <td class="text-center">&nbsp;</td>  
                                            @endif
                                    @else
                                            @if ($interval->d == 0)
                                                @php ($total_90dias = $total_90dias + $factura->monto_factura)
                                                <td class="text-center">&nbsp;</td>
                                                <td class="text-center">&nbsp;</td>
                                                <td class="text-center text-danger">${{ $factura->getAmountFormatted() }}</td> 
                                            @else
                                                @php ($total_90dias = $total_90dias + $factura->monto_factura)
                                                <td class="text-center">&nbsp;</td>
                                                <td class="text-center">&nbsp;</td>
                                                <td class="text-center text-danger">${{ $factura->getAmountFormatted()  }}</td>
                                            @endif
                                    @endif            
                                </tr>
                        @endforeach
                        <tr class='text-center'>
                            <td colspan="5"><strong>Totales</strong></td>
                            <td class='text-info'><strong>${{number_format($total_30dias,2,'.',',')}}</strong></td>
                            <td class='text-danger'><strong>${{number_format($total_60dias,2,'.',',') }}</strong></td>
                            <td class='text-danger'><strong>${{number_format($total_90dias,2,'.',',') }}</strong></td>
                        </tr>
                        @else
                            <tr>
                                <td colspan="8">
                                    <h2 class="text-success text-center"><i class="fa fa-check-circle" aria-hidden="true" style="font-size:30px"></i> No tiene facturas pendientes</h2>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            </div>
        </div> 
    </body>
</html>
<!DOCTYPE html>
<html>
<header>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="{{ public_path() }}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style>
        @media all {
            .page-break {
                display: none;
            }
        }

        @media print {
            .page-break {
                display: block;
                page-break-before: always;
            }
        }

        @page {
            margin-top: 5px;
        }

        .page-break {
            page-break-after: always;
        }

        .top-buffer {
            margin-top: 10px;
        }

        .footer {
            position: fixed;
            height: 10px;
            bottom: 0;
            width: 100%;
        }
        .table-productos{
            border: 1px solid black;
        }
        .table-productos, .table-productos th, .table-productos td {
            padding: 2px;
        }
        /* borderless table */
        .table-borderless td,
        .table-borderless th {
            border: 0 !important;
        }
        .left-padding {
        padding-left: 10px;
        }
        .right-padding {
        padding-right: 10px;
        }
    </style>
</header>

<body>
    <div class="row">
        <img src="{{ public_path() }}/images/cintillo_control_old.jpg" alt="bf_cintillo" width="100%" />
    </div>
    <div class="row top-buffer">
        <h2 class="text-center font-weight-bold">PURCHASE ORDER</h2>
    </div>
    <div class="row top-buffer">
        <div class="col-xs-8">
            <table>
                <thead>
                    <tr>
                        <th colspan="2" class="text-center bg-primary">SUPPLIER</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="scope">Name:</th>
                        <td>{{ $proveedor->name }}</td>
                    </tr>
                    <tr>
                        <th class="scope">Address:</th>
                        <td>{{ $proveedor->address }}</td>
                    </tr>
                    <tr>
                        <th class="scope">Country:</th>
                        <td>{{ $proveedor->country }}</td>
                    </tr>
                    <tr>
                        <th class="scope">City:</th>
                        <td>{{ $proveedor->city }}</td>
                    </tr>
                    <tr>
                        <th class="scope">Postal Code:</th>
                        <td>{{ $proveedor->postcode }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-xs-4">
            <p>
                <label>P.O #{{ $po->po_number }}</label>
            </p>
            <p>
                <label>Date: {{ $po->created_at->format('d-m-Y') }}</label>
            </p>
            <p>
                <label>Approved by: Brais Sanmartin</label>
            </p>
        </div>
    </div>

    <div class="row top-buffer">
        <div class="col-xs-6 right-padding">
            <table>
                <thead>
                    <tr>
                        <th colspan="2" class="text-center bg-primary">BILL TO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="scope">Name:</th>
                        <td>BF Services S.A</td>
                    </tr>
                    <tr>
                        <th class="scope">Address:</th>
                        <td>Parque Industrial Costa del Este</td>
                    </tr>
                    <tr>
                        <td colspan="2">Edificio IStorage, Local #1234</td>
                    </tr>
                    <tr>
                        <th class="scope">Country:</th>
                        <td>Panama</td>
                    </tr>
                    <tr>
                        <th class="scope">State:</th>
                        <td>Panama</td>
                    </tr>
                    <tr>
                        <th class="scope">City:</th>
                        <td>Panama</td>
                    </tr>
                    <tr>
                        <th class="scope">Phone:</th>
                        <td>(+507) 6330-1307</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-xs-6 left-padding">
            <table>
                <thead>
                    <tr>
                        <th colspan="2" class="text-center bg-primary">SHIP TO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="scope">Name:</th>
                        <td>{{$po->shipto->name}}</td>
                    </tr>
                    <tr>
                        <th class="scope">Address:</th>
                        <td>{{$po->shipto->address_line1}}</td>
                    </tr>
                    @if(!is_null($po->shipto->address_line2))
                    <tr>
                        <td colspan="2">{{$po->shipto->address_line2}}</td>
                    </tr>
                    @endif
                    <tr>
                        <th class="scope">Country:</th>
                        <td>{{$po->shipto->country}}</td>
                    </tr>
                    <tr>
                        <th class="scope">State:</th>
                        <td>{{$po->shipto->state}}</td>
                    </tr>
                    <tr>
                        <th class="scope">City:</th>
                        <td>{{$po->shipto->city}}</td>
                    </tr>
                    <tr>
                        <th class="scope">Phone:</th>
                        <td>{{$po->shipto->phone}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row top-buffer">
        <table width="100%" class="table-bordered">
            <thead>
                <tr>
                    <th class="bg-success text-center">Product Code</th>
                    <th class="bg-success text-center">Description</th>
                    <th class="bg-success text-center">Qty</th>
                    <th class="bg-success text-center">Unit Price</th>
                    <th class="bg-success text-center">Total</th>         
                </tr>
            </thead>
            <tbody>
            @foreach ($po->po_pp as $producto)
            <tr>
                <td class="text-center">{{$producto->codigo}}</td>
                <td class="text-center">{{$producto->descripcion}}</td>
                <td class="text-center">{{$producto->pivot->cantidad_producto}}</td>
                <td class="text-center">{{$producto->pivot->precio_final}}</td>
                <td class="text-center">${{number_format($producto->pivot->cantidad_producto*$producto->pivot->precio_final,2)}}</td>
            </tr>
            @endforeach
            <tr class="table-borderless">
                <td colspan="4" class='text-right'><strong>SUBTOTAL:</strong></td>
                <td class="text-center">${{number_format($po->po_subtotal,2) }}</td>
            </tr>
            <tr class="table-borderless">
                <td colspan="4" class='text-right'><strong>ITBMS ({{$po->itbms}}%):</strong></td>
                <td class="text-center">${{number_format($po->tax/100*$po->po_subtotal,2) }}</td>
            </tr>
            <tr class="table-borderless">
                <td colspan="4" class='text-right'><strong>Total:</strong></td>
                <td class="text-center">${{number_format($po->po_total_amount,2) }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    @if(!is_null($po->comments))
        <div class="row top-buffer">
            <table>
            <thead>
                <tr>
                    <th class="bg-info text-center">Comments or Special Instructions</th>
                </tr>
                <tbody>
                    <tr>
                        <td>
                            <pre>{{$po->comments}}</pre>
                        </td>
                    </tr>
                </tbody>
            </thead>
            </table>
        </div>
    @endif
    <!-- Footer -->
    <div class="row footer text-right">
        <p><i>BF Services S.A - {{ date("Y") }}</i></p>
    </div>
    <!-- Footer -->
</body>
</html>

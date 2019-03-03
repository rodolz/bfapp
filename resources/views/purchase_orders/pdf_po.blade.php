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
        /* borderless table */
        .table-borderless td,
        .table-borderless th {
            border: 0 !important;
        }
        .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
	border:0;
	padding:0;
	margin-left:-0.00001;
}
    </style>
</header>

<body>
    <div class="container-fluid">
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
                            <th width="100px" class="scope">Name:</th>
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
            <div class="col-xs-6">
                <div>
                    <table>
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center bg-primary">BILL TO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="scope" width="100px">Name:</th>
                                <td>BF Services S.A</td>
                            </tr>
                            <tr>
                                <th class="scope">Address:</th>
                                <td>Parque Industrial Costa del Este</td>
                            </tr>
                            <tr>
                                <td colspan="2">Edificio IStorage, Local #1234asdasdasdasdasdasdasdasdasdasdadasdadad</td>
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
            </div>
            <div class="col-xs-6">
                <div>
                    <table>
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center bg-primary">SHIP TO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="scope" width="100px">Name:</th>
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
        </div>
    </div>
</body>

</html>

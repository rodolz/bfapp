<!DOCTYPE html>
<html>
    <header>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="{{ public_path() }}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{ public_path() }}/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <style>
            @media all {
                .page-break	{ display: none; }
            }

            @media print {
                .page-break	{ display: block; page-break-before: always; }
            }
            @page { 
                margin-top: 5px;
            }
            .page-break {
                page-break-after: always;
            }
            .top-buffer { margin-top:10px; }
            .footer {
                position: fixed;
                height: 10px;
                bottom: 0;
                width: 100%;
            }
            /* borderless table */
            .table-borderless td, .table-borderless th {
                border: 0 !important;
            }
            .row-tables{
                margin-right: 50px;
            }
        </style>
    </header>
    <body>
        <div class="row">
            <img src="{{ public_path() }}/images/cintillo_control_old.jpg" alt="bf_cintillo" width="100%"/> 
        </div>
        <div class="row top-buffer">
            <h2 class="text-center font-weight-bold">PURCHASE ORDER</h2>
        </div>
        <div class="row-tables top-buffer">
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
        
        <div class="row-tables top-buffer">
            <div class="col-xs-6">
                <table width="100%">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center bg-primary">BILL TO</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                            <th width="100px" class="scope">Name:</th>
                            <td>BF Services S.A</td>
                        </tr>
                        <tr>
                            <th class="scope">Address:</th>
                            <td>Address: Parque Industrial Costa del Este</td>
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
            <div class="col-xs-6">
                <table width="100%">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center bg-primary">SHIP TO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th width="100px" class="scope">Name:</th>
                            <td>{{ $po->shipto->name }}</td>
                        </tr>
                        <tr>
                            <th class="scope">Address:</th>
                            <td>{{ $po->shipto->address_line1 }}</td>
                        </tr>
                        @if(!is_null($po->shipto->address_line2))
                            <tr>
                                <td colspan="2">{{ $po->shipto->address_line2 }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th class="scope">Country:</th>
                            <td>{{ $po->shipto->country }}</td>
                        </tr>
                        <tr>
                            <th class="scope">State:</th>
                            <td>{{ $po->shipto->state }}</td>
                        </tr>
                        <tr>
                            <th class="scope">City:</th>
                            <td>{{ $po->shipto->city }}</td>
                        </tr>
                        <tr>
                            <th class="scope">Postal Code:</th>
                            <td>{{ $po->shipto->phone }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
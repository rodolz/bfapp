@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Ordenes de Compra</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Lista de Ordenes de Compra</h2>
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
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      {{--   <div class="pull-left">
                            <h2 class="bold">Ordenes de Compra</h2>
                        </div> --}}
                        <div class="pull-right">
                            <a class="btn btn-info" href="{{ route('purchase_orders.select_proveedor') }}">Nueva Orden de Compra</a>
                        </div>
                </div>
            </div>
            <div class="row top15">
            	<div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>PO #</th>
                                <th>Fecha Creación</th>
                                <th>Proveedor</th>
                                <th>Monto Total</th>
                                <!-- <th>Estado</th> -->
                                <th width="200px">Acciones</th>
                            </tr>
                    	</thead>
                        <tbody>
                        @if(count($purchase_orders) > 0)
                            @foreach ($purchase_orders as $purchase_order)
                        	    <tr>
                        	        <th scope="row">{{ $purchase_order->po_num }}</th>
                        	        <td>{{ $purchase_order->created_at->format('d-m-Y') }}</td>
                        	        <td>{{ $purchase_order->proveedor->nombre }}</td>
                                    <td>${{ number_format($purchase_order->po_total_amount, 2, '.', ',') }}</td>
                                    <!-- @if($purchase_order->idpurchase_orderstado == 1)
                                        <td><label class="bg-warning"><a style="text-decoration: none; color: white;" href="/facturas/create-by-id/{{ $purchase_order->id }}" >{{ $purchase_order->estado->purchase_orders_estado }} </a></label></td>
                                    @else
                                         <td><label class="bg-info"><a style="text-decoration: none; color: white;" href="/factura-pdf/0/{{ $purchase_order->id }}" >{{ $purchase_order->estado->purchase_orders_estado }}</label></td>
                                    @endif -->
                                    <td>
                                        <div class="acciones-btn">
                                            @if($purchase_order->idpurchase_orderstado == 1)
                                                <a class="btn btn-primary" href="{{ route('purchase_orders.edit',$purchase_order->id) }}"><i class="fa fa-pencil"></i></a>
                                            @else
                                                <button class="btn btn-primary" href="{{ route('purchase_orders.edit',$purchase_order->id) }}" disabled><i class="fa fa-pencil"></i></button>
                                            @endif

                                            <a class="btn btn-info" href="{{ URL::to('purchase_order-pdf/'.$purchase_order->id) }}"><i class="fa fa-file-pdf-o"></i></a>
                                            {!! Form::open(['method' => 'DELETE','route' => ['purchase_orders.destroy', $purchase_order->id],'style'=>'display:inline']) !!}
                                        <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                            {!! Form::close() !!}
                                        </div>
                        	        </td>
                        	    </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    <h2 class="bold text-warning text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:30px"></i> No se han creado Ordenes de Compra</h2>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        	<!-- PAGINACION -->
           <center> {!! $purchase_orders->links() !!} </center>
        </div>
    </section>
@endsection
@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Notas de Entrega</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Lista de Notas de Entrega</h2>
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
                            <h2 class="bold">Notas de Entrega</h2>
                        </div> --}}
                        <div class="pull-right">
                            <a class="btn btn-info" href="{{ route('ordenes.create') }}">Nueva Nota de Entrega</a>
                        </div>
                </div>
            </div>
            <div class="row top15">
            	<div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th># Nota</th>
                                <th>Fecha Creación</th>
                                <th>Cliente</th>
                                <th>Monto Total </th>
                                <th>Repartidores </th>
                                <th>Estado</th>
                                <th width="200px">Acciones</th>
                            </tr>
                    	</thead>
                        <tbody>
                        @if(count($ordenes) > 0)
                            @foreach ($ordenes as $orden)
                        	    <tr>
                        	        <th scope="row">{{ $orden->num_orden }}</th>
                        	        <td>{{ $orden->created_at->format('d-m-Y') }}</td>
                        	        <td>{{ $orden->cliente->empresa }}</td>
                                    <td>${{ number_format($orden->monto_orden, 2, '.', ',') }}</td>
                        	        <td>
                                        <!-- MOSTRAR LOS REPARTIDORES DE CADA ORDEN -->
                                        @foreach($orden->ordenes_repartidores as $repartidor)
                                            {{ $repartidor->nombre }}
                                            <br>
                                        @endforeach
                                    </td>
                                    @if($orden->idOrdenEstado == 1)
                                        <td><label class="bg-warning"><a style="text-decoration: none; color: white;" href="/facturas/create-by-id/{{ $orden->id }}" >{{ $orden->estado->ordenes_estado }} </a></label></td>
                                    @else
                                         <td><label class="bg-info"><a style="text-decoration: none; color: white;" href="/factura-pdf/0/{{ $orden->id }}" >{{ $orden->estado->ordenes_estado }}</label></td>
                                    @endif
                                    <td>
                                        @if($orden->idOrdenEstado == 1)
                                            <a class="btn btn-primary" href="{{ route('ordenes.edit',$orden->id) }}"><i class="fa fa-pencil"></i></a>
                                        @else
                                             <button class="btn btn-primary" href="{{ route('ordenes.edit',$orden->id) }}" disabled><i class="fa fa-pencil"></i></button>
                                        @endif

                                        <a class="btn btn-info" href="{{ URL::to('orden-pdf/'.$orden->id) }}"><i class="fa fa-file-pdf-o"></i></a>
                        	            {!! Form::open(['method' => 'DELETE','route' => ['ordenes.destroy', $orden->id],'style'=>'display:inline']) !!}
                                       <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </button>
                        	            {!! Form::close() !!}
                        	        </td>
                        	    </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">
                                    <h2 class="bold text-warning text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:30px"></i> No se han agregado notas de entrega</h2>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

        	<!-- PAGINACION -->
           <center> {!! $ordenes->links() !!} </center>

        </div>
    </section>

@endsection


@section('add-plugins')
<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->
        <script src="assets/plugins/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js" type="text/javascript"></script><script src="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->
@endsection
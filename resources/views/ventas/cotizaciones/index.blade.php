@extends('layout.master')


	@section('page-title')
        <h2 class="title bold">Cotizaciones</h2>
    @endsection

	@section('panel-title')
	   <h2 class="title pull-left">Lista de Cotizaciones</h2>
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
                        <div class="pull-right">
                            <a class="btn btn-info" href="{{ URL::to('ventas/cotizaciones/create') }}"> Nueva Cotización</a>
                        </div>
                </div>
            </div>
            <div class="row top15">
            	<div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th># Cotización</th>
                                <th>Fecha Creación</th>
                                <th>Cliente</th>
                                <th>Condicion de Pago</th>
                                <th>Monto</th>
                                <th>Estado</th>
                                <th width="250px">Acciones</th>
                            </tr>
                    	</thead>
                        <tbody>
                        @if(count($cotizaciones) > 0)
                            @foreach ($cotizaciones as $cotizacion)
                        	    <tr>
                        	        <th scope="row">{{ $cotizacion->num_cotizacion }}</th>
                        	        <td>{{ $cotizacion->created_at->format('d-m-Y') }}</td>
                        	        <td>{{ $cotizacion->cliente->empresa }}</td>
                                    <td>{{ $cotizacion->condicion }}</td>
                                    <td>${{ number_format($cotizacion->monto_cotizacion,2,'.',',') }}</td>
                                    @if($cotizacion->idCotizacionEstado == 1)
                                        <td><label class="bg-warning"><a style="text-decoration: none; color: white;">{{ $cotizacion->estado->cotizacion_estado }} </a></label></td>
                                    @else
                                         <td><label class="bg-info"><a style="text-decoration: none; color: white;">{{ $cotizacion->estado->cotizacion_estado }}</label></td>
                                    @endif
                                    <td>
                                        <div class="acciones-btn">
                                            @if($cotizacion->idCotizacionEstado == 1)
                                                <a class="btn btn-primary" href="{{ route('ordenes.create_from_cotizacion',$cotizacion->id) }}"><i class="fa fa-file-text"></i></a>
                                                <a class="btn btn-primary" href="{{ route('cotizaciones.edit',$cotizacion->id) }}"><i class="fa fa-pencil"></i></a>
                                            @else
                                                    <button class="btn btn-primary" href="{{ route('ordenes.create_from_cotizacion',$cotizacion->id) }}" disabled><i class="fa fa-file-text"></i></button>
                                                    <button class="btn btn-primary" href="{{ route('cotizaciones.edit',$cotizacion->id) }}" disabled><i class="fa fa-pencil"></i></button>
                                            @endif
                                            <a class="btn btn-info" href="{{ URL::to('cotizacion-pdf/'.$cotizacion->id) }}"><i class="fa fa-file-pdf-o"></i></a>
                                            {!! Form::open(['method' => 'DELETE','route' => ['cotizaciones.destroy', $cotizacion->id],'style'=>'display:inline']) !!}
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                            {!! Form::close() !!}
                                        <div class="acciones-btn">
                        	        </td>
                        	    </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">
                                    <h2 class="bold text-warning text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:30px"></i> No se han creado cotizaciones</h2>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        	<!-- PAGINACION -->
           <center> {!! $cotizaciones->links() !!} </center>
        </div>
    </section>

@endsection
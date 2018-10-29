@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Pagos</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Nuevo Pago</h2>
    @endsection
@section('content')
    <section class="box primary">
        <header class="panel_header">
            @yield('panel-title')
        </header>
        <div class="content-body">    
        	<div class="row">
                <div class="well transparent col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                    	{!! Form::open(array('id' => 'cliente_form', 'action'=> 'PagosController@nuevo_pago', 'method'=>'POST','class' => 'form-inline')) !!}
                        <div class="col-md-12 col-sm-12 col-xs-12">
                        	<div class="form-group col-lg-4 col-md-4 col-sm-9 col-xs-12">
	                            <h2 class="bold">Clientes</h2><br/>
	                                Seleccione al cliente
	                                <div class="controls">
	                                    {!! Form::select('idCliente', $clientes, null, ['id' => 'cliente', 'placeholder' => 'Seleccione...', 'class' => 'form-control top15']) !!}
	                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div><br>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <button type="submit" target="_blank" class="btn btn-primary btn-md"><i class="fa fa-arrow-right"></i> Siguiente </button>      
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <!-- end -->
                </div>
            </div>
        </div>
    </section>
@endsection
@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Notas de Credito</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Nueva Nota de Credito</h2>
    @endsection

@section('add-styles')
    <link href="{{ asset('plugins/messenger/css/messenger.css') }}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{ asset('plugins/messenger/css/messenger-theme-future.css') }}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{ asset('plugins/messenger/css/messenger-theme-flat.css') }}" rel="stylesheet" type="text/css" media="screen"/>        
    <link href="{{ asset('plugins/messenger/css/messenger-theme-block.css') }}" rel="stylesheet" type="text/css" media="screen"/>
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
                <div id="feedback">
                    
                </div>
                {!! Form::open(array('route' => 'nota_creditos.store','method'=>'POST','class' => 'form-inline')) !!}
                <div class="well transparent">
                    <div class="row">
                        <div class="form-group col-lg-8 col-md-8 col-sm-9 col-xs-12">
                            <h2 class="bold">Controles</h2>
                            <p>Seleccione el Control</p>
                            <div class="controls">
                                    {!! Form::select('idFactura', $facturas_fmt, null, ['id' => 'factura', 'placeholder' => 'Seleccione...', 'class' => 'form-control top15']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix top15"></div>
                <!-- BOTONES -->
                <div class="row top15">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <button type="submit" class="btn btn-primary right15">Procesar</button>
                            <a type="button" class="btn" href="{{ URL::previous() }}">Cancelar</a>
                    </div>
                </div>
            </div>
                {!! Form::close() !!}
        </div>
    </section>
@endsection

@section('add-plugins')

    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
    <script src="{{ asset('plugins/messenger/js/messenger.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/messenger/js/messenger-theme-future.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/messenger/js/messenger-theme-flat.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/messenger.js') }}" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 

@endsection
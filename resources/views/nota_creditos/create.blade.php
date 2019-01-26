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
                {!! Form::open(array('id' => 'factura_form','method'=>'POST','class' => 'form-inline')) !!}
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
    <!-- JS NECESARIO PARA ORDENES - START --> 
    <script type="text/javascript">

        // INICIO - PROCESAR EL FORMULARIO

        $('#factura_form').submit(function(e){
        e.preventDefault();
        var idOrden = $('select[id=orden]').val();

        if(idOrden === ''){
            showErrorMessage('Seleccione una Nota de entrega!');
            return false;
        }
        var itbms = $('#itbms').val();
        if (itbms === '') {
            showErrorMessage('Indique un porcentaje valido!');
            return false;
        }
        var condicion = $('#condicion').val();
        if (condicion === '') {
            showErrorMessage('Debe escribir una condicion de pago!');
            return false;
        }
            $.ajax({
                  headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },  
                type : 'POST',
                url  : '/nueva_factura',
                data : {idOrden: idOrden, itbms: itbms, condicion: condicion},
                success: function( data, textStatus, jQxhr ){
                    if(data === "ok"){
                        swal({
                            title:"Factura creada!",
                            text: "Al cerrar será redireccionado a las facturas",
                            type: "success",
                            confirmButtonText: "Cerrar",
                            },
                            function(){
                              setTimeout(function(){
                                window.location.href = "{{URL::to('facturas')}}";
                              }, 1500);
                            });
                    }
                    else{
                        var errors = "<p>"+data+"</p>";
                        swal({
                            type: 'error',
                            title: "Hubo un error, contacte al ADMIN con el siguiente error:",
                            text: errors,
                            html: true
                        });
                    }
                },
                error: function( data ){
                    // Error...
                    console.log(errors);
                    console.log(data);
                    var errors = "<p>"+data.responseText+"</p>";
                    swal({
                        type: 'error',
                        title: "Hubo un error, contacte al ADMIN con el siguiente error:",
                        text: errors,
                        customClass: 'sweet-alert-lg',
                        html: true
                    });
                }
            });     
        });

        // FIN - PROCESAR EL FORMULARIO
    </script> 
    <!-- JS NECESARIO PARA ORDENES - END --> 
@endsection
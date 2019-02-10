@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Pagos</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Nuevo pago de | <strong>{{ $cliente->empresa }}</strong></h2>
    @endsection

@section('add-styles')
    <link href="{{ asset('plugins/messenger/css/messenger.css') }}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{ asset('plugins/messenger/css/messenger-theme-future.css') }}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{ asset('plugins/messenger/css/messenger-theme-flat.css') }}" rel="stylesheet" type="text/css" media="screen"/>        
    <link href="{{ asset('plugins/messenger/css/messenger-theme-block.css') }}" rel="stylesheet" type="text/css" media="screen"/>

    <link href="{{ asset('plugins/icheck/skins/all.css') }}" rel="stylesheet" type="text/css" media="screen"/>
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

              {!! Form::open(array('id' => 'pago_form', 'idcliente' =>  $cliente->id , 'method'=>'POST')) !!}

                <div class="well transparent">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="bold">Controles</h2>
                            <div class="form-group">
                                <label class="bold">Indicadores del Estado:</label>
                                <label class="bg-muted">Control Por Cobrar</label>
                                <label class="bg-purple">Control Abonado</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div id="div_facturas" class="form-group">
                                <p>Controles Seleccionados</p>
                                <div class="controls">
                                    @foreach($facturas as $factura)
                                        @if($factura->idFacturaEstado == 3)
                                            <label id="facturas" idfactura={{ $factura->id }} class="bg-purple">Control #{{$factura->num_factura}} | <small class="text-dark">${{$factura->monto_factura}}</small></label>
                                        @else
                                            <label id="facturas" idfactura={{ $factura->id }} class="bg-muted">Control #{{$factura->num_factura}} | <small class="text-dark">${{$factura->monto_factura}}</small></label>
                                        @endif
                                    @endforeach
                                </div>
                                <h4 class="bold">Monto Total</h4>
                                <div class="controls">
                                    <p class="uilabels text-lg">
                                        <label class="bg-info" monto_total = {{ $monto_total }} > ${{ $monto_total }}</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <h4 class="bold">Fecha</h4>
                                <div class="controls">
                                    <input type="text" class="form-control" id="created_at" data-mask="dd-mm-yyyy" placeholder=" Default: {{ date('d-m-Y') }} ">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="well transparent">
                    <div class="row">
                        <div class="form-group col-lg-8 col-md-8 col-sm-9 col-xs-12">
                            <h2 class="bold">Tipo de Pago</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12 col-md-12 col-sm-9 col-xs-12">
                            <div id="myRadioGroup">
                                <input id="tipo_pago" class="skin-square-grey" type="radio" name="tipo_pago" checked="checked" value="1"/>
                                <label class="icheck-label form-label">Cheque</label>

                                <input id="tipo_pago" class="skin-square-grey" type="radio" name="tipo_pago" value="2"/> 
                                <label class="icheck-label form-label">Efectivo</label>

                                <input id="tipo_pago" class="skin-square-grey" type="radio" name="tipo_pago" value="3"/> 
                                <label class="icheck-label form-label">ACH</label>

                                <input id="tipo_pago" class="skin-square-grey" type="radio" name="tipo_pago" value="4"/> 
                                <label class="icheck-label form-label">Otro</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12 col-md-12 col-sm-9 col-xs-12">
                                <div id="Tipo1" class="asd">
                                    <div class="well">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <h3>Pago en Cheque</h3>
                                                <div class="form-group">
                                                    <label class="form-label" for="banco">Banco:</label>
                                                    <input type="text" class="form-control" id="banco_cheque" placeholder="Nombre del Banco">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label" for="numero_referencia">Numero de Cheque:</label>
                                                    <input type="text" class="form-control" id="numero_referencia_cheque" placeholder="# de Cheque">
                                                </div>
                                                <div class="form-group has-warning">
                                                    <label class="form-label" for="monto_pago_cheque">Monto Pago:</label>
                                                        <!-- <input type="number" step="any" min="0" class="form-control" id="monto_pago_cheque"> -->
                                                        <input type="text" class="autoNumeric form-control" id="monto_pago_cheque" data-a-sign="$" placeholder="$0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="Tipo2" class="asd" style="display: none;">
                                    <div class="well">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <h3>Pago en Efectivo</h3>
                                                <div class="form-group has-warning">
                                                    <label class="form-label" for="monto_pago_cash">Monto Pago:</label>
                                                        <!-- <input type="number" step="0.01" min="1" class="form-control" id="monto_pago_cash"> -->
                                                        <input type="text" class="autoNumeric form-control" id="monto_pago_cash" data-a-sign="$" placeholder="$0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="Tipo3" class="asd" style="display: none;">
                                    <div class="well">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <h3>Pago por ACH</h3>
                                                <div class="form-group">
                                                    <label class="form-label" for="banco">Banco:</label>
                                                    <input type="text" class="form-control" id="banco_ach" placeholder="Nombre del Banco">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label" for="numero_referencia">Numero de Confirmación:</label>
                                                    <input type="text" class="form-control" id="numero_referencia_ach" placeholder="# de Confirmacion">
                                                </div>
                                                <div class="form-group has-warning">
                                                    <label class="form-label" for="monto_pago_ach">Monto Pago:</label>
                                                    <input type="text" class="autoNumeric form-control" id="monto_pago_ach" data-a-sign="$" placeholder="$0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="Tipo4" class="asd" style="display: none;">
                                    <div class="well">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <h3>Otro método de pago</h3>
                                                <div class="form-group">
                                                    <label class="form-label" for="descripcion">Otro:</label>
                                                    <input type="text" class="form-control" id="descripcion" placeholder="Descripcion">
                                                </div>
                                                 <div class="form-group has-warning">
                                                    <label class="form-label" for="monto_pago_otro">Monto Pago:</label>
                                                        <!-- <input type="number" step="0.01" min="1" class="form-control" id="monto_pago_otro"> -->
                                                        <input type="text" class="autoNumeric form-control" id="monto_pago_otro" data-a-sign="$" placeholder="$0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                   
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix top15"></div>
                <!-- BOTONES -->
                <div class="row top15">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <button id="submit" type="submit" class="btn btn-primary right15">Procesar</button>
                         <a type="button" class="btn" href="{{URL::to('pagos/nuevo_pago_index')}}">Cancelar</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
@endsection

@section('add-plugins')

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
    <script src="{{ asset('plugins/icheck/icheck.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/autosize/autosize.min.js') }}" type="text/javascript"></script>

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 

        <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('plugins/autonumeric/autoNumeric-min.js') }}" type="text/javascript"></script>
        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 

<script type="text/javascript">
$(document).ready(function() {
    $("input[name$='tipo_pago']").on('ifChecked', function() {
        var test = $(this).val();

        $("div.asd").hide();
        $("#Tipo" + test).show();
    });

});

    $('#pago_form').submit(function(e){

        e.preventDefault();
        //disable the submit button
        $("#submit").prop("disabled",true);

        var idCliente = $(this).attr('idcliente');
        var created_at = $('#created_at').val();

        var facturas = [];
        var monto_total = 0;
        $( "#div_facturas" ).find('label').each(function( index ) {
            if($(this).attr('id') == 'facturas'){
                facturas.push($(this).attr('idfactura'));
            }
            else{
                monto_total = $("label").attr('monto_total');
            }
        });
        
        monto_total = parseFloat(monto_total);

        var input;
        var tipo_pago;
        var monto_pago;

        $( "#myRadioGroup" ).find('div').each(function( index ) {
            var asd = $(this).attr('class');
            var ret = asd.split(" ");
            if(ret[1] === 'checked'){
                input = $(this).children();

                tipo_pago = input.val();
            }

        });
        

        if(tipo_pago === '1'){
            var banco = $('#banco_cheque').val();
            var numero_referencia = $('#numero_referencia_cheque').val();
            var monto_pago = $('#monto_pago_cheque').val()
            //Limpiar el $ y las commas
            monto_pago = monto_pago.replace(/[$,]/g, "");
            if( banco.length <= 0 || numero_referencia.length <= 0 || monto_pago.length <= 0){
                swal({
                    title:"ERROR",
                    text: "Debe llenar los campos",
                    type: "error",
                    confirmButtonText: "Cerrar",
                    });
                $("#submit").prop("disabled",false);
                return false;
                
            }
            if( monto_pago > monto_total){
                swal({
                    title:"ERROR",
                    text: "El pago no puede ser mayor al monto total",
                    type: "error",
                    confirmButtonText: "Cerrar",
                    });
                $("#submit").prop("disabled",false);
                return false;
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },  
                type : 'POST',
                url  : '/pagos/guardar_pago',
                data : { idCliente: idCliente, banco: banco, numero_referencia: numero_referencia, facturas: facturas, monto_pago: monto_pago, tipo_pago: tipo_pago, created_at: created_at},
                success: function( data, textStatus, jQxhr ){
                    if(data === "ok"){
                        swal({
                            title:"Pago creado!",
                            text: "Al cerrar será redireccionado a los pagos",
                            type: "success",
                            confirmButtonText: "Cerrar",
                            },
                            function(){
                              setTimeout(function(){
                                window.location.href = "{{URL::to('pagos')}}";
                              }, 3000);
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
                        $("#submit").prop("disabled",false);
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
                    $("#submit").prop("disabled",false);
                }
            });
        }
        else if(tipo_pago === '2'){
            var monto_pago = $('#monto_pago_cash').val()
            //Limpiar el $ y las commas
            monto_pago = monto_pago.replace(/[$,]/g, "");
            if( monto_pago.length <= 0){
                swal({
                    title:"ERROR",
                    text: "Debe llenar los campos",
                    type: "error",
                    confirmButtonText: "Cerrar",
                    });
                $("#submit").prop("disabled",false);
                return false;
            }
            if( monto_pago > monto_total){
                swal({
                    title:"ERROR",
                    text: "El pago no puede ser mayor al monto total",
                    type: "error",
                    confirmButtonText: "Cerrar",
                    });
                $("#submit").prop("disabled",false);
                return false;
            }
            $.ajax({
                  headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },  
                type : 'POST',
                url  : '/pagos/guardar_pago',
                data : { idCliente: idCliente, facturas: facturas, monto_pago: monto_pago, tipo_pago: tipo_pago, created_at: created_at},
                success: function( data, textStatus, jQxhr ){
                   if(data === "ok"){
                        swal({
                            title:"Pago creado!",
                            text: "Al cerrar será redireccionado a los pagos",
                            type: "success",
                            confirmButtonText: "Cerrar",
                            },
                            function(){
                              setTimeout(function(){
                                window.location.href = "{{URL::to('pagos')}}";
                              }, 3000);
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
                        $("#submit").prop("disabled",false);
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
                        // customClass: 'sweet-alert-lg',
                        html: true
                    });
                    $("#submit").prop("disabled",false);
                }
            });
        }
        else if(tipo_pago === '3'){
            var banco = $('#banco_ach').val();
            var numero_referencia = $('#numero_referencia_ach').val();
            var monto_pago = $('#monto_pago_ach').val();
            //Limpiar el $ y las commas
            monto_pago = monto_pago.replace(/[$,]/g, "");
            if( banco.length <= 0 || numero_referencia.length <= 0 || monto_pago.length <= 0){
                swal({
                    title:"ERROR",
                    text: "Debe llenar los campos",
                    type: "error",
                    confirmButtonText: "Cerrar",
                    });
                    $("#submit").prop("disabled",false);
                return false;
            }
            if( monto_pago > monto_total){
                swal({
                    title:"ERROR",
                    text: "El pago no puede ser mayor al monto total",
                    type: "error",
                    confirmButtonText: "Cerrar",
                    });
                    $("#submit").prop("disabled",false);
                return false;
            }
            $.ajax({
                  headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },  
                type : 'POST',
                url  : '/pagos/guardar_pago',
                data : { idCliente: idCliente, banco: banco, numero_referencia: numero_referencia, facturas: facturas, monto_pago: monto_pago, tipo_pago: tipo_pago, created_at: created_at},
                success: function( data, textStatus, jQxhr ){
                    if(data === "ok"){
                        swal({
                            title:"Pago creado!",
                            text: "Al cerrar será redireccionado a los pagos",
                            type: "success",
                            confirmButtonText: "Cerrar",
                            },
                            function(){
                              setTimeout(function(){
                                window.location.href = "{{URL::to('pagos')}}";
                              }, 3000);
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
                        $("#submit").prop("disabled",false);
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
                    $("#submit").prop("disabled",false);
                }
            });
        }
        else{
            var descripcion = $('#descripcion').val();
            var monto_pago = $('#monto_pago_otro').val();
            //Limpiar el $ y las commas
            monto_pago = monto_pago.replace(/[$,]/g, "");
            if( descripcion.length <= 0 || monto_pago.length <= 0){
                swal({
                    title:"ERROR",
                    text: "Debe llenar la descripcion",
                    type: "error",
                    confirmButtonText: "Cerrar",
                    });
                    $("#submit").prop("disabled",false);
                return false;
            }
            if( monto_pago > monto_total){
                swal({
                    title:"ERROR",
                    text: "El pago no puede ser mayor al monto total",
                    type: "error",
                    confirmButtonText: "Cerrar",
                    });
                    $("#submit").prop("disabled",false);
                return false;
            }
            $.ajax({
                  headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },  
                type : 'POST',
                url  : '/pagos/guardar_pago',
                data : { idCliente: idCliente, facturas: facturas, monto_pago: monto_pago, tipo_pago: tipo_pago, descripcion: descripcion, created_at: created_at},
                success: function( data, textStatus, jQxhr ){
                    if(data === "ok"){
                        swal({
                            title:"Pago creado!",
                            text: "Al cerrar será redireccionado a los pagos",
                            type: "success",
                            confirmButtonText: "Cerrar",
                            },
                            function(){
                              setTimeout(function(){
                                window.location.href = "{{URL::to('pagos')}}";
                              }, 3000);
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
                        $("#submit").prop("disabled",false);
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
                    $("#submit").prop("disabled",false);
                }
            });
        }    
    });
</script> 
<!-- JS NECESARIO PARA ORDENES - END --> 
@endsection
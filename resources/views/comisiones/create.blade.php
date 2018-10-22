@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Comisiones</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Nueva Comision</h2>
    @endsection

@section('add-styles')
    <link href="{{ asset('assets/plugins/messenger/css/messenger.css') }}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{ asset('assets/plugins/messenger/css/messenger-theme-future.css') }}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{ asset('assets/plugins/messenger/css/messenger-theme-flat.css') }}" rel="stylesheet" type="text/css" media="screen"/>        
    <link href="{{ asset('assets/plugins/messenger/css/messenger-theme-block.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection

@section('content')

    <section class="box primary">
        <!--  PANEL HEADER    -->      
        <header class="panel_header">
            <h2 class="title pull-left">@yield('panel-title')</h2>
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

              {!! Form::open(array('id' => 'orden_form','method'=>'POST','class' => 'form-inline')) !!}

                <div class="well transparent">
                    <div class="row">
                        <div class="form-group col-lg-8 col-md-8 col-sm-9 col-xs-12">
                            <h2 class="bold">Repartidor</h2>
                            <p>Seleccione al Repartidor</p>
                            <div class="controls">
                                {!! Form::select('idRepartidor', $repartidores, null, ['id' => 'repartidor', 'placeholder' => 'Seleccione...', 'class' => 'form-control right15']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="well transparent">
                    <div class="row">
                        <div class="form-group col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                <h2 class="bold">Ordenes</h2>
                                <p>Escoja la orden, el porcentaje y presione <kbd class="bg-primary">+</kbd></p>
                                    <div class="controls">
                                        <select id="orden" class="form-control right15 top15" name="orden">
                                            <option selected="selected" disabled="disabled" hidden="hidden" value="">Seleccione un repartidor</option>
                                        </select>
                                       <!--  {!! Form::select('orden', $ordenes, null, ['id' => 'orden', 'placeholder' => 'Seleccione...', 'class' => 'form-control right15 top15']) !!} -->
                                        <div class="input-group col-lg-2 col-md-6 col-sm-9 col-xs-12 right15 top15">
                                            <span class="input-group-addon"><i class="fa fa-percent" aria-hidden="true"></i></span>
                                            <input class="form-control" type="number" id="porcentaje" name="porcentaje" min=1 value=1>
                                        </div>

                                        <button type="button" id="add_orden" class="btn btn-primary top15">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </div>
                            </div>
                        </div>
                        <!-- Lista de comisiones -->
                        <div class="row top15">
                            <div class="form-group col-lg-4 col-md-4 col-sm-9 col-xs-12">
                                <div class="list-group" id="lista_ordenes" hidden="hidden">
                                    <div class="list-group-item">
                                        <h4 class="list-group-item-heading bold text-center">Notas de Entrega</h4>
                                    </div>
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
            <div id="test">
                
            </div>
                {!! Form::close() !!}
        </div>
    </section>
@endsection

@section('add-plugins')

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
    <script src="{{ asset('assets/plugins/messenger/js/messenger.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/messenger/js/messenger-theme-future.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/messenger/js/messenger-theme-flat.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/messenger.js') }}" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
    <!-- JS NECESARIO PARA ORDENES - START --> 
    <script type="text/javascript">

        $('#repartidor').change(function(){
            var idRepartidor = $(this).val();
            $('#orden').empty();
            $("#orden").append('<option selected="selected" disabled="disabled" hidden="hidden" value="">Ordenes Cargadas</option>');
            $.ajax({
                  headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },  
                type : 'POST',
                url  : '/comisiones_repartidor',
                dataType: 'json',
                data : {idRepartidor: idRepartidor},
                   beforeSend: function(){
                     $("#orden").prop("disabled",true);
                     $('#orden').empty();
                     $("#orden").append('<option selected="selected" disabled="disabled" hidden="hidden" value="">Cargando Ordenes</option>');
                   },
                   complete: function(){
                     $("#orden").prop("disabled",false);
                     $("#orden").append('<option selected="selected" disabled="disabled" hidden="hidden" value="">Ordenes Cargadas</option>');
                   },
                    success: function( data, textStatus, jQxhr ){
                        if (jQuery.isEmptyObject(data)) {
                            $("#orden").append('<option value="">No tiene ordenes</option>');
                        }
                        else{
                            $.each(data, function(index, value){
                                $("#orden").append('<option value="'+index+'">'+value+'</option>');
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

        // INICIO DE LA ACCION DE AGREGAR PRODUCTOS A LA LISTA
        $('#add_orden').click(function(){
            var idOrden = $('#orden').val();
            //Validacion del orden seleccionado
            if (idOrden === '') {
                showErrorMessage('Seleccione una Orden!');
                return false;
            }
            var porcentaje = $('#porcentaje').val();
            if(porcentaje < 1 || porcentaje === ''){
                showErrorMessage('Escriba un porcentaje valido!');
                return false;              
            }
            var num_orden = $('select[id=orden] option:selected').html();
            // $('#porcentaje').val(1);
            $('#lista_ordenes').show();
            var item = $('#lista_ordenes').find('li[idOrden='+idOrden+']');
            if(item.html() !== undefined){
                    item.remove();
            }
            var li = "<li idOrden="+idOrden+" porcentaje="+porcentaje+" class='list-group-item active'>Nota #"+num_orden+"<span class='badge'><a idOrden="+idOrden+"><i class='fa fa-times'></i></a></span><span class='badge'><i class='fa fa-percent' aria-hidden='true'></i>"+porcentaje+"</span></li>";
                $('#lista_ordenes').append(li);
       
        });
        // FIN DE LA ACCION DE AGREGAR PRODUCTOS A LA LISTA
        // INICIO Borrar Productos de la lista
        $("#lista_ordenes").on("click", "a", function(e) {
            e.preventDefault();
            var cantidad_hermanos = $(this).parent().parent().siblings().size();
            if(cantidad_hermanos === 1){
                $(this).parent().parent().parent().hide();
            }
            var id = $(this).attr('idOrden');
            $('li[idOrden='+id+']').remove();
        });
        // FIN BORRAR PRODUCTOS DE LA LISTA
        // INICIO - PROCESAR EL FORMULARIO

        $('#orden_form').submit(function(e){
        e.preventDefault();
        var idRepartidor = $('select[id=repartidor]').val();

        if(idRepartidor === ''){
            showErrorMessage('Seleccione a un Repartidor!');
            return false;
        }
        var ordenes = [];
        var obj = {};
        var lista = $('#lista_ordenes');
        $(lista).find('li').each(function(index, value){
            id = $(this).attr('idOrden');
            porcentaje = $(this).attr('porcentaje');
            obj[id] = porcentaje;
            ordenes.push(obj);
        });
        if(ordenes.length === 0){
            showErrorMessage('Escoja Al Menos Una Orden!');
            return false;
        }
            $.ajax({
                  headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },  
                type : 'POST',
                url  : '/nueva_comision',
                data : {ordenes: ordenes, idRepartidor: idRepartidor},
                    success: function( data, textStatus, jQxhr ){
                        if(data === "ok"){
                            swal({
                                title:"Comision creada!",
                                text: "Al cerrar será redireccionado a la lista de comisiones",
                                type: "success",
                                confirmButtonText: "Cerrar",
                                },
                                function(){
                                  setTimeout(function(){
                                    window.location.href = "{{URL::to('comisiones')}}";
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
                    }
            });     
        });

        // FIN - PROCESAR EL FORMULARIO
    </script> 
    <!-- JS NECESARIO PARA ORDENES - END --> 
@endsection
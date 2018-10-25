@extends('layout.master')
    @section('page-title')
        <h2 class="title bold">Notas de Entrega</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Modificando Nota de entrega #{{ $cotizacion->num_cotizacion }}</h2>
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

	           {!! Form::model($cotizacion, ['id' => 'cotizacion_form','name' => $cotizacion->id,'class' => 'form-inline']) !!}

                    <div class="well transparent">
                        <div class="row">
                            <div class="form-group col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                <h2 class="bold">Clientes</h2>
                                Seleccione al cliente
                                <div class="controls">
                                    {!! Form::select('idCliente', $clientes, null, ['id' => 'cliente', 'placeholder' => 'Seleccione...', 'class' => 'form-control top15']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="well transparent">
                        <div class="row top15">
                           <div class="form-group col-lg-12 col-md-8 col-sm-9 col-xs-12">
                                    <h2 class="bold">Productos</h2>
                                    Escoja el producto, la cantidad y presione <kbd class="bg-primary">+</kbd>
                                        <div class="controls">
                                            {!! Form::select('producto', $productos, null, ['id' => 'producto', 'placeholder' => 'Seleccione...', 'class' => 'form-control right15 top15']) !!}
                                            <div class="input-group col-lg-3 col-md-6 col-sm-9 col-xs-12 right15 top15">
                                                <span class="input-group-addon">Cantidad:</span>
                                                <input class="form-control" type="number" id="cantidad" name="cantidad" min=1 value=1>
                                            </div>
                                            <div class="input-group col-lg-2 col-md-6 col-sm-9 col-xs-12 right15 top15">
                                                {{-- <span class="input-group-addon"><i class='fa fa-usd'></i></span>
                                                <input class="form-control" lang="en-150" type="number" step="0.000001" id="precio" name="precio" min=1.00 value=0.00> --}}
                                                <span class="input-group-addon"><i class='fa fa-usd'></i></span>
                                                <input type="text" id="precio" name="precio" class="autoNumeric form-control" placeholder="0.00">
                                            </div>
                                            <button type="button" id="add_producto" class="btn btn-primary top15">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </div>
                            </div>
                        </div>
                        <!-- Lista de productos -->
                        <div class="row top15">
                            <div class="form-group col-lg-4 col-md-4 col-sm-9 col-xs-12">
                                <div class="list-group" id="lista_productos">
                                    <div class="list-group-item">
                                        <h4 class="list-group-item-heading bold text-center">Productos Seleccionados</h4>
                                    </div>
                                    @foreach($productos_seleccionados as $producto)
                                        <li idProducto="{{ $producto->id }}" cantidad="{{ $producto->pivot->cantidad_producto }}" precio="{{ $producto->pivot->precio_final }}" class='list-group-item active'>
                                        <span class='badge'><a idProducto="{{ $producto->id }}"><i class='fa fa-times'></i></a></span><span class='badge'>Qty: {{ $producto->pivot->cantidad_producto }}</span>
                                        <span class='badge'><i class='fa fa-usd'></i>{{ number_format($producto->pivot->precio_final,2,'.',',') }}</span>
                                        {{ $producto->codigo }}
                                        </li>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix top15"></div>
                    <div class="well transparent">
                        <h2 class="bold">Detalles</h2>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
                                <div class="form-group right15">
                                    {!! Form::label('condicion', 'Condicion de Pago', array('class' => 'form-label')) !!}
                                    <div class="controls">
                                        {!! Form::text('condicion', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="form-group right15">
                                    {!! Form::label('t_entrega', 'Tiempo de entrega', array('class' => 'form-label')) !!}
                                    <div class="controls">
                                        {!! Form::text('t_entrega', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="form-group right15">
                                    {!! Form::label('d_oferta', 'Duracion de la oferta', array('class' => 'form-label')) !!}
                                    <div class="controls">
                                        {!! Form::text('d_oferta', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="form-group right15">
                                    {!! Form::label('garantia', 'Garantia', array('class' => 'form-label')) !!}
                                    <div class="controls">
                                        {!! Form::text('garantia', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('itbms', 'ITBMS', array('class' => 'form-label')) !!}
                                    <div class="controls">
                                        {!! Form::number('itbms', '7', array('class' => 'form-control', 'min'=>'0')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-9 col-xs-12">
                                <div class="form-group">
                                    {!! Form::label('notas', 'Notas', array('class' => 'form-label')) !!}
                                    <div class="controls">
                                        {{ Form::textarea('notas', null, ['class' => 'form-control', 'size' => '30x5']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- BOTONES -->
                    <div class="row top15">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <button type="submit" class="btn btn-primary right15">Modificar</button>
                                 <a type="button" class="btn" href="{{ URL::previous() }}">Cancelar</a>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>



@endsection

@section('add-plugins')

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
    <script src="{{ asset('plugins/messenger/js/messenger.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/messenger/js/messenger-theme-future.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/messenger/js/messenger-theme-flat.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/messenger.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/autonumeric/autoNumeric-min.js') }}" type="text/javascript"></script>
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
    <!-- JS NECESARIO PARA cotizacionES - START --> 
    <script type="text/javascript">
        $('#producto').change(function(){
            var prod_id = $(this).val();
             $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/check_precio',
                data: {"idProducto": prod_id},
                success: function(data, textStatus){
                    $('#precio').val(data);
                }
            });
        });
        // INICIO DE LA ACCION DE AGREGAR PRODUCTOS A LA LISTA
        $('#add_producto').click(function(){
            var attr = 'idProducto';
            var idProducto = $('#producto').val();
            //Validacion del producto seleccionado
            if (idProducto === '') {
                showErrorMessage('Seleccione un Producto!');
                return false;
            }
            var codigo = $('select[id=producto] option:selected').html();
            var cantidad = $('#cantidad').val();
            var precio = $('#precio').val();
            $('#cantidad').val(1);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/check_inventario',
                data: {"idProducto": idProducto, "cantidad": cantidad},
               beforeSend: function(){
                 $("#loading").show();
               },
               complete: function(){
                 $("#loading").hide();
               },
                success: function(data, textStatus){
                    $("#add_producto").prop('disabled', false);
                   if(data === 'Disponible'){
                        $('#lista_productos').show();
                        var item = $('#lista_productos').find('li[idProducto='+idProducto+']');
                        if(item.html() !== undefined){
                                item.remove();
                        }
                        var li = "<li idProducto="+idProducto+" cantidad="+cantidad+" precio="+precio+" class='list-group-item active'>";
                        li += "<span class='badge'><a idProducto="+idProducto+"><i class='fa fa-times'></i></a></span>";
                        li += "<span class='badge'>Qty: "+cantidad+"</span>";
                        li += "<span class='badge'><i class='fa fa-usd'></i>"+precio+"</span>";
                        li += codigo+"</li>";
                        $('#lista_productos').append(li);
                   } 
                   else {
                        showErrorMessage('Cantidad no disponible para este producto ('+codigo+')');
                        $('#lista_productos').show();
                        var item = $('#lista_productos').find('li[idProducto='+idProducto+']');
                        if(item.html() !== undefined){
                                item.remove();
                        }
                        var li = "<li idProducto="+idProducto+" cantidad="+cantidad+" precio="+precio+" class='list-group-item active'>";
                        li += "<span class='badge'><a idProducto="+idProducto+"><i class='fa fa-times'></i></a></span>";
                        li += "<span class='badge'>Qty: "+cantidad+"</span>";
                        li += "<span class='badge'><i class='fa fa-usd'></i>"+precio+"</span>";
                        li += codigo+"</li>";
                        $('#lista_productos').append(li);
                   }
                } 
            });
        });
        // FIN DE LA ACCION DE AGREGAR PRODUCTOS A LA LISTA

        // INICIO Borrar Productos de la lista
        $("#lista_productos").on("click", "a", function(e) {
            e.preventDefault();
            var cantidad_hermanos = $(this).parent().parent().siblings().size();
            if(cantidad_hermanos === 1){
                $(this).parent().parent().parent().hide();
            }
            var id = $(this).attr('idProducto');
            $('li[idProducto='+id+']').remove();
        });
        // FIN BORRAR PRODUCTOS DE LA LISTA

        // INICIO - PROCESAR EL FORMULARIO

        $('#cotizacion_form').submit(function(e){
            e.preventDefault();
            var idCliente = $('select[id=cliente]').val();
            var idCotizacion = $('#cotizacion_form').attr('name');
            if(idCliente === null){
                showErrorMessage('Seleccione a un Cliente!');
                return false;
            }
            var condicion = $('#condicion').val();
            var t_entrega = $('#t_entrega').val();
            var d_oferta = $('#d_oferta').val();
            var garantia = $('#garantia').val();
            var notas = $('#notas').val();
            var itbms = $('#itbms').val();

            if( condicion == '' || t_entrega == '' || d_oferta == '' || garantia == '' || itbms == ''){
                showErrorMessage('Verifique los datos en el area de "Detalles", e intente de nuevo');
                return false;
            }
            var productos = [];
            var obj = {};
            var lista = $('#lista_productos');
            $(lista).find('li').each(function(index, value){
                id = $(this).attr('idproducto');
                cantidad = $(this).attr('cantidad');
                precio = $(this).attr('precio');
                obj = {
                    id: id,
                    cantidad: cantidad,
                    precio_final: precio
                };
                productos.push(obj);
            });
            if(productos.length === 0){
                showErrorMessage('Escoja Al Menos Un Producto!');
                return false;
            }
            var jsondata = JSON.stringify(productos);
                $.ajax({
                      headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },  
                    type : 'POST',
                    url  : '/ventas/cotizaciones/update',
                    data :
                            {
                                data: jsondata, 
                                idCotizacion: idCotizacion,
                                idCliente: idCliente, 
                                condicion: condicion,
                                t_entrega: t_entrega,
                                d_oferta: d_oferta,
                                garantia: garantia,
                                notas: notas,
                                itbms: itbms
                            },
                    beforeSend: function() { 
                      // $("#product_id").html('<option> Loading ...</option>');
                      $("#submit").prop('disabled', true);
                    },
                    success: function( data, textStatus, jQxhr ){
                        if(data === "ok"){
                            swal({
                                title:"Cotizacion creada!",
                                text: "Al cerrar será redireccionado a las cotizaciones",
                                icon: "success",
                                button: "Cerrar",
                                });
                        }
                        else{
                            var errors = "<p>"+data+"</p>";
                            swal({
                                icon: 'error',
                                title: "Hubo un error, contacte al ADMIN con el siguiente error:",
                                text: errors,
                                html: true
                            });
                            $("#submit").prop('disabled', false);
                        }
                    },
                    error: function( data ){
                        // Error...
                        console.log(errors);
                        console.log(data);
                        var errors = "<p>"+data.responseText+"</p>";
                        swal({
                            icon: 'error',
                            title: "Hubo un error, contacte al ADMIN con el siguiente error:",
                            text: errors,
                            // customClass: 'sweet-alert-lg',
                            html: true
                        });
                        $("#submit").prop('disabled', false);
                    }
                });    
        });

        // FIN - PROCESAR EL FORMULARIO
    </script> 
    <!-- JS NECESARIO PARA ORDENES - END --> 
@endsection
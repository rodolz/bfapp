@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Ordenes de Compra</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Nueva Orden de Compra para {{ $proveedor->name }}</h2>
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
	           {!! Form::open(array('id' => 'purchase_order_from','method'=>'POST','class' => 'form-inline')) !!}
                    <div class="well transparent">
                        <div class="row">
                            <div class="form-group col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                <h2 class="bold">Proveedor - {{ $proveedor->name }}</h2>
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
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="list-group" id="lista_productos" hidden="hidden">
                                    <div class="list-group-item">
                                        <h4 class="list-group-item-heading bold text-center">Productos Seleccionados</h4>
                                    </div>
                                </div>
                                <h4 class="list-group-item-heading bold text-center" id="loading" hidden="hidden">Cargando ...</h4>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <h4 class="list-group-item-heading bold text-center" id="subtotal">Subtotal: $0.00</h4><span id="loading" hidden="true">Calculando...</span>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix top15"></div>
                    <!-- BOTONES -->
                    <div class="row top15">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <button type="submit" id="submit" class="btn btn-primary right15">Procesar</button>
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
    <!-- JS NECESARIO PARA ORDENES - START --> 
    <script type="text/javascript">

        $('#lista_productos').on('DOMSubtreeModified',function(){
            var lis = $('#lista_productos').find("li");
            var subtotal = 0.00;
            lis.each(function(index){
                subtotal += parseFloat($(this).attr('precio').replace(',',''));
            });
            console.log(subtotal);
            $('#subtotal').html("Subtotal: $"+subtotal);
        });

        $('#producto').change(function(){
            var prod_id = $(this).val();
             $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/producto_proveedor/check_precio',
                data: {"idProducto": prod_id},
                beforeSend: function(){
                    $("#add_producto").prop("disabled",true);
                },
                success: function(data, textStatus){
                    $('#precio').val(data);
                    $("#add_producto").prop("disabled",false);
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
            $("#add_producto").prop('disabled', false);
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

        $('#purchase_order_from').submit(function(e){
            e.preventDefault();
            var idCliente = $('select[id=cliente]').val();

            if(idCliente === ''){
                showErrorMessage('Seleccione a un Cliente!');
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
            var repartidores = [];
            var lista = $('#lista_repartidores');
            $(lista).find('li').each(function(index, value){
                id = $(this).attr('idrepartidor');
                repartidores.push(id);
            });
            if(repartidores.length === 0){
                showErrorMessage('Escoja Al Menos A Un Repartidor!');
                return false;
            }
            var jsondata = JSON.stringify(productos);
                $.ajax({
                      headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },  
                    type : 'POST',
                    url  : '/nueva_orden',
                    data : {data: jsondata, idCliente: idCliente, repartidores: repartidores},
                    beforeSend: function() { 
                      // $("#product_id").html('<option> Loading ...</option>');
                      $("#submit").prop('disabled', true);
                    },
                    success: function( data, textStatus, jQxhr ){
                        if(data === "ok"){
                            swal({
                                title:"Orden de Compra creada!",
                                text: "Al cerrar será redireccionado a las Ordenes de Compra",
                                type: "success",
                                confirmButtonText: "Cerrar",
                                },
                                function(){
                                  setTimeout(function(){
                                    window.location.href = "{{URL::to('ordenes')}}";
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
                            $("#submit").prop('disabled', false);
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
                        $("#submit").prop('disabled', false);
                    }
                });     
        });
</script> 
<!-- JS NECESARIO PARA ORDENES - END --> 
@endsection
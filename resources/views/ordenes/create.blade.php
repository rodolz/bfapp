@extends('layout.master')

@section('page-title')
    <h2 class="title bold">Notas de Entrega</h2>
@endsection

@section('panel-title')
    <h2 class="title pull-left">Nueva Nota de Entrega</h2>
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

	           {!! Form::open(array('id' => 'orden_form','method'=>'POST','class' => 'form-inline')) !!}

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
                            <div class="form-group col-lg-4 col-md-6 col-sm-9 col-xs-12">
                                <div class="list-group" id="lista_productos" hidden="hidden">
                                    <div class="list-group-item">
                                        <h4 class="list-group-item-heading bold text-center">Productos Seleccionados</h4>
                                    </div>
                                </div>
                                <h4 class="list-group-item-heading bold text-center" id="loading" hidden="hidden">Cargando ...</h4>
                            </div>
                        </div>
                    </div>
                    <div class="well transparent">
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-9 col-xs-12">
                                <h2 class="bold">Repartidores</h2>
                                Escoja el repartidor y presione <kbd class="bg-primary">+</kbd>
                                    <div class="controls">
                                        {!! Form::select('repartidor', $repartidores, null, ['id' => 'repartidor', 'placeholder' => 'Seleccione...', 'class' => 'form-control right15 top15']) !!}
                                        <button type="button" id="add_repartidor" class="btn btn-primary top15">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </div>
                            </div>
                        </div>
                        <!-- Lista de repartidores -->
                        <div class="row top15">
                            <div class="form-group col-lg-4 col-md-4 col-sm-9 col-xs-12">
                                <div class="list-group" id="lista_repartidores" hidden="hidden">
                                    <div class="list-group-item">
                                        <h4 class="list-group-item-heading bold text-center">Repartidores Seleccionados</h4>
                                    </div>
                                </div>
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
    <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/autonumeric/autoNumeric-min.js') }}" type="text/javascript"></script>
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> 
    <!-- JS NECESARIO PARA ORDENES - START --> 
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
                beforeSend: function(){
                    $("#add_producto").prop("disabled",true);
                },
                success: function(data, textStatus){
                    $('#precio').val(data);
                    $("#add_producto").prop("disabled",false);
                }
                // error: function(data){
                //     console.log(data);
                // }
            });
        });
        // INICIO DE LA ACCION DE AGREGAR PRODUCTOS A LA LISTA
        $('#add_producto').click(function(){
            var attr = 'idProducto';
            var idProducto = $('#producto').val();
            //Validacion del producto seleccionado
            if (idProducto === '') {
                swal({
                    title: "Seleccione un producto",
                    icon: 'error'
                });
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
                        swal({
                            title: "Cantidad no disponible",
                            text: "Inventario insuficiente para:",
                            content: {
                                element: "p",
                                attributes: {
                                    innerText: codigo,
                                    className: 'bg-muted',
                                },
                            },
                            icon: 'error'
                        });
                   }
                } 
            });
        });
        // FIN DE LA ACCION DE AGREGAR PRODUCTOS A LA LISTA
        // INICIO DE LA ACCION DE AGREGAR REPARTIDORES A LA LISTA
        $('#add_repartidor').click(function(){
            var idRepartidor = $('select[id=repartidor]').val();
            var nombre_repartidor = $('select[id=repartidor] option:selected').html();
            //Validacion del producto seleccionado
            if (idRepartidor === '') {
                swal({
                    title: "Seleccione a un repartidor",
                    icon: 'error'
                });
                return false;
            }
            else{
                $('#lista_repartidores').show();
                var item = $('#lista_repartidores').find('li[idRepartidor='+idRepartidor+']');
                    if(item.html() !== undefined){
                        item.remove();
                    }
                var li = "<li idRepartidor="+idRepartidor+" class='list-group-item active'><span class='badge'><a idRepartidor="+idRepartidor+"><i class='fa fa-times'></i></a></span>"+nombre_repartidor+"</li>";
                $('#lista_repartidores').append(li);
            }
        });
        // FIN DE LA ACCION DE AGREGAR REPARTIDORES A LA LISTA

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
        // INICIO Borrar Productos de la lista
        $("#lista_repartidores").on("click", "a", function(e) {
            e.preventDefault();
            var cantidad_hermanos = $(this).parent().parent().siblings().size();
            if(cantidad_hermanos === 1){
                $(this).parent().parent().parent().hide();
            }
            var id = $(this).attr('idRepartidor');
            $('li[idRepartidor='+id+']').remove();
        });
        // FIN BORRAR PRODUCTOS DE LA LISTA
        // INICIO - PROCESAR EL FORMULARIO

        $('#orden_form').submit(function(e){
            e.preventDefault();
            var idCliente = $('select[id=cliente]').val();
            if(idCliente === ''){
                swal({
                    title: "Seleccione a un cliente",
                    icon: 'error'
                });
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
                swal({
                    title: "Debe seleccionar al menos un producto",
                    icon: 'error'
                });
                return false;
            }
            var repartidores = [];
            var lista = $('#lista_repartidores');
            $(lista).find('li').each(function(index, value){
                id = $(this).attr('idrepartidor');
                repartidores.push(id);
            });
            if(repartidores.length === 0){
                swal({
                    title: "Debe seleccionar al menos a un repartidor",
                    icon: 'error'
                });
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
                    success: function( data ){
                        if(data === "ok"){
                            swal({
                                title:"Nota de entrega creada!",
                                text: "Al cerrar será redireccionado a las notas de entrega",
                                icon: "success",
                                buttons: false,
                                timer: 1000
                                }).then(() => {
                                  setTimeout(function(){
                                    window.location.href = "{{URL::to('ordenes')}}";
                                  }, 1000);
                                });
                        }
                        else{
                            swal({
                                title: "Hubo un error, contacte al ADMIN con el siguiente error:",
                                text: data,
                                icon: 'error'
                            });
                            $("#submit").prop('disabled', false);
                        }
                    },
                    error: function( jqXHR ){
                        swal({
                            title: "Hubo un error, contacte al ADMIN con el siguiente error:",
                            text: jqXHR.status+" - "+jqXHR.statusText,
                            icon: 'error'
                        });
                        $("#submit").prop('disabled', false);
                    }
                });     
        });

        // FIN - PROCESAR EL FORMULARIO
</script>
@endsection


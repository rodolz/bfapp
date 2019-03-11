@extends('layout.master')

    @section('panel-title')
        <h2 class="title pull-left">Nueva Cotización</h2>
    @endsection

    @section('page-title')
        <h2 class="title bold">Cotizaciones</h2>
    @endsection

@section('content')

    <section class="box primary">    
        <header class="panel_header">
            @yield('panel-title')
        </header>
        <div class="content-body">    
            <div class="row">
                <div id="feedback">
            
                </div>

   		       {!! Form::open(array('id' => 'presupuesto_form','method'=>'POST','class' => 'form-inline')) !!}

                <div class="well transparent">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                            <h2 class="bold">Cliente</h2>
                            <p>Seleccione al cliente</p>
                            <div class="controls">
                                {!! Form::select('idCliente', $clientes, null, ['id' => 'cliente', 'placeholder' => 'Seleccione...', 'class' => 'form-control right15 top15']) !!}
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
                                <div class="list-group" id="lista_productos" hidden="hidden">
                                    <div class="list-group-item">
                                        <h4 class="list-group-item-heading bold text-center">Productos Seleccionados</h4>
                                    </div>
                                </div>
                                <h4 class="list-group-item-heading bold text-center" id="loading" hidden="hidden">Cargando ...</h4>
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
                        <button type="submit" class="btn btn-primary right15">Procesar</button>
                         <a type="button" class="btn" href="{{ URL::to('ventas/cotizaciones') }}">Cancelar</a>
                    </div>
                </div>
            </div>
                {!! Form::close() !!}
        </div>
    </section>
@endsection

@section('add-plugins')

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
                swal({
                    title: "Debe seleccionar al menos un producto",
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
                            icon: 'warning',
                            timer: 1000,
                            buttons: false
                        });
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

        $('#presupuesto_form').submit(function(e){
            e.preventDefault();
            var idCliente = $('select[id=cliente]').val();

            if(idCliente === ''){
                swal({
                    title: "Debe seleccionar a un cliente",
                    icon: 'error'
                });
                return false;
            }
            var condicion = $('#condicion').val();
            var t_entrega = $('#t_entrega').val();
            var d_oferta = $('#d_oferta').val();
            var garantia = $('#garantia').val();
            var notas = $('#notas').val();
            var itbms = $('#itbms').val();

            if( condicion == '' || t_entrega == '' || d_oferta == '' || garantia == '' || itbms == ''){
                swal({
                    title: "Verifique los datos en 'Detalles', e intente de nuevo",
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
                    title: "Seleccione al menos un Producto",
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
                    url  : '/ventas/nueva_cotizacion',
                    data :
                            {
                                data: jsondata, 
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
                            text: "Cotización creada correctamente",
                            icon: "success",
                            buttons: false,
                            timer: 1500
                            }).then(() => {
                                    swal({
                                        title:"Crear una nueva cotización?",
                                        text: "Si cancela se redireccionará a 'Cotizaciones'",
                                        icon: "info",
                                        buttons: true,
                                    }).then((value) => {
                                        if(value){
                                            setTimeout(function(){
                                                window.location.href = "{{Request::url()}}";
                                            });
                                        } else{
                                            setTimeout(function(){
                                                window.location.href = "{{ URL::to('ventas/cotizaciones') }}";
                                            }); 
                                        }
                                    })
                                })
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
    <!-- JS NECESARIO PARA ORDENES - END --> 
@endsection
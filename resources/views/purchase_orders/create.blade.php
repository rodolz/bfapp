@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Ordenes de Compra</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Nueva Orden de Compra para {{ $proveedor->name }}</h2>
    @endsection

@section('content')

    <section class="box primary">     
        <header class="panel_header">
            @yield('panel-title')
        </header>
        <div class="content-body">    
            <div class="row">
	           {!! Form::open(array('id' => 'purchase_order_form','method'=>'POST','class' => 'form-inline')) !!}
                    <div class="well transparent">
                        <div class="row">
                            <div class="form-group col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                <h2 class="bold" id="proveedor" name={{ $proveedor->id }} >Proveedor - {{ $proveedor->name }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="well transparent">
                        <div class="row">
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
                    <div class="well transparent">
                        <div class="row">
                            <div class="col-lg-12 col-md-8 col-sm-9 col-xs-12">
                                <h2 class="bold">Ship to</h2>
                            </div>
                            <div class="col-lg-12 col-md-8 col-sm-9 col-xs-12 top15">
                                {!! Form::label('taxlabel', 'Seleccionar la direccion a donde sera enviado', array('class' => 'form-label')) !!}
                            </div>
                            <div class="col-lg-12 col-md-8 col-sm-9 col-xs-12">
                                <div class="input-group col-lg-2 col-md-6 col-sm-9 col-xs-12">
                                    {!! Form::select('shipto', $shipto, null, ['id' => 'shipto', 'placeholder' => 'Seleccione...', 'class' => 'form-control right15 top15']) !!}
                                </div>
                            </div>
                        </div>    
                    </div>
                    <div class="well transparent">
                        <div class="row">
                            <div class="col-lg-12 col-md-8 col-sm-9 col-xs-12">
                                <h2 class="bold">Impuestos</h2>
                            </div>
                            <div class="col-lg-12 col-md-8 col-sm-9 col-xs-12 top15">
                                {!! Form::label('taxlabel', 'Tax Rate', array('class' => 'form-label')) !!}
                            </div>
                            <div class="col-lg-12 col-md-8 col-sm-9 col-xs-12">
                                <div class="input-group col-lg-2 col-md-6 col-sm-9 col-xs-12">
                                <span class="input-group-addon"><i class="fa fa-percent" aria-hidden="true"></i></span>
                                    {!! Form::number('tax', '7', array('id' => 'tax', 'class' => 'form-control', 'min'=>'0')) !!} 
                                </div>
                            </div>
                        </div>    
                    </div>
                    <div class="well transparent">
                        <div class="row">
                            <div class="col-lg-12 col-md-8 col-sm-9 col-xs-12">
                                <h2 class="bold">Detalles</h2>
                            </div>
                            <div class="form-group col-lg-12 col-md-8 col-sm-9 col-xs-12 top15">
                                {!! Form::label('commentslabel', 'Comentarios', array('class' => 'form-label')) !!}
                                <div class="controls">
                                    {!! Form::textarea('comments', '', array('id' => 'comments', 'maxlength' => '200' , 'rows' => 6, 'cols' => 35, 'class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix top15"></div>
                    <!-- BOTONES -->
                    <div class="row top15">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <button type="submit" id="submit" class="btn btn-primary right15">Procesar</button>
                                 <a type="button" class="btn" href="{{ URL::to('purchase_orders') }}">Cancelar</a>
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
        $('#lista_productos').on('DOMSubtreeModified',function(){
            var lis = $('#lista_productos').find("li");
            var subtotal = 0.00;
            lis.each(function(index){
                subtotal += parseFloat($(this).attr('precio').replace(',',''))*$(this).attr('cantidad');
            });
            $('#subtotal').html("Subtotal: $"+subtotal.toLocaleString());
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

        $('#purchase_order_form').submit(function(e){
            e.preventDefault();
            var idProveedor = $('#proveedor').attr('name');
            var tax = $('#tax').val();
            var shipto = $('#shipto').val();
            var comments = $('#comments').val();

            if(idProveedor === '' || tax === '' || shipto === ''){
                swal({
                    title: "Debe llenar todos los campos",
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
                    title: "Seleccione al menos un producto",
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
                    url  : '{{ route("purchase_orders.store") }}',
                    data : {data: jsondata, idProveedor: idProveedor, comments: comments, tax: tax, shipto: shipto},
                    beforeSend: function() { 
                      // $("#product_id").html('<option> Loading ...</option>');
                      $("#submit").prop('disabled', true);
                    },
                    success: function( data, textStatus, jQxhr ){
                        if(data === "ok"){
                            swal({
                                title:"Orden de Compra creada!",
                                text: "Al cerrar será redireccionado a las ordes de compra",
                                icon: "success",
                                buttons: false,
                                timer: 1000
                                }).then(() => {
                                  setTimeout(function(){
                                    window.location.href = "{{URL::to('purchase_orders')}}";
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
</script> 
<!-- JS NECESARIO PARA ORDENES - END --> 
@endsection
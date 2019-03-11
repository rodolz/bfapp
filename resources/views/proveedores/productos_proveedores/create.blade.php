@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Productos de los Proveedores</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Nuevo Producto</h2>
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

	           {!! Form::open(array('id' => 'submit_form','method'=>'POST','class' => 'form-inline')) !!}

                    <div class="well transparent">
                        <div class="row">
                            <div class="form-group col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                <h2 class="bold">Proveedor</h2>
                                Seleccione al Proveedor
                                <div class="controls">
                                    {!! Form::select('idProveedor', $proveedores, null, ['id' => 'proveedor', 'placeholder' => 'Seleccione...', 'class' => 'form-control top15']) !!}
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
                                            <div class="input-group col-lg-2 col-md-6 col-sm-9 col-xs-12 right15 top15">
                                                <span class="input-group-addon">Codigo:</span>
                                                <input class="form-control" type="text" id="codigo" name="codigo">
                                            </div>
                                            <div class="input-group col-lg-3 col-md-6 col-sm-9 col-xs-12 right15 top15">
                                                <span class="input-group-addon">Descripcion:</span>
                                                <input class="form-control" type="text" id="descripcion" name="descripcion">
                                            </div>
                                            <div class="input-group col-lg-2 col-md-6 col-sm-9 col-xs-12 right15 top15">
                                                {{-- <span class="input-group-addon"><i class='fa fa-usd'></i></span>
                                                <input class="form-control" lang="en-150" type="number" step="0.000001" id="precio" name="precio" min=1.00 value=0.00> --}}
                                                <span class="input-group-addon"><i class='fa fa-usd'></i></span>
                                                <input type="text" id="precio" name="precio" class="autoNumeric form-control" placeholder="0.00">
                                            </div>
                                            <div class="input-group col-lg-3 col-md-6 col-sm-9 col-xs-12 right15 top15">
                                            <span class="input-group-addon">Asociado a:</span>
                                            {!! Form::select('idProducto_empresa', $productos, null, ['id' => 'idProducto_empresa', 'placeholder' => 'Seleccione...', 'class' => 'form-control']) !!}
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
                                        <h4 class="list-group-item-heading bold text-center">Productos por agregar</h4>
                                    </div>
                                </div>
                                <h4 class="list-group-item-heading bold text-center" id="loading" hidden="hidden">Cargando ...</h4>
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

    // INICIO DE LA ACCION DE AGREGAR PRODUCTOS A LA LISTA
    $('#add_producto').click(function(){
        var attr = 'codigo';
        var codigo = $('#codigo').val();
        var descripcion = $('#descripcion').val();
        var producto_relacionado = $('#idProducto_empresa option:selected').text();
        var producto_relacionado_id = $('#idProducto_empresa option:selected').val();
        var precio = $('#precio').val();
        //Validacion del producto seleccionado
        if (codigo === '' || descripcion === '' || precio === '' ||  producto_relacionado === '') {
            swal({
                title: "Productos",
                text: "Debe llenar todos los campos",
                icon: 'error'
            });
            return false;
        }
        $("#add_producto").prop('disabled', false);
        $('#lista_productos').show();
        var item = $('#lista_productos').find("li[codigo=\""+codigo+"\"]");
        if(item.html() !== undefined){
                item.remove();
        }
        var li = "<li codigo=\""+codigo+"\" descripcion=\""+descripcion+"\"precio="+precio+" producto_relacionado="+producto_relacionado_id+" class='list-group-item active'>";
        li += "<span class='badge'><a codigo="+codigo+"><i class='fa fa-times'></i></a></span>";
        li += "<span class='badge'><i class='fa fa-usd'></i>"+precio+"</span>";
        li += codigo+" - "+descripcion+" - "+producto_relacionado+"</li>";
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
        var id = $(this).attr('codigo');
        $("li[codigo=\""+id+"\"]").remove();
    });
    // FIN BORRAR PRODUCTOS DE LA LISTA

    // INICIO - PROCESAR EL FORMULARIO
    $('#submit_form').submit(function(e){
        e.preventDefault();
        var idProveedor = $('select[id=proveedor]').val();

        if(idProveedor === ''){
            swal({
                title: "Seleccione un Proveedor",
                icon: 'error'
            });
            return false;
        }
        var productos = [];
        var obj = {};
        var lista = $('#lista_productos');
        $(lista).find('li').each(function(index, value){
            codigo = $(this).attr('codigo');
            descripcion = $(this).attr('descripcion');
            precio = $(this).attr('precio');
            producto_relacionado = $(this).attr('producto_relacionado');
            obj = {
                codigo: codigo,
                descripcion: descripcion,
                precio: precio,
                producto_relacionado: producto_relacionado
            };
            productos.push(obj);
        });
        if(productos.length === 0){
            swal({
                title: "Escoja al menos un producto",
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
                url  : '/productos_proveedores_store',
                data : {data: jsondata, idProveedor: idProveedor},
                beforeSend: function() { 
                    // $("#product_id").html('<option> Loading ...</option>');
                    $("#submit").prop('disabled', true);
                },
                success: function( data ){
                        if(data === "ok"){
                            swal({
                                title:"Productos creados",
                                text: "Los productos fueron creados y asociados correctamente",
                                icon: "success",
                                buttons: false,
                                timer: 2000
                                }).then(() => {
                                    swal({
                                        title:"Agregar productos para otro proveedor?",
                                        icon: "info",
                                        buttons: true,
                                    }).then((value) => {
                                        if(value){
                                            setTimeout(function(){
                                                window.location.href = "{{Request::url()}}";
                                            });
                                        } else{
                                            setTimeout(function(){
                                                window.location.href = "{{ URL::to('proveedores') }}";
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
@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Notas de Entrega</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Nueva Nota de Entrega</h2>
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
                                            <div class="input-group col-lg-3 col-md-6 col-sm-9 col-xs-12 right15 top15">
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
    <script src="{{ asset('plugins/messenger/js/messenger.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/messenger/js/messenger-theme-future.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/messenger/js/messenger-theme-flat.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/messenger.js') }}" type="text/javascript"></script>
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
            var precio = $('#precio').val();
            //Validacion del producto seleccionado
            if (codigo === '' || descripcion === '' || precio === '') {
                showErrorMessage('Productos - Debe llenar todos los campos!');
                return false;
            }
            $("#add_producto").prop('disabled', false);
            $('#lista_productos').show();
            var item = $('#lista_productos').find('li[codigo='+codigo+']');
            if(item.html() !== undefined){
                    item.remove();
            }
            var li = "<li codigo="+codigo+" descripcion="+descripcion+" precio="+precio+" class='list-group-item active'>";
            li += "<span class='badge'><a codigo="+codigo+"><i class='fa fa-times'></i></a></span>";
            li += "<span class='badge'><i class='fa fa-usd'></i>"+precio+"</span>";
            li += codigo+" - "+descripcion+"</li>";
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
            $('li[codigo='+id+']').remove();
        });
        // FIN BORRAR PRODUCTOS DE LA LISTA

        // INICIO - PROCESAR EL FORMULARIO
        $('#submit_form').submit(function(e){
            e.preventDefault();
            var idProveedor = $('select[id=proveedor]').val();

            if(idProveedor === ''){
                showErrorMessage('Seleccione a un Proveedor!');
                return false;
            }
            var productos = [];
            var obj = {};
            var lista = $('#lista_productos');
            $(lista).find('li').each(function(index, value){
                codigo = $(this).attr('codigo');
                descripcion = $(this).attr('descripcion');
                precio = $(this).attr('precio');
                obj = {
                    codigo: codigo,
                    descripcion: descripcion,
                    precio: precio
                };
                productos.push(obj);
            });
            if(productos.length === 0){
                showErrorMessage('Debe agregar al menos un producto!');
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
                    success: function( data, textStatus, jQxhr ){
                        if(data === "ok"){
                            swal({
                                title:"Productos Agregados!",
                                text: "Al cerrar será redireccionado a la pantalla anterior",
                                type: "success",
                                confirmButtonText: "Cerrar",
                                },
                                function(){
                                  setTimeout(function(){
                                    window.location.href = "{{URL::to('proveedores')}}";
                                  }, 3000);
                                });
                        }
                        else{
                            var errors = data;
                            console.log(errors);
                            console.log(data);
                            swal({
                                type: 'error',
                                title: "Hubo un error, contacte al ADMIN con el siguiente error:",
                                text: errors,
                                html: false
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
                            html: false
                        });
                        $("#submit").prop('disabled', false);
                    }
                });     
        });

        // FIN - PROCESAR EL FORMULARIO
    </script> 
    <!-- JS NECESARIO PARA ORDENES - END --> 
@endsection
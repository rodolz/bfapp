@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Controles</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Nuevo Controles</h2>
    @endsection

<!-- SI HAY ORDENES POR FACTURAR -->
@if(!empty($ordenes_fmt))
    @section('content')
        <section class="box primary">  
            <header class="panel_header">
                @yield('panel-title')
            </header>
            <div class="content-body">    
                <div class="row">
   		           {!! Form::open(array('id' => 'factura_form','method'=>'POST','class' => 'form-inline')) !!}
                    <div class="well transparent">
                        <div class="row">
                            <div class="form-group col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                <h2 class="bold">Nota de entrega</h2>
                                <p>Seleccione la Nota de Entrega</p>
                                <div class="controls">
                                        {!! Form::select('idOrden', $ordenes_fmt, null, ['id' => 'orden', 'placeholder' => 'Seleccione...', 'class' => 'form-control top15']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="well transparent">
                        <div class="row">
                            <div class="form-group col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                <h2 class="bold">Condiciones</h2>
                                <p>Indique el ITBMS y la Condición</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-sm-9 col-xs-12">
                                <div class="input-group right15 top15">
                                    <span class="input-group-addon">ITBMS(%):</span>
                                    <input class="form-control " type="number" id="itbms" name="itbms" min=0 value=7>
                                </div>
                                <div class="input-group right15 top15">
                                    <span class="input-group-addon">Condición:</span>
                                    <input class="form-control" type="text" id="condicion" name="condicion">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix top15"></div>
                    <!-- BOTONES -->
                    <div class="row top15">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <button type="submit" class="btn btn-primary right15">Procesar</button>
                             <a type="button" class="btn" href="{{ URL::to('facturas') }}">Cancelar</a>
                        </div>
                    </div>
                </div>
                    {!! Form::close() !!}
            </div>
        </section>
    @endsection

<!-- SI NO HAY ORDENES POR FACTURAR -->
@else
    @section('content')
        <section class="box warning">    
            <header class="panel_header">
                <h2 class="title">Advertencia</h2>
            </header>
            <div class="content-body">
                <div class="row text-center">
                    <h3> No tiene notas de entregas por facturar</h3>
                    Todas las notas de entregas en el sistema estan facturadas.
                </div>    
            </div>
        </section>
    @endsection
@endif

@section('add-plugins')
    <!-- JS NECESARIO PARA ORDENES - START --> 
    <script type="text/javascript">

        // INICIO - PROCESAR EL FORMULARIO

        $('#factura_form').submit(function(e){
        e.preventDefault();
        var idOrden = $('select[id=orden]').val();

        if(idOrden === ''){
            swal({
                title: "Seleccione una Nota de Entrega",
                icon: 'error'
            });
            return false;
        }
        var itbms = $('#itbms').val();
        if (itbms === '') {
            swal({
                title: "Indique un ITBMS(%) válido",
                icon: 'error'
            });
            return false;
        }
        var condicion = $('#condicion').val();
        if (condicion === '') {
            swal({
                title: "Indique una Condición",
                icon: 'error'
            });
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
                            text: "Control creado correctamente",
                            icon: "success",
                            buttons: false,
                            timer: 1500
                            }).then(() => {
                                    swal({
                                        title:"Crear nuevo control?",
                                        text: "Si cancela se redireccionará a 'Controles'",
                                        icon: "info",
                                        buttons: true,
                                    }).then((value) => {
                                        if(value){
                                            setTimeout(function(){
                                                window.location.href = "{{Request::url()}}";
                                            });
                                        } else{
                                            setTimeout(function(){
                                                window.location.href = "{{ URL::to('facturas') }}";
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
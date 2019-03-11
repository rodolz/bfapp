@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Controles</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Lista de Controles</h2>
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
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                   {{--  <div class="pull-left">
                        <h2 class="bold">Control</h2>
                    </div> --}}
                    <div class="pull-right">
                        <a class="btn btn-info" href="{{ route('facturas.create') }}"> Nuevo Control</a>
                    </div>
                </div>
            </div>

            <div class="row top15">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th># Control</th>
                                <th># Nota de Entrega</th>
                                <th>Fecha Creación</th>
                                <th>Cliente</th>
                                <th>Condición</th>
                                <th>Monto Total</th>
                                <th># Fiscal</th>
                                <th>Estado</th>
                                <th width="150px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($facturas) > 0)
                            @foreach ($facturas as $factura)
                                <tr>
                                    <th scope="row">{{ $factura->num_factura }}</th>
                                    <td>{{ $factura->orden->num_orden }} </td>
                                    <td>{{ $factura->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $factura->cliente->empresa }}</td>
                                    <td>{{ $factura->condicion }}</td>
                                    <td>${{ number_format($factura->monto_factura,2) }}</td>
                                    <td width="125px"><input  class="form-control bg-muted" type="number" name="{{ $factura->id }}" id="num_fiscal" value="{{ $factura->num_fiscal}}"></td>
                                    @if($factura->idFacturaEstado == 1)
                                        <td><label class="bg-warning">{{ $factura->estado->factura_estado }}</label></td>
                                    @elseif($factura->idFacturaEstado == 2)
                                        <td><label class="bg-success">{{ $factura->estado->factura_estado }}</label></td>
                                    @else
                                        <td><label class="bg-purple">{{ $factura->estado->factura_estado }}</label></td>
                                    @endif
                                    <td>
                                        <div class="acciones-btn">
                                            <a class="btn btn-info" href="{{ URL::to('facturas/factura_pdf/'.$factura->id) }}"><i class="fa fa-file-pdf-o"></i></a>
                                            {!! Form::open(['method' => 'DELETE','onclick' => 'deletePrompt()','name' => 'deleteForm','route' => ['facturas.destroy', $factura->id],'style'=>'display:inline']) !!}
                                            <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                            {!! Form::close() !!}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">
                                    <h2 class="bold text-warning text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:30px"></i> No se han agregado facturas</h2>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <!-- PAGINACION -->
                <center> {!! $facturas->render() !!} </center>
            </div>
        </div>
    </section>

@endsection

@section('add-plugins')
    <script type="text/javascript">

    $('input').focusin(function() {
        window.currentValue = $(this).val();
    });
    
    $('input').focusout(function() {
        var num_fiscal = $(this).val();
        if(num_fiscal == window.currentValue){
            return false;
        }
        var idFactura = $(this).attr('name');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },  
            type : 'POST',
            url  : '/facturas/update/num_fiscal',
            data : { 
                'num_fiscal' : num_fiscal,
                'idFactura' : idFactura
            },
            success: function( data, textStatus, jQxhr ){
                if(data === "ok"){
                    swal({
                        title:"Número Fiscal Actualizado",
                        text: "El número fiscal se actualizo correctamente!",
                        icon: "success",
                        timer: 1000,
                        buttons: false,
                        });
                }
                else{
                    swal({
                        title: "Hubo un error, contacte al ADMIN con el siguiente error:",
                        text: data,
                        icon: 'error'
                    });
                }
            },
            error: function( jqXHR ){
                swal({
                    title: "Hubo un error, contacte al ADMIN con el siguiente error:",
                    text: jqXHR.status+" - "+jqXHR.statusText,
                    icon: 'error'
                });
            }
        });
    });
    function deletePrompt() {
        event.preventDefault(); // prevent form submit
        var form = document.forms["deleteForm"]; // storing the form
        swal({
            title: "Esta seguro/a de eliminar?",
            text: "Si procede no se podrá recuperar esta información",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((result) => {
            if (result) {
                form.submit();
            }
        });
    }
    </script>
@endsection
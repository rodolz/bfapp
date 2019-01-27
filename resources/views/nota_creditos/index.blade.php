@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Notas de Credito</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Lista de Notas de Credito</h2>
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
                        <h2 class="bold">Notas de Credito</h2>
                    </div> --}}
                    <div class="pull-right">
                        <a class="btn btn-info" href="{{ route('nota_creditos.create') }}"> Nueva Nota de Credito</a>
                    </div>
                </div>
            </div>
            <div class="row top15">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th># Nota de Credito</th>
                                <th># Fiscal</th>
                                <th>Fecha Creación</th>
                                <th># Control</th>
                                <th>Cliente</th>
                                <th>Monto</th>
                                <th width="150px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($nota_creditos) > 0)
                            @foreach ($nota_creditos as $nota_credito)
                                <tr>
                                    <th scope="row">{{ $nota_credito->num_nota_credito }}</th>
                                    <td width="125px">
                                        <input  class="form-control bg-muted" type="number" name="{{ $nota_credito->id }}" id="num_fiscal" value="{{ $nota_credito->num_fiscal}}">
                                    </td>
                                    <td>{{ $nota_credito->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $nota_credito->pago->facturas->first()->num_factura }}</td>
                                    <td>{{ $nota_credito->pago->cliente->empresa }}</td>
                                    <td>${{ number_format($nota_credito->pago->monto_pago,2) }}</td>
                                    <td>
                                        <div class="acciones-btn">
                                            <a class="btn btn-info" href="{{ URL::to('nota_creditos-pdf/'.$nota_credito->id) }}"><i class="fa fa-file-pdf-o"></i></a>
                                            {!! Form::open(['method' => 'DELETE','route' => ['nota_creditos.destroy', $nota_credito->id],'style'=>'display:inline']) !!}
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
                                    <h2 class="bold text-warning text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:30px"></i> No se han agregado Notas de Credito</h2>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <!-- PAGINACION -->
                <center> {!! $nota_creditos->render() !!} </center>
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
        var idNotaCredito = $(this).attr('name');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },  
            type : 'POST',
            url  : '/nota_creditos/update/num_fiscal',
            data : { 
                'num_fiscal' : num_fiscal,
                'idNotaCredito' : idNotaCredito
            },
            success: function( data, textStatus, jQxhr ){
                if(data === "ok"){
                    swal({
                        title:"Numero Fiscal Actualizado!",
                        text: "El numero fiscal se actualizo correctamente!",
                        type: "success",
                        confirmButtonText: "Cerrar",
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
                    customClass: 'sweet-alert-lg',
                    html: true
                });
            }
        });
    });
    </script>
@endsection
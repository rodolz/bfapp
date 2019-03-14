@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Notas de Credito</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Lista de Notas de Credito</h2>
    @endsection

@section('content')
    <section class="box primary">    
        <header class="panel_header">
            @yield('panel-title')
        </header>
        <div class="content-body">    
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                            <a class="btn btn-info" href="{{ URL::to('nota_creditos/nota_credito_pdf/'.$nota_credito->id) }}"><i class="fas fa-file-pdf"></i></a>
                                            {!! Form::open(['method' => 'DELETE','onclick' => 'deletePrompt()','name' => 'deleteForm','route' => ['nota_creditos.destroy', $nota_credito->id],'style'=>'display:inline']) !!}
                                            <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash-alt" aria-hidden="true"></i>
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